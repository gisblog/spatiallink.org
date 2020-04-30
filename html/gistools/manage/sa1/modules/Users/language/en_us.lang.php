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
 * $Id: en_us.lang.php,v 1.46.2.1 2005/05/04 22:53:34 robert Exp $
 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

$mod_strings = array (
  'LBL_MODULE_NAME' => 'Users',
  'LBL_MODULE_TITLE' => 'Users: Home',
  'LBL_SEARCH_FORM_TITLE' => 'User Search',
  'LBL_LIST_FORM_TITLE' => 'Users',
  'LBL_NEW_FORM_TITLE' => 'New User',
  'LBL_USER' => 'Users:',
  'LBL_LOGIN' => 'Login',
  'LBL_RESET_PREFERENCES' => 'Reset To Default Preferences',
  'LBL_TIME_FORMAT' => 'Time Format:',
  'LBL_DATE_FORMAT' => 'Date Format:',
  'LBL_TIMEZONE' => 'Current Time:',
  'LBL_CURRENCY' => 'Currency:',
  'LBL_LIST_NAME' => 'Name',
  'LBL_LIST_TITLE' => 'Title',
  'LBL_LIST_LAST_NAME' => 'Last Name',
  'LBL_LIST_USER_NAME' => 'User Name',
  'LBL_LIST_DEPARTMENT' => 'Department',
  'LBL_LIST_EMAIL' => 'Email',
  'LBL_LIST_PRIMARY_PHONE' => 'Primary Phone',
  'LBL_LIST_ADMIN' => 'Admin',
  'LBL_NEW_USER_BUTTON_TITLE' => 'New User [Alt+N]',
  'LBL_NEW_USER_BUTTON_LABEL' => 'New User',
  'LBL_NEW_USER_BUTTON_KEY' => 'N',
  'LBL_ERROR' => 'Error:',
  'LBL_PASSWORD' => 'Password:',
  'LBL_USER_NAME' => 'User Name:',
  'LBL_FIRST_NAME' => 'First Name:',
  'LBL_LAST_NAME' => 'Last Name:',
  'LBL_USER_SETTINGS' => 'User Settings',
  'LBL_THEME' => 'Theme:',
  'LBL_LANGUAGE' => 'Language:',
  'LBL_ADMIN' => 'Administrator:',
  'LBL_USER_INFORMATION' => 'User Information',
  'LBL_OFFICE_PHONE' => 'Office Phone:',
  'LBL_REPORTS_TO' => 'Reports to:',
  'LBL_OTHER_PHONE' => 'Other:',
  'LBL_OTHER_EMAIL' => 'Other Email:',
  'LBL_NOTES' => 'Notes:',
  'LBL_DEPARTMENT' => 'Department:',
  'LBL_STATUS' => 'Status:',
  'LBL_TITLE' => 'Title:',
  'LBL_ANY_PHONE' => 'Any Phone:',
  'LBL_ANY_EMAIL' => 'Any Email:',
  'LBL_ADDRESS' => 'Address:',
  'LBL_CITY' => 'City:',
  'LBL_STATE' => 'State:',
  'LBL_POSTAL_CODE' => 'Postal Code:',
  'LBL_COUNTRY' => 'Country:',
  'LBL_NAME' => 'Name:',
  'LBL_MOBILE_PHONE' => 'Mobile:',
  'LBL_OTHER' => 'Other:',
  'LBL_FAX' => 'Fax:',
  'LBL_EMAIL' => 'Email:',
  'LBL_HOME_PHONE' => 'Home Phone:',
  'LBL_ADDRESS_INFORMATION' => 'Address Information',
  'LBL_PRIMARY_ADDRESS' => 'Primary Address:',
  'LBL_CHANGE_PASSWORD_BUTTON_TITLE' => 'Change Password [Alt+P]',
  'LBL_CHANGE_PASSWORD_BUTTON_KEY' => 'P',
  'LBL_CHANGE_PASSWORD_BUTTON_LABEL' => 'Change Password',
  'LBL_LOGIN_BUTTON_TITLE' => 'Login [Alt+L]',
  'LBL_LOGIN_BUTTON_KEY' => 'L',
  'LBL_LOGIN_BUTTON_LABEL' => 'Login',
  'LBL_CHANGE_PASSWORD' => 'Change Password',
  'LBL_OLD_PASSWORD' => 'Old Password:',
  'LBL_NEW_PASSWORD' => 'New Password:',
  'LBL_CONFIRM_PASSWORD' => 'Confirm Password:',
  'LBL_EMPLOYEE_STATUS' => 'Employee Status:',
  'LBL_MESSENGER_TYPE' => 'IM Type:',
  'LBL_MESSENGER_ID' => 'IM Name:',
  'ERR_ENTER_OLD_PASSWORD' => 'Please enter your old password.',
  'ERR_ENTER_NEW_PASSWORD' => 'Please enter your new password.',
  'ERR_ENTER_CONFIRMATION_PASSWORD' => 'Please enter your password confirmation.',
  'ERR_REENTER_PASSWORDS' => 'Please re-enter passwords.  The \\"new password\\" and \\"confirm password\\" values do not match.',
  'ERR_INVALID_PASSWORD' => 'You must specify a valid username and password.',
  'ERR_PASSWORD_CHANGE_FAILED_1' => 'User password change failed for ',
  'ERR_PASSWORD_CHANGE_FAILED_2' => ' failed.  The new password must be set.',
  'ERR_PASSWORD_INCORRECT_OLD' => 'Incorrect old password for user $this->user_name. Re-enter password information.',
  'ERR_USER_NAME_EXISTS_1' => 'The user name ',
  'ERR_USER_NAME_EXISTS_2' => ' already exists.  Duplicate user names are not allowed.  Change the user name to be unique.',
  'ERR_LAST_ADMIN_1' => 'The user name "',
  'ERR_LAST_ADMIN_2' => '" is the last user with administrator access.  At least one user must be an administrator.',
  'LNK_NEW_USER' => 'Create User',
  'LNK_USER_LIST' => 'Users',
  'ERR_DELETE_RECORD' => 'A record number must be specified to delete the account.',
  'LBL_RECEIVE_NOTIFICATIONS' => 'Assignment Notification:',
  'LBL_RECEIVE_NOTIFICATIONS_TEXT' => 'Receive an e-mail notification when a record is assigned to you.',
  'LBL_ADMIN_TEXT' => 'Grants administrator privileges to this user',
  'LBL_PORTAL_ONLY' => 'Portal Only User:',
  'LBL_PORTAL_ONLY_TEXT' => 'The user is a portal user and cannot login through the SugarCRM web interface. This user is only used for portal webservices. Normal users cannot be used for portal webservices.',
  'LBL_TIME_FORMAT_TEXT' => 'Set the display format for time stamps',
  'LBL_DATE_FORMAT_TEXT' => 'Set the display format for date stamps',
  'LBL_TIMEZONE_TEXT' => 'Set the current time',
  'LBL_GRIDLINE' => 'Show Gridlines:',
  'LBL_GRIDLINE_TEXT' => 'Controls gridlines on detail views',
  'LBL_CURRENCY_TEXT' => 'Select the default currency',
  'LBL_DISPLAY_TABS'=>'Display Tabs',
  'LBL_HIDE_TABS'=>'Hide Tabs',
  'LBL_EDIT_TABS'=>'Edit Tabs',
  'LBL_REMOVED_TABS'=>'Admin Remove Tabs',
  'LBL_CHOOSE_WHICH'=>'Choose which tabs are displayed',








  'LBL_MAIL_OPTIONS_TITLE' => 'E-mail Options',
  'LBL_MAIL_FROMNAME' => '"From" Name:',
  'LBL_MAIL_FROMADDRESS' => '"From" Address:',
  'LBL_MAIL_SMTPSERVER' => 'SMTP Server:',
  'LBL_MAIL_SMTPPORT' => 'SMTP Port:',
  'LBL_MAIL_SENDTYPE' => 'Mail Transfer Agent:',
  'LBL_MAIL_SMTPUSER' => 'SMTP Username:',
  'LBL_MAIL_SMTPPASS' => 'SMTP Password:',
  'LBL_MAIL_SMTPAUTH_REQ' => 'Use SMTP Authentication?',
  'LBL_CALENDAR_OPTIONS'=>'Calendar Options',
  'LBL_PUBLISH_KEY'=>'Publish Key:',
  'LBL_CHOOSE_A_KEY'=>'Choose a key to prevent unauthorized publishing of your calendar',
  'LBL_YOUR_PUBLISH_URL'=>'Publish at my location:',
  'LBL_YOUR_QUERY_URL'=>'Your Query URL:',
  'LBL_REMINDER'=>'Default Reminder:',
  'LBL_REMINDER_TEXT'=>'Default time to remind a person of an upcoming call or meeting',
  'LBL_SELECT_CHECKED_BUTTON_LABEL' => 'Select Checked Users',
  'LBL_SELECT_CHECKED_BUTTON_TITLE' => 'Select Checked Users',
  'LBL_LIST_STATUS' => 'Status',
  'LBL_MAX_TAB' => 'Number of tabs to display:',
	'LBL_SEARCH_URL'=>'Search location:',


);


?>
