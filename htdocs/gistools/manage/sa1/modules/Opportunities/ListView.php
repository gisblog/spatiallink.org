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
require_once('modules/Opportunities/Opportunity.php');
require_once('include/utils.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');

require_once('include/ListView/ListView.php');


$header_text = '';
global $app_strings;
global $app_list_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'Opportunities');

global $urlPrefix;

$log = LoggerManager::getLogger('opportunity_list');

global $currentModule;

global $theme;

if (!isset($where)) $where = "";
require_once('modules/MySettings/StoreQuery.php');
$storeQuery = new StoreQuery();
if($_REQUEST['action'] == 'index')
{
	if(!isset($_REQUEST['query'])){
		$storeQuery->loadQuery($currentModule);
		$storeQuery->populateRequest();
	}else{
		$storeQuery->saveFromGet($currentModule);	
	}
}
$seedOpportunity =& new Opportunity();

if(isset($_REQUEST['query']))
{
	// we have a query
	if (isset($_REQUEST['name'])) $name = $_REQUEST['name'];
	if (isset($_REQUEST['account_name'])) $account_name = $_REQUEST['account_name'];
	if (isset($_REQUEST['date_closed'])) $date_closed = $_REQUEST['date_closed'];
	if (isset($_REQUEST['date_start'])) $date_start = $_REQUEST['date_start'];
	if (isset($_REQUEST['amount'])) $amount = $_REQUEST['amount'];
	if (isset($_REQUEST['next_step'])) $next_step = $_REQUEST['next_step'];
	if (isset($_REQUEST['probability'])) $probability = $_REQUEST['probability'];

	if (isset($_REQUEST['lead_source'])) $lead_source = $_REQUEST['lead_source'];
	if (isset($_REQUEST['opportunity_type'])) $opportunity_type = $_REQUEST['opportunity_type'];
	if (isset($_REQUEST['sales_stage'])) $sales_stage = $_REQUEST['sales_stage'];
	if (isset($_REQUEST['current_user_only'])) $current_user_only = $_REQUEST['current_user_only'];
	if (isset($_REQUEST['assigned_user_id'])) $assigned_user_id = $_REQUEST['assigned_user_id'];

	$where_clauses = array();

	if(isset($name) && $name != "") array_push($where_clauses, "opportunities.name like '".PearDatabase::quote($name)."%'");
	if(isset($account_name) && $account_name != "") array_push($where_clauses, "accounts.name like '".PearDatabase::quote($account_name)."%'");
	if(isset($lead_source) && $lead_source == "None") array_push($where_clauses, "opportunities.lead_source = ''");
	elseif(isset($lead_source) && $lead_source != "") array_push($where_clauses, "opportunities.lead_source = '".PearDatabase::quote($lead_source)."'");
	if(isset($opportunity_type) && $opportunity_type != "") array_push($where_clauses, "opportunities.opportunity_type = '".PearDatabase::quote($opportunity_type)."'");
	if(isset($amount) && $amount != "") array_push($where_clauses, "opportunities.amount like '".PearDatabase::quote($amount)."%%'");
	if(isset($next_step) && $next_step != "") array_push($where_clauses, "opportunities.next_step like '".PearDatabase::quote($next_step)."%'");
	if(isset($sales_stage) && $sales_stage == "Other") array_push($where_clauses, "(opportunities.sales_stage <> 'Closed Won' && opportunities.sales_stage <> 'Closed Lost')");
	elseif(isset($sales_stage) && $sales_stage != "") array_push($where_clauses, "opportunities.sales_stage = '".PearDatabase::quote($sales_stage)."'");
	if(isset($probability) && $probability != "") array_push($where_clauses, "opportunities.probability like '".PearDatabase::quote($probability)."%'");
	if(isset($current_user_only) && $current_user_only != "") array_push($where_clauses, "opportunities.assigned_user_id='$current_user->id'");
	if(isset($date_closed) && $date_closed != "" && isset($date_start) && $date_start != "") array_push($where_clauses, "opportunities.date_closed >= '".PearDatabase::quote($date_start)."' and opportunities.date_closed <= '".PearDatabase::quote($date_closed)."'");
	elseif(isset($date_closed) && $date_closed != "") array_push($where_clauses, "opportunities.date_closed like '".PearDatabase::quote($date_closed)."%'");
	
$seedOpportunity->custom_fields->setWhereClauses($where_clauses);

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
			$where .= "opportunities.assigned_user_id IN(";
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
	$search_form=new XTemplate ('modules/Opportunities/SearchForm.html');
	$search_form->assign("MOD", $current_module_strings);
	$search_form->assign("APP", $app_strings);
	$search_form->assign("IMAGE_PATH", $image_path);
	$search_form->assign("ADVANCED_SEARCH_PNG", get_image($image_path.'advanced_search','alt="'.$app_strings['LNK_ADVANCED_SEARCH'].'"  border="0"'));
	$search_form->assign("BASIC_SEARCH_PNG", get_image($image_path.'basic_search','alt="'.$app_strings['LNK_BASIC_SEARCH'].'"  border="0"'));
	if (isset($name)) $search_form->assign("NAME", $name);
	if (isset($account_name)) $search_form->assign("ACCOUNT_NAME", $account_name);
	$search_form->assign("JAVASCRIPT", get_clear_form_js());

	if(isset($current_user_only)) $search_form->assign("CURRENT_USER_ONLY", "checked");
	if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){	
		$header_text = "&nbsp;<a href='index.php?action=index&module=DynamicLayout&from_action=SearchForm&from_module=".$_REQUEST['module'] ."'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>";
	}
	echo get_form_header($current_module_strings['LBL_SEARCH_FORM_TITLE']. $header_text, "", false);
	if (isset($_REQUEST['advanced']) && $_REQUEST['advanced'] == 'true') {
		if (isset($amount)) $search_form->assign("AMOUNT", $amount);
		if (isset($date_entered)) $search_form->assign("DATE_ENTERED", $date_entered);
		if (isset($date_closed)) $search_form->assign("DATE_CLOSED", $date_closed);
		if (isset($next_step)) $search_form->assign("NEXT_STEP", $next_step);
		if (isset($probability)) $search_form->assign("PROBABILITY", $probability);
		if (isset($date_modified)) $search_form->assign("DATE_MODIFIED", $date_modified);
		if (isset($modified_user_id)) $search_form->assign("MODIFIED_USER_ID", $modified_user_id);

		if (isset($lead_source)) $search_form->assign("LEAD_SOURCE_OPTIONS", get_select_options_with_id($app_list_strings['lead_source_dom'], $lead_source));
		else $search_form->assign("LEAD_SOURCE_OPTIONS", get_select_options_with_id($app_list_strings['lead_source_dom'], ''));
		if (isset($opportunity_type)) $search_form->assign("TYPE_OPTIONS", get_select_options_with_id($app_list_strings['opportunity_type_dom'], $opportunity_type));
		else $search_form->assign("TYPE_OPTIONS", get_select_options_with_id($app_list_strings['opportunity_type_dom'], ''));
		$sales_stage_dom = & $app_list_strings['sales_stage_dom'];
		array_unshift($sales_stage_dom, '');
		if (isset($sales_stage)) $search_form->assign("SALES_STAGE_OPTIONS", get_select_options_with_id($app_list_strings['sales_stage_dom'], $sales_stage));
		else $search_form->assign("SALES_STAGE_OPTIONS", get_select_options_with_id($app_list_strings['sales_stage_dom'], ''));

		if (!empty($assigned_user_id)) $search_form->assign("USER_FILTER", get_select_options_with_id(get_user_array(FALSE), $assigned_user_id));
		else $search_form->assign("USER_FILTER", get_select_options_with_id(get_user_array(FALSE), ''));
	        // adding custom fields:
		$seedOpportunity->custom_fields->populateXTPL($search_form, 'search' );

		$search_form->parse("advanced");
		$search_form->out("advanced");
	}
	else {
	        // adding custom fields:
		$seedOpportunity->custom_fields->populateXTPL($search_form, 'search' );
		$search_form->parse("main");
		$search_form->out("main");
	}
	echo get_form_footer();
	echo "\n<BR>\n";
}


$ListView = new ListView();

if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){	
		$header_text = "&nbsp;<a href='index.php?action=index&module=DynamicLayout&from_action=ListView&from_module=".$_REQUEST['module'] ."'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>";
}
$ListView->initNewXTemplate( 'modules/Opportunities/ListView.html',$current_module_strings);
$ListView->setHeaderTitle($current_module_strings['LBL_LIST_FORM_TITLE']. $header_text );
$ListView->setQuery($where, "", "name", "OPPORTUNITY");
$ListView->processListView($seedOpportunity, "main", "OPPORTUNITY");
?>
