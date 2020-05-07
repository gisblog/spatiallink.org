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

if (substr(phpversion(), 0, 1) == "5") {
        ini_set("zend.ze1_compatibility_mode", "1");
}

include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('modules/Users/User.php');
require_once('include/modules.php');
require_once('include/utils.php');

// check for old config format.
if(empty($sugar_config) && isset($dbconfig['db_host_name']))
{
   make_sugar_config($sugar_config);
}

if (!empty($sugar_config['session_dir'])) {
	session_save_path($sugar_config['session_dir']);
}

session_start();

$log = LoggerManager::getLogger('acceptDecline');
$current_user = new User();

$result = $current_user->retrieve($_REQUEST['user_id']);
if($result == null)
{
	session_destroy();
	die("The user id doesn't exist");
}

	$bean = $beanList[$_REQUEST['module']];
	require_once($beanFiles[$bean]);
	$focus = new $bean;
  $result = $focus->retrieve($_REQUEST['record']);

if($result == null)
{
	session_destroy();
	die("The focus id doesn't exist");
}

// if the language is not set yet, then set it to the default language.
if(isset($_SESSION['authenticated_user_language']) && $_SESSION['authenticated_user_language'] != '')
{
  $current_language = $_SESSION['authenticated_user_language'];
}
else
{
  $current_language = $sugar_config['default_language'];
}
$log->debug('current_language is: '.$current_language);
$app_strings = return_application_language($current_language);
$app_list_strings = return_app_list_strings_language($current_language);

$focus->set_accept_status($current_user,$_REQUEST['accept_status']);

print $app_strings['LBL_STATUS_UPDATED']."<BR><BR>";
print $app_strings['LBL_STATUS']. " ". $app_list_strings['dom_meeting_accept_status'][$_REQUEST['accept_status']];
print "<BR><BR>";
print "<a href='#' onclick='window.close(); return false;'>".$app_strings['LBL_CLOSE_WINDOW']."</a><br>";
exit;
?>
