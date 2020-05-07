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
 * $Id: Chart_outcome_by_month.php,v 1.45 2005/04/14 18:03:43 lam Exp $
 * Description:  returns HTML for client-side image map.
 ********************************************************************************/

require_once('include/utils.php');
require_once('include/logging.php');
require_once("modules/Opportunities/Charts.php");
require_once("modules/Dashboard/Forms.php");
global $app_list_strings, $current_language, $sugar_config, $currentModule, $action, $theme;
$current_module_strings = return_module_language($current_language, 'Dashboard');

$log = LoggerManager::getLogger('outcome_by_month');

if (isset($_REQUEST['obm_refresh'])) { $refresh = $_REQUEST['obm_refresh']; }
else { $refresh = false; }

$date_start = array();
$datax = array();
//get the dates to display
$user_date_start = $current_user->getPreference('obm_date_start');
if (!empty($user_date_start)  && !isset($_REQUEST['obm_date_start'])) {
	$date_start =$user_date_start;
	$log->debug("USER PREFERENCES['obm_date_start'] is:");
	$log->debug($user_date_start);
}
elseif (isset($_REQUEST['obm_year']) && $_REQUEST['obm_year'] != '') {
	$date_start = $_REQUEST['obm_year'].'-01-01';
	$current_user->setPreference('obm_date_start', $date_start);
	$log->debug("_REQUEST['obm_date_start'] is:");
	$log->debug($_REQUEST['obm_date_start']);
	$log->debug("_SESSION['obm_date_start'] is:");
	$log->debug($current_user->getPreference('obm_date_start'));
}
else {
	$date_start = date('Y').'-01-01';
}
$user_date_end = $current_user->getPreference('obm_date_end');
if (!empty($user_date_end) && !isset($_REQUEST['obm_date_end'])) {
	$date_end =$user_date_end;
	$log->debug("USER PREFERENCES['obm_date_end'] is:");
	$log->debug($date_end);
}
elseif (isset($_REQUEST['obm_year']) && $_REQUEST['obm_year'] != '') {
	$date_end = $_REQUEST['obm_year'].'-12-31';
	$current_user->setPreference('obm_date_end', $date_end );
	$log->debug("_REQUEST['obm_date_end'] is:");
	$log->debug($_REQUEST['obm_date_end']);
	$log->debug("USER PREFERENCES['obm_date_end'] is:");
	$log->debug($current_user->getPreference('obm_date_end'));
}
else {
	$date_end = date('Y').'-12-31';
}

$ids = array();
//get list of user ids for which to display data
$user_ids = $current_user->getPreference('obm_ids');
if (!empty($user_ids) && count($user_ids) != 0 && !isset($_REQUEST['obm_ids'])) {
	$ids = $user_ids;
	$log->debug("USER PREFERENCES['obm_ids'] is:");
	$log->debug($user_ids);
}
elseif (isset($_REQUEST['obm_ids']) && count($_REQUEST['obm_ids']) > 0) {
	$ids = $_REQUEST['obm_ids'];
	$current_user->setPreference('obm_ids', $_REQUEST['obm_ids']);
	$log->debug("_REQUEST['obm_ids'] is:");
	$log->debug($_REQUEST['obm_ids']);
	$log->debug("USER PREFRENCES['obm_ids'] is:");
	$log->debug($current_user->getPreference('obm_ids'));
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
$cache_file_name = $current_user->id."_".$id_md5."_".$theme."_".$id_hash."_outcome_by_month_".$current_language."_".crc32($date_start.$date_end).".xml";
$log->debug("cache file name is: $cache_file_name");


$draw_chart = new charts();
$tools='<div align="right"><a href="index.php?module='.$currentModule.'&action='. $action .'&obm_refresh=true" class="chartToolsLink">'.get_image($image_path.'refresh','alt="Refresh"  border="0" align="absmiddle"').'&nbsp;'.$current_module_strings['LBL_REFRESH'].'</a>&nbsp;&nbsp;<a href="javascript: toggleDisplay(\'outcome_by_month_edit\');" class="chartToolsLink">'.get_image($image_path.'edit','alt="Edit"  border="0"  align="absmiddle"').'&nbsp;'. $current_module_strings['LBL_EDIT'].'</a></div>';
?>
	<?php echo get_form_header($mod_strings['LBL_YEAR_BY_OUTCOME'],$tools,false);?>

<?php
	$cal_lang = "en";
	$cal_dateformat = parse_calendardate($app_strings['NTC_DATE_FORMAT']);

if (empty($_SESSION['obm_ids'])) $_SESSION['obm_ids'] = "";
?>
<p>
<div id='outcome_by_month_edit' style='display: none;'>
<form name="outcome_by_month" action="index.php" method="post" >
<input type="hidden" name="module" value="<?php echo $currentModule;?>">
<input type="hidden" name="action" value="<?php echo $action;?>">
<input type="hidden" name="obm_refresh" value="true">
<input type="hidden" name="obm_date_start" value="<?php if (isset($_SESSION['obm_date_start'])) echo $_SESSION['obm_date_start']?>">
<input type="hidden" name="obm_date_end" value="<?php if (isset($_SESSION['obm_date_end'])) echo $_SESSION['obm_date_end']?>">
<table cellpadding="0" cellspacing="0" border="0" class="chartForm" align="center">
<tr>
	<td valign='top' nowrap ><b><?php echo $current_module_strings['LBL_YEAR']?></b><br><span class="dateFormat"><?php echo $app_strings['NTC_YEAR_FORMAT']?></span></td>
	<td valign='top' ><input class="text" name="obm_year" size='12' maxlength='10' id='obm_year'  value='<?php if (isset($date_start)) echo substr($date_start,0,4)?>'>&nbsp;&nbsp;</td>
	<td valign='top'><b><?php echo $current_module_strings['LBL_USERS'];?></b></td>
	<td valign='top'><select name="obm_ids[]" multiple size='3'><?php echo get_select_options_with_id(get_user_array(false),$ids); ?></select></td>
	<td align="right" valign="top"><input class="button" onclick="return verify_chart_data(outcome_by_month);" type="submit" title="<?php echo $app_strings['LBL_SELECT_BUTTON_TITLE']; ?>" accessKey="<?php echo $app_strings['LBL_SELECT_BUTTON_KEY']; ?>" value="<?php echo $app_strings['LBL_SELECT_BUTTON_LABEL']?>" /><input class="button" onClick="javascript: toggleDisplay('outcome_by_month_edit');" type="button" title="<?php echo $app_strings['LBL_CANCEL_BUTTON_TITLE']; ?>" accessKey="<?php echo $app_strings['LBL_CANCEL_BUTTON_KEY'];?>" value="<?php echo $app_strings['LBL_CANCEL_BUTTON_LABEL']?>"/></td>
</tr>
</table>
</form>

</div>
</p>
<?php
// draw chart
echo "<p align='center'>".$draw_chart->outcome_by_month($date_start, $date_end, $ids, $sugar_config['tmp_dir'].$cache_file_name, $refresh)."</p>";
echo "<P align='center'><span class='chartFootnote'>".$current_module_strings['LBL_MONTH_BY_OUTCOME_DESC']."</span></P>";



?>


<?php
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
