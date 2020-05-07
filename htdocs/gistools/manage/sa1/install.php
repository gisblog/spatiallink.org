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

// $Id: install.php,v 1.78.2.2 2005/05/17 23:18:40 bob Exp $

require_once('sugar_version.php');
$setup_sugar_version = $sugar_version;

if(!is_dir('install')){
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <meta http-equiv="Content-Style-Type" content="text/css">
   <title>SugarCRM Setup Wizard Locked</title>
   <link rel="stylesheet" href="install/install.css" type="text/css">
</head>

<body>
  <table cellspacing="0" cellpadding="0" border="0" align="center" class=
  "shell">
    <tr>
      <th width="400">SugarCRM Setup Wizard Locked</th>
	</tr>

    <tr>
	 	<td>
        <p>Your SugarCRM application has been secured to prevent you from
        running the installation again.  To allow yourself to run the
        installation again you must rename your installation directory
        back to 'install'.</p>

        <p>For installation help, please visit the SugarCRM <a href=
        "http://www.sugarcrm.com/forums/" target="_blank">support
        forums</a>.</p>
      </td>
    </tr>

    <tr>
      <td>
        <hr>
      </td>
    </tr>
  </table>
</body>
</html>

<?php
}
else {

$install_script = true;

session_start();

if (substr(phpversion(), 0, 1) == "5"){
   ini_set("zend.ze1_compatibility_mode", "1");
}

require_once('include/utils.php');

$current_language = 'en_us';

// one place for form validation/conversion to boolean
function get_boolean_from_request( $field ){
    if( !isset($_REQUEST[$field]) ){
        return( false );
    }

    if( ($_REQUEST[$field] == 'on') || ($_REQUEST[$field] == 'yes') ){
        return(true);
    }
    else {
        return(false);
    }
}

function stripslashes_checkstrings($value){
   if(is_string($value)){
      return stripslashes($value);
   }
   return $value;
}

if(get_magic_quotes_gpc() == 1){
   $_REQUEST = array_map("stripslashes_checkstrings", $_REQUEST);
   $_POST = array_map("stripslashes_checkstrings", $_POST);
   $_GET = array_map("stripslashes_checkstrings", $_GET);
}

function print_debug_array( $name, $debug_array ){
    ksort( $debug_array );

    print( "$name vars:\n" );
    print( "(\n" );

    foreach( $debug_array as $key => $value ){
        if( stristr( $key, "password" ) ){
            $value = "WAS SET";
        }
        print( "    [$key] => $value\n" );
    }

    print( ")\n" );
}

function print_debug_comment(){
    if( !empty($_REQUEST['debug']) ){
        $_SESSION['debug'] = $_REQUEST['debug'];
    }

    if( !empty($_SESSION['debug']) && ($_SESSION['debug'] == 'true') ){
        print( "<!-- debug is on (to turn off, hit any page with 'debug=false' as a URL parameter.\n" );

        print_debug_array( "Session",   $_SESSION );
        print_debug_array( "Request",   $_REQUEST );
        print_debug_array( "Post",      $_POST );
        print_debug_array( "Get",       $_GET );

        print_r( "-->\n" );
    }
}

function validate_dbConfig(){
    $errors = array();
    $is_valid_database_name = false;

    if( $_SESSION['setup_db_host_name'] == '' ){
        $errors[] = 'Host name cannot be blank.';
    }

    if( $_SESSION['setup_db_database_name'] == '' ){
        $errors[] = 'Database name cannot be blank.';
    }
    else if( preg_match( "/[\\\\\/\.]/", $_SESSION['setup_db_database_name'] ) ){
        $errors[] = "Database name cannot contain a '\\', '/', or '.'";
    }
    else{
        $is_valid_database_name = true;
    }

    if( $_SESSION['setup_db_sugarsales_user'] == '' ){
        $errors[] = 'User name for SugarCRM cannot be blank.';
    }

    if( $_SESSION['setup_db_create_sugarsales_user'] &&
            ($_SESSION['setup_db_sugarsales_password'] != $_SESSION['setup_db_sugarsales_password_retype']) ){
        $errors[] = 'Passwords for SugarCRM do not match.';
    }

    // test the account that will talk to the db if we're not creating it
    if( $_SESSION['setup_db_sugarsales_user'] != '' &&
            !$_SESSION['setup_db_create_sugarsales_user'] &&
            ($_SESSION['setup_db_sugarsales_password'] == $_SESSION['setup_db_sugarsales_password_retype']) ){
        $link = mysql_connect(  $_SESSION['setup_db_host_name'],
                                $_SESSION['setup_db_sugarsales_user'],
                                $_SESSION['setup_db_sugarsales_password'] );
        if( !$link ){
            $errno = mysql_errno();
            $error = mysql_error();
            $errors[] = "SugarCRM database user name and/or password is invalid (Error $errno: $error).";
        }
        else{
            mysql_close( $link );
        }
    }

    // privileged account tests
    if( $_SESSION['setup_db_admin_user_name'] == '' ){
        $errors[] = 'Database admin user name is required.';
    }
    else {
        if( $_SESSION['setup_db_type'] == 'mysql' ){
            $link = @mysql_connect( $_SESSION['setup_db_host_name'],
                                    $_SESSION['setup_db_admin_user_name'],
                                    $_SESSION['setup_db_admin_password'] );
            if( $link ){
                // database admin credentials are valid--can continue check on stuff

                // check for existing database
                if( $is_valid_database_name ){
                    $db_selected = @mysql_select_db($_SESSION['setup_db_database_name'], $link);
                    if( $db_selected && $_SESSION['setup_db_create_database'] ){
                        $errors[] = "Database name already exists--cannot create another one with the same name.";
                    }
                    else if( !$db_selected && !$_SESSION['setup_db_create_database'] ){
                        $errors[] = "Database specified does not exist.";
                    }

                    // test for upgrade and inform user about the upgrade wizard
                    if( $db_selected ){
                        $config_query   = "show tables like 'config'";
                        $config_result  = mysql_query( $config_query, $link );
                        $config_table_exists    = (mysql_num_rows( $config_result ) == 1);
                        mysql_free_result( $config_result );

                        if( !$_SESSION['setup_db_drop_tables'] && $config_table_exists ){
                            $query = "select count(*) from config where category='info' and name='sugar_version' and value like '3.0%'";
                            $result = mysql_unbuffered_query( $query, $link );
                            $row = mysql_fetch_row( $result );

                            if($row[0] != 1){
                                $errors[] = 'Upgrades are now performed using the upgrade wizard.  Please read UPGRADE.TXT for more information.';
                            }
                            mysql_free_result($result);
                        }
                    }
                }

                // check for existing SugarCRM database user
                if($_SESSION['setup_db_create_sugarsales_user'] && $_SESSION['setup_db_sugarsales_user'] != ''){
                    $db_selected = mysql_select_db('mysql', $link);
                    $user = $_SESSION['setup_db_sugarsales_user'];
                    $query = "select count(*) from user where User='$user'";
                    $result = mysql_unbuffered_query($query, $link);
                    $row = mysql_fetch_row($result);

                    if($row[0] == 1){
                        $errors[] = 'User name for SugarCRM already exists--cannot create another one with the same name.';
                    }
                    mysql_free_result($result);
                }
                mysql_close($link);
            }
            else { // dblink was bad
                $errno = mysql_errno();
                $error = mysql_error();
                $errors[] = "Database admin user name and/or password is invalid (Error $errno: $error).";
            }
        }
        else if( $_SESSION['setup_db_type'] == 'oci8' ){








        }
    } // end of privileged user tests

    return $errors;
}

function validate_siteConfig(){
   $errors = array();

   if($_SESSION['setup_site_url'] == ''){
      $errors[] = 'URL cannot be blank.';
   }

   if($_SESSION['setup_site_admin_password'] == ''){
      $errors[] = 'SugarCRM admin password cannot be blank.';
   }

   if($_SESSION['setup_site_admin_password'] != $_SESSION['setup_site_admin_password_retype']){
      $errors[] = 'Passwords for SugarCRM admin do not match.';
   }

   if($_SESSION['setup_site_custom_session_path'] && $_SESSION['setup_site_session_path'] == ''){
      $errors[] = 'Session path is required if you wish to specify your own.';
   }

   if($_SESSION['setup_site_custom_session_path'] && $_SESSION['setup_site_session_path'] != ''){
      if(is_dir($_SESSION['setup_site_session_path'])){
         if(!is_writable($_SESSION['setup_site_session_path'])){
            $errors[] = 'Session directory provided is not a writable directory.';
         }
      }
      else {
         $errors[] = 'Session directory provided is not a valid directory.';
      }
   }

   if($_SESSION['setup_site_custom_log_dir'] && $_SESSION['setup_site_log_dir'] == ''){
      $errors[] = 'Log directory is required if you wish to specify your own.';
   }

   if($_SESSION['setup_site_custom_log_dir'] && $_SESSION['setup_site_log_dir'] != ''){
      if(is_dir($_SESSION['setup_site_log_dir'])){
         if(!is_writable($_SESSION['setup_site_log_dir'])){
            $errors[] = 'Log directory provided is not a writable directory.';
         }
      }
      else {
         $errors[] = 'Log directory provided is not a valid directory.';
      }
   }

   if($_SESSION['setup_site_specify_guid'] && $_SESSION['setup_site_guid'] == ''){
      $errors[] = 'Application ID is required if you wish to specify your own.';
   }

   return $errors;
}

print_debug_comment();

$next_clicked = false;
$next_step = 0;

// use a simple array to map out the steps of the installer page flow
$workflow = array(
                    '0welcome.php',
                    'license.php',
                    '1checkSystem.php',
                    '2dbConfig.php',
                    '3siteConfig.php',



                    'confirmSettings.php',
                    '4performSetup.php',
                    '5register.php',
                );

// increment/decrement the workflow pointer
if(!empty($_REQUEST['goto'])){
    switch($_REQUEST['goto']){
        case 'Re-check':
            $next_step = $_REQUEST['current_step'];
            break;
        case 'Back':
            $next_step = $_REQUEST['current_step'] - 1;
            break;
        case 'Next':
        case 'Start':
            $next_step = $_REQUEST['current_step'] + 1;
            $next_clicked = true;
            break;
        case 'SilentInstall':
            $next_step = 9999;
            break;
    }
}

$validation_errors = array();

// process the data posted
if($next_clicked){
    // store the submitted data because the 'Next' button was clicked
    switch($workflow[$_REQUEST['current_step']]){
        case '0welcome.php':
            // eventually default all vars here, with overrides from config.php
            if( is_readable('config.php') ) {
                include_once('config.php');
            }

            $default_db_type = 'mysql';









            $_SESSION['setup_db_type'] = empty($sugar_config['dbconfig']['db_type']) ? $default_db_type : $sugar_config['dbconfig']['db_type'];
            break;
        case 'license.php':
            $_SESSION['setup_license_accept']   = get_boolean_from_request( 'setup_license_accept' );
            $_SESSION['license_submitted']      = true;
            break;
        case '2dbConfig.php':
            $_SESSION['setup_db_host_name']                     = $_REQUEST['setup_db_host_name'];
            $_SESSION['setup_db_database_name']                 = $_REQUEST['setup_db_database_name'];
            $_SESSION['setup_db_create_database']               = get_boolean_from_request( 'setup_db_create_database' );
            $_SESSION['setup_db_sugarsales_user']               = $_REQUEST['setup_db_sugarsales_user'];
            $_SESSION['setup_db_create_sugarsales_user']        = get_boolean_from_request( 'setup_db_create_sugarsales_user' );
            $_SESSION['setup_db_sugarsales_password']           = $_REQUEST['setup_db_sugarsales_password'];
            $_SESSION['setup_db_sugarsales_password_retype']    = $_REQUEST['setup_db_sugarsales_password_retype'];
            $_SESSION['setup_db_drop_tables']                   = get_boolean_from_request( 'setup_db_drop_tables' );
            $_SESSION['setup_db_pop_demo_data']                 = get_boolean_from_request( 'setup_db_pop_demo_data' );
            $_SESSION['setup_db_username_is_privileged']        = get_boolean_from_request( 'setup_db_username_is_privileged' );
            if( $_SESSION['setup_db_username_is_privileged'] == true ){
                $_SESSION['setup_db_admin_user_name']           = $_SESSION['setup_db_sugarsales_user'];
                $_SESSION['setup_db_admin_password']            = $_SESSION['setup_db_sugarsales_password'];
            }
            else{
                $_SESSION['setup_db_admin_user_name']           = $_REQUEST['setup_db_admin_user_name'];
                $_SESSION['setup_db_admin_password']            = $_REQUEST['setup_db_admin_password'];
            }
            $_SESSION['dbConfig_submitted']                     = true;
            $validation_errors = validate_dbConfig();
            if(count($validation_errors) > 0){
                $next_step--;
            }
            break;
        case '3siteConfig.php':
            $_SESSION['setup_site_url']                     = $_REQUEST['setup_site_url'];
            $_SESSION['setup_site_admin_password']          = $_REQUEST['setup_site_admin_password'];
            $_SESSION['setup_site_admin_password_retype']   = $_REQUEST['setup_site_admin_password_retype'];
            $_SESSION['setup_site_defaults']                = get_boolean_from_request( 'setup_site_defaults' );
            $_SESSION['setup_site_custom_session_path']     = get_boolean_from_request( 'setup_site_custom_session_path' );
            $_SESSION['setup_site_session_path']            = $_REQUEST['setup_site_session_path'];
            $_SESSION['setup_site_custom_log_dir']          = get_boolean_from_request( 'setup_site_custom_log_dir' );
            $_SESSION['setup_site_log_dir']                 = $_REQUEST['setup_site_log_dir'];
            $_SESSION['setup_site_specify_guid']            = get_boolean_from_request( 'setup_site_specify_guid' );
            $_SESSION['setup_site_guid']                    = $_REQUEST['setup_site_guid'];
            $_SESSION['siteConfig_submitted']               = true;

            $validation_errors = validate_siteConfig();
            if(count($validation_errors) > 0) {
                $next_step--;
            }
            break;








    }
}

function pullSilentInstallVarsIntoSession(){
    require_once('config.php');
    require_once('config_si.php');

    $config_subset = array (
        'setup_site_url'                => $sugar_config['site_url'],
        'setup_db_host_name'            => $sugar_config['dbconfig']['db_host_name'],
        'setup_db_sugarsales_user'      => $sugar_config['dbconfig']['db_user_name'],
        'setup_db_sugarsales_password'  => $sugar_config['dbconfig']['db_password'],
        'setup_db_database_name'        => $sugar_config['dbconfig']['db_name'],
        'setup_db_type'                 => $sugar_config['dbconfig']['db_type'],
    );

    // third array of values derived from above values
    $derived = array (
        'setup_site_admin_password_retype'      => $sugar_config_si['setup_site_admin_password'],
        'setup_db_sugarsales_password_retype'   => $config_subset['setup_db_sugarsales_password'],
    );

    $all_config_vars = array_merge( $config_subset, $sugar_config_si, $derived );

    foreach( $all_config_vars as $key => $value ){
        $_SESSION[$key] = $value;
    }
}

if( $next_step == 9999 ){
    $the_file = 'SilentInstall';
}
else{
    $the_file = $workflow[$next_step];
}

switch( $the_file ){
    case '0welcome.php':
        // check to see if installer has been disabled
        if( is_readable('config.php') && (filesize('config.php') > 0) ) {
            include_once('config.php');

            if( !isset($sugar_config['installer_locked']) || $sugar_config['installer_locked'] == true ){
                $the_file = 'installDisabled.php';
            }
        }
        break;
    case '5register.php':
        session_unset();
        break;
    case 'SilentInstall':
        pullSilentInstallVarsIntoSession();
        $validation_errors = validate_dbConfig();
        if( count($validation_errors) > 0 ){
            $the_file = '2dbConfig.php';
        }
        else {
            $validation_errors = validate_siteConfig();
            if( count($validation_errors) > 0 ){
                $the_file = '3siteConfig.php';
            }
            else {
                $the_file = '4performSetup.php';
            }
        }

        // check whether we're getting this request from a command line tool
        // we want to output brief messages if we're outputting to a command line tool
        $cli_mode = false;
        if( isset($_REQUEST['cli']) && ($_REQUEST['cli'] == 'true') ){
            $_SESSION['cli'] = true;
            // if we have errors, just shoot them back now
            if( count($validation_errors) > 0 ){
                foreach( $validation_errors as $error ){
                    print( "The following errors were encountered:\n" );
                    print( "    " . $error . "\n" );
                    print( "Exit 1\n" );
                    exit( 1 );
                }
            }
        }
        break;
}

$the_file = clean_string($the_file, 'FILE');

// change to require to get a good file load error message if the file is not available.
require('install/' . $the_file);

    print_debug_comment();
}
?>
