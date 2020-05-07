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
 * $Id: ListView.php,v 1.7 2005/02/09 07:08:54 andrew Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('modules/Feeds/Feed.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');

require_once('include/ListView/ListView.php');


global $app_strings;
global $app_list_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'Feeds');

global $urlPrefix;

$log = LoggerManager::getLogger('feed_list');

global $currentModule;

global $theme;

if (!isset($where)) $where = "";

$seedFeed = new Feed();
require_once('modules/MySettings/StoreQuery.php');
$storeQuery = new StoreQuery();
if(!isset($_REQUEST['query'])){
	$storeQuery->loadQuery($currentModule);
	$storeQuery->populateRequest();
}else{
	$storeQuery->saveFromGet($currentModule);	
}
if(isset($_REQUEST['current_user_only']) && $_REQUEST['current_user_only'] != "")
{
		$seedFeed->my_favorites = true;
}

	// we have a query
	if (isset($_REQUEST['title'])) $title = $_REQUEST['title'];


	$where_clauses = Array();


	if(isset($_REQUEST['title']) && $_REQUEST['title'] != "") array_push($where_clauses, "feeds.title like '%".PearDatabase::quote($_REQUEST['title'])."%'");

	if(isset($_REQUEST['current_user_only']) && $_REQUEST['current_user_only'] != "") array_push($where_clauses," users_feeds.user_id='{$current_user->id}' ");



	$where = "";
	foreach($where_clauses as $clause)
	{
		if($where != "")
		$where .= " and ";
		$where .= $clause;
	}

	$log->info("Here is the where clause for the list view: $where");


if (!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false') {
echo get_form_header($current_module_strings['LBL_SEARCH_FORM_TITLE'], '', false);
	// Stick the form header out there.
	$search_form=new XTemplate ('modules/Feeds/SearchForm.html');
	$search_form->assign("MOD", $current_module_strings);
	$search_form->assign("APP", $app_strings);
	$search_form->assign("IMAGE_PATH", $image_path);
	 $search_form->assign("JAVASCRIPT", get_clear_form_js());

	if(isset($_REQUEST['title']) && $_REQUEST['title'] != "")
	{
		$search_form->assign("TITLE", $_REQUEST['title']);
	}

	if(isset($_REQUEST['current_user_only']) && $_REQUEST['current_user_only'] != "")
	{
		$search_form->assign("CURRENT_USER_ONLY", "CHECKED");
	}


		$search_form->parse("main");
		$search_form->out("main");
	echo get_form_footer();
	echo "\n<BR>\n";
}


$ListView = new ListView();

$ListView->initNewXTemplate( 'modules/Feeds/ListView.html',$current_module_strings);
if(isset($_REQUEST['current_user_only']) && $_REQUEST['current_user_only'] != "")
{
$ListView->setHeaderTitle($current_module_strings['LBL_MY_LIST_FORM_TITLE'] );
}
else
{
$ListView->setHeaderTitle($current_module_strings['LBL_LIST_FORM_TITLE'] );
}
$ListView->setQuery($where, "", "title", "FEED");
$ListView->processListView($seedFeed, "main", "FEED");
?>
