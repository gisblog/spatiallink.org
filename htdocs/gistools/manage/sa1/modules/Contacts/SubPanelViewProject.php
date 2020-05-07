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
 * $Id: SubPanelViewProject.php,v 1.1 2005/04/19 07:19:55 andrew Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('include/ListView/ListView.php');

class SubPanelViewContactsAndUsers{

var $hideUsers = false;
var $hideContacts = false;
var $contacts_list = null;
var $users_list = null;
var $hideNewButton = false;
var $focus;

function setFocus(&$value){
	$this->focus =(object) $value;
}

function setContactsList(&$value){
	$this->contacts_list =$value;
}

function setUsersList(&$value){
	$this->users_list =$value;
}

function setHideUsers($value){
	$this->hideUsers = $value;
}
function setHideContacts($value){
	$this->hideContacts = $value;
}
function setHideNewButton($value){
	$this->hideNewButton = $value;
}

function SubPanelViewContactsAndUsers(){
	global $theme;
	$theme_path="themes/".$theme."/";
	require_once($theme_path.'layout_utils.php');
}

function getHeaderText($action, $currentModule)
{
	global $app_strings;
	$button  = "<form border='0' action='index.php' method='post' name='form' id='form'>\n";
	$button .= "<input type='hidden' name='module' value='Contacts'>\n";
	$button .= "<input title='".$app_strings['LBL_SELECT_BUTTON_TITLE']
		."' accessyKey='".$app_strings['LBL_SELECT_BUTTON_KEY']
		."' type='button' class='button' value='  "
		.$app_strings['LBL_SELECT_BUTTON_LABEL']
		."  ' name='button' onclick='window.open(\"index.php?module=Contacts&action=Popup&html=Popup_picker&form=ProjectDetailView&form_submit=true&query=true\",\"new\",\"width=600,height=400,resizable=1,scrollbars=1\");'>\n";
	$button .= "</form>\n";
	return $button;
}

function ProcessSubPanelListView($xTemplatePath, &$mod_strings,$action, $curModule=''){
	global $currentModule,$app_strings,$image_path;
	if(empty($curModule))
		$curModule = $currentModule;

	if ( ! empty($_REQUEST['module']))
	{
		$curModule = $_REQUEST['module'];
	}

	$ListView = new ListView();
	$ListView->initNewXTemplate($xTemplatePath,$mod_strings);
	$ListView->xTemplateAssign("RETURN_URL", "&return_module=".$curModule."&return_action=DetailView&return_id=".$this->focus->id);
	$ListView->xTemplateAssign("RECORD_ID",  $this->focus->id);
	$ListView->xTemplateAssign("EDIT_INLINE_PNG",  get_image($image_path.'edit_inline','align="absmiddle" alt="'.$app_strings['LNK_EDIT'].'" border="0"'));
$ListView->xTemplateAssign("DELETE_INLINE_PNG",  get_image($image_path.'delete_inline','align="absmiddle" alt="'.$app_strings['LNK_DELETE'].'" border="0"'));
$ListView->xTemplateAssign("REMOVE_INLINE_PNG",  get_image($image_path.'delete_inline','align="absmiddle" alt="'.$app_strings['LNK_REMOVE'].'" border="0"'));
	global $current_user;
	$header_text= '';

	if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){	
	
	$exploded = explode('/', $xTemplatePath);
	$file_name = $exploded[sizeof($exploded) - 1];
	$file_name = str_replace('.html', '', $file_name);
	$mod_name =  $exploded[sizeof($exploded) - 2];
	$header_text= "&nbsp;<a href='index.php?action=index&module=DynamicLayout&from_action=$file_name&from_module=$mod_name&mod_lang=".$_REQUEST['module']."'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>";
}
	$ListView->setHeaderTitle($mod_strings['LBL_CONTACT_SUBPANEL_TITLE']. $header_text);
	$ListView->setHeaderText($this->getHeaderText($action, $curModule));
	if(!$this->hideContacts){

		if(!isset($this->contacts_list)){
			$this->setContactsList($this->focus->get_contacts());
		}

		$ListView->processListView($this->contacts_list, "contacts", "CONTACT");
	}

	if(!$this->hideUsers){
		if(!$this->hideContacts){
		$ListView->setDisplayHeaderAndFooter(false);
		}
		if(!isset($this->users_list)) {
			$this->setUsersList($this->focus->get_users());
		}
		$ListView->processListView($this->users_list, "users", "USER");
	}
}
}
?>
