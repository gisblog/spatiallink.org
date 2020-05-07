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
 * $Id: header.php,v 1.18 2005/04/30 04:50:23 majed Exp $
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
if (!isset($record)) {
$record="";
}



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
$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$xtpl->assign("MODULE_NAME", $currentModule);
$xtpl->assign("DATE", date("Y-m-d"));
$xtpl->assign("TITLE", $app_strings['LBL_SEARCH']);

if ($current_user->first_name != '') $xtpl->assign("CURRENT_USER", $current_user->first_name);
else $xtpl->assign("CURRENT_USER", $current_user->user_name);

$xtpl->assign("CURRENT_USER_ID", $current_user->id);
if ($record == $current_user->id && $action=="DetailView" && $module=="Users") {
$xtpl->assign("MY_SBOLD", "<b>");
$xtpl->assign("MY_EBOLD", "</b>");
}

if ($module=="Employees") {
$xtpl->assign("EMP_SBOLD", "<b>");
$xtpl->assign("EMP_EBOLD", "</b>");
}

if ($action=="About") {
$xtpl->assign("ABOUT_SBOLD", "<b>");
$xtpl->assign("ABOUT_EBOLD", "</b>");
}
$xtpl->assign("SHORTCUTS", $app_strings['LBL_SHORTCUTS']);
	
	
if (isset($_REQUEST['query_string'])) $xtpl->assign("SEARCH", $_REQUEST['query_string']);

if ($action == "EditView" || $action == "Login") $xtpl->assign("ONLOAD", 'onload="set_focus()"');


// associates submodules with admin link
$associateAdmin = array("Administration","Users","Currencies",



);
if(in_array($module,$associateAdmin))
	{
		if ($record == $current_user->id && $action == "DetailView" && $module == "Users") {
			if (is_admin($current_user)) $xtpl->assign("ADMIN_LINK", "<a href='index.php?module=Administration&action=index' class='myAreaLink'>".$app_strings['LBL_ADMIN']."</a>&nbsp;|&nbsp;");
		} else {
			if (is_admin($current_user)) $xtpl->assign("ADMIN_LINK", "<a href='index.php?module=Administration&action=index' class='myAreaLink'><b>".$app_strings['LBL_ADMIN']."</b></a>&nbsp;|&nbsp;");
		}
	} else {
if (is_admin($current_user)) $xtpl->assign("ADMIN_LINK", "<a href='index.php?module=Administration&action=index' class='myAreaLink'>".$app_strings['LBL_ADMIN']."</a>&nbsp;|&nbsp;");
	}
	
	
$numb_tabs=count($module_menu);
$pre_module_menu = array_slice($module_menu,0,$max_sub_tabs);
$post_module_menu = array_slice($module_menu,$max_sub_tabs,$numb_tabs);

$i=0;
if (isset($current_user->user_name)) {
	foreach($pre_module_menu as $menu_item)
	{
		$after_this = current($module_menu);

		if ($menu_item[1] != 'Deleted Items') {
			$xtpl->assign("URL", $menu_item[0]);
			$xtpl->assign("LABEL", $menu_item[1]);
			$xtpl->assign("SC_MODULE_NAME", $menu_item[2]);
			if ($i==$max_sub_tabs-1 and $numb_tabs>$max_sub_tabs) {
				$xtpl->assign("SEPARATOR", "| <img src='".$image_path."moreSub.gif' width='10' height='18' border='0' align='absmiddle' onmouseover='tbButtonMouseOver(this.id,120,\"\",0);' id='SubMoreHandle'>");
			} else {
				if ($i!=$numb_tabs-1) {
					$xtpl->assign("SEPARATOR", "|");
				} else {
					$xtpl->assign("SEPARATOR", "");
				}
			}
		}
		else {
			$xtpl->assign("DELETED_ITEMS_URL", $menu_item[0]);
			$xtpl->assign("DELETED_ITEMS_LABEL", $menu_item[1]);
		}

		$xtpl->parse("main.sub_menu2.sub_menu_item2");
		$i++;
	}
	$xtpl->parse("main.sub_menu2");
	foreach($post_module_menu as $menu_item) {
		$xtpl->assign("URL", $menu_item[0]);
		$xtpl->assign("LABEL", str_replace(' ','&nbsp;',$menu_item[1]));
		$xtpl->assign("SC_MODULE_NAME", $menu_item[2]);
		$xtpl->parse("main.sub_more.sub_more_item");
	}
	$xtpl->parse("main.sub_more");
}


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
foreach($modListHeader as $module_name)
{
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
	$xtpl->assign("MODULE_NAME", $app_list_strings['moduleList'][$module_name]);
	$xtpl->assign("MODULE_KEY", $module_name);
	if($module_name == $currentModule && ($module_name != 'iFrames' || empty($_REQUEST['record']) || (!empty($_REQUEST['tab']) && $_REQUEST['tab']=='false')))
	{
		$xtpl->assign("TAB_CLASS", "currentTab");
		$xtpl->assign("MOUSEOVER", "onmouseover=\"this.style.cursor='hand';\" onmouseout=\"this.style.cursor='auto';\"");

// Assign the module name back to the current module.
$xtpl->assign("MODULE_NAME", $app_list_strings['moduleList'][$module_name]);

//ignore these modules
$ignoreMods=array("Home","Dashboard");
if (!in_array($currentModule,$ignoreMods)) {
	foreach($module_menu as $menu_item) {
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
			else $xtpl->assign("SEPARATOR", "");
		}
		else {
			$xtpl->assign("DELETED_ITEMS_URL", $menu_item[0]);
			$xtpl->assign("DELETED_ITEMS_LABEL", $menu_item[1]);
		}
		$xtpl->parse("main.hide.sub_hide");
		$xtpl->parse("main.left_form.tab.sub_menu.sub_menu_item");
	}
}}

$xtpl->parse("main.left_form.tab.sub_menu");

	}
	else 
	{
		$xtpl->assign("TAB_CLASS", "otherTab");
		$xtpl->assign("MOUSEOVER", "onmouseover=\"this.style.cursor='hand';this.style.background='#7FCB7A'\" onmouseout=\"this.style.cursor='auto';this.style.background='transparent'\"");

	}
	$xtpl->parse("main.hide");
	$xtpl->parse("main.left_form.tab");
}
include('modules/iFrames/header.php');



if (isset($_SESSION["authenticated_user_id"])) {

$xtpl->assign("TITLE_LAST_VIEW", $app_strings['LBL_LAST_VIEWED']);
$xtpl->parse("main.left_form_search");
$xtpl->parse("main.left_form");
	


$tracker = new Tracker();
$history = $tracker->get_recently_viewed($current_user->id);

$current_row=1;

if (count($history) > 0) {
	foreach($history as $row)
	{
	if (strlen($row['item_summary']) > 14) {
	$chopOff = substr($row['item_summary'], 0, 	12)."..";
	$xtpl->assign("RECENT_LABEL",$chopOff);
	} else {
	$xtpl->assign("RECENT_LABEL",$row['item_summary']);
	}
	$xtpl->assign("RECENT_LABEL_FULL",$row['item_summary']);
		$xtpl->assign("MODULE_NAME",$row['module_name']);
		$xtpl->assign("ROW_NUMBER",$current_row);
		
		$xtpl->assign("RECENT_URL","index.php?module=$row[module_name]&action=DetailView&record=$row[item_id]");
	if ($current_row < 8) {
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
