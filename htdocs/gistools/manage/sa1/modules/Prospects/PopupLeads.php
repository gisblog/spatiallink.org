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
 * $Id: PopupLeads.php,v 1.2 2005/04/20 00:16:42 joey Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

global $theme;
require_once('modules/Leads/Lead.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('XTemplate/xtpl.php');
require_once('include/utils.php');
require_once('include/ListView/ListView.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;

$current_module_strings = return_module_language($current_language, 'Leads');

global $urlPrefix;
global $currentModule;

$log = LoggerManager::getLogger('lead');

$seed_object = & new Lead();


$where = "";
if(isset($_REQUEST['query']))
{
	$search_fields = Array("first_name","last_name");

	$where_clauses = Array();

	append_where_clause($where_clauses, "first_name", "leads.first_name");
	append_where_clause($where_clauses, "last_name", "leads.last_name");

	$where = generate_where_statement($where_clauses);
}


$image_path = 'themes/'.$theme.'/images/';

////////////////////////////////////////////////////////
// Start the output
////////////////////////////////////////////////////////
$form =new XTemplate ('modules/Prospects/Popup_Leads_picker.html');
$log->debug("using file modules/Prospects/Popup_Leads_picker.html");

$form->assign("MOD", $mod_strings);
$form->assign("APP", $app_strings);

// the form key is required
if(!isset($_REQUEST['form']))
	sugar_die("Missing 'form' parameter");
	

// This code should always return an answer.
// The form name should be made into a parameter and not be hard coded in this file.
if($_REQUEST['form'] == 'LeadsForm')
{
	$button  = "<form action='index.php' method='post' name='form' id='form'>\n";
	$button .= "<input type=hidden name='record' value='". $_REQUEST['record'] ."'>\n";
	$button .= "<input type=hidden name='module' value='ProspectLists'>\n";
	$button .= "<input type=hidden name='action' value='SaveLeadsRelationship'>\n";
	$button .= "<input title='".$current_module_strings['LBL_SELECT_CHECKED_BUTTON_TITLE']."'  class='button' LANGUAGE=javascript type='submit' name='button' value='  ".$current_module_strings['LBL_SELECT_CHECKED_BUTTON_LABEL']."  '>\n";
	$button .= "<input title='".$app_strings['LBL_DONE_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_DONE_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_DONE_BUTTON_LABEL']."  '>\n";
//	$button .= "</form>\n";
}

//$form->assign("SET_RETURN_JS", $the_javascript);

$form->assign("THEME", $theme);
$form->assign("IMAGE_PATH", $image_path);
$form->assign("MODULE_NAME", $currentModule);
if (isset($_REQUEST['form_submit'])) $form->assign("FORM_SUBMIT", $_REQUEST['form_submit']);
$form->assign("FORM", $_REQUEST['form']);
$form->assign("RECORD_VALUE", $_REQUEST['record']);

if (isset($_REQUEST['first_name'])) $form->assign("FIRST_NAME", $_REQUEST['first_name']);
if (isset($_REQUEST['last_name'])) $form->assign("LAST_NAME", $_REQUEST['last_name']);

insert_popup_header($theme);

// Quick search.
echo "<form>";
echo get_form_header($current_module_strings['LBL_SEARCH_FORM_TITLE'], "", false);

$form->parse("main.SearchHeader");
$form->out("main.SearchHeader");

echo get_form_footer();

$form->parse("main.SearchHeaderEnd");
$form->out("main.SearchHeaderEnd");

// Reset the sections that are already in the page so that they do not print again later.
$form->reset("main.SearchHeader");
$form->reset("main.SearchHeaderEnd");


$ListView = new ListView();
$ListView->setXTemplate($form);
$ListView->setHeaderTitle($current_module_strings['LBL_LIST_FORM_TITLE']);
$ListView->setHeaderText($button);
$ListView->setQuery($where, "", "last_name", "LEAD");
$ListView->setModStrings($current_module_strings);
$ListView->processListViewMulti($seed_object, "main", "LEAD");

?>

<?php echo get_form_footer(); ?>
<?php insert_popup_footer(); ?>
