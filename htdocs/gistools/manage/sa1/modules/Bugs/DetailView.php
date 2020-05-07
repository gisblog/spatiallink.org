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
 * $Id: DetailView.php,v 1.17 2005/04/27 02:35:52 majed Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Bugs/Bug.php');
require_once('modules/Bugs/Forms.php');


global $mod_strings;
global $app_strings;

$focus =& new Bug();

if(!empty($_REQUEST['record'])) {
    $result = $focus->retrieve($_REQUEST['record']);
    if($result == null)
    {
    	sugar_die("Error retrieving record.  You may not be authorized to view this record.");
    }
}
else {
	header("Location: index.php?module=Bugs&action=index");
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}
echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->name, true);
echo "\n</p>\n";
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("Bug detail view");

$xtpl=new XTemplate ('modules/Bugs/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

$xtpl->assign("THEME", $theme);
$xtpl->assign("GRIDLINE", $gridline);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("ASSIGNED_TO", $focus->assigned_user_name);
$xtpl->assign("NAME", $focus->name);
$xtpl->assign("DATE_ENTERED", $focus->date_entered);
$xtpl->assign("NUMBER", $focus->number);
$xtpl->assign("STATUS", $app_list_strings['bug_status_dom'][$focus->status]);
		//, "created_by"
		//, "created_by_hash"
if (!empty($focus->resolution))
$xtpl->assign("RESOLUTION", $app_list_strings['bug_resolution_dom'][$focus->resolution]);
if (!empty($focus->release))
$xtpl->assign("RELEASE", $focus->release_name);
if (isset($focus->priority)) {
	$priority=$focus->priority;
}
if (!empty($focus->type))
$xtpl->assign("TYPE", $app_list_strings['bug_type_dom'][$focus->type]);

else {
	$priority = '';
}
$xtpl->assign("PRIORITY", $app_list_strings['bug_priority_dom'][$focus->priority]);
$xtpl->assign("DESCRIPTION", nl2br($focus->description));
$xtpl->assign("DATE_MODIFIED", $focus->date_modified);
$xtpl->assign("DATE_ENTERED", $focus->date_entered);

if (!empty($focus->fixed_in_release))
$xtpl->assign("FIXED_IN_RELEASE", $focus->fixed_in_release_name);
#if (isset($focus->fixed_in_release)) $xtpl->assign("FIXED_IN_RELEASE", $focus->fixed_in_release);
if (isset($focus->work_log)) $xtpl->assign("WORK_LOG", $focus->work_log);

if (!empty($focus->source)) 
$xtpl->assign("SOURCE", $app_list_strings['source_dom'][$focus->source]);


if (!empty($focus->product_category)) 
$xtpl->assign("PRODUCT_CATEGORY", $app_list_strings['product_category_dom'][$focus->product_category]);





global $current_user;
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){

	$xtpl->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action=".$_REQUEST['action'] ."&from_module=".$_REQUEST['module'] ."&record=".$_REQUEST['record']. "'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>");
}

$xtpl->assign("CREATED_BY", $focus->created_by_name);
$xtpl->assign("MODIFIED_BY", $focus->modified_by_name);

// adding custom fields:
require_once('modules/DynamicFields/templates/Files/DetailView.php');






$xtpl->parse("main.open_source");





$xtpl->parse("main");
$xtpl->out("main");

echo "<BR>\n";
$sub_xtpl = $xtpl;
$old_contents = ob_get_contents();
ob_end_clean();

if(array_key_exists('Activities', $modListHeader))
{
	if($sub_xtpl->var_exists('subpanel', 'SUBACTIVITIES')){
ob_start();
// Now get the list of activities that match this opportunity.
$focus_tasks_list = & $focus->get_tasks();
$focus_meetings_list = & $focus->get_meetings();
$focus_calls_list = & $focus->get_calls();
$focus_emails_list = & $focus->get_emails();
$focus_notes_list = & $focus->get_notes();
include('modules/Activities/SubPanelView.php');
$subactivities =  ob_get_contents();
ob_end_clean();
}
}

if(array_key_exists('Contacts', $modListHeader))
{
if($sub_xtpl->var_exists('subpanel', 'SUBCONTACTS')){
ob_start();
// Now get the list of contacts that match this one.
$focus_list = &$focus->get_contacts();
include('modules/Contacts/SubPanelViewContactsAndUsers.php');
$SubPanel = new SubPanelViewContactsAndUsers();
$SubPanel->setFocus($focus);
$SubPanel->setContactsList($focus_list);
$SubPanel->setHideUsers(true);
$SubPanel->ProcessSubPanelListView( 'modules/Contacts/SubPanelViewBugs.html',$mod_strings, $action);
$subcontacts =  ob_get_contents();
ob_end_clean();
}
}



if(array_key_exists('Accounts', $modListHeader))
{
if($sub_xtpl->var_exists('subpanel', 'SUBACCOUNTS')){
ob_start();
echo "<p>\n";
// Now get the list of member accounts that match this one.
$focus_list = &$focus->get_accounts();
include('modules/Accounts/SubPanelViewBugs.php');
echo "</p>\n";
$subaccounts =  ob_get_contents();
ob_end_clean();
}
}



if(array_key_exists('Cases', $modListHeader))
{
if($sub_xtpl->var_exists('subpanel', 'SUBCASES')){
ob_start();
echo "<p>\n";
// Now get the list of member accounts that match this one.
$focus_list = &$focus->get_cases();
include('modules/Cases/SubPanelView.php');
echo "</p>\n";
$subcases =  ob_get_contents();
ob_end_clean();
}
}

ob_start();
echo $old_contents;

if(!empty($subactivities))$sub_xtpl->assign('SUBACTIVITIES', $subactivities);
if(!empty($subcontacts))$sub_xtpl->assign('SUBCONTACTS', $subcontacts);
if(!empty($subaccounts))$sub_xtpl->assign('SUBACCOUNTS', $subaccounts);
if(!empty($subcases))$sub_xtpl->assign('SUBCASES', $subcases);
$sub_xtpl->parse("subpanel");
$sub_xtpl->out("subpanel");
?>
