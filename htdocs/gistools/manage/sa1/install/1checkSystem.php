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

// $Id: 1checkSystem.php,v 1.71.2.3 2005/05/12 18:24:55 bob Exp $

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

// for keeping track of whether to enable/disable the 'Next' button
$error_found = false;

// Returns true if the given file/dir has been made writable (or is already
// writable).
function make_writable($file)
{
	$ret_val = false;
	if(is_file($file) || is_dir($file))
	{
		if(is_writable($file))
		{
			$ret_val = true;
		}
		else
		{
			$original_fileperms = fileperms($file);

			// add user writable permission
			$new_fileperms = $original_fileperms | 0x0080;
			@chmod($file, $new_fileperms);

			if(is_writable($file))
			{
				$ret_val = true;
			}
			else
			{
				// add group writable permission
				$new_fileperms = $original_fileperms | 0x0010;
				@chmod($file, $new_fileperms);
				
				if(is_writable($file))
				{
					$ret_val = true;
				}
				else
				{
					// add world writable permission
					$new_fileperms = $original_fileperms | 0x0002;
					@chmod($file, $new_fileperms);

					if(is_writable($file))
					{
						$ret_val = true;
					}
				}
			}
		}
	}

	return $ret_val;
}

function recursive_make_writable($start_file)
{
	$ret_val = make_writable($start_file);

	if($ret_val && is_dir($start_file))
	{
		// PHP 4 alternative to scandir()
		$files = array();
		$dh = opendir($start_file);
		$filename = readdir($dh);
		while(!empty($filename))
		{
			if($filename != '.' && $filename != '..')
			{
				$files[] = $filename;
			}

			$filename = readdir($dh);
		}

		foreach($files as $file)
		{
			$ret_val = recursive_make_writable($start_file . '/' . $file);

			if(!$ret_val)
			{
				break;
			}
		}
	}

	return $ret_val;
}

function recursive_is_writable($start_file)
{
	$ret_val = is_writable($start_file);

	if($ret_val && is_dir($start_file))
	{
		// PHP 4 alternative to scandir()
		$files = array();
		$dh = opendir($start_file);
		$filename = readdir($dh);
		while(!empty($filename))
		{
			if($filename != '.' && $filename != '..')
			{
				$files[] = $filename;
			}

			$filename = readdir($dh);
		}

		foreach($files as $file)
		{
			$ret_val = recursive_is_writable($start_file . '/' . $file);

			if(!$ret_val)
			{
				break;
			}
		}
	}

	return $ret_val;
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <meta http-equiv="Content-Script-Type" content="text/javascript">
   <meta http-equiv="Content-Style-Type" content="text/css">
   <title>SugarCRM Setup Wizard: Step <?php echo $next_step ?></title>
   <link rel="stylesheet" href="install/install.css" type="text/css">
   <script type="text/javascript" src="install/installCommon.js"></script>
</head>

<body onload="javascript:document.getElementById('defaultFocus').focus();">
  <table cellspacing="0" cellpadding="0" border="0" align="center" class="shell">
    <tr>
      <th width="400">Step <?php echo $next_step ?>: System Check Acceptance</th>
	  <th width="200" height="30" style="text-align: right;"><a href="http://www.sugarcrm.com" target=
      "_blank"><IMG src="include/images/sugarcrm_login.png" width="120" height="19" alt="SugarCRM" border="0"></a></th>
    </tr>

    <tr>
      <td colspan="2" width="600">
        <p>In order for your SugarCRM installation to function properly,
        please ensure all of the system check items listed below are green. If
        any are red, please take the necessary steps to fix them.</p>

        <table cellpadding="0" cellspacing="0" border="0" width="100%" class="StyleDottedHr">
          <tr>
            <th align="left">Component</th>

            <th style="text-align: right;">Status</th>
          </tr>

          <tr>
            <td><b>PHP Version 4.2.x or newer</b></td>

            <td align="right"><?php
               $php_version = phpversion();
               if(str_replace(".", "", $php_version) < "420")
               {
                  echo "<b><span class=stop>Invalid version ($php_version) Installed</span></b>";
                  $error_found = true;
               }
               else
               {
                  echo "<b><span class=go>OK (ver $php_version)</span></b>";
               }
            ?></td>
          </tr>

<?php
            switch($_SESSION['setup_db_type']){
                case 'mysql':
                    $db_name        = "MySQL Database";
                    $function_name  = "mysql_connect";
                    break;
                case 'oci8':




                    break;
                }
?>
          <tr>
            <td><strong><?php echo "$db_name" ?></strong></td>

            <td align="right"><?php
               if( function_exists( $function_name ) ){
                  echo '<b><span class=go>OK</font></b>';
               }
               else {
                  echo '<b><span class=stop>Not Available</font></b>';
                  $error_found = true;
               }
            ?></td>
          </tr>

          <tr>
            <td><strong>XML Parsing</strong></td>

            <td align="right"><?php
               if(function_exists('xml_parser_create'))
               {
                  echo '<b><span class=go>OK</font></b>';
               }
               else
               {
                  echo '<b><span class=stop>Not Available</font></b>';
                  $error_found = true;
               }
            ?></td>
          </tr>

          <tr>
            <td><b>Writable SugarCRM Configuration File (config.php)</b></td>

            <td align="right"><?php
               if(make_writable('./config.php'))
               {
                  echo '<b><span class="go">OK</font></b>';
               }
               elseif(is_writable('.'))
					{
                  echo '<b><span class="go">OK</font></b>';
					}
					else
               {
                  echo '<b><span class="stop">Warning: Not Writeable</font></b>';
               }
            ?></td>
          </tr>

          <tr>
            <td><b>Writable Custom Directory</b></td>

            <td align="right"><?php

               if(make_writable('./custom'))
               {
                  echo '<b><span class=go>OK</font></b>';
               }
               else
               {
                  echo '<b><span class=stop>Not Writeable</font></b>';
                  $error_found = true;
               }
            ?></td>
          </tr>

          <tr>
            <td><b>Writable Modules Sub-Directories and Files</b></td>

            <td align="right"><?php
               if(recursive_make_writable('./modules'))
               {
                  echo '<b><span class=go>OK</font></b>';
               }
               else
               {
                  echo '<b><span class=stop>Not Writeable</font></b>';
                  $error_found = true;
               }
            ?></td>
          </tr>

          <tr>
            <td><b>Writable Data Sub-Directories</b></td>

            <td align="right"><?php
               if(make_writable('./data') &&
                  make_writable('./data/upload'))
               {
                  echo '<b><span class=go>OK</font></b>';
               }
               else
               {
                  echo '<b><span class=stop>Not Writeable</font></b>';
                  $error_found = true;
               }
            ?></td>
          </tr>

          <tr>
            <td><b>Writable Cache Sub-Directories</b></td>

            <td align="right"><?php
               if(make_writable('./cache/custom_fields') &&
                  make_writable('./cache/dyn_lay') &&
                  make_writable('./cache/images') &&
                  make_writable('./cache/import') &&
                  make_writable('./cache/layout') &&
                  make_writable('./cache/pdf') &&
                  make_writable('./cache/upload') &&
                  make_writable('./cache/xml'))
               {
                  echo '<b><span class=go>OK</font></b>';
               }
               else
               {
                  echo '<b><span class=stop>Not Writeable</font></b>';
                  $error_found = true;
               }
            ?></td>
          </tr>

          <?php
          $temp_dir = (isset($_ENV['TEMP'])) ? $_ENV['TEMP'] : "";
          $session_save_path = (session_save_path() === "") ? $temp_dir : session_save_path();
          if (strpos ($session_save_path, ";") !== FALSE){
              $session_save_path = substr ($session_save_path, strpos ($session_save_path, ";")+1);
          }
          ?>

          <tr>
            <td><b>Writable Session Save Path (<?php echo $session_save_path; ?>)</b></td>

            <td align="right"><?php
               if(is_dir($session_save_path))
               {
                  if(is_writable($session_save_path))
                  {
                     echo '<b><span class=go>OK</font></b>';
                  }
                  else
                  {
                     echo '<b><span class=stop>Not Writeable</font></b>';
                     $error_found = true;
                  }
               }
               else
               {
                  echo '<b><span class=stop>Not A Valid Directory</font></b>';
                  $error_found = true;
               }
            ?></td>
          </tr>

<?php
    if( PHP_OS != "WINNT" ){    // Windows builds from php.net have unlimited memory
        $memory_limit = ini_get('memory_limit');
        rtrim($memory_limit, 'M');
        $memory_limit_int = (int) $memory_limit;
?>
          <tr>
            <td><b>PHP Memory Limit >= 10M</b></td>
            <td align="right">
<?php
        if( $memory_limit_int <= 10 ){
            print( "<b><span class=\"stop\">Warning: $memory_limit (Set this to 10M or larger in your php.ini file)</span></b>" );
        }
        else {
            print( "<b><span class=\"go\">OK ($memory_limit)</span></b>" );
        }
?>
            </td>
          </tr>
<?php
    }   // end PHP_OS check
?>
        </table>

        <div align="center" style="margin: 5px;">
          <i><b>Note:</b> Your php configuration file (php.ini) is located
          at:<br>
          <?php echo get_cfg_var("cfg_file_path"); ?></i>
        </div>
      </td>
    </tr>

    <tr>
      <td align="right" colspan="2">
        <hr>
        <form action="install.php" method="post" name="theForm" id="theForm">
        <input type="hidden" name="current_step" value="<?php echo $next_step ?>">
        <table cellspacing="0" cellpadding="0" border="0" class="stdTable">
          <tr>
            <td><input class="button" type="button" onclick="window.open('http://www.sugarcrm.com/forums/');" value="Help" /></td>
            <td>
                <input class="button" type="button" name="goto" value="Re-check" onclick="document.getElementById('goto').value='Re-check';document.getElementById('theForm').submit();" />
            </td>
            <td>
                <input class="button" type="button" name="goto" value="Back" onclick="document.getElementById('theForm').submit();" />
                <input type="hidden" name="goto" value="Back" id="goto" />
            </td>
            <td><input class="button" type="submit" name="goto" value="Next" id="defaultFocus"
                 <?php if($error_found) { echo 'disabled="disabled"'; } ?> /></td>
          </tr>
        </table>
        </form>
      </td>
    </tr>
  </table><br>
</body>
</html>

