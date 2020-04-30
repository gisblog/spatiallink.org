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
 * $Id: BusinessCard.php,v 1.15.2.1 2005/05/06 00:24:39 majed Exp $
 * Description:  Business Card Wizard
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
 
global $app_strings;
global $app_list_strings;
require_once('XTemplate/xtpl.php');
global $theme;
$error_msg = '';
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');
global $current_language;
$mod_strings = return_module_language($current_language, 'Contacts');
echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$mod_strings['LBL_BUSINESSCARD'], true); 
echo "\n</p>\n";
$xtpl=new XTemplate ('modules/Contacts/BusinessCard.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);

$xtpl->assign("HEADER", $mod_strings['LBL_ADD_BUSINESSCARD']);

$xtpl->assign("MODULE", $_REQUEST['module']);
if ($error_msg != '')
{
	$xtpl->assign("ERROR", $error_msg);
	$xtpl->parse("main.error");
}

if(isset($_POST['handle']) && $_POST['handle'] == 'Save'){
	require_once('modules/Contacts/Contact.php');
	require_once('modules/Contacts/ContactFormBase.php');
	$contactForm =& new ContactFormBase();
	require_once('modules/Accounts/AccountFormBase.php');
	$accountForm =& new AccountFormBase();
	require_once('modules/Opportunities/Opportunity.php');
	require_once('modules/Opportunities/OpportunityFormBase.php');
	$oppForm =& new OpportunityFormBase();
	if(!isset($_POST['selectedContact']) && !isset($_POST['ContinueContact'])){
		$duplicateContacts = $contactForm->checkForDuplicates('Contacts');
		if(isset($duplicateContacts)){
			$xtpl->assign('FORMBODY', $contactForm->buildTableForm($duplicateContacts));
			$xtpl->parse('main.formnoborder');
			$xtpl->parse('main');
			$xtpl->out('main');
			return;
		}
	}
	
	if(empty($_POST['selectedAccount']) && empty($_POST['ContinueAccount'])){
		$duplicateAccounts = $accountForm->checkForDuplicates('Accounts');
		
		if(isset($duplicateAccounts)){
			$xtpl->assign('FORMBODY', $accountForm->buildTableForm($duplicateAccounts));
			$xtpl->parse('main.formnoborder');
			$xtpl->parse('main');
			$xtpl->out('main');
			return;
		}
		
	}

	if(isset($_POST['newopportunity']) && $_POST['newopportunity']=='on' &&!isset($_POST['selectedOpportunity']) && !isset($_POST['ContinueOpportunity'])){

		$duplicateOpps = $oppForm->checkForDuplicates('Opportunities');
		if(isset($duplicateOpps)){
			$xtpl->assign('FORMBODY', $oppForm->buildTableForm($duplicateOpps));
			$xtpl->parse('main.formnoborder');
			$xtpl->parse('main');
			$xtpl->out('main');
			return;
		}
	}
	if(!empty($_POST['selectedContact'])){
		$contact =& new Contact();
		$contact->retrieve($_POST['selectedContact']);	
	}else{
		$contact= $contactForm->handleSave('Contacts',false, false);
	}
	if(!empty($_POST['selectedAccount'])){
		$account =& new Account();
		$account->retrieve($_POST['selectedAccount']);	
	}else{
		$account= $accountForm->handleSave('Accounts',false, false);
	}
	if(isset($_POST['newopportunity']) && $_POST['newopportunity']=='on' ){
		if(!empty($_POST['selectedOpportunity'])){
			$opportunity =& new Opportunity();
			$opportunity->retrieve($_POST['selectedOpportunity']);
		}else{
			if(isset($account)){
				$_POST['Opportunitiesaccount_id'] = $account->id;
				$_POST['Opportunitiesaccount_name'] = $account->name;
				
			}
			$opportunity=& $oppForm->handleSave('Opportunities',false, false);
			
		}
	}
	require_once('modules/Notes/NoteFormBase.php');

	$noteForm =& new NoteFormBase();
	if(isset($account))
		$accountnote= $noteForm->handleSave('AccountNotes',false, false);
	if(isset($contact))
		$contactnote= $noteForm->handleSave('ContactNotes',false, false);
	if(isset($opportunity)){
		$opportunitynote= $noteForm->handleSave('OpportunityNotes',false, false);
		}
	if(isset($_POST['newappointment']) && $_POST['newappointment']=='on' ){	
	if(isset($_POST['appointment']) && $_POST['appointment'] == 'Meeting'){
		require_once('modules/Meetings/MeetingFormBase.php');
		$meetingForm = new MeetingFormBase();
		$meeting= $meetingForm->handleSave('Appointments',false, false);
	}else{
		require_once('modules/Calls/CallFormBase.php');
		$callForm = new CallFormBase();
		$call= $callForm->handleSave('Appointments',false, false);	
	}
	}
	
	if(isset($call)){
		if(isset($contact))
			$call->set_calls_contact_invitee_relationship($call->id, $contact->id);
		if(isset($account)){
			$call->set_calls_account_relationship($call->id, $account->id);	
		}else if(isset($opportunity)){
			$call->set_calls_opportunity_relationship($call->id, $opportunity->id);	
		}
		
	}
	if(isset($meeting)){
		if(isset($contact))
			$meeting->set_meetings_contact_invitee_relationship($meeting->id, $contact->id);
		if(isset($account)){
			$meeting->set_meetings_account_relationship($meeting->id, $account->id);	
		}else if(isset($opportunity)){
			$meeting->set_meetings_opportunity_relationship($meeting->id, $opportunity->id);	
		}
	}
	if(isset($account)){
		if(isset($contact)){
			$account->set_account_contact_relationship($account->id, $contact->id);	
		}
		if(isset($opportunity)){
			$account->set_account_opportunity_relationship($account->id, $opportunity->id);	
		}
		if(isset($accountnote)){
			$account->set_account_note_relationship($account->id, $accountnote->id);
		}	
	}
	if(isset($opportunity)){
		if(isset($contact)){
			$opportunity->set_opportunity_contact_relationship($opportunity->id, $contact->id);	
		}
		if(isset($accountnote)){
			$opportunity->set_opportunity_note_relationship($opportunity->id, $accountnote->id);
		}	
	}
	if(isset($contact)){
		if(isset($contactnote)){
			$contact->set_note_contact_relationship($contact->id, $contactnote->id);	
		}
	}
	require_once('data/Tracker.php');
	if(isset($contact)){
		$contact->track_view($current_user->id, 'Contacts');
		if(isset($_POST['selectedContact']) && $_POST['selectedContact'] == $contact->id){
			$xtpl->assign('ROWVALUE', "<LI>".$mod_strings['LBL_EXISTING_CONTACT']." - <a href='index.php?action=DetailView&module=Contacts&record=".$contact->id."'>".$contact->first_name ." ".$contact->last_name."</a>" );
			$xtpl->parse('main.row');
		}else{
			
			$xtpl->assign('ROWVALUE', "<LI>".$mod_strings['LBL_CREATED_CONTACT']." - <a href='index.php?action=DetailView&module=Contacts&record=".$contact->id."'>".$contact->first_name ." ".$contact->last_name."</a>" );
			$xtpl->parse('main.row');
		}
	}
	if(isset($account)){
		$account->track_view($current_user->id, 'Accounts');
		if(isset($_POST['selectedAccount']) && $_POST['selectedAccount'] == $account->id){
			$xtpl->assign('ROWVALUE', "<LI>".$mod_strings['LBL_EXISTING_ACCOUNT']. " - <a href='index.php?action=DetailView&module=Accounts&record=".$account->id."'>".$account->name."</a>");
			$xtpl->parse('main.row');
		}else{
			$xtpl->assign('ROWVALUE', "<LI>".$mod_strings['LBL_CREATED_ACCOUNT']. " - <a href='index.php?action=DetailView&module=Accounts&record=".$account->id."'>".$account->name."</a>");		
			$xtpl->parse('main.row');
		}
		
	}
	if(isset($opportunity)){
		$opportunity->track_view($current_user->id, 'Opportunities');
		if(isset($_POST['selectedOpportunity']) && $_POST['selectedOpportunity'] == $opportunity->id){
			$xtpl->assign('ROWVALUE', "<LI>".$mod_strings['LBL_EXISTING_OPPORTUNITY']. " - <a href='index.php?action=DetailView&module=Opportunities&record=".$opportunity->id."'>".$opportunity->name."</a>");
			$xtpl->parse('main.row');
		}else{
			$xtpl->assign('ROWVALUE', "<LI>".$mod_strings['LBL_CREATED_OPPORTUNITY']. " - <a href='index.php?action=DetailView&module=Opportunities&record=".$opportunity->id."'>".$opportunity->name."</a>");
			$xtpl->parse('main.row');
		}

	}

	if(isset($call)){
		$call->track_view($current_user->id, 'Calls');
		$xtpl->assign('ROWVALUE', "<LI>".$mod_strings['LBL_CREATED_CALL']. " - <a href='index.php?action=DetailView&module=Calls&record=".$call->id."'>".$call->name."</a>");	
		$xtpl->parse('main.row');
		}
	if(isset($meeting)){
		$meeting->track_view($current_user->id, 'Meetings');
		$xtpl->assign('ROWVALUE', "<LI>".$mod_strings['LBL_CREATED_MEETING']. " - <a href='index.php?action=DetailView&module=Calls&record=".$meeting->id."'>".$meeting->name."</a>");	
		$xtpl->parse('main.row');
		}
		$xtpl->assign('ROWVALUE',"&nbsp;");	
		$xtpl->parse('main.row');
		$xtpl->assign('ROWVALUE',"<a href='index.php?module=Contacts&action=BusinessCard'>{$mod_strings['LBL_ADDMORE_BUSINESSCARD']}</a>");	
	$xtpl->parse('main.row');
	$xtpl->parse('main');
	$xtpl->out('main');	
}
	
else{


//CONTACT
$xtpl->assign('FORMHEADER',$mod_strings['LNK_NEW_CONTACT']);
$xtpl->parse("main.startform");
require_once('modules/Contacts/ContactFormBase.php');
$xtpl->assign('OPPNEEDSACCOUNT',$mod_strings['NTC_OPPORTUNITY_REQUIRES_ACCOUNT']);
$contactForm = new ContactFormBase();
$xtpl->assign('FORMBODY',$contactForm->getWideFormBody('Contacts', 'Contacts', 'BusinessCard'));
$xtpl->assign('FORMFOOTER',get_form_footer());
$xtpl->assign('TABLECLASS', 'tabForm');
$xtpl->assign('CLASS', 'dataLabel');
require_once('modules/Notes/NoteFormBase.php');
$noteForm = new NoteFormBase();
$postform = "<div id='contactnotelink'><p><a href='javascript:toggleDisplay(\"contactnote\");addToValidate(\"BusinessCard\", \"ContactNotesname\", \"varchar\", true,\"".$mod_strings['LBL_NOTE_SUBJECT']."\");' class='tabFormLink'>${mod_strings['LNK_NEW_NOTE']}</a></p></div>";
$postform .= '<div id="contactnote" style="display:none">'.'<input type=hidden name=CreateContactNote value=0>'.$noteForm->getFormBody('ContactNotes','Notes','BusinessCard', 85, false).'</div>';
$xtpl->assign('POSTFORM',$postform);
$xtpl->parse("main.form");


$xtpl->assign('HEADER', $app_strings['LBL_RELATED_RECORDS']);
$xtpl->parse("main.hrrow");
//Account
$selectAccountButton = "<div id='newaccountdivlink' style='display:inline'><b>{$mod_strings['LNK_SELECT_ACCOUNT']}</b>&nbsp;<input readonly name='display_account_name' type=\"text\" value=\"\"><input name='selectedAccount' type=\"hidden\" value=''>&nbsp;<input type='button' title=\"{$app_strings['LBL_CHANGE_BUTTON_TITLE']}\" accessKey=\"{$app_strings['LBL_CHANGE_BUTTON_KEY']}\" type=\"button\"  class=\"button\" value='{$app_strings['LBL_CHANGE_BUTTON_LABEL']}' name=btn1 LANGUAGE=javascript onclick='return window.open(\"index.php?module=Accounts&action=Popup&form=BusinessCard&form_submit=false\",\"\",\"width=600,height=400,resizable=1,scrollbars=1\");'> <input type='button' title=\"{$app_strings['LBL_CLEAR_BUTTON_TITLE']}\" accessKey=\"{$app_strings['LBL_CLEAR_BUTTON_KEY']}\" type=\"button\"  class=\"button\" value='{$app_strings['LBL_CLEAR_BUTTON_LABEL']}' name=btn1 LANGUAGE=javascript onclick='document.forms[\"ConvertLead\"].selectedAccount.value=\"\";document.forms[\"ConvertLead\"].display_account_name.value=\"\"; '><br><b>{$app_strings['LBL_OR']}</b></div>";
$xtpl->assign('FORMHEADER',get_form_header($mod_strings['LNK_NEW_ACCOUNT'], '', ''));
require_once('modules/Accounts/AccountFormBase.php');
$accountForm =& new AccountFormBase();
$xtpl->assign('CLASS', 'evenListRow');
$xtpl->assign('FORMBODY',$selectAccountButton."<h5 class='dataLabel'><input class='checkbox' type='checkbox' name='newaccount' onclick='document.forms[\"BusinessCard\"].selectedAccount.value=\"\";document.forms[\"BusinessCard\"].display_account_name.value=\"\";toggleDisplay(\"newaccountdiv\");'>".$mod_strings['LNK_NEW_ACCOUNT']."</h5><div id='newaccountdiv' style='display:none'>".$accountForm->getWideFormBody('Accounts', 'Accounts','BusinessCard', '' ));
$xtpl->assign('FORMFOOTER',get_form_footer());
require_once('modules/Notes/NoteFormBase.php');
$noteForm = new NoteFormBase();
$postform = "<div id='accountnotelink'><p><a href='javascript:toggleDisplay(\"accountnote\");'>${mod_strings['LNK_NEW_NOTE']}</a></p></div>";
$postform .= '<div id="accountnote" style="display:none">'.$noteForm->getFormBody('AccountNotes', 'Notes', 'BusinessCard', 85).'</div>';
$xtpl->assign('POSTFORM',$postform);
$xtpl->parse("main.headlessform");

//OPPORTUNITTY
$xtpl->assign('FORMHEADER',get_form_header($mod_strings['LNK_NEW_OPPORTUNITY'], '', ''));
require_once('modules/Opportunities/OpportunityFormBase.php');
$oppForm =& new OpportunityFormBase();
$xtpl->assign('CLASS', 'evenListRow');
$xtpl->assign('FORMBODY',"<h5 class='dataLabel'><input class='checkbox' type='checkbox' name='newopportunity' onclick='toggleDisplay(\"newoppdiv\");'>".$mod_strings['LNK_NEW_OPPORTUNITY']."</h5><div id='newoppdiv' style='display:none'>".$oppForm->getWideFormBody('Opportunities', 'Opportunities','BusinessCard', '' , false));
$xtpl->assign('FORMFOOTER',get_form_footer());
require_once('modules/Notes/NoteFormBase.php');
$noteForm =& new NoteFormBase();
$postform = "<div id='oppnotelink'><a href='javascript:toggleDisplay(\"oppnote\");'>${mod_strings['LNK_NEW_NOTE']}</a></div>";
$postform .= '<div id="oppnote" style="display:none">'.$noteForm->getFormBody('OpportunityNotes', 'Notes','BusinessCard', 85).'</div><br>';
$xtpl->assign('POSTFORM',$postform);
$xtpl->parse("main.headlessform");

//Appointment
$xtpl->assign('FORMHEADER',$mod_strings['LNK_NEW_APPOINTMENT']);
require_once('modules/Calls/CallFormBase.php');
$callForm = new CallFormBase();
$xtpl->assign('FORMBODY', "<h5 class='dataLabel'><input class='checkbox' type='checkbox' name='newappointment' onclick='toggleDisplay(\"newappointmentdiv\");'>".$mod_strings['LNK_NEW_APPOINTMENT']."</h5><div id='newappointmentdiv' style='display:none'>".$callForm->getWideFormBody('Appointments', 'Calls',85));
$xtpl->assign('FORMFOOTER', get_form_footer());
$xtpl->assign('POSTFORM','');
$xtpl->parse("main.headlessform");
$xtpl->parse("main.save");
$xtpl->parse("main.endform");
$xtpl->parse("main");

$xtpl->out("main");

}
?>
