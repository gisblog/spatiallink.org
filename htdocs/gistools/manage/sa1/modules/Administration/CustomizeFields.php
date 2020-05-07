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

// $Id: CustomizeFields.php,v 1.2 2005/02/16 23:50:36 andrew Exp $

global $app_strings;
global $app_list_strings;
global $mod_strings;

global $theme;
global $currentModule;
global $gridline;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');
require_once('include/logging.php');

$log = LoggerManager::getLogger('administration');
echo "\n<p>\n";
// echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_UPGRADE_TITLE'], true);
echo get_module_title('Customize Fields', 'Customize Fields', true);
echo "\n</p>\n";

?>
<table width="100%" cellpadding="0" cellspacing="<?php echo $gridline; ?>" border="0" class="tabDetailView2">
<tr>
<td>
<form>
Module Name:
<select>
<?php
foreach($moduleList as $module)
{
   echo "<option>$module</option>";
}
?>
</select>
<input type="button" class="button" value="Edit" />
</form>
</td>
</tr>
</table>

