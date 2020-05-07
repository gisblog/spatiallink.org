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


//treated as listview
$html_php_mapping_subpanel = array(
'modules/Accounts/SubPanelViewBugs.html'=>'modules/Accounts/SubPanelViewBugs.php',
'modules/Accounts/SubPanelViewMemberAccount.html'=>'modules/Accounts/SubPanelView.php',
'modules/Accounts/SubPanelViewProjects.html'=>'modules/Accounts/SubPanelViewProjects.php',
'modules/Contacts/SubPanelView.html'=>'modules/Contacts/SubPanelEdit.php',
'modules/Contacts/SubPanelViewAccounts.html'=>'modules/Contacts/SubPanelEdit.php',
'modules/Contacts/SubPanelViewDirectReport.html'=>'modules/Contacts/SubPanelEdit.php',
'modules/Contacts/SubPanelViewCase.html'=>'modules/Contacts/SubPanelEdit.php',
'modules/Contacts/SubPanelViewContact.html'=>'modules/Contacts/SubPanelEdit.php',
'modules/Contacts/SubPanelViewDirectReport.html'=>'modules/Contacts/SubPanelEdit.php',
'modules/Contacts/SubPanelViewDirectReport.html' =>'modules/Contacts/SubPanelEdit.php',
'modules/Contacts/SubPanelViewBugs.html'=>'modules/Contacts/SubPanelEdit.php',
'modules/Contacts/SubPanelViewOpportunity.html'=>'modules/Contacts/SubPanelEdit.php',
'modules/Contacts/SubPanelViewProject.html'=>'modules/Contacts/SubPanelEdit.php',
'modules/Emails/SubPanelViewRecipients.html'=>'modules/Contacts/SubPanelEditUsersContacts.php',
'modules/Meetings/SubPanelViewInvitees.html'=>'modules/Contacts/SubPanelEditUsersContacts.php',
'modules/Calls/SubPanelViewInvitees.html'=>'modules/Contacts/SubPanelEditUsersContacts.php',
'modules/Cases/SubPanelViewBug.html'=>'modules/Cases/SubPanelView.php',
'modules/Cases/SubPanelView.html'=>'modules/Cases/SubPanelView.php',
'modules/Currencies/ListView.html'=>'modules/Currencies/index.php',
'modules/Bugs/SubPanelView.html'=>'modules/Bugs/SubPanelView.php',
'modules/Leads/MyLeads.html'=>'modules/Leads/MyLeads.php',
'modules/Leads/SubPanelView.html'=>'modules/Leads/SubPanelView.php',
'modules/Cases/MyCases.html'=>'modules/Cases/MyCases.php',
'modules/Users/SubPanelViewTeams.html' => 'modules/Contacts/SubPanelEditUsers.php',
'modules/Releases/ListView.html'=>'modules/Releases/index.php',
'modules/Notes/SubPanelView.html'=>'modules/Notes/SubPanelView.php',
'modules/Opportunities/ListViewTop.html'=>'modules/Opportunities/ListViewTop.php',
'modules/Opportunities/SubPanelView.html'=>'modules/Opportunities/SubPanelView.php',
'modules/Opportunities/SubPanelViewProjects.html'=>'modules/Opportunities/SubPanelViewProjects.php',
'modules/Calendar/TasksListView.html'=>'modules/Calendar/TasksListView.php',
'modules/Project/SubPanelView.html'=>'modules/Project/SubPanelView.php',
'modules/ProjectTask/SubPanelView.html'=>'modules/ProjectTask/SubPanelView.php',











);

$html_php_mapping_edit = array(
'modules/Currencies/EditView.html'=>'modules/Currencies/index.php',








'modules/Releases/EditView.html'=>'modules/Releases/index.php',

);

$html_php_mapping_detail = array(

);

//no fields can be added
$html_php_mapping_other = array(
'modules/Home/Home.html'=> 'modules/Home/index.php',
);

$html_php_mapping_popup = array(
'modules/Accounts/Popup_picker.html'=>'modules/Accounts/Popup_picker.php',
'modules/Contacts/ContactOpportunityRelationshipEdit.html'=>'modules/Contacts/ContactOpportunityRelationshipEdit.php',
'modules/Contacts/Popup_picker.html'=>'modules/Contacts/Popup.php',
'modules/Calls/ContactCaseRelationshipEdit.html'=>'modules/Calls/ContactCaseRelationshipEdit.php',
)

?>
