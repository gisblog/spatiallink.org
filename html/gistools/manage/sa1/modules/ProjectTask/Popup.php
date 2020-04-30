<?php
/**
 * Save functionality for Project
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

// $Id: Popup.php,v 1.7.2.2 2005/05/06 19:14:55 andrew Exp $

global $theme;
global $mod_strings;
global $app_strings;
global $urlPrefix;
global $currentModule;

require_once('modules/ProjectTask/ProjectTask.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('XTemplate/xtpl.php');
require_once('include/ListView/ListView.php');

$log = LoggerManager::getLogger('project_task');

$seedProjectTask = new ProjectTask();

$where = '';
if(isset($_REQUEST['query']))
{
	$where_clauses = Array();

	// we have a query
	if(!empty($_REQUEST['parent_id']))
	{
		$parent_id = $_REQUEST['parent_id'];
		array_push($where_clauses,
			$seedProjectTask->table_name .'.'."parent_id='$parent_id'");
	}

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

$image_path = 'themes/'.$theme.'/images/';

////////////////////////////////////////////////////////
// Start the output
////////////////////////////////////////////////////////

$form =new XTemplate ('modules/ProjectTask/Popup.html');

$form->assign("MOD", $mod_strings);
$form->assign("APP", $app_strings);

// the form key is required
if(!isset($_REQUEST['form']))
	sugar_die("Missing 'form' parameter");

// This code should always return an answer.
// The form name should be made into a parameter and not be hard coded in this file.
$parent_form = $_REQUEST['form'];

if($currentModule == 'ProjectTask')
{
	$parent_form = 'EditView';
}
$parent_name = empty($_REQUEST['parent_name']) ? 'parent_name' : $_REQUEST['parent_name'];
$parent_id = empty($_REQUEST['parent_id']) ? 'parent_id' : $_REQUEST['parent_id'];

$form_parent_name = $parent_name;
if(!empty($_REQUEST['form_parent_name']))
{
	$form_parent_name = $_REQUEST['form_parent_name'];
}

$form_parent_id = $parent_id;
if(!empty($_REQUEST['form_parent_id']))
{
	$form_parent_id = $_REQUEST['form_parent_id'];
}

	$the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
	$the_javascript .= "function set_return(id, name) {\n";
	$the_javascript .= "	window.opener.document.$parent_form.$form_parent_name.value = name;\n";
	$the_javascript .= "	window.opener.document.$parent_form.$form_parent_id.value = id;\n";
//	$the_javascript .= "	window.opener.document.$parent_form.depends_on_name.value = name;\n";
//	$the_javascript .= "	window.opener.document.$parent_form.depends_on_id.value = id;\n";
	$the_javascript .= "}\n";
	$the_javascript .= "</script>\n";
	$button  = "<form action='index.php' method='post' name='form' id='form'>\n";
	$button .= "<input title='".$app_strings['LBL_CLEAR_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CLEAR_BUTTON_KEY']."' class='button' onclick=\"window.opener.document.$parent_form.parent_name.value = '';window.opener.document.$parent_form.parent_id.value = ''; window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CLEAR_BUTTON_LABEL']."  '>\n";
	$button .= "<input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']."' class='button' onclick=\"window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CANCEL_BUTTON_LABEL']."  '>\n";
	$button .= "</form>\n";

$form->assign("SET_RETURN_JS", $the_javascript);
$lbl_save_button_title = $app_strings['LBL_SAVE_BUTTON_TITLE'];
$lbl_save_button_key = $app_strings['LBL_SAVE_BUTTON_KEY'];
$lbl_save_button_label = $app_strings['LBL_SAVE_BUTTON_LABEL'];
$formSave= <<<EOQ
<input title='$lbl_save_button_title' accessKey='$lbl_save_button_key' class='button' type='submit' name='button' value='  $lbl_save_button_label  ' >&nbsp;<input title='{$app_strings['LBL_CANCEL_BUTTON_TITLE']}' accessKey='{$app_strings['LBL_CANCEL_BUTTON_KEY']}' class='button'  onClick="toggleDisplay('addform');" type='button' name='button' value='{$app_strings['LBL_CANCEL_BUTTON_LABEL']}' >
EOQ;

$form->assign("THEME", $theme);
$form->assign("IMAGE_PATH", $image_path);
$form->assign("MODULE_NAME", $currentModule);
if (isset($_REQUEST['form_submit'])) $form->assign("FORM_SUBMIT", $_REQUEST['form_submit']);
$form->assign("FORM", $_REQUEST['form']);

if (isset($_REQUEST['name'])) $form->assign("NAME", $_REQUEST['name']);

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
//$ListView->setHeaderTitle($mod_strings['LBL_LIST_FORM_TITLE']);
$ListView->setHeaderText($button);
$ListView->setQuery($where, '', 'name', "project_task");
$ListView->setModStrings($mod_strings);
$ListView->processListView($seedProjectTask, "main", "project_task");


?>

<?php echo get_form_footer(); ?>
<?php insert_popup_footer(); ?>
