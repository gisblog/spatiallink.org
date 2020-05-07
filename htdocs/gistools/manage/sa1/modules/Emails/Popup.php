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
 * $Id: Popup.php,v 1.3.2.1 2005/05/06 03:02:18 robert Exp $
 * Description:  This file is used for all popups on this module
 * The popup_picker.html file is used for generating a list from which to find and
 * choose one instance.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

global $theme;
require_once('modules/Emails/Email.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('XTemplate/xtpl.php');
require_once('include/utils.php');
require_once('include/ListView/ListView.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;

global $urlPrefix;
global $currentModule;

$log = LoggerManager::getLogger('email');

$seed_object = new Email();

$where = "";
if(isset($_REQUEST['query']))
{
	$search_fields = Array("name", "contact_name");

	$where_clauses = Array();

	append_where_clause($where_clauses, "name", "emails.name");
	append_where_clause($where_clauses, "contact_name", "contacts.last_name");

	$where = generate_where_statement($where_clauses);
}

$image_path = 'themes/'.$theme.'/images/';

////////////////////////////////////////////////////////
// Start the output
////////////////////////////////////////////////////////
if (!isset($_REQUEST['html'])) {
	$form =new XTemplate ('modules/Emails/Popup_picker.html');
	$log->debug("using file modules/Emails/Popup_picker.html");
}
else {
	$log->debug("_REQUEST['html'] is ".$_REQUEST['html']);
	$form =new XTemplate ('modules/Emails/'.$_REQUEST['html'].'.html');
	$log->debug("using file modules/Emails/".$_REQUEST['html'].'.html');
}

$form->assign("MOD", $mod_strings);
$form->assign("APP", $app_strings);

// the form key is required
if(!isset($_REQUEST['form']))
	sugar_die("Missing 'form' parameter");

// This code should always return an answer.
// The form name should be made into a parameter and not be hard coded in this file.

if($_REQUEST['form'] == "TasksEditView")
{
	$the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
	$the_javascript .= "function set_return(email_id, email_name) {\n";
	$the_javascript .= "	window.opener.document.EditView.parent_name.value = email_name;\n";
	$the_javascript .= "	window.opener.document.EditView.parent_id.value = email_id;\n";
	$the_javascript .= "}\n";
	$the_javascript .= "</script>\n";
	$button  = "<form action='index.php' method='post' name='form' id='form'>\n";
	$button .= "<input title='".$app_strings['LBL_CLEAR_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CLEAR_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.opener.document.EditView.parent_name.value = '';window.opener.document.EditView.parent_id.value = ''; window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CLEAR_BUTTON_LABEL']."  '>\n";
	$button .= "<input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CANCEL_BUTTON_LABEL']."  '>\n";
	$button .= "</form>\n";
}


// This code should always return an answer.
// The form name should be made into a parameter and not be hard coded in this file.
elseif($_REQUEST['form'] == 'EditView')
{
	$the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
	$the_javascript .= "function set_return(email_id, email_name) {\n";
	$the_javascript .= "	window.opener.document.EditView.email_name.value = email_name;\n";
	$the_javascript .= "	window.opener.document.EditView.email_id.value = email_id;\n";
	$the_javascript .= "}\n";
	$the_javascript .= "</script>\n";
	$button  = "<form border='0' action='index.php' method='post' name='form' id='form'>\n";

	$button .= "<input title='".$app_strings['LBL_CLEAR_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CLEAR_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.close()\" type='submit' name='button' value='  Clear  '>\n";
	$button .= "<input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CANCEL_BUTTON_LABEL']."  '>\n";
	$button .= "</form>\n";
}
elseif(isset($_REQUEST['form_submit']) && $_REQUEST['form'] == 'DetailView' && $_REQUEST['form_submit'] == 'true')
{
	$the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
	$the_javascript .= "function set_return(email_id, account_name) {\n";
	$the_javascript .= "	window.opener.document.DetailView.email_id.value = email_id; \n";
	$the_javascript .= "	window.opener.document.DetailView.return_module.value = window.opener.document.DetailView.module.value; \n";
	$the_javascript .= "	window.opener.document.DetailView.return_action.value = 'DetailView'; \n";
	$the_javascript .= "	window.opener.document.DetailView.return_id.value = window.opener.document.DetailView.record.value; \n";
	$the_javascript .= "	window.opener.document.DetailView.action.value = 'Save'; \n";
	$the_javascript .= "	window.opener.document.DetailView.submit(); \n";
	$the_javascript .= "}\n";
	$the_javascript .= "</script>\n";
	$button  = "<form border='0' action='index.php' method='post' name='form' id='form'>\n";

	$button .= "<input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CANCEL_BUTTON_LABEL']."  '>\n";
	$button .= "</form>\n";
}
else {
	$the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
	$the_javascript .= "function set_return(email_id, email_name) {\n";
	$the_javascript .= "	window.opener.document.".$_REQUEST['form'].".parent_name.value = email_name;\n";
	$the_javascript .= "	window.opener.document.".$_REQUEST['form'].".parent_id.value = email_id;\n";
	$the_javascript .= "}\n";
	$the_javascript .= "</script>\n";
	$button  = "<form border='0' action='index.php' method='post' name='form' id='form'>\n";

	$button .= "<input title='".$app_strings['LBL_CLEAR_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CLEAR_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.opener.document.".$_REQUEST['form'].".parent_name.value = '';window.opener.document.".$_REQUEST['form'].".parent_id.value = ''; window.close()\" type='submit' name='button' value='  Clear  '>\n";
	$button .= "<input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CANCEL_BUTTON_LABEL']."  '>\n";
	$button .= "</form>\n";
}

$form->assign("SET_RETURN_JS", $the_javascript);

$form->assign("THEME", $theme);
$form->assign("IMAGE_PATH", $image_path);
$form->assign("MODULE_NAME", $currentModule);
if (isset($_REQUEST['form_submit'])) $form->assign("FORM_SUBMIT", $_REQUEST['form_submit']);
$form->assign("FORM", $_REQUEST['form']);

if (isset($_REQUEST['contact_name']))
{
	$form->assign("CONTACT_NAME", $_REQUEST['contact_name']);

}
if (isset($_REQUEST['name']))
{
	$form->assign("NAME", $_REQUEST['name']);

}

insert_popup_header($theme);

// Quick search.
echo "<form>";
echo get_form_header($mod_strings['LBL_SEARCH_FORM_TITLE'], "", false);

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
$ListView->setHeaderTitle($mod_strings['LBL_LIST_FORM_TITLE']);
$ListView->setHeaderText($button);
$ListView->setQuery($where, "", "name", "EMAIL");
$ListView->setModStrings($mod_strings);
$ListView->processListView($seed_object, "main", "EMAIL");

?>

<?php echo get_form_footer(); ?>
<?php insert_popup_footer(); ?>
