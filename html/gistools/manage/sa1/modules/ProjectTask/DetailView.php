<?php
/**
 * The detailed view for a ProjectTask
 *
 * PHP version 4
 *
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
 */

// $Id: DetailView.php,v 1.14 2005/04/29 19:50:21 andrew Exp $

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('include/time.php');
require_once('modules/ProjectTask/ProjectTask.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_user;
global $theme;

$log->info("ProjectTask detail view");
$theme_path = "themes/$theme/";
$image_path = "{$theme_path}images/";
$focus =& new ProjectTask();

$focus->retrieve($_REQUEST['record']);

echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'],
	$mod_strings['LBL_MODULE_NAME'].": ".$focus->name, true);
echo "\n</p>\n";

require_once("{$theme_path}layout_utils.php");

$xtpl =& new XTemplate('modules/ProjectTask/DetailView.html');

if (isset($_REQUEST['return_module'])) $xtpl->assign('return_module', $_REQUEST['return_module']);
if (isset($_REQUEST['return_action'])) $xtpl->assign('return_action', $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) $xtpl->assign('return_id', $_REQUEST['return_id']);
$xtpl->assign('MOD', $mod_strings);
$xtpl->assign('APP', $app_strings);
$xtpl->assign('THEME', $theme);
$xtpl->assign('GRIDLINE', $gridline);
$xtpl->assign('IMAGE_PATH', $image_path);
$xtpl->assign('PRINT_URL', "index.php?".$GLOBALS['request_string']);
$xtpl->assign('id', $focus->id);
$xtpl->assign('name', $focus->name);
$xtpl->assign('assigned_user_name', $focus->assigned_user_name);
$xtpl->assign('status', $focus->status);
$xtpl->assign('date_due', $focus->date_due);
$xtpl->assign('time_due', $focus->time_due);
$xtpl->assign('date_start', $focus->date_start);
$xtpl->assign('time_start', $focus->time_start);
$xtpl->assign('parent_id', $focus->parent_id);
$xtpl->assign('parent_name', $focus->parent_name);
$xtpl->assign('priority', $focus->priority);
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
$xtpl->assign('description', nl2br($focus->description));

if(is_admin($current_user)
	&& $_REQUEST['module'] != 'DynamicLayout'
	&& !empty($_SESSION['editinplace']))
{
	$xtpl->assign('ADMIN_EDIT',
		"<a href='index.php?action=index&module=DynamicLayout&from_action="
			.$_REQUEST['action']
			."&from_module=".$_REQUEST['module'] ."&record="
			.$_REQUEST['record']. "'>"
			.get_image($image_path."EditLayout",
				"border='0' alt='Edit Layout' align='bottom'")."</a>");
}

// adding custom fields:
require_once('modules/DynamicFields/templates/Files/DetailView.php');






$xtpl->parse("main.open_source");




$xtpl->parse('main');
$xtpl->out('main');

///
/// Process the subpanels
///

$sub_xtpl = $xtpl;
$old_contents = ob_get_contents();
ob_end_clean();

if(array_key_exists('Activities', $modListHeader))
{
	if($sub_xtpl->var_exists('subpanel', 'SUBACTIVITIES'))
	{
		ob_start();
		echo "<p>\n";

		// Now get the list of activities that match this account.
		$focus_tasks_list = array();
		$focus_meetings_list =& $focus->get_meetings();
		$focus_calls_list =& $focus->get_calls();
		$focus_emails_list =& $focus->get_emails();
		$focus_notes_list =& $focus->get_notes();

		include('modules/Activities/SubPanelView.php');
		echo "</p>\n";
		$subactivities = ob_get_contents();
		ob_end_clean();
	}
}

ob_start();
echo $old_contents;

if(!empty($subactivities))
{
	$sub_xtpl->assign('SUBACTIVITIES', $subactivities);
}

$sub_xtpl->parse('subpanel');
$sub_xtpl->out('subpanel');


?>
