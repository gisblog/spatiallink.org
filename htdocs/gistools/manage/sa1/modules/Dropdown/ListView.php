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

// $Id: ListView.php,v 1.11.2.2 2005/05/17 21:25:36 joey Exp $

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('include/ListView/ListView.php');

global $app_strings;
global $app_list_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'Dropdown');
$log = LoggerManager::getLogger('dropdown');
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

$language_select = isset($_REQUEST['language_select']) ?
							$_REQUEST['language_select'] : 'en_us';
$dropdown_select = isset($_REQUEST['dropdown_select']) ?
							$_REQUEST['dropdown_select'] : '';
$array_index = isset($_REQUEST['array_index']) ?
					$_REQUEST['array_index'] : -1;

if(isset($_REQUEST['form']) && $_REQUEST['form'] == 'Delete')
{
	dropdown_item_delete($dropdown_select, $language_select, $array_index);
}

if(isset($_REQUEST['form']) && $_REQUEST['form'] == 'Up')
{
	dropdown_item_move_up($dropdown_select, $language_select, $array_index);
}

if(isset($_REQUEST['form']) && $_REQUEST['form'] == 'Down')
{
	dropdown_item_move_down($dropdown_select, $language_select, $array_index);
}

if (!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false') {
	$app_list_strings_to_edit = return_app_list_strings_language($language_select);
	$app_list_strings_arrays_only = array_filter($app_list_strings_to_edit, 'is_array');

	// Stick the form header out there.
	$search_form = new XTemplate('modules/Dropdown/SearchForm.html');
	$app_list_strings_key_key = array();
	foreach($app_list_strings_arrays_only as $key => $val)
	{
		$app_list_strings_key_key = $app_list_strings_key_key +
												array($key => $key);
	}
   asort($app_list_strings_key_key);
   reset($app_list_strings_key_key);

   $search_form->assign("DROPDOWN_OPTIONS", get_select_options_with_id($app_list_strings_key_key, $dropdown_select));
   $search_form->assign("LANGUAGE_OPTIONS", get_select_options_with_id($sugar_config['languages'], $language_select));

	$search_form->assign("MOD", $current_module_strings);
	$search_form->assign("APP", $app_strings);
	$search_form->assign("IMAGE_PATH", $image_path);
	$search_form->assign("JAVASCRIPT", get_clear_form_js());

	$header_text = '';
	echo get_form_header($current_module_strings['LBL_SEARCH_FORM_TITLE']. $header_text, "", false);

	$search_form->parse("main");
	$search_form->out("main");
	echo get_form_footer();
	echo "\n<BR>\n";
}

$ListView = new ListView();

if('' != $dropdown_select)
{
	$app_list_strings_to_edit = return_app_list_strings_language($language_select);
	$ListView->setDataArray($app_list_strings_to_edit[$dropdown_select]);
}

$ListView->initNewXTemplate('modules/Dropdown/ListView.html',$current_module_strings);
$ListView->xTemplateAssign('UPARROW_INLINE', get_image($image_path.'uparrow_inline','align="absmiddle" alt="'.$mod_strings['LNK_UP'].'" border="0"'));
$ListView->xTemplateAssign('DOWNARROW_INLINE', get_image($image_path.'downarrow_inline','align="absmiddle" alt="'.$mod_strings['LNK_DOWN'].'" border="0"'));
$ListView->xTemplateAssign('PLUS_INLINE', get_image($image_path.'plus_inline','align="absmiddle" alt="'.$mod_strings['LNK_INSERT'].'" border="0"'));
$ListView->xTemplateAssign('EDIT_INLINE', get_image($image_path.'edit_inline','align="absmiddle" alt="'.$app_strings['LNK_EDIT'].'" border="0"'));
$ListView->xTemplateAssign('DELETE_INLINE', get_image($image_path.'delete_inline','align="absmiddle" alt="'.$app_strings['LNK_DELETE'].'" border="0"'));
$ListView->xTemplateAssign('DROPDOWN_SELECT', $dropdown_select);
$ListView->xTemplateAssign('LANGUAGE_SELECT', $language_select);
$ListView->xTemplateAssign('INDEX', $array_index);
$delete_onclick = "return confirm('Are you sure you want to delete this item?')";
$ListView->xTemplateAssign('DELETE_ONCLICK', $delete_onclick);
$ListView->setHeaderTitle($current_module_strings['LBL_LIST_FORM_TITLE']. $header_text );

if('' != $dropdown_select)
{
	$app_list_strings_to_edit = return_app_list_strings_language($language_select);
	$ListView->processListView($app_list_strings_to_edit[$dropdown_select], "main", "DROPDOWN");
}
?>
