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

require_once('include/logging.php');
require_once('modules/Reports/Report.php');
require_once('modules/Reports/SavedReport.php');
require_once('modules/Reports/ReportContact.php');
require_once('modules/Reports/ReportLead.php');
require_once('modules/ProspectLists/ProspectList.php');


if(!isset($_REQUEST['id']))
	sugar_die("An id of a report must be specified to create the relationship.");

if(!isset($_REQUEST['record']))
	sugar_die("A record of a prospect list must be specified to create the relationship.");

if(!isset($_REQUEST['report_module']))
	sugar_die("A report_module must be specified to create the relationship.");

$module = $_REQUEST['report_module'];

$log =& LoggerManager::getLogger('save');

$prospectlist =& new ProspectList();
$prospectlist->retrieve($_REQUEST['record']);

$focus =& new Report($module);
$focus->run_query();

$sql = $focus->query_list[0];

$result = $focus->db->query($sql);

if($module == 'ReportContact')
	$goModule = 'contact';
if($module == 'ReportLead')
	$goModule = 'lead';

while($row = $focus->db->fetchByAssoc($result))
{
	$prospectlist->set_prospect_relationship_single($prospectlist->id, $row['id'], $goModule);
}

$header_URL = "Location: index.php?action={$_REQUEST['return_action']}&module={$_REQUEST['return_module']}&record={$_REQUEST['return_id']}";
$log->debug("about to post header URL of: $header_URL");

header($header_URL);

?>
