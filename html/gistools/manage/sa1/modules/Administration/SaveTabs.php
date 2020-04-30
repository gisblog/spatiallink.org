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
 * $Id: SaveTabs.php,v 1.3 2005/02/25 04:34:58 majed Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once("modules/Administration/Administration.php");

global $current_user;

if (!is_admin($current_user)) sugar_die("Unauthorized access to administration.");



require_once('modules/MySettings/TabController.php');


$tabs_def = urldecode($_REQUEST['display_tabs_def']);
$DISPLAY_ARR = array();
parse_str($tabs_def,$DISPLAY_ARR);
$tabs = new TabController();
$tabs->set_system_tabs($DISPLAY_ARR['display_tabs']);
require_once("modules/MySettings/TabController.php");
$controller = new TabController();	
$controller->set_users_can_edit(isset($_REQUEST['user_edit_tabs']) && $_REQUEST['user_edit_tabs'] == 1);

header("Location: index.php?action={$_POST['return_action']}&module={$_POST['return_module']}");

?>
