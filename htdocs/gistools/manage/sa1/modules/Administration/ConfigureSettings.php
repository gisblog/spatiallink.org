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
 * $Id: ConfigureSettings.php,v 1.19.6.2 2005/05/17 22:38:06 majed Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Administration/Administration.php');
require_once('modules/Administration/Forms.php');

global $mod_strings;
global $app_list_strings;
global $app_strings;
global $current_user;

if (!is_admin($current_user)) sugar_die("Unauthorized access to administration.");

echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$mod_strings['LBL_CONFIGURE_SETTINGS'], true);
echo "\n</p>\n";
global $theme;
global $currentModule;
$theme_path = "themes/".$theme."/";
$image_path = $theme_path."images/";


require_once($theme_path.'layout_utils.php');

$focus = new Administration();
$focus->retrieveSettings();
$timezone = 0;
if(isset($focus->settings['system_timezone'])){
	$timezone = $focus->settings['system_timezone'];
}
$log->info("Administration ConfigureSettings view");

$xtpl=new XTemplate ('modules/Administration/ConfigureSettings.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

$xtpl->assign("RETURN_MODULE", "Administration");
$xtpl->assign("RETURN_ACTION", "index");

$xtpl->assign("MODULE", $currentModule);
$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("HEADER", get_module_title("Administration", "{MOD.LBL_CONFIGURE_SETTINGS}", true));
$xtpl->assign("JAVASCRIPT", get_confsettings_js());

$xtpl->assign("NOTIFY_FROMADDRESS", $focus->settings['notify_fromaddress']);
$xtpl->assign("NOTIFY_SEND_BY_DEFAULT", ($focus->settings['notify_send_by_default']) ? "checked='checked'" : "");
$xtpl->assign("NOTIFY_ON", ($focus->settings['notify_on']) ? "checked='checked'" : "");
$xtpl->assign("NOTIFY_FROMNAME", $focus->settings['notify_fromname']);
$xtpl->assign("MAIL_SMTPSERVER", $focus->settings['mail_smtpserver']);
$xtpl->assign("MAIL_SMTPPORT", $focus->settings['mail_smtpport']);
$xtpl->assign("MAIL_SENDTYPE", get_select_options_with_id($app_list_strings['notifymail_sendtype'], $focus->settings['mail_sendtype']));
$xtpl->assign("MAIL_SMTPUSER", $focus->settings['mail_smtpuser']);
$xtpl->assign("MAIL_SMTPPASS", $focus->settings['mail_smtppass']);
$xtpl->assign("MAIL_SMTPAUTH_REQ", ($focus->settings['mail_smtpauth_req']) ? "checked='checked'" : "");

$xtpl->assign("PORTAL_ON", ($focus->settings['portal_on']) ? "checked='checked'" : "");

$xtpl->parse("main");

$xtpl->out("main");

require_once("include/javascript/javascript.php");
$javascript = new javascript();
$javascript->setFormName("ConfigureSettings");
$javascript->addFieldGeneric("notify_fromaddress", "email", $mod_strings['LBL_NOTIFY_FROMADDRESS'], TRUE, "");
$javascript->addFieldGeneric("notify_subject", "varchar", $mod_strings['LBL_NOTIFY_SUBJECT'], TRUE, "");
echo $javascript->getScript();

?>
