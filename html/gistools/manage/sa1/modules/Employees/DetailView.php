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
 * $Id: DetailView.php,v 1.4 2005/04/28 06:10:22 clint Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Employees/Employee.php');
require_once('include/utils.php');

global $current_user;
global $theme;
global $app_strings;
global $mod_strings;

$focus =& new Employee();

if(!empty($_REQUEST['record'])) {
    $result = $focus->retrieve($_REQUEST['record']);
    if($result == null)
    {
    	sugar_die("Error retrieving record.  You may not be authorized to view this record.");
    }
}
else {
	header("Location: index.php?module=Employees&action=ListView");
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}

if(isset($_REQUEST['reset_preferences'])){
	$current_user->resetPreferences();
}
echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->first_name." ".$focus->last_name, true);
echo "\n</p>\n";
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');


$log->info("Employee detail view");

$xtpl=new XTemplate ('modules/Employees/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

$xtpl->assign("THEME", $theme);
$xtpl->assign("GRIDLINE", $gridline);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("FIRST_NAME", $focus->first_name);
$xtpl->assign("LAST_NAME", $focus->last_name);

// adding custom fields:
require_once('modules/DynamicFields/templates/Files/DetailView.php');

if( is_admin($current_user) ) {

	$employee_status  = '<tr>';
	$employee_status .= '<td valign="top" class="tabDetailViewDL"><slot>'.$mod_strings['LBL_EMPLOYEE_STATUS'].'</slot></td>';
	$employee_status .= '<td valign="top" class="tabDetailViewDF"><slot>'.$focus->employee_status.'</slot></td>';
	$employee_status .= '<td valign="top" class="tabDetailViewDL"><slot>&nbsp;</slot></td>';
	$employee_status .= '<td valign="top" class="tabDetailViewDF"><slot>&nbsp;</slot></td>';
	$employee_status .= '</tr>';

} else { $employee_status = ''; }
$xtpl->assign("EMPLOYEE_STATUS", $employee_status);

$buttons = '';
//set the edit button if user is admin or owner of record
if ( (is_admin($current_user) || $_REQUEST['record'] == $current_user->id) ) {
	$buttons = "<input title='".$app_strings['LBL_EDIT_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_EDIT_BUTTON_KEY']."' class='button' onclick=\"this.form.return_module.value='Employees'; this.form.return_action.value='DetailView'; this.form.return_id.value='$focus->id'; this.form.action.value='EditView';\" type='submit' name='Edit' value='  ".$app_strings['LBL_EDIT_BUTTON_LABEL']."  '>  ";
}
if (is_admin($current_user)) {
	$buttons .= "<input title='".$app_strings['LBL_DUPLICATE_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_DUPLICATE_BUTTON_KEY']."' class='button' onclick=\"this.form.return_module.value='Employees'; this.form.return_action.value='DetailView'; this.form.isDuplicate.value='true'; this.form.action.value='EditView'\" type='submit' name='Duplicate' value=' ".$app_strings['LBL_DUPLICATE_BUTTON_LABEL']." '>";
}
//set the 'create user button if user is admin and record user_name=''
if ( (is_admin($current_user) && empty($focus->user_name)) ) {
	$buttons .= "&nbsp;<input title='".$mod_strings['LBL_CREATE_USER_BUTTON_TITLE']."' accessKey='".$mod_strings['LBL_CREATE_USER_BUTTON_KEY']."' class='button' onclick=\"this.form.module.value='Users'; this.form.return_module.value='Employees'; this.form.return_action.value='DetailView'; this.form.return_id.value='$focus->id'; this.form.action.value='EditView'\" type='submit' name='Create User' value='  ".$mod_strings['LBL_CREATE_USER_BUTTON_LABEL']."  '>  ";
}
if (isset($buttons) AND $buttons !='') $xtpl->assign("BUTTONS", $buttons);

if(isset($_SERVER['QUERY_STRING'])) $the_query_string = $_SERVER['QUERY_STRING'];
else $the_query_string = '';

/*
require_once("include/templates/TemplateGroupChooser.php");
require_once("modules/MySettings/TabController.php");
$chooser = new TemplateGroupChooser();
$controller = new TabController();
if(is_admin($current_user) || $controller->get_users_can_edit()){
$chooser->args['id'] = 'edit_tabs';
$chooser->args['values_array'] = $controller->get_tabs($focus);
$chooser->args['left_name'] = 'display_tabs';
$chooser->args['right_name'] = 'hide_tabs';
$chooser->args['left_label'] =  $mod_strings['LBL_DISPLAY_TABS'];
$chooser->args['right_label'] =  $mod_strings['LBL_HIDE_TABS'];
$chooser->args['title'] =  $mod_strings['LBL_EDIT_TABS'];
$chooser->args['disable'] = true;

foreach ($chooser->args['values_array'][0] as $key=>$value)
{
$chooser->args['values_array'][0][$key] = $app_list_strings['moduleList'][$key];
}
foreach ($chooser->args['values_array'][1] as $key=>$value)
{
$chooser->args['values_array'][1][$key] = $app_list_strings['moduleList'][$key];
}


$xtpl->assign("TAB_CHOOSER", $chooser->display());
$xtpl->assign("CHOOSE_WHICH", $mod_strings['LBL_CHOOSE_WHICH']);
$xtpl->parse("employee_info.tabchooser");
}
*/

$xtpl->parse("main");
$xtpl->out("main");

require_once('include/TimeDate.php');
$timedate = new TimeDate();
$xtpl->assign("DATEFORMAT", $sugar_config['date_formats'][$timedate->get_date_format()]);
$xtpl->assign("TIMEFORMAT", $sugar_config['time_formats'][$timedate->get_time_format()]);
$xtpl->assign("TIMEZONE", $timedate->to_display_date_time(date($timedate->get_db_date_time_format(), time()),true,true));

if(!empty($datef))
$xtpl->assign("DATEFORMAT", $sugar_config['date_formats'][$datef]);
if(!empty($timef))
$xtpl->assign("TIMEFORMAT", $sugar_config['time_formats'][$timef]);
	require_once('modules/Currencies/Currency.php');
	$currency  = new Currency();

$xtpl->assign("DESCRIPTION", nl2br($focus->description));
$xtpl->assign("TITLE", $focus->title);
$xtpl->assign("DEPARTMENT", $focus->department);
$xtpl->assign("REPORTS_TO_ID", $focus->reports_to_id);
$xtpl->assign("REPORTS_TO_NAME", $focus->reports_to_name);
$xtpl->assign("PHONE_HOME", $focus->phone_home);
$xtpl->assign("PHONE_MOBILE", $focus->phone_mobile);
$xtpl->assign("PHONE_WORK", $focus->phone_work);
$xtpl->assign("PHONE_OTHER", $focus->phone_other);
$xtpl->assign("PHONE_FAX", $focus->phone_fax);
$xtpl->assign("EMAIL1", $focus->email1);
$xtpl->assign("EMAIL2", $focus->email2);
$xtpl->assign("ADDRESS_STREET", $focus->address_street);
$xtpl->assign("ADDRESS_CITY", $focus->address_city);
$xtpl->assign("ADDRESS_STATE", $focus->address_state);
$xtpl->assign("ADDRESS_POSTALCODE", $focus->address_postalcode);
$xtpl->assign("ADDRESS_COUNTRY", $focus->address_country);
$xtpl->assign("MESSENGER_ID", $focus->messenger_id);
$xtpl->assign("MESSENGER_TYPE", $focus->messenger_type);

$xtpl->parse("employee_info");
$xtpl->out("employee_info");

echo "</td></tr>\n";

?>
