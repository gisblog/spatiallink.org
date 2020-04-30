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
 * $Id: DetailView.php,v 1.64 2005/04/20 01:32:56 joey Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Opportunities/Opportunity.php');
require_once('modules/Opportunities/Forms.php');


global $mod_strings;
global $app_strings;
global $app_list_strings;

$focus =& new Opportunity();

if(!empty($_REQUEST['record'])) {
    $result = $focus->retrieve($_REQUEST['record']);
    if($result == null)
    {
    	sugar_die("Error retrieving record.  You may not be authorized to view this record.");
    }
}
else {
	header("Location: index.php?module=Opportunities&action=index");
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}
echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->name, true);
echo "\n</p>\n";
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("Opportunity detail view");

$xtpl=new XTemplate ('modules/Opportunities/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

$xtpl->assign("THEME", $theme);
$xtpl->assign("GRIDLINE", $gridline);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("ACCOUNT_NAME", $focus->account_name);
$xtpl->assign("ACCOUNT_ID", $focus->account_id);
$xtpl->assign("ASSIGNED_TO", $focus->assigned_user_name);
$xtpl->assign("LEAD_SOURCE", $app_list_strings['lead_source_dom'][$focus->lead_source]);
$xtpl->assign("NAME", $focus->name);
$xtpl->assign("TYPE", $app_list_strings['opportunity_type_dom'][$focus->opportunity_type]);
if ($focus->amount != '') $xtpl->assign("AMOUNT", $focus->amount);
$xtpl->assign("DATE_ENTERED", $focus->date_entered);
$xtpl->assign("DATE_CLOSED", $focus->date_closed);
$xtpl->assign("NEXT_STEP", $focus->next_step);
$xtpl->assign("SALES_STAGE", $app_list_strings['sales_stage_dom'][$focus->sales_stage]);
$xtpl->assign("PROBABILITY", $focus->probability);
$xtpl->assign("DESCRIPTION", nl2br($focus->description));
$xtpl->assign("DATE_MODIFIED", $focus->date_modified);
$xtpl->assign("DATE_ENTERED", $focus->date_entered);

$xtpl->assign("CREATED_BY", $focus->created_by_name);
$xtpl->assign("MODIFIED_BY", $focus->modified_by_name);

require_once('modules/Currencies/Currency.php');
	$currency  = new Currency();
if(isset($focus->currency_id) && !empty($focus->currency_id))
{
	$currency->retrieve($focus->currency_id);
	if( $currency->deleted != 1){
		$xtpl->assign("CURRENCY", $currency->name .' : '.$currency->symbol );
	}else $xtpl->assign("CURRENCY", $currency->getDefaultCurrencyName() .' : $');
}else{

	$xtpl->assign("CURRENCY", $currency->getDefaultCurrencyName() .' : $');

}
global $current_user;
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){

	$xtpl->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action=".$_REQUEST['action'] ."&from_module=".$_REQUEST['module'] ."&record=".$_REQUEST['record']. "'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>");
}

// adding custom fields:
require_once('modules/DynamicFields/templates/Files/DetailView.php');







$xtpl->parse("main.open_source");





$xtpl->parse("main");
$xtpl->out("main");

echo "</p>\n";
$sub_xtpl = $xtpl;
$old_contents = ob_get_contents();
ob_end_clean();


if(array_key_exists('Activities', $modListHeader))
{
if($sub_xtpl->var_exists('subpanel', 'SUBACTIVITIES')){
ob_start();
echo "<p>\n";
// Now get the list of activities that match this opportunity.
$focus_tasks_list = & $focus->get_tasks();
$focus_meetings_list = & $focus->get_meetings();
$focus_calls_list = & $focus->get_calls();
$focus_emails_list = & $focus->get_emails();
$focus_notes_list = & $focus->get_notes();
include('modules/Activities/SubPanelView.php');
echo "</p>\n";
$subactivities=  ob_get_contents();
ob_end_clean();
}
}

if(array_key_exists('Leads', $modListHeader))
{
if($sub_xtpl->var_exists('subpanel', 'SUBLEADS')){
ob_start();
echo "<p>\n";
$focus_list = & $focus->get_leads();
include('modules/Leads/SubPanelView.php');
echo "</p>\n";
$subleads=  ob_get_contents();
ob_end_clean();
}
}

if(array_key_exists('Contacts', $modListHeader))
{
if($sub_xtpl->var_exists('subpanel', 'SUBCONTACTS')){
ob_start();
echo "<p>\n";
include('modules/Contacts/SubPanelViewContactsAndUsers.php');
$SubPanel = new SubPanelViewContactsAndUsers();
$SubPanel->setFocus($focus);
$SubPanel->setHideUsers(true);
$SubPanel->ProcessSubPanelListView( 'modules/Contacts/SubPanelViewOpportunity.html',$mod_strings, $action);
echo "</p>\n";
$subcontacts=  ob_get_contents();
ob_end_clean();
}
}


















if(array_key_exists('Project', $modListHeader))
{
	if($sub_xtpl->var_exists('subpanel', 'SUB_PROJECT'))
	{
		ob_start();
		echo "<p>\n";

		// Now get the list of member accounts that match this one.
		$focus_list = & $focus->get_projects();
		include('modules/Project/SubPanelView.php');
		echo "</p>\n";
		$sub_project =  ob_get_contents();
		ob_end_clean();
	}
}


ob_start();
echo $old_contents;
if(!empty($subactivities))$sub_xtpl->assign('SUBACTIVITIES', $subactivities);
if(!empty($subleads))$sub_xtpl->assign('SUBLEADS', $subleads);
if(!empty($subcontacts))$sub_xtpl->assign('SUBCONTACTS', $subcontacts);



if(!empty($sub_project))$sub_xtpl->assign('SUB_PROJECT', $sub_project);
$sub_xtpl->parse("subpanel");
$sub_xtpl->out("subpanel");
?>
