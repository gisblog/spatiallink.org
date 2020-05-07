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

// $Id: UpgradeConfig.php,v 1.3 2005/04/27 23:45:18 bob Exp $

require_once('XTemplate/xtpl.php');

global $mod_strings;

// the initial settings for the template variables to fill
$lbl_config_check = $mod_strings['LBL_CONFIG_CHECK'];
$config_check = '';
$lbl_perform_upgrade = $mod_strings['LBL_PERFORM_UPGRADE'];
$disable_config_upgrade = 'disabled="disabled"';
$btn_perform_upgrade = $mod_strings['BTN_PERFORM_UPGRADE'];

// check the status of the config file
$has_array_config   = empty($dbconfig['db_host_name']);
$config_current     = false;
$is_writable        = is_writable('config.php');
$config_file_ready  = false;

if( $has_array_config ){
    if( $sugar_config['sugar_version'] == $sugar_version ){
        $config_current = true;
    }
}

if( $has_array_config && $config_current ){
	$config_check = $mod_strings['MSG_CONFIG_FILE_UP_TO_DATE'];
}
else {
	if( $is_writable ){
		$config_check = $mod_strings['MSG_CONFIG_FILE_READY_FOR_UPGRADE'];
		$disable_config_upgrade = '';
		$config_file_ready = true;
	}
	else {
		$config_check = $mod_strings['MSG_MAKE_CONFIG_FILE_WRITABLE'];
	}
}

// only do the upgrade if config file checks out and user has posted back
if( !empty($_POST['perform_upgrade']) && $config_file_ready ){
    // add defaults to missing values of in-memory sugar_config
    $sugar_config = sugar_config_union( get_sugar_config_defaults(), $sugar_config );

    // need to verride version with default no matter what
    $sugar_config['sugar_version'] = $sugar_version;

	ksort( $sugar_config );

	$sugar_config_string = "<?php\n" .
		'// created: ' . date('Y-m-d H:i:s') . "\n" .
		'$sugar_config = ' .
		var_export($sugar_config, true) .
		";\n?>\n";

    if( $config_file = @fopen("config.php", "w") ) {
		fputs($config_file, $sugar_config_string, strlen($sugar_config_string));
  		fclose($config_file);
		$config_check = $mod_strings['MSG_CONFIG_FILE_UPGRADE_SUCCESS'];
		$disable_config_upgrade = 'disabled="disabled"';
	}
	else {
		$config_check = $mod_strings['MSG_CONFIG_FILE_UPGRADE_FAILED'];
	}

    make_not_writable( "config.php" );
}

/////////////////////////////////////////////////////////////////////
// TEMPLATE ASSIGNING

$xtpl = new XTemplate('modules/Administration/UpgradeConfig.html');
$xtpl->assign('LBL_CONFIG_CHECK', $lbl_config_check);
$xtpl->assign('CONFIG_CHECK', $config_check);
$xtpl->assign('LBL_PERFORM_UPGRADE', $lbl_perform_upgrade);
$xtpl->assign('DISABLE_CONFIG_UPGRADE', $disable_config_upgrade);
$xtpl->assign('BTN_PERFORM_UPGRADE', $btn_perform_upgrade);
$xtpl->parse('main');
$xtpl->out('main');

?>
