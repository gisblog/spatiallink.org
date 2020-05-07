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
 * $Id: SubPanelView.php,v 1.29 2005/04/14 18:03:43 lam Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once("include/ListView/ListView.php");
global $app_strings;
//we don't want the parent module's string file, but rather the string file specifc to this subpanel
global $current_language;
$current_module_strings = return_module_language($current_language, 'Cases');

global $currentModule;
global $theme;
global $focus;
global $action;

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

// focus_list is the means of passing data to a SubPanelView.
global $focus_list;

$button  = "<form  action='index.php' method='post' name='form' id='form'>\n";
$button .= "<input type='hidden' name='module' value='Cases'>\n";
if ($currentModule == 'Accounts') $button .= "<input type='hidden' name='account_id' value='$focus->id'>\n<input type='hidden' name='account_name' value='$focus->name'>\n";
elseif ($currentModule == 'Contacts') {
	$button .= "<input type='hidden' name='account_id' value='$focus->account_id'>\n<input type='hidden' name='account_name' value='$focus->account_name'>\n";
	$button .= "<input type='hidden' name='contact_id' value='$focus->id'>\n";
}
$button .= "<input type='hidden' name='return_module' value='".$currentModule."'>\n";
$button .= "<input type='hidden' name='return_action' value='".$action."'>\n";
$button .= "<input type='hidden' name='return_id' value='".$focus->id."'>\n";
$button .= "<input type='hidden' name='action'>\n";
$button .= "<input title='".$app_strings['LBL_NEW_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_NEW_BUTTON_KEY']."' class='button' onclick=\"this.form.action.value='EditView'\" type='submit' name='New' value='  ".$app_strings['LBL_NEW_BUTTON_LABEL']."  '>\n";
if ($currentModule == 'Accounts') $button .= "<input title='".$app_strings['LBL_SELECT_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_SELECT_BUTTON_KEY']."' type='button' class='button' value='  ".$app_strings['LBL_SELECT_BUTTON_LABEL']."  ' name='button' LANGUAGE=javascript onclick='window.open(\"index.php?module=Cases&action=Popup&html=Popup_picker&form=DetailView&form_submit=true\",\"new\",\"width=600,height=400,resizable=1,scrollbars=1\");'>\n";
elseif ($currentModule == 'Contacts') $button .= "<input title='".$app_strings['LBL_SELECT_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_SELECT_BUTTON_KEY']."' type='button' class='button' value='  ".$app_strings['LBL_SELECT_BUTTON_LABEL']."  ' name='button' LANGUAGE=javascript onclick='window.open(\"index.php?module=Cases&action=Popup&html=Popup_picker&form=ContactDetailView&form_submit=true&query=true&account_id=$focus->account_id&account_name=".urlencode($focus->account_name)."\",\"new\",\"width=600,height=400,resizable=1,scrollbars=1\");'>\n";
elseif ($currentModule == 'Bugs') {
	$button .= "\n<input type='hidden' name='bug_id' value='$focus->id'>\n";
	$button .= "<input title='".$app_strings['LBL_SELECT_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_SELECT_BUTTON_KEY']."' type='button' class='button' value='  ".$app_strings['LBL_SELECT_BUTTON_LABEL']."  ' name='button' LANGUAGE=javascript onclick='window.open(\"index.php?module=Cases&action=Popup&html=Popup_picker&form=BugDetailView&form_submit=true&query=true\",\"new\",\"width=600,height=400,resizable=1,scrollbars=1\");'>\n";
}
else $button .= "<input title='".$app_strings['LBL_SELECT_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_SELECT_BUTTON_KEY']."' type='button' class='button' value='  ".$app_strings['LBL_SELECT_BUTTON_LABEL']."  ' name='button' LANGUAGE=javascript onclick='window.open(\"index.php?module=Cases&action=Popup&html=Popup_picker&form=DetailView&form_submit=true&query=true&account_id=$focus->account_id&account_name=".urlencode($focus->account_name)."\",\"new\",\"width=600,height=400,resizable=1,scrollbars=1\");'>\n";
$button .= "</form>\n";
$ListView = new ListView();
$header_text = '';

if ($currentModule == 'Bugs') {
	if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){	
		$header_text = "&nbsp;<a href='index.php?action=index&module=DynamicLayout&from_action=SubPanelViewBug&from_module=Cases&record=".$_REQUEST['record']."'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>";
	}
	$ListView->initNewXTemplate( 'modules/Cases/SubPanelViewBug.html',$current_module_strings);
}
else {
	if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){	
		$header_text = "&nbsp;<a href='index.php?action=index&module=DynamicLayout&from_action=SubPanelView&from_module=Cases&record=".$_REQUEST['record']."'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>";
	}
	$ListView->initNewXTemplate( 'modules/Cases/SubPanelView.html',$current_module_strings);
}
$ListView->xTemplateAssign("RETURN_URL", "&return_module=".$currentModule."&return_action=DetailView&return_id={$_REQUEST['record']}");
$ListView->xTemplateAssign("EDIT_INLINE_PNG",  get_image($image_path.'edit_inline','align="absmiddle" alt="'.$app_strings['LNK_EDIT'].'" border="0"'));
$ListView->xTemplateAssign("DELETE_INLINE_PNG",  get_image($image_path.'delete_inline','align="absmiddle" alt="'.$app_strings['LNK_REMOVE'].'" border="0"'));
$ListView->setHeaderTitle($current_module_strings['LBL_MODULE_NAME'] . $header_text);
$ListView->setHeaderText($button);
$ListView->processListView($focus_list, "main", "CASE");
?>
