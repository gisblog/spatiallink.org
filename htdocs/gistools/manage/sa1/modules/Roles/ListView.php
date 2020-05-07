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
require_once('modules/Roles/Role.php');
require_once('include/utils.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('include/ListView/ListView.php');


$header_text = '';
global $app_strings;
global $app_list_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'Roles');

global $urlPrefix;

$log = LoggerManager::getLogger('role');

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
$seedRole =& new Role();

if(isset($_REQUEST['query']))
{
	// we have a query
	if (isset($_REQUEST['name'])) $name = $_REQUEST['name'];

	$where_clauses = array();

	if(isset($name) && $name != "") array_push($where_clauses, "roles.name like '".PearDatabase::quote($name)."%'");
	
	$seedRole->custom_fields->setWhereClauses($where_clauses);

	$where = "";
	foreach($where_clauses as $clause)
	{
		if($where != "")
		$where .= " and ";
		$where .= $clause;
	}

	$log->info("Here is the where clause for the list view: $where");
}

if (!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false') {
	// Stick the form header out there.
	$search_form=new XTemplate ('modules/Roles/SearchForm.html');
	$search_form->assign("MOD", $current_module_strings);
	$search_form->assign("APP", $app_strings);
	$search_form->assign("IMAGE_PATH", $image_path);
	if (isset($name)) $search_form->assign("NAME", $name);
	$search_form->assign("JAVASCRIPT", get_clear_form_js());

	echo get_form_header($current_module_strings['LBL_SEARCH_FORM_TITLE']. $header_text, "", false);
	$search_form->parse("main");
	$search_form->out("main");
	echo get_form_footer();
	echo "\n<BR>\n";
}


$ListView = new ListView();

$ListView->initNewXTemplate( 'modules/Roles/ListView.html',$current_module_strings);
$ListView->setHeaderTitle($current_module_strings['LBL_LIST_FORM_TITLE']. $header_text );
$ListView->setQuery($where, "", "name", "ROLE");
$ListView->processListView($seedRole, "main", "ROLE");
?>
