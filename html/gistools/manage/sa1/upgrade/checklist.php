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
// $Id: checklist.php,v 1.3 2005/04/29 02:51:31 bob Exp $

if( !isset($upgrade_script) || ($upgrade_script == false) ){
	die('Unable to process script directly.');
}

if(isset($_SESSION['upgrade_checklist_submitted']) && $_SESSION['upgrade_checklist_submitted']){
    // restore the values submitted by the user from the session
    $upgrade_checklist_accept   = $_SESSION['upgrade_checklist_accept'];
}
else{
    $upgrade_checklist_accept   = false;
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <meta http-equiv="Content-Style-Type" content="text/css">
   <title>SugarCRM Update Wizard: Step <?php echo $next_step ?></title>
   <link rel="stylesheet" href="upgrade.css" type="text/css">
   <script type="text/javascript" src="checklist.js"></script>
</head>

<body onload="javascript:toggleNextButton();document.getElementById('defaultFocus').focus();">
<form action="index.php" method="post" name="setConfig" id="form">
  <table cellspacing="0" cellpadding="0" border="0" align="center" class="shell">
    <tr>
      <th width="400">Step <?php echo $next_step ?>: Checklist Acceptance</th>

      <th width="200" height="30" style="text-align: right;"><a href="http://www.sugarcrm.com" target=
      "_blank"><IMG src="../include/images/sugarcrm_login.png" width="120" height="19" alt="SugarCRM" border="0"></a></th>
    </tr>

    <tr>
      <td colspan="2" width="600" style=
      "background-image : url(../include/images/cube_bg.gif); background-position : right; background-repeat : no-repeat;">
      <p><img src="../include/images/sugar_md.png" alt="SugarCRM"
      width="300" height="25" border="0"></p>

        <br>
		<b>Please do the following before proceeding:</b>
		<ul>
            <li>Check that your web server will have access to the current and new sugar app directories.
            <li>Make your config.php file writable (it will be upgraded)
			<li>Backup your existing SugarCRM directory
			<li>Backup your existing database
			<li>Backup your existing SugarCRM directory and database (it's really that important!)
		</ul>
      </td>
    </tr>
    <tr>
      <td align=left colspan="2">
        <input type="checkbox" class="checkbox" name="upgrade_checklist_accept" id="defaultFocus" onClick='toggleNextButton();' <?php if($upgrade_checklist_accept){ echo 'checked="on"';} ?> /><a href='javascript:void(0)' onClick='toggleChecklistAccept();toggleNextButton();'>I'm Ready!</a>
      </td>
    </tr>

    <tr>
      <td align="right" colspan="2">
        <hr>
        <input type="hidden" name="current_step" value="<?php echo $next_step ?>">
        <table cellspacing="0" cellpadding="0" border="0" class="stdTable">
          <tr>
            <td><input class="button" type="button" onclick="window.open('http://www.sugarcrm.com/forums/');" value="Help" /></td>
            <td><input class="button" type="submit" name="goto" value="Next" id="button_next" disabled="disabled" /></td>
          </tr>
        </table>
      </td>
    </tr>

  </table>
</form>
</body>
</html>
