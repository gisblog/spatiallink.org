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
 * $Id: ListView.php,v 1.44 2005/04/29 00:39:36 clint Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('modules/Notes/Note.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');

require_once('include/ListView/ListView.php');



global $app_strings;
global $app_list_strings;
global $mod_strings;
$header_text = '';
global $urlPrefix;
if(empty($_POST['mass']) && empty($where) && empty($_REQUEST['query'])){$_REQUEST['query']='true'; $_REQUEST['current_user_only']='checked'; };
$log = LoggerManager::getLogger('note_list');

global $currentModule;

global $image_path;
global $theme;

// focus_list is the means of passing data to a ListView.
global $focus_list;

$seedNote = new Note();
require_once('modules/MySettings/StoreQuery.php');
$storeQuery = new StoreQuery();
if(!isset($_REQUEST['query'])){
	$storeQuery->loadQuery($currentModule);
	$storeQuery->populateRequest();
}else{
	$storeQuery->saveFromGet($currentModule);	
}
if (!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false') {
	// Stick the form header out there.
	$search_form=new XTemplate ('modules/Notes/SearchForm.html');
	$search_form->assign("MOD", $mod_strings);
	$search_form->assign("APP", $app_strings);

	if(isset($_REQUEST['query'])) {
		if(isset($_REQUEST['name'])) $search_form->assign("NAME", $_REQUEST['name']);
		if(isset($_REQUEST['contact_name'])) $search_form->assign("CONTACT_NAME", $_REQUEST['contact_name']);
	}
    $seedNote->custom_fields->populateXTPL($search_form, 'search' );

	$search_form->assign("JAVASCRIPT", get_clear_form_js());
	$search_form->parse("main");
	echo "\n<p>\n";
	if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){	
		$header_text = "&nbsp;<a href='index.php?action=index&module=DynamicLayout&from_action=SearchForm&from_module=".$_REQUEST['module'] ."'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>";
	}
	echo get_form_header($mod_strings['LBL_SEARCH_FORM_TITLE']. $header_text, "", false);
	$search_form->out("main");
	echo get_form_footer();
	echo "\n</p>\n";
}

$list_form=new XTemplate ('modules/Notes/ListView.html');
$list_form->assign("MOD", $mod_strings);
$list_form->assign("APP", $app_strings);
$list_form->assign("THEME", $theme);
$list_form->assign("IMAGE_PATH", $image_path);
$list_form->assign("MODULE_NAME", $currentModule);

$where = "";

if(isset($_REQUEST['query']))
{
	// we have a query
	if (isset($_REQUEST['name'])) $name = $_REQUEST['name'];
	if (isset($_REQUEST['contact_name'])) $contact_name = $_REQUEST['contact_name'];

	$where_clauses = Array();

	if(isset($name) && $name != '')
	{
		array_push($where_clauses, "notes.name like '".PearDatabase::quote($name)."%'");
	}
	if(isset($contact_name) && $contact_name != '')
	{
		$contact_names = explode(" ", $contact_name);
		foreach ($contact_names as $name) {
			array_push($where_clauses, "(contacts.first_name like '".PearDatabase::quote($name)."%' OR contacts.last_name like '".PearDatabase::quote($name)."%')");
		}
	}
	$seedNote->custom_fields->setWhereClauses($where_clauses);

	$where = "";
	if (isset($where_clauses)) {
		foreach($where_clauses as $clause)
		{
			if($where != "")
			$where .= " and ";
			$where .= $clause;
		}
	}
	$log->info("Here is the where clause for the list view: $where");

}

global $note_title;
$display_title = $mod_strings['LBL_LIST_FORM_TITLE'];
if ($note_title) $display_title = $note_title;

$ListView = new ListView();

if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){	
		$header_text = "&nbsp;<a href='index.php?action=index&module=DynamicLayout&from_action=ListView&from_module=".$_REQUEST['module'] ."'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>";
}
$ListView->initNewXTemplate( 'modules/Notes/ListView.html',$mod_strings);
$ListView->setHeaderTitle($display_title . $header_text);
$ListView->setQuery($where, "", "notes.date_entered DESC", "NOTE");
$ListView->processListView($seedNote, "main", "NOTE");
?>
