<?php
/**
 * Display of ListView for ProjectTask
 *
 * PHP version 4
 *
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

// $Id: ListView.php,v 1.6.2.2 2005/05/17 22:29:38 andrew Exp $

global $current_language;
global $app_strings;

require_once('XTemplate/xtpl.php');
require_once('themes/' . $theme . '/layout_utils.php');
require_once('include/ListView/ListView.php');
require_once('include/logging.php');
require_once('include/modules.php');
require_once('modules/ProjectTask/ProjectTask.php');

if (!isset($where)) $where = '';
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
$seedProjectTask =& new ProjectTask();

if(isset($_REQUEST['query']))
{
	// we have a query

	$where_clauses = array();

	if(isset($_REQUEST['name'])) 
	{
		$name = $_REQUEST['name'];
		array_push($where_clauses, "project_task.name LIKE '"
			.PearDatabase::quote($name)."%'");
	}

	if(isset($_REQUEST['parent_name']))
	{
		$parent_name = $_REQUEST['parent_name'];
		array_push($where_clauses,
			"project.name LIKE '$parent_name%'");
	}

	if(isset($_REQUEST['current_user_only']))
	{
		$current_user_only = $_REQUEST['current_user_only'];
		array_push($where_clauses,
			"project_task.assigned_user_id='$current_user->id'");
	}

	$seedProjectTask->custom_fields->setWhereClauses($where_clauses);

	foreach($where_clauses as $clause)
	{
		if($where != '')
		{
			$where .= ' AND ';
		}

		$where .= $clause;
	}

	$log->info("Here is the where clause for the list view: $where");
}

$search_form =& new XTemplate('modules/ProjectTask/SearchForm.html');

$mod_strings = return_module_language($current_language,
	'ProjectTask');

// the title label and arrow pointing to the module search form
$header_text = '';
if(is_admin($current_user)
	&& $_REQUEST['module'] != 'DynamicLayout'
	&& !empty($_SESSION['editinplace']))
{
	$header_text = "<a href='index.php?action=index&module=DynamicLayout&from_action=SearchForm&from_module="
	.$_REQUEST['module'] ."'>"
	.get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")
	."</a>";
}
$header = get_form_header($mod_strings['LBL_SEARCH_FORM_TITLE'], $header_text, false);
$search_form->assign('header', $header);
$search_form->assign('mod', $mod_strings);
$search_form->assign('app', $app_strings);
$search_form->assign("JAVASCRIPT", get_clear_form_js());

///
/// keep the old values of the search form
///

if(isset($current_user_only))
{
	$search_form->assign('CURRENT_USER_ONLY', 'checked="checked"');
}

if(isset($name))
{
	$search_form->assign('name', $name);
}

if(isset($parent_name))
{
	$search_form->assign('parent_name', $parent_name);
}

///
/// take care of output of search form
///

$search_form->parse('main');
$search_form->out('main');

///
/// take care of output of rest of listview
///

$theme_path = "themes/$theme";
$img_path = "$theme_path/images";

$seed_project_task =& new ProjectTask();
$where_clause = '';
$listview =& new ListView();
$listview->initNewXTemplate('modules/ProjectTask/ListView.html', $mod_strings);
$listview->setHeaderTitle($mod_strings['LBL_LIST_FORM_TITLE']);
if(is_admin($current_user)
	&& $_REQUEST['module'] != 'DynamicLayout'
	&& !empty($_SESSION['editinplace']))
{
	$listview->setHeaderText("<a href='index.php?action=index&module=DynamicLayout&from_action=ListView&from_module="
	.$_REQUEST['module'] ."'>"
	.get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")
	."</a>" );
}
$listview->setQuery($where, '', 'name', 'PROJECT_TASK');
$listview->processListView($seed_project_task,  'main', 'PROJECT_TASK');

?>
