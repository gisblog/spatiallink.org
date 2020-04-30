<?PHP
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
if(isset($_REQUEST['refreshparent'])){
	echo '<SCRIPT> parent.location.reload();</script>';	
}else if(isset($_REQUEST['module_name']) && isset($_REQUEST['showlist'])){
	$the_strings = return_module_language($current_language, $_REQUEST['module_name']);
	$mod_name = $_REQUEST['module_name'];
	global $theme;
	$theme_path = 'themes/' . $theme . '/';
	echo "<link rel='stylesheet' type='text/css' media='all' href='$theme_path/style.css'>";
	echo '<table width="100%" border="0" cellspacing=0 cellpadding="0" class="contentBox">';
	$sugar_body_only = 0;
	if(isset($_REQUEST['sugar_body_only'])){
		$sugar_body_only = $_REQUEST['sugar_body_only'];
	}
	foreach($the_strings as $key=>$value){
		echo "<tr><td nowrap>$key &nbsp;=>&nbsp; <a href='index.php?action=EditView&module=LabelEditor&module_name=$mod_name&record=$key&sugar_body_only=$sugar_body_only&style=popup'> $value </a></td></tr>";	
		
	}
	echo '</table>';
}else if(isset($_REQUEST['module_name'])){
	$the_strings = return_module_language($current_language, $_REQUEST['module_name']);
	$mod_name = $_REQUEST['module_name'];
	global $theme;
	global $app_strings;
	$theme_path = 'themes/' . $theme . '/';
	echo '<form name="ListEdit"  name="EditView" method="POST" action="index.php">';
	echo '<input type="hidden" name="action" value="Save">';
	echo '<input type="hidden" name="multi_edit" value="true">';
	echo '<input type="hidden" name="module_name" value="'.$_REQUEST['module_name'].'">';
	echo '<input type="hidden" name="module" value="LabelEditor">';
	echo "<link rel='stylesheet' type='text/css' media='all' href='$theme_path/style.css'>";
	echo <<<EOQ
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
	<td style="padding-bottom: 2px;"><input title="{$app_strings['LBL_SAVE_BUTTON_TITLE']}" accessKey="{$app_strings['LBL_SAVE_BUTTON_KEY']}" class="button" type="submit" name="button" value="  {$app_strings['LBL_SAVE_BUTTON_LABEL']}  " > &nbsp;<input title="{$app_strings['LBL_CANCEL_BUTTON_TITLE']}" accessKey="{APP.LBL_CANCEL_BUTTON_KEY}" class="button" type="button" name="button" onclick="document.location.reload()" value="  {$app_strings['LBL_CANCEL_BUTTON_LABEL']}  " ></td>
	</tr>
	</table>
	
EOQ;
	echo '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabForm">';
	$sugar_body_only = 0;
	if(isset($_REQUEST['sugar_body_only'])){
		$sugar_body_only = $_REQUEST['sugar_body_only'];
	}
	foreach($the_strings as $key=>$value){
		echo "<tr><td><span class='dataLabel'>$value</span><br><span style='font-size: 9;'>$key</span><br><input name='$key' value='$value' size='40'></td></tr>";	
		
	}
	echo '</table>';
	echo <<<EOQ
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
	<td style="padding-top: 2px;"><input title="{$app_strings['LBL_SAVE_BUTTON_TITLE']}" accessKey="{$app_strings['LBL_SAVE_BUTTON_KEY']}" class="button" type="submit" name="button" value="  {$app_strings['LBL_SAVE_BUTTON_LABEL']}  " > &nbsp;<input title="{$app_strings['LBL_CANCEL_BUTTON_TITLE']}" accessKey="{APP.LBL_CANCEL_BUTTON_KEY}" class="button" type="button" name="button" onclick="document.location.reload()" value="  {$app_strings['LBL_CANCEL_BUTTON_LABEL']}  " ></td>
	</tr>
	</table>
	
EOQ;
	echo '</form>';
}else{
	echo 'No Module Selected';
}	


?>
