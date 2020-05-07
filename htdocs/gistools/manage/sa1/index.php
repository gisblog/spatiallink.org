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
 * $Id: index.php,v 1.194.2.1 2005/05/17 23:18:40 bob Exp $
 * Description: Main file and starting point for the application.  Calls the
 * theme header and footer files defined for the user as well as the module as
 * defined by the input parameters.
 ********************************************************************************/

if (substr(phpversion(), 0, 1) == "5") {
	ini_set("zend.ze1_compatibility_mode", "1");
}

require_once("include/utils.php");


// Allow for the session information to be passed via the URL for printing.
if(isset($_REQUEST['PHPSESSID']))
{
	session_id($_REQUEST['PHPSESSID']);
}

function insert_charset_header()
{
	global $app_strings;
	global $sugar_config;
	$charset = $sugar_config['default_charset'];

	if(isset($app_strings['LBL_CHARSET']))
	{
		$charset = $app_strings['LBL_CHARSET'];
	}

	header('Content-Type: text/html; charset='.$charset);
}

insert_charset_header();

if (!is_file('config.php')) {
    header("Location: install.php");
    exit();
}
require_once('config.php');
require_once('sugar_version.php');

// load up the config_override.php file.  This is used to provide default user settings
if (is_file('config_override.php'))
{
	require_once('config_override.php');
}

// check for old (non-array) config format.
if(empty($sugar_config) && isset($dbconfig['db_host_name']))
{
   make_sugar_config($sugar_config);
}

if (!empty($sugar_config['session_dir'])) {
	session_save_path($sugar_config['session_dir']);
}
$error_notice = '';
$use_current_user_login = false;
if(isset($_REQUEST['MSID'])){
	session_id($_REQUEST['MSID']);
	session_start();
	if(isset($_SESSION['user_id']) && isset($_SESSION['seamless_login'])){
		unset($_SESSION['seamless_login']);
		global $current_user;
		require_once('modules/Users/User.php');
		$current_user = new User();
		$current_user->retrieve($_SESSION['user_id']);
		$current_user->authenticated = true;
		$use_current_user_login = true;
		require_once('modules/Users/Authenticate.php');
	}

}else{
	session_start();
}

clean_incoming_data();


if (!empty($_REQUEST['cancel_redirect'])) {
	if (!empty($_REQUEST['return_action'])) {
		$_REQUEST['action'] = $_REQUEST['return_action'];
		$_POST['action'] = $_REQUEST['return_action'];
		$_GET['action'] = $_REQUEST['return_action'];
	}
	if (!empty($_REQUEST['return_module'])) {
		$_REQUEST['module'] = $_REQUEST['return_module'];
		$_POST['module'] = $_REQUEST['return_module'];
		$_GET['module'] = $_REQUEST['return_module'];
	}
	if (!empty($_REQUEST['return_id'])) {
		$_REQUEST['id'] = $_REQUEST['return_id'];
		$_POST['id'] = $_REQUEST['return_id'];
		$_GET['id'] = $_REQUEST['return_id'];
	}
}

if(isset($_REQUEST['action']))
{
	$action = $_REQUEST['action'];
}
else {
	$action = "";
}

if(isset($_REQUEST['module']))
{
	$module = $_REQUEST['module'];
}
else {
	$module = "";
}


$user_unique_key = (isset($_SESSION['unique_key'])) ? $_SESSION['unique_key'] : '';
$server_unique_key = (isset($sugar_config['unique_key'])) ? $sugar_config['unique_key'] : '';
$allowed_actions = array("Authenticate", "Login"); // these are actions where the user/server keys aren't compared

if (($user_unique_key != $server_unique_key) && (!in_array($action, $allowed_actions)) && (!isset($_SESSION['login_error']))) {
        session_destroy();
        header("Location: index.php?action=Login&module=Users");
        exit();
}

require_once('include/modules.php');


if (empty($sugar_config['dbconfig']['db_host_name'])) {
    header("Location: install.php");
    exit();
}

require_once('include/logging.php');

require_once('modules/Users/User.php');
global $currentModule, $moduleList;

require_once('modules/Administration/Administration.php');
global $system_config;
$system_config = new Administration();
$system_config->retrieveSettings('system');
if($sugar_config['calculate_response_time']) $startTime = microtime();

if (isset($simple_log))
{
	$log = new SimpleLog();
}
else
{
	$log =& LoggerManager::getLogger('index');
}
if (isset($_REQUEST['PHPSESSID'])) $log->debug("****Starting for session ".$_REQUEST['PHPSESSID']);
else $log->debug("****Starting for new session");

// We use the REQUEST_URI later to construct dynamic URLs.  IIS does not pass this field
// to prevent an error, if it is not set, we will assign it to ''
if(!isset($_SERVER['REQUEST_URI']))
{
	$_SERVER['REQUEST_URI'] = '';
}

// Check to see if there is an authenticated user in the session.
if(isset($_SESSION["authenticated_user_id"]))
{
	$log->debug("We have an authenticated user id: ".$_SESSION["authenticated_user_id"]);
}
else if(isset($action) && isset($module) && ($action=="Authenticate")  && $module=="Users")
{
	$log->debug("We are authenticating user now");
}
else
{
	$log->debug("The current user does not have a session.  Going to the login page");
	$action = "Login";
	$module = "Users";
	$_REQUEST['action'] = $action;
	$_REQUEST['module'] = $module;
}

// grab client ip address
$clientIP = query_client_ip();
$classCheck = 0;

// check to see if config entry is present, if not, verify client ip
if(!isset($sugar_config['verify_client_ip']) || $sugar_config['verify_client_ip'] == true)
{
        // check to see if we've got a current ip address in $_SESSION
        // and check to see if the session has been hijacked by a foreign ip
        if(isset($_SESSION["ipaddress"]))
        {
                $session_parts = explode(".", $_SESSION["ipaddress"]);
                $client_parts = explode(".", $clientIP);

                // match class C IP addresses
                for($i=0;$i<3;$i++)
                {
                        if($session_parts[$i] == $client_parts[$i])
                        {
                                $classCheck = 1;
                                continue;
                        }
                        else
                        {
                                $classCheck = 0;
                                break;
                        }
                }



                // we have a different IP address
                if($_SESSION["ipaddress"] != $clientIP && empty($classCheck))
                {
                        $log->fatal("IP Address mismatch: SESSION IP: {$_SESSION['ipaddress']} CLIENT IP: {$clientIP}");
                        session_destroy();
                        die("Your session was terminated due to a significant change in your IP address.  <a href=\"{$sugar_config['site_url']}\">Return to Home</a>");
                }
        }
        else
        {
                $_SESSION["ipaddress"] = $clientIP;
        }
}


$log->debug($_REQUEST);

$skipHeaders=false;
$skipFooters=false;
//define default home pages for each module
foreach ($moduleList as $mod) {
	$moduleDefaultFile[$mod] = "modules/".$currentModule."/index.php";
}


if(!empty($action) && !empty($module))
{
	$log->info("About to take action ".$action);
	$log->debug("in $action");
	if(ereg("^Save", $action) || ereg("^Delete", $action) || ereg("^Popup", $action) || ereg("^ChangePassword", $action) || ereg("^Authenticate", $action) || ereg("^Logout", $action) || ereg("^Export",$action))
	{
		$skipHeaders=true;
		if(ereg("^Popup", $action) || ereg("^ChangePassword", $action) || ereg("^Export", $action))
			$skipFooters=true;
	}
	if((isset($_REQUEST['sugar_body_only']) && $_REQUEST['sugar_body_only'])){
		$skipHeaders=true;
		$skipFooters=true;
	}
	if((isset($_REQUEST['from']) && $_REQUEST['from']=='ImportVCard') || ! empty($_REQUEST['to_pdf']) ){
		$skipHeaders=true;
		$skipFooters=true;
	}
	if($action == 'BusinessCard' || $action == 'ConvertLead'|| $action == 'Save'){
		header( "Expires: Mon, 20 Dec 1998 01:00:00 GMT" );
   		header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
    	header( "Cache-Control: no-cache, must-revalidate" );
    	header( "Pragma: no-cache" );
	}

	if ( $action == "Import" &&
		isset($_REQUEST['step']) &&
		$_REQUEST['step'] == '4'  )
	{
		$skipHeaders=true;
		$skipFooters=true;
	}


	$currentModuleFile = 'modules/'.$module.'/'.$action.'.php';
	$currentModule = $module;
}
elseif(!empty($module))
{
	$currentModule = $module;
	$currentModuleFile = $moduleDefaultFile[$currentModule];
}
else {
    // use $sugar_config['default_module'] and $sugar_config['default_action'] as set in config.php
    // Redirect to the correct module with the correct action.  We need the URI to include these fields.
    header("Location: index.php?action={$sugar_config['default_action']}&module={$sugar_config['default_module']}");
    exit();
}

$export_module = $currentModule;

$log->info("current page is $currentModuleFile");
$log->info("current module is $currentModule ");
// for printing
$GLOBALS['request_string'] = "";

foreach ($_GET as $key => $val) {
	if (is_array($val)) {
		foreach ($val as $k => $v) {
			$GLOBALS['request_string'] .= "{$key}[{$k}]=" . urlencode($v) . "&";
		}
	}
	else {
		$GLOBALS['request_string'] .= "{$key}=" . urlencode($val) . "&";
	}
}

$GLOBALS['request_string'] .= "&print=true";
// end printing

if(!$use_current_user_login){
	$current_user = new User();


if(isset($_SESSION['authenticated_user_id']))
{
	$result = $current_user->retrieve($_SESSION['authenticated_user_id']);
	if($result == null)
	{
		session_destroy();
	    header("Location: index.php?action=Login&module=Users");
	}

	$log->debug('Current user is: '.$current_user->user_name);
}
}

$result = $current_user->db->query("SELECT * FROM config WHERE category='info' AND name='sugar_version' AND value LIKE '3.0%'");
if(!$result || $current_user->db->getRowCount($result) == 0){
	sugar_die('Sugar CRM 3.0 Files May Only Be Used With A Sugar CRM 3.0 Database');
}
if(isset($_SESSION['authenticated_user_theme']) && $_SESSION['authenticated_user_theme'] != '')
{
	$theme = $_SESSION['authenticated_user_theme'];
}
else
{
	$theme = $sugar_config['default_theme'];
}
$log->debug('Current theme is: '.$theme);

//Used for current record focus
$focus = "";

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

//set module and application string arrays based upon selected language
$app_strings = return_application_language($current_language);
$app_list_strings = return_app_list_strings_language($current_language);
$mod_strings = return_module_language($current_language, $currentModule);

//TODO: Clint - this key map needs to be moved out of $app_list_strings since it never gets translated.
//              best to just have an upgrade script that changes the parent_type column from Account to Accounts, etc.
$app_list_strings['record_type_module'] = array('Contact'=>'Contacts', 'Account'=>'Accounts', 'Opportunity'=>'Opportunities', 'Case'=>'Cases', 'Note'=>'Notes', 'Call'=>'Calls', 'Email'=>'Emails', 'Meeting'=>'Meetings', 'Task'=>'Tasks', 'Lead'=>'Leads','Bug'=>'Bugs',



);

//If DetailView, set focus to record passed in
if($action == "DetailView")
{
	if(!isset($_REQUEST['record']))
		die("A record number must be specified to view details.");

	// If we are going to a detail form, load up the record now.
	// Use the record to track the viewing.
	// todo - Have a record of modules and thier primary object names.
	$entity = $beanList[$currentModule];
	require_once($beanFiles[$entity]);
	$focus = new $entity();
	$result = $focus->retrieve($_REQUEST['record']);
	if($result)
	{
		// Only track a viewing if the record was retrieved.
		$focus->track_view($current_user->id, $currentModule);
	}
}

// set user, theme and language cookies so that login screen defaults to last values
if (isset($_SESSION['authenticated_user_id'])) {
	$log->debug("setting cookie ck_login_id_20 to ".$_SESSION['authenticated_user_id']);
	setcookie('ck_login_id_20', $_SESSION['authenticated_user_id'], time() + 86400*90);
}
if (isset($_SESSION['authenticated_user_theme'])) {
	$log->debug("setting cookie ck_login_theme_20 to ".$_SESSION['authenticated_user_theme']);
	setcookie('ck_login_theme_20', $_SESSION['authenticated_user_theme'], time() + 86400*90);
}
if (isset($_SESSION['authenticated_user_language'])) {
	$log->debug("setting cookie ck_login_language_20 to ".$_SESSION['authenticated_user_language']);
	setcookie('ck_login_language_20', $_SESSION['authenticated_user_language'], time() + 86400*90);
}
ob_start();
require_once('include/database/PearDatabase.php');
require_once('include/javascript/jsAlerts.php');
if (empty($_REQUEST['to_pdf'])) {
	echo '<script type="text/javascript" src="include/javascript/sugar_3.js"></script>';
	echo '<link rel="stylesheet" type="text/css" media="all" href="jscalendar/calendar-win2k-cold-1.css">';
	echo '<script type="text/javascript" src="jscalendar/calendar.js"></script>';
	echo '<script type="text/javascript" src="jscalendar/lang/calendar-en.js"></script>';
	echo '<script type="text/javascript" src="jscalendar/calendar-setup_3.js"></script>';
	$timedate =& new TimeDate();
	echo $timedate->get_javascript_validation();
	$db =& new PearDatabase();
	$jsalerts =& new jsAlerts();
}
//skip headers for popups, deleting, saving, importing and other actions
if(!$skipHeaders) {


	$log->debug("including headers");

	if (!is_file('themes/'.$theme.'/header.php')) {
		$theme = $sugar_config['default_theme'];
	}
	if (!is_file('themes/'.$theme.'/header.php')) {
		sugar_die("Invalid theme specified");
	}

	include('themes/'.$theme.'/header.php');

	// Only print the errors for admin users.

	if(is_admin($current_user))
	{
        if(!empty($dbconfig['db_host_name']) || $sugar_config['sugar_version'] != $sugar_version ){
            echo '<p class="error">Warning: The config.php file needs to be upgraded.  Please upgrade your config file to the new format by clicking on the "Upgrade" link in the Admin screen.</p>';
        }

        if( !isset($sugar_config['installer_locked']) || $sugar_config['installer_locked'] == false ){
			echo '<p class="error">Warning: To safeguard your data, the installer must be locked by setting \'installer_locked\' to \'true\' in the config.php file.</p>';
		}

		if(is_writable('config.php'))
		{
			echo '<p class="error">Warning: The config.php file needs to be made unwritable for security purposes.</p>';
		}














































		if(isset($_SESSION['invalid_versions'])){
			$invalid_versions = $_SESSION['invalid_versions'];
			foreach($invalid_versions as $invalid){
				echo '<p class="error">Warning: Please upgrade '. $invalid['name'] .' using the upgrade in  the <a href="index.php?module=Administration&action=Upgrade">administration panel</a> </p>';
			}
		}

		if(isset($_SESSION['administrator_error']))
		{
			// Only print DB errors once otherwise they will still look broken
			// after they are fixed.
			echo $_SESSION['administrator_error'];
		}

		unset($_SESSION['administrator_error']);
	}

	echo "<!-- crmprint -->";
}
else {
		$log->debug("skipping headers");
}


// added a check for security of tabs to see if a user has access to them
// this prevents passing an "unseen" tab to the query string and pulling up its contents

if(!isset($modListHeader))
{
	if(isset($current_user))
	{
		$modListHeader = query_module_access_list($current_user);
	}
}

if	(array_key_exists($currentModule, $modListHeader) ||
	 in_array($currentModule, $modInvisList) ||
	 (( array_key_exists("Activities", $modListHeader) || array_key_exists("Calendar", $modListHeader))
	   && in_array($currentModule, $modInvisListActivities)) ||
	   ($currentModule == "iFrames" && isset($_REQUEST['record'])) )
	{
		include($currentModuleFile);

	}
	else
	{
		echo '<p class="error">Warning: You do not have permission to access this module.</p>';
	}

if(!$skipFooters) {
	echo "<!-- crmprint -->";
	echo $jsalerts->getScript();
	include('themes/'.$theme.'/footer.php');


echo "<table cellpadding='0' cellspacing='0' width='100%' border='0'><tr><td align='center' class='copyRight'>";
// Under the Sugar Public License referenced above, you are required to leave in all copyright statements in both
// the code and end-user application.
if($sugar_config['calculate_response_time'])
{
	$endTime = microtime();
	$deltaTime = microtime_diff($startTime, $endTime);
	$response_time_string = $app_strings['LBL_SERVER_RESPONSE_TIME']
		. " $deltaTime " . $app_strings['LBL_SERVER_RESPONSE_TIME_SECONDS']
		. '<br />';
	echo($response_time_string);
}
echo('&copy; 2004-2005 <a href="http://www.sugarcrm.com" target="_blank" class="copyRightLink">SugarCRM Inc.</a> All Rights Reserved.<br />');


// Under the Sugar Public License referenced above, you are required to leave in all copyright statements in both
// the code and end-user application as well as the the powered by image. You can not change the url or the image below  .




echo "<A href='http://www.sugarforge.org' target='_blank'><img style='margin-top: 2px' border='0' width='106' height='23' src='include/images/poweredby_sugarcrm.png' alt='Powered By SugarCRM'></a>\n";







// End Required Image
echo "</td></tr></table>\n";

echo "</body></html>";
}

echo $error_notice;

if (!function_exists("ob_get_clean")) {
   function ob_get_clean() {
       $ob_contents = ob_get_contents();
       ob_end_clean();
       return $ob_contents;
   }
}

if (isset($_GET['print'])) {
	$page_str = ob_get_clean();
	$page_arr = explode("<!-- crmprint -->", $page_str);
	include("phprint.php");
}


if(isset($sugar_config['log_memory_usage']) &&
	$sugar_config['log_memory_usage'] &&
	function_exists('memory_get_usage'))
{
	$fp = @fopen("memory_usage.log", "ab");
	@fwrite($fp, "Usage: " . memory_get_usage() . " - module: " . (isset($module) ? $module : "<none>") . " - action: " . (isset($action) ? $action : "<none>") . "\n");
	@fclose($fp);
}

?>
