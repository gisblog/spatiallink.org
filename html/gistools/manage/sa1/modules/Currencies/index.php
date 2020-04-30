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
 * $Id: index.php,v 1.14 2005/04/14 18:03:43 lam Exp $
 ********************************************************************************/

require_once('include/ListView/ListView.php');
require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('include/ListView/ListView.php');

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once ($theme_path."layout_utils.php");


global $mod_strings;
global $app_list_strings;
global $app_strings;
global $current_user, $log, $focus;


if($current_user->is_admin){
require_once('modules/Currencies/ListCurrency.php');
require_once('modules/Currencies/Currency.php');
$focus = new Currency();
$lc = new ListCurrency();
$lc->handleAdd();

if(isset($_REQUEST['merge']) && $_REQUEST['merge'] == 'true'){
	$isMerge = true;
	
}
if(isset($_REQUEST['domerge'])){
	$currencies = $_REQUEST['mergecur'];
	
	require_once('modules/Opportunities/Opportunity.php');
	$opp = new Opportunity();
	$opp->update_currency_id($currencies, $_REQUEST['mergeTo'] );








	foreach($currencies as $cur){
		if($cur != $_REQUEST['mergeTo'])
		$focus->mark_deleted($cur);
	}
}
$lc->lookupCurrencies();
if (isset($focus->id)) $focus_id = $focus->id;
else $focus_id='';
$merge_button = '';
$pretable = '';
if((isset($_REQUEST['doAction']) && $_REQUEST['doAction'] == 'merge') || (isset($isMerge) && !$isMerge)){
$merge_button = '<form name= "MERGE" method="POST" action="index.php"><input type="hidden" name="module" value="Currencies"><input type="hidden" name="record" value="'.$focus_id.'"><input type="hidden" name="action" value="index"><input type="hidden" name="merge" value="true"><input title="'.$mod_strings['LBL_MERGE'].'" accessKey="'.$mod_strings['LBL_MERGE'].'" class="button"  type="submit" name="button" value="'.$mod_strings['LBL_MERGE'].'" ></form>';
}
if(isset($isMerge) && $isMerge){
	$currencyList = new ListCurrency();
	$listoptions = $currencyList->getSelectOptions();
	$pretable =  <<<EOQ
		<form name= "MERGE" method="POST" action="index.php">
			<input type="hidden" name="module" value="Currencies">
			
			<input type="hidden" name="action" value="index">
		<table width="100%" cellspacing="0" cellpadding="0" border="0" class="tabForm">
			<tr><td><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr>
			<td>{$mod_strings['LBL_MERGE_TXT']}</td><td width='20%'><select name='mergeTo'>{$listoptions}</select></td>
			</tr>
			<tr><td></td><td><input title="{$mod_strings['LBL_MERGE']}" accessKey="{$mod_strings['LBL_MERGE']}" class="button" type="submit" name="domerge" value="{$mod_strings['LBL_MERGE']}" > 
		<input title="{$app_strings['LBL_CANCEL_BUTTON_TITLE']}" accessKey="{$app_strings['LBL_CANCEL_BUTTON_KEY']}" class="button"  type="submit" name="button" value="{$app_strings['LBL_CANCEL_BUTTON_LABEL']}" > </td></tr>
			</table></td></tr></table><br>
EOQ;
	

}
$edit_botton = '<form name="EditView" method="POST" action="index.php" >';
			$edit_botton .= '<input type="hidden" name="module" value="Currencies">';
			$edit_botton .= '<input type="hidden" name="record" value="'.$focus_id.'">';
			$edit_botton .= '<input type="hidden" name="action">';
			$edit_botton .= '<input type="hidden" name="edit">';
			$edit_botton .= '<input type="hidden" name="return_module" value="Currencies">';
			$edit_botton .= '<input type="hidden" name="return_action" value="index">';
			$edit_botton .= '<input type="hidden" name="return_id" value="">';
		$edit_botton .= '<input title="'.$app_strings['LBL_SAVE_BUTTON_TITLE'].'" accessKey="'.$app_strings['LBL_SAVE_BUTTON_KEY'].'" class="button" onclick="this.form.edit.value=\'true\';this.form.action.value=\'index\';return check_form(\'EditView\');" type="submit" name="button" value="'.$app_strings['LBL_SAVE_BUTTON_LABEL'].'" > ';
		$edit_botton .= '<input title="'.$app_strings['LBL_CLEAR_BUTTON_TITLE'].'" accessKey="'.$app_strings['LBL_CLEAR_BUTTON_KEY'].'" class="button" onclick="this.form.edit.value=\'false\';this.form.action.value=\'index\';" type="submit" name="button" value="'.$app_strings['LBL_CLEAR_BUTTON_LABEL'].'" > ';
$header_text = '';
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){	
		$header_text = "&nbsp;<a href='index.php?action=index&module=DynamicLayout&from_action=ListView&from_module=".$_REQUEST['module'] ."'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>";
	}
$ListView = new ListView();
$ListView->initNewXTemplate( 'modules/Currencies/ListView.html',$mod_strings);
$ListView->xTemplateAssign('PRETABLE', $pretable);
$ListView->xTemplateAssign('POSTTABLE', '</form>');
$ListView->xTemplateAssign("DELETE_INLINE_PNG",  get_image($image_path.'delete_inline','align="absmiddle" alt="'.$app_strings['LNK_DELETE'].'" border="0"'));
$ListView->setHeaderTitle($mod_strings['LBL_LIST_FORM_TITLE']. $header_text );
$ListView->setHeaderText($merge_button);
$ListView->processListView($lc->list, "main", "CURRENCY");

if(isset($_GET['record']) && !empty($_GET['record']) && !isset($_POST['edit'])) { $focus->retrieve($_GET['record']);}
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){	
		$header_text = "&nbsp;<a href='index.php?action=index&module=DynamicLayout&from_action=EditView&from_module=".$_REQUEST['module'] ."'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>";
}
echo get_form_header($mod_strings['LBL_CURRENCY']." ".$focus->name . $header_text,$edit_botton , false); 
$xtpl=new XTemplate ('modules/Currencies/EditView.html');

	$xtpl->assign("MOD", $mod_strings);
	$xtpl->assign("APP", $app_strings);
	
	if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
	if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
	if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
	
	$xtpl->assign("THEME", $theme);
	$xtpl->assign("IMAGE_PATH", $image_path);
	$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
	$xtpl->assign("JAVASCRIPT", get_set_focus_js());
	$xtpl->assign("ID", $focus->id);
	$xtpl->assign('NAME', $focus->name);
	$xtpl->assign('STATUS', $focus->status);
	$xtpl->assign('ISO4217', $focus->iso4217);
	$xtpl->assign('CONVERSION_RATE', $focus->conversion_rate);
	$xtpl->assign('SYMBOL', $focus->symbol);
	$xtpl->assign('STATUS_OPTIONS', get_select_options_with_id($mod_strings['currency_status_dom'], $focus->status));
	
	//if (empty($focus->list_order)) $xtpl->assign('LIST_ORDER', count($focus->get_manufacturers(false,'All'))+1); 
	//else $xtpl->assign('LIST_ORDER', $focus->list_order);
	
	$xtpl->parse("main");
	$xtpl->out("main");
	require_once('include/javascript/javascript.php');
	$javascript = new javascript();
	$javascript->setFormName('EditView');
	$javascript->setSugarBean($focus);
	$javascript->addAllFields('');
	echo $javascript->getScript();
			}else{
				echo 'Admin\'s Only';	
			}

?>
