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
 * $Id: EditView.php,v 1.7.2.2 2005/05/12 21:15:07 ajay Exp $
 * Description: TODO:  To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Documents/Document.php');
require_once('modules/Documents/DocumentRevision.php');
require_once('modules/Notes/Forms.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_user;

$focus =& new Document();

if(isset($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}
$old_id = '';
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') 
{
	$focus->id = "";
	$old_id=$_REQUEST['old_id'];
}

echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->document_name, true); 
echo "\n</p>\n";
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("Document detail view");

$xtpl=new XTemplate ('modules/Documents/EditView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js());
$xtpl->assign("ID", $focus->id);
$xtpl->assign("DOCUMENT_NAME",$focus->document_name);
$xtpl->assign("DESCRIPTION",$focus->description);
$xtpl->assign("FILENAME_TEXT",$focus->filename);
$xtpl->assign("REVISION",$focus->latest_revision);
$xtpl->assign("OLD_ID",$old_id);

if (isset($focus->id)) {
	$xtpl->assign("FILE_OR_HIDDEN","hidden");
	if (!isset($_REQUEST['isDuplicate'])) {
		$xtpl->assign("DISABLED","disabled");
	}
} else {
	$xtpl->assign("FILE_OR_HIDDEN","file");
}
if (empty($focus->active_date)) {
	$timedate = new TimeDate();
	$xtpl->assign("ACTIVE_DATE",$timedate->to_display_date(date("Y-m-d"), true) );
		
} else {
	$xtpl->assign("ACTIVE_DATE",$focus->active_date);
}
$xtpl->assign("EXP_DATE",$focus->exp_date);

if (isset($focus->category_id)) $xtpl->assign("CATEGORY_OPTIONS", get_select_options_with_id($app_list_strings['document_category_dom'], $focus->category_id));
else $xtpl->assign("CATEGORY_OPTIONS", get_select_options_with_id($app_list_strings['document_category_dom'], ''));

if (isset($focus->subcategory_id)) $xtpl->assign("SUBCATEGORY_OPTIONS", get_select_options_with_id($app_list_strings['document_subcategory_dom'], $focus->subcategory_id));
else $xtpl->assign("SUBCATEGORY_OPTIONS", get_select_options_with_id($app_list_strings['document_subcategory_dom'], ''));

if (isset($focus->status_id)) $xtpl->assign("STATUS_OPTIONS", get_select_options_with_id($app_list_strings['document_status_dom'], $focus->status_id));
else $xtpl->assign("STATUS_OPTIONS", get_select_options_with_id($app_list_strings['document_status_dom'], ''));






$xtpl->parse("main.open_source");




$timedate = new TimeDate();
$xtpl->assign("CALENDAR_DATEFORMAT", $timedate->get_cal_date_format());
$xtpl->assign("USER_DATE_FORMAT", $timedate->get_user_date_format());


$xtpl->parse("main");
$xtpl->out("main");


?>
