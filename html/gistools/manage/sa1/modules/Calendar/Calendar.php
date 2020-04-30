<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version
 * 1.1.3 ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an "AS IS" basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied.  See the License
 * for the specific language governing rights and limitations under the
 * License.
 *
 * All copies of the Covered Code must include on each user interface screen:
 *    (i) the "Powered by SugarCRM" logo and
 *    (ii) the SugarCRM copyright notice
 * in the same form as they appear in the distribution.  See full license for
 * requirements.
 *
 * The Original Code is: SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) 2004 SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Id: Calendar.php,v 1.51 2005/04/26 19:05:20 robert Exp $
 ********************************************************************************/
require_once('modules/Meetings/Meeting.php');
require_once('modules/Calls/Call.php');
require_once('modules/Calendar/DateTime.php');

function sort_func_by_act_date($act0,$act1)
{
	if ($act0->start_time->ts == $act1->start_time->ts)
	{
		return 0;
	}

	return ($act0->start_time->ts < $act1->start_time->ts) ? -1 : 1;
}

class Calendar
{
	var $view = 'month';
	var $date_time;
	var $slices_arr = array();
        // for monthly calendar view, if you want to see all the
        // days in the grid, otherwise you only see that months
	var $show_only_current_slice = false;
	var $show_activities = true;
	var $show_tasks = true;
	var $activity_focus;
        var $show_week_on_month_view = true;
	var $use_24 = 1;
	var $toggle_appt = true;
	var $slice_hash = array();
//	var $shared_users_arr = array();

	function Calendar($view,$time_arr=array())
	{
		global $current_user;
		global $sugar_config;
		if ( $current_user->getPreference('time'))
		{
			$time = $current_user->getPreference('time');
		}
		else
		{
			$time = $sugar_config['default_time_format'];
		}
        	if( substr_count($time, 'H') > 0)
		{
			$this->use_24 = 0;
		}

		$this->view = $view;

		if ( isset($time_arr['activity_focus']))
		{
			$this->activity_focus =  new CalendarActivity($time_arr['activity_focus']);
			$this->date_time =  $this->activity_focus->start_time;
		}
		else
		{
			$this->date_time = new DateTime($time_arr,true);
		}

		if (!( $view == 'day' || $view == 'month' || $view == 'year' || $view == 'week') )
		{
			sugar_die ("view needs to be one of: day, week, month, or year");
		}

		if ( empty($this->date_time->year))
		{
			sugar_die ("all views: year was not set");
		}
		else if ( $this->view == 'month' &&  empty($this->date_time->month))
		{
			sugar_die ("month view: month was not set");
		}
		else if ( $this->view == 'week' && empty($this->date_time->week))
		{
			sugar_die ("week view: week was not set");
		}
		else if ( $this->view == 'day' &&  empty($this->date_time->day) && empty($this->date_time->month))
		{
			sugar_die ("day view: day and month was not set");
		}

		$this->create_slices();

	}
	function add_shared_users(&$shared_users_arr)
	{
		$this->shared_users_arr = $shared_users_arr;
	}

	function get_view_name($view)
	{
		if ($view == 'month')
		{
			return "MONTH";
		}
		else if ($view == 'week')
		{
			return "WEEK";
		}
		else if ($view == 'day')
		{
			return "DAY";
		}
		else if ($view == 'year')
		{
			return "YEAR";
		}
		else
		{
			sugar_die ("get_view_name: view ".$this->view." not supported");
		}
	}

	function get_slices_arr()
	{
		return $this->slices_arr;
	}


	function create_slices()
	{

		global $current_user;


		if ( $this->view == 'month')
		{
			$days_in_month = $this->date_time->days_in_month;


			$first_day_of_month = $this->date_time->get_day_by_index_this_month(0);
			$num_of_prev_days = $first_day_of_month->day_of_week;
			// do 42 slices (6x7 grid)

			for($i=0;$i < 42;$i++)
			{
				$slice = new Slice('day',$this->date_time->get_day_by_index_this_month($i-$num_of_prev_days));
				$this->slice_hash[$slice->start_time->get_mysql_date()] = $slice;
				array_push($this->slices_arr,  $slice->start_time->get_mysql_date());
			}

		}
		else if ( $this->view == 'week')
		{
			$days_in_week = 7;

			for($i=0;$i<$days_in_week;$i++)
			{
				$slice = new Slice('day',$this->date_time->get_day_by_index_this_week($i));
				$this->slice_hash[$slice->start_time->get_mysql_date()] = $slice;
				array_push($this->slices_arr,  $slice->start_time->get_mysql_date());
			}
		}
		else if ( $this->view == 'day')
		{
			$hours_in_day = 24;

			for($i=0;$i<$hours_in_day;$i++)
			{
				$slice = new Slice('hour',$this->date_time->get_datetime_by_index_today($i));
				$this->slice_hash[$slice->start_time->get_mysql_date().":".$slice->start_time->hour ] = $slice;
				array_push($this->slices_arr,  $slice->start_time->get_mysql_date().":".$slice->start_time->hour);
			}
		}
		else if ( $this->view == 'year')
		{

			for($i=0;$i<12;$i++)
			{
				$slice = new Slice('month',$this->date_time->get_day_by_index_this_year($i));
				$this->slice_hash[$slice->start_time->get_mysql_date()] = $slice;
				array_push($this->slices_arr,  $slice->start_time->get_mysql_date());
			}
		}
		else
		{
			sugar_die("not a valid view:".$this->view);
		}

	}

	function add_activities($user,$type='sugar')
	{
		if ( $this->view == 'week')
		{
			$end_date_time = $this->date_time->get_first_day_of_next_week();
		}
		else
		{
			$end_date_time = $this->date_time;
		}

		$acts_arr = array();

    if ( $type == 'vfb')
    {
		$acts_arr =
			CalendarActivity::get_freebusy_activities($user,
           $this->date_time, 
           $end_date_time
			);
    }
    else
    {
		$acts_arr =
			CalendarActivity::get_activities($user->id,
				$this->show_tasks,
				$this->date_time,
				$end_date_time
			);
    }

    // loop thru each activity for this user
		for ($i = 0;$i < count($acts_arr);$i++)
		{
			$act = &$acts_arr[$i];
      // get "hashed" time slots for the current activity we are looping through
			$hash_list = DateTime::getHashList($this->view,$act->start_time,$act->end_time);

			for($j=0;$j < count($hash_list); $j++)
			{
				if ( !isset($this->slice_hash[$hash_list[$j]]) || !isset($this->slice_hash[$hash_list[$j]]->acts_arr[$user->id]))
				{
					$this->slice_hash[$hash_list[$j]]->acts_arr[$user->id] = array();
				}
				array_push($this->slice_hash[$hash_list[$j]]->acts_arr[$user->id],$act);
			}
		}

	}

	function occurs_within_slice(&$slice,&$act)
	{
		// if activity starts within this slice
		// OR activity ends within this slice
		// OR activity starts before and ends after this slice
		if ( ( $act->start_time->ts >= $slice->start_time->ts &&
			 $act->start_time->ts <= $slice->end_time->ts )
			||
			( $act->end_time->ts >= $slice->start_time->ts &&
			$act->end_time->ts <= $slice->end_time->ts )
			||
			( $act->start_time->ts <= $slice->start_time->ts &&
			$act->end_time->ts >= $slice->end_time->ts )
		)
		{
			//print "act_start:{$act->start_time->ts}<BR>";
			//print "act_end:{$act->end_time->ts}<BR>";
			//print "slice_start:{$slice->start_time->ts}<BR>";
			//print "slice_end:{$slice->end_time->ts}<BR>";
			return true;
		}

		return false;

	}

	function get_previous_date_str()
	{
		if ($this->view == 'month')
		{
			$day = $this->date_time->get_first_day_of_last_month();
		}
		else if ($this->view == 'week')
		{
			$day = $this->date_time->get_first_day_of_last_week();
		}
		else if ($this->view == 'day')
		{
			$day = $this->date_time->get_yesterday();
		}
		else if ($this->view == 'year')
		{
			$day = $this->date_time->get_first_day_of_last_year();
		}
		else
		{
			return "get_previous_date_str: notdefined for this view";
		}
		return $day->get_date_str();
	}

	function get_next_date_str()
	{
		if ($this->view == 'month')
		{
			$day = $this->date_time->get_first_day_of_next_month();
		}
		else
		if ($this->view == 'week')
		{
			$day = $this->date_time->get_first_day_of_next_week();
		}
		else
		if ($this->view == 'day')
		{
			$day = $this->date_time->get_tomorrow();
		}
		else
		if ($this->view == 'year')
		{
			$day = $this->date_time->get_first_day_of_next_year();
		}
		else
		{
			sugar_die("get_next_date_str: not defined for view");
		}
		return $day->get_date_str();
	}

	function get_start_slice_idx()
	{

		if ( $this->view == 'day' )
		{
			$start_at = 8;

			for($i=0;$i < 8; $i++)
			{
				if (count($this->slice_hash[$this->slices_arr[$i]]->acts_arr) > 0)
				{
					$start_at = $i;
					break;
				}
			}
			return $start_at;
		}
		else
		{
			return 0;
		}
	}
	function get_end_slice_idx()
	{
		if ( $this->view == 'month')
		{
			return $this->date_time->days_in_month - 1;
		}
		else if ( $this->view == 'week')
		{
			return 6;
		}
		else if ( $this->view == 'day' )
		{
			$end_at = 18;

			for($i=$end_at;$i < 23; $i++)
			{
				if (count($this->slice_hash[$this->slices_arr[$i+1]]->acts_arr) > 0)
				{
					$end_at = $i + 1;
				}
			}


			return $end_at;

		}
		else
		{
			return 1;
		}
	}


}

class Slice
{
	var $view = 'day';
	var $start_time;
	var $end_time;
	var $acts_arr = array();

	function Slice($view,$time)
	{
		$this->view = $view;
		$this->start_time = $time;

		if ( $view == 'day')
		{
			$this->end_time = $this->start_time->get_day_end_time();
		}
		if ( $view == 'hour')
		{
			$this->end_time = $this->start_time->get_hour_end_time();
		}

	}
	function get_view()
	{
		return $this->view;
	}

}

// global to switch on the offet

$DO_USER_TIME_OFFSET = false;

class CalendarActivity
{
	var $sugar_bean;
	var $start_time;
	var $end_time;

	function CalendarActivity($args)
	{
    // if we've passed in an array, then this is a free/busy slot
    // and does not have a sugarbean associated to it
		global $DO_USER_TIME_OFFSET;

    if ( is_array ( $args ))
    {
       $this->start_time = $args[0];     
       $this->end_time = $args[1];     
       $this->sugar_bean = null;
       return;
    }
 
    // else do regular constructor..

    $sugar_bean = $args;

		require_once('include/TimeDate.php');
		$timedate = new TimeDate();
		$this->sugar_bean = $sugar_bean;


		if ($sugar_bean->object_name == 'Task')
		{

			$newdate = $timedate->merge_date_time($this->sugar_bean->date_due, $this->sugar_bean->time_due);
			$tempdate  = $timedate->to_db_date($newdate,$DO_USER_TIME_OFFSET);

			if($newdate != $tempdate){
				$this->sugar_bean->date_due = $tempdate;
			}
			$temptime = $timedate->to_db_time($newdate, $DO_USER_TIME_OFFSET);
			if($newdate != $temptime){
				$this->sugar_bean->time_due = $temptime;
			}
			$this->start_time = DateTime::get_time_start(
				$this->sugar_bean->date_due,
				$this->sugar_bean->time_due
			);
			if ( empty($this->start_time))
			{
				return null;
			}

			$this->end_time = $this->start_time;
		}
		else
		{

			$newdate = $timedate->merge_date_time($this->sugar_bean->date_start, $this->sugar_bean->time_start);
			$tempdate  = $timedate->to_db_date($newdate,$DO_USER_TIME_OFFSET);

			if($newdate != $tempdate){
				$this->sugar_bean->date_start = $tempdate;
			}
			$temptime = $timedate->to_db_time($newdate,$DO_USER_TIME_OFFSET);
			if($newdate != $temptime){
				$this->sugar_bean->time_start = $temptime;
			}
			$this->start_time = DateTime::get_time_start(
			$this->sugar_bean->date_start,
			$this->sugar_bean->time_start
			);

		$this->end_time = DateTime::get_time_end(
			$this->start_time,
         		$this->sugar_bean->duration_hours,
        		$this->sugar_bean->duration_minutes
			);
		}

	}

        function get_occurs_within_where_clause($table_name,$start_ts,$end_ts)
        {
                                                                                                         
                $start_mysql_date = $start_ts->get_mysql_date();
                $start_mysql_date =  substr($start_mysql_date ,0,7);
                $end_mysql_date = $end_ts->get_mysql_date();
                $end_mysql_date =  substr($end_mysql_date ,0,7);
                                                                                                         
                $where =  "( LEFT({$table_name}.date_start,7) = '{$start_mysql_date}' OR ";
                $where .=  " LEFT({$table_name}.date_end,7) = '{$end_mysql_date}' )";
                return $where;
        }


  function get_freebusy_activities(&$user_focus,&$start_date_time,&$end_date_time)
  {
 
      require_once('modules/vCals/vCal.php');
		  $act_list = array();
      $vcal_focus = new vCal();
      $vcal_str = $vcal_focus->get_vcal_freebusy($user_focus);

      $lines = explode("\n",$vcal_str);

      foreach ($lines as $line)
      {
        $dates_arr = array();
        if ( preg_match('/^FREEBUSY.*?:([^\/]+)\/([^\/]+)/i',$line,$matches))
        {
          $dates_arr[] = & DateTime::parse_utc_date_time($matches[1]);
          $dates_arr[] = & DateTime::parse_utc_date_time($matches[2]);
          $act_list[] = & new CalendarActivity($dates_arr); 
        }
      }
		  usort($act_list,'sort_func_by_act_date');
      return $act_list;
  }


 	function get_activities($user_id,$show_tasks,&$view_start_time,&$view_end_time)
	{
		global $current_user;
		$act_list = array();
		$seen_ids = array();

		// get all upcoming meetings, tasks due, and calls for a user

		$meeting = new Meeting();







		$where = CalendarActivity::get_occurs_within_where_clause($meeting->table_name,$view_start_time,$view_end_time);

		$focus_meetings_list = array();

		$focus_meetings_list += $meeting->build_related_list_by_user_id($user_id,$where);

		foreach($focus_meetings_list as $meeting)
		{
			if (isset($seen_ids[$meeting->id]))
			{
				continue;
			}
			$seen_ids[$meeting->id] = 1;
			$act = new CalendarActivity($meeting);

			if ( ! empty($act))
			{
				$act_list[] = $act;
			}
		}


		$call = new Call();








		$where = CalendarActivity::get_occurs_within_where_clause($call->table_name,$view_start_time,$view_end_time);

		$focus_calls_list = array();

		$focus_calls_list += $call->build_related_list_by_user_id($user_id,$where);

		foreach($focus_calls_list as $call)
		{
			if (isset($seen_ids[$call->id]))
			{
				continue;
			}
			$seen_ids[$call->id] = 1;

			$act = new CalendarActivity($call);
			if ( ! empty($act))
			{
				$act_list[] = $act;
			}
		}


		if ( $show_tasks )
		{

			$task = new Task();
			$where = "tasks.assigned_user_id='$user_id'";

			$focus_tasks_list = $task->get_full_list("", $where,true);

			if(! isset($focus_tasks_list))
			{
				$focus_tasks_list = array();
			}

			foreach($focus_tasks_list as $task)
			{
				$act = new CalendarActivity($task);
				if ( ! empty($act))
				{
					$act_list[] = $act;
				}
			}
		}

		usort($act_list,'sort_func_by_act_date');
		return $act_list;

	}

}

?>
