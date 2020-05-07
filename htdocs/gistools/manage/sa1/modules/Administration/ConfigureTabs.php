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
 * $Id: ConfigureTabs.php,v 1.5 2005/02/25 04:34:58 majed Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Administration/Administration.php');
require_once('modules/Administration/Forms.php');

global $mod_strings;
global $app_list_strings;
global $app_strings;
global $current_user;

if (!is_admin($current_user)) sugar_die("Unauthorized access to administration.");

echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$mod_strings['LBL_CONFIGURE_TABS'], true);
echo "\n</p>\n";
global $theme;
global $currentModule;
$theme_path = "themes/".$theme."/";
$image_path = $theme_path."images/";


require_once($theme_path.'layout_utils.php');

$focus = new Administration();
$focus->retrieveSettings();
$timezone = 0;
if(isset($focus->settings['system_timezone'])){
	$timezone = $focus->settings['system_timezone'];
}
$log->info("Administration ConfigureSettings view");

$xtpl=new XTemplate ('modules/Administration/ConfigureTabs.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

$xtpl->assign("RETURN_MODULE", "Administration");
$xtpl->assign("RETURN_ACTION", "index");

$xtpl->assign("MODULE", $currentModule);
$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("HEADER", get_module_title("Administration", "{MOD.LBL_CONFIGURE_TABS}", true));
require_once("include/templates/TemplateGroupChooser.php");
require_once("modules/MySettings/TabController.php");
$chooser = new TemplateGroupChooser();
$controller = new TabController();
$chooser->args['id'] = 'edit_tabs';
$chooser->args['values_array'] = $controller->get_tabs_system();
$chooser->args['left_name'] = 'display_tabs';
$chooser->args['right_name'] = 'hide_tabs';
$chooser->args['left_label'] =  $mod_strings['LBL_DISPLAY_TABS'];
$chooser->args['right_label'] =  $mod_strings['LBL_HIDE_TABS'];
$chooser->args['title'] =  $mod_strings['LBL_EDIT_TABS'];
foreach ($chooser->args['values_array'][0] as $key=>$value)
{
$chooser->args['values_array'][0][$key] = $app_list_strings['moduleList'][$key];
}
foreach ($chooser->args['values_array'][1] as $key=>$value)
{
$chooser->args['values_array'][1][$key] = $app_list_strings['moduleList'][$key];
}



$xtpl->assign("TAB_CHOOSER", $chooser->display());
if($controller->get_users_can_edit()){
	$xtpl->assign('ENABLE_USER_EDIT', 'checked');
}

$xtpl->assign("JAVASCRIPT", get_chooser_js());

$xtpl->parse("main");

$xtpl->out("main");

?>
