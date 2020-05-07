<?php
/**
 * Display of ListView for EditCustomFields
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

// $Id: ListView.php,v 1.10 2005/04/17 19:25:10 majed Exp $

require_once('XTemplate/xtpl.php');
require_once('themes/' . $theme . '/layout_utils.php');
require_once('include/ListView/ListView.php');
require_once('include/logging.php');
require_once('include/modules.php');
require_once('modules/EditCustomFields/EditCustomFields.php');

$module_name = empty($_REQUEST['module_name']) ? '' :
	$_REQUEST['module_name'];

$search_form =& new XTemplate('modules/EditCustomFields/SearchForm.html');

function get_customizable_modules()
{
	$customizable_modules = array();
	$base_path = 'modules';
	$blocked_modules = array('iFrames', 'Dropdown', 'Feeds');
	$customizable_files = array('EditView.html', 'DetailView.html', 'ListView.html');

	$mod_dir = dir($base_path);

	while(false !== ($mod_dir_entry = $mod_dir->read()))
	{
		if($mod_dir_entry != '.'
			&& $mod_dir_entry != '..'
			&& !in_array($mod_dir_entry, $blocked_modules)
			&& is_dir($base_path . '/' . $mod_dir_entry))
		{
			$mod_sub_dir = dir($base_path . '/' . $mod_dir_entry);
			$add_to_array = false;

			while(false !== ($mod_sub_dir_entry = $mod_sub_dir->read()))
			{
				if(in_array($mod_sub_dir_entry, $customizable_files))
				{
					$add_to_array = true;
					break;
				}
			}

			if($add_to_array)
			{
				$customizable_modules[$mod_dir_entry] = $mod_dir_entry;
			}
		}
	}

	ksort($customizable_modules);
	return $customizable_modules;
}

$customizable_modules = get_customizable_modules();
$module_options_html = get_select_options_with_id($customizable_modules,
	$module_name);

global $current_language;
$mod_strings = return_module_language($current_language,
	'EditCustomFields');
global $app_strings;

// the title label and arrow pointing to the module search form
$header = get_form_header($mod_strings['LBL_SEARCH_FORM_TITLE'], '', false);
$search_form->assign('header', $header);
$search_form->assign('module_options', $module_options_html);
$search_form->assign('mod', $mod_strings);
$search_form->assign('app', $app_strings);

$search_form->parse('main');
$search_form->out('main');

if(!empty($module_name))
{
	$theme_path = "themes/$theme";
	$img_path = "$theme_path/images";

	require_once('modules/DynamicFields/DynamicField.php');
	$seed_fields_meta_data =& new FieldsMetaData();
	$where_clause = "custom_module='$module_name'";
	$listview =& new ListView();
	$listview->initNewXTemplate('modules/EditCustomFields/ListView.html', $mod_strings);
	$listview->setHeaderTitle("Custom Fields: $module_name Module");
	$listview->setQuery($where_clause, '', 'data_type', 'FIELDS_META_DATA');
	$listview->xTemplateAssign('DELETE_INLINE_PNG',
		get_image("$img_path/delete_inline", 'align="absmiddle" alt="'
		. $app_strings['LNK_DELETE'] . '" border="0"'));
	$listview->xTemplateAssign('EDIT_INLINE_PNG',
		get_image("$img_path/edit_inline", 'align="absmiddle" alt="'
		. $app_strings['LNK_EDIT'] . '" border="0"'));
	$listview->xTemplateAssign('return_module_name', $module_name);
	$listview->processListView($seed_fields_meta_data,  'main', 'FIELDS_META_DATA');
}

?>
