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
 * $Id: Forms.php,v 1.9 2005/04/20 02:07:48 joey Exp $
 * Description:  Contains a variety of utility functions used to display UI
 * components such as form headers and footers.  Intended to be modified on a per
 * theme basis.
 ********************************************************************************/


global $theme;
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('XTemplate/xtpl.php');
require_once('include/utils.php');

$log = LoggerManager::getLogger('Campaign-Forms');

$image_path = 'themes/'.$theme.'/images/';


/**
 * Create javascript to validate the data entered into a record.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_validate_record_js () {
global $mod_strings;
global $app_strings;

$err_missing_required_fields = $app_strings['ERR_MISSING_REQUIRED_FIELDS'];

$the_script  = <<<EOQ

<script type="text/javascript" language="Javascript">
<!--  to hide script contents from old browsers

function trim(s) {
	while (s.substring(0,1) == " ") {
		s = s.substring(1, s.length);
	}
	while (s.substring(s.length-1, s.length) == ' ') {
		s = s.substring(0,s.length-1);
	}

	return s;
}

function verify_data(form) {
	var isError = false;
	var errorMessage = "";

	if (isError == true) {
		alert("$err_missing_required_fields" + errorMessage);
		return false;
	}
	return true;
}

// end hiding contents from old browsers  -->
</script>

EOQ;

return $the_script;



}

/**
 * Create HTML form to enter a new record with the minimum necessary fields.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_new_record_form () {
	global $app_strings;
	global $app_list_strings;
	global $mod_strings;
	global $currentModule;
	global $theme;
	global $current_user;

	$the_form = get_left_form_header('New Campaign');
	$form = new XTemplate ('modules/Campaigns/Forms.html');

	require_once('include/TimeDate.php');
	$timedate =& new TimeDate();

	$module_select = empty($_REQUEST['module_select']) ? ''
		: $_REQUEST['module_select'];
	$form->assign('MOD', $mod_strings);
	$form->assign('APP', $app_strings);
	$form->assign('THEME', $theme);
	$form->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js());
	$form->assign("STATUS_OPTIONS", get_select_options_with_id($app_list_strings['campaign_status_dom'], "Planning"));
	$form->assign("TYPE_OPTIONS", get_select_options_with_id($app_list_strings['campaign_type_dom'], ""));

	$form->assign("USER_ID", $current_user->id);





	$form->assign("CALENDAR_LANG", "en");
	$form->assign("USER_DATEFORMAT", '('. $timedate->get_user_date_format().')');
	$form->assign("CALENDAR_DATEFORMAT", $timedate->get_cal_date_format());

	$form->parse('main');
	$the_form .= $form->text('main');

	require_once('modules/Campaigns/Campaign.php');
	$focus =& new Campaign();
	
	require_once('include/javascript/javascript.php');
	$javascript = new javascript();
	$javascript->setFormName('quick_save');
	$javascript->setSugarBean($focus);
	$javascript->addRequiredFields('');
	$jscript = $javascript->getScript();

	$the_form .= $jscript . get_left_form_footer();
	return $the_form;


}

?>
