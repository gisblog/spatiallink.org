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

require_once('modules/Contacts/Contact.php');
$sugar_config['list_max_entries_per_page'] = 1;
$focus = new Contact();
$focus->assign_display_fields('Contacts');
include('modules/Contacts/SubPanelViewContactsAndUsers.php');
$SubPanel = new SubPanelViewContactsAndUsers();
$SubPanel->setFocus($focus);
$SubPanel->setHideUsers(true);
global $mod_strings;
$SubPanel->setContactsList($focus);
$SubPanel->ProcessSubPanelListView('replace_file_name',$mod_strings, '');

?>
