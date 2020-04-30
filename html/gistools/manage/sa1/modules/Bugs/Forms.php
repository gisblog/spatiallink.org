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
 * $Id: Forms.php,v 1.7 2005/04/29 04:40:34 lam Exp $
 * Description:  Contains a variety of utility functions used to display UI
 * components such as form headers and footers.  Intended to be modified on a per
 * theme basis.
 ********************************************************************************/

require_once("modules/Releases/Release.php");

/**
 * Create javascript to validate the data entered into a record.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_validate_record_js () {

}

/**
 * Create HTML form to enter a new record with the minimum necessary fields.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_new_record_form () {
global $mod_strings;
global $app_strings;
global $app_list_strings;
global $mod_strings;
global $theme;
global $current_user;

$seedRelease = new Release();

$lbl_required_symbol = $app_strings['LBL_REQUIRED_SYMBOL'];
$lbl_default_status = $app_list_strings['bug_status_default_key'];

$lbl_subject = $mod_strings['LBL_SUBJECT'];
$lbl_save_button_title = $app_strings['LBL_SAVE_BUTTON_TITLE'];
$lbl_save_button_key = $app_strings['LBL_SAVE_BUTTON_KEY'];
$lbl_save_button_label = $app_strings['LBL_SAVE_BUTTON_LABEL'];
$user_id = $current_user->id;



$priority_options =get_select_options_with_id($app_list_strings['bug_priority_dom'], $app_list_strings['bug_priority_default_key']);
$release_options = get_select_options_with_id($seedRelease->get_releases(TRUE, "Active"), "");
$type_options = get_select_options_with_id($app_list_strings['bug_type_dom'],$app_list_strings['bug_type_default_key']);
$the_form = get_left_form_header($mod_strings['LBL_NEW_FORM_TITLE']);
$the_form .= <<<EOQ

		<form name="BugSave" onSubmit="return check_form('BugSave')" method="POST" action="index.php">
			<input type="hidden" name="module" value="Bugs">
			<input type="hidden" name="record" value="">

			<input type="hidden" name="status" value="${lbl_default_status}">
			<input type="hidden" name="assigned_user_id" value='${user_id}'>
			<input type="hidden" name="action" value="Save">




		${lbl_subject}&nbsp;<span class="required">${lbl_required_symbol}</span><br>
		<p><input name='name' type="text" size='20' maxlength="255"value=""><br>
 		${mod_strings['LBL_TYPE']}&nbsp;<br>
		<select name='type' >$type_options</select><br>
		${mod_strings['LBL_RELEASE']}&nbsp;<br>
		<select name='release' >$release_options</select><br>
		${mod_strings['LBL_PRIORITY']}&nbsp;<br>
		<select name='priority' >$priority_options</select>
</p><p>		<input title="${lbl_save_button_title}" accessKey="${lbl_save_button_key}" class="button" type="submit" name="button" value="  ${lbl_save_button_label}  " ></p>

		</form>
EOQ;
require_once('include/javascript/javascript.php');
require_once('modules/Bugs/Bug.php');
$javascript = new javascript();
$javascript->setFormName('BugSave');
$javascript->setSugarBean(new Bug());
$javascript->addRequiredFields('');
$the_form .=$javascript->getScript();
$the_form .= get_left_form_footer();

return $the_form;
}

?>
