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
 * $Id: vCal.php,v 1.6 2005/04/26 19:05:20 robert Exp $
 * Description:
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');
require_once('include/utils.php');
require_once('modules/Calendar/Calendar.php');

class vCal extends SugarBean {
	// Stored fields
	var $id;
	var $date_modified;
	var $user_id;
	var $content;
	var $deleted;
	var $type;
	var $source;
	var $module_dir = "vCals";
	var $table_name = "vcals";

	var $object_name = "vcal";

	var $new_schema = true;

	var $column_fields = Array(
		"id"
		,"user_id"
		,"date_modified"
		,"type"
		,"content"
		,"source"
		,"deleted"
		);

	var $field_defs = array(
	);

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array();

	// This is the list of fields that are in the lists.
	var $list_fields = Array();
	// This is the list of fields that are required
	var $required_fields =  array();

	function vCal() 
	{
		
		parent::SugarBean();
		$this->disable_row_level_security = true;
	}

	function get_summary_text()
	{
		return "";
	}

	function create_list_query(&$order_by, &$where)
	{
	}

	function fill_in_additional_list_fields()
	{
	}

	function fill_in_additional_detail_fields() 
	{
	}

	function get_list_view_data()
	{
	}

        // combines all freebusy vcals and returns just the FREEBUSY lines as a string
	function get_freebusy_lines_cache(&$user_bean)
	{
		$str = '';
		// First, get the list of IDs.
		$query = "SELECT id from vcals where user_id='{$user_bean->id}' AND type='vfb' AND deleted=0";
		$vcal_arr = $this->build_related_list($query, new vCal());

		foreach ($vcal_arr as $focus)
		{
			if (empty($focus->content))
			{
				return '';
			}

			$lines = explode("\n",$focus->content);

			foreach ($lines as $line)
			{
				if ( preg_match('/^FREEBUSY[;:]/i',$line))
				{
					$str .= "$line\n";
				}
			}
		}

		return $str;
	}

	// query and create the FREEBUSY lines for SugarCRM Meetings and Calls and 
        // return the string	
	function create_sugar_freebusy(&$user_bean,&$start_date_time,&$end_date_time)
	{
		$str = '';
		global $DO_USER_TIME_OFFSET;

		$DO_USER_TIME_OFFSET = true;
		// get activities.. queries Meetings and Calls
		$acts_arr =
		CalendarActivity::get_activities($user_bean->id,
			false,
			$start_date_time,
			$end_date_time
			);

		// loop thru each activity, get start/end time in UTC, and return FREEBUSY strings
		for ($i = 0;$i < count($acts_arr);$i++)
		{
			$act = &$acts_arr[$i];
			$date_arr = array('ts'=>$act->start_time->ts - $act->start_time->tz_offset);
			$start_time = new DateTime($date_arr,true);

			$date_arr = array('ts'=>$act->end_time->ts - $act->end_time->tz_offset);
			$end_time = new DateTime($date_arr,true);

			$str .= "FREEBUSY:". $start_time->get_utc_date_time() ."/". $end_time->get_utc_date_time()."\n";

		}
    
		return $str;

	}

        // return a freebusy vcal string 
        function get_vcal_freebusy(&$user_focus,$cached=true)
        {
           $str = "BEGIN:VCALENDAR\n";
           $str .= "VERSION:2.0\n";
           $str .= "PRODID:-//SugarCRM//SugarCRM Calendar//EN\n";
           $str .= "BEGIN:VFREEBUSY\n";
                                                                                                   
           $name = $user_focus->first_name. " ". $user_focus->last_name;
           $email = $user_focus->email1;
                                                                                                   
                                                                                                   
           // get current time local
           $date_arr = array();
           $now_date_time_local = new DateTime($date_arr,true);
                                                                                                   
           // get current time GMT
           $date_arr = array('ts'=>$now_date_time_local->ts - $now_date_time_local->tz_offset);
           $now_date_time = new DateTime($date_arr,true);
                                                                                                   
           // get start date GMT ( 1 day ago )
           $date_arr = array(
             'day'=>$now_date_time->day - 1,
             'month'=>($now_date_time->month),
             'hour'=>($now_date_time->hour),
             'min'=>($now_date_time->min),
             'year'=>$now_date_time->year);
                                                                                                   
           $start_date_time = new DateTime($date_arr,true);
                                                                                                   

           // get date 2 months from start date
           $date_arr = array(
             'day'=>$start_date_time->day,
             'month'=>($start_date_time->month + 2),
             'hour'=>($start_date_time->hour),
             'min'=>($start_date_time->min),
             'year'=>$start_date_time->year);
                                                                                                   
           $end_date_time = new DateTime($date_arr,true);
                                                                                                   
           // get UTC time format
           $utc_start_time = $start_date_time->get_utc_date_time();
           $utc_end_time = $end_date_time->get_utc_date_time();
           $utc_now_time = $now_date_time->get_utc_date_time();
                                                                                                   
           $str .= "ORGANIZER;CN=$name:$email\n";
           $str .= "DTSTART:$utc_start_time\n";
           $str .= "DTEND:$utc_end_time\n";
                                                                                                   
           // now insert the freebusy lines
           // retrieve cached freebusy lines from vcals
           if ($cached == true)
           {
             $str .= $this->get_freebusy_lines_cache($user_focus);
           } 
           // generate freebusy from Meetings and Calls
           else
           {      
	     $str .= $this->create_sugar_freebusy($user_focus,$start_date_time,$end_date_time);
           }
                                                                                                   
           // UID:20030724T213406Z-10358-1000-1-12@phoenix
           $str .= "DTSTAMP:$utc_now_time\n";
           $str .= "END:VFREEBUSY\n";
           $str .= "END:VCALENDAR\n";
           return $str;

	}

	// static function:
        // cache vcals
        function cache_sugar_vcal(&$user_focus)
        {
            vCal::cache_sugar_vcal_freebusy($user_focus);
        }

	// static function:
        // caches vcal for Activities in Sugar database
        function cache_sugar_vcal_freebusy(&$user_focus)
        {
            $focus = new vCal();
            // set freebusy members and save 
            $arr = array('user_id'=>$user_focus->id,'type'=>'vfb','source'=>'sugar');
            $focus->retrieve_by_string_fields($arr);
                                                                                                   
                                                                                                   
            $focus->content = $focus->get_vcal_freebusy($user_focus,false);
            $focus->type = 'vfb';
            $focus->date_modified = null;
            $focus->source = 'sugar';
            $focus->user_id = $user_focus->id;
            $focus->save();
        }


}

?>
