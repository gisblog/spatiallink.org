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
 * $Id: SubPanelView.php,v 1.15 2005/04/14 18:03:43 lam Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('include/ListView/ListView.php');

class SubPanelViewNotes {
	
var $notes_list = null;
var $hideNewButton = false;
var $focus;

function setFocus(&$value){
	$this->focus =(object) $value;		
}


function setNotesList(&$value){
	$this->notes_list =$value;		
}

function setHideNewButton($value){
	$this->hideNewButton = $value;	
}

function SubPanelViewNotes(){
	global $theme;
	$theme_path="themes/".$theme."/";
	require_once($theme_path.'layout_utils.php');
}

function getHeaderText($action, $currentModule){
	global $app_strings;
	$button  = "<table cellspacing='0' cellpadding='0' border='0'><form border='0' action='index.php' method='post' name='form' id='form'>\n";
	$button .= "<input type='hidden' name='module' value='Notes'>\n";














	if(!$this->hideNewButton){
		$button .= "<td><input title='".$app_strings['LBL_NEW_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_NEW_BUTTON_KEY']."' class='button' onclick=\"this.form.action.value='EditView'\" type='submit' name='button' value='  ".$app_strings['LBL_NEW_BUTTON_LABEL']."  '></td>\n";
	}
	$button .= "</tr></form></table>\n";
	return $button;
}

function ProcessSubPanelListView($xTemplatePath, &$mod_strings,$action, $curModule=''){
	global $currentModule,$image_path,$app_strings;
	if(empty($curModule))
		$curModule = $currentModule;
	$ListView = new ListView();
	global $current_user;
$header_text = '';
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){	
		$header_text = "&nbsp;<a href='index.php?action=index&module=DynamicLayout&from_action=SubPanelView&from_module=Notes&record=". $this->focus->id."'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>";
}
	$ListView->initNewXTemplate($xTemplatePath,$mod_strings);
	$ListView->xTemplateAssign("RETURN_URL", "&return_module=".$curModule."&return_action=DetailView&return_id=".$this->focus->id);
	$ListView->xTemplateAssign("DELETE_INLINE_PNG",  get_image($image_path.'delete_inline','align="absmiddle" alt="'.$app_strings['LNK_DELETE'].'" border="0"'));
	$ListView->xTemplateAssign("EDIT_INLINE_PNG",  get_image($image_path.'edit_inline','align="absmiddle" alt="'.$app_strings['LNK_EDIT'].'" border="0"'));
	$ListView->xTemplateAssign("RECORD_ID",  $this->focus->id);
	$ListView->setHeaderTitle($mod_strings['LBL_MODULE_NAME']. $header_text);
	$ListView->setHeaderText($this->getHeaderText($action, $curModule));
	$ListView->processListView($this->notes_list, "notes", "NOTE");
}

}
?>
