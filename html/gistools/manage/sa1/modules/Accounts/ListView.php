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

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('modules/Accounts/Account.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('include/ListView/ListView.php');

require_once('include/database/PearDatabase.php');



global $app_strings;
global $app_list_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'Accounts');

global $urlPrefix;

$log = LoggerManager::getLogger('account_list');

global $currentModule;

global $theme;

// focus_list is the means of passing data to a ListView.
global $focus_list;

if (!isset($where)) $where = "";
$seedAccount =& new Account();
require_once('modules/MySettings/StoreQuery.php');
$storeQuery = new StoreQuery();
if(!isset($_REQUEST['query'])){
	$storeQuery->loadQuery($currentModule);
	$storeQuery->populateRequest();
}else{
	$storeQuery->saveFromGet($currentModule);	
}
if(isset($_REQUEST['query']))
{
	// we have a query
	if (isset($_REQUEST['name'])) $name = $_REQUEST['name'];
	if (isset($_REQUEST['website'])) $website = $_REQUEST['website'];
	if (isset($_REQUEST['phone'])) $phone = $_REQUEST['phone'];
	if (isset($_REQUEST['annual_revenue'])) $annual_revenue = $_REQUEST['annual_revenue'];
	if (isset($_REQUEST['email'])) $email = $_REQUEST['email'];
	if (isset($_REQUEST['employees'])) $employees = $_REQUEST['employees'];
	if (isset($_REQUEST['industry'])) $industry = $_REQUEST['industry'];
	if (isset($_REQUEST['ownership'])) $ownership = $_REQUEST['ownership'];
	if (isset($_REQUEST['rating'])) $rating = $_REQUEST['rating'];
	if (isset($_REQUEST['sic_code'])) $sic_code = $_REQUEST['sic_code'];
	if (isset($_REQUEST['ticker_symbol'])) $ticker_symbol = $_REQUEST['ticker_symbol'];
	if (isset($_REQUEST['account_type'])) $account_type = $_REQUEST['account_type'];
	if (isset($_REQUEST['address_street'])) $address_street = $_REQUEST['address_street'];
	if (isset($_REQUEST['address_city'])) $address_city = $_REQUEST['address_city'];
	if (isset($_REQUEST['address_state'])) $address_state = $_REQUEST['address_state'];
	if (isset($_REQUEST['address_country'])) $address_country = $_REQUEST['address_country'];
	if (isset($_REQUEST['address_postalcode'])) $address_postalcode = $_REQUEST['address_postalcode'];
	if (isset($_REQUEST['current_user_only'])) $current_user_only = $_REQUEST['current_user_only'];
	if (isset($_REQUEST['assigned_user_id'])) $assigned_user_id = $_REQUEST['assigned_user_id'];

	$where_clauses = Array();

	if(isset($name) && $name != "") array_push($where_clauses, "accounts.name like '".PearDatabase::quote($name)."%'");
	if(isset($website) && $website != "") array_push($where_clauses, "accounts.website like '".PearDatabase::quote($website)."%'");
	if(isset($phone) && $phone != "") array_push($where_clauses, "(accounts.phone_office like '%".PearDatabase::quote($phone)."%' OR accounts.phone_alternate like '%".PearDatabase::quote($phone)."%' OR accounts.phone_fax like '%".PearDatabase::quote($phone)."%')");
	if(isset($annual_revenue) && $annual_revenue != "") array_push($where_clauses, "accounts.annual_revenue like '".PearDatabase::quote($annual_revenue)."%'");
	if(isset($address_street) && $address_street != "") array_push($where_clauses, "(accounts.billing_address_street like '".PearDatabase::quote($address_street)."%' OR accounts.shipping_address_street like '".PearDatabase::quote($address_street)."%')");
	if(isset($address_city) && $address_city != "") array_push($where_clauses, "(accounts.billing_address_city like '".PearDatabase::quote($address_city)."%' OR accounts.shipping_address_city like '".PearDatabase::quote($address_city)."%')");
	if(isset($address_state) && $address_state != "") array_push($where_clauses, "(accounts.billing_address_state like '".PearDatabase::quote($address_state)."%' OR accounts.shipping_address_state like '".PearDatabase::quote($address_state)."%')");
	if(isset($address_postalcode) && $address_postalcode != "") array_push($where_clauses, "(accounts.billing_address_postalcode like '".PearDatabase::quote($address_postalcode)."%' OR accounts.shipping_address_postalcode like '".PearDatabase::quote($address_postalcode)."%')");
	if(isset($address_country) && $address_country != "") array_push($where_clauses, "(accounts.billing_address_country like '".PearDatabase::quote($address_country)."%' OR accounts.shipping_address_country like '".PearDatabase::quote($address_country)."%')");
	if(isset($email) && $email != "") array_push($where_clauses, "(accounts.email1 like '".PearDatabase::quote($email)."%' OR accounts.email2 like '".PearDatabase::quote($email)."%')");
	if(isset($industry) && $industry != "") array_push($where_clauses, "accounts.industry = '".PearDatabase::quote($industry)."'");
	if(isset($ownership) && $ownership != "") array_push($where_clauses, "accounts.ownership like '".PearDatabase::quote($ownership)."%'");
	if(isset($rating) && $rating != "") array_push($where_clauses, "accounts.rating like '".PearDatabase::quote($rating)."%'");
	if(isset($sic_code) && $sic_code != "") array_push($where_clauses, "accounts.sic_code like '".PearDatabase::quote($sic_code)."%'");
	if(isset($ticker_symbol) && $ticker_symbol != "") array_push($where_clauses, "accounts.ticker_symbol like '".PearDatabase::quote($ticker_symbol)."%'");
	if(isset($account_type) && $account_type != "") array_push($where_clauses, "accounts.account_type = '".PearDatabase::quote($account_type)."'");
	if(isset($current_user_only) && $current_user_only != "") array_push($where_clauses, "accounts.assigned_user_id='$current_user->id'");
require_once('modules/DynamicFields/DynamicField.php');
$seedAccount->setupCustomFields('Accounts');
$seedAccount->custom_fields->setWhereClauses($where_clauses);

	$where = "";
	foreach($where_clauses as $clause)
	{
		if($where != "")
		$where .= " and ";
		$where .= $clause;
	}

	if (isset($assigned_user_id) && is_array($assigned_user_id))
	{
		$count = count($assigned_user_id);
		if ($count > 0 ) {
			if (!empty($where)) {
				$where .= " AND ";
			}
			$where .= "accounts.assigned_user_id IN(";
			foreach ($assigned_user_id as $key => $val) {
				$where .= "'$val'";
				$where .= ($key == count($assigned_user_id) - 1) ? ")" : ", ";
			}
		}
	}

	$log->info("Here is the where clause for the list view: $where");

}

if (!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false') {
	// Stick the form header out there.
	$search_form=new XTemplate ('modules/Accounts/SearchForm.html');
	$search_form->assign("MOD", $current_module_strings);
	$search_form->assign("APP", $app_strings);
	$search_form->assign("IMAGE_PATH", $image_path);
	$search_form->assign("ADVANCED_SEARCH_PNG", get_image($image_path.'advanced_search','alt="'.$app_strings['LNK_ADVANCED_SEARCH'].'"  border="0"'));
	$search_form->assign("BASIC_SEARCH_PNG", get_image($image_path.'basic_search','alt="'.$app_strings['LNK_BASIC_SEARCH'].'"  border="0"'));
	$search_form->assign("JAVASCRIPT", get_clear_form_js());

	if (isset($name)) $search_form->assign("NAME", to_html($name));
	if (isset($website)) $search_form->assign("WEBSITE", to_html($website));
	if (isset($phone)) $search_form->assign("PHONE", to_html($phone));
	if (isset($address_city)) $search_form->assign("ADDRESS_CITY", to_html($address_city));

	if(isset($current_user_only)) $search_form->assign("CURRENT_USER_ONLY", "checked");
		$header_text = '';
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){	
	$header_text = "<a href='index.php?action=index&module=DynamicLayout&from_action=SearchForm&from_module=".$_REQUEST['module'] ."'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>";
	}
	echo get_form_header($current_module_strings['LBL_SEARCH_FORM_TITLE'], $header_text, false);
	if (isset($_REQUEST['advanced']) && $_REQUEST['advanced'] == 'true') {
		if (isset($annual_revenue)) $search_form->assign("ANNUAL_REVENUE", to_html($annual_revenue));
		if (isset($address_street)) $search_form->assign("ADDRESS_STREET", to_html($address_street));
		if (isset($address_city)) $search_form->assign("ADDRESS_CITY", to_html($address_city));
		if (isset($address_state)) $search_form->assign("ADDRESS_STATE", to_html($address_state));
		if (isset($address_country)) $search_form->assign("ADDRESS_COUNTRY", to_html($address_country));
		if (isset($address_postalcode)) $search_form->assign("ADDRESS_POSTALCODE", to_html($address_postalcode));
		if (isset($email)) $search_form->assign("EMAIL", to_html($email));
		if (isset($ownership)) $search_form->assign("OWNERSHIP", to_html($ownership));
		if (isset($rating)) $search_form->assign("RATING", to_html($rating));
		if (isset($sic_code)) $search_form->assign("SIC_CODE", to_html($sic_code));
		if (isset($ticker_symbol)) $search_form->assign("TICKER_SYMBOL", to_html($ticker_symbol));

		if (isset($industry)) $search_form->assign("INDUSTRY_OPTIONS", get_select_options_with_id($app_list_strings['industry_dom'], $industry));
		else $search_form->assign("INDUSTRY_OPTIONS", get_select_options_with_id($app_list_strings['industry_dom'], ''));
		if (isset($account_type)) $search_form->assign("ACCOUNT_TYPE_OPTIONS", get_select_options_with_id($app_list_strings['account_type_dom'], $account_type));
		else $search_form->assign("ACCOUNT_TYPE_OPTIONS", get_select_options_with_id($app_list_strings['account_type_dom'], ''));

		if (!empty($assigned_user_id)) $search_form->assign("USER_FILTER", get_select_options_with_id(get_user_array(FALSE), $assigned_user_id));
		else $search_form->assign("USER_FILTER", get_select_options_with_id(get_user_array(FALSE), ''));
		
                // adding custom fields:
		$seedAccount->custom_fields->populateXTPL($search_form, 'search' );

		$search_form->parse("advanced");
		$search_form->out("advanced");
	}
	else {
                // adding custom fields:
		$seedAccount->custom_fields->populateXTPL($search_form, 'search' );
		$search_form->parse("main");
		$search_form->out("main");
	}
	echo get_form_footer();
	echo "\n<BR>\n";
}



$ListView = new ListView();
$ListView->initNewXTemplate('modules/Accounts/ListView.html',$current_module_strings);
$ListView->setHeaderTitle($current_module_strings['LBL_LIST_FORM_TITLE']);
global $current_user;
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){	
	$ListView->setHeaderText("<a href='index.php?action=index&module=DynamicLayout&from_action=ListView&from_module=".$_REQUEST['module'] ."'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>" );
}
$ListView->setQuery($where, "", "name", "ACCOUNT");
$ListView->processListView($seedAccount, "main", "ACCOUNT");
?>
