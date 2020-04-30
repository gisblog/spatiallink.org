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
 * $Id: EditView.php,v 1.4 2005/04/20 08:16:47 robert Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Employees/Employee.php');
require_once('modules/Employees/Forms.php');

require_once('modules/Administration/Administration.php');
$admin = new Administration();
$admin->retrieveSettings("notify");
global $app_strings;
global $app_list_strings;
global $mod_strings;

$focus =& new Employee();

if (!isset($_REQUEST['record'])) $_REQUEST['record'] = "";

if(isset($_REQUEST['record']) && isset($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
	$focus->employee_name = "";
}
echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->first_name." ".$focus->last_name, true);
echo "\n</p>\n";
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("employee edit view");
$xtpl=new XTemplate ('modules/Employees/EditView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

if (isset($_REQUEST['error_string'])) $xtpl->assign("ERROR_STRING", "<span class='error'>Error: ".$_REQUEST['error_string']."</span>");
if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
$xtpl->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js().get_chooser_js());
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("FIRST_NAME", $focus->first_name);
$xtpl->assign("LAST_NAME", $focus->last_name);
$xtpl->assign("TITLE", $focus->title);
$xtpl->assign("DEPARTMENT", $focus->department);
$xtpl->assign("REPORTS_TO_ID", $focus->reports_to_id);
$xtpl->assign("REPORTS_TO_NAME", $focus->reports_to_name);
$xtpl->assign("PHONE_HOME", $focus->phone_home);
$xtpl->assign("PHONE_MOBILE", $focus->phone_mobile);
$xtpl->assign("PHONE_WORK", $focus->phone_work);
$xtpl->assign("PHONE_OTHER", $focus->phone_other);
$xtpl->assign("PHONE_FAX", $focus->phone_fax);
$xtpl->assign("EMAIL1", $focus->email1);
$xtpl->assign("EMAIL2", $focus->email2);
$xtpl->assign("ADDRESS_STREET", $focus->address_street);
$xtpl->assign("ADDRESS_CITY", $focus->address_city);
$xtpl->assign("ADDRESS_STATE", $focus->address_state);
$xtpl->assign("ADDRESS_POSTALCODE", $focus->address_postalcode);
$xtpl->assign("ADDRESS_COUNTRY", $focus->address_country);
$xtpl->assign("DESCRIPTION", $focus->description);
//Add Custom Fields
require_once('modules/DynamicFields/templates/Files/EditView.php');

if (is_admin($current_user)) {
	$employee_status  = "<tr><td class='dataLabel'><slot>".$mod_strings['LBL_EMPLOYEE_STATUS']." <span class='required'>".$app_strings['LBL_REQUIRED_SYMBOL']."</span></slot></td>\n";
	$employee_status .= "<td><slot><select name='employee_status' tabindex='1'";
	if(!empty($sugar_config['default_user_name']) &&
		$sugar_config['default_user_name']== $focus->user_name &&
		isset($sugar_config['lock_default_user_name']) &&
		$sugar_config['lock_default_user_name'] )
	{
		$employee_status .= " disabled ";
	}
	$employee_status .= ">";
	$employee_status .= get_select_options_with_id($app_list_strings['employee_status_dom'], $focus->employee_status);
	$employee_status .= "</select></slot></td>\n";
}
else { $employee_status = ''; }
$xtpl->assign("EMPLOYEE_STATUS_OPTIONS", $employee_status);

$messenger_type = '<select name="messenger_type">';
$messenger_type .= get_select_options_with_id($app_list_strings['messenger_type_dom'], $focus->messenger_type);
$messenger_type .= '</select>';
$xtpl->assign("MESSENGER_TYPE_OPTIONS", $messenger_type);
$xtpl->assign("MESSENGER_ID", $focus->messenger_id);

if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){
	$record = '';
	if(!empty($_REQUEST['record'])){
		$record = 	$_REQUEST['record'];
	}
	$xtpl->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action=".$_REQUEST['action'] ."&from_module=".$_REQUEST['module'] ."&record=".$record. "'>".get_image($image_path."EditLayout.png","border='0' alt='Edit Layout' align='bottom'")."</a>");
}

if(!empty($sugar_config['default_employee_name']) &&
	$sugar_config['default_employee_name'] == $focus->employee_name &&
	isset($sugar_config['lock_default_employee_name']) &&
	$sugar_config['lock_default_employee_name'])
{
	$status .= " disabled ";
	$xtpl->assign("DISABLED", "disabled");
}

require_once("include/templates/TemplateGroupChooser.php");
require_once("modules/MySettings/TabController.php");

/*
$chooser = new TemplateGroupChooser();
$controller = new TabController();
if(is_admin($current_employee) || $controller->get_employees_can_edit()){
$chooser->args['id'] = 'edit_tabs';
$chooser->args['values_array'] = $controller->get_tabs($focus);
foreach ($chooser->args['values_array'][0] as $key=>$value)
{
$chooser->args['values_array'][0][$key] = $app_list_strings['moduleList'][$key];
}
foreach ($chooser->args['values_array'][1] as $key=>$value)
{
$chooser->args['values_array'][1][$key] = $app_list_strings['moduleList'][$key];
}
$chooser->args['left_name'] = 'display_tabs';
$chooser->args['right_name'] = 'hide_tabs';
$chooser->args['left_label'] =  $mod_strings['LBL_DISPLAY_TABS'];
$chooser->args['right_label'] =  $mod_strings['LBL_HIDE_TABS'];
$chooser->args['title'] =  $mod_strings['LBL_EDIT_TABS'];
$xtpl->assign("TAB_CHOOSER", $chooser->display());
$xtpl->assign("CHOOSER_SCRIPT","set_chooser();");
$xtpl->assign("CHOOSE_WHICH", $mod_strings['LBL_CHOOSE_WHICH']);
$xtpl->parse("main.tabchooser");
}
*/

$xtpl->parse("main");
$xtpl->out("main");

?>
