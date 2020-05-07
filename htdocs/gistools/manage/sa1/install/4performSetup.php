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

// $Id: 4performSetup.php,v 1.107.2.2 2005/05/04 20:03:46 bob Exp $

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

set_time_limit(90);

   $dictionary = array();
   require_once('modules/TableDictionary.php');
   $rel_dictionary = $dictionary;
// This file will load the configuration settings from session data,
// write to the config file, and execute any necessary database steps.

   require_once('include/utils.php');

   $setup_db_host_name = $_SESSION['setup_db_host_name'];
   $setup_db_sugarsales_user = $_SESSION['setup_db_sugarsales_user'];
   $setup_db_sugarsales_password = $_SESSION['setup_db_sugarsales_password'];
   $setup_db_database_name = $_SESSION['setup_db_database_name'];
   $setup_db_drop_tables = $_SESSION['setup_db_drop_tables'];
   $setup_db_create_database = $_SESSION['setup_db_create_database'];
   $setup_db_pop_demo_data = $_SESSION['setup_db_pop_demo_data'];
   $setup_site_url = $_SESSION['setup_site_url'];
   $setup_site_admin_password = $_SESSION['setup_site_admin_password'];
   $setup_db_create_sugarsales_user = $_SESSION['setup_db_create_sugarsales_user'];
   $setup_db_admin_user_name = $_SESSION['setup_db_admin_user_name'];
   $setup_db_admin_password = $_SESSION['setup_db_admin_password'];
	$parsed_url = parse_url($setup_site_url);
	$setup_site_host_name = $parsed_url['host'];

    if( $_SESSION['setup_site_custom_session_path'] ){
        $setup_site_session_path = $_SESSION['setup_site_session_path'];
    }
    else {
        $setup_site_session_path = '';
    }

    if( $_SESSION['setup_site_custom_log_dir'] ){
        $setup_site_log_dir = $_SESSION['setup_site_log_dir'];
    }
    else {
        $setup_site_log_dir = '.';
    }
    $setup_site_log_file = 'sugarcrm.log';  // may be an option later

    if( $_SESSION['setup_site_specify_guid'] ){
        $setup_site_guid = $_SESSION['setup_site_guid'];
    }
    else {
        $setup_site_guid = md5( create_guid() );
    }

   $cache_dir = 'cache/';

   // flush after each output so the user can see the progress in real-time
   ob_implicit_flush();

   $bottle = array();
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
</head>
<body onload="javascript:document.getElementById('defaultFocus').focus();">
<table cellspacing="0" cellpadding="0" border="0" align="center" class="shell" style="height=15px;margin-bottom=0px">
<tr>
   <th width="400">Step <?php echo $next_step ?>: Perform Setup</th>
   <th width="200" height="30" style="text-align: right;"><a href="http://www.sugarcrm.com" target=
      "_blank"><IMG src="include/images/sugarcrm_login.png" width="120" height="19" alt="SugarCRM" border="0"></a></th>
</tr>
<tr>
   <td colspan="2" width="600">

<?php
    echo 'Creating the config file...';
    if( is_file('config.php') ){
        $is_writable = is_writable('config.php');
        // require is needed here (config.php is sometimes require'd from install.php)
        require('config.php');
    }
    else {
        $is_writable = is_writable('.');
    }

    $sugar_config = sugar_config_union( get_sugar_config_defaults(), $sugar_config );

    // always lock the installer
    $sugar_config['installer_locked'] = true;

    // we're setting these since the user was given a fair chance to change them
    $sugar_config['dbconfig']['db_host_name']  = addslashes($setup_db_host_name);
    $sugar_config['dbconfig']['db_user_name']  = addslashes($setup_db_sugarsales_user);
    $sugar_config['dbconfig']['db_password']   = addslashes($setup_db_sugarsales_password);
    $sugar_config['dbconfig']['db_name']       = addslashes($setup_db_database_name);
    $sugar_config['dbconfig']['db_type']       = $_SESSION['setup_db_type'];

    $sugar_config['cache_dir']      = $cache_dir;
    $sugar_config['host_name']      = $setup_site_host_name;
    $sugar_config['import_dir']     = $cache_dir . 'import/';
    $sugar_config['session_dir']    = $setup_site_session_path;
    $sugar_config['log_dir']        = $setup_site_log_dir;
    $sugar_config['log_file']       = $setup_site_log_file;
    $sugar_config['site_url']       = $setup_site_url;
    $sugar_config['sugar_version']  = $setup_sugar_version;
    $sugar_config['tmp_dir']        = $cache_dir . 'xml/';
    if( !isset( $sugar_config['unique_key'] ) ){
        $sugar_config['unique_key']     = $setup_site_guid;
    }
    $sugar_config['upload_dir']     = $cache_dir . 'upload/';

ksort($sugar_config);

$sugar_config_string = "<?php\n" .
	'// created: ' . date('Y-m-d H:i:s') . "\n" .
	'$sugar_config = ' .
	var_export($sugar_config, true) .
	";\n?>\n";

if ($is_writable && ($config_file = @ fopen("config.php", "w"))) {
	fputs($config_file, $sugar_config_string, strlen($sugar_config_string));
	fclose($config_file);
	echo 'done<br>';
}
else {
	echo 'failed<br>';
	echo "<p>Cannot write to the <span class=stop>config.php</span> file.</p>\n";
	echo "<p>You can continue this installation by manually creating the config.php file and pasting the configuration information below into the config.php file.  However, you <strong>must </strong>create the config.php file before you continue to the next step.  </p>\n";
	echo  "<TEXTAREA  rows=\"15\" cols=\"80\">".$sugar_config_string."</TEXTAREA>";
	echo "<p>Did you remember to create the config.php file?</p>";

	$bottle[] = "Warning: Could not write to config.php file.  Please ensure it exists.";
}

if( is_writable("log4php.properties") && ($fh = @ fopen("log4php.properties", "r+")) ){
    $props      = fread( $fh, filesize("log4php.properties") );

    $props = preg_replace( '/(log4php.appender.A2.File=).*\n/', "$1" . $setup_site_log_dir . "/" . $setup_site_log_file . "\n", $props );

    rewind( $fh );
    fwrite( $fh, $props );
    ftruncate( $fh, ftell($fh) );
    fclose( $fh );
}

// (re)write the .htaccess file to prevent browser access to the log file
$htaccess_file  = $setup_site_log_dir . "/.htaccess";
$site_path      = $parsed_url['path'];
$redirect_str   = "Redirect $site_path/$setup_site_log_dir/$setup_site_log_file $setup_site_url/log_file_restricted.html\n";
$redirect_str   = preg_replace( "#/./#", "/", $redirect_str );
if( file_exists( $htaccess_file ) ){
    if( is_writable( $htaccess_file ) && ($fh = @ fopen( $htaccess_file, "r+" )) ){
        $props  = fread( $fh, filesize( $htaccess_file ) );

        if( !preg_match( "#" . $redirect_str . "#", $props ) ){
            // need to add redirect
            $props .= $redirect_str;
        }

        rewind( $fh );
        fwrite( $fh, $props );
        ftruncate( $fh, ftell($fh) );
        fclose( $fh );
    }
}
else{
    // create the file
    if( $fh = @ fopen( $htaccess_file, "w") ){
        fputs( $fh, $redirect_str, strlen($redirect_str) );
        fclose( $fh );
    }
    else {
        echo "<p>Cannot write to the <span class=stop>$htaccess_file</span> file.</p>\n";
        echo "<p>If you want to secure your log file from being accessible via browser, create an .htaccess file in your log directory with the line:</p>\n";
        echo "$redirect_str";
    }
}

print( "<p>Installation is proceeding... the output will appear below once it is complete.  Please wait.</p>" );

$user_agent = strtolower( $_SERVER['HTTP_USER_AGENT']);
if( strstr( $user_agent, "msie" ) ){
?>
   </td>
</tr>
</table>

<table cellspacing="0" cellpadding="0" border="0" align="center" class="shell" style="margin-top=0px">
<tr>
   <td colspan="2" width="600">
<?php
} // end of msie test

// TABLE STUFF **************************

   // create the SugarCRM database
   if($setup_db_create_database)
   {
      echo "Creating the database $setup_db_database_name on $setup_db_host_name...";
      $link = @mysql_connect($setup_db_host_name, $setup_db_admin_user_name,
                             $setup_db_admin_password);
      $query = 'create database `' . $setup_db_database_name . '`';
      @mysql_unbuffered_query($query, $link);
      mysql_close($link);
      echo 'done<br>';
   }

   // create the SugarCRM database user
   if($setup_db_create_sugarsales_user)
   {

      echo 'Creating the db username and password...';
      $link = @mysql_connect($setup_db_host_name, $setup_db_admin_user_name,
                             $setup_db_admin_password);
      $query = <<< HEREDOC_END
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, ALTER, DROP, INDEX
ON `$setup_db_database_name`.*
TO "$setup_db_sugarsales_user"@"$setup_site_host_name"
IDENTIFIED BY '$setup_db_sugarsales_password';
HEREDOC_END;

      if(!@mysql_unbuffered_query($query, $link))
      {
         $errno = mysql_errno();
         $error = mysql_error();
      }

      $query = <<< HEREDOC_END
set password for "$setup_db_sugarsales_user"@"$setup_site_host_name"
 = old_password('$setup_db_sugarsales_password');
HEREDOC_END;
      if(!@mysql_unbuffered_query($query, $link))
      {
         $errno = mysql_errno();
         $error = mysql_error();
      }

		if($setup_site_host_name != 'localhost')
		{
      	echo 'Creating the db username and password for localhost...';
      	$link = @mysql_connect($setup_db_host_name, $setup_db_admin_user_name,
                             	$setup_db_admin_password);
      	$query = <<< HEREDOC_END
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, ALTER, DROP, INDEX
ON `$setup_db_database_name`.*
TO "$setup_db_sugarsales_user"@"localhost"
IDENTIFIED BY '$setup_db_sugarsales_password';
HEREDOC_END;

      	if(!@mysql_unbuffered_query($query, $link))
      	{
         	$errno = mysql_errno();
         	$error = mysql_error();
      	}

      	$query = <<< HEREDOC_END
set password for "$setup_db_sugarsales_user"@"localhost"
 = old_password('$setup_db_sugarsales_password');
HEREDOC_END;
      	if(!@mysql_unbuffered_query($query, $link))
      	{
         	$errno = mysql_errno();
         	$error = mysql_error();
      	}
		}

      mysql_close($link);
      echo 'done<br>';
   }

   $new_tables = 0;
   $new_config = 0;
   $new_report = 0;

   require_once('include/logging.php');
   require_once('data/Tracker.php');
   require_once('include/utils.php');
   require_once('include/modules.php');

   global $beanFiles;
   foreach ($beanFiles as $bean => $file)
   {
      require_once($file);
   }

    // load up the config_override.php file.  This is used to provide default user settings
    if( is_file("config_override.php") ){
        require_once("config_override.php");
    }

   
   include_once ('include/database/DBManagerFactory.php');
   $db = DBManagerFactory::getInstance();
   

    $log = & LoggerManager::getLogger('create_table');

    //Drop old tables if table exists and told to drop it
    function drop_table_install( &$focus ){
        global $log, $db;

        $result = $db->tableExists($focus->table_name);

        if( $result ){
            $focus->drop_tables();
            $log->info("Dropped old ".$focus->table_name." table.");
            return 1;
        }
        else {
            $log->info("Did not need to drop old ".$focus->table_name." table.  It doesn't exist.");
            return 0;
        }
    }

    // Creating new tables if they don't exist.
		function create_table_if_not_exist( &$focus )
		{
			global $log, $db;
			$table_created = false;

			// normal code follows
			$result = $db->tableExists($focus->table_name);
			if( $result )
			{
				$log->info("Table ".$focus->table_name." already exists.");
			}
			else
			{
				$focus->create_tables();
				$log->info("Created ".$focus->table_name." table.");
				$table_created = true;
			}

			return $table_created;
		}


   function create_default_users()
   {
      global $log, $db;
      global $setup_site_admin_password;
      global $create_default_user;
      global $sugar_config;

      //Create default admin user
      $user = new User();
      $user->id = 1;
      $user->new_with_id = true;
      $user->last_name = 'Administrator';
      $user->user_name = 'admin';
      $user->title = "Administrator";
      $user->status = 'Active';
      $user->is_admin = 'on';
      $user->user_password = $user->encrypt_password($setup_site_admin_password);
      $user->user_hash = strtolower(md5($setup_site_admin_password));
      $user->email = '';
     
      $user->save();

      echo 'Creating RSS Feeds';
      $feed = new Feed();
      $feed->createRSSHomePage($user->id);


      // We need to change the admin user to a fixed id of 1.
      // $query = "update users set id='1' where user_name='$user->user_name'";
      // $result = $db->query($query, true, "Error updating admin user ID: ");

      $log->info("Created ".$user->table_name." table. for user $user->id");

      if ($create_default_user)
      {
         $default_user = new User();
         $default_user->last_name = $sugar_config['default_user_name'];
         $default_user->user_name = $sugar_config['default_user_name'];
         $default_user->status = 'Active';
         if (isset($sugar_config['default_user_is_admin']) &&
				$sugar_config['default_user_is_admin'])
			{
				$default_user->is_admin = 'on';
			}
         $default_user->user_password = $default_user->encrypt_password($sugar_config['default_password']);
         $default_user->user_hash = strtolower(md5($sugar_config['default_password']));
         $default_user->save();
         $feed->createRSSHomePage($user->id);
      }
   }

   function set_admin_password($password)
   {
      global $db;
      global $log;

      $user = new User();
      $encrypted_password = $user->encrypt_password($password);
      $user_hash = strtolower(md5($password));

      $query = "update users set user_password='$encrypted_password', user_hash='$user_hash' where id='1'";
      $db->query($query);
   }

    function insert_default_settings(){
        global $log;
        global $db;
        global $setup_sugar_version;

        $db->query("INSERT INTO config (category, name, value) VALUES ('notify', 'fromaddress', 'sugar@example.com')");
        $db->query("INSERT INTO config (category, name, value) VALUES ('notify', 'fromname', 'SugarCRM')");
        $db->query("INSERT INTO config (category, name, value) VALUES ('notify', 'send_by_default', '1')");
        $db->query("INSERT INTO config (category, name, value) VALUES ('notify', 'on', '0')");
        $db->query("INSERT INTO config (category, name, value) VALUES ('mail', 'smtpserver', 'localhost')");
        $db->query("INSERT INTO config (category, name, value) VALUES ('mail', 'smtpport', '25')");
        $db->query("INSERT INTO config (category, name, value) VALUES ('mail', 'sendtype', 'sendmail')");
        $db->query("INSERT INTO config (category, name, value) VALUES ('mail', 'smtpuser', '')");
        $db->query("INSERT INTO config (category, name, value) VALUES ('mail', 'smtppass', '')");
        $db->query("INSERT INTO config (category, name, value) VALUES ('mail', 'smtpauth_req', '0')");
        $db->query("INSERT INTO config (category, name, value) VALUES ('info', 'sugar_version', '" . $setup_sugar_version . "')");
        $db->query("INSERT INTO config (category, name, value) VALUES ('MySettings', 'tab', '')");
        $db->query("INSERT INTO config (category, name, value) VALUES ('portal', 'on', '0')");







    }
















   // start of the table creation stuff

   $startTime = microtime();

   $focus = 0;

	// for keeping track of the tables we have worked on
	$processed_tables = array();

   foreach ($beanFiles as $bean => $file)
   {
      $focus = new $bean();
		$table_name = $focus->table_name;

		// check to see if we have already setup this table
		if(!in_array($table_name, $processed_tables))
		{
			// table has not been setup...we will do it now and remember that
			$processed_tables[] = $table_name;

			$focus->db->database =& $db->database; // set db connection so we do not need to reconnect

			global $setup_db_admin_user_name;
			global $setup_db_admin_password;

			$focus->db->setUserName($setup_db_admin_user_name);
			$focus->db->setUserPassword($setup_db_admin_password);

			if ($setup_db_drop_tables)
			{
				echo "Dropping table ". $focus->table_name ." if exists...";
				$existed = drop_table_install($focus);

				if ($existed)
				{
					echo 'done<br>';
				}
				else
				{
					echo 'skipped (not found)<br>';
				}
			}

			echo "Creating the table ". $focus->table_name."...";

			if(create_table_if_not_exist($focus))
			{
				echo 'done<br>';
				if ($bean == "User")
				{
					$new_tables = 1;
				}
				if ($bean == "Administration" )
				{
					$new_config = 1;
				}






			}
			else
			{
				echo "skipped (already exists)<br>\n";
			}
		}
   }



   echo '<b>Creating relationship tables';
  	
   foreach ($rel_dictionary as $rel_name => $rel_data)
   {  
      if ($setup_db_drop_tables)
      {
        // echo "Dropping table ". $rel_name ." if exists...";

         if ($db->tableExists($rel_name))
         {
         	$db->dropTableName($rel_name);
            //echo 'done<br>';
         }
         else
         {
           // echo 'skipped (not found)<br>';
         }
      }

      //echo "Creating the table ". $rel_name."...";

      if(!$db->tableExists($rel_name))
      {
      	$db->createTableParams($rel_name, $rel_data['fields'], $rel_data['indices']);
         //echo 'done<br>';
      }
      else
      {
         //echo "skipped (already exists)<br>\n";
      }
      echo '.';
      flush();
   }

   echo '</b>done<br>';

   require_once('modules/Versions/InstallDefaultVersions.php');
   if ($new_config)
   {
      echo '<b>Inserting default settings...</b>';
      insert_default_settings();
      echo 'done<br>';
   }







   if ($new_tables)
   {
      echo '<b>Creating default users...</b>';
      create_default_users();
      echo 'done<br>';
   }
   else
   {
      echo "<b>Setting site admin password...</b>";
      $db->setUserName($setup_db_sugarsales_user);
      $db->setUserPassword($setup_db_sugarsales_password);
      set_admin_password($setup_site_admin_password);
      echo 'done<br>';
   }
  










    // populating the db with seed data
    if( $setup_db_pop_demo_data ){
        set_time_limit( 301 );
        echo '<b>Populating the database tables with demo data (this may take a little while)...</b>';
        $current_user = new User();
        $current_user->retrieve(1);
        include("install/populateSeedData.php");
        echo 'done<br>';
    }

	echo 'Making config file not writable to secure the system...';
	if( make_not_writable('./config.php') ){
        echo 'failed<br>';
        $temp_message = "Please make your config.php file not writable for security purposes.";
        echo '<p><b>' . $temp_message . '</b></p>';
        $bottle[] = "Warning: $temp_message";
    }
    else {
        echo 'done<br>';
    }

    print( "<p><b>After you finish with the installation, please ensure your config.php file has locked the installer from running again by containing this line:<BR>
<pre>
 'installer_locked' => true,
</pre>
</b></p>" );


   $endTime = microtime();
   $deltaTime = microtime_diff($startTime, $endTime);
?>
<p>The setup of SugarCRM <?php echo $setup_sugar_version; ?> is now complete.</p>
<hr>
Total time: <?php echo $deltaTime; ?> seconds.<br />
<?php
   if(function_exists('memory_get_usage'))
   {
      echo 'Approximate memory used: ' . memory_get_usage() . ' bytes.<br />';
   }
?>
<hr>

<p>Your system is now installed and configured for use.  You will need
to log in for the first time using the "admin" user name and the password
you entered during setup.</p>

<?php
   $fp = @fsockopen("www.sugarcrm.com", 80, $errno, $errstr, 3);
   if (!$fp)
   {
      echo "<p><b>We could not detected an internet connection.</b> When you do have a connection, please visit <a href=\"http://www.sugarcrm.com/home/index.php?option=com_extended_registration&task=register\">http://www.sugarcrm.com/home/index.php?option=com_extended_registration&task=register</a> to register with SugarCRM. By letting us know a little bit about how your company plans to use SugarCRM, we can ensure we are always delivering the right application for your business needs.</p>";
   }
?>
	</td>
</tr>
<tr>
<td align="right" colspan="2">
<hr>
<table cellspacing="0" cellpadding="0" border="0" class="stdTable">
<tr>
<td>

<?php
   if ($fp)
   {
      @fclose($fp);
?>
     <form action="install.php" method="post" name="form" id="form">
     <input type="hidden" name="current_step" value="<?php echo $next_step ?>">
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
     </form>
<?php
   } else {
?>
     <table cellspacing="0" cellpadding="0" border="0" class="stdTable">
       <tr>
         <td><input class="button" type="button" onclick="showHelp(4);" value="Help" /></td>
         <td>
            <form action="install.php" method="post" name="form" id="form">
                <input type="hidden" name="current_step" value="4">
                <input class="button" type="button" name="goto" value="Back" />
            </form>
         </td>
         <td>
            <form action="index.php" method="post" name="formFinish" id="formFinish">
                <input type="hidden" name="default_user_name" value="admin" />
                <input class="button" type="submit" name="next" value="Finish" id="defaultFocus"/>
            </form>
         </td>
       </tr>
     </table>
<?php
   }
?>
</td>
</tr>
</table>
</td>
</tr>
</table>
<br>
</body>
</html>

<!--
<bottle>
<?php
    if( count( $bottle ) > 0 ){
        foreach( $bottle as $bottle_message ){
            print( $bottle_message . "\n" );
        }
    }
    else{
        print( "Success!\n" );
    }
?>
</bottle>
-->

