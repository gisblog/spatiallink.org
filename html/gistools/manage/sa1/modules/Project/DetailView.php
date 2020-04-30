<?php
/**
 * The detailed view for a project
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

// $Id: DetailView.php,v 1.26 2005/04/22 21:38:53 andrew Exp $

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('include/time.php');
require_once('modules/Project/Project.php');

global $app_strings;
global $mod_strings;
global $theme;
global $current_user;

$log->info('Project detail view');
$focus =& new Project();

// only load a record if a record id is given;
// a record id is not given when viewing in layout editor
if(!empty($_REQUEST['record']))
{
	$focus->retrieve($_REQUEST['record']);
}

echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'],
	$mod_strings['LBL_MODULE_NAME'] . ': ' . $focus->name, true);
echo "\n</p>\n";

$theme_path = 'themes/' . $theme . '/';
$image_path = $theme_path . 'images/';

require_once($theme_path.'layout_utils.php');

$xtpl = new XTemplate('modules/Project/DetailView.html');

///
/// Assign the template variables
///

$xtpl->assign('MOD', $mod_strings);
$xtpl->assign('APP', $app_strings);
if(isset($_REQUEST['return_module']))
{
	$xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
}

if(isset($_REQUEST['return_action']))
{
	$xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
}

if(isset($_REQUEST['return_id']))
{
	$xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
}

$xtpl->assign('PRINT_URL', "index.php?".$GLOBALS['request_string']);
$xtpl->assign('THEME', $theme);
$xtpl->assign('GRIDLINE', $gridline);
$xtpl->assign('IMAGE_PATH', $image_path);
$xtpl->assign('id', $focus->id);
$xtpl->assign('name', $focus->name);
$xtpl->assign('assigned_user_name', $focus->assigned_user_name);
$xtpl->assign('total_estimated_effort', $focus->total_estimated_effort);
$xtpl->assign('total_actual_effort', $focus->total_actual_effort);
$xtpl->assign('description', nl2br($focus->description));

if(is_admin($current_user)
	&& $_REQUEST['module'] != 'DynamicLayout'
	&& !empty($_SESSION['editinplace']))
{
	$xtpl->assign('ADMIN_EDIT',
		'<a href="index.php?action=index&module=DynamicLayout&from_action='
		. $_REQUEST['action'] . '&from_module=' . $_REQUEST['module']
		. '&record=' . $_REQUEST['record'] . '">'
		. get_image($image_path . 'EditLayout',
			 'border="0" alt="Edit Layout" align="bottom"') . '</a>');
}

// adding custom fields
require_once('modules/DynamicFields/templates/Files/DetailView.php');






$xtpl->parse('main.open_source');




$xtpl->parse('main');
$xtpl->out('main');

echo "<p>\n";
echo "</p>\n";

///
/// Check for and construct the sub panels
///

$sub_xtpl = $xtpl;
$old_contents = ob_get_contents();
ob_end_clean();

	if($sub_xtpl->var_exists('subpanel', 'SUB_PROJECT_TASK'))
	{
		ob_start();
		echo "<p>\n";
		$focus_list =& $focus->get_project_tasks();
		include('modules/ProjectTask/SubPanelView.php');
		echo "</p>\n";
		$sub_project_task =  ob_get_contents();
		ob_end_clean();
	}

if(array_key_exists('Activities', $modListHeader))
{
	if($sub_xtpl->var_exists('subpanel', 'SUBACTIVITIES'))
	{
		ob_start();
		echo "<p>\n";

		// Now get the list of activities that match this account.
		$focus_tasks_list = array();
		$focus_meetings_list = & $focus->get_meetings();
		$focus_calls_list = & $focus->get_calls();
		$focus_emails_list = & $focus->get_emails();
		$focus_notes_list = & $focus->get_notes();

		include('modules/Activities/SubPanelView.php');
		echo "</p>\n";
		$subactivities = ob_get_contents();
		ob_end_clean();
	}
}

if(array_key_exists('Contacts', $modListHeader))
{
	if($sub_xtpl->var_exists('subpanel', 'SUBCONTACTS'))
	{
		ob_start();
		echo "<p>\n";
		include('modules/Contacts/SubPanelViewProject.php');
		$focus_list =& $focus->get_contact_relations();
		$SubPanel = new SubPanelViewContactsAndUsers();
		$SubPanel->setContactsList($focus_list);
		$SubPanel->setFocus($focus);
		$SubPanel->setHideUsers(true);
		$SubPanel->setHideNewButton(true);
		$SubPanel->ProcessSubPanelListView('modules/Contacts/SubPanelViewProject.html',
			$mod_strings, $action);
		echo "</p>\n";
		$subcontacts = ob_get_contents();
		ob_end_clean();
	}
}

if(array_key_exists('Accounts', $modListHeader))
{
	if($sub_xtpl->var_exists('subpanel', 'SUBACCOUNTS'))
	{
		ob_start();
		echo "<p>\n";
		$focus_list = &$focus->get_account_relations();
		include('modules/Accounts/SubPanelViewProjects.php');
		echo "</p>\n";
		$subaccounts =  ob_get_contents();
		ob_end_clean();
	}
}

















if(array_key_exists('Opportunities', $modListHeader))
{
	if($sub_xtpl->var_exists('subpanel', 'SUBOPPORTUNITIES'))
	{
		ob_start();
		echo "<p>\n";
		$focus_list =& $focus->get_opportunity_relations();
		include('modules/Opportunities/SubPanelViewProjects.php');
		echo "</p>\n";
		$subopps = ob_get_contents();
		ob_end_clean();
	}
}


ob_start();
echo $old_contents;

if(!empty($sub_project_task))
{
	$sub_xtpl->assign('SUB_PROJECT_TASK', $sub_project_task);
}

if(!empty($subactivities))
{
	$sub_xtpl->assign('SUBACTIVITIES', $subactivities);
}

if(!empty($subcontacts))
{
	$sub_xtpl->assign('SUBCONTACTS', $subcontacts);
}

if(!empty($subaccounts))
{
	$sub_xtpl->assign('SUBACCOUNTS', $subaccounts);
}







if(!empty($subopps))
{
	$sub_xtpl->assign('SUBOPPORTUNITIES', $subopps);
}

$sub_xtpl->parse("subpanel");
$sub_xtpl->out("subpanel");

?>
