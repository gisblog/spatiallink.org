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

// $Id: Upgrade.php,v 1.20.2.2 2005/05/17 22:29:54 majed Exp $

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
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_UPGRADE_TITLE'], true);
echo "\n</p>\n";

?>
<p>
<table width="100%" cellpadding="0" cellspacing="<?php echo $gridline;?>" border="0" class="tabDetailView2">
<tr>
	<td width="20%" class="tabDetailViewDL2" nowrap><?php echo get_image($image_path.'Opportunities','alt="'. $mod_strings['LBL_MANAGE_OPPORTUNITIES'].'" align="absmiddle" border="0"'); ?>&nbsp;<a href="./index.php?module=Opportunities&action=UpgradeCurrency"><?php echo $mod_strings['LBL_MANAGE_OPPORTUNITIES']; ?></a></td>
	<td class="tabDetailViewDF2"> <?php echo $mod_strings['LBL_UPGRADE_CURRENCY'] .' ' .$mod_strings['LBL_MANAGE_OPPORTUNITIES']; ?> </td>
</tr>
<tr>
	<td class="tabDetailViewDL2"
	nowrap><?php echo get_image($image_path.'Upgrade','alt="'.
	$mod_strings['LBL_UPGRADE_CONFIG_TITLE'].'" align="absmiddle"
	border="0"'); ?>&nbsp;<a href="./index.php?module=Administration&action=UpgradeConfig"><?php
	echo $mod_strings['LBL_UPGRADE_CONFIG_TITLE']; ?></a></td>
	<td class="tabDetailViewDF2"> <?php echo $mod_strings['LBL_UPGRADE_CONFIG'] ; ?> </td>
</tr>


<?php














?>
<tr>
	<td class="tabDetailViewDL2" nowrap><?php echo get_image($image_path.'Upgrade','alt="'. $mod_strings['LBL_UPGRADE_DROPDOWN_TITLE'].'" align="absmiddle" border="0"'); ?>&nbsp;<a href="./index.php?module=Administration&action=language_upgrade"><?php echo $mod_strings['LBL_UPGRADE_DROPDOWN_TITLE']; ?></a></td>
	<td class="tabDetailViewDF2"> <?php echo $mod_strings['LBL_UPGRADE_DROPDOWN'] ; ?> </td>
</tr>
<tr>
	<td class="tabDetailViewDL2" nowrap><?php echo get_image($image_path.'Upgrade','alt="'. $mod_strings['LBL_UPGRADE_CUSTOM_FIELDS_TITLE'].'" align="absmiddle" border="0"'); ?>&nbsp;<a href="./index.php?module=Administration&action=UpgradeFields"><?php echo $mod_strings['LBL_UPGRADE_CUSTOM_FIELDS_TITLE']; ?></a></td>
	<td class="tabDetailViewDF2"> <?php echo $mod_strings['LBL_UPGRADE_CUSTOM_FIELDS'] ; ?> </td>
</tr>

</table></p>


