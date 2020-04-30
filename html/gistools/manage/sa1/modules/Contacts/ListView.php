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
 * $Id: ListView.php,v 1.70 2005/04/14 18:03:43 lam Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('modules/Contacts/Contact.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');

require_once('include/ListView/ListView.php');



global $app_strings;
global $app_list_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'Contacts');

global $urlPrefix;

$log = LoggerManager::getLogger('contact_list');

global $currentModule;

global $theme;

if (!isset($where)) $where = "";

$seedContact =& new Contact();
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
	if (isset($_REQUEST['account_name'])) $account_name = $_REQUEST['account_name'];
	if (isset($_REQUEST['lead_source'])) $lead_source = $_REQUEST['lead_source'];
	if (isset($_REQUEST['do_not_call'])) $do_not_call = $_REQUEST['do_not_call'];
	if (isset($_REQUEST['phone'])) $phone = $_REQUEST['phone'];
	if (isset($_REQUEST['email'])) $email = $_REQUEST['email'];
	if (isset($_REQUEST['assistant'])) $assistant = $_REQUEST['assistant'];
	if (isset($_REQUEST['email_opt_out'])) $email_opt_out = $_REQUEST['email_opt_out'];
	if (isset($_REQUEST['address_street'])) $address_street = $_REQUEST['address_street'];
	if (isset($_REQUEST['address_city'])) $address_city = $_REQUEST['address_city'];
	if (isset($_REQUEST['address_state'])) $address_state = $_REQUEST['address_state'];
	if (isset($_REQUEST['address_postalcode'])) $address_postalcode = $_REQUEST['address_postalcode'];
	if (isset($_REQUEST['address_country'])) $address_country = $_REQUEST['address_country'];
	if (isset($_REQUEST['current_user_only'])) $current_user_only = $_REQUEST['current_user_only'];
	if (isset($_REQUEST['assigned_user_id'])) $assigned_user_id = $_REQUEST['assigned_user_id'];


	$where_clauses = Array();


	if(isset($last_name) && $last_name != "") array_push($where_clauses, "contacts.last_name like '".PearDatabase::quote($last_name)."%'");
	if(isset($first_name) && $first_name != "")	array_push($where_clauses, "contacts.first_name like '".PearDatabase::quote($first_name)."%'");
	if(isset($account_name) && $account_name != "")	array_push($where_clauses, "accounts.name like '".PearDatabase::quote($account_name)."%'");
	if(isset($lead_source) && $lead_source != "") array_push($where_clauses, "contacts.lead_source = '".PearDatabase::quote($lead_source)."'");
	if(isset($do_not_call) && $do_not_call != "") array_push($where_clauses, "contacts.do_not_call = '".PearDatabase::quote($do_not_call)."'");
	if(isset($phone) && $phone != "") array_push($where_clauses, "(contacts.phone_home like '%".PearDatabase::quote($phone)."%' OR contacts.phone_mobile like '%".PearDatabase::quote($phone)."%' OR contacts.phone_work like '%".PearDatabase::quote($phone)."%' OR contacts.phone_other like '%".PearDatabase::quote($phone)."%' OR contacts.phone_fax like '%".PearDatabase::quote($phone)."%' OR contacts.assistant_phone like '%".PearDatabase::quote($phone)."%')");
	if(isset($email) && $email != "") array_push($where_clauses, "(contacts.email1 like '".PearDatabase::quote($email)."%' OR contacts.email2 like '".PearDatabase::quote($email)."%')");
	if(isset($assistant) && $assistant != "") array_push($where_clauses, "contacts.assistant like '".PearDatabase::quote($assistant)."%'");
	if(isset($email_opt_out) && $email_opt_out != "") array_push($where_clauses, "contacts.email_opt_out = '".PearDatabase::quote($email_opt_out)."'");
	if(isset($address_street) && $address_street != "") array_push($where_clauses, "(contacts.primary_address_street like '".PearDatabase::quote($address_street)."%' OR contacts.alt_address_street like '".PearDatabase::quote($address_street)."%')");
	if(isset($address_city) && $address_city != "") array_push($where_clauses, "(contacts.primary_address_city like '".PearDatabase::quote($address_city)."%' OR contacts.alt_address_city like '".PearDatabase::quote($address_city)."%')");
	if(isset($address_state) && $address_state != "") array_push($where_clauses, "(contacts.primary_address_state like '".PearDatabase::quote($address_state)."%' OR contacts.alt_address_state like '".PearDatabase::quote($address_state)."%')");
	if(isset($address_postalcode) && $address_postalcode != "") array_push($where_clauses, "(contacts.primary_address_postalcode like '".PearDatabase::quote($address_postalcode)."%' OR contacts.alt_address_postalcode like '".PearDatabase::quote($address_postalcode)."%')");
	if(isset($address_country) && $address_country != "") array_push($where_clauses, "(contacts.primary_address_country like '".PearDatabase::quote($address_country)."%' OR contacts.alt_address_country like '".PearDatabase::quote($address_country)."%')");
	if(isset($current_user_only) && $current_user_only != "") array_push($where_clauses, "contacts.assigned_user_id='$current_user->id'");

$seedContact->custom_fields->setWhereClauses($where_clauses);



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
			$where .= "contacts.assigned_user_id IN(";
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
	$search_form=new XTemplate ('modules/Contacts/SearchForm.html');
	$search_form->assign("MOD", $current_module_strings);
	$search_form->assign("APP", $app_strings);
	$search_form->assign("IMAGE_PATH", $image_path);
	$search_form->assign("ADVANCED_SEARCH_PNG", get_image($image_path.'advanced_search','alt="'.$app_strings['LNK_ADVANCED_SEARCH'].'"  border="0"'));
	$search_form->assign("BASIC_SEARCH_PNG", get_image($image_path.'basic_search','alt="'.$app_strings['LNK_BASIC_SEARCH'].'"  border="0"'));
	if (isset($first_name)) $search_form->assign("FIRST_NAME", $_REQUEST['first_name']);
	if (isset($last_name)) $search_form->assign("LAST_NAME", $_REQUEST['last_name']);
	if (isset($account_name)) $search_form->assign("ACCOUNT_NAME", $_REQUEST['account_name']);
	$search_form->assign("JAVASCRIPT", get_clear_form_js());
	$header_text = '';
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){
	$header_text = "<a href='index.php?action=index&module=DynamicLayout&from_action=SearchForm&from_module=".$_REQUEST['module'] ."'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>";
	}
	echo get_form_header($current_module_strings['LBL_SEARCH_FORM_TITLE'], $header_text, false);

	if(isset($current_user_only)) $search_form->assign("CURRENT_USER_ONLY", "checked");

	if (isset($_REQUEST['advanced']) && $_REQUEST['advanced'] == 'true') {
		if(isset($account_name)) $search_form->assign("ACCOUNT_NAME", $account_name);
		if(isset($date_entered)) $search_form->assign("DATE_ENTERED", $date_entered);
		if(isset($date_modified)) $search_form->assign("DATE_MODIFIED", $date_modified);
		if(isset($modified_user_id)) $search_form->assign("MODIFIED_USER_ID", $modified_user_id);
		if(isset($do_not_call)) $search_form->assign("DO_NOT_CALL", $do_not_call);
		if(isset($phone)) $search_form->assign("PHONE", $phone);
		if(isset($email)) $search_form->assign("EMAIL", $email);
		if(isset($assistant)) $search_form->assign("ASSISTANT", $assistant);
		if(isset($email_opt_out)) $search_form->assign("EMAIL_OPT_OUT", $email_opt_out);
		if(isset($address_street)) $search_form->assign("ADDRESS_STREET", $address_street);
		if(isset($address_city)) $search_form->assign("ADDRESS_CITY", $address_city);
		if(isset($address_state)) $search_form->assign("ADDRESS_STATE", $address_state);
		if(isset($address_postalcode)) $search_form->assign("ADDRESS_POSTALCODE", $address_postalcode);
		if(isset($address_country)) $search_form->assign("ADDRESS_COUNTRY", $address_country);

		if (isset($lead_source)) $search_form->assign("LEAD_SOURCE_OPTIONS", get_select_options_with_id($app_list_strings['lead_source_dom'], $lead_source));
		else $search_form->assign("LEAD_SOURCE_OPTIONS", get_select_options_with_id($app_list_strings['lead_source_dom'], ''));

		if (!empty($assigned_user_id)) $search_form->assign("USER_FILTER", get_select_options_with_id(get_user_array(FALSE), $assigned_user_id));
		else $search_form->assign("USER_FILTER", get_select_options_with_id(get_user_array(FALSE), ''));

		$seedContact->custom_fields->populateXTPL($search_form, 'search' );

		$search_form->parse("advanced");
		$search_form->out("advanced");
	}
	else {
		// adding custom fields:
		$seedContact->custom_fields->populateXTPL($search_form, 'search' );

		$search_form->parse("main");
		$search_form->out("main");
	}
	echo get_form_footer();
	echo "\n<BR>\n";
}


$ListView = new ListView();

$ListView->initNewXTemplate( 'modules/Contacts/ListView.html',$current_module_strings);
$ListView->setHeaderTitle($current_module_strings['LBL_LIST_FORM_TITLE'] );
global $current_user;
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){
	$ListView->setHeaderText("<a href='index.php?action=index&module=DynamicLayout&from_action=ListView&from_module=".$_REQUEST['module'] ."'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>" );
}
$ListView->setQuery($where, "", "last_name, first_name", "CONTACT");
$ListView->processListView($seedContact, "main", "CONTACT");
?>
