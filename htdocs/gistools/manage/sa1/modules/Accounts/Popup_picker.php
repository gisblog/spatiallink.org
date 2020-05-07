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
 * $Id: Popup_picker.php,v 1.41.2.1 2005/05/06 02:59:39 robert Exp $
 * Description:  This file is used for all popups on this module
 * The popup_picker.html file is used for generating a list from which to find and
 * choose one instance.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

global $theme;

require_once('modules/Accounts/Account.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('XTemplate/xtpl.php');
require_once('include/ListView/ListView.php');
global $mod_strings;
global $app_strings;

global $urlPrefix;
global $currentModule;

$log = LoggerManager::getLogger('account');

$seedAccount = new Account();

$where = "";
if(isset($_REQUEST['query']))
{
	// we have a query
	if(!empty($_REQUEST['name'])) $name = $_REQUEST['name'];
	if(!empty($_REQUEST['billing_address_city'])) $billing_address_city = $_REQUEST['billing_address_city'];
	if(!empty($_REQUEST['phone_office'])) $phone_office = $_REQUEST['phone_office'];

	$where_clauses = Array();

	if(!empty($name))
	{
		array_push($where_clauses, $seedAccount->table_name .'.'."name like '$name%'");
	}
	if(!empty($billing_address_city))
	{
		array_push($where_clauses, $seedAccount->table_name .'.'."billing_address_city like '$billing_address_city%'");
	}

	$where = "";
	foreach($where_clauses as $clause)
	{
		if($where != "")
		$where .= " and ";
		$where .= $clause;
	}

	$log->info("Here is the where clause for the list view: $where");

}



$image_path = 'themes/'.$theme.'/images/';

////////////////////////////////////////////////////////
// Start the output
////////////////////////////////////////////////////////

if (!isset($_REQUEST['html'])) {
	$form =new XTemplate ('modules/Accounts/Popup_picker.html');
	$log->debug("using file modules/Accounts/Popup_picker.html");
}
else {
	$log->debug("_REQUEST['html'] is ".$_REQUEST['html']);
	$form =new XTemplate ('modules/Accounts/'.$_REQUEST['html'].'.html');
	$log->debug("using file modules/Accounts/".$_REQUEST['html'].'.html');
}

$form->assign("MOD", $mod_strings);
$form->assign("APP", $app_strings);

// the form key is required
if(!isset($_REQUEST['form']))
	sugar_die("Missing 'form' parameter");

// This code should always return an answer.
// The form name should be made into a parameter and not be hard coded in this file.
if($_REQUEST['form'] == 'TasksEditView')
{
	$the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
	$the_javascript .= "function set_return(account_id, account_name) {\n";
	$the_javascript .= "	window.opener.document.EditView.parent_name.value = account_name;\n";
	$the_javascript .= "	window.opener.document.EditView.parent_id.value = account_id;\n";
	$the_javascript .= "}\n";
	$the_javascript .= "</script>\n";
	$button  = "<form action='index.php' method='post' name='form' id='form'>\n";
	$button .= "<input title='".$app_strings['LBL_CLEAR_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CLEAR_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.opener.document.EditView.parent_name.value = '';window.opener.document.EditView.parent_id.value = ''; window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CLEAR_BUTTON_LABEL']."  '>\n";
	$button .= "<input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CANCEL_BUTTON_LABEL']."  '>\n";
	$button .= "</form>\n";
}
elseif(!empty($_REQUEST['form_submit']) && $_REQUEST['form'] == 'ProjectDetailView')
{
	$the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
	$the_javascript .= "function set_return(account_id, account_name) {\n";
	$the_javascript .= "	window.opener.document.ProjectDetailView.relation_id.value = account_id; \n";
	$the_javascript .= "	window.opener.document.ProjectDetailView.relation_type.value = 'Accounts'; \n";
	$the_javascript .= "	window.opener.document.ProjectDetailView.module.value = 'ProjectRelation'; \n";
	$the_javascript .= "	window.opener.document.ProjectDetailView.action.value = 'Save'; \n";
	$the_javascript .= "	window.opener.document.ProjectDetailView.submit(); \n";
	$the_javascript .= "}\n";
	$the_javascript .= "</script>\n";
	$button  = "<form action='index.php' method='post' name='form' id='form'>\n";
	$button .= "<input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']."' class='button' onclick=\"window.close();\" type='submit' name='button' value='  ".$app_strings['LBL_CANCEL_BUTTON_LABEL']."  '>\n";
	$button .= "</form>\n";
}
elseif(isset($_REQUEST['form_submit']) && $_REQUEST['form'] == 'AccountDetailView' && $_REQUEST['form_submit'] == 'true')
{
	$the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
	$the_javascript .= "function set_return(account_id, account_name) {\n";
	$the_javascript .= "	window.opener.document.DetailView.member_id.value = account_id; \n";
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
elseif(isset($_REQUEST['form_submit']) && $_REQUEST['form'] == 'BugDetailView' && $_REQUEST['form_submit'] == 'true')
{
	$the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
	$the_javascript .= "function set_return(account_id, account_name, billing_address_street, billing_address_city,billing_address_state,billing_address_postalcode, billing_address_country, shipping_address_street, shipping_address_city,shipping_address_state,shipping_address_postalcode, shipping_address_country ) {\n";
	$the_javascript .= "	window.opener.document.DetailView.account_id.value = account_id; \n";
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
elseif ($_REQUEST['form'] == 'EditView')
{
  $parent_name_name = 'account_name';
	$parent_id_name = 'account_id';
        if ( ! empty($_REQUEST['parent_name']))
        {
          $parent_name_name = $_REQUEST['parent_name'];
        }
        if ( ! empty($_REQUEST['parent_id']))
        {
          $parent_id_name = $_REQUEST['parent_id'];
        }


	$the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
	$the_javascript .= "function set_return(account_id, account_name) {\n";
	$the_javascript .= "	window.opener.document.EditView.{$parent_name_name}.value = account_name;\n";
	$the_javascript .= "	window.opener.document.EditView.{$parent_id_name}.value = account_id;\n";
	$the_javascript .= "}\n";
	$the_javascript .= "</script>\n";
	$button  = "<form action='index.php' method='post' name='form' id='form'>\n";
	$button .= "<input title='".$app_strings['LBL_CLEAR_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CLEAR_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.opener.document.EditView.account_name.value = '';window.opener.document.EditView.account_id.value = ''; window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CLEAR_BUTTON_LABEL']."  '>\n";
	$button .= "<input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CANCEL_BUTTON_LABEL']."  '>\n";
	$button .= "</form>";
}
























































elseif ($_REQUEST['form'] == 'ConvertLead' || $_REQUEST['form'] == 'BusinessCard')
{
        $the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
        $the_javascript .= "function set_return(account_id, account_name) {\n";
        $the_javascript .= "    window.opener.document.{$_REQUEST['form']}.display_account_name.value=account_name;\n";
        $the_javascript .= "    window.opener.document.{$_REQUEST['form']}.selectedAccount.value=account_id;\n";
        $the_javascript .= "}\n";
        $the_javascript .= "</script>\n";
        $button  = "<form action='index.php' method='post' name='form' id='form'>\n";
        $button .= "<input title='".$app_strings['LBL_CLEAR_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CLEAR_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.opener.document.{$_REQUEST['form']}.account_name.value = '';window.opener.document.{$_REQUEST['form']}.account_id.value = ''; window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CLEAR_BUTTON_LABEL']."  '>\n";
        $button .= "<input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CANCEL_BUTTON_LABEL']."  '>\n";
        $button .= "</form>\n";
}elseif ($_REQUEST['form'] == 'MassUpdate')
{
        $the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
        $the_javascript .= "function set_return(account_id, account_name) {\n";
        $the_javascript .= "    window.opener.document.{$_REQUEST['form']}.parent_name.value=account_name;\n";
        $the_javascript .= "    window.opener.document.{$_REQUEST['form']}.parent_id.value=account_id;\n";
        $the_javascript .= "}\n";
        $the_javascript .= "</script>\n";
        $button  = "<form action='index.php' method='post' name='form' id='form'>\n";
        $button .= "<input title='".$app_strings['LBL_CLEAR_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CLEAR_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.opener.document.{$_REQUEST['form']}.parent_name.value = '';window.opener.document.{$_REQUEST['form']}.parent_id.value = ''; window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CLEAR_BUTTON_LABEL']."  '>\n";
        $button .= "<input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CANCEL_BUTTON_LABEL']."  '>\n";
        $button .= "</form>\n";
}elseif ($_REQUEST['form'] == 'MassUpdateAccount' && empty($_REQUEST['input_type']))
{
        $the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
        $the_javascript .= "function set_return(account_id, account_name) {\n";
        $the_javascript .= "    window.opener.document.MassUpdate.account_name.value=account_name;\n";
        $the_javascript .= "    window.opener.document.MassUpdate.account_id.value=account_id;\n";
        $the_javascript .= "}\n";
        $the_javascript .= "</script>\n";
        $button  = "<form action='index.php' method='post' name='form' id='form'>\n";
        $button .= "<input title='".$app_strings['LBL_CLEAR_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CLEAR_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.opener.document.MassUpdate.parent_name.value = '';window.opener.document.MassUpdate.parent_id.value = ''; window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CLEAR_BUTTON_LABEL']."  '>\n";
        $button .= "<input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CANCEL_BUTTON_LABEL']."  '>\n";
        $button .= "</form>\n";
}
elseif ($_REQUEST['form'] == 'MassUpdateAccount' && isset($_REQUEST["input_type"]))
{
        $the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
        $the_javascript .= "function set_return(account_id, account_name) {\n";
        $the_javascript .= "    window.opener.document.MassUpdate." . $_REQUEST["input_type"] . "_account_name.value=account_name;\n";
        $the_javascript .= "    window.opener.document.MassUpdate." . $_REQUEST["input_type"] . "_account_idvalue=account_id;\n";
        $the_javascript .= "}\n";
        $the_javascript .= "</script>\n";
        $button  = "<form action='index.php' method='post' name='form' id='form'>\n";
        $button .= "<input title='".$app_strings['LBL_CLEAR_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CLEAR_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.opener.document.MassUpdate.parent_name.value = '';window.opener.document.MassUpdate.parent_id.value = ''; window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CLEAR_BUTTON_LABEL']."  '>\n";
        $button .= "<input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CANCEL_BUTTON_LABEL']."  '>\n";
        $button .= "</form>\n";
}
else
{
        $the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
        $the_javascript .= "function set_return(account_id, account_name) {\n";
        $the_javascript .= "    window.opener.document.{$_REQUEST['form']}.account_name.value=account_name;\n";
        $the_javascript .= "    window.opener.document.{$_REQUEST['form']}.account_id.value=account_id;\n";
        $the_javascript .= "}\n";
        $the_javascript .= "</script>\n";
        $button  = "<form action='index.php' method='post' name='form' id='form'>\n";
        $button .= "<input title='".$app_strings['LBL_CLEAR_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CLEAR_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.opener.document.{$_REQUEST['form']}.account_name.value = '';window.opener.document.{$_REQUEST['form']}.account_id.value = ''; window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CLEAR_BUTTON_LABEL']."  '>\n";
        $button .= "<input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CANCEL_BUTTON_LABEL']."  '>\n";
        $button .= "</form>\n";
}


$form->assign("SET_RETURN_JS", $the_javascript);
require_once('modules/Accounts/AccountFormBase.php');
$formBase = new AccountFormBase();
if(isset($_REQUEST['doAction']) && $_REQUEST['doAction'] == 'save'){
	$formBase->handleSave('', false, true);
}
$lbl_save_button_title = $app_strings['LBL_SAVE_BUTTON_TITLE'];
$lbl_save_button_key = $app_strings['LBL_SAVE_BUTTON_KEY'];
$lbl_save_button_label = $app_strings['LBL_SAVE_BUTTON_LABEL'];
$formbody = $formBase->getFormBody('');
$formbody = '<table><tr><td nowrap valign="top">'.str_replace('<br>', '</td><td nowrap valign="top">&nbsp;', $formbody). '</td></tr></table>';
$formSave= <<<EOQ
<input title='$lbl_save_button_title' accessKey='$lbl_save_button_key' class='button' type='submit' name='button' value='  $lbl_save_button_label  ' >&nbsp;<input title='{$app_strings['LBL_CANCEL_BUTTON_TITLE']}' accessKey='{$app_strings['LBL_CANCEL_BUTTON_KEY']}' class='button'  onClick="toggleDisplay('addform');" type='button' name='button' value='{$app_strings['LBL_CANCEL_BUTTON_LABEL']}' >
EOQ;

$createAccount = <<<EOQ
<input class='button' type='button' name='showAdd' value='{$mod_strings['LNK_NEW_ACCOUNT']}' onClick='toggleDisplay("addform");'>
EOQ;
$form->assign("CREATEACCOUNT", $createAccount);
$form->assign("ADDFORMHEADER", get_form_header($mod_strings['LNK_NEW_ACCOUNT'], $formSave, false));
$form->assign("ADDFORM", $formbody);
$form->assign("THEME", $theme);
$form->assign("IMAGE_PATH", $image_path);
$form->assign("MODULE_NAME", $currentModule);
if (isset($_REQUEST['form_submit'])) $form->assign("FORM_SUBMIT", $_REQUEST['form_submit']);
$form->assign("FORM", $_REQUEST['form']);

if (isset($_REQUEST['name'])) $form->assign("NAME", $_REQUEST['name']);
if (isset($_REQUEST['billing_address_city'])) $form->assign("BILLING_ADDRESS_CITY", $_REQUEST['billing_address_city']);

insert_popup_header($theme);
// Quick search.

echo get_form_header($mod_strings['LBL_SEARCH_FORM_TITLE'], '', false);

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
$ListView->setQuery($where, "", "name", "ACCOUNT");
$ListView->setModStrings($mod_strings);
$ListView->processListView($seedAccount, "main", "ACCOUNT");


?>

	<!--tr><td COLSPAN=7><?php echo get_form_footer(); ?></td></tr>
	</table>
</td></tr>
</table>
</td></tr-->
<?php echo get_form_footer(); ?>
<?php insert_popup_footer(); ?>
