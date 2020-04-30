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
 * $Id: EditView.php,v 1.16 2005/04/27 17:35:49 ajay Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Bugs/Bug.php');
require_once('modules/Bugs/Forms.php');
require_once('modules/Releases/Release.php');

global $app_strings;
global $mod_strings;
global $mod_strings;
global $current_user;

$focus =& new Bug();
$seedRelease =& new Release();

if(isset($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
	$focus->number="";
}

if (is_null($focus->status)) {
	$focus->status = $app_list_strings['bug_status_default_key'];
}
echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->name, true);
echo "\n</p>\n";
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("Bug detail view");

$xtpl=new XTemplate ('modules/Bugs/EditView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

if(isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if(isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if(isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
//account_id will be set when user chooses to create a new bug from account detail view.
if (isset($_REQUEST['account_id'])) $xtpl->assign("ACCOUNT_ID", $_REQUEST['account_id']);
//set the case_id, if set.
if (isset($_REQUEST['case_id'])) $xtpl->assign("CASE_ID",$_REQUEST['case_id']);


$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js());
$xtpl->assign("ID", $focus->id);
if (isset($focus->name)) $xtpl->assign("NAME", $focus->name);
else $xtpl->assign("NAME", "");
$xtpl->assign("DATE_ENTERED", $focus->date_entered);
$xtpl->assign("DATE_MODIFIED", $focus->date_modified);
$xtpl->assign("CREATED_BY", $focus->created_by_name);
$xtpl->assign("MODIFIED_BY", $focus->modified_by_name);


if (isset($focus->fixed_in_release)) $xtpl->assign("FIXED_IN_RELEASE", $focus->fixed_in_release);
if (isset($focus->work_log)) $xtpl->assign("WORK_LOG", $focus->work_log);

if (!empty($focus->source)) {
$xtpl->assign("SOURCE_OPTIONS", get_select_options_with_id($app_list_strings['source_dom'],$focus->source));
}
else {
	$xtpl->assign("SOURCE_OPTIONS", get_select_options_with_id($app_list_strings['source_dom'],$app_list_strings['source_default_key']));
}

if (!empty($focus->product_category)) {
$xtpl->assign("PRODUCT_CATEGORY_OPTIONS", get_select_options_with_id($app_list_strings['product_category_dom'],$focus->product_category));
}
else {
	$xtpl->assign("PRODUCT_CATEGORY_OPTIONS", get_select_options_with_id($app_list_strings['product_category_dom'],$app_list_strings['product_category_default_key']));
}


$xtpl->assign("NUMBER", $focus->number);
if(!isset($_REQUEST['isDuplicate'])) $xtpl->assign("NUMBER", $focus->number);
$xtpl->assign("DESCRIPTION", $focus->description);
global $current_user;
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){
	$record = '';
	if(!empty($_REQUEST['record'])){
		$record = 	$_REQUEST['record'];
	}
	$xtpl->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action=".$_REQUEST['action'] ."&from_module=".$_REQUEST['module'] ."&record=".$record. "'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>");
}
if ($focus->assigned_user_id == '' && (!isset($focus->id) || $focus->id=0)) $focus->assigned_user_id = $current_user->id;
$xtpl->assign("ASSIGNED_USER_OPTIONS", get_select_options_with_id(get_user_array(TRUE, "Active", $focus->assigned_user_id), $focus->assigned_user_id));
$xtpl->assign("STATUS_OPTIONS", get_select_options_with_id($app_list_strings['bug_status_dom'], $focus->status));
if (!empty($focus->resolution)) {
$xtpl->assign("RESOLUTION_OPTIONS", get_select_options_with_id($app_list_strings['bug_resolution_dom'],$focus->resolution));
}
else {
	$xtpl->assign("RESOLUTION_OPTIONS", get_select_options_with_id($app_list_strings['bug_resolution_dom'],$app_list_strings['bug_resolution_default_key']));
}
if (!empty($focus->type)) {
$xtpl->assign("TYPE_OPTIONS", get_select_options_with_id($app_list_strings['bug_type_dom'],$focus->type));
}
else {
	$xtpl->assign("TYPE_OPTIONS", get_select_options_with_id($app_list_strings['bug_type_dom'],$app_list_strings['bug_type_default_key']));
}

$xtpl->assign("RELEASE_OPTIONS", get_select_options_with_id($seedRelease->get_releases(TRUE, "Active"), $focus->release));

$xtpl->assign("FIXED_IN_RELEASE_OPTIONS", get_select_options_with_id($seedRelease->get_releases(TRUE, "Active"), $focus->fixed_in_release));



if (empty($focus->priority)) {
	$xtpl->assign("PRIORITY_OPTIONS", get_select_options_with_id($app_list_strings['bug_priority_dom'], $app_list_strings['bug_priority_default_key']));
}
else {
	$xtpl->assign("PRIORITY_OPTIONS", get_select_options_with_id($app_list_strings['bug_priority_dom'], $focus->priority));
}

//Add Custom Fields
require_once('modules/DynamicFields/templates/Files/EditView.php');














$xtpl->parse("main");

$xtpl->out("main");
require_once('include/javascript/javascript.php');
$javascript = new javascript();
$javascript->setFormName('EditView');
$javascript->setSugarBean($focus);
$javascript->addAllFields('');
echo $javascript->getScript();

?>
