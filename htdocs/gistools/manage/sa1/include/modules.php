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
 * $Id: modules.php,v 1.85 2005/04/30 04:50:23 majed Exp $
 * Description:  Executes a step in the installation process.
 ********************************************************************************/

$moduleList = array();
// this list defines the modules shown in the top tab list of the app
//the order of this list is the default order displayed - do not change the order unless it is on purpose
$moduleList[] = 'Home';
$moduleList[] = 'iFrames';
$moduleList[] = 'Calendar';
$moduleList[] = 'Activities';
$moduleList[] = 'Contacts';
$moduleList[] = 'Accounts';
$moduleList[] = 'Leads';
$moduleList[] = 'Opportunities';




$moduleList[] = 'Cases';
$moduleList[] = 'Bugs';
$moduleList[] = 'Documents';
$moduleList[] = 'Emails';
$moduleList[] = 'Campaigns';
$moduleList[] = 'Project';
$moduleList[] = 'Feeds';




$moduleList[] = 'Dashboard';


// this list defines all of the module names and bean names in the app
// to create a new module's bean class, add the bean definition here
$beanList['Leads'] 			= 'Lead';
$beanList['Contacts'] 		= 'Contact';
$beanList['Accounts']		= 'Account';
$beanList['DynamicFields']	= 'DynamicField';
$beanList['Opportunities']	= 'Opportunity';
$beanList['Cases']			= 'aCase';
$beanList['Notes']			= 'Note';
$beanList['EmailTemplates']		= 'EmailTemplate';
$beanList['EmailMan'] = 'EmailMan';
$beanList['Calls']			= 'Call';
$beanList['Emails']			= 'Email';
$beanList['Meetings']		= 'Meeting';
$beanList['Tasks']			= 'Task';
$beanList['Users']			= 'User';
$beanList['Employees']		= 'Employee';
$beanList['Currencies']		= 'Currency';
$beanList['Trackers']		= 'Tracker';
$beanList['Import']			= 'ImportMap';
$beanList['Import_1']		= 'SugarFile';
$beanList['Import_2']		= 'UsersLastImport';
$beanList['Versions']		= 'Version';
$beanList['Administration']	= 'Administration';



















$beanList['vCals'] 			= 'vCal';
$beanList['CustomFields']		= 'CustomFields';
$beanList['Bugs'] 			= 'Bug';
$beanList['Releases']		= 'Release';
$beanList['Feeds'] 			= 'Feed';
$beanList['iFrames'] 			= 'iFrame';

$beanList['Project'] 			= 'Project';
$beanList['ProjectTask'] 			= 'ProjectTask';
$beanList['ProjectRelation'] 			= 'ProjectRelation';

$beanList['Campaigns']          = 'Campaign';
$beanList['ProspectLists']      = 'ProspectList';
$beanList['Prospects']  = 'Prospect';
$beanList['Documents']  = 'Document';
$beanList['Roles']  = 'Role';
$beanList['EmailMarketing']  = 'EmailMarketing';


// this list defines all of the files that contain the SugarBean class definitions from $beanList
// to create a new module's bean class, add the file definition here
$beanFiles['Lead'] 			= 'modules/Leads/Lead.php';
$beanFiles['Contact'] 		= 'modules/Contacts/Contact.php';
$beanFiles['Account']		= 'modules/Accounts/Account.php';
$beanFiles['Opportunity']	= 'modules/Opportunities/Opportunity.php';
$beanFiles['aCase']			= 'modules/Cases/Case.php';
$beanFiles['Note']			= 'modules/Notes/Note.php';
$beanFiles['EmailTemplate']			= 'modules/EmailTemplates/EmailTemplate.php';
$beanFiles['EmailMan']			= 'modules/EmailMan/EmailMan.php';
$beanFiles['Call']			= 'modules/Calls/Call.php';

$beanFiles['Email']			= 'modules/Emails/Email.php';
$beanFiles['Meeting']		= 'modules/Meetings/Meeting.php';
$beanFiles['iFrame']		= 'modules/iFrames/iFrame.php';
$beanFiles['Task']			= 'modules/Tasks/Task.php';
$beanFiles['User']			= 'modules/Users/User.php';
$beanFiles['Employee']		= 'modules/Employees/Employee.php';
$beanFiles['Currency']			= 'modules/Currencies/Currency.php';
$beanFiles['Tracker']		= 'data/Tracker.php';
$beanFiles['ImportMap']		= 'modules/Import/ImportMap.php';
$beanFiles['SugarFile']		= 'modules/Import/SugarFile.php';
$beanFiles['UsersLastImport']= 'modules/Import/UsersLastImport.php';
$beanFiles['Administration']= 'modules/Administration/Administration.php';



















$beanFiles['vCal'] 			= 'modules/vCals/vCal.php';
$beanFiles['Bug'] 			= 'modules/Bugs/Bug.php';
$beanFiles['Version'] 			= 'modules/Versions/Version.php';
$beanFiles['Release'] 			= 'modules/Releases/Release.php';
$beanFiles['Feed'] 			= 'modules/Feeds/Feed.php';
$beanFiles['Project'] 			= 'modules/Project/Project.php';
$beanFiles['ProjectTask'] 			= 'modules/ProjectTask/ProjectTask.php';
$beanFiles['ProjectRelation'] = 'modules/ProjectRelation/ProjectRelation.php';
$beanFiles['Role']          = 'modules/Roles/Role.php';
$beanFiles['EmailMarketing']          = 'modules/EmailMarketing/EmailMarketing.php';
$beanFiles['Campaign']          = 'modules/Campaigns/Campaign.php';
$beanFiles['ProspectList']      = 'modules/ProspectLists/ProspectList.php';
$beanFiles['Prospect']  = 'modules/Prospects/Prospect.php';
$beanFiles['Document']  = 'modules/Documents/Document.php';
$beanFiles['DocumentRevision']  = 'modules/Documents/DocumentRevision.php';
$beanFiles['FieldsMetaData']			= 'modules/EditCustomFields/FieldsMetaData.php';
// added these lists for security settings for tabs
$modInvisList = array('Administration', 'Currencies', 'CustomFields',
	'Dropdown', 'Dynamic', 'DynamicFields', 'DynamicLayout', 'EditCustomFields',
	'EmailTemplates', 'Help', 'Import',  'MySettings',



	'Releases', 
	'Users',  'Versions', 'EmailMan', 'ProjectTask', 'ProjectRelation','ProspectLists', 'Prospects', 'Employees', 'LabelEditor','Roles','EmailMarketing');
$modInvisListActivities	= array('Calls', 'Meetings','Notes','Tasks');



if (file_exists('include/modules_override.php'))
{
	include('include/modules_override.php');
}
?>
