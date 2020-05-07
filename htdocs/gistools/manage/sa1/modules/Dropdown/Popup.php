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

// $Id: Popup.php,v 1.8.6.1 2005/05/17 17:58:34 andrew Exp $

global $theme;
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('XTemplate/xtpl.php');
require_once('include/utils.php');
require_once('modules/Administration/Common.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $currentModule;

$log = LoggerManager::getLogger('dropdown');

$image_path = 'themes/'.$theme.'/images/';

// the form key is required
if(!isset($_REQUEST['form']))
	sugar_die("Missing 'form' parameter");

if(isset($_REQUEST['button']))
{
	// manipulate the array
	$key = $_REQUEST['key'];
	$value = $_REQUEST['value'];
	$dropdown_select = $_REQUEST['dropdown_select'];
	$language_select = $_REQUEST['language_select'];

	if($_REQUEST['form'] == 'Edit')
	{
		dropdown_item_edit($dropdown_select, $language_select, $key, $value);
	}

	if($_REQUEST['form'] == 'Insert')
	{
		$array_index = empty($_REQUEST['array_index']) ? 9999 : $_REQUEST['array_index'];
		dropdown_item_insert($dropdown_select, $language_select,
									$array_index, $key, $value);
	}

	// refresh the parent and close
	echo '<script>opener.window.location.reload();self.close();</script>';
	die();
}

///////////////////////////////////////
// Populate the template
///////////////////////////////////////

$form = new XTemplate ('modules/Dropdown/Popup.html');
$log->debug("using file modules/Dropdown/Popup.html");
$form->assign("MOD", $mod_strings);
$form->assign("APP", $app_strings);
$form->assign("IMAGE_PATH", $image_path);
$form->assign("MODULE_NAME", $currentModule);
if (isset($_REQUEST['form_submit'])) $form->assign("FORM_SUBMIT", $_REQUEST['form_submit']);
$form->assign("FORM", $_REQUEST['form']);
if($_REQUEST['form'] == 'Edit')
{
	$form->assign('READONLY', 'readonly="readonly"');
}

if (isset($_REQUEST['name']))
{
	$form->assign("NAME", $_REQUEST['name']);
}

$form->assign('DROPDOWN_SELECT', $_REQUEST['dropdown_select']);
$key = isset($_REQUEST['key']) ? $_REQUEST['key'] : '';
$value = isset($_REQUEST['value']) ? $_REQUEST['value'] : '';
$form->assign('KEY', $key);
$form->assign('VALUE', $value);
$array_index = isset($_REQUEST['array_index']) ? $_REQUEST['array_index'] : '';
$form->assign('INDEX', $array_index);
$form->assign('LANGUAGE_SELECT', $_REQUEST['language_select']);

$form->assign('SUBMIT_BUTTON_LABEL', $app_strings['LBL_SAVE_BUTTON_LABEL']);
$form->assign('SUBMIT_BUTTON_TITLE', $app_strings['LBL_SAVE_BUTTON_TITLE']);
$form->assign('SUBMIT_BUTTON_KEY', $app_strings['LBL_SAVE_BUTTON_KEY']);

$form->assign('CANCEL_BUTTON_LABEL', $app_strings['LBL_CANCEL_BUTTON_LABEL']);
$form->assign('CANCEL_BUTTON_TITLE', $app_strings['LBL_CANCEL_BUTTON_TITLE']);
$form->assign('CANCEL_BUTTON_KEY', $app_strings['LBL_CANCEL_BUTTON_KEY']);

///////////////////////////////////////
// Start the output
///////////////////////////////////////

insert_popup_header($theme);

echo get_form_header($mod_strings['LBL_DROPDOWN'], "", false);

$form->parse("main");
$form->out("main");

// Reset the sections that are already in the page so that they do not print again later.
$form->reset("main");

echo get_form_footer();
insert_popup_footer();
?>
