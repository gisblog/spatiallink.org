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
/////////////////////////////////
// template
/////////////////////////////////
$timedate = new TimeDate();
function template_cal_tabs(&$args)
{
global $mod_strings;
$tabs = array(
  'day',
  'week',
  'month',
  'year'
);

$other_class = 'button';
$sel_class = 'buttonOn';

?>

<table id="cal_tabs" width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td style="padding-bottom: 2px;">
<?php 
$bg = $other_class;
$time_arr = array();

foreach ($tabs as $tab) 
{ 
 if ($args['view'] == $tab)
 {
   $bg = $sel_class;
 } else 
 {
   $bg = $other_class;
 }
?>
<input onclick="window.location.href='index.php?module=Calendar&action=index&view=<?php echo $tab; ?><?php echo $args['calendar']->date_time->get_date_str(); ?>'" type="button" class="<?php echo $bg; ?>" value=" <?php echo $mod_strings["LBL_".$args['calendar']->get_view_name($tab)]; ?> " title="<?php echo $mod_strings["LBL_".$args['calendar']->get_view_name($tab)]; ?>"></a>&nbsp;
<?php } ?>
</td>
</tr>
</table>

<?php
}


/////////////////////////////////
// template
/////////////////////////////////
function template_cal_month_slice(&$args)
{
?>
<?php template_echo_slice_date($args); 
$newargs = array();
$cal_arr = array();
$cal_arr['month'] = $args['slice']->start_time->month;
$cal_arr['year'] = $args['slice']->start_time->year;
$newargs['calendar'] = new Calendar('month',$cal_arr);
$newargs['calendar']->show_only_current_slice = true;
$newargs['calendar']->show_activities = false;
$newargs['calendar']->show_week_on_month_view = false;
template_calendar_month($newargs); 
?>
<?php
}

/////////////////////////////////
// template
/////////////////////////////////
function template_echo_slice_activities(&$args)
{
	global $app_list_strings;
	global $image_path;
	global $current_user;
	$count = 0;
	if ( empty($args['slice']->acts_arr[$current_user->id]))
	{
		return;
	}

	foreach ($args['slice']->acts_arr[$current_user->id] as $act)
	{
		$count++;
?><div style="margin-top: 1px;">
		<table cellpadding="0" cellspacing="0" border="0" width="100%" class="monthCalBodyDayItem">
		<tr>
		<?php if ($act->sugar_bean->object_name == 'Call') { ?>
		<td class="monthCalBodyDayIconTd"><?php echo get_image($image_path.'Calls','alt="'.$app_list_strings['call_status_dom'][$act->sugar_bean->status].': '.$act->sugar_bean->name.'"'); ?></td>
		<td class="monthCalBodyDayItemTd" width="100%"><a href="index.php?module=Calls&action=DetailView&record=<?php echo $act->sugar_bean->id; ?>" class="monthCalBodyDayItemLink"><?php echo $app_list_strings['call_status_dom'][$act->sugar_bean->status]; ?>: <?php echo $act->sugar_bean->name; ?></a></td>
		<?php } else if ($act->sugar_bean->object_name == 'Meeting') { ?>
		<td class="monthCalBodyDayIconTd"><?php echo get_image($image_path.'Meetings','alt="'.$app_list_strings['meeting_status_dom'][$act->sugar_bean->status].': '.$act->sugar_bean->name.'"'); ?></td>
		<td class="monthCalBodyDayItemTd" width="100%"><a href="index.php?module=Meetings&action=DetailView&record=<?php echo $act->sugar_bean->id; ?>" class="monthCalBodyDayItemLink"><?php echo $app_list_strings['meeting_status_dom'][$act->sugar_bean->status]; ?>: <?php echo $act->sugar_bean->name; ?></a></td>
		<?php } else if ($act->sugar_bean->object_name == 'Task') { ?>
		<td class="monthCalBodyDayIconTd"><?php echo get_image($image_path.'Tasks','alt="Due: '.$act->sugar_bean->name.'"'); ?></td>
		<td class="monthCalBodyDayItemTd" width="100%"><a href="index.php?module=Tasks&action=DetailView&record=<?php echo $act->sugar_bean->id; ?>" class="monthCalBodyDayItemLink">Due: <?php echo $act->sugar_bean->name; ?></a></td>
		<?php } ?>
		</tr>
		</table><div>
<?php
	}
}

/////////////////////////////////
// template
/////////////////////////////////
function template_cal_day_slice(&$args)
{
/*
	echo "cale:".$args['calendar']->view;
	echo "cal1:".$args['calendar']->date_time->month;
	echo "cal3:".$args['slice']->date_time->month;
*/
	if ($args['calendar']->show_only_current_slice == false
		||
		$args['calendar']->date_time->month  == $args['slice']->start_time->month )
	{
	 	template_echo_slice_date($args); 

		if ($args['calendar']->show_activities == true) 
		{ 
			template_echo_slice_activities($args); 
		}
	
	}
}


/////////////////////////////////
// template
/////////////////////////////////
function template_calendar(&$args)
{
	global $timedate;
if (isset( $args['size']) && $args['size'] = 'small')
{
$args['calendar']->show_activities = false;
$args['calendar']->show_week_on_month_view = false;
}

?>

<?php 
$newargs = array();
$newargs['view'] = $args['view'];
$newargs['calendar'] = $args['calendar'];
if (! isset( $args['size']) || $args['size'] != 'small')
{
template_cal_tabs($newargs); 
}
?>
<script language="javascript">
<?php 
if (isset($_REQUEST['view']) && !empty($_REQUEST['month'])) {
	if ( $_REQUEST['view'] == 'day') {
			
		?>

document.CallSave.date_start.value = "<?php echo $timedate->to_display_date($args['calendar']->date_time->get_mysql_date(),false); ?>";
document.CallSave.time_start.value = "<?php echo $timedate->to_display_time($args['calendar']->date_time->get_mysql_time(). ':00', false); ?>";
<?php
	}
}
?>
function set_dates(date,time)
{
document.CallSave.date_start.value = date;
document.CallSave.time_start.value = time;

}
</script>
<table id="daily_cal_table_outside" width="100%" border="0" cellpadding="0" cellspacing="0" class="monthBox">
<tr>
<td>
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="monthHeader">
  <tr>
  <td width="1%" class="monthHeaderPrevTd" nowrap><?php 

if (! isset($args['size']) || $args['size'] != 'small')
{
template_get_previous_calendar($args); 
}

?>  </td>
  <td width="98%" align=center scope='row'>
<?php

if (isset( $args['size']) && $args['size'] = 'small')
{
?>
<a style="text-decoration: none;"href="index.php?module=Calendar&action=index&view=month&<?php echo $args['calendar']->date_time->get_date_str(); ?>">
<?php
}
?>
<span class="monthHeaderH3">
<?php template_echo_date_info($args['view'],$args['calendar']->date_time); ?>
</span>
<?php
if(isset( $args['size']) && $args['size'] = 'small')
{
echo "</a>";
}

?>

</span>
  </td>
  <td align="right" class="monthHeaderNextTd" width="1%" nowrap><?php 

if (! isset($args['size']) || $args['size'] != 'small')
{
template_get_next_calendar($args); 
}

?> </td>
  </tr>
  </table>
</td>
</tr>
<tr>
<td class="monthCalBody">
<?php 
if ($args['calendar']->view == 'month')
{
	template_calendar_month($args);
} 
else if ($args['calendar']->view == 'year')
{
	template_calendar_year($args);
} 
else
{
	template_calendar_vertical($args);
} 
?>
</td>
</tr>
<tr>
<td>
  <table width="100%" cellspacing="0" cellpadding="0" class="monthFooter">
  <tr>
  <td width="50%" class="monthFooterPrev"><?php template_get_previous_calendar($args); ?></td>
  <td align="right" width="50%" class="monthFooterNext"><?php template_get_next_calendar($args); ?></td>
  </tr>
  </table>

</td>
</tr>
</table>
<?php

}

function template_calendar_vertical(&$args)
{
?>
  <table id="daily_cal_table" border="0" cellpadding="0" cellspacing="1" width="100%">
  <?php
  // need to change these values after we find out what activities
  // occur outside of these values
  $start_slice_idx = $args['calendar']->get_start_slice_idx();
  $end_slice_idx = $args['calendar']->get_end_slice_idx();
  $cur_slice_idx = 1;
  for($cur_slice_idx=$start_slice_idx;$cur_slice_idx<=$end_slice_idx;$cur_slice_idx++)
  {
	$calendar  =  $args['calendar'];
	$args['slice'] =  $calendar->slice_hash[$calendar->slices_arr[$cur_slice_idx]];
	//print_r($cur_time);
  ?>
  <tr>
  <?php template_cal_vertical_slice($args); ?>
  </tr>
  <?php
  }
  ?>
  </table>
<?php
}


function template_cal_vertical_slice(&$args)
{
	global $timedate;
?>
<td width="1%" class="dailyCalBodyTime" id="bodytime" scope='row'>
<?php template_echo_slice_date($args) ; ?>

</td>
<td width="99%" class="dailyCalBodyItems" id="bodyitem">

<div style="display:none;" id='<?php echo template_echo_daily_view_24_hour($args); ?>_appt'> <?php 
require_once('modules/Calls/CallFormBase.php');
$callForm = new CallFormBase();
echo $callForm->getFormBody('', 'Calls','inlineCal'.template_echo_daily_view_24_hour($args).'CallSave',$timedate->to_display_date($args['calendar']->date_time->get_mysql_date(), false),$timedate->to_display_time(template_echo_daily_view_24_hour ($args).':00:00',true, false)) ."<br>";
?></div>

<?php template_echo_slice_activities($args); ?>
</td>
<?php
}



function template_calendar_year(&$args)
{
$count = 0;
?>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
    <td class="yearCalBody">
  <table id="daily_cal_table" border="0" cellpadding="0"  cellspacing="1" width="100%">
<?php

  for($i=0;$i<4;$i++)
  {
?>
<tr>
<?php
        for($j=0;$j<3;$j++)
        {
         $args['slice'] =  $args['calendar']->slice_hash[$args['calendar']->slices_arr[$count]];
?>

<td valign="top" align="center" scope='row' class="yearCalBodyMonth"><?php template_cal_month_slice($args); ?></td>

<?php
                $count++;
        }
?>
</tr>
<?php
  }
?>
</table>
</td>
</tr>
</table>

<?php
}



function template_calendar_month(&$args)
{
	global $mod_strings;
?>

<table width="100%" id="daily_cal_table" border="0" cellspacing="1" cellpadding="0" >
  <?php
  // need to change these values after we find out what activities
  // occur outside of these values
/*
  $start_slice_idx = $args['calendar']->get_start_slice_idx();
  $end_slice_idx = $args['calendar']->get_end_slice_idx();
  $cur_slice_idx = 1;
*/
  $count = 0;
  if ($args['calendar']->slice_hash[$args['calendar']->slices_arr[35]]->start_time->month
	!= $args['calendar']->date_time->month)
  {
	$rows = 5;
  }
  else
  {
	$rows = 6;
  }
?>
<tr>
<?php if ($args['calendar']->show_week_on_month_view ) { ?>
<th width="1%"  class="monthCalBodyTHWeek" scope='col'><?php echo $mod_strings['LBL_WEEK']; ?></th>
<?php } ?>
<?php

  for($i=0;$i<7;$i++)
  {
	$first_row_slice =  $args['calendar']->slice_hash[$args['calendar']->slices_arr[$i]];
?>
<th width="14%"  class="monthCalBodyTHDay" scope='col' ><?php echo $first_row_slice->start_time->get_day_of_week_short(); ?></th>
<?php
  }
?>
</tr>
<?php

if (isset($_REQUEST['view']) && $_REQUEST['view'] == 'month') 
{ 
  		$height_class="monthViewDayHeight";
} 
else  if (isset($args['size']) && $args['size'] == 'small')
{ 
   		$height_class="";
} 
else
{
   		$height_class="yearViewDayHeight";
}

  for($i=0;$i<$rows;$i++)
  {

?>
<tr class="<?php echo $height_class; ?>">
<?php if ($args['calendar']->show_week_on_month_view ) { ?>
<td valign=middle align=center class="monthCalBodyWeek" scope='row'><a href="index.php?module=Calendar&action=index&view=week&<?php echo $args['calendar']->slice_hash[$args['calendar']->slices_arr[$count]]->start_time->get_date_str(); ?>" class="monthCalBodyWeekLink"><?php echo $args['calendar']->slice_hash[$args['calendar']->slices_arr[$count + 1]]->start_time->week; ?></a></td>
<?php } ?>
<?php
  	for($j=0;$j<7;$j++)
  	{
	$args['slice'] =  $args['calendar']->slice_hash[$args['calendar']->slices_arr[$count]];
?>

<td  valign=top <?php if($j==0)echo "scope='row' ";?> class="<?php if($j==0 || $j==6) { ?>monthCalBody<?php if (get_current_day($args) == true) {echo "Today"; }?>WeekEnd<?php } else { ?>monthCalBody<?php if (get_current_day($args) == true) {echo "Today"; }?>WeekDay<?php } ?>"><?php  template_cal_day_slice($args); ?></td>

<?php
		$count++;
	}
?>
</tr>
<?php
  }
?>
</table>
<?php
}


function get_current_day(&$args) {
$slice = $args['slice'];
if ( $slice->start_time->get_mysql_date() == date('Y-m-d') )
{
return true;
}
}

function template_echo_daily_view_hour (&$args) {

$slice = $args['slice'];
 $hour=$slice->start_time->get_hour();
return $hour;


}

function template_echo_daily_view_24_hour (&$args) {

$slice = $args['slice'];
 $hour=$slice->start_time->get_24_hour();
return $hour;


}

function template_echo_slice_date(&$args)
{
	global $mod_strings;
	$slice = $args['slice'];
	
		if ( $slice->view != 'hour') {
			if ($slice->start_time->get_day_of_week_short() == 'Sun' || $slice->start_time->get_day_of_week_short() == 'Sat') {
			echo "<a href=\"index.php?module=Calendar&action=index&view=".$slice->get_view()."&". $slice->start_time->get_date_str() ."\" ";
			} else {
			echo "<a href=\"index.php?module=Calendar&action=index&view=".$slice->get_view()."&". $slice->start_time->get_date_str() ."\" ";
			}
		}

                if ($slice->view=='day' && $args['calendar']->view == 'week')
                {
				echo "class='weekCalBodyDayLink'>";
                        echo $slice->start_time->get_day_of_week_short();
			echo "&nbsp;";
                        echo $slice->start_time->get_day();
                }
                else if ($slice->view=='day')
                {
				echo "class='monthCalBodyWeekDayDateLink'>";
					if ($slice->start_time->get_month() == $args['calendar']->date_time->get_month())
					{
	                        echo $slice->start_time->get_day();
					}
	                        //echo $slice->start_time->get_day();
                }
                else if ($slice->view=='month')
                {
				echo "class='yearCalBodyMonthLink'>";
                        echo $slice->start_time->get_month_name();
                }
                else if ($slice->view=='hour')
                {
			//echo $slice->date_time->dump_date_info();
			if ($args['calendar']->toggle_appt == true)
			{
				echo '<a href="javascript:void  toggleDisplay(\''.$slice->start_time->get_24_hour().'_appt\');" class="weekCalBodyDayLink">';
			}
			/*echo "<a href=\"#\" class='weekCalBodyDayLink' onclick=\"set_dates('";
			echo $slice->start_time->get_mysql_date();
			echo "','";
			echo $slice->start_time->get_mysql_time();
			echo "'); return false;\">";*/
			if ($args['calendar']->use_24)
			{
                        	echo $slice->start_time->get_24_hour();
				echo ":00";
			}
			else
			{
                        	echo $slice->start_time->get_hour();
				echo ":00";
				echo "&nbsp;".$mod_strings['LBL_'.$slice->start_time->get_am_pm()];
			}
                }
                else
                {
			sugar_die("template_echo_slice_date: view not supported");
                }

			echo "</a>";
}

function template_echo_date_info($view,$date_time)
{
                if ($view=='month')
                {
                		
                        echo $date_time->get_month_name()." ";
                        echo $date_time->year;
                }
                else if ($view=='week')
                {
			$first_day = $date_time->get_day_by_index_this_week(0);
			$last_day = $date_time->get_day_by_index_this_week(6);
                        echo $first_day->get_day()." ";
                        echo $first_day->get_month_name()." ";
                        echo $first_day->year;
			echo " - ";
                        echo $last_day->get_day()." ";
                        echo $last_day->get_month_name()." ";
                        echo $last_day->year;
                }
                else if ($view=='day')
                {
                        echo $date_time->get_day_of_week()." ";
                        echo $date_time->get_day()." ";
                        echo $date_time->get_month_name()." ";
                        echo $date_time->year;
		}
                else if ($view=='year')
                {
                        echo $date_time->year;
                }
                else
                {
                        sugar_die( "echo_date_info: date not supported");
                }
}

function template_get_next_calendar(&$args)
{
global $image_path;
global $mod_strings;
?>
<a href="index.php?action=index&module=Calendar&view=<?php echo $args['calendar']->view; ?>&<?php echo $args['calendar']->get_next_date_str(); ?>" class="NextPrevLink"><?php echo $mod_strings["LBL_NEXT_".$args['calendar']->get_view_name($args['calendar']->view)]; ?>&nbsp;<?php echo get_image($image_path.'calendar_next','alt="'. $mod_strings["LBL_NEXT_".$args['calendar']->get_view_name($args['calendar']->view)].'" align="absmiddle" border="0"'); ?></a>
<?php
}

function template_get_previous_calendar(&$args)
{
global $mod_strings;
global $image_path;
?>
<a href="index.php?action=index&module=Calendar&view=<?php echo $args['calendar']->view; ?>&<?php echo $args['calendar']->get_previous_date_str(); ?>" class="NextPrevLink"><?php echo get_image($image_path.'calendar_previous','alt="'. $mod_strings["LBL_PREVIOUS_".$args['calendar']->get_view_name($args['calendar']->view)].'" align="absmiddle" border="0"'); ?>&nbsp;&nbsp;<?php echo $mod_strings["LBL_PREVIOUS_".$args['calendar']->get_view_name($args['calendar']->view)]; ?></a>
<?php
}
?>
