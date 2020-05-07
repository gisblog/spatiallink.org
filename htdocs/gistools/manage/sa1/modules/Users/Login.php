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
 * $Id: Login.php,v 1.50 2005/04/14 18:30:35 lam Exp $
 * Description: TODO:  To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
$theme_path="themes/".$theme."/";
require_once($theme_path.'layout_utils.php');

global $app_language, $sugar_config;
//we don't want the parent module's string file, but rather the string file specifc to this subpanel
global $current_language;
$current_module_strings = return_module_language($current_language, 'Users');

// Retrieve username and password from the session if possible.
if(isset($_SESSION["login_user_name"]))
{
	if (isset($_REQUEST['default_user_name']))
		$login_user_name = $_REQUEST['default_user_name'];
	else
		$login_user_name = $_SESSION['login_user_name'];
}
else
{
	if (isset($_REQUEST['default_user_name']))
	{
		$login_user_name = $_REQUEST['default_user_name'];
	}
	elseif (isset($_REQUEST['ck_login_id_20'])) {
		$login_user_name = get_assigned_user_name($_REQUEST['ck_login_id_20']);
	}
	else
	{
		$login_user_name = $sugar_config['default_user_name'];
	}
	$_session['login_user_name'] = $login_user_name;
}

$current_module_strings['VLD_ERROR'] = base64_decode('UGxlYXNlIHJlcGxhY2UgdGhlIFN1Z2FyQ1JNIGxvZ29zLg==');

// Retrieve username and password from the session if possible.
if(isset($_SESSION["login_password"]))
{
	$login_password = $_SESSION['login_password'];
}
else
{
	$login_password = $sugar_config['default_password'];
	$_session['login_password'] = $login_password;
}

if(isset($_SESSION["login_error"]))
{
	$login_error = $_SESSION['login_error'];
}




//echo get_module_title($current_module_strings['LBL_MODULE_NAME'], $current_module_strings['LBL_LOGIN'], false);
?>
<script type="text/javascript" language="JavaScript">
<!-- Begin
function set_focus() {
	if (document.DetailView.user_name.value != '') {
		document.DetailView.user_password.focus();
		document.DetailView.user_password.select();
	}
	else document.DetailView.user_name.focus();
}

function toggleDisplay(id){

	if(this.document.getElementById( id).style.display=='none'){
		this.document.getElementById( id).style.display='inline'
		if(this.document.getElementById(id+"link") != undefined){
			this.document.getElementById(id+"link").style.display='none';
		}
	document['options'].src = 'include/images/options_up.gif';		
	}else{
		this.document.getElementById(  id).style.display='none'
		if(this.document.getElementById(id+"link") != undefined){
			this.document.getElementById(id+"link").style.display='inline';
		}
	document['options'].src = 'include/images/options.gif';	
	}
}
		//  End -->
	</script>
	<style type="text/css">
	body { 
		margin: 0px 0px 0px 0px;
		font-family: Arial, Verdana, Helvetica, sans-serif;
		color: #444444;
		font-size: 12px;
		background-color: #ffffff;
		}

	.body { 
		font-size: 12px;
		}
		
	.buttonLogin {
		border: 1px solid #444444;
		font-size: 11px;
		color: #ffffff;
		background-color: #666666;
		font-weight: bold;
		}
		
	input,select {
		border: 1px solid #bbbbbb;
		font-size: 11px;
		background-color: #ffffff;
		}
		
	table,td {
		color: #444444;
		font-size: 11px;
		}
	
	p {
		MARGIN-TOP: 0px;
		MARGIN-BOTTOM: 10px;
		}
		
	form {
		margin: 0px;
		}
	
	.dataLabel {
		font-weight: bold;
		vertical-align : top;
	}
	.dataField {
		font-weight: bold;
		vertical-align : top;
	}
	</style><br>
	<br>
	
<table cellpadding="0" align="center" width="100%" cellspacing="0" border="0">
<tr>
<td>

<table cellpadding="0"  cellspacing="0" border="0" align="center" style="border: 1px solid #888888;">
<form action="index.php" method="post" name="DetailView" id="form">
<tr>
<td  align="right" style="padding: 4px; background-color: #ffffff; border-bottom: 1px solid #bbbbbb;"><IMG src="include/images/sugarcrm_login.png" width="120" height="19" alt="SugarCRM"></td>
</tr>
<tr>
<td style="background-color: #dddddd; padding: 5px;" align="center">

		<table cellpadding="0" cellspacing="2" border="0" align="center" width="100%">
		<tr>
			<td colspan="2" width="100%"><p><IMG src="include/images/sugar_md.png" width="300" height="25" alt="Sugar"></p></td>
		</tr>
		<tr>
			<td colspan="2" width="100%" style="font-size: 12px; padding-bottom: 5px;"><?php echo $app_strings['NTC_LOGIN_MESSAGE']; ?></td>
		</tr>
			<input type="hidden" name="module" value="Users">
			<input type="hidden" name="action" value="Authenticate">
			<input type="hidden" name="return_module" value="Users">
			<input type="hidden" name="return_action" value="Login">
			<input type="hidden" name="login_module" value="<?php if (isset($_GET['module'])) echo $_GET['module']; ?>">
			<input type="hidden" name="login_action" value="<?php if (isset($_GET['action'])) echo $_GET['action']; ?>">
			<input type="hidden" name="login_record" value="<?php if (isset($_GET['record'])) echo $_GET['record']; ?>">
<?php
if( isset($_SESSION['validation'])){
?>
		<tr>
			<td class="dataLabel"><?php echo $current_module_strings['LBL_ERROR'];?></td>
			<td><span class="error"><?php echo $current_module_strings['VLD_ERROR']; ?></span></td>
		</tr>
<?php
}
else if(isset($login_error) && $login_error != "")
{
?>
		<tr>
			<td class="dataLabel"><?php echo $current_module_strings['LBL_ERROR'] ?></td>
			<td><span class="error"><?php echo $login_error ?></span></td>
		</tr>
<?php
}



if (isset($_REQUEST['ck_login_language_20'])) {
	$display_language = $_REQUEST['ck_login_language_20'];
}
else {
	$display_language = $sugar_config['default_language'];
}

if (isset($_REQUEST['ck_login_theme_20'])) {
	$display_theme = $_REQUEST['ck_login_theme_20'];
}
else {
	$display_theme = $sugar_config['default_theme'];
}

?>
		<tr>
			<td class="dataLabel" width="30%"><?php echo $current_module_strings['LBL_USER_NAME'] ?></td>
			<td width="70%"><input type="text" size='20' name="user_name"  value=<?php echo "\"$login_user_name\"/>"; if (!empty($sugar_config['default_user_name'])) echo " ({$sugar_config['default_user_name']})"; ?></td>
		</tr>
		<tr>
			<td class="dataLabel"><?php echo $current_module_strings['LBL_PASSWORD'] ?></td>
			<td width="30%"><input type="password" size='20' name="user_password" value=<?php echo "\"$login_password\"/>"; if (!empty($sugar_config['default_password'])) echo " ({$sugar_config['default_password']})"; ?></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input title="<?php echo $current_module_strings['LBL_LOGIN_BUTTON_TITLE'] ?>" accessKey="<?php echo $current_module_strings['LBL_LOGIN_BUTTON_TITLE'] ?>" class="buttonLogin" type="submit" name="Login" value="  <?php echo $current_module_strings['LBL_LOGIN_BUTTON_LABEL'] ?>  "></td>
		</tr>
		<tr>
			<td align="right" colspan="2"><div  style="cursor: hand;" onclick='toggleDisplay("more");'><IMG name="options" src="include/images/options.gif" width="63" height="8" alt="Options"></div></td>
		</tr>
		</table>
		<div id='more'>
		<table cellpadding="0" cellspacing="2" border="0" align="center" width="100%">
		<tr>
			<td class="dataLabel" width="30%"><?php echo $current_module_strings['LBL_THEME'] ?></td>
			<td width="70%"><select name='login_theme'><?php echo get_select_options_with_id(get_themes(), $display_theme) ?></select></td>
		</tr>
		<tr>
			<td class="dataLabel"><?php echo $current_module_strings['LBL_LANGUAGE'] ?></td>
			<td><select name='login_language'><?php $the_languages = get_languages(); echo get_select_options_with_id($the_languages, $display_language); ?></select></td>
		</tr>
		</table>
		</div>
	</td>
	</form>
</tr>
</table>
</td>
</tr></table><br>
<br>

