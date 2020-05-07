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
// $Id: EditView.php,v 1.22.2.1 2005/05/05 21:49:41 andrew Exp $

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/ProjectTask/ProjectTask.php');
require_once('modules/ProjectTask/Forms.php');
require_once('include/time.php');
require_once('include/TimeDate.php');
$timedate = new TimeDate();

global $app_strings;
global $app_list_strings;
global $current_language;
global $current_user;
// Unimplemented until jscalendar language files are fixed
// global $current_language;
// global $default_language;
// global $cal_codes;

$focus =& new ProjectTask();

if(!empty($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}

echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->name, true);
echo "\n</p>\n";
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("ProjectTask detail view");

$xtpl=new XTemplate ('modules/ProjectTask/EditView.html');
$xtpl->assign('MOD', $mod_strings);
$xtpl->assign('APP', $app_strings);

///
/// Populate the fields with existing data
///

$xtpl->assign('name', $focus->name);
$user_list_options = get_select_options_with_id(get_user_array(), $focus->assigned_user_id);
$xtpl->assign("user_list_options", $user_list_options);














if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}

$the_status = empty($_REQUEST['status']) ? $focus->status
	: $_REQUEST['status'];

$options = get_select_options_with_id($app_list_strings['project_task_status_options'], $the_status);
$xtpl->assign('status_options', $options);
$xtpl->assign('id', $focus->id);
$xtpl->assign('date_due', $focus->date_due);
$xtpl->assign('time_due', substr($focus->time_due,0,5));
$xtpl->assign('date_start', $focus->date_start);
$xtpl->assign('time_start', substr($focus->time_start,0,5));
$xtpl->assign('parent_id', $focus->parent_id);
$xtpl->assign('parent_name', $focus->parent_name);
$xtpl->assign('priority_options', get_select_options_with_id($app_list_strings['project_task_priority_options'], $focus->priority));
$xtpl->assign('task_number', $focus->task_number);
$xtpl->assign('depends_on_id', $focus->depends_on_id);
$xtpl->assign('depends_on_name', $focus->depends_on_name);
$xtpl->assign('order_number', $focus->order_number);
if(!empty($focus->milestone_flag) && $focus->milestone_flag == 'on')
{
	$xtpl->assign('milestone_checked', 'checked="checked"');
}
$xtpl->assign('estimated_effort', $focus->estimated_effort);
$xtpl->assign('actual_effort', $focus->actual_effort);
$xtpl->assign('utilization', $focus->utilization);
$xtpl->assign('percent_complete', $focus->percent_complete);
$xtpl->assign('description', $focus->description);
$xtpl->assign('utilization_options',
	get_select_options_with_id($app_list_strings['project_task_utilization_options'],
	$focus->utilization));
$xtpl->assign('task_number', $focus->task_number);

//setting default date and time
if (is_null($focus->date_start))$focus->date_start = $timedate->to_display_date(date('Y-m-d'));
if (is_null($focus->time_start))$focus->time_start = $timedate->to_display_time(date('H:i:s'), true);

$xtpl->assign('time_meridian', $timedate->AMPMMenu('', $focus->time_start));
$xtpl->assign("user_dateformat", '('. $timedate->get_user_date_format().')');
$xtpl->assign("time_format", '('. $timedate->get_user_time_format().')');
if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) $xtpl->assign('RETURN_ID', $_REQUEST['return_id']);
if (isset($_REQUEST['parent_id'])) $xtpl->assign('parent_id', $_REQUEST['parent_id']);
if (isset($_REQUEST['parent_name'])) $xtpl->assign('parent_name', $_REQUEST['parent_name']);


$xtpl->assign("CALENDAR_DATEFORMAT", $timedate->get_cal_date_format());
$xtpl->assign("THEME", $theme);
$xtpl->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js());
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("id", $focus->id);
if (isset($focus->name)) $xtpl->assign("NAME", $focus->name);
else $xtpl->assign("NAME", "");

//Add Custom Fields
require_once('modules/DynamicFields/templates/Files/EditView.php');

global $current_user;
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){	
	$record = '';
	if(!empty($_REQUEST['record'])){
		$record = 	$_REQUEST['record'];
	}
	$xtpl->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action=".$_REQUEST['action'] ."&from_module=".$_REQUEST['module'] ."&record=".$record. "'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>");	
}




$xtpl->parse("main.open_source");



$xtpl->parse("main");

$xtpl->out("main");
require_once('include/javascript/javascript.php');
$javascript = new javascript();
$javascript->setFormName('EditView');
$javascript->setSugarBean($focus);
$javascript->addAllFields('');
echo $javascript->getScript();

?>
