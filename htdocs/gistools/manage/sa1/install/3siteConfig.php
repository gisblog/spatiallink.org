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

// $Id: 3siteConfig.php,v 1.44.2.2 2005/05/04 20:03:46 bob Exp $

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

if( isset($_SESSION['siteConfig_submitted']) && $_SESSION['siteConfig_submitted'] ){
   // restore the values submitted by the user from the session
   $setup_site_url                      = $_SESSION['setup_site_url'];
   $setup_site_cache_path               = $_SESSION['setup_site_cache_path'];
   $setup_site_defaults                 = $_SESSION['setup_site_defaults'];
   $setup_site_custom_session_path      = $_SESSION['setup_site_custom_session_path'];
   $setup_site_session_path             = $_SESSION['setup_site_session_path'];
   $setup_site_custom_log_dir           = $_SESSION['setup_site_custom_log_dir'];
   $setup_site_log_dir                  = $_SESSION['setup_site_log_dir'];
   $setup_site_specify_guid             = $_SESSION['setup_site_specify_guid'];
   $setup_site_guid                     = $_SESSION['setup_site_guid'];
   $setup_site_admin_password           = $_SESSION['setup_site_admin_password'];
   $setup_site_admin_password_retype    = $_SESSION['setup_site_admin_password_retype'];
}
else {
    $web_root = $_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];
    $web_root = str_replace("/install.php", "", $web_root);
    $web_root = "http://$web_root";
    $current_dir = str_replace('\install',"", dirname(__FILE__));
    $current_dir = str_replace('/install',"", $current_dir);
    $current_dir = trim($current_dir);

	if( is_readable('config.php') ){
        include_once('config.php');
	}

	// check for old config format.
	if(empty($sugar_config) && isset($dbconfig['db_host_name']))
	{
   	make_sugar_config($sugar_config);
	}

   // set the form's php var to the loaded config's var else default to sane settings

   if(empty($sugar_config['site_url']))
   {
      $setup_site_url = $web_root;
   }
	else
   {
      if($sugar_config['site_url']== '')
      {
         $setup_site_url = $web_root;
      }
      else
      {
         $setup_site_url = $sugar_config['site_url'];
      }
   }

   $_SESSION['$setup_site_url'] = $setup_site_url;

   if(isset($cache_dir))
   {
      $setup_site_cache_path = $cache_dir;
   }
   else
   {
      $setup_site_cache_path = $current_dir . '/cache';
   }
   $_SESSION['setup_site_cache_path'] = $setup_site_cache_path;

   $setup_site_defaults = true;
   $_SESSION['setup_site_defaults'] = $setup_site_defaults;

    $setup_site_custom_session_path = false;
    $_SESSION['setup_site_custom_session_path'] = $setup_site_custom_session_path;

    if( isset($sugar_config['session_dir']) ){
        $setup_site_session_path = $sugar_config['session_dir'];
    }
    else {
        $setup_site_session_path = '';
    }
    $_SESSION['setup_site_session_path'] = $setup_site_session_path;

    $setup_site_custom_log_dir = false;
    $_SESSION['setup_site_custom_log_dir'] = $setup_site_custom_log_dir;

    if( isset($sugar_config['log_dir']) ){
        $setup_site_log_dir = $sugar_config['log_dir'];
    }
    else {
        $setup_site_log_dir = '.';
    }
    $_SESSION['setup_site_log_dir'] = $setup_site_log_dir;

    $setup_site_specify_guid = false;
    $_SESSION['setup_site_specify_guid'] = $setup_site_specify_guid;

    if( isset($sugar_config['unique_key']) ){
        $setup_site_guid = $sugar_config['unique_key'];
    }
    else {
        $setup_site_guid = '';
    }
    $_SESSION['setup_site_guid'] = $setup_site_guid;

    $setup_site_admin_password = '';
    $setup_site_admin_password_retype = '';
}

// should this be moved to install.php?
if (is_file("config.php"))
{
   require_once("config.php");

	if(!empty($sugar_config['default_theme']))
      $_SESSION['site_default_theme'] = $sugar_config['default_theme'];

	if(!empty($sugar_config['disable_persistent_connections']))
		$_SESSION['disable_persistent_connections'] =
		$sugar_config['disable_persistent_connections'];
	if(!empty($sugar_config['default_language']))
		$_SESSION['default_language'] = $sugar_config['default_language'];
	if(!empty($sugar_config['translation_string_prefix']))
		$_SESSION['translation_string_prefix'] = $sugar_config['translation_string_prefix'];
	if(!empty($sugar_config['default_charset']))
		$_SESSION['default_charset'] = $sugar_config['default_charset'];

	if(!empty($sugar_config['rss_cache_time']))
		$_SESSION['rss_cache_time'] = $sugar_config['rss_cache_time'];
	if(!empty($sugar_config['languages']))
	{
		// We need to encode the languages in a way that can be retrieved later.
		$language_keys = Array();
		$language_values = Array();

		foreach($sugar_config['languages'] as $key=>$value)
		{
			$language_keys[] = $key;
			$language_values[] = $value;
		}

		$_SESSION['language_keys'] = urlencode(implode(",",$language_keys));
		$_SESSION['language_values'] = urlencode(implode(",",$language_values));
	}
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
   <script type="text/javascript" src="install/3siteConfig.js"></script>
</head>
<body onload="javascript:toggleGUID();toggleSession();toggleSiteDefaults();document.getElementById('defaultFocus').focus();">
<form action="install.php" method="post" name="setConfig" id="form">
<input type="hidden" name="current_step" value="<?php echo $next_step ?>">
<table cellspacing="0" cellpadding="0" border="0" align="center" class="shell">
<tr>
   <th width="400">Step <?php echo $next_step ?>: Site Configuration</th>
   <th width="200" height="30" style="text-align: right;"><a href="http://www.sugarcrm.com" target=
      "_blank"><IMG src="include/images/sugarcrm_login.png" width="120" height="19" alt="SugarCRM" border="0"></a></th>
   </tr>
<tr>
   <td colspan="2" width="600">
   <p>Please enter your site configuration information below.
      If you are unsure of the fields, we suggest that you use the default
      values.</p>
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
   <tr><th colspan="3" align="left">Site Configuration</td></tr>
   <tr><td><span class="required">*</span></td>
       <td><b>URL</td>
       <td align="left"><input type="text" name="setup_site_url" id="defaultFocus"
			value="<?php echo $setup_site_url; ?>" size="40" /></td></tr>
   <tr><td><span class="required">*</span></td>
       <td><b>SugarCRM <em>admin</em> password</b><br><i>Caution: This will override the admin password
				of any previous installation.</i></td>
       <td align="left"><input type="password" name="setup_site_admin_password"
                         value="<?php echo $setup_site_admin_password; ?>" size="20" /></td></tr>
   <tr><td><span class="required">*</span></td>
       <td><b>Re-type SugarCRM <em>admin</em> password</td>
       <td align="left"><input type="password" name="setup_site_admin_password_retype"
                        value="<?php echo $setup_site_admin_password_retype; ?>" size="20" /></td></tr>
   <tr><th colspan="3" align="left">Advanced Site Security</td></tr>
   <tr><td></td>
       <td><b>Use defaults?</b></td>
       <td><input type="checkbox" class="checkbox" name="setup_site_defaults" value="yes"
                  onclick="javascript:toggleSiteDefaults();"
                  <?php if($setup_site_defaults) echo 'checked="checked"'; ?> /></td></tr>

   <tbody id="setup_site_session_section_pre">
   <tr><td></td>
       <td><b>Use a Custom Session Directory for SugarCRM</b><br>
				<em>Provide a secure folder for storing SugarCRM session information
					to prevent session data from being vulnerable on shared servers.</em></td>
       <td><input type="checkbox" class="checkbox" name="setup_site_custom_session_path" value="yes"
                  onclick="javascript:toggleSession();"
                  <?php if($setup_site_custom_session_path) echo 'checked="checked"'; ?> /></td></tr>
   </tbody>
   <tbody id="setup_site_session_section">
   <tr><td><span class="required">*</span></td>
       <td><b>Path to Session Directory<br>(must be writable)</td>
       <td align="left"><input type="text" name="setup_site_session_path" size='40'
                        value="<?php echo $setup_site_session_path; ?>" /></td></tr>
   </tbody>

   <tbody id="setup_site_log_dir_pre">
   <tr><td></td>
       <td><b>Use a Custom Log Directory</b><br>
                <em>Override the default directory where the SugarCRM log resides.  No matter where
                    the log file resides, access to it via browser will be restricted via an .htaccess
                    redirect.</em></td>

       <td><input type="checkbox" class="checkbox" name="setup_site_custom_log_dir" value="yes"
                  onclick="javascript:toggleLogDir();"
                  <?php if($setup_site_custom_log_dir) echo 'checked="checked"'; ?> /></td></tr>
   </tbody>
   <tbody id="setup_site_log_dir">
   <tr><td><span class="required">*</span></td>
       <td><b>Log Directory</td>
       <td align="left"><input type="text" name="setup_site_log_dir" size='30'
                        value="<?php echo $setup_site_log_dir; ?>" /></td></tr>
   </tbody>

   <tbody id="setup_site_guid_section_pre">
   <tr><td></td>
       <td><b>Provide Your Own Application ID</b><br>
				<em>Override the auto-generated
				application ID that prevents sessions
				from one instance of SugarCRM from being
				used on another instance.  If you have
				a cluster of SugarCRM installations,
				they all must share the same application
				ID.</em></td>

       <td><input type="checkbox" class="checkbox" name="setup_site_specify_guid" value="yes"
                  onclick="javascript:toggleGUID();"
                  <?php if($setup_site_specify_guid) echo 'checked="checked"'; ?> /></td></tr>
   </tbody>
   <tbody id="setup_site_guid_section">
   <tr><td><span class="required">*</span></td>
       <td><b>Application ID</td>
       <td align="left"><input type="text" name="setup_site_guid" size='30'
                        value="<?php echo $setup_site_guid; ?>" /></td></tr>
   </tbody>
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
