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
/*********************************************************************************
 * $Id: AccountFormBase.php,v 1.29.2.3 2005/05/06 06:22:21 majed Exp $
 * Description:  base form for account
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
 
class AccountFormBase{
	
	
function checkForDuplicates($prefix){
	require_once('include/formbase.php');
	require_once('modules/Accounts/Account.php');
	$focus =& new Account();
	$query = '';
	$baseQuery = 'select id, name, website, billing_address_city  from accounts where deleted!=1 and ';
	if(!empty($_POST[$prefix.'name'])){	
		$query = $baseQuery ."  name like '".$_POST[$prefix.'name']."%'";
	}
	
	if(!empty($_POST[$prefix.'billing_address_city']) || !empty($_POST[$prefix.'shipping_address_city'])){	
		
		$temp_query = '';
		if(!empty($_POST[$prefix.'billing_address_city'])){	
			if(empty($temp_query)){
				$temp_query =  "  billing_address_city like '".$_POST[$prefix.'billing_address_city']."%'";
			}else {
				$temp_query .= "or billing_address_city like '".$_POST[$prefix.'billing_address_city']."%'";
			}
		}
		if(!empty($_POST[$prefix.'shipping_address_city'])){	
			if(empty($temp_query)){
				$temp_query = "  shipping_address_city like '".$_POST[$prefix.'shipping_address_city']."%'";
			}else {
				$temp_query .= "or shipping_address_city like '".$_POST[$prefix.'shipping_address_city']."%'";
			}
		}
		if(empty($query)){
			$query .= $baseQuery;	
		}else{
			$query .= ' AND ';	
		}
		$query .=   ' ('. $temp_query . ' ) ';
		
	}
		
	if(!empty($query)){
		$rows = array();
		require_once('include/database/PearDatabase.php');
		$db = new PearDatabase();
		$result =& $db->query($query);
		if($db->getRowCount($result) == 0){
			return null;
		}
		for($i = 0; $i < $db->getRowCount($result); $i++){
			$rows[$i] = $db->fetchByAssoc($result, $i);	
		}
		return $rows;		
	}
	return null;
}


function buildTableForm($rows, $mod='Accounts'){
	global $odd_bg, $even_bg, $action;
	if(!empty($mod)){
	global $current_language;
	$mod_strings = return_module_language($current_language, $mod);
	}else global $mod_strings;
	global $app_strings;
	$cols = sizeof($rows[0]) * 2 + 1;
	if ($action != 'ShowDuplicates') 
	{
		$form = "<form action='index.php' method='post' id='dupAccounts'  name='dupAccounts'><input type='hidden' name='selectedAccount' value=''>";
		$form .= '<table width="100%"><tr><td>'.$mod_strings['MSG_DUPLICATE']. '</td></tr><tr><td height="20"></td></tr></table>';
		unset($_POST['selectedAccount']);
	}
	else 
	{
		$form = '<table width="100%"><tr><td>'.$mod_strings['MSG_SHOW_DUPLICATES']. '</td></tr><tr><td height="20"></td></tr></table>';
	}

	$form .=  get_form_header($mod_strings['LBL_DUPLICATE'], "", '');
	$form .= "<table width='100%' cellpadding='0' cellspacing='0'>	<tr class='listViewThS1'>	";
	if ($action != 'ShowDuplicates') 
	{
		$form .= "<td class='listViewThS1'> &nbsp;</td>";
	}
	require_once('include/formbase.php');
	$form .= getPostToForm();
	if(isset($rows[0])){
		foreach ($rows[0] as $key=>$value){
			if($key != 'id'){
									
					$form .= "<td class='listViewThS1'>". $mod_strings[$mod_strings['db_'.$key]]. "</td>";
		}}
	
		$form .= "</tr>";
	}
		
	$bgcolor = $odd_bg;
	$rowColor = 'oddListRowS1';
	foreach($rows as $row){
		
		$form .= "<tr class='$rowColor'  bgcolor='$bgcolor'>";	
		if ($action != 'ShowDuplicates') 
		{
		$form .= "<td width='1%' class='$rowColor' nowrap><a href='#' onclick='document.dupAccounts.selectedAccount.value=\"${row['id']}\"; document.dupAccounts.submit(); '>[${app_strings['LBL_SELECT_BUTTON_LABEL']}]</a>&nbsp;&nbsp;</td>\n";	
		}
		foreach ($row as $key=>$value){
				if($key != 'id'){
								
					$form .= "<td class='$rowColor'><a target='_blank' href='index.php?module=Accounts&action=DetailView&record=${row['id']}'>$value</a></td>\n";
		
				}}
		
		if($rowColor == 'evenListRowS1'){
			$rowColor = 'oddListRowS1';	
			$bgcolor = $odd_bg;
		}else{
			 $rowColor = 'evenListRowS1';
			 $bgcolor = $even_bg;
		}
		$form .= "</tr>";
	}
	$form .= "<tr class='listViewThS1'><td colspan='$cols' class='blackline'></td></tr>";
	if ($action == 'ShowDuplicates') 
	{
		$form .= "</table><br><input type='hidden' name='selectedAccount' id='selectedAccount' value=''><input title='${app_strings['LBL_SAVE_BUTTON_TITLE']}' accessKey='${app_strings['LBL_SAVE_BUTTON_KEY']}' class='button' onclick=\"this.form.action.value='Save';\" type='submit' name='button' value='  ${app_strings['LBL_SAVE_BUTTON_LABEL']}  '> <input title='${app_strings['LBL_CANCEL_BUTTON_TITLE']}' accessKey='${app_strings['LBL_CANCEL_BUTTON_KEY']}' class='button' onclick=\"this.form.action.value='ListView'; this.form.module.value='Accounts';this.form.assigned_user_id.value='';\" type='submit' name='button' value='  ${app_strings['LBL_CANCEL_BUTTON_LABEL']}  '></form>";
	}
	else 
	{
		$form .= "</table><BR><input type='submit' class='button' name='ContinueAccount' value='${mod_strings['LNK_NEW_ACCOUNT']}'></form>\n";
	}
	return $form;
	
	
		
}

function getForm($prefix, $mod='', $form=''){
if(!empty($mod)){
	global $current_language;
	$mod_strings = return_module_language($current_language, $mod);
}else global $mod_strings;
global $app_strings;
$lbl_save_button_title = $app_strings['LBL_SAVE_BUTTON_TITLE'];
$lbl_save_button_key = $app_strings['LBL_SAVE_BUTTON_KEY'];
$lbl_save_button_label = $app_strings['LBL_SAVE_BUTTON_LABEL'];


$the_form = get_left_form_header($mod_strings['LBL_NEW_FORM_TITLE']);
$the_form .= <<<EOQ
		<form name="${prefix}AccountSave" onSubmit="return check_form('${prefix}AccountSave');" method="POST" action="index.php">
			<input type="hidden" name="${prefix}module" value="Accounts">
			<input type="hidden" name="${prefix}action" value="Save">
EOQ;
$the_form .= $this->getFormBody($prefix, $mod, $prefix."AccountSave");
$the_form .= <<<EOQ
		<p><input title="$lbl_save_button_title" accessKey="$lbl_save_button_key" class="button" type="submit" name="button" value="  $lbl_save_button_label  " ></p>
		</form>

EOQ;
$the_form .= get_left_form_footer();
$the_form .= get_validate_record_js();

return $the_form;	
}


function getFormBody($prefix,$mod='', $formname=''){
global $mod_strings;
$temp_strings = $mod_strings;
if(!empty($mod)){
	global $current_language;
	$mod_strings = return_module_language($current_language, $mod);
}
	global $app_strings;
global $current_user;

$lbl_required_symbol = $app_strings['LBL_REQUIRED_SYMBOL'];
$lbl_account_name = $mod_strings['LBL_ACCOUNT_NAME'];
$lbl_phone = $mod_strings['LBL_PHONE'];
$lbl_website = $mod_strings['LBL_WEBSITE'];
$lbl_save_button_title = $app_strings['LBL_SAVE_BUTTON_TITLE'];
$lbl_save_button_key = $app_strings['LBL_SAVE_BUTTON_KEY'];
$lbl_save_button_label = $app_strings['LBL_SAVE_BUTTON_LABEL'];
$user_id = $current_user->id;

	$form = <<<EOQ
			<p><input type="hidden" name="record" value="">
			<input type="hidden" name="email1" value="">
			<input type="hidden" name="email2" value="">
			<input type="hidden" name="assigned_user_id" value='${user_id}'>
			<input type="hidden" name="action" value="Save">
		$lbl_account_name&nbsp;<span class="required">$lbl_required_symbol</span><br>
		<input name='name' type="text" value=""><br>
		$lbl_phone<br>
		<input name='phone_office' type="text" value=""><br>
		$lbl_website<br>
		<input name='website' type="text" value="http://"></p>
		
		 
EOQ;
require_once('include/javascript/javascript.php');
require_once('modules/Accounts/Account.php');
$javascript = new javascript();
$javascript->setFormName($formname);
$javascript->setSugarBean(new Account());
$javascript->addRequiredFields($prefix);
$form .=$javascript->getScript();
$mod_strings = $temp_strings;
return $form;
}



function getWideFormBody($prefix, $mod='',$formname='',  $contact=''){
	require_once('modules/Contacts/Contact.php');
	if(empty($contact)){
		$contact = new Contact();	
	}
global $mod_strings;
$temp_strings = $mod_strings;
if(!empty($mod)){
	global $current_language;
	$mod_strings = return_module_language($current_language, $mod);
}
global $app_strings;
global $current_user;
$account = new Account();

$lbl_required_symbol = $app_strings['LBL_REQUIRED_SYMBOL'];
$lbl_account_name = $mod_strings['LBL_ACCOUNT_NAME'];
$lbl_phone = $mod_strings['LBL_PHONE'];
$lbl_website = $mod_strings['LBL_WEBSITE'];
if (isset($contact->assigned_user_id)) {
	$user_id=$contact->assigned_user_id;
} else {
	$user_id = $current_user->id;
}







		$form="";




	$form .= <<<EOQ
		<input type="hidden" name="${prefix}record" value="">
		<input type="hidden" name="${prefix}phone_fax" value="{$contact->phone_fax}">
		<input type="hidden" name="${prefix}phone_other" value="{$contact->phone_other}">
		<input type="hidden" name="${prefix}email1" value="{$contact->email1}">
		<input type="hidden" name="${prefix}email2" value="{$contact->email2}">
		<input type='hidden' name='${prefix}billing_address_street' value='{$contact->primary_address_street}'><input type='hidden' name='${prefix}billing_address_city' value='{$contact->primary_address_city}'><input type='hidden' name='${prefix}billing_address_state'   value='{$contact->primary_address_state}'><input type='hidden' name='${prefix}billing_address_postalcode'   value='{$contact->primary_address_postalcode}'><input type='hidden' name='${prefix}billing_address_country'  value='{$contact->primary_address_country}'>
		<input type='hidden' name='${prefix}shipping_address_street' value='{$contact->primary_address_street}'><input type='hidden' name='${prefix}shipping_address_city' value='{$contact->primary_address_city}'><input type='hidden' name='${prefix}shipping_address_state'   value='{$contact->primary_address_state}'><input type='hidden' name='${prefix}shipping_address_postalcode'   value='{$contact->primary_address_postalcode}'><input type='hidden' name='${prefix}shipping_address_country'  value='{$contact->primary_address_country}'>
		<input type="hidden" name="${prefix}assigned_user_id" value='${user_id}'>
		<table width='100%' border="0" cellpadding="0" cellspacing="0">
		<tr>
		<td width="20%" nowrap class="dataLabel">$lbl_account_name&nbsp;<span class="required">$lbl_required_symbol</span></td>
		<TD width="80%" nowrap class="dataLabel">{$mod_strings['LBL_DESCRIPTION']}</TD>
		</tr>
		<tr>
		<td nowrap class="dataField"><input name='{$prefix}name' type="text" value="$contact->account_name"></td>
		<TD rowspan="5" class="dataField"><textarea name='{$prefix}description' rows='6' cols='50'></textarea></TD>
		</tr>
		<tr>
		<td nowrap class="dataLabel">$lbl_phone</td>
		</tr>
		<tr>
		<td nowrap class="dataField"><input name='{$prefix}phone_office' type="text" value="$contact->phone_work"></td>
		</tr>
		<tr>
		<td nowrap class="dataLabel">$lbl_website</td>
		</tr>
		<tr>
		<td nowrap class="dataField"><input name='{$prefix}website' type="text" value="http://"></td>
		</tr>
		</TABLE>
EOQ;
	require_once('include/javascript/javascript.php');
require_once('modules/Accounts/Account.php');
$javascript = new javascript();
$javascript->setFormName($formname);
$javascript->setSugarBean(new Account());
$javascript->addRequiredFields($prefix);
$form .=$javascript->getScript();
$mod_strings = $temp_strings;
	return $form;
}


function handleSave($prefix,$redirect=true, $useRequired=false){
	require_once('modules/Accounts/Account.php');
	require_once('include/logging.php');
	require_once('include/formbase.php');
	$local_log =& LoggerManager::getLogger('AccountSaveForm');
	$focus =& new Account();
	if($useRequired &&  !checkRequired($prefix, array_keys($focus->required_fields))){
		return null;
	}
//bug fix, moved this line 7 lines up to occure before the check_notify - jgreen	
	$focus = populateFromPost($prefix, $focus);
//end bug fix	
	if (isset($GLOBALS['check_notify'])) {
		$check_notify = $GLOBALS['check_notify'];
	}
	else {
		$check_notify = FALSE;
	}
	
	if (empty($_POST['record']) && empty($_POST['dup_checked'])) {
		$duplicateAccounts = $this->checkForDuplicates($prefix);
		if(isset($duplicateAccounts)){
			$get='module=Accounts&action=ShowDuplicates';
			
			//add all of the post fields to redirect get string
			foreach ($focus->column_fields as $field) 
			{
				if (!empty($focus->$field))
				{
					$get .= "&Accounts$field=".urlencode($focus->$field);
				}	
			}
			
			foreach ($focus->additional_column_fields as $field) 
			{
				if (!empty($focus->$field))
				{
					$get .= "&Accounts$field=".urlencode($focus->$field);
				}	
			}

			//create list of suspected duplicate account id's in redirect get string
			$i=0;
			foreach ($duplicateAccounts as $account)
			{
				$get .= "&duplicate[$i]=".$account['id'];
				$i++;
			}

			//add return_module, return_action, and return_id to redirect get string
			$get .= "&return_module=";
			if(!empty($_POST['return_module'])) $get .= $_POST['return_module'];
			else $get .= "Accounts";
			$get .= "&return_action=";
			if(!empty($_POST['return_action'])) $get .= $_POST['return_action'];
			else $get .= "DetailView";
			if(!empty($_POST['return_id'])) $get .= "&return_id=".$_POST['return_id'];

			//echo $get;
			//die;
			//now redirect the post to modules/Accounts/ShowDuplicates.php
			header("Location: index.php?$get");
			return null;
		}
	}
	$focus->save($check_notify);
	$return_id = $focus->id;
	$local_log->debug("Saved record with id of ".$return_id);
	if($redirect){
		handleRedirect($return_id,'Accounts');
	}else{
		return $focus;	
	}
}


}
?>
