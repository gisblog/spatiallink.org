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
 * $Id: campaign_tracker.php,v 1.2 2005/04/29 22:17:33 joey Exp $
 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

// logic will be added here at a later date to track campaigns
// this script; currently forwards to site_URL variable of $sugar_config
// redirect URL will also be added so specified redirect URL can be used

// additionally, another script using fopen will be used to call this 
// script externally

include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('modules/Campaigns/Campaign.php');
require_once('include/utils.php');

$db =& new PearDatabase();
$track = $_REQUEST['track'];
$track = $db->quote($track);

if(ereg('^[0-9A-Za-z\-]*$', $track))
{
	$query = "UPDATE campaigns SET tracker_count=tracker_count+1 WHERE tracker_key='$track'";
	$db->query($query);
	
	$query = "SELECT refer_url FROM campaigns WHERE tracker_key='$track'";
	$res = $db->query($query);
	
	$row = $db->fetchByAssoc($res);

	$redirect_URL = $row['refer_url'];
	header("Location: $redirect_URL");
}
exit;
?>
