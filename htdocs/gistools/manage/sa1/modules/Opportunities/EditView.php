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
 * $Id: EditView.php,v 1.57 2005/04/14 18:03:43 lam Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Opportunities/Opportunity.php');
require_once('modules/Opportunities/Forms.php');
require_once('include/TimeDate.php');
$timedate =& new TimeDate();
global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_user;
// Unimplemented until jscalendar language files are fixed
// global $current_language;
// global $default_language;
// global $cal_codes;

$focus =& new Opportunity();

if(isset($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}


//needed when creating a new opportunity with a default account value passed in
if (isset($_REQUEST['account_name']) && is_null($focus->account_name)) {
	$focus->account_name = urldecode($_REQUEST['account_name']);

}
if (isset($_REQUEST['account_id']) && is_null($focus->account_id)) {
	$focus->account_id = $_REQUEST['account_id'];
}
if (isset($_REQUEST['contact_id']) && is_null($focus->contact_id)) {
	$focus->contact_id = $_REQUEST['contact_id'];
}

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("Opportunity detail view");
echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->name, true);
echo "\n</p>\n";
$xtpl=new XTemplate ('modules/Opportunities/EditView.html');
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$xtpl->assign("DUPLICATE_PARENT_ID", $focus->id);	
	$focus->id = "";
}
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

// Unimplemented until jscalendar language files are fixed
// $xtpl->assign("CALENDAR_LANG", ((empty($cal_codes[$current_language])) ? $cal_codes[$default_language] : $cal_codes[$current_language]));
$xtpl->assign("CALENDAR_LANG", "en");
$xtpl->assign("USER_DATEFORMAT", '('. $timedate->get_user_date_format().')');
$xtpl->assign("CALENDAR_DATEFORMAT", $timedate->get_cal_date_format());

if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("JAVASCRIPT", get_set_focus_js());
$xtpl->assign("ID", $focus->id);
$xtpl->assign("ACCOUNT_NAME", $focus->account_name);
$xtpl->assign("ACCOUNT_ID", $focus->account_id);
$xtpl->assign("CONTACT_ID", $focus->contact_id);
if (isset($focus->name)) $xtpl->assign("NAME", $focus->name);
else $xtpl->assign("NAME", "");
$xtpl->assign("AMOUNT", $focus->amount);
$xtpl->assign("DATE_ENTERED", $focus->date_entered);
$xtpl->assign("DATE_CLOSED", $focus->date_closed);
$xtpl->assign("NEXT_STEP", $focus->next_step);
$xtpl->assign("PROBABILITY", $focus->probability);
$xtpl->assign("DESCRIPTION", $focus->description);

if ($focus->assigned_user_id == '' && (!isset($focus->id) || $focus->id=0)) $focus->assigned_user_id = $current_user->id;
$xtpl->assign("ASSIGNED_USER_OPTIONS", get_select_options_with_id(get_user_array(TRUE, "Active", $focus->assigned_user_id), $focus->assigned_user_id));
$xtpl->assign("LEAD_SOURCE_OPTIONS", get_select_options_with_id($app_list_strings['lead_source_dom'], $focus->lead_source));
$xtpl->assign("TYPE_OPTIONS", get_select_options_with_id($app_list_strings['opportunity_type_dom'], $focus->opportunity_type));
$xtpl->assign("SALES_STAGE_OPTIONS", get_select_options_with_id($app_list_strings['sales_stage_dom'], $focus->sales_stage));
global $current_user;
require_once('modules/Currencies/ListCurrency.php');
$currency = new ListCurrency();
if(isset($focus->currency_id) && !empty($focus->currency_id)){
	$selectCurrency = $currency->getSelectOptions($focus->currency_id);
	$xtpl->assign("CURRENCY", $selectCurrency);
}
else if($current_user->getPreference('currency') && !isset($focus->id))
{
	$selectCurrency = $currency->getSelectOptions($current_user->getPreference('currency'));
	$xtpl->assign("CURRENCY", $selectCurrency);
}else{

	$selectCurrency = $currency->getSelectOptions();
	$xtpl->assign("CURRENCY", $selectCurrency);

}
global $current_user;
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){	
	$record = '';
	if(!empty($_REQUEST['record'])){
		$record = 	$_REQUEST['record'];
	}
	$xtpl->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action=".$_REQUEST['action'] ."&from_module=".$_REQUEST['module'] ."&record=".$record. "'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>");	
}

//Add Custom Fields
require_once('modules/DynamicFields/templates/Files/EditView.php');











$xtpl->parse("main.open_source");




echo $currency->getJavascript();


$xtpl->parse("main");
$xtpl->out("main");
require_once('include/javascript/javascript.php');
$javascript = new javascript();
$javascript->setFormName('EditView');
$javascript->setSugarBean($focus);
$javascript->addAllFields('');
echo $javascript->getScript();


?>
