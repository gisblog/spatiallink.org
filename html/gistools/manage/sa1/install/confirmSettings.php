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

// $Id: confirmSettings.php,v 1.8.2.2 2005/05/04 20:03:46 bob Exp $

$suicide = true;
if(isset($install_script))
{
   if($install_script)
   {
      $suicide = false;
   }
}

if($suicide)
{
   // mysterious suicide note
   die('Unable to process script directly.');
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
</head>
<body onload="javascript:document.getElementById('defaultFocus').focus();">
<form action="install.php" method="post" name="setConfig" id="form">
<input type="hidden" name="current_step" value="<?php echo $next_step ?>">
<table cellspacing="0" cellpadding="0" border="0" align="center" class="shell">
    <tr>
        <th width="400">Step <?php echo $next_step ?>: Confirm Settings</th>
        <th width="200" height="30" style="text-align: right;"><a href="http://www.sugarcrm.com" target=
            "_blank"><IMG src="include/images/sugarcrm_login.png" width="120" height="19" alt="SugarCRM" border="0"></a></th>
    </tr>
    <tr>
        <td colspan="2" width="600">
            <p>Please confirm the settings below.  If you would like to change any of the values, click "Back" to edit.
            Otherwise, click "Next" to start the installation.
            </p>

        <table width="100%" cellpadding="0" cellpadding="0" border="0" class="StyleDottedHr">

            <tr><th colspan="3" align="left">Database Settings</th></tr>
            <tr>
                <td></td>
                <td><b>Host Name</b></td>
                <td><?php print( $_SESSION['setup_db_host_name'] ); ?></td>
            </tr>
            <tr>
                <td></td>
                <td><b>Database Name</b></td>
                <td>
                    <?php
                        print( $_SESSION['setup_db_database_name'] );
                        print( " (will " );
                        if( $_SESSION['setup_db_create_database'] != 1 ){
                            print( "not " );
                        }
                        print( "be created)" );
                    ?>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><b>User Name for SugarCRM</b></td>
                <td>
                    <?php
                        print( $_SESSION['setup_db_sugarsales_user'] );
                        print( " (will " );
                        if( $_SESSION['setup_db_create_sugarsales_user'] != 1 ){
                            print( "not " );
                        }
                        print( "be created)" );
                    ?>
            </tr>
            <tr>
                <td></td>
                <td><b>Drop and re-create existing SugarCRM tables?</b></td>
                <td>
                    <?php
                        if( $_SESSION['setup_db_drop_tables'] == 1 ){
                            print( "Yes " );
                        }
                        else{
                            print( "No" );
                        }
                    ?>
            </tr>
            <tr>
                <td></td>
                <td><b>Populate database with demo data?</b></td>
                <td>
                    <?php
                        if( $_SESSION['setup_db_pop_demo_data'] == 1 ){
                            print( "Yes " );
                        }
                        else{
                            print( "No" );
                        }
                    ?>
            </tr>
            <tr>
                <td></td>
                <td><b>Priveleged Database User Name</b></td>
                <td>
                    <?php
                        print( $_SESSION['setup_db_admin_user_name'] );
                    ?>
            </tr>

            <tr><th colspan="3" align="left">Site Configuration</th></tr>
            <tr>
                <td></td>
                <td><b>URL</b></td>
                <td>
                    <?php
                        print( $_SESSION['setup_site_url'] );
                    ?>
            </tr>

            <tr><th colspan="3" align="left">Advanced Site Security</th></tr>
            <tr>
                <td></td>
                <td><b>Use a Custom Session Directory for SugarCRM?</b></td>
                <td>
                    <?php
                        if( $_SESSION['setup_site_custom_session_path'] == 1 ){
                            print( "Yes ( " . $_SESSION['setup_site_session_path'] . " )" );
                        }
                        else{
                            print( "No" );
                        }
                    ?>
            </tr>
            <tr>
                <td></td>
                <td><b>Use a Custom Log Directory for SugarCRM?</b></td>
                <td>
                    <?php
                        if( $_SESSION['setup_site_custom_log_dir'] == 1 ){
                            print( "Yes ( " . $_SESSION['setup_site_log_dir'] . " )" );
                        }
                        else{
                            print( "No" );
                        }
                    ?>
            </tr>
            <tr>
                <td></td>
                <td><b>Own Application ID Provided?</b></td>
                <td>
                    <?php
                        if( $_SESSION['setup_site_specify_guid'] == 1 ){
                            print( "Yes ( " . $_SESSION['setup_site_guid'] . " )" );
                        }
                        else{
                            print( "No" );
                        }
                    ?>
            </tr>

<!--




















-->
        </table>
        </td>
    </tr>
    <tr>
        <td align="right" colspan="2">
        <hr>
        <table cellspacing="0" cellpadding="0" border="0" class="stdTable">
            <tr>
                <td><input class="button" type="button" onclick="window.open('http://www.sugarcrm.com/forums/');" value="Help" /></td>
                <td>
                    <input class="button" type="button" name="goto" value="Back" onclick="document.getElementById('form').submit();" />
                    <input type="hidden" name="goto" value="Back" />
                </td>
                <td><input class="button" type="submit" name="goto" value="Next" id="defaultFocus"/></td>
            </tr>
        </table>
        </td>
    </tr>
</table>
</form>
<br>
</body>
</html>
