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
 * $Id: DetailView.php,v 1.4 2005/04/30 06:12:18 joey Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/


require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Roles/Role.php');
require_once('modules/Roles/Forms.php');

global $mod_strings;
global $app_strings;
global $app_list_strings;
global $current_user;

if (!is_admin($current_user)) sugar_die("Unauthorized access to administration.");

$focus =& new Role();

if (!empty($_REQUEST['record'])) {
    $result = $focus->retrieve($_REQUEST['record']);
    if($result == null)
    {
    	sugar_die("Error retrieving record.  You may not be authorized to view this record.");
    }
}
else {
	header("Location: index.php?module=Roles&action=index");
}

echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME']. ": " . $focus->get_summary_text(), true);
echo "\n</p>\n";
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("Role detail view");

$xtpl=new XTemplate ('modules/Roles/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("THEME", $theme);
$xtpl->assign("GRIDLINE", $gridline);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("RETURN_MODULE", "Roles");
$xtpl->assign("RETURN_ACTION", "DetailView");
$xtpl->assign("ACTION", "EditView");

$xtpl->assign("NAME", $focus->name);
$xtpl->assign("DESCRIPTION", nl2br($focus->description));
require_once("include/templates/TemplateGroupChooser.php");
require_once("modules/MySettings/TabController.php");

$chooser = new TemplateGroupChooser();
$controller = new TabController();
$chooser->args['id'] = 'edit_tabs';

if(isset($_REQUEST['record']))
{
	$chooser->args['values_array'][0] = $focus->query_modules(1);
	$chooser->args['values_array'][1] = $focus->query_modules(0);

	foreach ($chooser->args['values_array'][0] as $key=>$value)
	{
		$chooser->args['values_array'][0][$value] = $app_list_strings['moduleList'][$value];
		unset($chooser->args['values_array'][0][$key]);
	}

	foreach ($chooser->args['values_array'][1] as $key=>$value)
	{
		$chooser->args['values_array'][1][$value] = $app_list_strings['moduleList'][$value];
		unset($chooser->args['values_array'][1][$key]);

	}
}
else
{
	$chooser->args['values_array'] = $controller->get_tabs_system();
	foreach ($chooser->args['values_array'][0] as $key=>$value)
	{
		$chooser->args['values_array'][0][$key] = $app_list_strings['moduleList'][$key];
	}
	foreach ($chooser->args['values_array'][1] as $key=>$value)
	{
	$chooser->args['values_array'][1][$key] = $app_list_strings['moduleList'][$key];
	}

}
	
$chooser->args['left_name'] = 'display_tabs';
$chooser->args['right_name'] = 'hide_tabs';
$chooser->args['left_label'] =  $mod_strings['LBL_ALLOWED_MODULES'];
$chooser->args['right_label'] =  $mod_strings['LBL_DISALLOWED_MODULES'];
$chooser->args['title'] =  $mod_strings['LBL_ASSIGN_MODULES'];

$chooser->args['disable'] = true;
$xtpl->assign("TAB_CHOOSER", $chooser->display());

$xtpl->parse("main");
$xtpl->out("main");

echo "<BR>\n";
$sub_xtpl = $xtpl;
$old_contents = ob_get_contents();
ob_end_clean();

echo $old_contents;

if($sub_xtpl->var_exists('subpanel', 'SUBUSERS'))
{
	ob_start();
	echo "<p>\n";
	$focus_list =  & $focus->get_users();
	include('modules/Roles/SubPanelViewUsers.php');
	echo "</p>\n";
	$subusers = ob_get_clean();
}


if(!empty($subusers))
	$sub_xtpl->assign('SUBUSERS', $subusers);

$sub_xtpl->parse("subpanel");
$sub_xtpl->out("subpanel");

?>
