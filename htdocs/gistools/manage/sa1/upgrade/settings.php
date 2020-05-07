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

// $Id: settings.php,v 1.3.2.2 2005/05/04 20:03:46 bob Exp $

if( !isset($upgrade_script) || ($upgrade_script == false) ){
    die('Unable to process script directly.');
}

if( isset($_SESSION['upgrade_settings_submitted']) && $_SESSION['upgrade_settings_submitted'] ){
    // restore the values submitted by the user from the session
    $upgrade_target_dir         = $_SESSION['upgrade_target_dir'];
    $upgrade_source_dir         = $_SESSION['upgrade_source_dir'];
    $upgrade_db_admin_username  = $_SESSION['upgrade_db_admin_username'];
    $upgrade_db_admin_password  = $_SESSION['upgrade_db_admin_password'];
}
else {
    if( is_readable( 'config.php' ) ){
        include_once('config.php');
    }

    // set the form's php var to the loaded config's var else default to sane settings
    $original_dir = getCwd();
    chdir( ".." );
    $upgrade_target_dir = getCwd();
    chdir( $original_dir );
    $upgrade_source_dir = "";
    $upgrade_db_admin_username  = "root";
    $upgrade_db_admin_password  = "";
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <meta http-equiv="Content-Script-Type" content="text/javascript">
   <meta http-equiv="Content-Style-Type" content="text/css">
   <title>SugarCRM Setup Wizard: Step <?php echo $next_step ?></title>
   <link rel="stylesheet" href="upgrade.css" type="text/css" />
</head>
<body>
<form action="index.php" method="post" name="setConfig" id="form">
<table cellspacing="0" cellpadding="0" border="0" align="center" class="shell">
<tr>
    <th width="400">Step <?php echo $next_step ?>: Upgrade Settings</th>
	<th width="200" height="30" style="text-align: right;"><a href="http://www.sugarcrm.com" target=
      "_blank"><IMG src="../include/images/sugarcrm_login.png" width="120" height="19" alt="SugarCRM" border="0"></a></th>
</tr>
<tr>
   <td colspan="2" width="600">	
      <p>Please enter the following information.</p>

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
   ?>
<div class="required">* Required field</div>
<table width="100%" cellpadding="0" cellpadding="0" border="0" class="StyleDottedHr">
    <tr><th colspan="3" align="left">Upgrade Settings</td></tr>
    <tr>
        <td><span class="required">*</span></td>
        <td nowrap> <b>Current Sugar Directory</b><br>
                    <em>This is the full path to the directory.</em></td>
        <td align="left"><input type="text" name="upgrade_target_dir" id="defaultFocus" size="50" value="<?php echo $upgrade_target_dir; ?>" /></td>
    </tr>
    <tr>
        <td><span class="required">*</span></td>
        <td nowrap><b>Sugar 3.0 Directory</b></td>
        <td align="left"><input type="text" name="upgrade_source_dir" size="50" value="<?php echo $upgrade_source_dir; ?>" /></td>
    </tr>

    <tr>
        <td><span class="required">*</span></td>
        <td width="50%"><b>Privileged Database User Name</b><br>
            <em>This privileged database user must have the proper permissions to
                create a database, drop/create tables, and create a user.  This privileged
                database user will only be used to grant extra permissions to the Sugar
                database user account.</em></td>
        <td align="left"><input type="text" name="upgrade_db_admin_username" value="<?php echo $upgrade_db_admin_username; ?>" /></td>
    </tr>
    <tr>
        <td></td>
        <td nowrap><b>Privileged Database User Password</b></td>
        <td align="left"><input type="password" name="upgrade_db_admin_password" value="<?php echo $upgrade_db_admin_password; ?>" /></td>
    </tr>
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
</body>
</html>
