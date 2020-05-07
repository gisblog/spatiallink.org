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
 * $Id: Popup.php,v 1.61.2.1 2005/05/06 02:59:39 robert Exp $
 * Description:  This file is used for all popups on this module
 * The popup_picker.html file is used for generating a list from which to find and 
 * choose one instance.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

global $theme;
require_once('modules/Contacts/Contact.php');
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

$log = LoggerManager::getLogger('contact');

$seed_object = new Contact();


$where = "";
if(isset($_REQUEST['query']))
{
	$search_fields = Array("first_name", "last_name", "account_name");

	$where_clauses = Array();

	append_where_clause($where_clauses, "first_name", "contacts.first_name");
	append_where_clause($where_clauses, "last_name", "contacts.last_name");
	append_where_clause($where_clauses, "account_name", "accounts.name");
	append_where_clause($where_clauses, "account_id", "accounts.id");

	$where = generate_where_statement($where_clauses);
	$log->info($where);
}


$image_path = 'themes/'.$theme.'/images/';

////////////////////////////////////////////////////////
// Start the output
////////////////////////////////////////////////////////
if (!isset($_REQUEST['html'])) {
	$form =new XTemplate ('modules/Contacts/Popup_picker.html');
	$log->debug("using file modules/Contacts/Popup_picker.html");
}
else {
	$log->debug("_REQUEST['html'] is ".$_REQUEST['html']);
	$form =new XTemplate ('modules/Contacts/'.$_REQUEST['html'].'.html');
	$log->debug("using file modules/Contacts/".$_REQUEST['html'].'.html');
}

$form->assign("MOD", $mod_strings);
$form->assign("APP", $app_strings);

// the form key is required
if(!isset($_REQUEST['form']))
	sugar_die("Missing 'form' parameter");
	
// This code should always return an answer.
// The form name should be made into a parameter and not be hard coded in this file.
if(isset($_REQUEST['form_submit']) && $_REQUEST['form'] == 'DetailView' && $_REQUEST['form_submit'] == 'true')
{
	$the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
	$the_javascript .= "function set_return(contact_id, contact_name) {\n";
	$the_javascript .= "	window.opener.document.DetailView.contact_id.value = contact_id; \n";
	$the_javascript .= "	window.opener.document.DetailView.return_module.value = window.opener.document.DetailView.module.value; \n";
	$the_javascript .= "	window.opener.document.DetailView.return_action.value = 'DetailView'; \n";
	$the_javascript .= "	window.opener.document.DetailView.return_id.value = window.opener.document.DetailView.record.value; \n";
	$the_javascript .= "	window.opener.document.DetailView.action.value = 'Save'; \n";
	$the_javascript .= "	window.opener.document.DetailView.submit(); \n";
	$the_javascript .= "}\n";
	$the_javascript .= "</script>\n";
	$button  = "<form  action='index.php' method='post' name='form' id='form'>\n";
	$button .= "<input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CANCEL_BUTTON_LABEL']."  '>\n";
	$button .= "</form>\n";
}

elseif(isset($_REQUEST['form_submit']) && $_REQUEST['form'] == 'ContactDetailView' && $_REQUEST['form_submit'] == 'true')
{
	$the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
	$the_javascript .= "function set_return(contact_id, contact_name) {\n";
	$the_javascript .= "	window.opener.document.DetailView.new_reports_to_id.value = contact_id; \n";
	$the_javascript .= "	window.opener.document.DetailView.return_module.value = window.opener.document.DetailView.module.value; \n";
	$the_javascript .= "	window.opener.document.DetailView.return_action.value = 'DetailView'; \n";
	$the_javascript .= "	window.opener.document.DetailView.return_id.value = window.opener.document.DetailView.record.value; \n";
	$the_javascript .= "	window.opener.document.DetailView.action.value = 'Save'; \n";
	$the_javascript .= "	window.opener.document.DetailView.submit(); \n";
	$the_javascript .= "}\n";
	$the_javascript .= "</script>\n";
	$button  = "<form action='index.php' method='post' name='form' id='form'>\n";
	$button .= "<input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CANCEL_BUTTON_LABEL']."  '>\n";
	$button .= "</form>\n";
}




























elseif ($_REQUEST['form'] == 'ContactEditView') 
{
	$the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
	$the_javascript .= "function set_return(contact_id, contact_name) {\n";
	$the_javascript .= "	window.opener.document.EditView.reports_to_name.value = contact_name;\n";
	$the_javascript .= "	window.opener.document.EditView.reports_to_id.value = contact_id;\n";
	$the_javascript .= "}\n";
	$the_javascript .= "</script>\n";
	$button  = "<form  action='index.php' method='post' name='form' id='form'>\n";
	$button .= "<input title='".$app_strings['LBL_CLEAR_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CLEAR_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.opener.document.EditView.reports_to_name.value = '';window.opener.document.EditView.reports_to_id.value = ''; window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CLEAR_BUTTON_LABEL']."  '>\n";
	$button .= "<td><input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CANCEL_BUTTON_LABEL']."  '>\n";
	$button .= "</form>\n";
}	
elseif ($_REQUEST['form'] == 'EmailEditView') 
{
	
	$the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
	$the_javascript .= "function set_return(contact_id, contact_email,contact_name,contact_display) {\n";
	$the_javascript .= "	window.opener.set_current_parent(contact_id,contact_email,contact_name,contact_display);\n";
	$the_javascript .= "}\n";
	$the_javascript .= "</script>\n";
	$button  = "<form  action='index.php' method='post' name='form' id='form'>\n";
	$button .= "<input title='".$app_strings['LBL_CLEAR_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CLEAR_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.opener.clear_email_addresses(); window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CLEAR_BUTTON_LABEL']."  '>\n";
	$button .= "<input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CANCEL_BUTTON_LABEL']."  '>\n";
	$button .= "</form>\n";

	//$form->assign('INPUT_VALUE_NAME',$_REQUEST['input_value_name']);
	//$form->assign('INPUT_VALUE_ID',$_REQUEST['input_value_id']);
}	
elseif ($_REQUEST['form'] == 'MassUpdate' && !empty($_REQUEST['input_type'])) 
{
	$the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
	$the_javascript .= "function set_return(contact_id, contact_name) {\n";
	$the_javascript .= "	window.opener.document.".$_REQUEST['form'].".".$_REQUEST['input_type']."_contact_name.value = contact_name;\n";
	$the_javascript .= "	window.opener.document.".$_REQUEST['form'].".".$_REQUEST['input_type']."_contact_id.value = contact_id;\n";
	$the_javascript .= "}\n";
	$the_javascript .= "</script>\n";
	$button  = "<form action='index.php' method='post' name='form' id='form'>\n";
	$button .= "<input title='".$app_strings['LBL_CLEAR_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CLEAR_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.opener.document.".$_REQUEST['form'].".contact_name.value = '';window.opener.document.".$_REQUEST['form'].".contact_id.value = ''; window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CLEAR_BUTTON_LABEL']."  '>\n";
	$button .= "<input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CANCEL_BUTTON_LABEL']."  '>\n";
	$button .= "</form>\n";
}
elseif($_REQUEST['form'] == 'ProjectDetailView')
{
	$the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
	$the_javascript .= "function set_return(contact_id, contact_name) {\n";
	$the_javascript .= "	window.opener.document.".$_REQUEST['form'].".relation_id.value = contact_id;\n";
	$the_javascript .= "	window.opener.document.".$_REQUEST['form'].".relation_type.value = 'Contacts';\n";
	$the_javascript .= "	window.opener.document.".$_REQUEST['form'].".module.value = 'ProjectRelation';\n";
	$the_javascript .= "	window.opener.document.".$_REQUEST['form'].".action.value = 'Save';\n";
	$the_javascript .= "	window.opener.document.".$_REQUEST['form'].".submit();\n";
	$the_javascript .= "}\n";
	$the_javascript .= "</script>\n";
	$button  = '<form action="index.php" method="post" name="form" id="form">' . "\n";
	$button .= "<input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']
		."' accessKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']
		."' class='button' onclick=\"window.close();\" type='submit' name='button' value='  "
		.$app_strings['LBL_CANCEL_BUTTON_LABEL']."  ' />\n";
	$button .= "</form>\n";
}
else 
{
        $parent_name_name = 'contact_name';
        $parent_id_name = 'contact_id';
        if ( ! empty($_REQUEST['parent_name']))
        {
          $parent_name_name = $_REQUEST['parent_name'];
        }
        if ( ! empty($_REQUEST['parent_id']))
        {
          $parent_id_name = $_REQUEST['parent_id'];
        }

	$the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
	$the_javascript .= "function set_return(contact_id, contact_name) {\n";
	$the_javascript .= "	window.opener.document.".$_REQUEST['form'].".{$parent_name_name}.value = contact_name;\n";
	$the_javascript .= "	window.opener.document.".$_REQUEST['form'].".{$parent_id_name}.value = contact_id;\n";
	$the_javascript .= "}\n";
	$the_javascript .= "</script>\n";
	$button  = "<form action='index.php' method='post' name='form' id='form'>\n";
	$button .= "<input title='".$app_strings['LBL_CLEAR_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CLEAR_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.opener.document.".$_REQUEST['form'].".contact_name.value = '';window.opener.document.".$_REQUEST['form'].".contact_id.value = ''; window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CLEAR_BUTTON_LABEL']."  '>\n";
	$button .= "<input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CANCEL_BUTTON_LABEL']."  '>\n";
	$button .= "</form>\n";
}	

$form->assign("SET_RETURN_JS", $the_javascript);

require_once('modules/Contacts/ContactFormBase.php');
$formBase = new ContactFormBase();
if(isset($_REQUEST['doAction']) && $_REQUEST['doAction'] == 'save'){
        $formBase->handleSave('', false, true);
}
$lbl_save_button_title = $app_strings['LBL_SAVE_BUTTON_TITLE'];
$lbl_save_button_key = $app_strings['LBL_SAVE_BUTTON_KEY'];
$lbl_save_button_label = $app_strings['LBL_SAVE_BUTTON_LABEL'];
$formbody = $formBase->getFormBody('','',$_REQUEST['form']);
$formbody = '<table><tr><td nowrap valign="top">'.str_replace('<br>', '</td><td nowrap valign="top">&nbsp;', $formbody). '</td></tr></table>';
$formSave= <<<EOQ
<input title='$lbl_save_button_title' accessKey='$lbl_save_button_key' class='button' type='submit' name='button' value='  $lbl_save_button_label  ' >&nbsp;<input title='{$app_strings['LBL_CANCEL_BUTTON_TITLE']}' accessKey='{$app_strings['LBL_CANCEL_BUTTON_KEY']}' class='button'  onClick="toggleDisplay('addform');" type='button' name='button' value='{$app_strings['LBL_CANCEL_BUTTON_LABEL']}' >
EOQ;

$createContact = <<<EOQ
<input class='button' type='button' name='showAdd' value='{$mod_strings['LNK_NEW_CONTACT']}' onClick='toggleDisplay("addform");'>
EOQ;
$form->assign("CREATECONTACT", $createContact);

$form->assign("ADDFORMHEADER", get_form_header($mod_strings['LNK_NEW_CONTACT'], $formSave, false));
$form->assign("ADDFORM", $formbody);

$form->assign("THEME", $theme);
$form->assign("IMAGE_PATH", $image_path);
$form->assign("MODULE_NAME", $currentModule);
if (isset($_REQUEST['form_submit'])) $form->assign("FORM_SUBMIT", $_REQUEST['form_submit']);
$form->assign("FORM", $_REQUEST['form']);

insert_popup_header($theme);
// Quick search.
echo get_form_header($mod_strings['LBL_SEARCH_FORM_TITLE'], "", false);

if (isset($_REQUEST['first_name']))
{
	$last_search['FIRST_NAME'] = $_REQUEST['first_name'];
 	
}

if (isset($_REQUEST['last_name']))
{
	$last_search['LAST_NAME'] = $_REQUEST['last_name'];
 	
}

if (isset($_REQUEST['account_name'])) 
{
	$last_search['ACCOUNT_NAME'] = $_REQUEST['account_name'];
 	
}

if (isset($last_search)) 
{
	$form->assign("LAST_SEARCH", $last_search);
}

$form->parse("main.SearchHeader");
$form->out("main.SearchHeader");

echo get_form_footer();

$form->parse("main.SearchHeaderEnd");
$form->out("main.SearchHeaderEnd");

// Reset the sections that are already in the page so that they do not print again later.
$form->reset("main.SearchHeader");
$form->reset("main.SearchHeaderEnd");

// Stick the form header out there.



$ListView = new ListView();
$ListView->setXTemplate($form);
$ListView->setHeaderTitle($mod_strings['LBL_LIST_FORM_TITLE']);
$ListView->setHeaderText($button);
$ListView->setQuery($where, "", "last_name, first_name", "CONTACT");
$ListView->setModStrings($mod_strings);
$ListView->processListView($seed_object, "main", "CONTACT");

?>

<?php echo get_form_footer(); ?>
<?php insert_popup_footer(); ?>
