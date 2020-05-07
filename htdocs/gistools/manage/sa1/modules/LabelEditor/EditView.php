<?PHP
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
$style='embeded';
if(isset($_REQUEST['style'])){
	$style = $_REQUEST['style'];	
}
if(isset($_REQUEST['module_name'])){
	$the_strings = return_module_language($current_language, $_REQUEST['module_name']);
	require_once('XTemplate/xtpl.php');
	require_once('data/Tracker.php');

	global $app_strings;
	global $app_list_strings;
	global $mod_strings;
	global $current_user;
global $theme;
	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	require_once($theme_path.'layout_utils.php');
	echo "<link rel='stylesheet' type='text/css' media='all' href='$theme_path/style.css'>";
	echo "\n<p>\n";
	echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ". $_REQUEST['module_name'], true);
	echo "\n</p>\n";
	
		





	$xtpl=&new XTemplate ('modules/LabelEditor/EditView.html');
	$xtpl->assign("MOD", $mod_strings);
	$xtpl->assign("APP", $app_strings);
	$xtpl->assign("MODULE_NAME", $_REQUEST['module_name']);
	$xtpl->assign("STYLE",$style);
	if(isset($_REQUEST['sugar_body_only'])){
		$xtpl->assign("SUGAR_BODY_ONLY",$_REQUEST['sugar_body_only']);
	}
	
	if(isset($_REQUEST['record']) ){
		$xtpl->assign("NO_EDIT", "readonly");
		$xtpl->assign("KEY", $_REQUEST['record']);
		if(isset($the_strings[$_REQUEST['record']])){
			$xtpl->assign("VALUE",$the_strings[$_REQUEST['record']]);
		}else{
			if(isset($_REQUEST['value']) )$xtpl->assign("VALUE", $_REQUEST['value']);	
		}
	}
	if($style == 'popup'){
		$xtpl->parse("main.popup");
	}
	$xtpl->parse("main");
	$xtpl->out("main");

}
else{
	echo 'No Module Selected';
}	



?>
