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
 */

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('modules/Campaigns/Campaign.php');
require_once('include/utils.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');

require_once('include/ListView/ListView.php');


$header_text = '';
global $app_strings;
global $app_list_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'Campaigns');

global $urlPrefix;

$log = LoggerManager::getLogger('campaign_list');

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
$seedCampaigns =& new Campaign();

if(isset($_REQUEST['query']))
{
	// we have a query
	if (isset($_REQUEST['name'])) $name = $_REQUEST['name'];
	if (isset($_REQUEST['campaign_type'])) $campaign_type = $_REQUEST['campaign_type'];
	if (isset($_REQUEST['status'])) $status = $_REQUEST['status'];
	if (isset($_REQUEST['current_user_only'])) $current_user_only = $_REQUEST['current_user_only'];
	if (isset($_REQUEST['assigned_user_id'])) $assigned_user_id = $_REQUEST['assigned_user_id'];

	$where_clauses = array();

	if(isset($name) && $name != "") array_push($where_clauses, "campaigns.name like '".PearDatabase::quote($name)."%'");
	if(isset($campaign_type) && $campaign_type != "") array_push($where_clauses, "campaigns.campaign_type = '".PearDatabase::quote($campaign_type)."'");
	if(isset($status) && $status != "") array_push($where_clauses, "campaigns.status = '".PearDatabase::quote($status)."'");
	
	if(isset($current_user_only) && $current_user_only != "") array_push($where_clauses, "campaigns.assigned_user_id='$current_user->id'");

	$seedCampaigns->custom_fields->setWhereClauses($where_clauses);

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
			$where .= "campaigns.assigned_user_id IN(";
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
	$search_form=new XTemplate ('modules/Campaigns/SearchForm.html');
	$search_form->assign("MOD", $current_module_strings);
	$search_form->assign("APP", $app_strings);
	$search_form->assign("IMAGE_PATH", $image_path);
	$search_form->assign("ADVANCED_SEARCH_PNG", get_image($image_path.'advanced_search','alt="'.$app_strings['LNK_ADVANCED_SEARCH'].'"  border="0"'));
	$search_form->assign("BASIC_SEARCH_PNG", get_image($image_path.'basic_search','alt="'.$app_strings['LNK_BASIC_SEARCH'].'"  border="0"'));
	if (isset($name)) $search_form->assign("NAME", $name);
	$search_form->assign("JAVASCRIPT", get_clear_form_js());

	if(isset($current_user_only)) $search_form->assign("CURRENT_USER_ONLY", "checked");
	if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){	
		$header_text = "&nbsp;<a href='index.php?action=index&module=DynamicLayout&from_action=SearchForm&from_module=".$_REQUEST['module'] ."'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>";
	}
	echo get_form_header($current_module_strings['LBL_SEARCH_FORM_TITLE']. $header_text, "", false);
	if (isset($_REQUEST['advanced']) && $_REQUEST['advanced'] == 'true') {

		if (isset($campaign_type)) $search_form->assign("TYPE_OPTIONS", get_select_options_with_id($app_list_strings['campaign_type_dom'], $campaign_type));
		else $search_form->assign("TYPE_OPTIONS", get_select_options_with_id($app_list_strings['campaign_type_dom'], ''));

		if (isset($campaign_status)) $search_form->assign("STATUS_OPTIONS", get_select_options_with_id($app_list_strings['campaign_status_dom'], $campaign_status_type));
		else $search_form->assign("STATUS_OPTIONS", get_select_options_with_id($app_list_strings['campaign_status_dom'], ''));

		if (!empty($assigned_user_id)) $search_form->assign("USER_FILTER", get_select_options_with_id(get_user_array(FALSE), $assigned_user_id));
		else $search_form->assign("USER_FILTER", get_select_options_with_id(get_user_array(FALSE), ''));
	        // adding custom fields:
		$seedCampaigns->custom_fields->populateXTPL($search_form, 'search' );

		$search_form->parse("advanced");
		$search_form->out("advanced");
	}
	else {
	        // adding custom fields:
		$seedCampaigns->custom_fields->populateXTPL($search_form, 'search' );
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
$ListView->initNewXTemplate( 'modules/Campaigns/ListView.html',$current_module_strings);
$ListView->setHeaderTitle($current_module_strings['LBL_LIST_FORM_TITLE']. $header_text );
$ListView->setQuery($where, "", "name", "CAMPAIGN");
$ListView->processListView($seedCampaigns, "main", "CAMPAIGN");
?>
