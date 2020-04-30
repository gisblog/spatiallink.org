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
 * $Id: DetailView.php,v 1.10.2.1 2005/05/05 21:00:16 joey Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Campaigns/Campaign.php');
require_once('modules/Campaigns/Forms.php');


global $mod_strings;
global $app_strings;
global $app_list_strings;

$focus =& new Campaign();

if(!empty($_REQUEST['record'])) {
    $result = $focus->retrieve($_REQUEST['record']);
    if($result == null)
    {
    	sugar_die("Error retrieving record.  You may not be authorized to view this record.");
    }
}
else {
	header("Location: index.php?module=Campaigns&action=index");
}

echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->name, true);
echo "\n</p>\n";
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("Campaign detail view");

$xtpl=new XTemplate ('modules/Campaigns/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

$xtpl->assign("THEME", $theme);
$xtpl->assign("GRIDLINE", $gridline);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("ASSIGNED_TO", $focus->assigned_user_name);
$xtpl->assign("STATUS", $app_list_strings['campaign_status_dom'][$focus->status]);
$xtpl->assign("NAME", $focus->name);
$xtpl->assign("TYPE", $app_list_strings['campaign_type_dom'][$focus->campaign_type]);
$xtpl->assign("START_DATE", $focus->start_date);
$xtpl->assign("END_DATE", $focus->end_date);

$xtpl->assign("BUDGET", sprintf("%4.2f", $focus->budget));
$xtpl->assign("ACTUAL_COST", sprintf("%4.2f", $focus->actual_cost));
$xtpl->assign("EXPECTED_COST", sprintf("%4.2f", $focus->expected_cost));
$xtpl->assign("EXPECTED_REVENUE", sprintf("%4.2f", $focus->expected_revenue));


$xtpl->assign("OBJECTIVE", nl2br($focus->objective));
$xtpl->assign("CONTENT", nl2br($focus->content));
$xtpl->assign("DATE_MODIFIED", $focus->date_modified);
$xtpl->assign("DATE_ENTERED", $focus->date_entered);

$xtpl->assign("CREATED_BY", $focus->created_by_name);
$xtpl->assign("MODIFIED_BY", $focus->modified_by_name);
$xtpl->assign("TRACKER_URL", $sugar_config['site_url'] . '/campaign_tracker.php?track=' . $focus->tracker_key);
$xtpl->assign("TRACKER_COUNT", intval($focus->tracker_count));
$xtpl->assign("TRACKER_TEXT", $focus->tracker_text);
$xtpl->assign("REFER_URL", $focus->refer_url);


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
$old_contents = ob_get_clean();

echo $old_contents;

if($sub_xtpl->var_exists('subpanel', 'SUBPROSPECTLISTS'))
{
	ob_start();
	echo "<p>\n";
	$focus_list =  & $focus->get_prospect_lists();
	include('modules/ProspectLists/SubPanelView.php');
	echo "</p>\n";
	$subprospectlists = ob_get_clean();
}

if($sub_xtpl->var_exists('subpanel', 'SUBEMAILMARKETING'))
{
	ob_start();
	echo "<p>\n";
	$focus_list =  & $focus->get_email_campaigns();
	include('modules/EmailMarketing/SubPanelView.php');
	echo "</p>\n";
	$subemailmarketing = ob_get_clean();
}


if(!empty($subprospectlists))
	$sub_xtpl->assign('SUBPROSPECTLISTS', $subprospectlists);
if(!empty($subemailmarketing) && ($focus->campaign_type == 'Email'))
	$sub_xtpl->assign('SUBEMAILMARKETING', $subemailmarketing);


$sub_xtpl->parse("subpanel");
$sub_xtpl->out("subpanel");

echo "</p>\n";


?>
