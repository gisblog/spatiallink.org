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
 * $Id: Forms.php,v 1.31 2005/02/09 07:08:54 andrew Exp $
 * Description:  Contains a variety of utility functions used to display UI
 * components such as form headers and footers.  Intended to be modified on a per
 * theme basis.
 ********************************************************************************/

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
global $theme;
global $current_user;

$lbl_required_symbol = $app_strings['LBL_REQUIRED_SYMBOL'];
$lbl_default_status = $app_list_strings['case_status_default_key'];
$lbl_subject = $mod_strings['LBL_SUBJECT'];
$lbl_save_button_title = $app_strings['LBL_SAVE_BUTTON_TITLE'];
$lbl_save_button_key = $app_strings['LBL_SAVE_BUTTON_KEY'];
$lbl_save_button_label = $app_strings['LBL_SAVE_BUTTON_LABEL'];
$user_id = $current_user->id;




$the_form = get_left_form_header($mod_strings['LBL_NEW_FORM_TITLE']);
$the_form .= <<<EOQ

		<form name="CaseSave" onSubmit="return check_form('CaseSave')" method="POST" action="index.php">
			<input type="hidden" name="module" value="Cases">
			<input type="hidden" name="record" value="">
			<input type="hidden" name="priority" value="P2">
			<input type="hidden" name="status" value="${lbl_default_status}">
			<input type="hidden" name="assigned_user_id" value='${user_id}'>
			<input type="hidden" name="action" value="Save">



		${lbl_subject}&nbsp;<span class="required">${lbl_required_symbol}</span><br>
		<p><input name='name' type="text" size='27' maxlength="255"value=""><br>
EOQ;
global $sugar_config;
if($sugar_config['require_accounts']){
$the_form .= <<<EOQ
		${mod_strings['LBL_ACCOUNT_NAME']}&nbsp;<span class="required">${lbl_required_symbol}</span><br>
		<input name='account_name' type='text' readonly value="" size="16"><input name='account_id' type="hidden" value=''>&nbsp;<input  title="{$app_strings['LBL_CHANGE_BUTTON_TITLE']}" accessKey="{$app_strings['LBL_CHANGE_BUTTON_KEY']}" type="button" class="button" value='{$app_strings['LBL_CHANGE_BUTTON_LABEL']}' name=btn1 LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&html=Popup_picker&form=CaseSave&form_submit=false","","width=600,height=400,resizable=1,scrollbars=1");'></p>
EOQ;
}
$the_form .= <<<EOQ
<p>		<input title="${lbl_save_button_title}" accessKey="${lbl_save_button_key}" class="button" type="submit" name="button" value="  ${lbl_save_button_label}  " ></p>
		
		</form>
EOQ;
require_once('include/javascript/javascript.php');
require_once('modules/Cases/Case.php');
$javascript = new javascript();
$javascript->setFormName('CaseSave');
$javascript->setSugarBean(new aCase());
$javascript->addRequiredFields('');
$the_form .=$javascript->getScript();
$the_form .= get_left_form_footer();

return $the_form;
}

?>
