<?php
/**
 * EditView for Project
 *
 * PHP version 4
 *
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
 */

// $Id: EditView.php,v 1.14.2.1 2005/05/12 23:39:49 clint Exp $

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Project/Project.php');
require_once('include/time.php');
require_once('include/TimeDate.php');
require_once('modules/Project/Forms.php');
$timedate = new TimeDate();

global $app_strings;
global $app_list_strings;
global $current_language;
global $current_user;

$focus =& new Project();

if(!empty($_REQUEST['record']))
{
    $focus->retrieve($_REQUEST['record']);
}

echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->name, true);
echo "\n</p>\n";
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("Project detail view");

$xtpl=new XTemplate ('modules/Project/EditView.html');

///
/// Assign the template variables
///

$xtpl->assign('MOD', $mod_strings);
$xtpl->assign('APP', $app_strings);
$xtpl->assign('name', $focus->name);
$user_array =& get_user_array();
$initial_user_id = empty($focus->assigned_user_id) ? $current_user->id
	: $focus->assigned_user_id;
$options = get_select_options_with_id($user_array, $initial_user_id);
$xtpl->assign('assigned_user_options', $options);
$xtpl->assign('description', $focus->description);
$change_parent_button = "<input title='".$app_strings['LBL_CHANGE_BUTTON_TITLE']
	."' accessKey='".$app_strings['LBL_CHANGE_BUTTON_KEY']
	."' tabindex='2' type='button' class='button' value='"
	.$app_strings['LBL_CHANGE_BUTTON_LABEL']
	."' name='button' LANGUAGE=javascript onclick='return window.open(\"index.php?module=\"+ document.EditView.parent_type.value + \"&action=Popup&html=Popup_picker&form=TasksEditView\",\"test\",\"width=600,height=400,resizable=1,scrollbars=1\");'>";
$xtpl->assign("CHANGE_PARENT_BUTTON", $change_parent_button);








if (!empty($_REQUEST['account_id'])) {
 	$xtpl->assign('account_id', $_REQUEST['account_id']);
}
if (!empty($_REQUEST['opportunity_name']) && empty($focus->name)) {
		$focus->name = $_REQUEST['opportunity_name'];
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}

if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['relation_id'])) $xtpl->assign("relation_id", $_REQUEST['relation_id']);
if (isset($_REQUEST['relation_type'])) $xtpl->assign("relation_type", $_REQUEST['relation_type']);
if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
$xtpl->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js());
$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("id", $focus->id);
$xtpl->assign("name", $focus->name);

//Add Custom Fields
require_once('modules/DynamicFields/templates/Files/EditView.php');

global $current_user;
if(is_admin($current_user)
	&& $_REQUEST['module'] != 'DynamicLayout'
	&& !empty($_SESSION['editinplace']))
{
	$record = '';
	if(!empty($_REQUEST['record']))
	{
		$record = 	$_REQUEST['record'];
	}

	$xtpl->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action="
		.$_REQUEST['action'] ."&from_module=".$_REQUEST['module']
		."&record=".$record. "'>".get_image($image_path
		."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>");	
}




$xtpl->parse("main.open_source");



$xtpl->parse("main");
$xtpl->out("main");
require_once('include/javascript/javascript.php');
$javascript = new javascript();
$javascript->setFormName('EditView');
$javascript->setSugarBean($focus);
$javascript->addAllFields('');
echo $javascript->getScript();

?>
