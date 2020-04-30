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
 * $Id: SubPanelSharedCalendar.php,v 1.3 2005/02/09 07:08:54 andrew Exp $
 ********************************************************************************/

/// START SHARED CALENDAR
include('modules/Calendar/templates/template_shared_calendar.php');


function SubPanelSharedCalendar(&$focus)
{

global $mod_strings;
global $current_language;
global $currentModule;

$currentModule = $_REQUEST['module'];
$temp_module = $currentModule;
$mod_strings = return_module_language($current_language,'Calendar');
$currentModule = 'Calendar';

$args = array(
"activity_focus"=>$focus,
"users"=>$focus->get_users());

template_shared_calendar($args);

$mod_strings = return_module_language($current_language,$temp_module);
$currentModule = $_REQUEST['module'];
/// END SHARED CALENDAR
}

?>
