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
 * $Id: DeleteLeadsRelationship.php,v 1.1 2005/04/15 12:30:25 joey Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('modules/ProspectLists/ProspectList.php');
require_once('include/logging.php');
require_once('include/utils.php');

$log =& LoggerManager::getLogger('save');

$focus = new ProspectList();

$focus->retrieve($_REQUEST['record']);

$focus->clear_prospect_relationship($focus->id, $_REQUEST['lead_id'], "lead");

$header_URL = "Location: index.php?action={$_REQUEST['return_action']}&module={$_REQUEST['return_module']}&record={$_REQUEST['return_id']}";
$log->debug("about to post header URL of: $header_URL");

header($header_URL);
?>

