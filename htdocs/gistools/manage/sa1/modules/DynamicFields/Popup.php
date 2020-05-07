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

global $theme;
global $app_list_strings;
require_once('themes/'.$theme.'/layout_utils.php');
$image_path = 'themes/'.$theme.'/images/';
insert_popup_header($theme);
?>
<form action="index.php">
<input type="hidden" name="module" value="DynamicFields"/>
<input type="hidden" name="action" value="Save"/>
<input type="hidden" name="module_name" value="<?php echo $_REQUEST['module_name']; ?>"/>
<input type="hidden" name="file_type" value="<?php echo $_REQUEST['file_type']; ?>"/>
<input type="hidden" name="field_count" value="<?php echo $_REQUEST['field_count']; ?>"/>
<p>
<?php
echo get_form_header('Add Custom Field','','');

?>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="tabForm">
<tr><td>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td class="dataLabel" width="20%">Field Label:</td>
<td width="80%"><input type="text" name="field_label"></td>
</tr>
<tr>
<td class="dataLabel">Field Type:</td>
<td>
<select name="field_type" onchange="typeChanged(this);">
<option value="text">Text Field</option>
<option value="textarea">Text Area</option>
<option value="int">Integer</option>
<option value="float">Decimal</option>
<option value="bool">Yes/No</option>
<option value="enum">Dropdown</option>
<option value="date">Date</option>
</select>
</td>
</tr>
<tr class="dataLabel" id="field_options" style="display: none">
<td>Dropdown list:</td>
<td>
<select name="options">
<?php
foreach($app_list_strings as $key => $value)
{
   echo "<option value=\"$key\">$key</option>";
}
?>
</select>
</td>
</tr>
</table>
</td>
</tr>
</table>
</p>
<p align="right">
<input type="submit" value=" Add " class="button">
</p>
</form>
<p>
<b>Notes:</b>
<ul>
<li>
<?php echo $mod_strings['NOTE_CREATE_DROPDOWN'];?>
</li>
</ul>
</p>
</body>
<script>
function typeChanged(obj)
{

	if(obj.options[obj.selectedIndex].value == 'enum')
	{
		document.getElementById('field_options').style.display = '';
	}
	else
	{
		document.getElementById('field_options').style.display = 'none';
	}

}
</script>
</html>
