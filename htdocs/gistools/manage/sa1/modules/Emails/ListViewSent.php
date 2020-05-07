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
 * $Id: ListViewSent.php,v 1.7 2005/04/14 18:03:43 lam Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('modules/Emails/Email.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');

require_once('include/ListView/ListView.php');

$seedEmail =& new Email();

global $app_strings;
global $app_list_strings;
global $mod_strings;

global $urlPrefix;

$log = LoggerManager::getLogger('email_list');

global $currentModule;

global $image_path;
global $theme;

// focus_list is the means of passing data to a ListView.
global $focus_list;
$header_text = '';
require_once('modules/MySettings/StoreQuery.php');
$storeQuery = new StoreQuery();
if(!isset($_REQUEST['query'])){
	$storeQuery->loadQuery($currentModule);
	$storeQuery->populateRequest();
}else{
	$storeQuery->saveFromGet($currentModule);	
}
if (isset($_REQUEST['current_user_only'])) $current_user_only = $_REQUEST['current_user_only'];

if (!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false') {
	// Stick the form header out there.
	$search_form=new XTemplate ('modules/Emails/SearchForm.html');
	$search_form->assign("MOD", $mod_strings);
	$search_form->assign("APP", $app_strings);
	$search_form->assign("SEARCH_ACTION", 'ListViewSent');

	if(isset($_REQUEST['query'])) {
		if(isset($_REQUEST['name'])) $search_form->assign("NAME", $_REQUEST['name']);
		if(isset($_REQUEST['contact_name'])) $search_form->assign("CONTACT_NAME", $_REQUEST['contact_name']);
		if(isset($current_user_only)) $search_form->assign("CURRENT_USER_ONLY", "checked");
	}
	                // adding custom fields:
	$seedEmail->custom_fields->populateXTPL($search_form, 'search' );

	$search_form->parse("main");
	if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){	
		$header_text = "&nbsp;<a href='index.php?action=index&module=DynamicLayout&from_action=SearchForm&from_module=".$_REQUEST['module'] ."'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>";
	}
	echo get_form_header($mod_strings['LBL_SEARCH_FORM_SENT_TITLE']. $header_text, "", false);
	$search_form->out("main");
	echo get_form_footer();
	echo "\n<BR>\n";
}



$where = "";



if(isset($_REQUEST['query']))
{
	// we have a query
	if (isset($_REQUEST['name'])) $name = $_REQUEST['name'];
	if (isset($_REQUEST['contact_name'])) $contact_name = $_REQUEST['contact_name'];
	if (isset($_REQUEST['date_sent'])) $date_sent = $_REQUEST['date_sent'];

	$where_clauses = Array();

	if(isset($current_user_only) && $current_user_only != "") array_push($where_clauses, "emails.assigned_user_id='$current_user->id'");
	if(isset($name) && $name != '')
	{
		array_push($where_clauses, "emails.name like '".PearDatabase::quote($name)."%'");
	}
	if(isset($contact_name) && $contact_name != '')
	{
		$contact_names = explode(" ", $contact_name);
		foreach ($contact_names as $name) {
			array_push($where_clauses, "(contacts.first_name like '".PearDatabase::quote($name)."%' OR contacts.last_name like '".PearDatabase::quote($name)."%')");
		}
	}

	$seedEmail->custom_fields->setWhereClauses($where_clauses);

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

			if($where != "")
			$where .= " and ";
			$where .= " emails.type='out' AND emails.status='sent'";


global $email_title;
$display_title = $mod_strings['LBL_LIST_FORM_SENT_TITLE'];
if($email_title)$display_title = $email_title;



$ListView = new ListView();

if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){	
		$header_text = "&nbsp;<a href='index.php?action=index&module=DynamicLayout&from_action=ListViewSent&from_module=".$_REQUEST['module'] ."'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>";
	}
$ListView->initNewXTemplate( 'modules/Emails/ListViewSent.html',$mod_strings);
$ListView->setHeaderTitle($display_title . $header_text );
$ListView->setQuery($where, "", "emails.date_start", "SENTEMAIL");
$ListView->processListView($seedEmail, "main", "SENTEMAIL");

?>
