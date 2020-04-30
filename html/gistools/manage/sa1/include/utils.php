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
 * $Id: utils.php,v 1.138.2.2 2005/05/17 22:29:54 majed Exp $
 * Description:  Includes generic helper functions used throughout the application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/


include('include/utils/security_utils.php');

function make_sugar_config(&$sugar_config)
{
    /* used to convert non-array config.php file to array format */
	global $admin_export_only;
	global $cache_dir;
	global $calculate_response_time;
	global $create_default_user;
	global $dateFormats;
	global $dbconfig;
	global $dbconfigoption;
	global $default_action;
	global $default_charset;
	global $defaultDateFormat;
	global $default_language;
	global $default_module;
	global $default_password;
	global $default_theme;
	global $defaultTimeFormat;
	global $default_user_is_admin;
	global $default_user_name;
	global $disable_export;
	global $disable_persistent_connections;
	global $display_email_template_variable_chooser;
	global $display_inbound_email_buttons;
	global $history_max_viewed;
	global $host_name;
	global $import_dir;
	global $languages;
	global $list_max_entries_per_page;
	global $lock_default_user_name;
	global $log_memory_usage;
	global $requireAccounts;
	global $RSS_CACHE_TIME;
	global $session_dir;
	global $site_URL;
	global $site_url;
	global $sugar_version;
	global $timeFormats;
	global $tmp_dir;
	global $translation_string_prefix;
	global $unique_key;
	global $upload_badext;
	global $upload_dir;
	global $upload_maxsize;

	// assumes the following variables must be set:
   // $dbconfig, $dbconfigoption, $cache_dir, $import_dir, $session_dir, $site_URL, $tmp_dir, $upload_dir

   $sugar_config = array (
   'admin_export_only' => empty($admin_export_only) ? false : $admin_export_only,
   'cache_dir' => empty($cache_dir) ? 'cache/' : $cache_dir,
   'calculate_response_time' => empty($calculate_response_time) ? true : $calculate_response_time,
   'create_default_user' => empty($create_default_user) ? false : $create_default_user,
   'date_formats' => empty($dateFormats) ? array('Y-m-d'=>'2006-12-23',
      'm-d-Y'=>'12-23-2006', 'Y/m/d'=>'2006/12/23', 'm/d/Y'=>'12/23/2006') : $dateFormats,
   'dbconfig' => $dbconfig,  // this must be set!!
   'dbconfigoption' => $dbconfigoption,  // this must be set!!
   'default_action' => empty($default_action) ? 'index' : $default_action,
   'default_charset' => empty($default_charset) ? 'ISO-8859-1' : $default_charset,
   'default_date_format' => empty($defaultDateFormat) ? 'Y-m-d' : $defaultDateFormat,
   'default_language' => empty($default_language) ? 'en_us' : $default_language,
   'default_module' => empty($default_module) ? 'Home' : $default_module,
   'default_password' => empty($default_password) ? '' : $default_password,
   'default_theme' => empty($default_theme) ? 'Sugar' : $default_theme,
   'default_time_format' => empty($defaultTimeFormat) ? 'H:i' : $defaultTimeFormat,
   'default_user_is_admin' => empty($default_user_is_admin) ? false : $default_user_is_admin,
   'default_user_name' => empty($default_user_name) ? '' : $default_user_name,
   'disable_export' => empty($disable_export) ? false : $disable_export,
   'disable_persistent_connections' => empty($disable_persistent_connections) ? false : $disable_persistent_connections,
   'display_email_template_variable_chooser' => empty($display_email_template_variable_chooser) ? false : $display_email_template_variable_chooser,
   'display_inbound_email_buttons' => empty($display_inbound_email_buttons) ? false : $display_inbound_email_buttons,
   'history_max_viewed' => empty($history_max_viewed) ? 10 : $history_max_viewed,
   'host_name' => empty($host_name) ? 'localhost' : $host_name,
   'import_dir' => $import_dir,  // this must be set!!
   'languages' => empty($languages) ? array('en_us' => 'US English') : $languages,
   'list_max_entries_per_page' => empty($list_max_entries_per_page) ? 20 : $list_max_entries_per_page,
   'lock_default_user_name' => empty($lock_default_user_name) ? false : $lock_default_user_name,
   'log_memory_usage' => empty($log_memory_usage) ? false : $log_memory_usage,
   'require_accounts' => empty($requireAccounts) ? true : $requireAccounts,
   'rss_cache_time' => empty($RSS_CACHE_TIME) ? '10800' : $RSS_CACHE_TIME,
   'session_dir' => $session_dir,  // this must be set!!
   'site_url' => empty($site_URL) ? $site_url : $site_URL,  // this must be set!!
   'sugar_version' => empty($sugar_version) ? 'unknown' : $sugar_version,
   'time_formats' => empty($timeFormats) ? array (
      'H:i'=>'23:00', 'h:ia'=>'11:00pm', 'h:iA'=>'11:00PM',
      'H.i'=>'23.00', 'h.ia'=>'11.00pm', 'h.iA'=>'11.00PM' ) : $timeFormats,
   'tmp_dir' => $tmp_dir,  // this must be set!!
   'translation_string_prefix' => empty($translation_string_prefix) ? false : $translation_string_prefix,
   'unique_key' => empty($unique_key) ? md5(create_guid()) : $unique_key,
   'upload_badext' => empty($upload_badext) ? array (
      'php', 'php3', 'php4', 'php5', 'pl', 'cgi', 'py',
      'asp', 'cfm', 'js', 'vbs', 'html', 'htm' ) : $upload_badext,
   'upload_dir' => $upload_dir,  // this must be set!!
   'upload_maxsize' => empty($upload_maxsize) ? 3000000 : $upload_maxsize,
	);
}

function get_sugar_config_defaults(){
    /*  used for getting base values for array style config.php.  used by the
        installer and to fill in new entries on upgrades.  see also: sugar_config_union
    */

    $sugar_config_defaults = array (
        'admin_export_only' => false,
        'calculate_response_time' => true,
        'create_default_user' => false,
        'date_formats' => array (
            'Y-m-d'=>'2006-12-23', 'm-d-Y'=>'12-23-2006','d-m-Y'=>'23-12-2006',
            'Y/m/d'=>'2006/12/23', 'm/d/Y'=>'12/23/2006', 'd/m/Y'=>'23/12/2006' ),
        'dbconfigoption' => array (
            'persistent' => true,
            'autofree' => false,
            'debug' => 0,
            'seqname_format' => '%s_seq',
            'portability' => 0,
            'ssl' => false ),
        'default_action' => 'index',
        'default_charset' => return_session_value_or_default('default_charset',
            'ISO-8859-1'),
        'default_date_format' => 'Y-m-d',
        'default_language' => return_session_value_or_default('default_language',
            'en_us'),
        'default_module' => 'Home',
        'default_password' => '',
        'default_theme' => return_session_value_or_default('site_default_theme', 'Sugar'),
        'default_time_format' => 'H:i',
        'default_user_is_admin' => false,
        'default_user_name' => '',
        'disable_export' => false,
        'disable_persistent_connections' =>
            return_session_value_or_default('disable_persistent_connections',
            'false'),
        'display_email_template_variable_chooser' => false,
        'display_inbound_email_buttons' => false,
        'dump_slow_queries' => false,
        'history_max_viewed' => 10,
        'installer_locked' => true,
        'languages' => array('en_us' => 'US English'),
        'large_scale_test' => false,
        'list_max_entries_per_page' => 20,
        'lock_default_user_name' => false,
        'log_memory_usage' => false,
        'login_nav' => true,
        'require_accounts' => true, 
        'rss_cache_time' => return_session_value_or_default('rss_cache_time',
            '10800'),
        'save_query' => 'all',
        'slow_query_time_msec' => '100',
        'time_formats' => array (
            'H:i'=>'23:00', 'h:ia'=>'11:00pm', 'h:iA'=>'11:00PM',
            'H.i'=>'23.00', 'h.ia'=>'11.00pm', 'h.iA'=>'11.00PM' ),
        'translation_string_prefix' =>
            return_session_value_or_default('translation_string_prefix', false),
        'upload_badext' => array (
            'php', 'php3', 'php4', 'php5', 'pl', 'cgi', 'py',
            'asp', 'cfm', 'js', 'vbs', 'html', 'htm' ),
        'upload_maxsize' => 3000000,
        'verify_client_ip' => true,
    );
    return( $sugar_config_defaults );
}

function sugar_config_union( $default, $override ){
    // a little different then array_merge and array_merge_recursive.  we want
    // the second array to override the first array if the same value exists,
    // otherwise merge the unique keys.  it handles arrays of arrays recursively
    // might be suitable for a generic array_union
    if( !is_array( $override ) ){
        $override = array();
    }
    foreach( $default as $key => $value ){
        if( !array_key_exists($key, $override) ){
            $override[$key] = $value;
        }
        else if( is_array( $key ) ){
            $override[$key] = sugar_config_union( $value, $override[$key] );
        }
    }
    return( $override );
}

function make_not_writable( $file ){
    // Returns true if the given file/dir has been made not writable
    $ret_val = false;
    if( is_file($file) || is_dir($file) ){
        if( !is_writable($file) ){
            $ret_val = true;
        }
        else {
            $original_fileperms = fileperms($file);

            // take away writable permissions
            $new_fileperms = $original_fileperms & ~0x0092;
            @chmod($file, $new_fileperms);

            if( !is_writable($file) ){
                $ret_val = true;
            }
        }
    }
    return $ret_val;
}


/** This function returns the name of the person.
  * It currently returns "first last".  It should not put the space if either name is not available.
  * It should not return errors if either name is not available.
  * If no names are present, it will return ""
  * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
  * All Rights Reserved.
  * Contributor(s): ______________________________________..
  */
function return_name($row, $first_column, $last_column)
{
	$first_name = "";
	$last_name = "";
	$full_name = "";

	if(isset($row[$first_column]))
	{
		$first_name = stripslashes($row[$first_column]);
	}

	if(isset($row[$last_column]))
	{
		$last_name = stripslashes($row[$last_column]);
	}

	$full_name = $first_name;

	// If we have a first name and we have a last name
	if($full_name != "" && $last_name != "")
	{
		// append a space, then the last name
		$full_name .= " ".$last_name;
	}
	// If we have no first name, but we have a last name
	else if($last_name != "")
	{
		// append the last name without the space.
		$full_name .= $last_name;
	}

	return $full_name;
}


function get_languages()
{
	global $sugar_config;
	return $sugar_config['languages'];
}

function get_language_display($key)
{
	global $sugar_config;
	return $sugar_config['languages'][$key];
}

function get_assigned_user_name($assigned_user_id)
{
	$user_list = &get_user_array(false,"");
	if(isset($user_list[$assigned_user_id]))
	{
		return $user_list[$assigned_user_id];
	}

	return "";
}
































































function get_user_array($add_blank=true, $status="Active", $assigned_user="")
{
	global $log;
	$user_array = get_register_value('user_array', $add_blank. $status . $assigned_user);
	if(!$user_array)
	{
		require_once('include/database/PearDatabase.php');
		$db = new PearDatabase();
		$temp_result = Array();
		// Including deleted users for now.
		if (empty($status)) {
				$query = "SELECT id, user_name from users";
		}
		else {
				$query = "SELECT id, user_name from users WHERE status='$status'";
		}
		if (!empty($assigned_user)) {
			 $query .= " OR id='$assigned_user'";
		}
		$query = $query.' order by user_name asc';
		$log->debug("get_user_array query: $query");
		$result = $db->query($query, true, "Error filling in user array: ");

		if ($add_blank==true){
			// Add in a blank row
			$temp_result[''] = '';
		}

		// Get the id and the name.
		while($row = $db->fetchByAssoc($result))
		{
			$temp_result[$row['id']] = $row['user_name'];
		}

		$user_array = $temp_result;
		set_register_value('user_array', $add_blank. $status . $assigned_user, $temp_result);
	}

	return $user_array;
}


function clean($string, $maxLength)
{
	$string = substr($string, 0, $maxLength);
	return escapeshellcmd($string);
}

/**
 * Copy the specified request variable to the member variable of the specified object.
 * Do no copy if the member variable is already set.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function safe_map($request_var, & $focus, $always_copy = false)
{
	safe_map_named($request_var, $focus, $request_var, $always_copy);
}

/**
 * Copy the specified request variable to the member variable of the specified object.
 * Do no copy if the member variable is already set.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function safe_map_named($request_var, & $focus, $member_var, $always_copy)
{
	global $log;
	if (isset($_REQUEST[$request_var]) && ($always_copy || is_null($focus->$member_var))) {
		$log->debug("safe map named called assigning '{$_REQUEST[$request_var]}' to $member_var");
		$focus->$member_var = $_REQUEST[$request_var];
	}
}

/** This function retrieves an application language file and returns the array of strings included in the $app_list_strings var.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 * If you are using the current language, do not call this function unless you are loading it for the first time */
function return_app_list_strings_language($language)
{
   global $app_list_strings;
   global $sugar_config;
   global $log;

	$default_language = $sugar_config['default_language'];
   $temp_app_list_strings = $app_list_strings;
   $language_used = $language;

   include("include/language/$language.lang.php");

   if(file_exists("include/language/$language.lang.override.php"))
   {
      include("include/language/$language.lang.override.php");
   }

   if(file_exists("include/language/$language.lang.php.override"))
   {
      include("include/language/$language.lang.php.override");
   }

   if(file_exists("custom/include/language/$language.lang.php"))
   {
      include("custom/include/language/$language.lang.php");
      $log->info("Found custom language file: $language.lang.php");
   }

   if(!isset($app_list_strings))
   {
      $log->warn("Unable to find the application language file for language: ".$language);

      require("include/language/$default_language.lang.php");

      if(file_exists("include/language/$default_language.lang.override.php"))
      {
         include("include/language/$default_language.lang.override.php");
      }

      if(file_exists("include/language/$default_language.lang.php.override"))
      {
         include("include/language/$default_language.lang.php.override");
      }

      $language_used = $default_language;
   }

   if(!isset($app_list_strings))
   {
      $log->fatal("Unable to load the application language file for the selected language($language) or the default language($default_language)");
      return null;
   }

   $return_value = $app_list_strings;
   $app_list_strings = $temp_app_list_strings;

   return $return_value;
}

/** This function retrieves an application language file and returns the array of strings included.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 * If you are using the current language, do not call this function unless you are loading it for the first time */
function return_application_language($language)
{
	global $app_strings, $sugar_config, $log;
	$temp_app_strings = $app_strings;
	$language_used = $language;
	$default_language = $sugar_config['default_language'];

	include("include/language/$language.lang.php");
	if(file_exists("include/language/$language.lang.override.php")){
			include("include/language/$language.lang.override.php");
	}
	if(file_exists("include/language/$language.lang.php.override")){
			include("include/language/$language.lang.php.override");
	}

	if(!isset($app_strings))
	{
		$log->warn("Unable to find the application language file for language: ".$language);
		require("include/language/$default_language.lang.php");
		if(file_exists("include/language/$default_language.lang.override.php")){
			include("include/language/$default_language.lang.override.php");
	}
		if(file_exists("include/language/$default_language.lang.php.override")){
			include("include/language/$default_language.lang.php.override");
	}
		$language_used = $default_language;
	}

	if(!isset($app_strings))
	{
		$log->fatal("Unable to load the application language file for the selected language($language) or the default language($default_language)");
		return null;
	}

	// If we are in debug mode for translating, turn on the prefix now!
	if($sugar_config['translation_string_prefix'])
	{
		foreach($app_strings as $entry_key=>$entry_value)
		{
			$app_strings[$entry_key] = $language_used.' '.$entry_value;
		}
	}

	$return_value = $app_strings;
	$app_strings = $temp_app_strings;

	return $return_value;
}

/** This function retrieves a module's language file and returns the array of strings included.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 * If you are in the current module, do not call this function unless you are loading it for the first time */
$mod_strings_array = array();
function return_module_language($language, $module)
{
   global $mod_strings_array;
   global $mod_strings;
   global $sugar_config;
   global $log;
   global $currentModule;
	$default_language = $sugar_config['default_language'];

	/*if($currentModule == $module && !empty($mod_strings))
	{

		// We should have already loaded the array.  return the current one.
		//$log->fatal("module strings already loaded for language: ".$language." and module: ".$module);
		return $mod_strings;
	}
	*/

   if(isset($mod_strings_array[$module]))
   {
      return $mod_strings_array[$module];
   }

   $temp_mod_strings = $mod_strings;
   $language_used = $language;

   if(!isset($language))
   {
   		$language = $default_language;
   }

   include("modules/$module/language/$language.lang.php");

   if(file_exists("modules/$module/language/$language.lang.override.php"))
   {
      include("modules/$module/language/$language.lang.override.php");
   }

   if(file_exists("modules/$module/language/$language.lang.php.override"))
   {
      echo 'Please Change:<br>' .
           "modules/$module/language/$language.lang.php.override" .
           '<br>to<br>' . 'Please Change:<br>' .
           "modules/$module/language/$language.lang.override.php";

      include("modules/$module/language/$language.lang.php.override");
   }

   // include the customized field information
   if(file_exists("custom/modules/$module/language/$language.lang.php"))
   {
      include("custom/modules/$module/language/$language.lang.php");
   }

	if(!isset($mod_strings))
	{
		$log->warn("Unable to find the module language file for language: ".$language." and module: ".$module);
		require("modules/$module/language/$default_language.lang.php");
		if(file_exists("modules/$module/language/$default_language.lang.php.override")){
			include("modules/$module/language/$default_language.lang.php.override");
	}
		$language_used = $default_language;
	}

	if(!isset($mod_strings))
	{
		$log->fatal("Unable to load the module($module) language file for the selected language($language) or the default language($default_language)");
		return null;
	}

	// If we are in debug mode for translating, turn on the prefix now!
	if($sugar_config['translation_string_prefix'])
	{
		foreach($mod_strings as $entry_key=>$entry_value)
		{
			$mod_strings[$entry_key] = $language_used.' '.$entry_value;
		}
	}

	$return_value = $mod_strings;
	$mod_strings = $temp_mod_strings;
	$mod_strings_array[$module] = $return_value;
	return $return_value;
}

/** This function retrieves an application language file and returns the array of strings included in the $mod_list_strings var.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 * If you are using the current language, do not call this function unless you are loading it for the first time */
function return_mod_list_strings_language($language,$module)
{
	global $mod_list_strings, $sugar_config, $log, $currentModule;

	$language_used = $language;
	$temp_mod_list_strings = $mod_list_strings;
	$default_language = $sugar_config['default_language'];

	if($currentModule == $module && isset($mod_list_strings) && $mod_list_strings != null)
	{
		return $mod_list_strings;
	}

	include("modules/$module/language/$language.lang.php");
	if(file_exists("modules/$module/language/$language.lang.override.php")){
			include("modules/$module/language/$language.lang.override.php");
	}
	if(file_exists("modules/$module/language/$language.lang.php.override")){
			echo 'Please Change:<br>' . "modules/$module/language/$language.lang.php.override" . '<br>to<br>' . 'Please Change:<br>' . "modules/$module/language/$language.lang.override.php";
			include("modules/$module/language/$language.lang.php.override");
	}
	if(!isset($mod_list_strings))
	{
		$log->fatal("Unable to load the application list language file for the selected language($language) or the default language($default_language)");
		return null;
	}

	$return_value = $mod_list_strings;
	$mod_list_strings = $temp_mod_list_strings;

	return $return_value;
}

/** This function retrieves a theme's language file and returns the array of strings included.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function return_theme_language($language, $theme)
{
	global $mod_strings, $sugar_config, $log, $currentModule;

	$language_used = $language;
	$default_language = $sugar_config['default_language'];

	include("themes/$theme/language/$current_language.lang.php");
	if(file_exists("themes/$theme/language/$current_language.lang.override.php")){
			include("themes/$theme/language/$current_language.lang.override.php");
	}
	if(file_exists("themes/$theme/language/$current_language.lang.php.override")){
			echo 'Please Change:<br>' . "themes/$theme/language/$current_language.lang.php.override" . '<br>to<br>' . 'Please Change:<br>' . "themes/$theme/language/$current_language.lang.override.php";
			include("themes/$theme/language/$current_language.lang.php.override");
	}
	if(!isset($theme_strings))
	{
		$log->warn("Unable to find the theme file for language: ".$language." and theme: ".$theme);
		require("themes/$theme/language/$default_language.lang.php");
		$language_used = $default_language;
	}

	if(!isset($theme_strings))
	{
		$log->fatal("Unable to load the theme($theme) language file for the selected language($language) or the default language($default_language)");
		return null;
	}

	// If we are in debug mode for translating, turn on the prefix now!
	if($sugar_config['translation_string_prefix'])
	{
		foreach($theme_strings as $entry_key=>$entry_value)
		{
			$theme_strings[$entry_key] = $language_used.' '.$entry_value;
		}
	}

	return $theme_strings;
}



/** If the session variable is defined and is not equal to "" then return it.  Otherwise, return the default value.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
*/
function return_session_value_or_default($varname, $default)
{
	if(isset($_SESSION[$varname]) && $_SESSION[$varname] != "")
	{
		return $_SESSION[$varname];
	}

	return $default;
}

/**
  * Creates an array of where restrictions.  These are used to construct a where SQL statement on the query
  * It looks for the variable in the $_REQUEST array.  If it is set and is not "" it will create a where clause out of it.
  * @param &$where_clauses - The array to append the clause to
  * @param $variable_name - The name of the variable to look for an add to the where clause if found
  * @param $SQL_name - [Optional] If specified, this is the SQL column name that is used.  If not specified, the $variable_name is used as the SQL_name.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
  */
function append_where_clause(&$where_clauses, $variable_name, $SQL_name = null)
{
	require_once('include/database/PearDatabase.php');

	if($SQL_name == null)
	{
		$SQL_name = $variable_name;
	}

	if(isset($_REQUEST[$variable_name]) && $_REQUEST[$variable_name] != "")
	{
		array_push($where_clauses, "$SQL_name like '".PearDatabase::quote($_REQUEST[$variable_name])."%'");
	}
}

/**
  * Generate the appropriate SQL based on the where clauses.
  * @param $where_clauses - An Array of individual where clauses stored as strings
  * @returns string where_clause - The final SQL where clause to be executed.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
  */
function generate_where_statement($where_clauses)
{
	global $log;
	$where = "";
	foreach($where_clauses as $clause)
	{
		if($where != "")
		$where .= " and ";
		$where .= $clause;
	}

	$log->info("Here is the where clause for the list view: $where");
	return $where;
}

/**
 * A temporary method of generating GUIDs of the correct format for our DB.
 * @return String contianing a GUID in the format: aaaaaaaa-bbbb-cccc-dddd-eeeeeeeeeeee
 *
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
*/
function create_guid()
{
    $microTime = microtime();
	list($a_dec, $a_sec) = explode(" ", $microTime);

	$dec_hex = sprintf("%x", $a_dec* 1000000);
	$sec_hex = sprintf("%x", $a_sec);

	ensure_length($dec_hex, 5);
	ensure_length($sec_hex, 6);

	$guid = "";
	$guid .= $dec_hex;
	$guid .= create_guid_section(3);
	$guid .= '-';
	$guid .= create_guid_section(4);
	$guid .= '-';
	$guid .= create_guid_section(4);
	$guid .= '-';
	$guid .= create_guid_section(4);
	$guid .= '-';
	$guid .= $sec_hex;
	$guid .= create_guid_section(6);

	return $guid;

}

function create_guid_section($characters)
{
	$return = "";
	for($i=0; $i<$characters; $i++)
	{
		$return .= sprintf("%x", mt_rand(0,15));
	}
	return $return;
}

function ensure_length(&$string, $length)
{
	$strlen = strlen($string);
	if($strlen < $length)
	{
		$string = str_pad($string,$length,"0");
	}
	else if($strlen > $length)
	{
		$string = substr($string, 0, $length);
	}
}

function microtime_diff($a, $b) {
   list($a_dec, $a_sec) = explode(" ", $a);
   list($b_dec, $b_sec) = explode(" ", $b);
   return $b_sec - $a_sec + $b_dec - $a_dec;
}

/**
 * Check if user id belongs to a system admin.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function is_admin($user) {
	if ($user->is_admin == 'on') return true;
	else return false;
}

/**
 * Return the display name for a theme if it exists.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_theme_display($theme) {
	global $theme_name, $theme_description;
	$temp_theme_name = $theme_name;
	$temp_theme_description = $theme_description;

	if (is_file("./themes/$theme/config.php")) {
		include("./themes/$theme/config.php");
		$return_theme_value = $theme_name;
	}
	else {
		$return_theme_value = $theme;
	}
	$theme_name = $temp_theme_name;
	$theme_description = $temp_theme_description;

	return $return_theme_value;
}

/**
 * Return an array of directory names.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_themes() {
   if ($dir = opendir("./themes")) {
		while (($file = readdir($dir)) !== false) {
           if ($file != ".." && $file != "." && $file != "CVS" && $file != "Attic") {
			   if(is_dir("./themes/".$file)) {
				   if(!($file[0] == '.')) {
				   	// set the initial theme name to the filename
				   	$name = $file;

				   	// if there is a configuration class, load that.
				   	if(is_file("./themes/$file/config.php"))
				   	{
				   		unset($theme_name);
				   		unset($version_compatibility);
				   		require("./themes/$file/config.php");
				   		$name = $theme_name;
				   		if(is_file("./themes/$file/header.php") && $version_compatibility >= 2.0)
				   		{
				   			$filelist[$file] = $name;
				   		}

				   	}

				   }
			   }
		   }
	   }
	   closedir($dir);
   }

   ksort($filelist);
   return $filelist;
}

/**
 * THIS FUNCTION IS DEPRECATED AND SHOULD NOT BE USED; USE get_select_options_with_id()
 * Create HTML to display select options in a dropdown list.  To be used inside
 * of a select statement in a form.
 * param $option_list - the array of strings to that contains the option list
 * param $selected - the string which contains the default value
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_select_options ($option_list, $selected) {
	return get_select_options_with_id($option_list, $selected);
}

/**
 * Create HTML to display select options in a dropdown list.  To be used inside
 * of a select statement in a form.   This method expects the option list to have keys and values.  The keys are the ids.  The values are the display strings.
 * param $option_list - the array of strings to that contains the option list
 * param $selected - the string which contains the default value
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_select_options_with_id ($option_list, $selected_key) {
	return get_select_options_with_id_separate_key($option_list, $option_list, $selected_key);
}


/**
 * Create HTML to display select options in a dropdown list.  To be used inside
 * of a select statement in a form.   This method expects the option list to have keys and values.  The keys are the ids.  The values are the display strings.
 * param $label_list - the array of strings to that contains the option list
 * param $key_list - the array of strings to that contains the values list
 * param $selected - the string which contains the default value
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_select_options_with_id_separate_key ($label_list, $key_list, $selected_key) {
	global $app_strings;
	$select_options = "";

	//for setting null selection values to human readable --None--
	$pattern = "/'0?'></";
	$replacement = "''>".$app_strings['LBL_NONE']."<";

	//create the type dropdown domain and set the selected value if $opp value already exists
	foreach ($key_list as $option_key=>$option_value) {

		$selected_string = '';
		// the system is evaluating $selected_key == 0 || '' to true.  Be very careful when changing this.  Test all cases.
		// The bug was only happening with one of the users in the drop down.  It was being replaced by none.
		if (($option_key != '' && $selected_key == $option_key) || ($selected_key == '' && $option_key == '') || (is_array($selected_key) &&  in_array($option_key, $selected_key)))
		{
			$selected_string = 'selected ';
		}

		$html_value = $option_key;

		$select_options .= "\n<OPTION ".$selected_string."value='$html_value'>$label_list[$option_key]</OPTION>";
	}
	$select_options = preg_replace($pattern, $replacement, $select_options);

	return $select_options;
}

/**
 * Call this method instead of die().
 * Then we call the die method with the error message that is passed in.
 */
function sugar_die($error_message)
{
	global $focus;
	die($error_message);
}

/**
 * Create javascript to clear values of all elements in a form.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_clear_form_js () {
$the_script = <<<EOQ
<script type="text/javascript" language="JavaScript">
<!-- Begin
function clear_form(form) {
	var newLoc = 'index.php?action=' + form.action.value + '&module=' + form.module.value + '&query=true&clear_query=true';
	if(typeof(form.advanced) != 'undefined'){
		newLoc += '&advanced=' + form.advanced.value;
	}
	document.location.href= newLoc;
}
//  End -->
</script>
EOQ;

return $the_script;
}

/**
 * Create javascript to set the cursor focus to specific field in a form
 * when the screen is rendered.  The field name is currently hardcoded into the
 * the function.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_set_focus_js () {
//TODO Clint 5/20 - Make this function more generic so that it can take in the target form and field names as variables
$the_script = <<<EOQ
<script type="text/javascript" language="JavaScript">
<!-- Begin
function set_focus() {
	if (document.forms.length > 0) {
		for (i = 0; i < document.forms.length; i++) {
			for (j = 0; j < document.forms[i].elements.length; j++) {
				var field = document.forms[i].elements[j];
				if ((field.type == "text" || field.type == "textarea" || field.type == "password") &&
						!field.disabled && (field.name == "first_name" || field.name == "name" || field.name == "user_name" || field.name=="document_name")) {
					field.focus();
                    if (field.type == "text") {
                        field.select();
                    }
					break;
	    		}
			}
      	}
   	}
}
//  End -->
</script>
EOQ;

return $the_script;
}

/**
 * Very cool algorithm for sorting multi-dimensional arrays.  Found at http://us2.php.net/manual/en/function.array-multisort.php
 * Syntax: $new_array = array_csort($array [, 'col1' [, SORT_FLAG [, SORT_FLAG]]]...);
 * Explanation: $array is the array you want to sort, 'col1' is the name of the column
 * you want to sort, SORT_FLAGS are : SORT_ASC, SORT_DESC, SORT_REGULAR, SORT_NUMERIC, SORT_STRING
 * you can repeat the 'col',FLAG,FLAG, as often you want, the highest prioritiy is given to
 * the first - so the array is sorted by the last given column first, then the one before ...
 * Example: $array = array_csort($array,'town','age',SORT_DESC,'name');
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function array_csort() {
   $args = func_get_args();
   $marray = array_shift($args);
   $i = 0;

   $msortline = "return(array_multisort(";
   foreach ($args as $arg) {
	   $i++;
	   if (is_string($arg)) {
		   foreach ($marray as $row) {
			   $sortarr[$i][] = $row[$arg];
		   }
	   } else {
		   $sortarr[$i] = $arg;
	   }
	   $msortline .= "\$sortarr[".$i."],";
   }
   $msortline .= "\$marray));";

   eval($msortline);
   return $marray;
}

/**
 * Converts localized date format string to jscalendar format
 * Example: $array = array_csort($array,'town','age',SORT_DESC,'name');
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function parse_calendardate($local_format) {
	preg_match("/\(?([^-]{1})[^-]*-([^-]{1})[^-]*-([^-]{1})[^-]*\)/", $local_format, $matches);
	$calendar_format = "%" . $matches[1] . "-%" . $matches[2] . "-%" . $matches[3];
	return str_replace(array("y", "�", "a", "j"), array("Y", "Y", "Y", "d"), $calendar_format);
}





function translate($string, $mod='', $selectedValue=''){
	if(!empty($mod)){
		global $current_language;
		$mod_strings = return_module_language($current_language, $mod);
	}else{
		global $mod_strings;
	}
//	echo "AFTER!!!";
//	print_r($mod_strings);
//	echo "AFTER!!!";
	
	$returnValue = '';
	global $app_strings, $app_list_strings;

//	$string = str_replace("MOD.", "", $string);
	
	if(isset($mod_strings[$string]))
		$returnValue = $mod_strings[$string];
	else if(isset($app_strings[$string]))
		$returnValue = $app_strings[$string];
	else if(isset($app_list_strings[$string]))
		$returnValue = $app_list_strings[$string];

	if(empty($returnValue)){
		return $string;
	}

	if(is_array($returnValue) && ! empty($selectedValue) ){
		return $returnValue[$selectedValue];
	}
	
	return $returnValue;
}

function add_http($url)
	{
		if(!eregi("://", $url))
		{
			return 'http://'.$url;
		}
		return $url;
	}

// Designed to take a string passed in the URL as a parameter and clean all "bad" data from it
// The second argument is a string, "filter," which corresponds to a regular expression
function clean_string($str, $filter = "STANDARD") {
	global $log, $sugar_config;

	$filters = Array(
		"STANDARD"        => "#[^A-Z0-9\-_\.\@]#i",
		"STANDARDSPACE"        => "#[^A-Z0-9\-_\.\@\ ]#i",
		"FILE"            => "#[^A-Z0-9\-_\.]#i",
		"NUMBER"          => "#[^0-9\-]#i",
		"SQL_COLUMN_LIST" => "#[^A-Z0-9,_\.]#i"
	);

	if (($str != escapeshellcmd($str)) || (preg_match($filters[$filter], $str))) {
		if (!isset($log)) {
			require_once('include/logging.php');
			$log = LoggerManager::getLogger('clean_string');
		}
		$log->fatal("SECURITY: bad data passed in; string: {$str}");
		die("Bad data passed in; <a href=\"{$sugar_config['site_url']}\">Return to Home</a>");
	}
	else {
		return $str;
	}
}

// Works in conjunction with clean_string() to defeat SQL injection, file inclusion attacks, and XSS
function clean_incoming_data() {
	global $sugar_config;

	if (get_magic_quotes_gpc() == 1) {
 		$_REQUEST = array_map("preprocess_param", $_REQUEST);
		$_POST = array_map("preprocess_param", $_POST);
		$_GET = array_map("preprocess_param", $_GET);
	}
	else {
		$_REQUEST = array_map("securexss", $_REQUEST);
		$_POST = array_map("securexss", $_POST);
		$_GET = array_map("securexss", $_GET);
	}

	// Any additional variables that need to be cleaned should be added here
	if (isset($_REQUEST['action'])) clean_string($_REQUEST['action']);
	if (isset($_REQUEST['module'])) clean_string($_REQUEST['module']);
	if (isset($_REQUEST['record'])) clean_string($_REQUEST['record'], 'STANDARDSPACE');
	if (isset($_SESSION['authenticated_user_theme'])) clean_string($_SESSION['authenticated_user_theme']);
	if (isset($_SESSION['authenticated_user_language'])) clean_string($_SESSION['authenticated_user_language']);
	if (isset($sugar_config['default_theme'])) clean_string($sugar_config['default_theme']);

	// Clean "offset" and "order_by" parameters in URL
	foreach ($_GET as $key => $val) {
		if (str_end($key, "_offset")) {
			clean_string($_GET[$key], "NUMBER");
		}
		elseif (str_end($key, "_ORDER_BY")) {
			clean_string($_GET[$key], "SQL_COLUMN_LIST");
		}
	}

	return 0;
}

// Returns TRUE if $str begins with $begin
function str_begin($str, $begin) {
	return (substr($str, 0, strlen($begin)) == $begin);
}

// Returns TRUE if $str ends with $end
function str_end($str, $end) {
	return (substr($str, strlen($str) - strlen($end)) == $end);
}

function securexss($value) {
	$xss_cleanup=  array('"' =>'&quot;', "'" =>  '&#039;' , '<' =>'&lt;' , '>'=>'&gt;');
	$value = preg_replace('/javascript:/i', 'java script:', $value);
	return str_replace(array_keys($xss_cleanup), array_values($xss_cleanup), $value);
}

function preprocess_param($value){
 	if(is_string($value)){
	 	if(get_magic_quotes_gpc() == 1){
	 		$value = stripslashes($value);
	 	}
	 	$value = securexss($value);
 	}
 	return $value;


}

if(empty($register))$register = array();

function set_register_value($category, $name, $value){
	global $register;
	if(empty($register[$category]))
		$register[$category] = array();
	$register[$category][$name] = $value;
}

function get_register_value($category,$name){
	global $register;
	if(empty($register[$category]) || empty($register[$category][$name])){
		return false;
	}
	return $register[$category][$name];
}

function get_register_values($category){
	global $register;
	if(empty($register[$category])){
		return false;
	}
	return $register[$category];
}

function clear_register($category, $name){
	global $register;
	if(empty($name)){
		unset($register[$category]);
	}else{
		if(!empty($register[$category]))
			unset($register[$category][$name]);
	}
}

// this function cleans id's when being imported
function convert_id($string)
{
 return preg_replace_callback( '|[^A-Za-z0-9\-]|',
        create_function(
             // single quotes are essential here,
             // or alternative escape all $ as \$
             '$matches',
             'return ord($matches[0]);'
         ) ,$string);
}

function get_image($image,$other_attributes,$width="",$height=""){
	global $png_support;
	if ($png_support == false)
	$ext = "gif";
	else
	$ext = "png";
	if (file_exists($image.'.'.$ext)){
		$size=getimagesize($image.'.'.$ext);
		if ($width == "") { $width = $size[0];}
		if ($height == "") { $height = $size[1];}
		$out= "<img src='$image.$ext' width='".$width."' height='".$height."' $other_attributes>";
	} else {
		$out = "";
	}
	return $out;
}

function getSQLDate($date_str)
{
                if (preg_match('/^(\d{1,2})-(\d{1,2})-(\d{4})$/',$date_str,$match))
                {
                        if ( strlen($match[2]) == 1)
                        {
                                $match[2] = "0".$match[2];
                        }
                        if ( strlen($match[1]) == 1)
                        {
                                $match[1] = "0".$match[1];
                        }
                        return "{$match[3]}-{$match[1]}-{$match[2]}";
                }
                else if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/',$date_str,$match))
                {
                        if ( strlen($match[2]) == 1)
                        {
                                $match[2] = "0".$match[2];
                        }
                        if ( strlen($match[1]) == 1)
                        {
                                $match[1] = "0".$match[1];
                        }
                        return "{$match[3]}-{$match[1]}-{$match[2]}";
                }
                else
                {
                        return "";
                }
}

function clone_history(&$db, $from_id,$to_id, $to_type){
$tables = array('emails', 'calls', 'meetings', 'notes', 'tasks');

foreach($tables as $table){
$query = "SELECT * FROM $table WHERE parent_id='$from_id'";
$results = $db->query($query);
while($row = $db->fetchByAssoc($results)){
	$query = "INSERT INTO $table ";
	$names = '';
	$values = '';
	$row['parent_id'] = $to_id;
	$row['id'] = create_guid();
	$row['parent_type'] = $to_type;
	foreach($row as $name=>$value){

			if(empty($names)){
				$names .= $name;
				$values .= "'$value'";
			}else {
				$names .= ', '. $name;
				$values .= ", '$value'";
			}
	}

	$query .= "($names)	VALUES ($values);";
	$db->query($query);
}
}
}

function values_to_keys($array){
	$new_array = array();
	if(!is_array($array)){
		return $new_array;
	}
	foreach($array as $arr){
		$new_array[$arr] = $arr;
	}
	return $new_array;
}

function clone_relationship(&$db, $tables = array(), $from_column, $from_id, $to_id){
foreach($tables as $table){
$query = "SELECT * FROM $table WHERE $from_column='$from_id'";
$results = $db->query($query);
while($row = $db->fetchByAssoc($results)){
	$query = "INSERT INTO $table ";
	$names = '';
	$values = '';
	$row[$from_column] = $to_id;
	$row['id'] = create_guid();
	foreach($row as $name=>$value){

			if(empty($names)){
				$names .= $name;
				$values .= "'$value'";
			}else {
				$names .= ', '. $name;
				$values .= ", '$value'";
			}
	}

	$query .= "($names)	VALUES ($values);";
	$db->query($query);
}
}

}

function number_empty($value){
	return empty($value) && $value != '0';
}

function get_bean_select_array($add_blank=true, $bean_name,$display_columns,$where='',$order_by='')
{
        global $log,$beanFiles;
        require_once($beanFiles[$bean_name]);
        $focus = new $bean_name();
        $user_array = array();
        $user_array = get_register_value('select_array',$bean_name. $display_columns. $where . $order_by);
       if(!$user_array)
        {
                require_once('include/database/PearDatabase.php');
                $db = new PearDatabase();
                $temp_result = Array();
                $query = "SELECT id, {$display_columns} as display from {$focus->table_name} where ";
                if ( $where != '')
                {
                        $query .= " AND ";
                }

                $query .=  " deleted=0";

                if ( $order_by != '')
                {
                        $query .= ' order by '.$order_by;
                }
                $log->debug("get_user_array query: $query");
                $result = $db->query($query, true, "Error filling in user array: ");

                if ($add_blank==true){
                        // Add in a blank row
                        $temp_result[''] = '';
                }

                // Get the id and the name.
                while($row = $db->fetchByAssoc($result))
                {
                        $temp_result[$row['id']] = $row['display'];
                }

                $user_array = $temp_result;
        	set_register_value('select_array',$bean_name. $display_columns. $where . $order_by,$temp_result);
        }

        return $user_array;

}

// function parse_list_modules
// searches a list for items in a user's allowed tabs and returns an array that removes unallowed tabs from list
function parse_list_modules(&$listArray)
{
	global $modListHeader;

	$returnArray = array();

	foreach($listArray as $optionName => $optionVal)
	{
		if(array_key_exists($optionName, $modListHeader))
		{
			$returnArray[$optionName] = $optionVal;
		}
		// special case for products
		if(array_key_exists('Products', $modListHeader))
		{
			$returnArray['ProductTemplates'] = 'Product';
		}

		// special case for projects
		if(array_key_exists('Project', $modListHeader))
		{
			$returnArray['ProjectTask'] = 'Project Task';
		}
	}

	return $returnArray;
}

function display_notice($msg, $log = false){
	global $error_notice;
	//no error notice - lets just display the error to the user
	if(!isset($error_notice)){
		echo '<br>'.$msg . '<br>';;
	}else{
		$error_notice .= $msg . '<br>';
	}
}

/* checks if it is a number that atleast has the plus at the beggining
 */
function skype_formatted($number){
	return substr($number, 0, 1) == '+' || substr($number, 0, 2) == '00' || substr($number, 0, 2) == '011';
}
require_once('include/utils/db_utils.php');

?>
