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
 * $Id: header.php,v 1.19 2005/04/29 22:16:10 joey Exp $
 * Description:  Contains a variety of utility functions used to display UI
 * components such as form headers and footers.  Intended to be modified on a per
 * theme basis.
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");


global $currentModule;
global $moduleList;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');
require($theme_path.'config.php');



global $app_strings;
$default_charset = $sugar_config['default_charset'];
$module_path="modules/".$currentModule."/";
require_once($module_path.'Menu.php');
global $module_menu;

$xtpl=new XTemplate ($theme_path."header.html");
$xtpl->assign("APP", $app_strings);


if(isset($app_strings['LBL_CHARSET']))
{
	$xtpl->assign("LBL_CHARSET", $app_strings['LBL_CHARSET']);
}
else
{
	$xtpl->assign("LBL_CHARSET", $default_charset);
}

$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("MODULE_NAME", $currentModule);
$xtpl->assign("DATE", date("Y-m-d"));
$xtpl->assign("TITLE", $app_strings['LBL_SEARCH']);
if(isset($_REQUEST['RTL']) && $_REQUEST['RTL'] == 'RTL'){
		$_SESSION['RTL'] = true;
	}
	if(isset($_REQUEST['LTR']) && $_REQUEST['LTR'] == 'LTR'){
		unset($_SESSION['RTL']);
	}
	if(isset($_SESSION['RTL']) && $_SESSION['RTL']){
		$xtpl->assign("DIR", 'dir="RTL"');
	}
if ($current_user->first_name != '') $xtpl->assign("CURRENT_USER", $current_user->first_name);
else $xtpl->assign("CURRENT_USER", $current_user->user_name);

$xtpl->assign("CURRENT_USER_ID", $current_user->id);

if (is_admin($current_user)) $xtpl->assign("ADMIN_LINK", "<a href='index.php?module=Administration&action=index' class='myAreaLink'>".$app_strings['LBL_ADMIN']."</a>&nbsp;|&nbsp;");

if (isset($_REQUEST['query_string'])) $xtpl->assign("SEARCH", $_REQUEST['query_string']);

if ($action == "EditView" || $action == "Login") $xtpl->assign("ONLOAD", 'onload="set_focus()"');

// Loop through the module list.
// For each tab that is off, parse a tab_off.
// For the current tab, parse a tab_on
$modListHeader = $moduleList;
if(isset($current_user->id))
{
	if($action=='Login') 
	{
		if ($sugar_config['login_nav']==false)

		$modListHeader = array();
	} 
	else
	{
		$modListHeader = query_module_access_list($current_user);
	}
} 
else 
{
	$modListHeader = query_module_access_list($current_user);
}

$modListHeader = get_val_array($modListHeader);
if(isset($current_user->id))
{
	$user_max_tabs = $current_user->getPreference('max_tabs');
	if(intval($user_max_tabs) > 0)
		$max_tabs = intval($user_max_tabs);
}


$modListHeaderClone = $modListHeader;
require_once('modules/iFrames/iFrame.php');
$iFrame = new iFrame();
$frames = $iFrame->lookup_frames('tab');
foreach($frames as $name => $values){
	$modListHeaderClone[$name] = $values;
}

$numb_tabs=count($modListHeaderClone);
$moreListHeader = array_slice($modListHeaderClone, $max_tabs-1,$numb_tabs);
$defaultMore = array_slice($moreListHeader, 0,1);
if ($max_tabs==$numb_tabs) {
	$preListHeader = array_slice($modListHeaderClone, 0,$max_tabs);
} else {
	$preListHeader = array_slice($modListHeaderClone, 0,$max_tabs-1);
}
$preListHeader = array_slice($modListHeaderClone, 0,$max_tabs-1);
if (!isset($_SESSION['moreTab'])) {
	foreach ($defaultMore as $module){
		$_SESSION['moreTab'] = $module;
	}
}
if (isset($_SESSION['moreTab']) and in_array($_SESSION['moreTab'],$preListHeader)) {
	foreach ($defaultMore as $module){
		$_SESSION['moreTab'] = $module;
	}
}
if(in_array($currentModule,$moreListHeader)) {
	$_SESSION['moreTab'] = $currentModule;
} elseif($currentModule=="iFrames" and isset($_REQUEST['record']) and isset($_REQUEST['tab'])) {

	$frame = $iFrame->lookup_frame_by_record_id($_REQUEST['record']);
	foreach($frame as $name => $values){
	$_SESSION['moreTab'] = $values;
	}
}

if ($numb_tabs>$max_tabs){
	$preListHeader[] = $_SESSION['moreTab'];
}

// Associates Modules with tabs
$activities= array("Calls","Meetings","Tasks","Notes");

if (in_array("Calendar",$moduleList)) {

	$cal_activities= array("Calls","Meetings");
	if (in_array($currentModule,$cal_activities)) {
		$currentModule = "Calendar";
	} else  {
		if (in_array($currentModule,$activities)) {
			$currentModule = "Activities";
		}
	}


} else {
	if (in_array($currentModule,$activities)) {
		$currentModule = "Activities";

	}
}

$i=0;
foreach($preListHeader as $module_name)
{
		if (!is_array($module_name)) {
		$xtpl->assign("MODULE_NAME", $app_list_strings['moduleList'][$module_name]);
		$xtpl->assign("MODULE_KEY", $module_name);
	
		
		if($module_name == $currentModule && ($module_name != 'iFrames' || empty($_REQUEST['record'])|| (!empty($_REQUEST['tab']) && $_REQUEST['tab']=='false')))
		{	
			$xtpl->assign("TAB_CLASS", "currentTab");
			$xtpl->assign("OTHER_TAB", "currentTab");
		}
		else
		{	
			$xtpl->assign("TAB_CLASS", "otherTab");
			$xtpl->assign("OTHER_TAB", "currentTab");
		}

		} else {
		
		$xtpl->assign("MODULE_NAME", $module_name[4]);
		$xtpl->assign("MODULE_KEY", $module_name[3]);
	
		
		if($module_name[3] == $currentModule && (!empty($_REQUEST['record']) and $_REQUEST['record']==$module_name[0] and !empty($_REQUEST['tab'])))
		{	
			$xtpl->assign("TAB_CLASS", "currentTab");
			$xtpl->assign("OTHER_TAB", "currentTab");
		}
		else
		{	
			$xtpl->assign("TAB_CLASS", "otherTab");
			$xtpl->assign("OTHER_TAB", "currentTab");
		}

					
		}
		
		if ($i==$max_tabs-1 and $numb_tabs>$max_tabs) {
			$xtpl->assign("MORE", "<img src='".$image_path."more.gif' alt='' align='absmiddle' id='MoreHandle' style='margin-top: 5px; margin-left:4px; cursor: hand;' align='absmiddle' onmouseover='tbButtonMouseOver(this.id,74,\"\",0);'>");
		} 
		$xtpl->parse("main.tab");
	$i++;
}

foreach ($moreListHeader as $module_name) {
	if (!is_array($module_name)) {
		if($module_name!=$currentModule and $_SESSION['moreTab']!=$module_name){
			$xtpl->assign("MODULE_NAME", $app_list_strings['moduleList'][$module_name]);
			$xtpl->assign("MODULE_KEY", $module_name);
			$xtpl->parse("main.more");
		} 
	} else {
		if (!isset($_REQUEST['record']) or $_REQUEST['record']!=$module_name[0]) {
			$xtpl->assign("MODULE_NAME", $module_name[4]);
			$xtpl->assign("MODULE_KEY", $module_name[3]);
			$xtpl->assign("MODULE_QUERY", '&record='.$module_name[0].'&tab='.$module_name[2]);
			$xtpl->parse("main.more");
		}
		
	}

}
	unset($name);
	unset($id);
// Assign the module name back to the current module.
$xtpl->assign("MODULE_NAME", $currentModule);

foreach($module_menu as $menu_item)
{
	$subModuleCheck = 0;
	$subModuleCheckArray = array("Tasks", "Calls", "Meetings", "Notes");
	
	if(isset($menu_item[3]))
	{
		if(in_array($menu_item[3], $subModuleCheckArray) && 			(array_key_exists("Calendar", $modListHeader) || 
			array_key_exists("Activities", $modListHeader)))
				$subModuleCheck = 1;
	}

	if(!isset($menu_item[3])|| !isset($modListHeader) || (isset($menu_item[3]) && (key_exists($menu_item[3],$modListHeader) || $subModuleCheck))){
	$after_this = current($module_menu);
	
	if ($menu_item[1] != 'Deleted Items') {
		$xtpl->assign("URL", $menu_item[0]);
		$xtpl->assign("LABEL", $menu_item[1]);
		$xtpl->assign("SC_MODULE_NAME", $menu_item[2]);
		if (empty($after_this)) $xtpl->assign("SEPARATOR", "");
		else $xtpl->assign("SEPARATOR", "</br>");
	}
	else {
		$xtpl->assign("DELETED_ITEMS_URL", $menu_item[0]);
		$xtpl->assign("DELETED_ITEMS_LABEL", $menu_item[1]);
	}
	$xtpl->parse("main.hide");
	$xtpl->parse("main.left_form.sub_menu.sub_menu_item");
}}

$xtpl->parse("main.left_form.sub_menu");
$xtpl->assign("SHORTCUTS", $app_strings['LBL_SHORTCUTS']);


if (isset($_SESSION["authenticated_user_id"])) {
$xtpl->assign("LEFT_FORM_OTD", '<td valign="top" style="width: 10px;"><img style="cursor: hand;" id="HideHandle" name="HideHandle" src="'.$image_path.'show.gif" alt="" onclick=\'hideLeftCol("leftCol");closeMenus();\' onmouseover="showSubMenu(\'leftCol\')"></td><td id="left" valign="top" style="width: 160px;"><div style="display: none;" id="leftCol"><table cellpadding="0" cellspacing="0" border="0" width="160"><tr><td style="padding-right: 10px;">');
$xtpl->assign("LEFT_FORM_CTD", '<img src="'.$image_path.'blank.gif" alt="" width="160" height="1" border="0"></td></tr></table></div></td>');

$xtpl->assign("TITLE_LAST_VIEW", $app_strings['LBL_LAST_VIEWED']);
$xtpl->parse("main.left_form_search");
$xtpl->parse("main.left_form");
	


$tracker = new Tracker();
$history = $tracker->get_recently_viewed($current_user->id);

$current_row=1;

if (count($history) > 0) {
	foreach($history as $row)
	{
	if (strlen($row['item_summary']) > 20) {
	$chopOff = substr($row['item_summary'], 0, 	15)."..";
	$xtpl->assign("RECENT_LABEL",$chopOff);
	} else {
	$xtpl->assign("RECENT_LABEL",$row['item_summary']);
	}
	$xtpl->assign("RECENT_LABEL_FULL",$row['item_summary']);
		$xtpl->assign("MODULE_NAME",$row['module_name']);
		$xtpl->assign("ROW_NUMBER",$current_row);
		
		$xtpl->assign("RECENT_URL","index.php?module=$row[module_name]&action=DetailView&record=$row[item_id]");
	if ($current_row < 9) {
		$xtpl->parse("main.left_form_recent_view.left_form_recent_view_row");}
		$current_row++;
	}
}
else {
		$xtpl->parse("main.left_form_recent_view.left_form_recent_view_empty");
}

$xtpl->parse("main.left_form_recent_view");


require_once("modules/".$currentModule."/Forms.php");
if ($currentModule && $action == "index" && function_exists('get_new_record_form')) {
	$xtpl->assign("NEW_RECORD", get_new_record_form());
	$xtpl->parse("main.left_form_new_record");
}
}
$xtpl->parse("main");
$xtpl->out("main");

?>
