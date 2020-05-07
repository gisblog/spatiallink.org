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
 * $Id: OpenListView.php,v 1.48.2.1 2005/05/05 02:38:26 robert Exp $
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once("include/utils.php");
require_once("modules/Calls/Call.php");
require_once("modules/Meetings/Meeting.php");
require_once('modules/Activities/config.php');
require_once("include/TimeDate.php");
$timedate = new TimeDate();
global $currentModule, $theme, $focus, $action, $open_status, $log;

global $app_strings;
//we don't want the parent module's string file, but rather the string file specifc to this subpanel
global $current_language;
$current_module_strings = return_module_language($current_language, 'Activities');

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$today = date("Y-m-d");
$this_month = date("F Y", strtotime("+1 month"));
$todayTime = date("H:i:s");
$log->debug("today is '$today'; this_month is '$this_month'; todayTime is '$todayTime';");

if (empty($_REQUEST['appointment_filter']))
{
	if ($current_user->getPreference('appointment_filter') == '')
	{
		$appointment_filter = 'today';
	}
	else 
	{
		$appointment_filter = $current_user->getPreference('appointment_filter');
	}
}
else
{
	$appointment_filter = $_REQUEST['appointment_filter'];
	$current_user->setPreference('appointment_filter', $_REQUEST['appointment_filter']);
}	

if ($appointment_filter == 'last this_month')
{
	$next_month = strftime("%B %Y", strtotime("+1 month"));
	$first_day = strftime("%d %B %Y", strtotime("first $next_month"));
	$appt_filter = strftime("%d %B %Y", strtotime("-1 day", strtotime($first_day)));
}
elseif ($appointment_filter == 'last next_month')
{
	$next_month = strftime("%B %Y", strtotime("+2 month"));
	$first_day = strftime("%d %B %Y", strtotime("first $next_month"));
	$appt_filter = strftime("%d %B %Y", strtotime("-1 day", strtotime($first_day)));
	$log->debug("next_month is '$next_month'; first_day is '$first_day';");
}
else 
{
	$appt_filter = $appointment_filter;
}

$later = date("Y-m-d H:i:s", strtotime("$appt_filter"));
$log->debug("appt_filter is '$appt_filter'; later is '$later'");
$later = $timedate->handle_offset($later, $timedate->dbDayFormat, true);

$meeting = new Meeting();
$where = '(';
$or = false;
foreach ($open_status as $status)
{
	if ($or) $where .= ' OR ';
	$or = true;
	$where .= " meetings.status = '$status' "; 
}
$where .= ") and meetings.date_start <= ". db_convert($later, 'date') . " and  meetings_users.user_id='$current_user->id' ";
$where .= " and meetings_users.accept_status != 'decline'";





$focus_meetings_list = $meeting->get_full_list("time_start", $where);

$call = new Call();
$where = '(';
$or = false;
foreach ($open_status as $status)
{
	if ($or) $where .= ' OR ';
	$or = true;
	$where .= " calls.status = '$status' "; 
}
//$where .= ") and calls.date_start <= ". db_convert($later, 'date') . " and (calls.assigned_user_id='$current_user->id' or calls_users.user_id='$current_user->id')";
$where .= ") and calls.date_start <= ". db_convert($later, 'date') . " and calls_users.user_id='$current_user->id' ";
$where .= " and calls_users.accept_status != 'decline'";




$focus_calls_list = $call->get_full_list("time_start", $where);


$open_activity_list = array();

if (count($focus_meetings_list)>0)
  foreach ($focus_meetings_list as $meeting) {
  	$td =  $timedate->merge_date_time($meeting->date_start, $meeting->time_start);
	$open_activity_list[] = Array('name' => $meeting->name,
								 'id' => $meeting->id,
								 'type' => "Meeting",
								 'module' => "Meetings",
								 'status' => $meeting->status,
								 'parent_id' => $meeting->parent_id,
								 'parent_type' => $meeting->parent_type,
								 'parent_name' => $meeting->parent_name,
								 'contact_id' => $meeting->contact_id,
								 'contact_name' => $meeting->contact_name,
								 'normal_date_start' => $meeting->date_start,
								 'date_start' => $timedate->to_display_date($td),
								 'normal_time_start' => $meeting->time_start,
								 'time_start' => $timedate->to_display_time($td,true),
								 'required' => $meeting->required,
								 'accept_status' => $meeting->accept_status,
								 );
}

if (count($focus_calls_list)>0)
  foreach ($focus_calls_list as $call) {
  	$td =  $timedate->merge_date_time($call->date_start, $call->time_start);
	$open_activity_list[] = Array('name' => $call->name,
								 'id' => $call->id,
								 'type' => "Call",
								 'module' => "Calls",
								 'status' => $call->status,
								 'parent_id' => $call->parent_id,
								 'parent_type' => $call->parent_type,
								 'parent_name' => $call->parent_name,
								 'contact_id' => $call->contact_id,
								 'contact_name' => $call->contact_name,
								 'date_start' =>  $timedate->to_display_date($td),
								 'normal_date_start' => $call->date_start,
								 'normal_time_start' => $call->time_start,
								 'time_start' =>$timedate->to_display_time($td,true),
								 'required' => $call->required,
								 'accept_status' => $call->accept_status,
								 );
}

$xtpl=new XTemplate ('modules/Activities/OpenListView.html');
$xtpl->assign("MOD", $current_module_strings);
$xtpl->assign("APP", $app_strings);

// Stick the form header out there.
$filter = get_select_options_with_id($current_module_strings['appointment_filter_dom'], $appointment_filter );
echo "<form method='POST' action='index.php'>\n";
echo "<input type='hidden' name='module' value='Home'>\n";
echo "<input type='hidden' name='action' value='index'>\n";
$day_filter = "<select name='appointment_filter' language='JavaScript' onchange='this.form.submit();'>$filter</select>";

echo get_form_header($current_module_strings['LBL_UPCOMING'], $current_module_strings['LBL_TODAY'].$day_filter.' ('.$timedate->to_display_date($later).') ', false);
echo "</form>\n";

$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("RETURN_URL", "&return_module=$currentModule&return_action=DetailView&return_id=" . ((is_object($focus) && ! empty($focus->id)) ? $focus->id : ""));

$oddRow = true;
if (count($open_activity_list) > 0) $open_activity_list = array_csort($open_activity_list, 'normal_date_start', 'normal_time_start', SORT_ASC);
foreach($open_activity_list as $activity)
{
	
	if( $activity['normal_date_start']	< $today ||  ($activity['normal_date_start'] ==  $today && $activity['normal_time_start'] < $todayTime)){
			$time = "<font class='overdueTask'>".$activity['date_start'].' '.$activity['time_start']."</font>";
	}else if( $activity['normal_date_start']	== $today ){
			$time = "<font class='todaysTask'>".$activity['date_start'].' '.$activity['time_start']."</font>";
		}else{
			$time = "<font class='futureTask'>".$activity['date_start'].' '.$activity['time_start']."</font>";	
		}
		
	
	$activity_fields = array(
		'ID' => $activity['id'],
		'NAME' => $activity['name'],
		'TYPE' => $activity['type'],
		'MODULE' => $activity['module'],
		'STATUS' => $activity['status'],
		'CONTACT_NAME' => $activity['contact_name'],
		'CONTACT_ID' => $activity['contact_id'],
		'PARENT_TYPE' => $activity['parent_type'],
		'PARENT_NAME' => $activity['parent_name'],
		'PARENT_ID' => $activity['parent_id'],
		'TIME' => $time
	);
	switch ($activity['parent_type']) {
		case 'Accounts':
			$activity_fields['PARENT_MODULE'] = 'Accounts';
			break;
		case 'Cases':
			$activity_fields['PARENT_MODULE'] = 'Cases';
			break;
		case 'Opportunities':
			$activity_fields['PARENT_MODULE'] = 'Opportunities';
			break;
		case 'Quotes':
			$activity_fields['PARENT_MODULE'] = 'Quotes';
			break;
	}
	switch ($activity['type']) {
		case 'Call':
			$activity_fields['SET_COMPLETE'] = "<a href='index.php?return_module=$currentModule&return_action=$action&return_id=" . ((is_object($focus) && ! empty($focus->id)) ? $focus->id : "")."&action=EditView&module=Calls&status=Held&record=".$activity['id']."&status=Held'>".get_image($image_path."close_inline","alt='Close' border='0'")."</a>";
			break;
		case 'Meeting':
			$activity_fields['SET_COMPLETE'] = "<a href='index.php?return_module=$currentModule&return_action=$action&return_id=" . ((is_object($focus) && ! empty($focus->id)) ? $focus->id : "")."&action=EditView&module=Meetings&status=Held&record=".$activity['id']."&status=Held'>".get_image($image_path."close_inline","alt='Close' border='0'")."</a>";
			break;
	}

	if (! empty($activity['accept_status']))
  {
	 if ( $activity['accept_status'] == 'none')
	 {
	   $activity_fields['SET_ACCEPT_LINKS'] = "<div id=\"accept".$activity['id']."\"><a title=\"".$app_list_strings['dom_meeting_accept_options']['accept']."\" href=\"javascript:setAcceptStatus('".$activity_fields['MODULE']."','".$activity['id']."','accept');\">". get_image($image_path."accept_inline","alt='".$app_list_strings['dom_meeting_accept_options']['accept']."' border='0'"). "</a>&nbsp;<a title=\"".$app_list_strings['dom_meeting_accept_options']['tentative']."\" href=\"javascript:setAcceptStatus('".$activity_fields['MODULE']."','".$activity['id']."','tentative');\">".get_image($image_path."tentative_inline","alt='".$app_list_strings['dom_meeting_accept_options']['tentative']."' border='0'")."</a>&nbsp;<a title=\"".$app_list_strings['dom_meeting_accept_options']['decline']."\" href=\"javascript:setAcceptStatus('".$activity_fields['MODULE']."','".$activity['id']."','decline');\">".get_image($image_path."decline_inline","alt='".$app_list_strings['dom_meeting_accept_options']['decline']."' border='0'")."</a></div>";
	 } else {
	   $activity_fields['SET_ACCEPT_LINKS'] = $app_list_strings['dom_meeting_accept_status'][$activity['accept_status']];
	 }
  }

$activity_fields['TITLE'] = '';
if (!empty($activity['contact_name'])) {
	$activity_fields['TITLE'] .= $current_module_strings['LBL_LIST_CONTACT'].": ".$activity['contact_name'];
}
if (!empty($activity['parent_name'])) {
	$activity_fields['TITLE'] .= "\n".$app_list_strings['record_type_display'][$activity['parent_type']].": ".$activity['parent_name'];
}

$xtpl->assign("ACTIVITY_MODULE_PNG", get_image($image_path.$activity_fields['MODULE'].'','border="0" alt="'.$activity_fields['NAME'].'"'));
	$xtpl->assign("ACTIVITY", $activity_fields);
 global $odd_bg;
 global $even_bg;
 global $hilite_bg;
 global $click_bg;

$xtpl->assign("BG_HILITE", $hilite_bg);
$xtpl->assign("BG_CLICK", $click_bg);

	if($oddRow)
    {
        //todo move to themes
		$xtpl->assign("ROW_COLOR", 'oddListRow');
		$xtpl->assign("BG_COLOR", $odd_bg);
    }
    else
    {
        //todo move to themes
		$xtpl->assign("ROW_COLOR", 'evenListRow');
		$xtpl->assign("BG_COLOR", $even_bg);
    }
    $oddRow = !$oddRow;

	$xtpl->parse("open_activity.row");
// Put the rows in.
}

$xtpl->parse("open_activity");
if (count($open_activity_list)>0) $xtpl->out("open_activity");
else echo "<i>".$current_module_strings['NTC_NONE_SCHEDULED']."</i>";
// Stick on the form footer
echo get_form_footer();

?>
