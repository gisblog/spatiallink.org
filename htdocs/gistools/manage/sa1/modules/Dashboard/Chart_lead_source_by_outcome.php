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
 * $Id: Chart_lead_source_by_outcome.php,v 1.36 2005/04/14 18:03:43 lam Exp $
 * Description:  returns HTML for client-side image map.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('include/utils.php');
require_once('include/logging.php');
require_once("modules/Opportunities/Charts.php");
global $app_list_strings, $current_language, $sugar_config, $currentModule, $action,$theme;
$current_module_strings = return_module_language($current_language, 'Dashboard');

$log = LoggerManager::getLogger('lead_source_by_outcome');

if (isset($_REQUEST['lsbo_refresh'])) { $refresh = $_REQUEST['lsbo_refresh']; }
else { $refresh = false; }

$tempx = array();
$datax = array();
//get list of sales stage keys to display

$tempx = $current_user->getPreference('lsbo_lead_sources');
if (!empty($lsbo_lead_sources) && count($lsbo_lead_sources) > 0 && !isset($_REQUEST['lsbo_lead_sources'])) {
	$log->fatal("user->getPreference('lsbo_lead_sources') is:");
	$log->fatal($tempx);
}
elseif (isset($_REQUEST['lsbo_lead_sources']) && count($_REQUEST['lsbo_lead_sources']) > 0) {
	$tempx = $_REQUEST['lsbo_lead_sources'];
	$current_user->setPreference('lsbo_lead_sources', $_REQUEST['lsbo_lead_sources']);
	$log->fatal("_REQUEST['lsbo_lead_sources'] is:");
	$log->fatal($_REQUEST['lsbo_lead_sources']);
	$log->fatal("user->getPreference('lsbo_lead_sources') is:");
	$log->fatal($current_user->getPreference('lsbo_lead_sources'));
}
//set $datax using selected sales stage keys
if (!empty($tempx) && sizeof($tempx) > 0) {
	foreach ($tempx as $key) {
		$datax[$key] = $app_list_strings['lead_source_dom'][$key];
	}
}
else {
	$datax = $app_list_strings['lead_source_dom'];
}

$ids =$current_user->getPreference('lsbo_ids');
//get list of user ids for which to display data
if (!empty($ids) && count($ids) != 0 && !isset($_REQUEST['lsbo_ids'])) {
	$log->debug("_SESSION['lsbo_ids'] is:");
	$log->debug($ids);
}
elseif (isset($_REQUEST['lsbo_ids']) && count($_REQUEST['lsbo_ids']) > 0) {
	$ids = $_REQUEST['lsbo_ids'];
	$current_user->setPreference('lsbo_ids', $_REQUEST['lsbo_ids']);
	$log->debug("_REQUEST['lsbo_ids'] is:");
	$log->debug($_REQUEST['lsbo_ids']);
	$log->debug("user->getPreference('lsbo_ids') is:");
	$log->debug($current_user->getPreference('lsbo_ids'));
}
else {
	$ids = get_user_array(false);
	$ids = array_keys($ids);
}

//create unique prefix based on selected users for image files
$id_hash = '1';
if (isset($ids)) {
	sort($ids);
	$id_hash = crc32(implode('',$ids));
	if($id_hash < 0)
	{
        $id_hash = $id_hash * -1;
	}

}
$log->debug("ids is:");
$log->debug($ids);
$id_md5 = substr(md5($current_user->id),0,9);
$cache_file_name = $current_user->id."_".$id_md5."_".$theme."_".$id_hash."_lead_source_by_outcome_".$current_language."_".crc32(implode('',$datax)).".xml";
$log->debug("cache file name is: $cache_file_name");

$draw_chart = new charts();
$tools='<div align="right"><a href="index.php?module='.$currentModule.'&action='. $action .'&lsbo_refresh=true" class="chartToolsLink">'.get_image($image_path.'refresh','alt="Refresh"  border="0" align="absmiddle"').'&nbsp;'.$current_module_strings['LBL_REFRESH'].'</a>&nbsp;&nbsp;<a href="javascript: toggleDisplay(\'lsbo_edit\');" class="chartToolsLink">'.get_image($image_path.'edit','alt="Edit"  border="0"  align="absmiddle"').'&nbsp;'. $current_module_strings['LBL_EDIT'].'</a></div>';
?>

<?php
echo get_form_header($mod_strings['LBL_LEAD_SOURCE_BY_OUTCOME'],$tools,false);

if (empty($_SESSION['lsbo_ids'])) $_SESSION['lsbo_ids'] = "";
?>

<p>
<div id='lsbo_edit' style='display: none;'>
<form action="index.php" method="post" >
<input type="hidden" name="module" value="<?php echo $currentModule;?>">
<input type="hidden" name="action" value="<?php echo $action;?>">
<input type="hidden" name="lsbo_refresh" value="true">
<table cellpadding="0" cellspacing="0" border="0" class="chartForm" align="center">
<tr>
	<td valign='top' nowrap><b><?php echo $current_module_strings['LBL_LEAD_SOURCES'];?></b></td>
	<td valign='top'><select name="lsbo_lead_sources[]" multiple size='3'><?php echo get_select_options_with_id($app_list_strings['lead_source_dom'],$datax); ?></select></td>
</tr>

<tr>
	<td valign='top' nowrap><b><?php echo $current_module_strings['LBL_USERS'];?></b></td>
	<td valign='top'><select name="lsbo_ids[]" multiple size='3'><?php echo get_select_options_with_id(get_user_array(false),$ids); ?></select></td>
</tr>

<tr>
	<td align="right" colspan="2"> <input class="button" type="submit" title="<?php echo $app_strings['LBL_SELECT_BUTTON_TITLE']; ?>" accessKey="<?php echo $app_strings['LBL_SELECT_BUTTON_KEY']; ?>" value="<?php echo $app_strings['LBL_SELECT_BUTTON_LABEL']?>" /><input class="button" onClick="javascript: toggleDisplay('lsbo_edit');" type="button" title="<?php echo $app_strings['LBL_CANCEL_BUTTON_TITLE']; ?>" accessKey="<?php echo $app_strings['LBL_CANCEL_BUTTON_KEY'];?>" value="<?php echo $app_strings['LBL_CANCEL_BUTTON_LABEL']?>"/></td>
	</tr>
</table>
</form>
</div>
</p>
<?php

echo "<p align='center'>".$draw_chart->lead_source_by_outcome($datax, $ids, $sugar_config['tmp_dir'].$cache_file_name, $refresh)."</p>";
echo "<P align='center'><span class='chartFootnote'>".$current_module_strings['LBL_LEAD_SOURCE_BY_OUTCOME_DESC']."</span></P>";


	if (file_exists($sugar_config['tmp_dir'].$cache_file_name)) {
		$file_date = date('Y-m-d H:i', filemtime($sugar_config['tmp_dir'].$cache_file_name));
	}
	else {
		$file_date = '';
	}
?>
<span class='chartFootnote'>
<p align="right"><i><?php  echo $current_module_strings['LBL_CREATED_ON'].' '.$file_date; ?></i></p>
</span>
