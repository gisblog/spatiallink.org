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

// $Id: 2dbConfig.php,v 1.51.2.2 2005/05/04 20:03:46 bob Exp $

$suicide = true;
if( isset($install_script) ){
    if( $install_script ){
        $suicide = false;
    }
}

if( $suicide ){
    // mysterious suicide note
    die('Unable to process script directly.');
}

   $web_root = $_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];
   $web_root = str_replace("/install.php", "", $web_root);
   $web_root = "http://$web_root";
   $current_dir = str_replace('\install', "", dirname(__FILE__));
   $current_dir = str_replace('/install', "", $current_dir);
   $current_dir = trim($current_dir);
   $cache_dir = "cache/";
   $setup_db_conn_test_result = -1;
    
if(isset($_SESSION['dbConfig_submitted']) && $_SESSION['dbConfig_submitted'])
{
    // restore the values submitted by the user from the session
    $setup_db_host_name                     = $_SESSION['setup_db_host_name'];
    $setup_db_database_name                 = $_SESSION['setup_db_database_name'];
    $setup_db_create_database               = $_SESSION['setup_db_create_database'];
    $setup_db_drop_tables                   = $_SESSION['setup_db_drop_tables'];
    $setup_db_pop_demo_data                 = $_SESSION['setup_db_pop_demo_data'];
    $setup_db_username_is_privileged        = $_SESSION['setup_db_username_is_privileged'];
    $setup_db_admin_user_name               = $_SESSION['setup_db_admin_user_name'];
    $setup_db_admin_password                = $_SESSION['setup_db_admin_password'];
    $setup_db_create_sugarsales_user        = $_SESSION['setup_db_create_sugarsales_user'];
    $setup_db_sugarsales_user               = $_SESSION['setup_db_sugarsales_user'];
    $setup_db_sugarsales_password           = $_SESSION['setup_db_sugarsales_password'];
    $setup_db_sugarsales_password_retype    = $_SESSION['setup_db_sugarsales_password_retype'];
}
else
{
	if(is_readable('config.php'))
	{
   	include_once('config.php');
	}

	// check for old config format.
	if(empty($sugar_config) && isset($dbconfig['db_host_name']))
	{
   	make_sugar_config($sugar_config);
	}

   // set the form's php var to the loaded config's var else default to sane settings

   $setup_db_host_name = empty($sugar_config['dbconfig']['db_host_name'])
      ? 'localhost' : $sugar_config['dbconfig']['db_host_name'];
   $_SESSION['setup_db_host_name'] = $setup_db_host_name;

   $setup_db_database_name = empty($sugar_config['dbconfig']['db_name'])
      ? 'sugarcrm' : $sugar_config['dbconfig']['db_name'];
   $_SESSION['setup_db_database_name'] = $setup_db_database_name;

   $setup_db_create_database = false;
   $_SESSION['setup_db_create_database'] = $setup_db_create_database;

   $setup_db_drop_tables = false;
   $_SESSION['setup_db_drop_tables'] = $setup_db_drop_tables;

   $setup_db_pop_demo_data = false;
   $_SESSION['setup_db_pop_demo_data'] = $setup_db_pop_demo_data;

   $setup_db_username_is_privileged = true;
   $_SESSION['setup_db_username_is_privileged'] = $setup_db_username_is_privileged;

   $setup_db_admin_user_name = 'root';
   $_SESSION['setup_db_admin_user_name'] = $setup_db_admin_user_name;

   $setup_db_admin_password = '';
   $_SESSION['setup_db_admin_password'] = $setup_db_admin_password;

   $setup_db_create_sugarsales_user = false;
   $_SESSION['setup_db_create_sugarsales_user'] = $setup_db_create_sugarsales_user;

   $setup_db_sugarsales_user = empty($sugar_config['dbconfig']['db_user_name'])
      ? 'sugarcrm' : $sugar_config['dbconfig']['db_user_name'];
   $_SESSION['setup_db_sugarsales_user'] = $setup_db_sugarsales_user;

   $setup_db_sugarsales_password = empty($sugar_config['dbconfig']['db_password'])
      ? '' : $sugar_config['dbconfig']['db_password'];
   $_SESSION['setup_db_sugarsales_password'] = $setup_db_sugarsales_password;

   $setup_db_sugarsales_password_retype = empty($sugar_config['dbconfig']['db_password'])
      ? '' : $sugar_config['dbconfig']['db_password'];
   $_SESSION['setup_db_sugarsales_password_retype'] = $setup_db_sugarsales_password_retype;
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <meta http-equiv="Content-Script-Type" content="text/javascript">
   <meta http-equiv="Content-Style-Type" content="text/css">
   <title>SugarCRM Setup Wizard: Step <?php echo $next_step ?></title>
   <link rel="stylesheet" href="install/install.css" type="text/css" />
   <script type="text/javascript" src="install/installCommon.js"></script>
   <script type="text/javascript" src="install/2dbConfig.js"></script>
</head>
<body onload="toggleDropTables();togglePasswordRetypeRequired();toggleUsernameIsPrivileged();document.getElementById('defaultFocus').focus();">
<form action="install.php" method="post" name="setConfig" id="form">
<table cellspacing="0" cellpadding="0" border="0" align="center" class="shell">
<tr>
    <th width="400">Step <?php echo $next_step ?>: Database Configuration</th>
	<th width="200" height="30" style="text-align: right;"><a href="http://www.sugarcrm.com" target=
      "_blank"><IMG src="include/images/sugarcrm_login.png" width="120" height="19" alt="SugarCRM" border="0"></a></th>
</tr>
<tr>
   <td colspan="2" width="600">	
      <p>Please enter your database configuration information below.
      If you are unsure of what to fill in, we suggest that you use the
      default values.</p>

   <?php
      if(isset($validation_errors))
      {
         if(count($validation_errors) > 0)
         {
            echo '<div id="errorMsgs">';
            echo '<p>Please fix the following errors before proceeding:</p>';
            echo '<ul>';

            foreach($validation_errors as $error)
            {
               echo '<li>' . $error . '</li>';
            }

            echo '</ul>';
            echo '</div>';
         }
      }
$_SESSION['setup_db_create_database'] = $setup_db_create_database;
   ?>
<div class="required">* Required field</div>
<table width="100%" cellpadding="0" cellpadding="0" border="0" class="StyleDottedHr">
<tr><th colspan="3" align="left">Database Configuration</td></tr>
<tr>
    <td><span class="required">*</span></td>
    <td nowrap><b>Host Name</b></td>
    <td align="left"><input type="text" name="setup_db_host_name" id="defaultFocus"
        value="<?php echo $setup_db_host_name; ?>" /></td></tr>
<tr><td><span class="required">*</span></td>
    <td nowrap><b>Database Name <?php if( $_SESSION['setup_db_type'] == 'oci8' ){ print( "(SID from tnsnames.ora)" ); } ?></b></td>
    <td align="left">
        <input type="text" name="setup_db_database_name"
            maxlength="64"
            value="<?php echo $setup_db_database_name; ?>" />
        <input type="checkbox"
            class="checkbox" name="setup_db_create_database"
            onclick="toggleDropTables();" value="yes"
            <?php if($setup_db_create_database) echo 'checked="checked"'; ?>/>
        <b>Create database</b>
    </td>
</tr>
<tr>
    <td><span class="required">*</span></td>
    <td nowrap><b>User Name for SugarCRM</b></td>
    <td align="left">
        <input type="text" name="setup_db_sugarsales_user"
            maxlength="16"
            value="<?php echo $setup_db_sugarsales_user; ?>" />
        <input type="checkbox"
            class="checkbox" name="setup_db_create_sugarsales_user"
            onclick="togglePasswordRetypeRequired();" value="yes"
            <?php if($setup_db_create_sugarsales_user) echo 'checked="checked"'; ?> />
        <b>Create user</b>
    </td>
</tr>
<tr><td></td>
    <td nowrap><b>Password for SugarCRM</b></td>
    <td align="left"><input type="password" name="setup_db_sugarsales_password"
                            value="<?php echo $setup_db_sugarsales_password; ?>" /></td></tr>
<tbody id="password_retype_required">
<tr><td></td>
    <td nowrap><b>Re-Type Password for SugarCRM</b></td>
    <td align="left"><input type="password" name="setup_db_sugarsales_password_retype"
                            value="<?php echo $setup_db_sugarsales_password_retype; ?>" /></td></tr>
</tbody>
<tr><td></td>
    <td nowrap><b>Drop and re-create existing SugarCRM tables?</b><br><i>Caution: All SugarCRM data will be erased<br>if this box is checked.</i></td>
    <td align="left"><input type="checkbox" class="checkbox" name="setup_db_drop_tables" value="yes"
        <?php if($setup_db_drop_tables) echo 'checked="checked"'; ?> /></td></tr>
<tr><td></td>
    <td nowrap><b>Populate database with demo data?</b></td>
    <td align="left"><input type="checkbox" class="checkbox" name="setup_db_pop_demo_data" value="yes"
                    <?php if($setup_db_pop_demo_data) echo 'checked="checked"'; ?> /></td></tr>

<tr><td></td>
    <td nowrap><b>Database account above is a privileged user?</b></td>
    <td align="left"><input type="checkbox" class="checkbox" name="setup_db_username_is_privileged" value="yes"
                            onclick="toggleUsernameIsPrivileged();"
                    <?php if($setup_db_username_is_privileged) echo 'checked="checked"'; ?> /></td></tr>
<tbody id="privileged_user_info">
<tr><td><span class="required">*</span></td>
    <td width="50%"><b>Privileged Database User Name</b><br>
<em>This privileged database user must have the proper permissions to
create a database, drop/create tables, and create a user.  This privileged
database user will only be used to perform these tasks as needed during
the installation process.  You may also use the same database user as
above if that user has sufficient privileges.</em></td>

    <td align="left"><input type="text" name="setup_db_admin_user_name"
                            value="<?php echo $setup_db_admin_user_name; ?>" /></td></tr>

<tr><td></td>
    <td nowrap><b>Privileged Database User Password</b></td>
    <td align="left"><input type="password" name="setup_db_admin_password"
                            value="<?php echo $setup_db_admin_password; ?>" /></td></tr>
</tbody>

</table>
</td>
</tr>
<tr>
<td align="right" colspan="2">
<hr>
     <input type="hidden" name="current_step" value="<?php echo $next_step ?>">
     <table cellspacing="0" cellpadding="0" border="0" class="stdTable">
       <tr>
         <td><input class="button" type="button" onclick="window.open('http://www.sugarcrm.com/forums/');" value="Help" /></td>
         <td>
            <input class="button" type="button" name="goto" value="Back" onclick="document.getElementById('form').submit();" />
            <input type="hidden" name="goto" value="Back" />
         </td>
         <td><input class="button" type="submit" name="goto" value="Next" /></td>
       </tr>
     </table>
</td>
</tr>
</table>
</form>
<br>
</body>
</html>

