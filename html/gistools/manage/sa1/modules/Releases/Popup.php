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
 * $Id: Popup.php,v 1.2.2.2 2005/05/06 03:02:18 robert Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

global $theme;
global $mod_strings;
global $app_strings;
global $urlPrefix;
global $currentModule;

require_once('modules/Releases/Release.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('XTemplate/xtpl.php');
require_once('include/utils.php');
require_once('include/ListView/ListView.php');

$log = LoggerManager::getLogger('releases');

$seed_object = new Release();

$where = "";
if(isset($_REQUEST['query'])) {
	$search_fields = Array("name");
	$where_clauses = Array();
	append_where_clause($where_clauses, "name", "releases.name");
	$where = generate_where_statement($where_clauses);
}

$image_path = 'themes/'.$theme.'/images/';

////////////////////////////////////////////////////////
// Start the output
////////////////////////////////////////////////////////

if (!isset($_REQUEST['html'])) {
	$form = new XTemplate("modules/Releases/Popup_picker.html");
	$log->debug("using file modules/Releases/Popup_picker.html");
}
else {
	$form = new XTemplate('modules/Releases/'.$_REQUEST['html'].'.html');
	$log->debug("using file modules/Releases/".$_REQUEST['html'].'.html');
	$log->debug("_REQUEST['html'] is ".$_REQUEST['html']);
}

$form->assign("MOD", $mod_strings);
$form->assign("APP", $app_strings);

// the form key is required
if(!isset($_REQUEST['form']))
	sugar_die("Missing 'form' parameter");

// This code should always return an answer.
// The form name should be made into a parameter and not be hard coded in this file.
if(isset($_REQUEST['form_submit']) && $_REQUEST['form'] == 'UsersDetailView' && $_REQUEST['form_submit'] == 'true') {
	$the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";







	$the_javascript .= "function set_return(val_1, val_2) {\n";
	$the_javascript .= "	window.opener.document.UsersDetailView.submit(); \n";
	$the_javascript .= "}\n";



	$the_javascript .= "</script>\n";
}
elseif ($_REQUEST['form'] == 'EditView')
{
			  $parent_name_name = 'release_name';
        $parent_id_name = 'release_id';
        if ( ! empty($_REQUEST['parent_name']))
        {
          $parent_name_name = $_REQUEST['parent_name'];
        }
        if ( ! empty($_REQUEST['parent_id']))
        {
          $parent_id_name = $_REQUEST['parent_id'];
				}

        $the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
        $the_javascript .= "function set_return(release_id, release_name) {\n";
        $the_javascript .= "    window.opener.document.EditView.{$parent_name_name}.value = release_name;\n";
        $the_javascript .= "    window.opener.document.EditView.{$parent_id_name}.value = release_id;\n";
        $the_javascript .= "}\n";
        $the_javascript .= "</script>\n";
/*
        $button  = "<form action='index.php' method='post' name='form' id='form'>\n";
        $button .= "<input title='".$app_strings['LBL_CLEAR_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CLEAR_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.opener.document.EditView.account_name.value = '';window.opener.document.EditView.account_id.value = ''; window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CLEAR_BUTTON_LABEL']."  '>\n";
        $button .= "<input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CANCEL_BUTTON_LABEL']."  '>\n";
        $button .= "</form>\n";
*/
}


$form->assign("SET_RETURN_JS", $the_javascript);
$form->assign("THEME", $theme);
$form->assign("IMAGE_PATH", $image_path);
$form->assign("MODULE_NAME", $currentModule);
$form->assign("FORM_SUBMIT", $_REQUEST['form_submit']);
$form->assign("FORM", $_REQUEST['form']);

insert_popup_header($theme);

// Quick search.
echo "<form>";
echo get_form_header($mod_strings['LBL_SEARCH_FORM_TITLE'], "", false);

if (isset($_REQUEST['name'])) $last_search['NAME'] = $_REQUEST['name'];
if (isset($last_search)) $form->assign("LAST_SEARCH", $last_search);

$form->parse("main.SearchHeader");
$form->out("main.SearchHeader");

echo get_form_footer();

// Reset the sections that are already in the page so that they do not print again later.
$form->reset("main.SearchHeader");
$form->reset("main.SearchHeaderEnd");

// start the form before the form header to avoid creating a gap in IE
$button  = "<table cellspacing='0' cellpadding='0' border='0'><form border='0' action='index.php' method='post' name='form' id='form'>\n";
$button .= "<tr>";
//if ($_REQUEST['form_submit'] != 'true') $button .= "<td style=\"padding-bottom: 2px;\"><input title='".$app_strings['LBL_CLEAR_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CLEAR_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.opener.document.EditView.account_name.value = '';window.opener.document.EditView.account_id.value = ''; window.close()\" type='submit' name='button' value='  Clear  '>   ";
$button .= "<input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CANCEL_BUTTON_LABEL']."  '></td>\n";
$button .= "</tr></form></table>\n";

$ListView = new ListView();
$ListView->setXTemplate($form);
$ListView->setHeaderTitle($mod_strings['LBL_LIST_FORM_TITLE']);
$ListView->setHeaderText($button);
$ListView->setQuery($where, "", "name", "RELEASE");
$ListView->setModStrings($mod_strings);
$ListView->processListView($seed_object, "main", "RELEASE");
?>

	<tr>
		<td colspan="7"><?php echo get_form_footer(); ?></td>
	</tr>
</form>
</table>

</td></tr></table>
</td></tr>

<?php insert_popup_footer(); ?>
