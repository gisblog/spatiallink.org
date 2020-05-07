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
 * $Id: SubPanelView.php,v 1.53 2005/04/28 22:14:14 robert Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once("include/upload_file.php");
require_once("include/TimeDate.php");
global $currentModule;

global $theme;
global $focus;
global $action;

global $app_strings;
global $app_list_strings;
//we don't want the parent module's string file, but rather the string file specifc to this subpanel
global $current_language;
$current_module_strings = return_module_language($current_language, 'Activities');
$timedate = new TimeDate();
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

// history_list is the means of passing data to a SubPanelView.
global $focus_tasks_list;
global $focus_meetings_list;
global $focus_calls_list;
global $focus_emails_list;

$open_activity_list = Array();
$history_list = Array();

foreach ($focus_tasks_list as $task) {
	if ($task->status != "Not Started" && $task->status != "In Progress" && $task->status != "Pending Input") {
		$history_list[] = Array('name' => $task->name,
									 'id' => $task->id,
									 'type' => "Task",
									 'direction' => '',
									 'module' => "Tasks",
									 'status' => $task->status,
									 'parent_id' => $task->parent_id,
									 'parent_type' => $task->parent_type,
									 'parent_name' => $task->parent_name,
									 'contact_id' => $task->contact_id,
									 'contact_name' => $task->contact_name,
									 'date_modified' => $timedate->to_display_date($task->date_modified, true),
									 );
	}
	else {
		if ($task->date_due == '0000-00-00') $date_due = '';
		else {
			$date_due = $task->date_due;
					
		}
		$open_activity_list[] = Array('name' => $task->name,
									 'id' => $task->id,
									 'type' => "Task",
									 'direction' => '',
									 'module' => "Tasks",
									 'status' => $task->status,
									 'parent_id' => $task->parent_id,
									 'parent_type' => $task->parent_type,
									 'parent_name' => $task->parent_name,
									 'contact_id' => $task->contact_id,
									 'contact_name' => $task->contact_name,
									 'date_due' => $date_due
									 );
	}
}

foreach ($focus_meetings_list as $meeting) {
		if ($meeting->status != "Planned") {
		$history_list[] = Array('name' => $meeting->name,
									 'id' => $meeting->id,
									 'type' => "Meeting",
									 'direction' => '',
									 'module' => "Meetings",
									 'status' => $meeting->status,
									 'parent_id' => $meeting->parent_id,
									 'parent_type' => $meeting->parent_type,
									 'parent_name' => $meeting->parent_name,
									 'contact_id' => $meeting->contact_id,
									 'contact_name' => $meeting->contact_name,
									 'date_modified' => $meeting->date_modified
									 );
	}
	else {
		$open_activity_list[] = Array('name' => $meeting->name,
									 'id' => $meeting->id,
									 'type' => "Meeting",
									 'direction' => '',
									 'module' => "Meetings",
									 'status' => $meeting->status,
									 'parent_id' => $meeting->parent_id,
									 'parent_type' => $meeting->parent_type,
									 'parent_name' => $meeting->parent_name,
									 'contact_id' => $meeting->contact_id,
									 'contact_name' => $meeting->contact_name,
									 'date_due' => $meeting->date_start
									 );
	}
}

foreach ($focus_calls_list as $call) {
	if ($call->status != "Planned") {
		$history_list[] = Array('name' => $call->name,
									 'id' => $call->id,
									 'type' => "Call",
									 'direction' => $call->direction,
									 'module' => "Calls",
									 'status' => $call->status,
									 'parent_id' => $call->parent_id,
									 'parent_type' => $call->parent_type,
									 'parent_name' => $call->parent_name,
									 'contact_id' => $call->contact_id,
									 'contact_name' => $call->contact_name,
									 'date_modified' => $call->date_modified
									 );
	}
	else {
		$open_activity_list[] = Array('name' => $call->name,
									 'id' => $call->id,
									 'direction' => $call->direction,
									 'type' => "Call",
									 'module' => "Calls",
									 'status' => $call->status,
									 'parent_id' => $call->parent_id,
									 'parent_type' => $call->parent_type,
									 'parent_name' => $call->parent_name,
									 'contact_id' => $call->contact_id,
									 'contact_name' => $call->contact_name,
									 'date_due' => $call->date_start
									 );
	}
}

foreach ($focus_emails_list as $email) {
	$history_list[] = Array('name' => $email->name,
									 'id' => $email->id,
									 'type' => "Email",
									 'direction' => '',
									 'module' => "Emails",
									 'status' => '',
									 'parent_id' => $email->parent_id,
									 'parent_type' => $email->parent_type,
									 'parent_name' => $email->parent_name,
									 'contact_id' => $email->contact_id,
									 'contact_name' => $email->contact_name,
									 'date_modified' => $email->date_start." ".$email->time_start
									 );
}

foreach ($focus_notes_list as $note) {
	$history_list[] = Array('name' => $note->name,
									 'id' => $note->id,
									 'type' => "Note",
									 'direction' => '',
									 'module' => "Notes",
									 'status' => '',
									 'parent_id' => $note->parent_id,
									 'parent_type' => $note->parent_type,
									 'parent_name' => $note->parent_name,
									 'contact_id' => $note->contact_id,
									 'contact_name' => $note->contact_name,
									 'date_modified' => $note->date_modified
									 );
	if (!empty($note->filename))
	{
		$count = count($history_list);
		$count--;
		$history_list[$count]['filename'] = $note->filename;
		$history_list[$count]['fileurl'] = UploadFile::get_url($note->filename,$note->id);
	}

}

if ($currentModule == 'Contacts')
{
	$xtpl=new XTemplate ('modules/Activities/SubPanelViewContacts.html');
	$xtpl->assign("CONTACT_ID", $focus->id);
}
else
{
	$xtpl=new XTemplate ('modules/Activities/SubPanelView.html');
}

$xtpl->assign("DELETE_INLINE_PNG",  get_image($image_path.'delete_inline','align="absmiddle" alt="'.$app_strings['LNK_DELETE'].'" border="0"'));
$xtpl->assign("EDIT_INLINE_PNG",  get_image($image_path.'edit_inline','align="absmiddle" alt="'.$app_strings['LNK_EDIT'].'" border="0"'));

$xtpl->assign("MOD", $current_module_strings);
$xtpl->assign("APP", $app_strings);

$button  = "<form border='0' action='index.php' method='post' name='form' id='form'>\n";
$button .= "<input type='hidden' name='module'>\n";
$button .= "<input type='hidden' name='type'>\n";
if ($currentModule == 'Accounts')
{
	$button .= "<input type='hidden' name='parent_type' value='Accounts'>\n<input type='hidden' name='parent_id' value='$focus->id'>\n<input type='hidden' name='parent_name' value='$focus->name'>\n";
}
elseif ($currentModule == 'Opportunities')
{
	$button .= "<input type='hidden' name='parent_type' value='Opportunities'>\n<input type='hidden' name='parent_id' value='$focus->id'>\n<input type='hidden' name='parent_name' value='$focus->name'>\n";
}
elseif ($currentModule == 'Cases')
{
	$button .= "<input type='hidden' name='parent_type' value='Cases'>\n<input type='hidden' name='parent_id' value='$focus->id'>\n<input type='hidden' name='parent_name' value='$focus->name'>\n";
}
elseif ($currentModule == 'Contacts')
{
	$button .= "<input type='hidden' name='contact_id' value='$focus->id'>\n<input type='hidden' name='contact_name' value='$focus->first_name $focus->last_name'>\n";
	$button .= "<input type='hidden' name='parent_type' value='Accounts'>\n<input type='hidden' name='parent_id' value='$focus->account_id'>\n<input type='hidden' name='parent_name' value='$focus->account_name'>\n";
	$button .= "<input type='hidden' name='to_email_addrs' value='$focus->email1'>\n";
}
else
{
	$button .= "<input type='hidden' name='parent_type' value='$currentModule'>\n<input type='hidden' name='parent_id' value='$focus->id'>\n<input type='hidden' name='parent_name' value='$focus->name'>\n";
}

$button .= "<input type='hidden' name='return_module' value='".$currentModule."'>\n";
$button .= "<input type='hidden' name='return_action' value='".$action."'>\n";
$button .= "<input type='hidden' name='return_id' value='".$focus->id."'>\n";
$button .= "<input type='hidden' name='type' value='out'>\n";
$button .= "<input type='hidden' name='action'>\n";

if($currentModule != 'Project' && $currentModule != 'ProjectTask')
{
	$button .= "<input title='".$current_module_strings['LBL_NEW_TASK_BUTTON_TITLE']."' accessKey='".$current_module_strings['LBL_NEW_TASK_BUTTON_KEY']."' class='button' onclick=\"this.form.action.value='EditView';this.form.module.value='Tasks'\" type='submit' name='button' value='".$current_module_strings['LBL_NEW_TASK_BUTTON_LABEL']."'>\n";
}

$button .= "<input title='".$current_module_strings['LBL_SCHEDULE_MEETING_BUTTON_TITLE']."' accessKey='".$current_module_strings['LBL_SCHEDULE_MEETING_BUTTON_KEY']."' class='button' onclick=\"this.form.action.value='EditView';this.form.module.value='Meetings'\" type='submit' name='button' value='".$current_module_strings['LBL_SCHEDULE_MEETING_BUTTON_LABEL']."'>\n";

$button .= "<input title='".$current_module_strings['LBL_SCHEDULE_CALL_BUTTON_LABEL']."' accessKey='".$current_module_strings['LBL_SCHEDULE_CALL_BUTTON_KEY']."' class='button' onclick=\"this.form.action.value='EditView';this.form.module.value='Calls'\" type='submit' name='button' value='".$current_module_strings['LBL_SCHEDULE_CALL_BUTTON_LABEL']."'>\n";

$button .= "<input title='".$app_strings['LBL_COMPOSE_EMAIL_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_COMPOSE_EMAIL_BUTTON_KEY']."' class='button' onclick=\"this.form.type.value='out';this.form.action.value='EditView';this.form.module.value='Emails';\" type='submit' name='button' value='".$app_strings['LBL_COMPOSE_EMAIL_BUTTON_LABEL']."'>\n";

$button .= "</form>\n";

// Stick the form header out there.
echo get_form_header($current_module_strings['LBL_OPEN_ACTIVITIES'], $button, false);

$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("RETURN_URL", "&return_module=$currentModule&return_action=DetailView&return_id=$focus->id");

$oddRow = true;
if (count($open_activity_list) > 0) $open_activity_list = array_csort($open_activity_list, 'date_due', SORT_DESC);
foreach($open_activity_list as $activity)
{
	$activity_fields = array(
		'ID' => $activity['id'],
		'NAME' => $activity['name'],
		'MODULE' => $activity['module'],
		'CONTACT_NAME' => $activity['contact_name'],
		'CONTACT_ID' => $activity['contact_id'],
		'PARENT_TYPE' => $activity['parent_type'],
		'PARENT_NAME' => $activity['parent_name'],
		'PARENT_ID' => $activity['parent_id'],
		'DATE' => $activity['date_due']
	);

	if (empty($activity['direction'])) {
		$activity_fields['TYPE'] = $app_list_strings['activity_dom'][$activity['type']];
	}
	else {
		$activity_fields['TYPE'] = $app_list_strings['call_direction_dom'][$activity['direction']].' '.$app_list_strings['activity_dom'][$activity['type']];
	}
	if (isset($activity['parent_type'])) $activity_fields['PARENT_MODULE'] = $activity['parent_type'];
	switch ($activity['type']) {
		case 'Call':
			$activity_fields['SET_COMPLETE'] = "<a href='index.php?return_module=$currentModule&return_action=$action&return_id=$focus->id&action=EditView&module=Calls&status=Held&record=".$activity['id']."&status=Held'>".get_image($image_path."close_inline","alt='Close' border='0'")."</a>";
			$activity_fields['STATUS'] = $app_list_strings['call_status_dom'][$activity['status']];
			break;
		case 'Meeting':
			$activity_fields['SET_COMPLETE'] = "<a href='index.php?return_module=$currentModule&return_action=$action&return_id=$focus->id&action=EditView&module=Meetings&status=Held&record=".$activity['id']."&status=Held'>".get_image($image_path."close_inline","alt='Close' border='0'")."</a>";
			$activity_fields['STATUS'] = $app_list_strings['meeting_status_dom'][$activity['status']];
			break;
		case 'Task':
			$activity_fields['SET_COMPLETE'] = "<a href='index.php?return_module=$currentModule&return_action=$action&return_id=$focus->id&action=EditView&module=Tasks&status=Completed&record=".$activity['id']."&status=Completed'>".get_image($image_path."close_inline","alt='Close' border='0'")."</a>";
			$activity_fields['STATUS'] = $app_list_strings['task_status_dom'][$activity['status']];
			break;
	}

 global $odd_bg;
 global $even_bg;
 global $hilite_bg;
 global $click_bg;
$xtpl->assign("BG_HILITE", $hilite_bg);
$xtpl->assign("BG_CLICK", $click_bg);
$xtpl->assign("ACTIVITY_MODULE_PNG", get_image($image_path.$activity_fields['MODULE'].'','border="0" alt="'.$activity_fields['NAME'].'"'));
	$xtpl->assign("ACTIVITY", $activity_fields);

	if($oddRow)
    {
        //todo move to themes
		$xtpl->assign("ROW_COLOR", 'oddListRow');
		$xtpl->assign("BG_COLOR", $odd_bg);
    }
    else
    {
        //todo move to themes
		$xtpl->assign("ROW_COLOR", 'evenListRow');
		$xtpl->assign("BG_COLOR", $even_bg);
    }
    $oddRow = !$oddRow;

	$xtpl->parse("open_activity.row");
// Put the rows in.
}

$xtpl->parse("open_activity");
$xtpl->out("open_activity");
echo "<BR>";
// Stick on the form footer
echo get_form_footer();


$button  = "<form border='0' action='index.php' method='post' name='form' id='form'>\n";
$button .= "<input type='hidden' name='module'>\n";
$button .= "<input type='hidden' name='type' value='archived'>\n";
if ($currentModule == 'Accounts') $button .= "<input type='hidden' name='parent_type' value='Accounts'>\n<input type='hidden' name='parent_id' value='$focus->id'>\n<input type='hidden' name='parent_name' value='$focus->name'>\n";
if ($currentModule == 'Opportunities') $button .= "<input type='hidden' name='parent_type' value='Opportunities'>\n<input type='hidden' name='parent_id' value='$focus->id'>\n<input type='hidden' name='parent_name' value='$focus->name'>\n";
elseif ($currentModule == 'Cases') $button .= "<input type='hidden' name='parent_type' value='Cases'>\n<input type='hidden' name='parent_id' value='$focus->id'>\n<input type='hidden' name='parent_name' value='$focus->name'>\n";
elseif ($currentModule == 'Contacts') {
	$button .= "<input type='hidden' name='contact_id' value='$focus->id'>\n<input type='hidden' name='contact_name' value='$focus->first_name $focus->last_name'>\n";
  $button .= "<input type='hidden' name='to_email_addrs' value='$focus->email1'>\n";
	$button .= "<input type='hidden' name='parent_type' value='Accounts'>\n<input type='hidden' name='parent_id' value='$focus->account_id'>\n<input type='hidden' name='parent_name' value='$focus->account_name'>\n";
}else{
	$button .= "<input type='hidden' name='parent_type' value='$currentModule'>\n<input type='hidden' name='parent_id' value='$focus->id'>\n<input type='hidden' name='parent_name' value='$focus->name'>\n";
}
$button .= "<input type='hidden' name='return_module' value='".$currentModule."'>\n";
$button .= "<input type='hidden' name='return_action' value='".$action."'>\n";
$button .= "<input type='hidden' name='return_id' value='".$focus->id."'>\n";
$button .= "<input type='hidden' name='action'>\n";
$button .= "<input title='".$current_module_strings['LBL_NEW_NOTE_BUTTON_TITLE']."' accessKey='".$current_module_strings['LBL_NEW_NOTE_BUTTON_KEY']."' class='button' onclick=\"this.form.action.value='EditView';this.form.module.value='Notes'\" type='submit' name='button' value='".$current_module_strings['LBL_NEW_NOTE_BUTTON_LABEL']."'>\n";
$button .= "<input title='".$current_module_strings['LBL_TRACK_EMAIL_BUTTON_TITLE']."' accessKey='".$current_module_strings['LBL_TRACK_EMAIL_BUTTON_KEY']."' class='button' onclick=\"this.form.type.value='archived';this.form.action.value='EditView';this.form.module.value='Emails'\" type='submit' name='button' value='".$current_module_strings['LBL_TRACK_EMAIL_BUTTON_LABEL']."'>\n";
$button .= "</form>\n";

// Stick the form header out there.
echo get_form_header($current_module_strings['LBL_HISTORY'], $button, false);

$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("RETURN_URL", "&return_module=$currentModule&return_action=DetailView&return_id=$focus->id");

$oddRow = true;
if (count($history_list) > 0) $history_list = array_csort($history_list, 'date_modified', SORT_DESC);
foreach($history_list as $activity)
{
	$activity_fields = array(
		'ID' => $activity['id'],
		'NAME' => $activity['name'],
		'MODULE' => $activity['module'],
		'CONTACT_NAME' => $activity['contact_name'],
		'CONTACT_ID' => $activity['contact_id'],
		'PARENT_TYPE' => $activity['parent_type'],
		'PARENT_NAME' => $activity['parent_name'],
		'PARENT_ID' => $activity['parent_id'],
		'DATE' => $activity['date_modified'],
	);
	if (empty($activity['direction'])) {
		$activity_fields['TYPE'] = $app_list_strings['activity_dom'][$activity['type']];
	}
	else {
		$activity_fields['TYPE'] = $app_list_strings['call_direction_dom'][$activity['direction']].' '.$app_list_strings['activity_dom'][$activity['type']];
	}

	switch ($activity['type']) {
		case 'Call':
			$activity_fields['STATUS'] = $app_list_strings['call_status_dom'][$activity['status']];
			break;
		case 'Meeting':
			$activity_fields['STATUS'] = $app_list_strings['meeting_status_dom'][$activity['status']];
			break;
		case 'Task':
			$activity_fields['STATUS'] = $app_list_strings['task_status_dom'][$activity['status']];
			break;
	}

	if (isset($activity['location'])) $activity_fields['LOCATION'] = $activity['location'];
	if (isset($activity['filename'])) {
		$activity_fields['ATTACHMENT'] = "<a href='".$activity['fileurl']."' target='_blank'>".get_image($image_path."attachment","alt='".$activity['filename']."' border='0' align='absmiddle'")."</a>";
    }

	if (isset($activity['parent_type'])) $activity_fields['PARENT_MODULE'] = $activity['parent_type'];

	$xtpl->assign("ACTIVITY", $activity_fields);
	$xtpl->assign("ACTIVITY_MODULE_PNG", get_image($image_path.$activity_fields['MODULE'].'','border="0" alt="'.$activity_fields['NAME'].'"'));

	if($oddRow)
    {
        //todo move to themes
		$xtpl->assign("ROW_COLOR", 'oddListRow');
		$xtpl->assign("BG_COLOR", $odd_bg);
    }
    else
    {
        //todo move to themes
		$xtpl->assign("ROW_COLOR", 'evenListRow');
		$xtpl->assign("BG_COLOR", $even_bg);
    }
    $oddRow = !$oddRow;

	$xtpl->parse("history.row");
// Put the rows in.
}

$xtpl->parse("history");
$xtpl->out("history");

// Stick on the form footer
echo get_form_footer();

?>
