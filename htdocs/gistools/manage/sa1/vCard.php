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
require_once('config.php');
require_once('include/vCard.php');
require_once('include/logging.php');
require_once('include/utils.php');
require_once('modules/Users/User.php');

// check for old config format.
if(empty($sugar_config) && isset($dbconfig['db_host_name']))
{
   make_sugar_config($sugar_config);
}

$current_user = new User();

if (!empty($sugar_config['session_dir'])) {
	session_save_path($sugar_config['session_dir']);
}

session_start();

$user_unique_key = (isset($_SESSION['unique_key'])) ? $_SESSION['unique_key'] : '';
$server_unique_key = (isset($sugar_config['unique_key'])) ? $sugar_config['unique_key'] : '';

if ($user_unique_key != $server_unique_key) {
	session_destroy();
	header("Location: index.php?action=Login&module=Users");
	exit();
}

if(isset($_SESSION['authenticated_user_id']))
{
	$result = $current_user->retrieve($_SESSION['authenticated_user_id']);
	if($result == null)
	{
		session_destroy();
	    header("Location: index.php?action=Login&module=Users");
	}

}

if(isset($_SESSION['authenticated_user_language']) && $_SESSION['authenticated_user_language'] != '')
{
	$current_language = $_SESSION['authenticated_user_language'];
}
else
{
	$current_language = $sugar_config['default_language'];
}


//set module and application string arrays based upon selected language

$app_strings = return_application_language($current_language);

$vcard = new vCard();
$log = LoggerManager::getLogger('vcard');
$module = 'Contact';
if(isset($_REQUEST['module']))
	$module = clean_string($_REQUEST['module']);

$vcard->loadContact($_REQUEST['contact_id'], $module);

$vcard->saveVCard();

?>

