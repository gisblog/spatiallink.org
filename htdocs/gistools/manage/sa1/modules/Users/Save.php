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
 * $Id: Save.php,v 1.46.2.1 2005/05/13 18:50:48 joey Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('modules/Users/User.php');
require_once('include/logging.php');
require_once('modules/MySettings/TabController.php');

$log =& LoggerManager::getLogger('index');
$display_tabs_def = urldecode($_REQUEST['display_tabs_def']);
$hide_tabs_def = urldecode($_REQUEST['hide_tabs_def']);
$remove_tabs_def = urldecode($_REQUEST['remove_tabs_def']);

$DISPLAY_ARR = array();
$HIDE_ARR = array();
$REMOVE_ARR = array();

parse_str($display_tabs_def,$DISPLAY_ARR);
parse_str($hide_tabs_def,$HIDE_ARR);
parse_str($remove_tabs_def,$REMOVE_ARR);


if (isset($_POST['record']) && !is_admin($current_user) && $_POST['record'] != $current_user->id) sugar_die("Unauthorized access to administration.");
elseif (!isset($_POST['record']) && !is_admin($current_user)) echo ("Unauthorized access to user administration.");

$focus = new User();
$focus->retrieve($_POST['record']);

if(strtolower($current_user->is_admin) == 'off'  && $current_user->id != $focus->id){
		$log->fatal("SECURITY:Non-Admin ". $current_user->id . " attempted to change settings for user:". $focus->id);
		header("Location: index.php?module=Users&action=Logout");
		exit;
	}
if(strtolower($current_user->is_admin) == 'off'  && isset($_POST['is_admin']) && strtolower($_POST['is_admin']) == 'on'){
		$log->fatal("SECURITY:Non-Admin ". $current_user->id . " attempted to change is_admin settings for user:". $focus->id);
		header("Location: index.php?module=Users&action=Logout");
		exit;
	}


if (isset($_POST['user_name']) && isset($_POST['old_password']) && isset($_POST['new_password'])) {
	if (!$focus->change_password($_POST['old_password'], $_POST['new_password'])) {
		header("Location: index.php?action=Error&module=Users&error_string=".urlencode($focus->error_string));
		exit;
	}
}
else
{
	// New user





	foreach($focus->column_fields as $field)
	{
		if(isset($_POST[$field]))
		{
			$value = $_POST[$field];
			$focus->$field = $value;

		}
	}

	foreach($focus->additional_column_fields as $field)
	{
		if(isset($_POST[$field]))
		{
			$value = $_POST[$field];
			$focus->$field = $value;

		}
	}

	if (!isset($_POST['is_admin'])) $focus->is_admin = 'off';
	if (!isset($_POST['portal_only']) || $focus->is_admin != 'off') $focus->portal_only = '0';
	if (!isset($_POST['receive_notifications'])) $focus->receive_notifications = 0;
	if(isset($_POST['gridline'])) {
	$focus->setPreference('gridline','on' );
	} else {
	$focus->setPreference('gridline','off' );
	}

	if(isset($_POST['user_max_tabs']))
	{
		$focus->setPreference('max_tabs', $_POST['user_max_tabs']);
	}
	
	$tabs = new TabController();
	$tabs->set_user_tabs($DISPLAY_ARR['display_tabs'], &$focus, 'display');
	if(isset($HIDE_ARR['hide_tabs']))
		$tabs->set_user_tabs($HIDE_ARR['hide_tabs'], &$focus, 'hide');
	if(isset($REMOVE_ARR['remove_tabs']))
		$tabs->set_user_tabs($REMOVE_ARR['remove_tabs'], &$focus, 'remove');
	
	
	
	if(isset($_POST['should_remind'])&&$_POST['should_remind'] == '1' &&isset($_POST['reminder_time'])) $focus->setPreference('reminder_time',$_POST['reminder_time'] );
	if(isset($_POST['timeformat'])) $focus->setPreference('timef',$_POST['timeformat'] );
	if(isset($_POST['currency'])) $focus->setPreference('currency',$_POST['currency'] );
	if(isset($_POST['dateformat'])) $focus->setPreference('datef',$_POST['dateformat'] );
	if(isset($_POST['timezone'])) $focus->setPreference('timez',$_POST['timezone'] );
	if(isset($_POST['mail_fromname'])) $focus->setPreference('mail_fromname',$_POST['mail_fromname'] );
	if(isset($_POST['mail_fromaddress'])) $focus->setPreference('mail_fromaddress',$_POST['mail_fromaddress'] );
	if(isset($_POST['mail_sendtype'])) $focus->setPreference('mail_sendtype', $_POST['mail_sendtype']);
	if(isset($_POST['mail_smtpserver'])) $focus->setPreference('mail_smtpserver',$_POST['mail_smtpserver'] );
	if(isset($_POST['mail_smtpport'])) $focus->setPreference('mail_smtpport',$_POST['mail_smtpport'] );
	if(isset($_POST['mail_smtpuser'])) $focus->setPreference('mail_smtpuser',$_POST['mail_smtpuser'] );
	if(isset($_POST['mail_smtppass'])) $focus->setPreference('mail_smtppass',$_POST['mail_smtppass'] );
	if(isset($_POST['mail_smtpauth_req']))
	{
		$focus->setPreference('mail_smtpauth_req',$_POST['mail_smtpauth_req'] );
	}
	else
	{
		$focus->setPreference('mail_smtpauth_req','');
	}
	if(isset($_POST['mail_popserver'])) $focus->setPreference('mail_popserver',$_POST['mail_popserver'] );
	if(isset($_POST['mail_popport'])) $focus->setPreference('mail_popport',$_POST['mail_popport'] );
	if(isset($_POST['mail_popuser'])) $focus->setPreference('mail_popuser',$_POST['mail_popuser'] );
	if(isset($_POST['mail_poppass'])) $focus->setPreference('mail_poppass',$_POST['mail_poppass'] );

	if(isset($_POST['mail_popauth_req']))
	{
		$focus->setPreference('mail_popauth_req',$_POST['mail_popauth_req'] );
	}
	else
	{
		$focus->setPreference('mail_popauth_req','');
	}

	if(isset($_POST['calendar_publish_key'])) $focus->setPreference('calendar_publish_key',$_POST['calendar_publish_key'] );

	if (!$focus->verify_data())
	{
		header("Location: index.php?action=Error&module=Users&error_string=".urlencode($focus->error_string));
		exit;
	}
	else
	{
		$focus->save();
		$return_id = $focus->id;








	}
}
if(isset($_POST['return_module']) && $_POST['return_module'] != "") $return_module = $_POST['return_module'];
else $return_module = "Users";
if(isset($_POST['return_action']) && $_POST['return_action'] != "") $return_action = $_POST['return_action'];
else $return_action = "DetailView";
if(isset($_POST['return_id']) && $_POST['return_id'] != "") $return_id = $_POST['return_id'];

$log->debug("Saved record with id of ".$return_id);

header("Location: index.php?action=$return_action&module=$return_module&record=$return_id");
?>
