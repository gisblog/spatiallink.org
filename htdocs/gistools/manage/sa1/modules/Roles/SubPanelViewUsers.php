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
 * $Id: SubPanelViewUsers.php,v 1.2 2005/04/27 04:32:36 joey Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('include/ListView/ListView.php');

global $app_strings;
//we don't want the parent module's string file, but rather the string file specifc to this subpanel
global $current_language;
$current_module_strings = return_module_language($current_language, 'Users');

global $currentModule;

global $theme;
global $focus;
global $action;

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

// focus_list is the means of passing data to a SubPanelView.
global $focus_list;

$button  = "<form action='index.php' method='post' name='UsersForm' id='UsersForm'>\n";
$button .= "<input type='hidden' name='module' value='Users'>\n";
if ($currentModule == 'Roles') {
	$button .= "<input type='hidden' name='record' value='$focus->id'>\n";
}
$button .= "<input type='hidden' name='return_module' value='".$currentModule."'>\n";
$button .= "<input type='hidden' name='return_action' value='".$action."'>\n";
$button .= "<input type='hidden' name='return_id' value='".$focus->id."'>\n";
$button .= "<input type='hidden' name='action'>\n";
$button .= "<input type='hidden' name='report_module'>\n";
$button .= "<input type='hidden' name='id'>\n";

if ($currentModule == 'Roles') 
	$button .= "<input title='".$app_strings['LBL_SELECT_BUTTON_TITLE']." ' accessKey='".$app_strings['LBL_SELECT_BUTTON_KEY']."' type='button' class='button' value='  ".$app_strings['LBL_SELECT_BUTTON_LABEL']."  ' name='button' LANGUAGE=javascript onclick='window.open(\"index.php?module=Users&action=PopupUsers&html=Popup_Users_picker&form=UsersForm&record=".$focus->id."&form_submit=true&query=true\",\"new\",\"width=600,height=400,resizable=1,scrollbars=1\");'>\n";

$button .= "</form>\n";



$ListView = new ListView();
$ListView->initNewXTemplate('modules/Roles/SubPanelViewUsers.html', $current_module_strings);

$ListView->xTemplateAssign("ROLE_RECORD", $focus->id);
$ListView->xTemplateAssign("EDIT_INLINE_PNG",  get_image($image_path.'edit_inline','align="absmiddle" alt="'.$app_strings['LNK_EDIT'].'" border="0"'));
$ListView->xTemplateAssign("REMOVE_INLINE_PNG",  get_image($image_path.'delete_inline','align="absmiddle" alt="'.$app_strings['LNK_REMOVE'].'" border="0"'));

$ListView->xTemplateAssign("RETURN_URL", "&return_module=".$currentModule."&return_action=DetailView&return_id=".$focus->id);
$ListView->setHeaderTitle($current_module_strings['LBL_MODULE_NAME'] );
$ListView->setQuery("", "", "user_name", "USER");
$ListView->setHeaderText($button);
$ListView->processListView($focus_list, "main", "USER");

?>
