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
 * $Id: Forms.php,v 1.46 2005/04/14 09:31:05 lam Exp $
 * Description:  Contains a variety of utility functions used to display UI
 * components such as form headers and footers.  Intended to be modified on a per
 * theme basis.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

/**
 * Create javascript to validate the data entered into a record.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_validate_record_js () {

}
function get_validate_record_js_old () {

}

/**
 * Create HTML form to enter a new record with the minimum necessary fields.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_new_record_form_old () {
require_once('include/time.php');
global $mod_strings;
global $app_strings;
global $app_list_strings;
global $current_user;
global $theme;
// Unimplemented until jscalendar language files are fixed
// global $current_language;
// global $default_language;
// global $cal_codes;

$lbl_required_symbol = $app_strings['LBL_REQUIRED_SYMBOL'];
$lbl_subject = $mod_strings['LBL_SUBJECT'];
$lbl_date = $mod_strings['LBL_DATE'];
$lbl_time = $mod_strings['LBL_TIME'];
$ntc_date_format = $app_strings['NTC_DATE_FORMAT'];
$lbl_save_button_title = $app_strings['LBL_SAVE_BUTTON_TITLE'];
$lbl_save_button_key = $app_strings['LBL_SAVE_BUTTON_KEY'];
$lbl_save_button_label = $app_strings['LBL_SAVE_BUTTON_LABEL'];
$default_parent_type= $app_list_strings['record_type_default_key'];
$default_date_start = date('Y-m-d');
$default_time_start = to_display_time(date('H:i'));
$ntc_time_format = '('.getDisplayTimeFormat().')';

$ampm = AMPMMenu('',date('H:i'));
$user_id = $current_user->id;
// Unimplemented until jscalendar language files are fixed
// $cal_lang = (empty($cal_codes[$current_language])) ? $cal_codes[$default_language] : $cal_codes[$current_language];
$cal_lang = "en";
$cal_dateformat = parse_calendardate($app_strings['NTC_DATE_FORMAT']);

$the_form = get_left_form_header($mod_strings['LBL_NEW_FORM_TITLE']);
$the_form .= <<<EOQ

		<form name="EmailSave" onSubmit="return check_form('EmailSave')" method="POST" action="index.php">
			<input type="hidden" name="module" value="Emails">
			<input type="hidden" name="record" value="">
			<input type="hidden" name="action" value="Save">
			<input type="hidden" name="parent_type" value="${default_parent_type}">
			<input type="hidden" name="assigned_user_id" value='${user_id}'>
<p>		$lbl_subject <span class="required">$lbl_required_symbol</span><br>
		<input name='name' type="text"><br>
		$lbl_date <span class="required">$lbl_required_symbol</span>&nbsp;<span class="dateFormat">$ntc_date_format</span><br>
		<input name='date_start' id='jscal_field' type="text" maxlength="10" value="$default_date_start"> <img src="themes/$theme/images/jscalendar.gif" alt="{$app_strings['LBL_ENTER_DATE']}"  id="jscal_trigger" align="absmiddle"><br>
		$lbl_time <span class="required">$lbl_required_symbol</span>&nbsp;<span class="dateFormat">$ntc_time_format</span><br>
		<input name='time_start' maxlength='5' type="text" value="$default_time_start">{$ampm}</p>
<p>		<input title="$lbl_save_button_title" accessKey="$lbl_save_button_key" class="button" type="submit" name="button" value="  $lbl_save_button_label  " ></p>
		</form>
		<script type="text/javascript">
		Calendar.setup ({
			inputField : "jscal_field", ifFormat : "$cal_dateformat", showsTime : false, button : "jscal_trigger", singleClick : true, step : 1
		});
		</script>

EOQ;

$the_form .= get_left_form_footer();
 require_once('include/javascript/javascript.php');
require_once('modules/Emails/Email.php');
$javascript = new javascript();
$javascript->setFormName('EmailSave');
$javascript->setSugarBean(new Email());
$javascript->addRequiredFields('');
$the_form .=$javascript->getScript();

return $the_form;
}

?>
