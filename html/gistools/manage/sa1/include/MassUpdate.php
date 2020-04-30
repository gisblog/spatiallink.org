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
 * $Id: MassUpdate.php,v 1.34 2005/04/30 04:24:09 majed Exp $
 * Description:
 ********************************************************************************/

class MassUpdate{
var $sugarbean = null;
function setSugarBean(&$sugar){
	$this->sugarbean =& $sugar;
}

function getDisplayMassUpdateForm($bool){
	global $current_module;
	require_once('include/formbase.php');
	$form = "<form name='displayMassUpdate' method='post' action='index.php'>";
	if($bool)
		$form .= "<input type='hidden' name='mu' value='false'>";
	else $form .="<input type='hidden' name='mu' value='true'>";
	$form .= getAnyToForm('mu');
	$form .= '</form>';
	return $form;

}

function getMassUpdateFormHeader(){
	global $current_module;
	$display = 'none';


	return "<form name='MassUpdate' method='post' action='index.php' onsubmit=\"return check_form('MassUpdate')\"><input type='hidden' name='action' value='index'><input type='hidden' name='module' value='{$_REQUEST['module']}'><input type='hidden' name='massupdate' value='true'><input type='hidden' name='delete' value='false'> ";

}
function handleMassUpdate(){
	require_once('include/formbase.php');
	global $current_user;
	
	
	foreach($_POST as $post=>$value){
			if(empty($value)){
				unset($_POST[$post]);
			}
	}
	if(isset($_POST['mass']) && is_array($_POST['mass'])){
	foreach($_POST['mass'] as $id){
		if(isset($_POST['Delete'])){
			$this->sugarbean->mark_deleted($id);
		}else{
			$this->sugarbean->retrieve($id);
		$old_assigned_user_id = $this->sugarbean->assigned_user_id;
		$_POST['record'] = $id;
		$_GET['record'] = $id;
		$_REQUEST['record'] = $id;
		$newbean = populateFromPost('', $this->sugarbean);
		$newbean->save_from_post = false;
		if (!empty($_POST['assigned_user_id']) 
			&& ($old_assigned_user_id != $_POST['assigned_user_id']) 
			&& ($_POST['assigned_user_id'] != $current_user->id)) 
		{
			$check_notify = TRUE;
		}
		else 
		{
			$check_notify = FALSE;
		}
		
		$newbean->save($check_notify);
	}	}
	}
}
function getMassUpdateForm(){
	$lang_delete = translate('LBL_DELETE');
	$lang_update = translate('LBL_UPDATE');

	if ( ! isset($this->sugarbean->field_defs) || count($this->sugarbean->field_defs) == 0)
	{
	$html = "<table cellpadding='0' cellspacing='0' border='0' width='100%'><tr><td><input type='submit' name='Delete' value='{$lang_delete}'  class='button'></td></tr></table>";
	return $html;
	}
	$should_use = false;
	$html = "<table cellpadding='0' cellspacing='0' border='0' width='100%'><tr><td style='padding-bottom: 2px;'><input type='submit' name='Update' value='{$lang_update}'  class='button'> <input type='submit' name='Delete' value='{$lang_delete}'  class='button'></td></tr></table>

	<table cellpadding='0' cellspacing='0' border='0' width='100%' class='tabForm'><tr><td><table width='100%' border='0' cellspacing='0' cellpadding='0'>";

	$even = true;
	  static $banned = array('date_modified'=>1, 'date_entered'=>1, 'created_by'=>1, 'modified_user_id'=>1);
      foreach($this->sugarbean->field_defs as $field){
      	if(!isset($banned[$field['name']]) && (!isset($field['massupdate']) || (isset($field['massupdate']) && $field['massupdate']))){
      		$newhtml = '';
      		if($even){
      			$newhtml .= "<tr>";
      		}
      		if(isset($field['vname'])){
      			$displayname = translate($field['vname']);
      		}else{
      			$displayname = '';	
      			
      		}
      		
      		switch($field["type"]){

      				case "relate": $even = !$even; $newhtml .= $this->handleRelationship($displayname, $field); break;
      				case "parent":$even = !$even; $newhtml .=$this->addParent($displayname, $field); break;
      				case "contact_id":$even = !$even; $newhtml .=$this->addContactID($displayname, $field["name"]); break;
      				case "assigned_user_name":$even = !$even; $newhtml .= $this->addAssignedUserID($displayname,  $field["name"]); break;
      				case "account_id":$even = !$even; $newhtml .= $this->addAccountID($displayname,  $field["name"]); break;
      				case "account_name":$even = !$even; $newhtml .= $this->addAccountID($displayname,  $field["id_name"]); break;
      				case "enum":$even = !$even; $newhtml .= $this->addStatus($displayname,  $field["name"], translate($field["options"])); break;
      				case "date":$even = !$even; $newhtml .= $this->addDate($displayname,  $field["name"]); break;



      		}
      		if($even){
      			$newhtml .="</tr>";
      		}else{
      			$should_use = true;
      		}
      		if($newhtml != '<tr>' && $newhtml != '</tr>' && $newhtml != '<tr></tr>'){

      			$html.=$newhtml;
      		}


      	}


      }
      $html .="</table></td></tr></table>";
      if($should_use){
      return $html;
      }else{
      	return "<table cellpadding='0' cellspacing='0' border='0' width='100%'><tr><td><input type='submit' name='Delete' value='Delete'  class='button'></td></tr></table>";
      }
}

function endMassUpdateForm(){
	return '</form>';
}

function handleRelationship($displayname, $field)
{

	if(isset($field['module']))
	{
		switch($field['module'])
		{
			case 'Accounts':
				return  $this->addAccountID($displayname, $field['name'], $field['id_name']);
			case 'Contacts':
				return $this->addContactID($displayname, $field['name'], $field['id_name']);
		}

		return '<td></td><td>Not Implemented</td>';
	}

	return '<td></td><td></td>';
}
function addParent($displayname, $field){
	global $app_strings, $app_list_strings;
	$change_parent_button = "<input title='".$app_strings['LBL_CHANGE_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CHANGE_BUTTON_KEY']."'  type='button' class='button' value='".$app_strings['LBL_CHANGE_BUTTON_LABEL']."' name='button' LANGUAGE=javascript onclick='return window.open(\"index.php?module=\"+ document.MassUpdate.{$field['type_name']}.value + \"&action=Popup&html=Popup_picker&form=MassUpdate\",\"\",\"width=600,height=400,resizable=1,scrollbars=1\");'>";
	$parent_type = $field['parent_type'];
	$types = get_select_options_with_id($app_list_strings[$parent_type], '');
	return "<td width='25%' class='dataField' valign='top'><select name='{$field['type_name']}'>$types</select></td><td width='25%' class='dataField'><input name='{$field['id_name']}' type='hidden' value=''><input name='parent_name' readonly type='text' value=''>$change_parent_button</td>";
}

function addContactID($displayname, $varname, $id_name=''){
		global $app_strings;

		if(empty($id_name))
			$id_name = "contact_id";

		if(stristr($varname, "shipping"))
			$pass_val = "shipping";
		elseif(stristr($varname, "billing"))
			$pass_val = "billing";
		else
			$pass_val = "";

		return "<td width='15%' class='dataLabel'>$displayname</td><td width='35%' class='dataField'><input name='{$varname}' readonly type='text' value=''><input name='{$id_name}' type='hidden' value=''>&nbsp;<input title=\"{$app_strings['LBL_CHANGE_BUTTON_TITLE']}\" accessKey='{$app_strings['LBL_CHANGE_BUTTON_KEY']}'  type='button' class='button' value='{$app_strings['LBL_CHANGE_BUTTON_LABEL']}' name='button' LANGUAGE=javascript onclick=\"return window.open('index.php?module=Contacts&action=Popup&html=Popup_picker&form=MassUpdate&input_type={$pass_val}','','width=600,height=400,resizable=1,scrollbars=1');\"></td>";


}

function addAccountID($displayname, $varname, $id_name=''){
		global $app_strings;

		if(empty($id_name))
			$id_name = "account_id";

		if(stristr($varname, "shipping"))
			$pass_val = "shipping";
		elseif(stristr($varname, "billing"))
			$pass_val = "billing";
		else
			$pass_val = '';

		return "<td class='dataLabel'>$displayname </td><td ><input name='{$varname}' type='text' readonly value=''><input name='{$id_name}' type='hidden' value=''>&nbsp;<input  title='".$app_strings['LBL_CHANGE_BUTTON_LABEL']."' accessKey='".$app_strings['LBL_CHANGE_BUTTON_KEY']."' type='button' class='button' value='".$app_strings['LBL_CHANGE_BUTTON_LABEL']."' name=btn1 LANGUAGE=javascript onclick=\"return window.open('index.php?module=Accounts&action=Popup&html=Popup_picker&form=MassUpdateAccount&form_submit=false&input_type={$pass_val}','','width=600,height=400,resizable=1,scrollbars=1');\"></td>";


}












function addAssignedUserID($displayname, $varname){
	$user_array = get_user_array(TRUE, "Active", "");
	$userlist = get_select_options_with_id($user_array, "");
	
$html = <<<EOQ
	<td width="15%" class="dataLabel">$displayname</td>
	<td width="35%" class="dataField"><select  name="$varname">$userlist</select></td>
EOQ;
return $html;

}

function addStatus($displayname, $varname, $options){
	global $app_strings, $app_list_strings;
	$module =$_REQUEST['module'];
	if(!isset($options['']) && !isset($options['0']))
		$options = array_merge(array(''=>''), $options);
	$options = get_select_options_with_id($options, '');

	 $html = <<<EOQ
	<td class="dataLabel" width="15%">$displayname</td>
	<td ><select name='$varname'>$options</select></td>
EOQ;
return $html;
}

function addDate($displayname, $varname){
	require_once('include/TimeDate.php');
$timedate = new TimeDate();
$userformat = '('. $timedate->get_user_date_format().')';
$cal_dateformat = $timedate->get_cal_date_format();
	global $app_strings, $app_list_strings, $theme;
	$module =$_REQUEST['module'];
	$cal_lang = "en";

	$javascriptend = <<<EOQ
		 <script type="text/javascript">
		Calendar.setup ({
			inputField : "${varname}jscal_field", ifFormat : "$cal_dateformat", showsTime : false, button : "${varname}jscal_trigger", singleClick : true, step : 1
		});
		</script>
EOQ;

	 $html = <<<EOQ
	<td class="dataLabel" width="20%">$displayname</td>
	<td class='dataField' width="30%"><input name='$varname' size="12" id='{$varname}jscal_field' maxlength='10' type="text" value=""> <img src="themes/$theme/images/jscalendar.gif" id="{$varname}jscal_trigger" align="absmiddle" alt="Select Date">&nbsp;<span class="dateFormat">$userformat</span>$javascriptend</td>
		<script> addToValidate('MassUpdate', '$varname', 'date',false,  '$displayname');</script>
EOQ;
return $html;

}
}



?>
