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

// $Id: index.php,v 1.3.2.2 2005/05/06 04:12:25 majed Exp $

$upgrade_script = true;

session_start();

if (substr(phpversion(), 0, 1) == "5"){
	ini_set("zend.ze1_compatibility_mode", "1");
}

require_once('dir_inc.php');
if(!file_exists('../config.php')){
	// add something here
}
require_once('../config.php');

$current_language = 'en_us';
$current_version = '3.0';
$current_patch = 'b';
$previous_patches = 'a';
// form validation/conversion to boolean
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

function validate_settings(){
    global $sugar_config;

    $errors = array();

    if( $_SESSION['upgrade_target_dir'] == '' ){
        $errors[] = 'Current Sugar Directory cannot be empty.';
    }
    else if( !is_dir( $_SESSION['upgrade_target_dir'] ) ){
        $errors[] = 'The specified Current Sugar Directory does not exist.';
    }
    else if( !is_file( $_SESSION['upgrade_target_dir'] . "/sugar_version.php" ) ){
        $errors[] = 'The specified Current Sugar Directory does not appear to be the base directory.';
    }
    else{
        include_once( $_SESSION['upgrade_target_dir'] . "/sugar_version.php" );
        if( !preg_match( "/^2\.5\.1/", $sugar_version ) && !preg_match( "/^3\.0/", $sugar_version ) ){
            $errors[] = "Sugar Directory is version $sugar_version, but only upgrades from 2.5.1 are supported.";
        }

        $original_dir   = getCwd();

        chdir( $_SESSION['upgrade_target_dir'] );
        $all_dest_files = findAllFiles( ".", array() );

        foreach( $all_dest_files as $dest_file ){
            if( !is_writable( $dest_file ) ){
                $errors[] = "All files under " . $_SESSION['upgrade_target_dir'] . " need to be writable.";
                break;
            }
        }
        chdir( $original_dir );
    }

    if( $_SESSION['upgrade_source_dir'] == '' ){
        $errors[] = 'Sugar 3.0 Directory cannot be empty.';
    }
    else if( !is_dir( $_SESSION['upgrade_source_dir'] ) ){
        $errors[] = 'The specified Sugar 3.0 Directory does not exist.';
    }
    else if( !is_file( $_SESSION['upgrade_source_dir'] . "/sugar_version.php" ) ){
        $errors[] = 'The specified Sugar 3.0 Directory does not appear to be the base directory.';
    }
    else{
        include_once( $_SESSION['upgrade_source_dir'] . "/sugar_version.php" );
        if( !preg_match( "/^3\.0/", $sugar_version ) ){
            $errors[] = 'Sugar Directory is version $sugar_version, but should be 3.0.';
        }
    }

    if( $_SESSION['upgrade_db_admin_username'] == '' ){
        $errors[] = 'Privileged database account name cannot be empty.';
    }

    if( count($errors) == 0 ){
        $db_name = $sugar_config['dbconfig']['db_host_name'];
        $db_user = $_SESSION['upgrade_db_admin_username'];
        $db_pass = $_SESSION['upgrade_db_admin_password'];

        $link = @mysql_connect( $db_name, $db_user, $db_pass );
        if( $link ){
            mysql_close($link);
        }
        else{
            $errors[] = "Could not instantiate db connection to database $db_name as user $db_user and the supplied password.";
        }
    }
    return $errors;
}

print_debug_comment();


$next_clicked = false;
$next_step = 0;

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
    }
}
chdir('../');
require_once('include/utils.php');
require_once('include/database/PearDatabase.php');
chdir('upgrade/');
$db =& new PearDatabase();
if(!isset($_SESSION['upgrade_to_3b'])){

	$result = $db->query("SELECT value FROM config WHERE category='info' AND name='sugar_version'");
	$row = $db->fetchByAssoc($result);
	if($row['value'] == $current_version. $current_patch){
		die("Already upgraded to current version - $current_version$current_patch.");
	}
	$result = $db->query("SHOW TABLES LIKE 'prospects'");



	$force_run = false;
	if(preg_match( "/^3\.0[$previous_patches]*/", $row['value'] ) || $db->getRowCount($result) > 0){

	   $_SESSION['upgrade_to_3b'] = true;
	}else{
		$_SESSION['upgrade_to_3b'] = false;
	}
}
$upgrade_to_3b = false;
if($_SESSION['upgrade_to_3b']){
		 $upgrade_to_3b = true;
		 $force_run = true;
		 $no_temp = true;
	   	 $php_upgrades = array('custom_fields'=>true, 'tabs'=>false);
}

// use a simple array to map out the steps of the upgrade page flow
$workflow = array(
                  'checklist.php',
                   'settings.php',
                   'copyFiles.php',
                   'runSql.php',
                   'phpCalls.php',
                   'configFile.php',
					'fin.php',
                );



if($upgrade_to_3b){
	$workflowflip = array_flip($workflow);
	unset($workflowflip['copyFiles.php']);
	unset($workflowflip['runSql.php']);
	$workflow = array_values( array_flip($workflowflip) );


}


$validation_errors = array();

// process the data posted
if($next_clicked){
    // store the submitted data because the 'Next' button was clicked
    switch($workflow[$_REQUEST['current_step']]){
        case 'checklist.php':
            $_SESSION['upgrade_checklist_submitted']    = true;
            $_SESSION['upgrade_checklist_accept']       = get_boolean_from_request( 'upgrade_checklist_accept' );
            break;
        case 'settings.php':
            $_SESSION['upgrade_settings_submitted']     = true;
            $_SESSION['upgrade_target_dir']             = $_REQUEST['upgrade_target_dir'];
            $_SESSION['upgrade_source_dir']             = $_REQUEST['upgrade_source_dir'];
            $_SESSION['upgrade_db_admin_username']      = $_REQUEST['upgrade_db_admin_username'];
            $_SESSION['upgrade_db_admin_password']      = $_REQUEST['upgrade_db_admin_password'];

            $validation_errors = validate_settings();
            if( count($validation_errors) > 0 ){
                $next_step--;
            }


            break;
    }
}

$the_file = $workflow[$next_step];

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
}

require( $the_file );
print_debug_comment();
?>
