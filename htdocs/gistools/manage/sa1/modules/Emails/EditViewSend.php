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
 * $Id: EditViewSend.php,v 1.14.2.1 2005/05/02 22:05:19 robert Exp $
 * Description: TODO:  To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Emails/Email.php');
require_once('include/TimeDate.php');
$timedate = new TimeDate();

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_user;
// Unimplemented until jscalendar language files are fixed
// global $current_language;
// global $default_language;
// global $cal_codes;

$focus =& new Email();

//setting default date and time
//if (is_null($focus->date_sent)) $focus->date_sent = $timedate->to_display_date(date('Y-m-d'));
if(isset($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}

//needed when creating a new email with default values passed in
if (isset($_REQUEST['contact_name']) && is_null($focus->contact_name)) {
	$focus->contact_name = $_REQUEST['contact_name'];
}
if (isset($_REQUEST['contact_id']) && is_null($focus->contact_id)) {
	$focus->contact_id = $_REQUEST['contact_id'];
}
if (isset($_REQUEST['parent_name'])) {
	$focus->parent_name = $_REQUEST['parent_name'];
}
if (isset($_REQUEST['parent_id'])) {
	$focus->parent_id = $_REQUEST['parent_id'];
}
if (isset($_REQUEST['parent_type'])) {
	$focus->parent_type = $_REQUEST['parent_type'];
}
elseif (is_null($focus->parent_type)) {
	$focus->parent_type = $app_list_strings['record_type_default_key'];
}
echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME_SEND'], $mod_strings['LBL_MODULE_NAME_SEND'], true);
echo "\n</p>\n";
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("EmailSend edit view");

$xtpl=new XTemplate ('modules/Emails/EditViewSend.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

// Unimplemented until jscalendar language files are fixed
// $xtpl->assign("CALENDAR_LANG", ((empty($cal_codes[$current_language])) ? $cal_codes[$default_language] : $cal_codes[$current_language]));
$xtpl->assign("CALENDAR_LANG", "en");

if (!isset($focus->id)) $xtpl->assign("USER_ID", $current_user->id);
if (!isset($focus->id) && isset($_REQUEST['contact_id'])) $xtpl->assign("CONTACT_ID", $_REQUEST['contact_id']);

if(isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if(isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if(isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js());
$xtpl->assign("ID", $focus->id);
$xtpl->assign("PARENT_NAME", $focus->parent_name);

$xtpl->assign("TO_ADDRS", $focus->to_addrs);
$xtpl->assign("TO_ADDRS_IDS", $focus->to_addrs_ids);
$xtpl->assign("TO_ADDRS_NAMES", $focus->to_addrs_names);
$xtpl->assign("TO_ADDRS_EMAILS", $focus->to_addrs_emails);

$xtpl->assign("CC_ADDRS", $focus->cc_addrs);
$xtpl->assign("CC_ADDRS_IDS", $focus->cc_addrs_ids);
$xtpl->assign("CC_ADDRS_NAMES", $focus->cc_addrs_names);
$xtpl->assign("CC_ADDRS_EMAILS", $focus->cc_addrs_emails);

$xtpl->assign("BCC_ADDRS", $focus->bcc_addrs);
$xtpl->assign("BCC_ADDRS_IDS", $focus->bcc_addrs_ids);
$xtpl->assign("BCC_ADDRS_NAMES", $focus->bcc_addrs_names);
$xtpl->assign("BCC_ADDRS_EMAILS", $focus->bcc_addrs_emails);

$xtpl->assign("NAME", $focus->name);
$xtpl->assign("DESCRIPTION", $focus->description);

if (empty($focus->parent_type)) 
{
	$xtpl->assign("PARENT_RECORD_TYPE", '');
}
else 
{
	$xtpl->assign("PARENT_RECORD_TYPE", $focus->parent_type);
}
$xtpl->assign("PARENT_ID", $focus->parent_id);
if (isset($focus->name)) $xtpl->assign("NAME", $focus->name);
else $xtpl->assign("NAME", "");

//$xtpl->assign("TIME_MERIDIEM", $timedate->AMPMMenu('',$focus->time_start));
$xtpl->assign("TIME_FORMAT", '('. $timedate->get_user_time_format().')');
$xtpl->assign("USER_DATEFORMAT", '('. $timedate->get_user_date_format().')');
$xtpl->assign("CALENDAR_DATEFORMAT", $timedate->get_cal_date_format());
global $current_user;
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){	
	$record = '';
	if(!empty($_REQUEST['record'])){
		$record = 	$_REQUEST['record'];
	}
	$xtpl->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action=".$_REQUEST['action'] ."&from_module=".$_REQUEST['module'] ."&record=".$record. "'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>");	
}
//if ($focus->assigned_user_id == '' && (!isset($focus->id) || $focus->id=0)) $focus->assigned_user_id = $current_user->id;
//$xtpl->assign("ASSIGNED_USER_OPTIONS", get_select_options_with_id(get_user_array(TRUE, "Active", $focus->assigned_user_id), $focus->assigned_user_id));
//$xtpl->assign("DURATION_HOURS", $focus->duration_hours);


//$xtpl->assign("TYPE_OPTIONS", get_select_options_with_id($app_list_strings['record_type_display'], $focus->parent_type));
$xtpl->assign("TYPE_OPTIONS", get_select_options_with_id(parse_list_modules($app_list_strings['record_type_display']), $focus->parent_type));


//if (isset($focus->duration_minutes)) $xtpl->assign("DURATION_MINUTES_OPTIONS", get_select_options_with_id($focus->minutes_values,$focus->duration_minutes));

$change_parent_button = "<input title='".$app_strings['LBL_CHANGE_BUTTON_TITLE']."' tabindex='2' accessKey='".$app_strings['LBL_CHANGE_BUTTON_KEY']."' type='button' class='button' value='".$app_strings['LBL_CHANGE_BUTTON_LABEL']."' name='button' LANGUAGE=javascript onclick='return window.open(\"index.php?module=\"+ document.EditView.parent_type.value + \"&action=Popup&html=Popup_picker&form=TasksEditView\",\"test\",\"width=600,height=400,resizable=1,scrollbars=1\");'>";
$xtpl->assign("CHANGE_PARENT_BUTTON", $change_parent_button);

$change_to_addrs_button = "<input title='".$app_strings['LBL_CHANGE_BUTTON_TITLE']."' tabindex='2' accessKey='".$app_strings['LBL_CHANGE_BUTTON_KEY']."' type='button' class='button' value='".$app_strings['LBL_CHANGE_BUTTON_LABEL']."' name='button' LANGUAGE=javascript onclick='return button_change_onclick(this);'>";
$xtpl->assign("CHANGE_TO_ADDRS_BUTTON", $change_to_addrs_button);

$change_cc_addrs_button = "<input title='".$app_strings['LBL_CHANGE_BUTTON_TITLE']."' tabindex='2' accessKey='".$app_strings['LBL_CHANGE_BUTTON_KEY']."' type='button' class='button' value='".$app_strings['LBL_CHANGE_BUTTON_LABEL']."' name='button' LANGUAGE=javascript onclick='return button_change_onclick(this);'>";
$xtpl->assign("CHANGE_CC_ADDRS_BUTTON", $change_cc_addrs_button);

$change_bcc_addrs_button = "<input title='".$app_strings['LBL_CHANGE_BUTTON_TITLE']."' tabindex='2' accessKey='".$app_strings['LBL_CHANGE_BUTTON_KEY']."' type='button' class='button' value='".$app_strings['LBL_CHANGE_BUTTON_LABEL']."' name='button' LANGUAGE=javascript onclick='return button_change_onclick(this);'>";
$xtpl->assign("CHANGE_BCC_ADDRS_BUTTON", $change_bcc_addrs_button);

// adding custom fields:
require_once('modules/DynamicFields/templates/Files/EditView.php');









$email_templates_arr = get_bean_select_array(true, 'EmailTemplate','name');
$xtpl->assign("EMAIL_TEMPLATE_OPTIONS", get_select_options_with_id($email_templates_arr, ""));
$xtpl->parse("main.pro");


$xtpl->parse("main");

$xtpl->out("main");
require_once('include/javascript/javascript.php');
$javascript = new javascript();
$javascript->setFormName('EditView');
$javascript->setSugarBean($focus);
$javascript->addAllFields('');
echo $javascript->getScript();

?>
