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
 * $Id: ListView.php,v 1.4 2005/04/29 19:21:55 ajay Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('modules/Documents/Document.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('include/ListView/ListView.php');

global $app_strings;
global $app_list_strings;
global $current_language;
global $current_user;
global $urlPrefix;
global $currentModule;

$log = LoggerManager::getLogger('document_list');

$current_module_strings = return_module_language($current_language, 'Documents');

echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_TITLE'], true);
echo "\n</p>\n";
global $theme;

if(isset($_REQUEST['query']))
{
	// we have a query
	if (isset($_REQUEST['document_name'])) $document_name = $_REQUEST['document_name'];
	if (isset($_REQUEST['category_id'])) $category_id = $_REQUEST['category_id'];
	if (isset($_REQUEST['subcategory_id'])) $subcategory_id = $_REQUEST['subcategory_id'];
	if (isset($_REQUEST['active_date'])) $active_date = $_REQUEST['active_date'];
	if (isset($_REQUEST['exp_date'])) $exp_date = $_REQUEST['exp_date'];

	$where_clauses = Array();

	if(isset($document_name) && $document_name != "") array_push($where_clauses, "document_name like '".PearDatabase::quote($document_name)."%'");
	if(isset($category_id) && $category_id != "") array_push($where_clauses, "category_id like '".PearDatabase::quote($category_id)."%'");
	if(isset($subcategory_id) && $subcategory_id != "") array_push($where_clauses, "subcategory_id like '".PearDatabase::quote($subcategory_id)."%'");
	if(isset($active_date) && $active_date != "") array_push($where_clauses, "active_date like '".PearDatabase::quote($active_date)."%'");
	if(isset($exp_date) && $exp_date != "") array_push($where_clauses, "exp_date like '".PearDatabase::quote($exp_date)."%'");

	$where = "";
	foreach($where_clauses as $clause)
	{
		if($where != "")
		$where .= " and ";
		$where .= $clause;
	}

	$log->info("Here is the where clause for the list view: $where");
}

if (!isset($where)) $where = "";
$seedTimePeriod = new Document();
require_once('modules/MySettings/StoreQuery.php');
$storeQuery = new StoreQuery();
if(!isset($_REQUEST['query'])){
	$storeQuery->loadQuery($currentModule);
	$storeQuery->populateRequest();
}else{
	$storeQuery->saveFromGet($currentModule);	
}

if (!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false') {

	if (isset($_REQUEST['document_name'])) $document_name = $_REQUEST['document_name'];
	if (isset($_REQUEST['category_id'])) $category_id = $_REQUEST['category_id'];
	if (isset($_REQUEST['subcategory_id'])) $subcategory_id = $_REQUEST['subcategory_id'];
	if (isset($_REQUEST['active_date'])) $active_date = $_REQUEST['active_date'];
	if (isset($_REQUEST['exp_date'])) $exp_date = $_REQUEST['exp_date'];


	// Stick the form header out there.
	$search_form=new XTemplate ('modules/Documents/SearchForm.html');
	$search_form->assign("MOD", $current_module_strings);
	$search_form->assign("APP", $app_strings);
	$search_form->assign("IMAGE_PATH", $image_path);
	$search_form->assign("ADVANCED_SEARCH_PNG", get_image($image_path.'advanced_search','alt="'.$app_strings['LNK_ADVANCED_SEARCH'].'"  border="0"'));
	$search_form->assign("BASIC_SEARCH_PNG", get_image($image_path.'basic_search','alt="'.$app_strings['LNK_BASIC_SEARCH'].'"  border="0"'));

	if (isset($document_name)) $search_form->assign("DOCUMENT_NAME", $document_name);

	if (isset($category_id)) $search_form->assign("CATEGORY_OPTIONS", get_select_options_with_id($app_list_strings['document_category_dom'], $category_id));
	else $search_form->assign("CATEGORY_OPTIONS", get_select_options_with_id($app_list_strings['document_category_dom'], ''));

	if (isset($subcategory_id)) $search_form->assign("SUBCATEGORY_OPTIONS", get_select_options_with_id($app_list_strings['document_subcategory_dom'], $subcategory_id));
	else $search_form->assign("SUBCATEGORY_OPTIONS", get_select_options_with_id($app_list_strings['document_subcategory_dom'], ''));

	$search_form->assign("JAVASCRIPT", get_clear_form_js());

	echo get_form_header($current_module_strings['LBL_SEARCH_FORM_TITLE'], "", false);
	if (isset($_REQUEST['advanced']) && $_REQUEST['advanced'] == 'true') {
		if(isset($active_date)) $search_form->assign("ACTIVEDATE", $active_date);
		if(isset($exp_date)) $search_form->assign("EXP_DATE", $exp_date);
		
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
$ListView->initNewXTemplate('modules/Documents/ListView.html',$current_module_strings);
$ListView->setHeaderTitle($current_module_strings['LBL_LIST_FORM_TITLE']);
$ListView->setQuery($where, "", "document_name", "DOCUMENT");
$ListView->processListView($seedTimePeriod, "main", "DOCUMENT");
?>
