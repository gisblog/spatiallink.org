<?PHP
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

/* 
 * func: query_module_access 
 * param: $moduleName
 * 
 * returns 1 if user has access to a module, else returns 0
 * 
 */


function query_module_access_list(&$user)
{
	require_once('modules/MySettings/TabController.php');
	$controller = new TabController();
	$tabArray = $controller->get_tabs($user); 

	return $tabArray[0];
		
}

function query_user_has_roles($user_id)
{
	require_once('modules/Roles/Role.php');
	
	$role =& new Role();
	
	return $role->check_user_role_count($user_id);
}

function get_user_allowed_modules($user_id)
{
	require_once('modules/Roles/Role.php');

	$role =& new Role();
	
	$allowed = $role->query_user_allowed_modules($user_id);
	return $allowed;
}

function get_user_disallowed_modules($user_id, &$allowed)
{
	require_once('modules/Roles/Role.php');

	$role =& new Role();
	$disallowed = $role->query_user_disallowed_modules($user_id, $allowed);
	return $disallowed;
}
// grabs client ip address and returns its value
function query_client_ip()
{
	global $_SERVER;
	
	if(isset($_SERVER['HTTP_CLIENT_IP']))
	{
		$clientIP = $_SERVER['HTTP_CLIENT_IP'];
	}
	elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches))
	{
		// check for internal ips by looking at the first octet
		foreach($matches[0] AS $ip)
		{
			if(!preg_match("#^(10|172\.16|192\.168)\.#", $ip))
			{
				$clientIP = $ip;
				break;
			}
		}

	}
	elseif(isset($_SERVER['HTTP_FROM']))
	{
		$clientIP = $_SERVER['HTTP_FROM'];
	}
	else
	{
		$clientIP = $_SERVER['REMOTE_ADDR'];
	}

	return $clientIP;
}

// sets value to key value
function get_val_array($arr){
	$new = array();
	if(!empty($arr)){
	foreach($arr as $key=>$val){
		$new[$key] = $key;
	}
	}
	return $new;
}

?>
