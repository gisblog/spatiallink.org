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
 * $Id: ListView.php,v 1.3 2005/04/28 06:10:22 clint Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('modules/Employees/Employee.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('include/ListView/ListView.php');

global $app_strings;
global $app_list_strings;
global $current_language;
global $current_user;
global $urlPrefix;
global $currentModule;
$log = LoggerManager::getLogger('employee_list');

$current_module_strings = return_module_language($current_language, 'Employees');

echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_TITLE'], true);
echo "\n</p>\n";
global $theme;

if (!isset($where)) $where = "";

$seedEmployee =& new Employee();
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
	if (isset($_REQUEST['first_name'])) $first_name = $_REQUEST['first_name'];
	if (isset($_REQUEST['last_name'])) $last_name = $_REQUEST['last_name'];
	if (isset($_REQUEST['user_name'])) $last_name = $_REQUEST['user_name'];
	if (isset($_REQUEST['title'])) $title = $_REQUEST['title'];
	if (isset($_REQUEST['phone'])) $phone = $_REQUEST['phone'];
	if (isset($_REQUEST['department'])) $department = $_REQUEST['department'];
	if (isset($_REQUEST['email'])) $email = $_REQUEST['email'];
	if (isset($_REQUEST['employee_status'])) $employee_status = $_REQUEST['employee_status'];
	if (isset($_REQUEST['address_street'])) $address_street = $_REQUEST['address_street'];
	if (isset($_REQUEST['address_city'])) $address_city = $_REQUEST['address_city'];
	if (isset($_REQUEST['address_state'])) $address_state = $_REQUEST['address_state'];
	if (isset($_REQUEST['address_postalcode'])) $address_postalcode = $_REQUEST['address_postalcode'];
	if (isset($_REQUEST['address_country'])) $address_country = $_REQUEST['address_country'];


	$where_clauses = Array();

	if(!empty($last_name)) array_push($where_clauses, "users.last_name like '".PearDatabase::quote($last_name)."%'");
	if(!empty($first_name)) array_push($where_clauses, "users.first_name like '".PearDatabase::quote($first_name)."%'");
	if(!empty($user_name)) array_push($where_clauses, "users.user_name like '".PearDatabase::quote($user_name)."%'");
	if(!empty($phone)) array_push($where_clauses, "(users.phone_home like '%".PearDatabase::quote($phone)."%' OR users.phone_mobile like '%".PearDatabase::quote($phone)."%' OR users.phone_work like '%".PearDatabase::quote($phone)."%' OR users.phone_other like '%".PearDatabase::quote($phone)."%' OR users.phone_fax like '%".PearDatabase::quote($phone)."%')");
	if(!empty($email)) array_push($where_clauses, "(users.email1 like '".PearDatabase::quote($email)."%' OR users.email2 like '".PearDatabase::quote($email)."%')");
	if(!empty($department)) array_push($where_clauses, "users.department like '".PearDatabase::quote($department)."%'");
	if(!empty($title)) array_push($where_clauses, "users.title like '".PearDatabase::quote($title)."%'");
	if(!empty($address_street)) array_push($where_clauses, "users.address_street like '".PearDatabase::quote($address_street)."%'");
	if(!empty($address_city)) array_push($where_clauses, "users.address_city like '".PearDatabase::quote($address_city)."%'");
	if(!empty($address_state)) array_push($where_clauses, "users.address_state like '".PearDatabase::quote($address_state)."%'");
	if(!empty($address_postalcode)) array_push($where_clauses, "users.address_postalcode like '".PearDatabase::quote($address_postalcode)."%'");
	if(!empty($address_country)) array_push($where_clauses, "users.address_country like '".PearDatabase::quote($address_country)."%'");
	if(!empty($address_country)) array_push($where_clauses, "users.address_country like '".PearDatabase::quote($address_country)."%'");
	if(!empty($employee_status)) array_push($where_clauses, "users.employee_status = '".PearDatabase::quote($employee_status)."'");

	$where = "";
	foreach($where_clauses as $clause)
	{
		if(!empty($where))
		$where .= " and ";
		$where .= $clause;
	}

	$log->info("Here is the where clause for the list view: $where");

}

if (!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false') {
	// Stick the form header out there.
	$search_form=new XTemplate ('modules/Employees/SearchForm.html');
	$search_form->assign("MOD", $current_module_strings);
	$search_form->assign("APP", $app_strings);
	$search_form->assign("IMAGE_PATH", $image_path);
	$search_form->assign("ADVANCED_SEARCH_PNG", get_image($image_path.'advanced_search.png','alt="'.$app_strings['LNK_ADVANCED_SEARCH'].'"  border="0"'));
	$search_form->assign("BASIC_SEARCH_PNG", get_image($image_path.'basic_search.png','alt="'.$app_strings['LNK_BASIC_SEARCH'].'"  border="0"'));
	if (isset($first_name)) $search_form->assign("FIRST_NAME", $_REQUEST['first_name']);
	if (isset($last_name)) $search_form->assign("LAST_NAME", $_REQUEST['last_name']);
	$search_form->assign("JAVASCRIPT", get_clear_form_js());

	$employee_status_list = & $app_list_strings['employee_status_dom'];
	if (!is_admin($current_user)) 
	{
		foreach ($employee_status_list as $key=>$value) {
			if ($key != "Terminated") $employee_status_dom[$key] = $value;
		} 
	}
	else 
	{
		$employee_status_dom = $employee_status_list;
	}	
	array_unshift($employee_status_dom, '');
	if (isset($employee_status)) $search_form->assign("STATUS_OPTIONS", get_select_options_with_id($employee_status_dom, $employee_status));
	else $search_form->assign("STATUS_OPTIONS", get_select_options_with_id($employee_status_dom, ''));

	echo get_form_header($current_module_strings['LBL_SEARCH_FORM_TITLE'], "", false);
	if (isset($_REQUEST['advanced']) && $_REQUEST['advanced'] == 'true') {
		if(isset($title)) $search_form->assign("TITLE", $title);
		if(isset($phone)) $search_form->assign("PHONE", $phone);
		if(isset($email)) $search_form->assign("EMAIL", $email);
		if(isset($department)) $search_form->assign("DEPARTMENT", $department);
		if(isset($address_street)) $search_form->assign("ADDRESS_STREET", $address_street);
		if(isset($address_city)) $search_form->assign("ADDRESS_CITY", $address_city);
		if(isset($address_state)) $search_form->assign("ADDRESS_STATE", $address_state);
		if(isset($address_postalcode)) $search_form->assign("ADDRESS_POSTALCODE", $address_postalcode);
		if(isset($address_country)) $search_form->assign("ADDRESS_COUNTRY", $address_country);

		$search_form->parse("advanced");
		$search_form->out("advanced");
	}
	else {
		$search_form->parse("main");
		$search_form->out("main");
	}
	echo get_form_footer();
	echo "\n<BR>\n";
}


$ListView = new ListView();
$ListView->initNewXTemplate('modules/Employees/ListView.html',$current_module_strings);
$ListView->setHeaderTitle($current_module_strings['LBL_LIST_FORM_TITLE']);
$ListView->setQuery($where, "", "last_name, first_name", "EMPLOYEE");
$ListView->processListView($seedEmployee, "main", "EMPLOYEE");
?>
