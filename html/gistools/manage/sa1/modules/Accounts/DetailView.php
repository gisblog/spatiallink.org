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
 * $Id: DetailView.php,v 1.77 2005/04/20 01:27:12 joey Exp $
 * Description:  TODO To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Accounts/Account.php');
require_once('include/TimeDate.php');
$timedate = new TimeDate();
global $mod_strings;
global $app_strings;
global $app_list_strings;
global $gridline;
$focus =& new Account();
//$focus->set_strings();

if(!empty($_REQUEST['record'])) {
    $result = $focus->retrieve($_REQUEST['record']);
    if($result == null)
    {
    	sugar_die("Error retrieving record.  You may not be authorized to view this record.");
    }
}
else {
	header("Location: index.php?module=Accounts&action=index");
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}
echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->name, true);
echo "\n</p>\n";
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("Account detail view");

$xtpl=new XTemplate ('modules/Accounts/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

$xtpl->assign("THEME", $theme);
$xtpl->assign("GRIDLINE", $gridline);
$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
if ($focus->annual_revenue != '')
{
	$xtpl->assign("ANNUAL_REVENUE", $app_strings['LBL_CURRENCY_SYMBOL'].$focus->annual_revenue);
}
$xtpl->assign("BILLING_ADDRESS_STREET", nl2br($focus->billing_address_street));
if (empty($focus->billing_address_state))
{
	$xtpl->assign("BILLING_ADDRESS_CITY", $focus->billing_address_city);
}
else
{
	$xtpl->assign("BILLING_ADDRESS_CITY", $focus->billing_address_city.', ');
}
$xtpl->assign("BILLING_ADDRESS_STATE", $focus->billing_address_state);
$xtpl->assign("BILLING_ADDRESS_POSTALCODE", $focus->billing_address_postalcode);
$xtpl->assign("BILLING_ADDRESS_COUNTRY", $focus->billing_address_country);
$xtpl->assign("DATE_ENTERED", $focus->date_entered);
$xtpl->assign("ASSIGNED_TO", $focus->assigned_user_name);
$xtpl->assign("DESCRIPTION", nl2br($focus->description));
$xtpl->assign("EMAIL1", $focus->email1);
$xtpl->assign("EMAIL2", $focus->email2);
$xtpl->assign("EMPLOYEES", $focus->employees);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("INDUSTRY", $app_list_strings['industry_dom'][$focus->industry]);
$xtpl->assign("NAME", $focus->name);
$xtpl->assign("OWNERSHIP", $focus->ownership);
$xtpl->assign("PARENT_ID", $focus->parent_id);
$xtpl->assign("PARENT_NAME", $focus->parent_name);

$xtpl->assign("CREATED_BY", $focus->created_by_name);
$xtpl->assign("MODIFIED_BY", $focus->modified_by_name);

$xtpl->assign("PHONE_ALTERNATE", $focus->phone_alternate);
$xtpl->assign("PHONE_FAX", $focus->phone_fax);
$xtpl->assign("PHONE_OFFICE", $focus->phone_office);
$xtpl->assign("RATING", $focus->rating);
$xtpl->assign("SHIPPING_ADDRESS_STREET", nl2br($focus->shipping_address_street));
if (empty($focus->shipping_address_state))
{
	$xtpl->assign("SHIPPING_ADDRESS_CITY", $focus->shipping_address_city);
}
else
{
	$xtpl->assign("SHIPPING_ADDRESS_CITY", $focus->shipping_address_city.', ');
}
$xtpl->assign("SHIPPING_ADDRESS_STATE", $focus->shipping_address_state);
$xtpl->assign("SHIPPING_ADDRESS_COUNTRY", $focus->shipping_address_country);
$xtpl->assign("SHIPPING_ADDRESS_POSTALCODE", $focus->shipping_address_postalcode);
$xtpl->assign("SIC_CODE", $focus->sic_code);
$xtpl->assign("TICKER_SYMBOL", $focus->ticker_symbol);
$xtpl->assign("ACCOUNT_TYPE", $app_list_strings['account_type_dom'][$focus->account_type]);
if ($focus->website != '') $xtpl->assign("WEBSITE", $focus->website);
$xtpl->assign("DATE_MODIFIED", $focus->date_modified);
$xtpl->assign("DATE_ENTERED",$focus->date_entered);

global $current_user;
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){

	$xtpl->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action=".$_REQUEST['action'] ."&from_module=".$_REQUEST['module'] ."&record=".$_REQUEST['record']. "'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>");
}

// adding custom fields:
require_once('modules/DynamicFields/templates/Files/DetailView.php');








$xtpl->parse("main.open_source");





$xtpl->parse("main");
$xtpl->out("main");
$sub_xtpl = $xtpl;
$old_contents = ob_get_contents();
ob_end_clean();


if(array_key_exists('Activities', $modListHeader))
{
if($sub_xtpl->var_exists('subpanel', 'SUBACTIVITIES')){
ob_start();
echo "<p>\n";


// Now get the list of activities that match this account.
$focus_tasks_list = & $focus->get_tasks();
$focus_meetings_list = & $focus->get_meetings();
$focus_calls_list = & $focus->get_calls();
$focus_emails_list = & $focus->get_emails();
$focus_notes_list = & $focus->get_notes();

include('modules/Activities/SubPanelView.php');
echo "</p>\n";
$subactivities =  ob_get_contents();
ob_end_clean();
}
}


if(array_key_exists('Contacts', $modListHeader))
{
if($sub_xtpl->var_exists('subpanel', 'SUBCONTACTS')){
	ob_start();
echo "<p>\n";
// Now get the list of contacts that match this one.
$focus_list = & $focus->get_contacts();
include('modules/Contacts/SubPanelViewContactsAndUsers.php');
$SubPanel = new SubPanelViewContactsAndUsers();
$SubPanel->setFocus($focus);
$SubPanel->setHideUsers(true);
//global 
$contact_mod_strings = return_module_language($current_language, 'Contacts');
$result_array = array_merge($contact_mod_strings,$mod_strings);
$SubPanel->ProcessSubPanelListView('modules/Contacts/SubPanelViewAccounts.html',$result_array, $action);
echo "</p>\n";
$subcontacts =  ob_get_contents();
ob_end_clean();
}
}

if(array_key_exists('Leads', $modListHeader))
{
if($sub_xtpl->var_exists('subpanel', 'SUBLEADS')){
	ob_start();
echo "<p>\n";

// Now get the list of leads that match this one.
$focus_list = & $focus->get_leads();
include('modules/Leads/SubPanelView.php');

echo "</p>\n";
$subleads =  ob_get_contents();
ob_end_clean();
}
}

if(array_key_exists('Opportunities', $modListHeader))
{
if($sub_xtpl->var_exists('subpanel', 'SUBOPPORTUNITIES')){
	ob_start();
echo "<p>\n";

// Now get the list of opportunities that match this one.
$focus_list = & $focus->get_opportunities();
include('modules/Opportunities/SubPanelView.php');

echo "</p>\n";
$subopps =  ob_get_contents();
ob_end_clean();
}
}










































if(array_key_exists('Cases', $modListHeader))
{
if($sub_xtpl->var_exists('subpanel', 'SUBCASES')){
	ob_start();
echo "<p>\n";

// Now get the list of cases that match this one.
$focus_list = & $focus->get_cases();
include('modules/Cases/SubPanelView.php');

echo "</p>\n";
$subcases =  ob_get_contents();
ob_end_clean();
}
}

if(array_key_exists('Bugs', $modListHeader))
{
if($sub_xtpl->var_exists('subpanel', 'SUBBUGS')){
	ob_start();
echo "<p>\n";

$focus_list = & $focus->get_bugs();
include('modules/Bugs/SubPanelView.php');

echo "</p>\n";
$subbugs =  ob_get_contents();
ob_end_clean();
}
}

if(array_key_exists('Accounts', $modListHeader))
{
if($sub_xtpl->var_exists('subpanel', 'SUBACCOUNTS')){
	ob_start();
echo "<p>\n";

// Now get the list of member accounts that match this one.
$focus_list = & $focus->get_member_accounts();
include('modules/Accounts/SubPanelView.php');
echo "</p>\n";
$subaccounts =  ob_get_contents();
ob_end_clean();
}
}

if(array_key_exists('Project', $modListHeader))
{
	if($sub_xtpl->var_exists('subpanel', 'SUB_PROJECT'))
	{
		ob_start();
		echo "<p>\n";

		// Now get the list of member accounts that match this one.
		$focus_list = & $focus->get_projects();
		include('modules/Project/SubPanelView.php');
		echo "</p>\n";
		$sub_project =  ob_get_contents();
		ob_end_clean();
	}
}

ob_start();
echo $old_contents;

if(!empty($subactivities))$sub_xtpl->assign('SUBACTIVITIES', $subactivities);
if(!empty($subcontacts))$sub_xtpl->assign('SUBCONTACTS', $subcontacts);
if(!empty($subopps))$sub_xtpl->assign('SUBOPPORTUNITIES', $subopps);
if(!empty($subleads))$sub_xtpl->assign('SUBLEADS', $subleads);
if(!empty($subcases))$sub_xtpl->assign('SUBCASES', $subcases);





if(!empty($subaccounts))$sub_xtpl->assign('SUBACCOUNTS', $subaccounts);
if(!empty($subbugs))$sub_xtpl->assign('SUBBUGS', $subbugs);
if(!empty($sub_project))$sub_xtpl->assign('SUB_PROJECT', $sub_project);

$sub_xtpl->parse("subpanel");
$sub_xtpl->out("subpanel");


?>
