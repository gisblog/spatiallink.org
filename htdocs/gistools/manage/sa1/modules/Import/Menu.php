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
 * $Id: Menu.php,v 1.13 2005/02/09 07:08:54 andrew Exp $
 * Description:  TODO To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

global $mod_strings;
$module_menu = Array(
	Array("index.php?module=Contacts&action=EditView&return_module=Contacts&return_action=DetailView", $mod_strings['LNK_NEW_CONTACT'],"Contacts"),
	Array("index.php?module=Accounts&action=EditView&return_module=Accounts&return_action=DetailView", $mod_strings['LNK_NEW_ACCOUNT'],"Accounts"),
	Array("index.php?module=Opportunities&action=EditView&return_module=Opportunities&return_action=DetailView", $mod_strings['LNK_NEW_OPPORTUNITY'],"Opportunities"),
	Array("index.php?module=Cases&action=EditView&return_module=Cases&return_action=DetailView", $mod_strings['LNK_NEW_CASE'],"Cases"),
	Array("index.php?module=Notes&action=EditView&return_module=Notes&return_action=DetailView", $mod_strings['LNK_NEW_NOTE'],"Notes"),
	Array("index.php?module=Calls&action=EditView&return_module=Calls&return_action=DetailView", $mod_strings['LNK_NEW_CALL'],"Calls"),
	Array("index.php?module=Emails&action=EditView&return_module=Emails&return_action=DetailView", $mod_strings['LNK_NEW_EMAIL'],"Emails"),
	Array("index.php?module=Meetings&action=EditView&return_module=Meetings&return_action=DetailView", $mod_strings['LNK_NEW_MEETING'],"Meetings"),
	Array("index.php?module=Tasks&action=EditView&return_module=Tasks&return_action=DetailView", $mod_strings['LNK_NEW_TASK'],"Tasks")
	);

?>
