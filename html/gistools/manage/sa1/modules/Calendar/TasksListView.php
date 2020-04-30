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
 * $Id: TasksListView.php,v 1.17 2005/04/30 06:44:29 clint Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('modules/Tasks/Task.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');

require_once('include/ListView/ListView.php');

global $app_strings;
global $app_list_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'Tasks');

global $urlPrefix;

$log = LoggerManager::getLogger('task_list');

global $currentModule;
global $current_user;

global $image_path;
global $theme;

// focus_list is the means of passing data to a ListView.
global $focus_list;

require_once('include/ListView/ListView.php');
require_once('include/TimeDate.php');
$timedate = new TimeDate();

$today = $timedate->to_db_date(date("Y-m-d"), false); 
$today = $timedate->handle_offset($today, $timedate->dbDayFormat, true);

$where = "tasks.assigned_user_id='$current_user->id' and tasks.status<>'Completed' and tasks.status<>'Deferred'";
$where .= "and (tasks.date_start is NULL or tasks.date_start <= '$today')";



$seedTask = new Task();


global  $task_title;
$title_display = $current_module_strings['LBL_LIST_FORM_TITLE'];
if ($task_title) $title_display= $task_title;

$sugar_config['disable_export'] = true;
$ListView = new ListView();

$header_text = '';
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){	
		$header_text = "&nbsp;<a href='index.php?action=index&module=DynamicLayout&from_action=TasksListView&from_module=".$_REQUEST['module'] ."&mod_class=Tasks'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>";
}
$ListView->initNewXTemplate('modules/Calendar/TasksListView.html',$current_module_strings);
$ListView->setCurrentModule("Tasks");
$ListView->setHeaderTitle($title_display. $header_text);
$ListView->setQuery($where, "", "date_due,status desc", "TASK");
$ListView->processListView($seedTask, "main", "TASK");

?>
