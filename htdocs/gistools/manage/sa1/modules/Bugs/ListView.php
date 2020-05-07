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
 * $Id: ListView.php,v 1.18 2005/04/27 02:35:52 majed Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('modules/Bugs/Bug.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');

require_once('include/ListView/ListView.php');

require_once("modules/Releases/Release.php");

$header_text = '';

global $app_strings;
global $mod_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'Bugs');

global $urlPrefix;

$log = LoggerManager::getLogger('bug_list');

global $currentModule;

global $theme;

$seedRelease =& new Release();

if (!isset($where)) $where = "";

$seedBug =& new Bug();
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
	if (isset($_REQUEST['status'])) $status = $_REQUEST['status'];
	if (isset($_REQUEST['priority'])) $priority = $_REQUEST['priority'];
	if (isset($_REQUEST['release'])) $release = $_REQUEST['release'];
	if (isset($_REQUEST['resolution'])) $resolution = $_REQUEST['resolution'];
	if (isset($_REQUEST['number'])) $number = $_REQUEST['number'];
	if (isset($_REQUEST['current_user_only'])) $current_user_only = $_REQUEST['current_user_only'];
	if (isset($_REQUEST['assigned_user_id'])) $assigned_user_id = $_REQUEST['assigned_user_id'];
	if (isset($_REQUEST['type'])) $type = $_REQUEST['type'];

	$where_clauses = Array();

	if(isset($name) && $name != "") array_push($where_clauses, "bugs.name like '%".PearDatabase::quote($name)."%'");
	if(isset($status) && $status != "") array_push($where_clauses, "bugs.status = '".PearDatabase::quote($status)."'");
	if(isset($priority) && $priority != "") array_push($where_clauses, "bugs.priority = '".PearDatabase::quote($priority)."'");
	if(isset($release) && $release != "") array_push($where_clauses, "bugs.release = '".PearDatabase::quote($release)."'");
	if(isset($resolution) && $resolution != "") array_push($where_clauses, "bugs.resolution = '".PearDatabase::quote($resolution)."'");
	if(isset($number) && $number != "") array_push($where_clauses, "bugs.number like '".PearDatabase::quote($number)."%'");
	if(isset($current_user_only) && $current_user_only != "") array_push($where_clauses, "bugs.assigned_user_id='$current_user->id'");
	if(isset($type) && $type != "") array_push($where_clauses, "bugs.type = '" . PearDatabase::quote($type) . "'");
require_once('modules/DynamicFields/DynamicField.php');
$seedBug->setupCustomFields('Bugs');
$seedBug->custom_fields->setWhereClauses($where_clauses);
	$where = "";
	foreach($where_clauses as $clause)
	{
		if($where != "")
		$where .= " and ";
		$where .= $clause;
	}

	if(isset($assigned_user_id)){
	$count = count($assigned_user_id);
	if ($count > 0 && is_array($assigned_user_id)) {
		if (!empty($where)) {
			$where .= " AND ";
		}
		$where .= "bugs.assigned_user_id IN(";
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
	$search_form=new XTemplate ('modules/Bugs/SearchForm.html');
	$search_form->assign("MOD", $current_module_strings);
	$search_form->assign("APP", $app_strings);
	$search_form->assign("IMAGE_PATH", $image_path);
	$search_form->assign("ADVANCED_SEARCH_PNG", get_image($image_path.'advanced_search','alt="'.$app_strings['LNK_ADVANCED_SEARCH'].'"  border="0"'));
	$search_form->assign("BASIC_SEARCH_PNG", get_image($image_path.'basic_search','alt="'.$app_strings['LNK_BASIC_SEARCH'].'"  border="0"'));
	$bug_release_dom = $seedRelease->get_releases(false, "Active");
	array_unshift($bug_release_dom, '');
	if (!isset($resolution))$search_form->assign("RESOLUTION_OPTIONS", get_select_options_with_id($app_list_strings['bug_resolution_dom'],''));
	else $search_form->assign("RESOLUTION_OPTIONS", get_select_options_with_id($app_list_strings['bug_resolution_dom'],$resolution));
	if (!isset($release))$search_form->assign("RELEASE_OPTIONS", get_select_options_with_id($bug_release_dom,''));
	else $search_form->assign("RELEASE_OPTIONS", get_select_options_with_id($bug_release_dom,$release));
	if(isset($name)) $search_form->assign("NAME", $name);
	$search_form->assign("JAVASCRIPT", get_clear_form_js());
	if(isset($current_user_only)) $search_form->assign("CURRENT_USER_ONLY", "checked");
	if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){
		$header_text = "&nbsp;<a href='index.php?action=index&module=DynamicLayout&from_action=SearchForm&from_module=".$_REQUEST['module'] ."'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>";
	}
	echo get_form_header($current_module_strings['LBL_SEARCH_FORM_TITLE']. $header_text, "", false);
	if (isset($_REQUEST['advanced']) && $_REQUEST['advanced'] == 'true') {
		if (isset($number)) $search_form->assign("NUMBER", $number);

		$bug_status_dom = & $app_list_strings['bug_status_dom'];
		array_unshift($bug_status_dom, '');
		if (isset($status)) $search_form->assign("STATUS_OPTIONS", get_select_options_with_id($bug_status_dom, $status));
		else $search_form->assign("STATUS_OPTIONS", get_select_options_with_id($bug_status_dom, ''));

		$bug_priority_dom = & $app_list_strings['bug_priority_dom'];
		array_unshift($bug_priority_dom, '');
		if (isset($status)) $search_form->assign("PRIORITY_OPTIONS", get_select_options_with_id($bug_priority_dom, $priority));
		else $search_form->assign("PRIORITY_OPTIONS", get_select_options_with_id($bug_priority_dom, ''));

		if (!empty($assigned_user_id)) $search_form->assign("USER_FILTER", get_select_options_with_id(get_user_array(FALSE), $assigned_user_id));
		else $search_form->assign("USER_FILTER", get_select_options_with_id(get_user_array(FALSE), ''));

		if (!isset($type))$search_form->assign("TYPE_OPTIONS", get_select_options_with_id($app_list_strings['bug_type_dom'],''));
		else $search_form->assign("TYPE_OPTIONS", get_select_options_with_id($app_list_strings['bug_type_dom'],$type));

      // adding custom fields:
		$seedBug->custom_fields->populateXTPL($search_form, 'search' );

		$search_form->parse("advanced");
		$search_form->out("advanced");
	}
	else {
    // adding custom fields:
		$seedBug->custom_fields->populateXTPL($search_form, 'search' );

		$search_form->parse("main");
		$search_form->out("main");
	}
	echo get_form_footer();
	echo "\n<BR>\n";
}




$newForm = null;



$ListView =& new ListView();

$ListView->initNewXTemplate( 'modules/Bugs/ListView.html',$current_module_strings);
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){
		$header_text = "&nbsp;<a href='index.php?action=index&module=DynamicLayout&from_action=ListView&from_module=".$_REQUEST['module'] ."'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>";
}
$ListView->setHeaderTitle($current_module_strings['LBL_LIST_FORM_TITLE'] . $header_text);
$ListView->setQuery($where, "", "number", "BUG");

$ListView->processListView($seedBug, "main", "BUG");
?>
