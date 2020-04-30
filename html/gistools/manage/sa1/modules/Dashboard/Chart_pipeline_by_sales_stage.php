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
 * $Id: Chart_pipeline_by_sales_stage.php,v 1.53 2005/04/14 18:03:43 lam Exp $
 * Description:  returns HTML for client-side image map.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('include/utils.php');
require_once('include/logging.php');
require_once("modules/Opportunities/Charts.php");
global $app_list_strings, $current_language, $sugar_config, $currentModule, $action, $theme;
$current_module_strings = return_module_language($current_language, 'Dashboard');

$log = LoggerManager::getLogger('pipeline_by_sales_stage');

if (isset($_REQUEST['pbss_refresh'])) { $refresh = $_REQUEST['pbss_refresh']; }
else { $refresh = false; }

//get the dates to display
$user_date_start = $current_user->getPreference('pbss_date_start');

if (!empty($user_date_start) && !isset($_REQUEST['pbss_date_start'])) {
	$date_start = $user_date_start;
	$log->debug("USER PREFERENCES['pbss_date_start'] is:");
	$log->debug($user_date_start);
}
elseif (isset($_REQUEST['pbss_date_start']) && $_REQUEST['pbss_date_start'] != '') {
	$date_start = $_REQUEST['pbss_date_start'];
	$current_user->setPreference('pbss_date_start', $_REQUEST['pbss_date_start']);
	$log->debug("_REQUEST['pbss_date_start'] is:");
	$log->debug($_REQUEST['pbss_date_start']);
	$log->debug("USER PREFERENCES['pbss_date_start'] is:");
	$log->debug($current_user->getPreference('pbss_date_start'));
}
else {
	$date_start = date("Y-m-d", time());
}
$user_date_end = $current_user->getPreference('pbss_date_end');

if (!empty($user_date_end) && !isset($_REQUEST['pbss_date_end'])) {
	$date_end = $user_date_end;
	$log->debug("USER PREFERENCES['pbss_date_end'] is:");
	$log->debug($user_date_end);
}
elseif (isset($_REQUEST['pbss_date_end']) && $_REQUEST['pbss_date_end'] != '') {
	$date_end = $_REQUEST['pbss_date_end'];
	$current_user->setPreference('pbss_date_end', $_REQUEST['pbss_date_end']);
	$log->debug("_REQUEST['pbss_date_end'] is:");
	$log->debug($_REQUEST['pbss_date_end']);
	$log->debug("USER PREFERENCES['pbss_date_end'] is:");
	$log->debug( $current_user->getPreference('pbss_date_end'));
}
else {
	$date_end = '2100-01-01';
}

$tempx = array();
$datax = array();
$user_tempx = $current_user->getPreference('pbss_sales_stages');
//get list of sales stage keys to display
if (!empty($user_tempx) && count($user_tempx) > 0 && !isset($_REQUEST['pbss_sales_stages'])) {
	$tempx = $user_tempx ;
	$log->debug("USER PREFERENCES['pbss_sales_stages'] is:");
	$log->debug($user_tempx );
}
elseif (isset($_REQUEST['pbss_sales_stages']) && count($_REQUEST['pbss_sales_stages']) > 0) {
	$tempx = $_REQUEST['pbss_sales_stages'];
	$current_user->setPreference('pbss_sales_stages', $_REQUEST['pbss_sales_stages']);
	$log->debug("_REQUEST['pbss_sales_stages'] is:");
	$log->debug($_REQUEST['pbss_sales_stages']);
	$log->debug("USER PREFERENCES['pbss_sales_stages'] is:");
	$log->debug($current_user->getPreference('pbss_sales_stages'));
}

//set $datax using selected sales stage keys
if (count($tempx) > 0) {
	foreach ($tempx as $key) {
		$datax[$key] = $app_list_strings['sales_stage_dom'][$key];
	}
}
else {
	$datax = $app_list_strings['sales_stage_dom'];
}
$log->debug("datax is:");
$log->debug($datax);

$ids = array();
$new_ids = array();
$user_ids = $current_user->getPreference('pbss_ids');
//get list of user ids for which to display data
if (!empty($user_ids) && count($user_ids) != 0 && !isset($_REQUEST['pbss_ids'])) {
	$ids = $user_ids;

	$log->debug("USER PREFERENCES['pbss_ids'] is:");
	$log->debug($user_ids);
}
elseif (isset($_REQUEST['pbss_ids']) && count($_REQUEST['pbss_ids']) > 0) {
	$ids = $_REQUEST['pbss_ids'];
	$current_user->setPreference('pbss_ids', $_REQUEST['pbss_ids']);
	$log->debug("_REQUEST['pbss_ids'] is:");
	$log->debug($_REQUEST['pbss_ids']);
	$log->debug("USER PREFERENCES['pbss_ids'] is:");
	$log->debug($current_user->getPreference('pbss_ids'));
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
	$cache_file_name = $current_user->id."_".$id_md5."_".$theme."_".$id_hash."_pipeline_".$current_language."_".crc32(implode('',$datax)).$date_start.$date_end.".xml";

$log->debug("cache file name is: $cache_file_name");

$draw_chart = new charts();
$tools='<div align="right"><a href="index.php?module='.$currentModule.'&action='. $action .'&pbss_refresh=true" class="chartToolsLink">'.get_image($image_path.'refresh','alt="Refresh"  border="0" align="absmiddle"').'&nbsp;'.$current_module_strings['LBL_REFRESH'].'</a>&nbsp;&nbsp;<a href="javascript: toggleDisplay(\'pipeline_by_sales_stage_edit\');" class="chartToolsLink">'.get_image($image_path.'edit','alt="Edit"  border="0"  align="absmiddle"').'&nbsp;'. $current_module_strings['LBL_EDIT'].'</a></div>';

echo get_form_header($mod_strings['LBL_SALES_STAGE_FORM_TITLE'], $tools , false); ?>

<?php
	$cal_lang = "en";
	$cal_dateformat = parse_calendardate($app_strings['NTC_DATE_FORMAT']);

if (empty($_SESSION['pbss_sales_stages'])) $_SESSION['pbss_sales_stages'] = "";
if (empty($_SESSION['pbss_ids'])) $_SESSION['pbss_ids'] = "";


// set populate values
$puser_date_start = $current_user->getPreference('user_date_start');

?>
<p>
	<div id='pipeline_by_sales_stage_edit' style='display: none;'>
<form name='pipeline_by_sales_stage' action="index.php" method="post" >
<input type="hidden" name="module" value="<?php echo $currentModule;?>">
<input type="hidden" name="action" value="<?php echo $action;?>">
<input type="hidden" name="pbss_refresh" value="true">
<table cellpadding="0" cellspacing="0" border="0" class="chartForm" align="center">
<tr>
	<td valign='top' nowrap><b><?php echo $current_module_strings['LBL_DATE_START']?></b> <br><span class="dateFormat"><?php echo $app_strings['NTC_DATE_FORMAT']?></span></td>
	<td valign='top' ><input class="text" name="pbss_date_start" size='12' maxlength='10' id='date_start' value='<?php if (isset($date_start)) echo $date_start; ?>'>  <img src="themes/<?php echo $theme ?>/images/jscalendar.gif" alt="<?php echo $app_strings['LBL_ENTER_DATE']; ?>"  id="date_start_trigger" align="absmiddle"> </td>
</tr>
<tr>
	<td valign='top' nowrap><b><?php echo $current_module_strings['LBL_DATE_END'];?></b><br><span class="dateFormat"><?php echo $app_strings['NTC_DATE_FORMAT']?></span></td>
	<td valign='top' ><input class="text" name="pbss_date_end" size='12' maxlength='10' id='date_end' value='<?php if (isset($date_end)) echo $date_end; ?>'>  <img src="themes/<?php echo $theme ?>/images/jscalendar.gif" alt="<?php echo $app_strings['LBL_ENTER_DATE']; ?>"  id="date_end_trigger" align="absmiddle"> </td>
</tr>
<tr>
	<td valign='top' nowrap><b><?php echo $current_module_strings['LBL_SALES_STAGES'];?></b></td>
	<td valign='top' ><select name="pbss_sales_stages[]" multiple size='3'><?php echo get_select_options_with_id($app_list_strings['sales_stage_dom'],$datax); ?></select></td>
</tr>
<tr>
	<td valign='top' nowrap><b><?php echo $current_module_strings['LBL_USERS'];?></b></td>
	<td valign='top' ><select name="pbss_ids[]" multiple size='3'><?php echo  get_select_options_with_id(get_user_array(false),$ids); ?></select></td>
</tr>
<tr>
	<td align="right" colspan="2"><input class="button" onclick="return verify_chart_data(pipeline_by_sales_stage);" type="submit" title="<?php echo $app_strings['LBL_SELECT_BUTTON_TITLE']; ?>" accessKey="<?php echo $app_strings['LBL_SELECT_BUTTON_KEY']; ?>" value="<?php echo $app_strings['LBL_SELECT_BUTTON_LABEL']?>" /><input class="button" onClick="javascript: toggleDisplay('pipeline_by_sales_stage_edit');" type="button" title="<?php echo $app_strings['LBL_CANCEL_BUTTON_TITLE']; ?>" accessKey="<?php echo $app_strings['LBL_CANCEL_BUTTON_KEY'];?>" value="<?php echo $app_strings['LBL_CANCEL_BUTTON_LABEL']?>"/></td>
</tr>
</table>
</form>
<script type="text/javascript">
Calendar.setup ({
	inputField : "date_start", ifFormat : "<?php echo $cal_dateformat ?>", showsTime : false, button : "date_start_trigger", singleClick : true, step : 1
});
Calendar.setup ({
	inputField : "date_end", ifFormat : "<?php echo $cal_dateformat ?>", showsTime : false, button : "date_end_trigger", singleClick : true, step : 1
});
</script>
</div>
</p>
	<?php

// draw table


echo "<P align='center'>".$draw_chart->pipeline_by_sales_stage($datax, $date_start, $date_end, $ids, $sugar_config['tmp_dir'].$cache_file_name, $refresh)."</P>";
echo "<P align='center'><span class='chartFootnote'>".$current_module_strings['LBL_SALES_STAGE_FORM_DESC']."</span></P>";

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
<?php
echo get_validate_chart_js();
?>
