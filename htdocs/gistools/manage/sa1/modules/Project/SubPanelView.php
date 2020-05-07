<?php
/**
 * Project sub-panel
 *
 * PHP version 4
 *
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
 */

// $Id: SubPanelView.php,v 1.6.2.2 2005/05/17 22:29:38 andrew Exp $

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('include/ListView/ListView.php');

global $app_strings;
//we don't want the parent module's string file, but rather the string file specifc to this subpanel
global $current_language;
$current_module_strings = return_module_language($current_language, 'Project');

global $currentModule;
global $theme;
global $focus;
global $action;

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

// focus_list is the means of passing data to a SubPanelView.
global $focus_list;

$button  = "<form action='index.php' method='post' name='form' id='form'>\n";
$button .= "<input type='hidden' name='module' value='Project'>\n";
$button .= "<input type='hidden' name='relation_id' value='$focus->id'>\n";
$button .= "<input type='hidden' name='relation_type' value='$currentModule'>\n";
$button .= "<input type='hidden' name='return_module' value='".$currentModule."'>\n";
$button .= "<input type='hidden' name='return_action' value='".$action."'>\n";
$button .= "<input type='hidden' name='return_id' value='".$focus->id."'>\n";
$button .= "<input type='hidden' name='action'>\n";
if (!empty($focus->object_name) && $focus->object_name == 'Opportunity') {
 	$button .= "<input type='hidden' name='account_id' value='$focus->account_id'>\n";
 	$button .= "<input type='hidden' name='opportunity_name' value='$focus->name'>\n";
}
$button .= "<input title='"
	. $app_strings['LBL_NEW_BUTTON_TITLE']
	. "' accessyKey='".$app_strings['LBL_NEW_BUTTON_KEY']
	. "' class='button' onclick=\"this.form.action.value='EditView'\" type='submit' name='New' value='  "
	. $app_strings['LBL_NEW_BUTTON_LABEL']."  '>\n";

$button .= "</form>\n";

$ListView = new ListView();
$ListView->initNewXTemplate( 'modules/Project/SubPanelView.html',$current_module_strings);
$ListView->xTemplateAssign("EDIT_INLINE_PNG",
	get_image($image_path.'edit_inline','align="absmiddle" alt="'.$app_strings['LNK_EDIT'].'" border="0"'));
$ListView->xTemplateAssign("RETURN_URL",
	"&return_module=".$currentModule."&return_action=DetailView&return_id=".$focus->id);

$header_text = '';
if(is_admin($current_user)
	&& $_REQUEST['module'] != 'DynamicLayout'
	&& !empty($_SESSION['editinplace']))
{	
	$header_text = " <a href='index.php?action=index&module=DynamicLayout&from_action="
		.$_REQUEST['action']
		."&from_module=".$_REQUEST['module'] ."&record="
		.$_REQUEST['record']. "'>"
		.get_image($image_path."EditLayout",
			"border='0' alt='Edit Layout' align='bottom'")."</a>";
}

$ListView->setHeaderTitle($current_module_strings['LBL_PROJECT_SUBPANEL_TITLE'] . $header_text);
$ListView->setHeaderText($button);
$ListView->processListView($focus_list, "main", "PROJECT");

?>
