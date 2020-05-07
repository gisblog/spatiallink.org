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
// $Id: fin.php,v 1.5.2.1 2005/05/06 04:12:25 majed Exp $

if( !isset($upgrade_script) || ($upgrade_script == false) ){
    die('Unable to process script directly.');
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <meta http-equiv="Content-Style-Type" content="text/css">
   <title>SugarCRM Upgrade Wizard</title>
   <link rel="stylesheet" href="upgrade.css" type="text/css">
</head>

<body onload="javascript:document.getElementById('defaultFocus').focus();">
  <table cellspacing="0" cellpadding="0" border="0" align="center" class=
  "shell">
    <tr>
      <th width="400">The SugarCRM Upgrade Wizard has completed</th>

      <th width="200" height="30" style="text-align: right;"><a href="http://www.sugarcrm.com" target=
      "_blank"><IMG src="../include/images/sugarcrm_login.png" width="120" height="19" alt="SugarCRM" border="0"></a></th>
    </tr>

    <tr>
      <td colspan="2" width="600" style=
      "background-image : url(include/images/cube_bg.gif); background-position : right; background-repeat : no-repeat;">
      <p><img src="../include/images/sugar_md.png" alt="SugarCRM"
      width="300" height="25" border="0"></p>

<?php
	$upgrade_target_dir     = $_SESSION['upgrade_target_dir'];

	if (is_file("$upgrade_target_dir/index.php.bak")) {
		print( "Cleaning temp files...<br>" );
		rmdir_recursive( "$upgrade_target_dir/cache/dynamic_fields" );

		print( "Enabling new version of Sugar...<br>" );
		unlink( "$upgrade_target_dir/index.php" );
		copy( "$upgrade_target_dir/index.php.bak", "$upgrade_target_dir/index.php" );
		unlink( "$upgrade_target_dir/index.php.bak" );
		$query = "UPDATE  config SET value='$current_version$current_patch' WHERE category='info' AND name='sugar_version'";
	}
	$query = "UPDATE  config SET value='$current_version$current_patch' WHERE category='info' AND name='sugar_version'";
	$result = $db->query($query);

?>

        <p>The upgrade is complete.  Please click 'Finish' below and login as
            an admin to confirm the success of the upgrade.</p>

        <p>For help, please visit the SugarCRM <a href=
        "http://www.sugarcrm.com/forums/" target="_blank">support
        forums</a>.</p>
      </td>
    </tr>

    <tr>
      <td align="right" colspan="2" height="20">
        <hr>
        <form action="../index.php">
        <table cellspacing="0" cellpadding="0" border="0" class="stdTable">
          <tr>
            <td><input class="button" type="submit" value="Finish" id="defaultFocus" /></td>
          </tr>
        </table>
        </form>
      </td>
    </tr>
  </table>
</body>
</html>
