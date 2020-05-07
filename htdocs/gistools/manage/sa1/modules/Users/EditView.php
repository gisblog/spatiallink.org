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
 * $Id: EditView.php,v 1.69.2.2 2005/05/09 18:00:06 andrew Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Users/User.php');
require_once('modules/Users/Forms.php');

require_once('modules/Administration/Administration.php');
$admin = new Administration();
$admin->retrieveSettings("notify");

global $app_strings;
global $app_list_strings;
global $mod_strings;

$admin = new Administration();
$admin->retrieveSettings();

$focus = new User();

if (!isset($_REQUEST['record'])) $_REQUEST['record'] = "";

if (!is_admin($current_user) && $_REQUEST['record'] != $current_user->id) sugar_die("Unauthorized access to administration.");

if(isset($_REQUEST['record']) && isset($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
	$focus->user_name = "";
}
echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->first_name." ".$focus->last_name." (".$focus->user_name.")", true);
echo "\n</p>\n";
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("User edit view");
$xtpl=new XTemplate ('modules/Users/EditView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

if (isset($_REQUEST['error_string'])) $xtpl->assign("ERROR_STRING", "<span class='error'>Error: ".$_REQUEST['error_string']."</span>");
if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
$xtpl->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js().get_chooser_js());
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("USER_NAME", $focus->user_name);
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

require_once($theme_path.'config.php');

$user_max_tabs = intval($focus->getPreference('max_tabs'));

if($user_max_tabs > 0)
	$xtpl->assign("MAX_TAB", $user_max_tabs);
else
	$xtpl->assign("MAX_TAB", $max_tabs);

$xtpl->assign("MAIL_SENDTYPE", get_select_options_with_id($app_list_strings['notifymail_sendtype'], $focus->getPreference('mail_sendtype')));
$reminder_time = $focus->getPreference('reminder_time');
if(empty($reminder_time)){
	$reminder_time = -1;
}

$xtpl->assign("REMINDER_TIME_OPTIONS", get_select_options_with_id($app_list_strings['reminder_time_options'],$reminder_time));
if($reminder_time > -1){
	$xtpl->assign("REMINDER_TIME_DISPLAY", 'inline');
	$xtpl->assign("REMINDER_CHECKED", 'checked');
}else{
	$xtpl->assign("REMINDER_TIME_DISPLAY", 'none');
}
//Add Custom Fields
require_once('modules/DynamicFields/templates/Files/EditView.php');
if (is_admin($current_user)) {
	$status  = "<td class='dataLabel'>".$mod_strings['LBL_STATUS']." <span class='required'>".$app_strings['LBL_REQUIRED_SYMBOL']."</span></td>\n";
	$status .= "<td><select name='status' tabindex='1'";
	if(!empty($sugar_config['default_user_name']) &&
		$sugar_config['default_user_name']== $focus->user_name &&
		isset($sugar_config['lock_default_user_name']) &&
		$sugar_config['lock_default_user_name'] )
	{
		$status .= ' disabled="disabled" ';
	}
	$status .= ">";
	$status .= get_select_options_with_id($app_list_strings['user_status_dom'], $focus->status);
	$status .= "</select></td>\n";
	$xtpl->assign("USER_STATUS_OPTIONS", $status);
}
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){
	$record = '';
	if(!empty($_REQUEST['record'])){
		$record = 	$_REQUEST['record'];
	}
	$xtpl->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action=".$_REQUEST['action'] ."&from_module=".$_REQUEST['module'] ."&record=".$record. "'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>");
}
require_once('modules/Currencies/ListCurrency.php');
$currency = new ListCurrency();
if($focus->getPreference('currency'))
{
	$selectCurrency = $currency->getSelectOptions($focus->getPreference('currency'));
	$xtpl->assign("CURRENCY", $selectCurrency);
}else{

	$selectCurrency = $currency->getSelectOptions();
	$xtpl->assign("CURRENCY", $selectCurrency);

}

if(!empty($sugar_config['default_user_name']) &&
	$sugar_config['default_user_name'] == $focus->user_name &&
	isset($sugar_config['lock_default_user_name']) &&
	$sugar_config['lock_default_user_name'])
{
	$status .= " disabled ";
	$xtpl->assign("FIRST_NAME_DISABLED", 'disabled="disabled"');
	$xtpl->assign("USER_NAME_DISABLED", 'disabled="disabled"');
	$xtpl->assign("LAST_NAME_DISABLED", 'disabled="disabled"');
	$xtpl->assign("IS_ADMIN_DISABLED", 'disabled="disabled"');
	$xtpl->assign("IS_PORTAL_ONLY_DISABLED", 'disabled="disabled"');
}

if ($focus->receive_notifications || (!isset($focus->id) && $admin->settings['notify_send_by_default'])) $xtpl->assign("RECEIVE_NOTIFICATIONS", "checked");


if($focus->getPreference('gridline') == 'on') {
$xtpl->assign("GRIDLINE", "checked");
}








if(!empty($focus->portal_only) && $focus->portal_only == 1){
	if (is_admin($current_user) && !is_admin($focus)){
		$xtpl->assign("IS_PORTALONLY", "checked");
	}else{
		$xtpl->assign("IS_PORTALONLY", "disabled checked");
	}
}
else
{
	if (!is_admin($current_user)|| is_admin($focus)){
		$xtpl->assign("IS_PORTALONLY", "disabled");
	}
}

if(is_admin($focus))
{
	$xtpl->assign("IS_ADMIN", "checked");
}

$reports_to_change_button_html = '';

if(is_admin($current_user))
{
	$reports_to_change_button_html = '<input type="button"'
	. " title=\"{$app_strings['LBL_CHANGE_BUTTON_TITLE']}\""
	. " accesskey=\"{$app_strings['LBL_CHANGE_BUTTON_KEY']}\""
	. " value=\"{$app_strings['LBL_CHANGE_BUTTON_LABEL']}\""
	. ' tabindex="5" class="button" name="btn1" onclick="'
	. "return window.open('index.php?module=Users&action=Popup&form=UsersEditView&form_submit=false',"
	. "'test','width=600,height=400,resizable=1,scrollbars=1');"
	. '" />';
}
else
{
	$xtpl->assign("IS_ADMIN_DISABLED", 'disabled="disabled"');
}

$xtpl->assign('REPORTS_TO_CHANGE_BUTTON', $reports_to_change_button_html);

$timeOptions = '';
foreach($sugar_config['time_formats'] as $format=>$example){
		if($focus->getPreference('timef') == $format){
		$timeOptions .= "<option value='$format' selected>$example";
		}else $timeOptions .= "<option value='$format'>$example";
}
$xtpl->assign("TIMEOPTIONS", $timeOptions);
$dateOptions = '';
foreach($sugar_config['date_formats'] as $format=>$example){
		if($focus->getPreference('datef') == $format){
		$dateOptions .= "<option value='$format' selected>$example";
		}else $dateOptions .= "<option value='$format'>$example";
}
$xtpl->assign("DATEOPTIONS", $dateOptions);
$format = '';
if($focus->getPreference('datef')){
	$format .= $focus->getPreference('datef');
}else{
	$format .= $sugar_config['default_date_format'];
}
$format .=' ';
if($focus->getPreference('timef')){
	$format .= $focus->getPreference('timef');
}else{
	$format .= $sugar_config['default_time_format'];
}



$timezoneOptions = '';
$timezone = date('Z') / 3600;
$userzone = $focus->getPreference('timez');
//handle daylight savings makes 25 hours in a day
for($v = -12 - $timezone; $v < 14 - $timezone ; $v++){
	if($focus->getPreference('timez') == $v || (!$userzone && $v == 0)){

		 $timezoneOptions.="<option value='$v' selected>".date($format, time() + ($v ) * 60 * 60 );
	}else{

	 $timezoneOptions.="<option value='$v'>".date($format, time() + ($v ) * 60 * 60 );
}}
$xtpl->assign("TIMEZONEOPTIONS", $timezoneOptions);
require_once("include/templates/TemplateGroupChooser.php");
require_once("modules/MySettings/TabController.php");
$chooser = new TemplateGroupChooser();
$controller = new TabController();

if(is_admin($current_user))
{
	$chooser->display_hide_tabs = true;
	$chooser->display_third_tabs = true;
	$chooser->args['third_name'] = 'remove_tabs';
	$chooser->args['third_label'] =  $mod_strings['LBL_REMOVED_TABS'];
	//$xtpl->parse("main.tabchooser");
}

if(is_admin($current_user) || $controller->get_users_can_edit())
{
	$chooser->display_hide_tabs = true;	
}
else 
{
	$chooser->display_hide_tabs = false;
}

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


$xtpl->assign("MAIL_FROMNAME", $focus->getPreference('mail_fromname'));
$xtpl->assign("MAIL_FROMADDRESS", $focus->getPreference('mail_fromaddress'));
$xtpl->assign("MAIL_SMTPSERVER", $focus->getPreference('mail_smtpserver' ));
$xtpl->assign("MAIL_SMTPPORT", $focus->getPreference('mail_smtpport'));
$xtpl->assign("MAIL_SMTPUSER", $focus->getPreference('mail_smtpuser'));
$xtpl->assign("MAIL_SMTPPASS", $focus->getPreference('mail_smtppass'));
if ($focus->getPreference('mail_smtpauth_req' ))
{
$xtpl->assign("MAIL_SMTPAUTH_REQ", " checked");
}
$xtpl->assign("MAIL_SMTPAUTH", $focus->getPreference('mail_smtpauth' ));
$xtpl->assign("MAIL_POPSERVER", $focus->getPreference('mail_popserver') );
$xtpl->assign("MAIL_POPPORT", $focus->getPreference('mail_popport' ));
$xtpl->assign("MAIL_POPUSER", $focus->getPreference('mail_popuser' ));
$xtpl->assign("MAIL_POPPASS", $focus->getPreference('mail_poppass' ));
if ($focus->getPreference('mail_popauth_req' ))
{
  $xtpl->assign("MAIL_POPAUTH_REQ", " checked");
}

$employee_status = '<select tabindex="5" name="employee_status">';
$employee_status .= get_select_options_with_id($app_list_strings['employee_status_dom'], $focus->employee_status);
$employee_status .= '</select>';
$xtpl->assign("EMPLOYEE_STATUS_OPTIONS", $employee_status);

$messenger_type = '<select tabindex="5" name="messenger_type">';
$messenger_type .= get_select_options_with_id($app_list_strings['messenger_type_dom'], $focus->messenger_type);
$messenger_type .= '</select>';
$xtpl->assign("MESSENGER_TYPE_OPTIONS", $messenger_type);
$xtpl->assign("MESSENGER_ID", $focus->messenger_id);


$xtpl->assign("CALENDAR_PUBLISH_KEY", $focus->getPreference('calendar_publish_key' ));
$xtpl->parse("main.freebusy");


$xtpl->parse("main");
$xtpl->out("main");

?>
