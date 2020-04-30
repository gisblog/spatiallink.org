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
 * $Id: DetailView.php,v 1.75.2.1 2005/05/04 22:53:34 robert Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Users/User.php');
require_once('include/utils.php');

global $current_user;
global $theme;
global $app_strings;
global $mod_strings;

if (!is_admin($current_user) && ($_REQUEST['record'] != $current_user->id)) sugar_die("Unauthorized access to administration.");

$focus = new User();


if(!empty($_REQUEST['record'])) {
    $result = $focus->retrieve($_REQUEST['record']);
    if($result == null)
    {
    	sugar_die("Error retrieving record.  You may not be authorized to view this record.");
    }
}
else {
	header("Location: index.php?module=Users&action=ListView");
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}

if(isset($_REQUEST['reset_preferences'])){
	$current_user->resetPreferences();
}
echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->first_name." ".$focus->last_name." (".$focus->user_name.")", true);
echo "\n</p>\n";
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');


$log->info("User detail view");

$xtpl=new XTemplate ('modules/Users/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

$xtpl->assign("THEME", $theme);
$xtpl->assign("GRIDLINE", $gridline);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("USER_NAME", $focus->user_name);
$xtpl->assign("FIRST_NAME", $focus->first_name);
$xtpl->assign("LAST_NAME", $focus->last_name);
$xtpl->assign("STATUS", $focus->status);
$reminder_time = $focus->getPreference('reminder_time');

if(empty($reminder_time)){
	$reminder_time = -1;
}
if($reminder_time != -1){
	$xtpl->assign("REMINDER_CHECKED", 'checked');
	$xtpl->assign("REMINDER_TIME", translate('reminder_time_options', '', $reminder_time));
}
// adding custom fields:
require_once('modules/DynamicFields/templates/Files/DetailView.php');
if ((is_admin($current_user) || $_REQUEST['record'] == $current_user->id)
		&& !empty($sugar_config['default_user_name'])
		&& $sugar_config['default_user_name'] == $focus->user_name
		&& isset($sugar_config['lock_default_user_name'])
		&& $sugar_config['lock_default_user_name']) {
	$buttons = "<input title='".$app_strings['LBL_EDIT_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_EDIT_BUTTON_KEY']."' class='button' onclick=\"this.form.return_module.value='Users'; this.form.return_action.value='DetailView'; this.form.return_id.value='$focus->id'; this.form.action.value='EditView'\" type='submit' name='Edit' value='  ".$app_strings['LBL_EDIT_BUTTON_LABEL']."  '>  ";
}
elseif (is_admin($current_user) || $_REQUEST['record'] == $current_user->id) {
	$buttons = "<input title='".$app_strings['LBL_EDIT_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_EDIT_BUTTON_KEY']."' class='button' onclick=\"this.form.return_module.value='Users'; this.form.return_action.value='DetailView'; this.form.return_id.value='$focus->id'; this.form.action.value='EditView'\" type='submit' name='Edit' value='  ".$app_strings['LBL_EDIT_BUTTON_LABEL']."  '>  ";
	$buttons .= "<input title='".$mod_strings['LBL_CHANGE_PASSWORD_BUTTON_TITLE']."' accessKey='".$mod_strings['LBL_CHANGE_PASSWORD_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick='return window.open(\"index.php?module=Users&action=ChangePassword&form=DetailView\",\"test\",\"width=320,height=230,resizable=1,scrollbars=1\");' type='button' name='password' value='".$mod_strings['LBL_CHANGE_PASSWORD_BUTTON_LABEL']."'>  ";
}

if(isset($_SERVER['QUERY_STRING'])) $the_query_string = $_SERVER['QUERY_STRING'];
else $the_query_string = '';

if (is_admin($current_user)) $buttons .= "<input title='".$app_strings['LBL_DUPLICATE_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_DUPLICATE_BUTTON_KEY']."' class='button' onclick=\"this.form.return_module.value='Users'; this.form.return_action.value='DetailView'; this.form.isDuplicate.value=true; this.form.action.value='EditView'\" type='submit' name='Duplicate' value=' ".$app_strings['LBL_DUPLICATE_BUTTON_LABEL']." '>";
$buttons .="<td width='100%' align='right' nowrap><a href='".$_SERVER['PHP_SELF'] .'?'.$the_query_string."&reset_preferences=true' >". $mod_strings['LBL_RESET_PREFERENCES']. " </a>";
if (isset($buttons)) $xtpl->assign("BUTTONS", $buttons);





require_once("include/templates/TemplateGroupChooser.php");
require_once("modules/MySettings/TabController.php");
$chooser = new TemplateGroupChooser();
$controller = new TabController();

//if(is_admin($current_user) || $controller->get_users_can_edit())
if(is_admin($current_user))
{
	$chooser->display_third_tabs = true;
	$chooser->args['third_name'] = 'remove_tabs';
	$chooser->args['third_label'] =  $mod_strings['LBL_REMOVED_TABS'];
}
elseif(!$controller->get_users_can_edit())
{
	$chooser->display_hide_tabs = false;
}
else
{
	$chooser->display_hide_tabs = true;
}

$chooser->args['id'] = 'edit_tabs';
$chooser->args['values_array'] = $controller->get_tabs($focus);
$chooser->args['left_name'] = 'display_tabs';
$chooser->args['right_name'] = 'hide_tabs';
$chooser->args['left_label'] =  $mod_strings['LBL_DISPLAY_TABS'];
$chooser->args['right_label'] =  $mod_strings['LBL_HIDE_TABS'];
$chooser->args['title'] =  $mod_strings['LBL_EDIT_TABS'];
$chooser->args['disable'] = true;

foreach ($chooser->args['values_array'][0] as $key=>$value)
{
$chooser->args['values_array'][0][$key] = $app_list_strings['moduleList'][$key];
}
foreach ($chooser->args['values_array'][1] as $key=>$value)
{
$chooser->args['values_array'][1][$key] = $app_list_strings['moduleList'][$key];
}


$xtpl->assign("TAB_CHOOSER", $chooser->display());
$xtpl->assign("CHOOSE_WHICH", $mod_strings['LBL_CHOOSE_WHICH']);
$xtpl->parse("user_info.tabchooser");


$xtpl->parse("main");
$xtpl->out("main");

if(!empty($focus->portal_only) && $focus->portal_only == 1){
	$xtpl->assign("IS_PORTALONLY", "checked");

}
if ((is_admin($current_user) || $_REQUEST['record'] == $current_user->id) && $focus->is_admin == 'on') {
	$xtpl->assign("IS_ADMIN", "checked");
}

if ($focus->receive_notifications) $xtpl->assign("RECEIVE_NOTIFICATIONS", "checked");

if($focus->getPreference('gridline') == 'on') {
$xtpl->assign("GRIDLINE_CHECK", "checked");
}

require_once('include/TimeDate.php');
$timedate = new TimeDate();
$xtpl->assign("DATEFORMAT", $sugar_config['date_formats'][$timedate->get_date_format()]);
$xtpl->assign("TIMEFORMAT", $sugar_config['time_formats'][$timedate->get_time_format()]);
$xtpl->assign("TIMEZONE", $timedate->to_display_date_time(date($timedate->get_db_date_time_format(), time()),true,true));
$datef = $focus->getPreference('datef');
$timef = $focus->getPreference('timef');
if(!empty($datef))
$xtpl->assign("DATEFORMAT", $sugar_config['date_formats'][$datef]);
if(!empty($timef))
$xtpl->assign("TIMEFORMAT", $sugar_config['time_formats'][$timef]);
	require_once('modules/Currencies/Currency.php');
	$currency  = new Currency();
if($focus->getPreference('currency') )
{

	$currency->retrieve($focus->getPreference('currency'));
	$xtpl->assign("CURRENCY", $currency->name .' : '.$currency->symbol );
}else{

	$xtpl->assign("CURRENCY", $currency->getDefaultCurrencyName() .' : $');

}

$xtpl->parse("user_settings");
$xtpl->out("user_settings");

$xtpl->assign("DESCRIPTION", nl2br($focus->description));
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
$xtpl->assign("EMPLOYEE_STATUS", $focus->employee_status);
$xtpl->assign("MESSENGER_ID", $focus->messenger_id);
$xtpl->assign("MESSENGER_TYPE", $focus->messenger_type);
$xtpl->assign("ADDRESS_STREET", $focus->address_street);
$xtpl->assign("ADDRESS_CITY", $focus->address_city);
$xtpl->assign("ADDRESS_STATE", $focus->address_state);
$xtpl->assign("ADDRESS_POSTALCODE", $focus->address_postalcode);
$xtpl->assign("ADDRESS_COUNTRY", $focus->address_country);
$xtpl->assign("MAIL_FROMNAME", $focus->getPreference('mail_fromname'));
$xtpl->assign("MAIL_FROMADDRESS", $focus->getPreference('mail_fromaddress'));
$xtpl->assign("MAIL_SENDTYPE", $focus->getPreference('mail_sendtype'));
$xtpl->assign("MAIL_SMTPSERVER", $focus->getPreference('mail_smtpserver' ));
$xtpl->assign("MAIL_SMTPPORT", $focus->getPreference('mail_smtpport'));
$xtpl->assign("MAIL_SMTPUSER", $focus->getPreference('mail_smtpuser'));
if ($focus->getPreference('mail_smtpauth_req' ) ) $xtpl->assign("MAIL_SMTPAUTH_REQ", " checked");
$xtpl->assign("MAIL_SMTPAUTH", $focus->getPreference('mail_smtpauth' ));
$xtpl->assign("MAIL_POPSERVER", $focus->getPreference('mail_popserver') );
$xtpl->assign("MAIL_POPPORT", $focus->getPreference('mail_popport' ));
$xtpl->assign("MAIL_POPUSER", $focus->getPreference('mail_popuser' ));
$xtpl->assign("MAIL_POPPASS", $focus->getPreference('mail_poppass' ));
if ($focus->getPreference('mail_popauth_req' ) ) $xtpl->assign("MAIL_POPAUTH_REQ", " checked");

$xtpl->assign("CALENDAR_PUBLISH_KEY", $focus->getPreference('calendar_publish_key' ));
if (! empty($current_user->email1))
{
  $xtpl->assign("CALENDAR_PUBLISH_URL", $sugar_config['site_url'].'/vcal_server.php/type=vfb&email='.$focus->email1.'&source=outlook&key='.$focus->getPreference('calendar_publish_key' ));
  $xtpl->assign("CALENDAR_SEARCH_URL", $sugar_config['site_url'].'/vcal_server.php/type=vfb&email=%NAME%@%SERVER%');
}
else
{
  $xtpl->assign("CALENDAR_PUBLISH_URL", $sugar_config['site_url'].'/vcal_server.php/type=vfb&user_name='.$focus->user_name.'&source=outlook&key='.$focus->getPreference('calendar_publish_key' ));
  $xtpl->assign("CALENDAR_SEARCH_URL", $sugar_config['site_url'].'/vcal_server.php/type=vfb&email=%NAME%@%SERVER%');
}
$xtpl->parse("user_info.freebusy");


$xtpl->parse("user_info");
$xtpl->out("user_info");











echo "</td></tr>\n";

?>
