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
 * $Id: license.php,v 1.8.2.2 2005/05/04 20:03:46 bob Exp $
 * Description:  license acceptance page.
 ********************************************************************************/

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

if(isset($_SESSION['license_submitted']) && $_SESSION['license_submitted']){
    // restore the values submitted by the user from the session
    $setup_license_accept   = $_SESSION['setup_license_accept'];
}
else{
    $setup_license_accept   = false;
}

$license_file_name = "LICENSE.txt";
$fh = fopen( $license_file_name, 'r' ) or die( "License file not found!" );
$license_file = fread( $fh, filesize( $license_file_name ) );
fclose( $fh );

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <meta http-equiv="Content-Style-Type" content="text/css">
   <title>SugarCRM Setup Wizard: Step <?php echo $next_step ?></title>
   <link rel="stylesheet" href="install/install.css" type="text/css">
   <script type="text/javascript" src="install/license.js"></script>
</head>

<body onload="javascript:toggleNextButton();document.getElementById('defaultFocus').focus();">
<form action="install.php" method="post" name="setConfig" id="form">
  <table cellspacing="0" cellpadding="0" border="0" align="center" class=
  "shell">
    <tr>
      <th width="400">Step <?php echo $next_step ?>: License Acceptance</th>

      <th width="200" height="30" style="text-align: right;"><a href="http://www.sugarcrm.com" target=
      "_blank"><IMG src="include/images/sugarcrm_login.png" width="120" height="19" alt="SugarCRM" border="0"></a></th>
    </tr>

    <tr>
      <td colspan="2" width="600" style=
      "background-image : url(include/images/cube_bg.gif); background-position : right; background-repeat : no-repeat;">
      <p><img src="include/images/sugar_md.png" alt="SugarCRM"
      width="300" height="25" border="0"></p>

        <br>
        <textarea cols="80" rows="20" readonly><?php print("$license_file"); ?></textarea>
      </td>
    </tr>
    <tr>
      <td align=left>
        <input type="checkbox" class="checkbox" name="setup_license_accept" id="defaultFocus" onClick='toggleNextButton();' <?php if($setup_license_accept){ echo 'checked="on"';} ?> /><a href='javascript:void(0)' onClick='toggleLicenseAccept();toggleNextButton();'>I Accept</a>
      </td>
      <td align=right>
        <input type="button" class="button" name="print_license" value=" Printable View " onClick='window.open("install/licensePrint.php");' />
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
            <td><input class="button" type="submit" name="goto" value="Next" id="button_next" disabled="disabled" /></td>
          </tr>
        </table>
      </td>
    </tr>

  </table>
</form>
</body>
</html>
