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
 * $Id: index.php,v 1.10 2005/04/14 18:03:46 lam Exp $
 * Description:
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('include/ListView/ListView.php');
require_once('modules/Releases/Release.php');
require_once('modules/Releases/Forms.php');
global $theme;
$header_text = '';
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once ($theme_path."layout_utils.php");


global $mod_strings;
global $app_list_strings;
global $app_strings;
global $current_user, $log;

$focus =& new Release();

$is_edit = false;
if(!empty($_REQUEST['record'])) {
    $result = $focus->retrieve($_REQUEST['record']);
    if($result == null)
    {
    	sugar_die("Error retrieving record.  You may not be authorized to view this record.");
    }
	$is_edit=true;
}
if(isset($_REQUEST['edit']) && $_REQUEST['edit']=='true') {
	$is_edit=true;
	//Only allow admins to enter this screen
	if (!is_admin($current_user)) {
		$log->error("Non-admin user ($current_user->user_name) attempted to enter the Releases edit screen");
		session_destroy();
		include('modules/Users/Logout.php');
	}
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}

$log->info("Release list view");
echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_TITLE'], true);
echo "\n</p>\n";
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$button  = "<form action='index.php' method='post' name='form' id='form'>\n";
$button .= "<input type='hidden' name='module' value='Releases'>\n";
$button .= "<input type='hidden' name='action' value='EditView'>\n";
$button .= "<input type='hidden' name='edit' value='true'>\n";
$button .= "<input type='hidden' name='return_module' value='".$currentModule."'>\n";
$button .= "<input type='hidden' name='return_action' value='".$action."'>\n";
$button .= "<input title='".$app_strings['LBL_NEW_BUTTON_TITLE']."' accessyKey='".$app_strings['LBL_NEW_BUTTON_KEY']."' class='button' type='submit' name='New' value='  ".$app_strings['LBL_NEW_BUTTON_LABEL']."  '>\n";
$button .= "</form>\n";
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){	
		$header_text = "&nbsp;<a href='index.php?action=index&module=DynamicLayout&from_action=ListView&from_module=".$_REQUEST['module'] ."'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>";
}
$ListView = new ListView();
$ListView->initNewXTemplate( 'modules/Releases/ListView.html',$mod_strings);
$ListView->xTemplateAssign("DELETE_INLINE_PNG",  get_image($image_path.'delete_inline','align="absmiddle" alt="'.$app_strings['LNK_DELETE'].'" border="0"'));
$ListView->setHeaderTitle($mod_strings['LBL_LIST_FORM_TITLE']. $header_text );
$ListView->setHeaderText($button);
$ListView->processListView($focus, "main", "RELEASE");

if ($is_edit) {
	$log->info("Release edit view");

	$edit_botton = '<form name="EditView" method="POST" action="index.php">';
			$edit_botton .= '<input type="hidden" name="module" value="Releases">';
			$edit_botton .= '<input type="hidden" name="record" value="'.$focus->id.'">';
			$edit_botton .= '<input type="hidden" name="action">';
			$edit_botton .= '<input type="hidden" name="edit">';
			$edit_botton .= '<input type="hidden" name="return_module" value="Releases">';
			$edit_botton .= '<input type="hidden" name="return_action" value="index">';
			$edit_botton .= '<input type="hidden" name="return_id" value="">';
		$edit_botton .= '<input title="'.$app_strings['LBL_SAVE_BUTTON_TITLE'].'" accessKey="'.$app_strings['LBL_SAVE_BUTTON_KEY'].'" class="button" onclick="this.form.action.value=\'Save\'; return check_form(\'EditView\')" type="submit" name="button" value="'.$app_strings['LBL_SAVE_BUTTON_LABEL'].'" > ';
		$edit_botton .= '<input title="'.$app_strings['LBL_SAVE_NEW_BUTTON_TITLE'].'" accessKey="'.$app_strings['LBL_SAVE_NEW_BUTTON_KEY'].'" class="button" onclick="this.form.action.value=\'Save\'; this.form.edit.value=\'true\'; this.form.return_action.value=\'EditView\'; return check_form(\'EditView\')" type="submit" name="button" value="'.$app_strings['LBL_SAVE_NEW_BUTTON_LABEL'].'" >';
		if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){	
		$header_text = "&nbsp;<a href='index.php?action=index&module=DynamicLayout&edit=true&from_action=EditView&from_module=".$_REQUEST['module'] ."'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>";
		}
		echo get_form_header($mod_strings['LBL_RELEASE']." ".$focus->name . '&nbsp;' . $header_text,$edit_botton , false);
	$xtpl=new XTemplate ('modules/Releases/EditView.html');

	$xtpl->assign("MOD", $mod_strings);
	$xtpl->assign("APP", $app_strings);

	if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
	if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
	if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
	$xtpl->assign("THEME", $theme);
	$xtpl->assign("IMAGE_PATH", $image_path);
	$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
	$xtpl->assign("JAVASCRIPT", get_set_focus_js());
	$xtpl->assign("ID", $focus->id);
	$xtpl->assign('NAME', $focus->name);
	$xtpl->assign('STATUS', $focus->status);

	$xtpl->assign('STATUS_OPTIONS', get_select_options_with_id($mod_strings['release_status_dom'], $focus->status));

	if (empty($focus->list_order)) $xtpl->assign('LIST_ORDER', count($focus->get_releases(FALSE, 'All'))+1);
	else $xtpl->assign('LIST_ORDER', $focus->list_order);

// adding custom fields:
require_once('modules/DynamicFields/templates/Files/EditView.php');


	$xtpl->parse("main");
	$xtpl->out("main");

	require_once('include/javascript/javascript.php');
	$javascript = new javascript();
	$javascript->setFormName('EditView');
	$javascript->setSugarBean($focus);
	$javascript->addAllFields('');
	echo $javascript->getScript();
}
?>
