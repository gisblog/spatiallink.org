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
 * $Id: DetailView.php,v 1.77 2005/04/27 23:35:48 majed Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Contacts/Contact.php');
require_once('modules/Contacts/Forms.php');

global $mod_strings;
global $app_strings;
global $app_list_strings;


$focus =& new Contact();


if(!empty($_REQUEST['record'])) {
    $result = $focus->retrieve($_REQUEST['record']);
    if($result == null)
    {
    	sugar_die("Error retrieving record.  You may not be authorized to view this record.");
    }
}
else {
	header("Location: index.php?module=Contacts&action=index");
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

$log->info("Contact detail view");

$xtpl=new XTemplate ('modules/Contacts/DetailView.html');
$sub_xtpl = new XTemplate ('modules/Contacts/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("PREFORM", "<form name='vcard' action='vCard.php'><input type='hidden' name='contact_id' value='".$focus->id."'></form>");
$xtpl->assign("VCARD_LINK", "<input type='button' class='button' name='vCardButton' value='vCard' onClick='document.vcard.submit();' title='vCard'>");
$xtpl->assign("THEME", $theme);
$xtpl->assign("GRIDLINE", $gridline);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("ACCOUNT_NAME", $focus->account_name);
$xtpl->assign("ACCOUNT_ID", $focus->account_id);
$xtpl->assign("LEAD_SOURCE", $app_list_strings['lead_source_dom'][$focus->lead_source]);
$xtpl->assign("SALUTATION", $app_list_strings['salutation_dom'][$focus->salutation]."&nbsp;");
$xtpl->assign("FIRST_NAME", $focus->first_name);
$xtpl->assign("LAST_NAME", $focus->last_name);
$xtpl->assign("TITLE", $focus->title);
$xtpl->assign("DEPARTMENT", $focus->department);
if ($focus->birthdate == '0000-00-00') $xtpl->assign("BIRTHDATE", '');
else $xtpl->assign("BIRTHDATE", $focus->birthdate);
if ($focus->do_not_call == 'on') $xtpl->assign("DO_NOT_CALL", "checked");
$xtpl->assign("ASSIGNED_TO", $focus->assigned_user_name);
$xtpl->assign("REPORTS_TO_ID", $focus->reports_to_id);
$xtpl->assign("REPORTS_TO_NAME", $focus->reports_to_name);
$xtpl->assign("PHONE_HOME", $focus->phone_home);
$xtpl->assign("PHONE_MOBILE", $focus->phone_mobile);
$xtpl->assign("PHONE_WORK", $focus->phone_work);
$xtpl->assign("PHONE_OTHER", $focus->phone_other);
$xtpl->assign("PHONE_FAX", $focus->phone_fax);
$xtpl->assign("EMAIL1", $focus->email1);
$xtpl->assign("EMAIL2", $focus->email2);
$xtpl->assign("ASSISTANT", $focus->assistant);
$xtpl->assign("ASSISTANT_PHONE", $focus->assistant_phone);
if ($focus->invalid_email == '1') $xtpl->assign("INVALID_EMAIL", "checked");
$xtpl->assign("CREATED_BY", $focus->created_by_name);
$xtpl->assign("MODIFIED_BY", $focus->modified_by_name);

global $current_user;
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){

	$xtpl->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action=".$_REQUEST['action'] ."&from_module=".$_REQUEST['module'] ."&record=".$_REQUEST['record']. "'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>");
}
if ($focus->email_opt_out == 'on')
{
	$xtpl->assign("EMAIL_OPT_OUT", "checked");
}
$xtpl->assign("PRIMARY_ADDRESS_STREET", nl2br($focus->primary_address_street));
if (empty($focus->primary_address_state))
{
	$xtpl->assign("PRIMARY_ADDRESS_CITY", $focus->primary_address_city);
}
else
{
	$xtpl->assign("PRIMARY_ADDRESS_CITY", $focus->primary_address_city.', ');
}
$xtpl->assign("PRIMARY_ADDRESS_STATE", $focus->primary_address_state);
$xtpl->assign("PRIMARY_ADDRESS_POSTALCODE", $focus->primary_address_postalcode);
$xtpl->assign("PRIMARY_ADDRESS_COUNTRY", $focus->primary_address_country);
$xtpl->assign("ALT_ADDRESS_STREET", nl2br($focus->alt_address_street));
if (empty($focus->alt_address_state))
{
	$xtpl->assign("ALT_ADDRESS_CITY", $focus->alt_address_city);
}
else
{
	$xtpl->assign("ALT_ADDRESS_CITY", $focus->alt_address_city.', ');
}
$xtpl->assign("ALT_ADDRESS_STATE", $focus->alt_address_state);
$xtpl->assign("ALT_ADDRESS_POSTALCODE", $focus->alt_address_postalcode);
$xtpl->assign("ALT_ADDRESS_COUNTRY", $focus->alt_address_country);
$xtpl->assign("DESCRIPTION", nl2br($focus->description));
$xtpl->assign("DATE_MODIFIED",$focus->date_modified);
$xtpl->assign("DATE_ENTERED", $focus->date_entered);

// adding custom fields:
require_once('modules/DynamicFields/templates/Files/DetailView.php');






$xtpl->parse("main.open_source");





$xtpl->parse("main");
$xtpl->out("main");

echo "<BR>\n";
// Now get the list of activities that match this contact.
$focus_tasks_list = & $focus->get_tasks();
$focus_meetings_list = & $focus->get_meetings();
$focus_calls_list = & $focus->get_calls();
$focus_emails_list = & $focus->get_emails();
$focus_notes_list = & $focus->get_notes();

$old_contents = ob_get_contents();
ob_end_clean();

if(array_key_exists('Activities', $modListHeader))
{
if($sub_xtpl->var_exists('subpanel', 'SUBACTIVITIES')){
ob_start();
include('modules/Activities/SubPanelView.php');
echo "<BR>\n";
$subactivities =  ob_get_contents();
ob_end_clean();
}
}

if(array_key_exists('Leads', $modListHeader))
{
if($sub_xtpl->var_exists('subpanel', 'SUBLEADS')){
ob_start();
$focus_list = & $focus->get_leads();
include('modules/Leads/SubPanelView.php');
echo "<BR>\n";
$subleads =ob_get_contents();
ob_end_clean();
}
}


if(array_key_exists('Opportunities', $modListHeader))
{
if($sub_xtpl->var_exists('subpanel', 'SUBOPPORTUNITIES')){
ob_start();
// Now get the list of opportunities that match this one.
$focus_list = & $focus->get_opportunities();

include('modules/Opportunities/SubPanelView.php');
echo "<BR>\n";
$subopps =  ob_get_contents();
ob_end_clean();
}
}








































if(array_key_exists('Cases', $modListHeader))
{
if($sub_xtpl->var_exists('subpanel', 'SUBCASES')){
ob_start();
echo "<p>\n";


// Now get the list of cases that match this one.
$focus_list = & $focus->get_cases();

include('modules/Cases/SubPanelView.php');
echo "<BR>\n";
$subcases =  ob_get_contents();
ob_end_clean();
}
}

if(array_key_exists('Bugs', $modListHeader))
{
if($sub_xtpl->var_exists('subpanel', 'SUBBUGS')){
ob_start();
echo "<p>\n";

$focus_list = & $focus->get_bugs();
include('modules/Bugs/SubPanelView.php');

echo "</p>\n";
$subbugs =  ob_get_contents();
ob_end_clean();
}
}

if(array_key_exists('Contacts', $modListHeader))
{
if($sub_xtpl->var_exists('subpanel', 'SUBCONTACTS')){
ob_start();



// Now get the list of direct reports that match this one.
$focus_list = & $focus->get_direct_reports();

include('modules/Contacts/SubPanelViewContactsAndUsers.php');
$SubPanel = new SubPanelViewContactsAndUsers();
$SubPanel->setFocus($focus);
$SubPanel->setContactsList($focus_list);
$SubPanel->setHideUsers(true);
$SubPanel->ProcessSubPanelListView( 'modules/Contacts/SubPanelViewDirectReport.html',$mod_strings, $action);
$subcontacts =  ob_get_contents();
ob_end_clean();
}
}

if(array_key_exists('Project', $modListHeader))
{
	if($sub_xtpl->var_exists('subpanel', 'SUB_PROJECT'))
	{
		ob_start();
		echo "<p>\n";

		// Now get the list of member accounts that match this one.
		$focus_list = & $focus->get_projects();
		include('modules/Project/SubPanelView.php');
		echo "</p>\n";
		$sub_project =  ob_get_contents();
		ob_end_clean();
	}
}

ob_start();
echo $old_contents;

if(!empty($subactivities))$sub_xtpl->assign('SUBACTIVITIES', $subactivities);
if(!empty($subopps))$sub_xtpl->assign('SUBOPPORTUNITIES', $subopps);
if(!empty($subleads))$sub_xtpl->assign('SUBLEADS', $subleads);
if(!empty($subcases))$sub_xtpl->assign('SUBCASES', $subcases);




if(!empty($subcontacts))$sub_xtpl->assign('SUBCONTACTS', $subcontacts);
if(!empty($subbugs))$sub_xtpl->assign('SUBBUGS', $subbugs);
if(!empty($sub_project))$sub_xtpl->assign('SUB_PROJECT', $sub_project);
$sub_xtpl->parse("subpanel");
$sub_xtpl->out("subpanel");
?>
