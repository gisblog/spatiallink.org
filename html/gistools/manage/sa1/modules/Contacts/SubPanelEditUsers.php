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


$sugar_config['list_max_entries_per_page'] = 1;

require_once('modules/Users/User.php');
$focusUser = new User();
$focusUser->assign_display_fields('Users');
include('modules/Contacts/SubPanelViewContactsAndUsers.php');
$SubPanel = new SubPanelViewContactsAndUsers();
$SubPanel->setFocus($focusUser);
$SubPanel->setHideContacts(true);
$SubPanel->setHideUsers(false);
$SubPanel->setUsersList($focusUser);
//global $mod_strings;
if(!empty($_REQUEST['mod_lang']))			
$mod_strings = return_module_language($current_language, $_REQUEST['mod_lang']);

$SubPanel->ProcessSubPanelListView('replace_file_name',$mod_strings, '');

?>
