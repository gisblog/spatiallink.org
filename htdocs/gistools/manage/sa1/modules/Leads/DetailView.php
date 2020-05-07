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
 * $Id: DetailView.php,v 1.31.2.3 2005/05/13 17:47:53 ajay Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Leads/Lead.php');
require_once('modules/Leads/Forms.php');
require_once('include/TimeDate.php');
$timedate =& new TimeDate();
global $mod_strings;
global $app_strings;
global $app_list_strings;

$focus =& new Lead();

if(!empty($_REQUEST['record'])) {
    $result = $focus->retrieve($_REQUEST['record']);
    if($result == null)
    {
    	sugar_die("Error retrieving record.  You may not be authorized to view this record.");
    }
}
else {
	header("Location: index.php?module=Leads&action=index");
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}
echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->first_name." ".$focus->last_name, true);
echo "\n</p>\n";
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("Lead detail/edit view");

$xtpl=new XTemplate ('modules/Leads/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("PREFORM", "<form name='vcard' action='vCard.php'><input type='hidden' name='contact_id' value='".$focus->id."'><input type='hidden' name='module' value='Lead'></form>");
$xtpl->assign("VCARD_LINK", "<input type='button' class='button' name='vCardButton' value='vCard' onClick='document.vcard.submit();'>");
$xtpl->assign("THEME", $theme);
$xtpl->assign("GRIDLINE", $gridline);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("ACCOUNT_NAME", $focus->account_name);
$xtpl->assign("OPPORTUNITY_NAME", $focus->opportunity_name);
$xtpl->assign("OPPORTUNITY_AMOUNT", $focus->opportunity_amount);
$xtpl->assign("LEAD_SOURCE", $app_list_strings['lead_source_dom'][$focus->lead_source]);
$xtpl->assign("SALUTATION", $app_list_strings['salutation_dom'][$focus->salutation]);
$xtpl->assign("FIRST_NAME", $focus->first_name);
$xtpl->assign("LAST_NAME", $focus->last_name);
$xtpl->assign("TITLE", $focus->title);
$xtpl->assign("DEPARTMENT", $focus->department);

$xtpl->assign("CREATED_BY", $focus->created_by_name);
$xtpl->assign("MODIFIED_BY", $focus->modified_by_name);

if ($focus->do_not_call == 'on') $xtpl->assign("DO_NOT_CALL", "checked");
$xtpl->assign("ASSIGNED_TO", $focus->assigned_user_name);
$xtpl->assign("PHONE_HOME", $focus->phone_home);
$xtpl->assign("PHONE_MOBILE", $focus->phone_mobile);
$xtpl->assign("PHONE_WORK", $focus->phone_work);
$xtpl->assign("PHONE_OTHER", $focus->phone_other);
$xtpl->assign("PHONE_FAX", $focus->phone_fax);
$xtpl->assign("EMAIL1", $focus->email1);
$xtpl->assign("EMAIL2", $focus->email2);

if ($focus->invalid_email == '1') $xtpl->assign("INVALID_EMAIL", "checked");
if ($focus->email_opt_out == 'on') $xtpl->assign("EMAIL_OPT_OUT", "checked");
$xtpl->assign("PRIMARY_ADDRESS_STREET", $focus->primary_address_street);
$xtpl->assign("PRIMARY_ADDRESS_CITY", $focus->primary_address_city);
$xtpl->assign("PRIMARY_ADDRESS_STATE", $focus->primary_address_state);
$xtpl->assign("PRIMARY_ADDRESS_POSTALCODE", $focus->primary_address_postalcode);
$xtpl->assign("PRIMARY_ADDRESS_COUNTRY", $focus->primary_address_country);
$xtpl->assign("ALT_ADDRESS_STREET", $focus->alt_address_street);
$xtpl->assign("ALT_ADDRESS_CITY", $focus->alt_address_city);
$xtpl->assign("ALT_ADDRESS_STATE", $focus->alt_address_state);
$xtpl->assign("ALT_ADDRESS_POSTALCODE", $focus->alt_address_postalcode);
$xtpl->assign("ALT_ADDRESS_COUNTRY", $focus->alt_address_country);
$xtpl->assign("DESCRIPTION", nl2br($focus->description));
$xtpl->assign("DATE_MODIFIED", $focus->date_modified);
$xtpl->assign("DATE_ENTERED", $focus->date_entered);
$xtpl->assign("STATUS",(!empty($focus->status) ? $app_list_strings['lead_status_noblank_dom'][$focus->status]:""));
global $current_user;
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){

	$xtpl->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action=".$_REQUEST['action'] ."&from_module=".$_REQUEST['module'] ."&record=".$_REQUEST['record']. "'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>");
}

// adding custom fields:
require_once('modules/DynamicFields/templates/Files/DetailView.php');






$xtpl->parse("main.open_source");




$xtpl->assign("LEAD_SOURCE_DESCRIPTION", nl2br($focus->lead_source_description));
$xtpl->assign("STATUS_DESCRIPTION", nl2br($focus->status_description));
$xtpl->assign("REFERED_BY", $focus->refered_by);
$preform = "<table width='100%' border='1' cellspacing='0' cellpadding='0'><tr><td><table width='100%'><tr><td>";
$displayPreform = false;
if(isset($focus->contact_id) && isset($focus->contact_name)){
	$displayPreform = true;
	$preform .= $mod_strings["LBL_CONVERTED_CONTACT"]."&nbsp;<a href='index.php?module=Contacts&action=DetailView&record=".$focus->contact_id."'>".$focus->contact_name."</a>";
}
$preform .= "</td><td>";
if(isset($focus->account_id) && !empty($focus->account_id)&& isset($focus->account_name)){
	$displayPreform = true;
	$preform .=$mod_strings["LBL_CONVERTED_ACCOUNT"]."&nbsp;<a href='index.php?module=Accounts&action=DetailView&record=".$focus->account_id."'>".$focus->account_name."</a>";
}
$preform .= "</td><td>";
if(isset($focus->opportunity_id) && !empty($focus->opportunity_id) &&isset($focus->opportunity_name)){
	$displayPreform = true;
	$preform .= $mod_strings["LBL_CONVERTED_OPP"]."&nbsp;<a href='index.php?module=Opportunities&action=DetailView&record=".$focus->opportunity_id."'>".$focus->opportunity_name."</a>";
}
$preform.= "</td></tr></table></td></tr></table>";
if($displayPreform){
	$xtpl->assign("PREVIEW", $preform);
}


$xtpl->parse("main");
$xtpl->out("main");


echo "<BR>\n";
// Now get the list of activities that match this contact.

// check to see whether or not a user has Activities tab enabled

if(array_key_exists('Activities', $modListHeader))
{
	$sub_xtpl = $xtpl;  
	$old_contents = ob_get_contents();   
	ob_end_clean(); 


	if($sub_xtpl->var_exists('subpanel', 'SUBACTIVITIES')){  
		ob_start(); 
		$focus_tasks_list = & $focus->get_tasks();
		$focus_meetings_list = & $focus->get_meetings();
		$focus_calls_list = & $focus->get_calls();
		$focus_emails_list = & $focus->get_emails();
		$focus_notes_list = & $focus->get_notes();
		include('modules/Activities/SubPanelView.php');
		$subactivities =  ob_get_contents();  
		ob_end_clean(); 
	}  
	
	ob_start();
	echo $old_contents;

	if(!empty($subactivities))
		$sub_xtpl->assign('SUBACTIVITIES', $subactivities);
	$sub_xtpl->parse("subpanel");
	$sub_xtpl->out("subpanel");
}


?>
