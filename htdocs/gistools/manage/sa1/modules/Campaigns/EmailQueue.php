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
 * $Id: EmailQueue.php,v 1.4 2005/04/29 08:00:26 majed Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('modules/Campaigns/Campaign.php');
require_once('modules/ProspectLists/ProspectList.php');
require_once('modules/EmailMan/EmailMan.php');
require_once('include/logging.php');
require_once('include/utils.php');

global $timedate;
global $current_user;

$log =& LoggerManager::getLogger('emailqueue');

$campaign =& new Campaign();
$campaign->retrieve($_REQUEST['record']);

$query = "SELECT prospect_list_id as id FROM prospect_list_campaigns WHERE campaign_id='$campaign->id' AND deleted=0";

$fromName = $_REQUEST['from_name'];
$fromEmail = $_REQUEST['from_address'];
$date_start = $_REQUEST['date_start'];
$time_start = $_REQUEST['time_start'];
$template_id = $_REQUEST['email_template'];

$dateval = $timedate->merge_date_time($date_start, $time_start);


$listresult = $campaign->db->query($query);

while($list = $campaign->db->fetchByAssoc($listresult))
{
	$prospect_list = $list['id'];
	$focus =& new ProspectList();
	
	$focus->retrieve($prospect_list);

	$query = "SELECT prospect_id,contact_id,lead_id FROM prospect_lists_prospects WHERE prospect_list_id='$focus->id' AND deleted=0";
	$result = $focus->db->query($query);

	while($row = $focus->db->fetchByAssoc($result))
	{
		$prospect_id = $row['prospect_id'];
		$contact_id = $row['contact_id'];
		$lead_id = $row['lead_id'];
		
		if($prospect_id <> '')
		{
			$moduleName = "Prospects";
			$moduleID = $row['prospect_id'];
		}
		if($contact_id <> '')
		{
			$moduleName = "Contacts";
			$moduleID = $row['contact_id'];
		}
		if($lead_id <> '')
		{
			$moduleName = "Leads";
			$moduleID = $row['lead_id'];
		}
		
		$mailer =& new EmailMan();
		$mailer->module = $moduleName;
		$mailer->module_id = $moduleID;
		$mailer->user_id = $current_user->id;
		$mailer->list_id = $prospect_list;
		$mailer->template_id = $template_id;
		$mailer->from_name = $fromName;
		$mailer->from_email = $fromEmail;
		$mailer->send_date_time = $dateval;
		$mailer->save();
	}
	
	
}


$header_URL = "Location: index.php?action=DetailView&module=Campaigns&record={$_REQUEST['record']}";
$log->debug("about to post header URL of: $header_URL");

header($header_URL);
?>

