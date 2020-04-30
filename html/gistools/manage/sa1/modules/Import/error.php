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
 * $Id: error.php,v 1.16 2005/02/09 07:08:54 andrew Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');

function show_error_import($message) {
$_SESSION['import_error'] = $message;
	header("Location: index.php?module={$_REQUEST['module']}&action=Import&step=Error");
}

//if (isset($step) && $step == 'Error') {

function display_error_import()
{
	$message = $_SESSION['import_error'];
	global $import_mod_strings;

	global $theme;

	global $log;
	global $mod_strings;
	global $app_strings;
	$theme_path="themes/".$theme."/";

	$image_path=$theme_path."images/";

	require_once($theme_path.'layout_utils.php');

	$log->info("Upload Error");

	$xtpl=new XTemplate ('modules/Import/error.html');
	$xtpl->assign("MOD", $mod_strings);
	$xtpl->assign("APP", $app_strings);
	echo "\n<p>\n";
	echo get_module_title($mod_strings['LBL_MODULE_NAME'],$mod_strings['LBL_MODULE_NAME'] , true); 
	echo "\n</p>\n";

	if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);

	if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);

	$xtpl->assign("THEME", $theme);

	$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);

	$xtpl->assign("MODULE", $_REQUEST['module']);
	$xtpl->assign("MESSAGE", $message);

	$xtpl->parse("main");

	$xtpl->out("main");
}
?>
