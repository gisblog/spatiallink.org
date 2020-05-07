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

// $Id: phpCalls.php,v 1.4.2.1 2005/05/06 04:12:25 majed Exp $

if( !isset($upgrade_script) || ($upgrade_script == false) ){
	die('Unable to process script directly.');
}
 
set_time_limit(90);

    // flush after each output so the user can see the progress in real-time
    ob_implicit_flush();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <meta http-equiv="Content-Script-Type" content="text/javascript">
   <meta http-equiv="Content-Style-Type" content="text/css">
   <title>SugarCRM Upgrade Wizard: Step <?php echo $next_step ?></title>
   <link rel="stylesheet" href="upgrade.css" type="text/css" />
</head>
<body>
<form action="index.php" method="post" id="form">
<table cellspacing="0" cellpadding="0" border="0" align="center" class="shell" style="height=15px;margin-bottom=0px">
<tr>
   <th width="400">Step <?php echo $next_step ?>: Application Upgrade</th>
   <th width="200" height="30" style="text-align: right;"><a href="http://www.sugarcrm.com" target=
      "_blank"><IMG src="../include/images/sugarcrm_login.png" width="120" height="19" alt="SugarCRM" border="0"></a></th>
</tr>
<tr>
   <td colspan="2" width="800">

<?php

    // ACK!  need to cd back a dir for requires in required file to succeed
    chdir( ".." );
	if(!isset($php_upgrades['custom_fields']) || $php_upgrades['custom_fields']){
	    print( "Running migrating custom fields to new structure...<br>" );
	    require_once( 'upgrade/upgradeCustomFields.php' );
	}

    print( "<hr>" );
	if(!isset($php_upgrades['tabs']) || $php_upgrades['tabs']){
  	 	 print( "Upgrading tab structure...<br>" );
   	 	require_once( 'upgrade/upgradeTabs.php' );
	}
    print( "<hr>" );

    print( "<br>Done!<br>" );
    print( "Click 'Next' to continue...<br>" );
?>
	</td>
</tr>
<tr>
<td align="right" colspan="2">
<hr>
<table cellspacing="0" cellpadding="0" border="0" class="stdTable">
<tr>
<td>
     <input type="hidden" name="current_step" value="<?php echo $next_step ?>">
     <table cellspacing="0" cellpadding="0" border="0" class="stdTable">
       <tr>
         <td><input class="button" type="submit" name="goto" value="Next" /></td>
       </tr>
     </table>
</td>
</tr>
</table>
</td>
</tr>
</table>
</form>
</body>
</html>
