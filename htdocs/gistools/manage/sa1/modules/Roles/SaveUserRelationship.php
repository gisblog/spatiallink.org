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
 * $Id: SaveUserRelationship.php,v 1.1 2005/04/27 04:32:36 joey Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('modules/Roles/Role.php');
require_once('include/logging.php');
require_once('include/utils.php');

$log =& LoggerManager::getLogger('save');

$focus =& new Role();

$focus->retrieve($_REQUEST['record']);

$focus->set_user_relationship($focus->id, $_REQUEST['mass']);


$header_URL = $sugar_config["site_url"] . "/index.php?action=PopupUsers&form=UsersForm&module=Users&record={$_REQUEST['record']}";
$log->debug("about to post header URL of: $header_URL");

echo "<script language=javascript>\n";
echo "<!-- //\n";
echo "  window.opener.location.reload();\n";
echo "	window.location=\"{$header_URL}\";\n";
echo "// -->\n";
echo "</script>";

?>

