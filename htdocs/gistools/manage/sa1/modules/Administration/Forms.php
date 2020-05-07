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
 * $Id: Forms.php,v 1.19 2005/02/09 07:08:54 andrew Exp $
 * Description:  Contains a variety of utility functions specific to this module.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

function get_confsettings_js() {
	global $mod_strings;
	global $app_strings;

	$lbl_last_name = $mod_strings['LBL_NOTIFY_FROMADDRESS'];
	$err_missing_required_fields = $app_strings['ERR_MISSING_REQUIRED_FIELDS'];

	return <<<EOQ

<script type="text/javascript" language="Javascript">
<!--  to hide script contents from old browsers

function notify_setrequired(f) {
	document.getElementById("smtp_settings").style.display = (f.mail_sendtype.value == "SMTP") ? "inline" : "none";
	document.getElementById("smtp_settings").style.visibility = (f.mail_sendtype.value == "SMTP") ? "visible" : "hidden";
	document.getElementById("smtp_auth").style.display = (f.mail_smtpauth_req.checked) ? "inline" : "none";
	document.getElementById("smtp_auth").style.visibility = (f.mail_smtpauth_req.checked) ? "visible" : "hidden";
	return true;
}

function add_checks(f) {
	removeFromValidate('ConfigureSettings', 'mail_smtpserver');
	removeFromValidate('ConfigureSettings', 'mail_smtpport');
	removeFromValidate('ConfigureSettings', 'mail_smtpuser');
	removeFromValidate('ConfigureSettings', 'mail_smtppass');

	if (f.mail_sendtype.value == "SMTP") {
		addToValidate('ConfigureSettings', 'mail_smtpserver', 'varchar', 'true', '{$mod_strings['LBL_MAIL_SMTPSERVER']}');
		addToValidate('ConfigureSettings', 'mail_smtpport', 'int', 'true', '{$mod_strings['LBL_MAIL_SMTPPORT']}');
		if (f.mail_smtpauth_req.checked) {
			addToValidate('ConfigureSettings', 'mail_smtpuser', 'varchar', 'true', '{$mod_strings['LBL_MAIL_SMTPUSER']}');
			addToValidate('ConfigureSettings', 'mail_smtppass', 'varchar', 'true', '{$mod_strings['LBL_MAIL_SMTPPASS']}');
		}
	}
	return true;
}

notify_setrequired(document.ConfigureSettings);

// end hiding contents from old browsers  -->
</script>

EOQ;
}

function get_chooser_js()
{
$the_script  = <<<EOQ

<script type="text/javascript" language="Javascript">
<!--  to hide script contents from old browsers

function set_chooser()
{



var display_tabs_def = '';

for(i=0; i < object_refs['display_tabs'].options.length ;i++)
{
         display_tabs_def += "display_tabs[]="+object_refs['display_tabs'].options[i].value+"&";
}

document.EditView.display_tabs_def.value = display_tabs_def;



}
// end hiding contents from old browsers  -->
</script>
EOQ;

return $the_script;
}




?>
