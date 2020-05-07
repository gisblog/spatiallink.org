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
 * $Id: index.php,v 1.69.2.3 2005/05/17 23:20:34 majed Exp $
 * Description: TODO:  To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $theme;
global $currentModule;
global $current_language;
global $gridline;
global $current_user;

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

require_once($theme_path.'layout_utils.php');
require_once('include/logging.php');

$log = LoggerManager::getLogger('administration');

if (!is_admin($current_user))
{
   sugar_die("Unauthorized access to administration.");
}

echo '<p>' .
      get_module_title($mod_strings['LBL_MODULE_NAME'],
                       $mod_strings['LBL_MODULE_TITLE'], true)
      . '</p>';
?>

<p>
<?php echo get_form_header($mod_strings['LBL_ADMINISTRATION_HOME_TITLE'],
                           '',false);

// variables for the images and text content to be used in the html

$configure_settings_img_html = get_image($image_path . 'Administration',
                      'alt="' .  $mod_strings['LBL_CONFIGURE_SETTINGS_TITLE'] .
                      '" border="0" align="absmiddle"');
$configure_settings_lbl = $mod_strings['LBL_CONFIGURE_SETTINGS_TITLE'];
$configure_settings_desc = $mod_strings['LBL_CONFIGURE_SETTINGS'];

$upgrade_img_html = get_image($image_path . 'Upgrade', 'alt="' .
                              $mod_strings['LBL_UPGRADE_TITLE'] .
                              '" border="0" align="absmiddle"');
$upgrade_lbl = $mod_strings['LBL_UPGRADE_TITLE'];
$upgrade_desc = $mod_strings['LBL_UPGRADE'];

$user_img_html = get_image($image_path . 'Users', 'alt="' .
                           $mod_strings['LBL_MANAGE_USERS_TITLE'] .
                           '" border="0" align="absmiddle"');
$user_lbl = $mod_strings['LBL_MANAGE_USERS_TITLE'];
$user_desc = $mod_strings['LBL_MANAGE_USERS'];

$roles_img_html = get_image($image_path . 'Roles', 'alt="' .
                            $mod_strings['LBL_MANAGE_ROLES_TITLE'] .
                            '" border="0" align="absmiddle"');
$roles_lbl = $mod_strings['LBL_MANAGE_ROLES_TITLE'];
$roles_desc = $mod_strings['LBL_MANAGE_ROLES'];























$currencies_img_html = get_image($image_path . 'Currencies', 'alt="' .
                                 $mod_strings['LBL_MANAGE_CURRENCIES'] .
                                 '" border="0" align="absmiddle"');
$currencies_lbl = $mod_strings['LBL_MANAGE_CURRENCIES'];
$currencies_desc = $mod_strings['LBL_CURRENCY'];

$edit_custom_fields_img_html = get_image($image_path . 'FieldLabels',
	'alt="' .  $mod_strings['LBL_EDIT_CUSTOM_FIELDS'] .
	'" border="0" align="absmiddle"');
$edit_custom_fields_lbl = $mod_strings['LBL_EDIT_CUSTOM_FIELDS'];
$edit_custom_fields_desc = $mod_strings['DESC_EDIT_CUSTOM_FIELDS'];


$tabs_img_html = get_image($image_path . 'ConfigureTabs',
                   'alt="' .  $mod_strings['LBL_CONFIGURE_TABS'] .
                   '" border="0" align="absmiddle"');

$rename_tabs_img_html = get_image($image_path . 'RenameTabs',
                   'alt="' .  $mod_strings['LBL_CONFIGURE_TABS'] .
                   '" border="0" align="absmiddle"');
                   
$layout_img_html = get_image($image_path . 'Layout',
                   'alt="' .  $mod_strings['LBL_MANAGE_LAYOUT'] .
                   '" border="0" align="absmiddle"');
$layout_lbl = $mod_strings['LBL_MANAGE_LAYOUT'];
$layout_desc = $mod_strings['LBL_LAYOUT'];

$dropdown_img_html = get_image($image_path . 'Dropdown',
                   'alt="' .  $mod_strings['LBL_CONFIGURE_SETTINGS_TITLE'] .
                   '" border="0" align="absmiddle"');
$dropdown_lbl = $mod_strings['LBL_DROPDOWN_EDITOR'];
$dropdown_desc = $mod_strings['DESC_DROPDOWN_EDITOR'];

$iframe_img_html = get_image($image_path . 'iFrames', 'alt="' .
                                 $mod_strings['DESC_IFRAME'] .
                                 '" border="0" align="absmiddle"');
$iframe_lbl = $mod_strings['LBL_IFRAME'];
$iframe_desc = $mod_strings['DESC_IFRAME'];

$mass_img_html = get_image($image_path . 'EmailMan', 'alt="' .
                                 $mod_strings['LBL_MASS_EMAIL_MANAGER_TITLE'] .
                                 '" border="0" align="absmiddle"');
$mass_desc = $mod_strings['LBL_MASS_EMAIL_MANAGER_DESC'];
$mass_lbl = $mod_strings['LBL_MASS_EMAIL_MANAGER_TITLE'];
?>
<table width="100%" cellpadding="0" cellspacing="1" border="0" class="tabDetailView2">
<tr>
<td width="20%" class="tabDetailViewDL2" nowrap><?php echo $configure_settings_img_html; ?>&nbsp;<a href="./index.php?module=Administration&action=ConfigureSettings" class="tabDetailViewDL2Link"><?php echo $configure_settings_lbl; ?></a></td>
<td align="left" width="30%" class="tabDetailViewDF2"><?php echo $configure_settings_desc; ?></td>
<td width="20%" class="tabDetailViewDL2"><?php echo $upgrade_img_html; ?>&nbsp;<a href="./index.php?module=Administration&action=Upgrade" class="tabDetailViewDL2Link"><?php echo $upgrade_lbl; ?></a></td>
<td align="left" width="30%" class="tabDetailViewDF2"><?php echo $upgrade_desc; ?></td>
</tr>

<tr>
<td class="tabDetailViewDL2"><?php echo $user_img_html; ?>&nbsp;<a href="./index.php?module=Users&action=ListView" class="tabDetailViewDL2Link"><?php echo $user_lbl; ?></a></td>
<td align="left" class="tabDetailViewDF2"><?php echo $user_desc; ?></td>
<td class="tabDetailViewDL2"><?php echo $currencies_img_html; ?>&nbsp;<a href="./index.php?module=Currencies&action=index" class="tabDetailViewDL2Link"><?php echo $currencies_lbl; ?></a></td>
<td align="left" class="tabDetailViewDF2"><?php echo $currencies_desc; ?></td>
</tr>









<tr>
<td class="tabDetailViewDL2"><?php echo $roles_img_html; ?>&nbsp;<a href="./index.php?module=Roles&action=ListView" class="tabDetailViewDL2Link"><?php echo $roles_lbl; ?></a></td>
<td align="left" class="tabDetailViewDF2"><?php echo $roles_desc; ?></td>
<td class="tabDetailViewDL2">&nbsp;</td>
<td align="left" class="tabDetailViewDF2">&nbsp;</td>
</tr>
</table>
</p>

<p><?php echo get_form_header($mod_strings['LBL_STUDIO_TITLE'],'',false); ?>
<table width="100%" cellpadding="0" cellspacing="1" border="0" class="tabDetailView2">

<tr>
<!-- field layout and dropdown editor -->
<td width="20%" class="tabDetailViewDL2" nowrap><?php echo $layout_img_html; ?>&nbsp;<a href="./index.php?module=DynamicLayout&action=index" class="tabDetailViewDL2Link"><?php echo $layout_lbl; ?></a></td>
<td align="left" width="30%" class="tabDetailViewDF2"><?php echo $layout_desc; ?></td>
<td width="20%" class="tabDetailViewDL2"><?php echo $dropdown_img_html; ?>&nbsp;<a href="./index.php?module=Dropdown&action=index" class="tabDetailViewDL2Link"><?php echo $dropdown_lbl; ?></a></td>
<td align="left" width="30%" class="tabDetailViewDF2"><?php echo $dropdown_desc; ?></td>
</tr>

<tr>
<!-- edit custom fields and configure tabs -->
<td  width="20%" class="tabDetailViewDL2"><?php echo $edit_custom_fields_img_html; ?><a href="./index.php?module=EditCustomFields&action=index" class="tabDetailViewDL2Link"> <?php echo $edit_custom_fields_lbl; ?></a></td>
<td align="left" width="30%" class="tabDetailViewDF2"><?php echo $edit_custom_fields_desc; ?></td>
<td class="tabDetailViewDL2"><?php echo $tabs_img_html; ?>&nbsp;<a href="./index.php?module=Administration&action=ConfigureTabs" class="tabDetailViewDL2Link"><?php echo $mod_strings['LBL_CONFIGURE_TABS']; ?></a> </td>
<td align="left" class="tabDetailViewDF2"><?php echo $mod_strings['LBL_CHOOSE_WHICH']; ?></td>
</tr>

<tr>
<!-- portal -->
<td class="tabDetailViewDL2"><?php echo $iframe_img_html; ?>&nbsp;<a href="./index.php?module=iFrames&action=index" class="tabDetailViewDL2Link"><?php echo $iframe_lbl; ?></a></td>
<td align="left" class="tabDetailViewDF2"><?php echo $iframe_desc; ?></td>
<td class="tabDetailViewDL2"><?php echo $rename_tabs_img_html; ?>&nbsp;<a href="./index.php?dropdown_select=moduleList&language_select=<?php echo $current_language; ?>&action=index&query=true&module=Dropdown&button=Select" class="tabDetailViewDL2Link"><?php echo $mod_strings['LBL_RENAME_TABS']; ?></a> </td>
<td align="left" class="tabDetailViewDF2"><?php echo $mod_strings['LBL_CHANGE_NAME_TABS']; ?></td>
</tr>
<tr>
<!-- External Dev -->
<td class="tabDetailViewDL2"><?php echo get_image($image_path . 'MigrateFields',
	'alt="' .  $mod_strings['LBL_EXTERNAL_DEV_TITLE'] .
	'" border="0" align="absmiddle"'); ?>&nbsp;<a href="./index.php?module=Administration&action=Development" class="tabDetailViewDL2Link"><?php echo $mod_strings['LBL_EXTERNAL_DEV_TITLE']; ?></a></td>
<td align="left" class="tabDetailViewDF2"><?php echo $mod_strings['LBL_EXTERNAL_DEV_DESC']; ?></td>
<td class="tabDetailViewDL2">&nbsp;</td>
<td align="left" class="tabDetailViewDF2">&nbsp;</td>
</tr>

</table></p>




























<p><?php echo get_form_header($mod_strings['LBL_BUG_TITLE'],'',false); ?>
<table width="100%" cellpadding="0" cellspacing="1" border="0" class="tabDetailView2">
<tr>
	<td  width="20%" class="tabDetailViewDL2"><?php echo  get_image($image_path.'Releases','alt="'.$mod_strings['LBL_MANAGE_RELEASES'].'" border="0" align="absmiddle"');?>&nbsp;<a href="./index.php?module=Releases&action=index" class="tabDetailViewDL2Link"><?php echo $mod_strings['LBL_MANAGE_RELEASES']; ?></a></td>
	<td align="left" width="30%" class="tabDetailViewDF2"><?php echo $mod_strings['LBL_RELEASE']; ?></td>
	<td  width="20%" class="tabDetailViewDL2">&nbsp;</td>
	<td align="left" width="30%" class="tabDetailViewDF2">&nbsp;</td>
</tr>
</table></p>













<p><?php echo get_form_header($mod_strings['LBL_MASS_EMAIL_MANAGER_TITLE'],'',false); ?>
<table width="100%" cellpadding="0" cellspacing="1" border="0" class="tabDetailView2">
<tr>
<td width="20%" class="tabDetailViewDL2"><table cellpadding="0" cellspacing="0""><tr><td valign="top"><?php echo $mass_img_html;?>&nbsp;</td><td><a href="./index.php?module=EmailMan&action=index" class="tabDetailViewDL2Link"><?php echo $mass_lbl; ?></a></td></tr></table></td>
<td align="left" class="tabDetailViewDF2"><?php echo $mass_desc; ?></td>
<td width="20%" class="tabDetailViewDL2">&nbsp;</td>
<td align="left" width="30%" class="tabDetailViewDF2">&nbsp;</td>
</tr>
</table></p>

