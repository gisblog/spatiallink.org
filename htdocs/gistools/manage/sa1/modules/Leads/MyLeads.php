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
require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('modules/Leads/Lead.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('include/ListView/ListView.php');
global $app_strings;
global $app_list_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'Leads');

$ListView = new ListView();
$seedLeads = new Lead();
$header_text = '';
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){	
		$header_text = "&nbsp;<a href='index.php?action=index&module=DynamicLayout&from_action=MyLeads&from_module=Leads'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>";
}
$where = "assigned_user_id='". $current_user->id ."' and (leads.status is NULL or (leads.status!='Converted' and leads.status!='Dead' and leads.status!='recycled')) ";
$ListView->initNewXTemplate( 'modules/Leads/MyLeads.html',$current_module_strings);
$ListView->setHeaderTitle($current_module_strings['LBL_LIST_MY_LEADS'] . $header_text);
$ListView->setQuery($where, "", "leads.date_entered desc", "LEAD");
$ListView->processListView($seedLeads, "main", "LEAD");
?>
