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
 * $Id: ImportStep2.php,v 1.30 2005/02/09 07:08:54 andrew Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Import/Forms.php');
require_once('modules/Import/ImportMap.php');
require_once('modules/Import/config.php');

global $mod_strings, $app_list_strings, $app_strings, $current_user, $import_bean_map;
global $import_mod_strings;


$focus = 0;
echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME']." ".$mod_strings['LBL_STEP_2_TITLE'], true); 
echo "\n</p>\n";
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info($mod_strings['LBL_MODULE_NAME'] . " Upload Step 2");

$xtpl=new XTemplate ('modules/Import/ImportStep2.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("IMP", $import_mod_strings);

if (isset($import_bean_map[$_REQUEST['module']]))
{
	$bean = $import_bean_map[$_REQUEST['module']];
	require_once("modules/Import/$bean.php");
	$focus = new $bean();
}
else
{
 echo "Imports aren't set up for this module type\n";
 exit;
}

if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);

if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);

$xtpl->assign("THEME", $theme);

$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);

$xtpl->assign("HEADER", $app_strings['LBL_IMPORT']." ". $mod_strings['LBL_MODULE_NAME']);

$xtpl->assign("MODULE", $_REQUEST['module']);

// see if the source starts with 'custom' 
// if so, pull off the id, load that map, and get the name
if ($_REQUEST['source'] == "outlook")
{
	$xtpl->assign("SOURCE", $_REQUEST['source']);
	$xtpl->assign("SOURCE_NAME","Outlook ");
	$xtpl->assign("HAS_HEADER_CHECKED"," CHECKED");
} 
else if ($_REQUEST['source'] == "act")
{
	$xtpl->assign("SOURCE", $_REQUEST['source']);
	$xtpl->assign("SOURCE_NAME","ACT! ");
	$xtpl->assign("HAS_HEADER_CHECKED"," CHECKED");
}
else if ( strncasecmp("custom:",$_REQUEST['source'],7) == 0)
{
	$id = substr($_REQUEST['source'],7);
	$import_map_seed = new ImportMap();

	$import_map_seed->retrieve($id, false);

	$xtpl->assign("SOURCE_ID", $import_map_seed->id);
	$xtpl->assign("SOURCE_NAME", $import_map_seed->name);
	$xtpl->assign("SOURCE", $import_map_seed->source);

	if ($import_map_seed->has_header)
	{
		$xtpl->assign("HAS_HEADER_CHECKED"," CHECKED");
	}
}
else
{
	$xtpl->assign("HAS_HEADER_CHECKED"," CHECKED");
	$xtpl->assign("SOURCE", $_REQUEST['source']);
}

$xtpl->assign("JAVASCRIPT", get_validate_upload_js());

$lang_key = '';

if ($_REQUEST['source'] == "outlook")
{
	$lang_key = "OUTLOOK";
}
else if ($_REQUEST['source'] == "act")
{
	$lang_key = "ACT";
}
else if ($_REQUEST['source'] == "salesforce")
{
	$lang_key = "SF";
}
else if ($_REQUEST['source'] == "other_tab")
{
	$lang_key = "TAB";
}
else 
{
	$lang_key = "CUSTOM";
}

$xtpl->assign("INSTRUCTIONS_TITLE",$mod_strings["LBL_IMPORT_{$lang_key}_TITLE"]);

for ($i = 1; isset($mod_strings["LBL_{$lang_key}_NUM_$i"]);$i++)
{
$xtpl->assign("STEP_NUM",$mod_strings["LBL_NUM_$i"]);
$xtpl->assign("INSTRUCTION_STEP",$mod_strings["LBL_{$lang_key}_NUM_$i"]);
$xtpl->parse("main.instructions.step");
}
$xtpl->parse("main.instructions");

$xtpl->parse("main");

$xtpl->out("main");

?>
