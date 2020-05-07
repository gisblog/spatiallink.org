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
 * $Id: MeetingFormBase.php,v 1.32 2005/04/20 01:32:58 robert Exp $
 * Description:  Base Form For Meetings
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/


class MeetingFormBase{

	function getFormBody($prefix, $mod='', $formname=''){
		require_once('include/time.php');
		global $mod_strings;
		$temp_strings = $mod_strings;
		if(!empty($mod)){
			global $current_language;
			$mod_strings = return_module_language($current_language, $mod);
		}
			global $app_strings;
			global $app_list_strings;
			global $current_user;
			global $theme;
			// Unimplemented until jscalendar language files are fixed
			// global $current_language;
			// global $default_language;
			// global $cal_codes;
			
			require_once('include/TimeDate.php');
			
	$timedate = new TimeDate();
	$cal_lang = "en";
$cal_dateformat = $timedate->get_cal_date_format();

$lbl_required_symbol = $app_strings['LBL_REQUIRED_SYMBOL'];
$lbl_date = $mod_strings['LBL_DATE'];
$lbl_time = $mod_strings['LBL_TIME'];
$ntc_date_format = $timedate->get_user_date_format();
$ntc_time_format = '('.$timedate->get_user_time_format().')';

	$user_id = $current_user->id;
$default_status = $mod_strings['LBL_DEFAULT_STATUS'];
$default_parent_type= $app_list_strings['record_type_default_key'];
$default_date_start = $timedate->to_display_date(date('Y-m-d'));
$default_time_start = $timedate->to_display_time((date('H:i')), true, false);
$time_ampm = $timedate->AMPMMenu($prefix,date('H:i'));
			// Unimplemented until jscalendar language files are fixed
			// $cal_lang = (empty($cal_codes[$current_language])) ? $cal_codes[$default_language] : $cal_codes[$current_language];

			$form = <<<EOF
					<input type="hidden" name="${prefix}record" value="">
					<input type="hidden" name="${prefix}status" value="${default_status}">
					<input type="hidden" name="${prefix}parent_type" value="${default_parent_type}">
					<input type="hidden" name="${prefix}assigned_user_id" value='${user_id}'>
					<input type="hidden" name="${prefix}duration_hours" value="1">
					<input type="hidden" name="${prefix}duration_minutes" value="00">
					<p>$lbl_subject<span class="required">$lbl_required_symbol</span><br>
					<input name='${prefix}name' size='25' maxlength='255' type="text"><br>
					$lbl_date&nbsp;<span class="required">$lbl_required_symbol</span>&nbsp;<span class="dateFormat">$ntc_date_format</span><br>
					<input name='${prefix}date_start' id='jscal_field' type="text" maxlength="10" value="${default_date_start}"> <img src="themes/$theme/images/jscalendar.gif" alt="{$app_strings['LBL_ENTER_DATE']}"  id="jscal_trigger" align="absmiddle"><br>
					$lbl_time&nbsp;<span class="required">$lbl_required_symbol</span>&nbsp;<span class="dateFormat">$ntc_time_format</span><br>
					<input name='${prefix}time_start' type="text" maxlength='5' value="${default_time_start}">{$time_ampm}</p>
					<script type="text/javascript">
					Calendar.setup ({
						inputField : "jscal_field", ifFormat : "$cal_dateformat", showsTime : false, button : "jscal_trigger", singleClick : true, step : 1
					});
					</script>
EOF;
require_once('include/javascript/javascript.php');
require_once('modules/Meetings/Meeting.php');
$javascript = new javascript();
$javascript->setFormName($formname);
$javascript->setSugarBean(new Meeting());
$javascript->addRequiredFields($prefix);
$form .=$javascript->getScript();
$mod_strings = $temp_strings;
return $form;
}



function getForm($prefix, $mod='Meetings'){
		if(!empty($mod)){
	global $current_language;
	$mod_strings = return_module_language($current_language, $mod);
}else global $mod_strings;
		global $app_strings;
		global $app_list_strings;
		$lbl_save_button_title = $app_strings['LBL_SAVE_BUTTON_TITLE'];
		$lbl_save_button_key = $app_strings['LBL_SAVE_BUTTON_KEY'];
		$lbl_save_button_label = $app_strings['LBL_SAVE_BUTTON_LABEL'];


$the_form = get_left_form_header($mod_strings['LBL_NEW_FORM_TITLE']);
$the_form .= <<<EOQ


		<form name="${prefix}MeetingSave" onSubmit="return check_form('${prefix}MeetingSave')" method="POST" action="index.php">
			<input type="hidden" name="${prefix}module" value="Meetings">

			<input type="hidden" name="${prefix}action" value="Save">

EOQ;
$the_form	.= $this->getFormBody($prefix, 'Meetings',"${prefix}MeetingSave" );
$the_form .= <<<EOQ
		<p><input title="$lbl_save_button_title" accessKey="$lbl_save_button_key" class="button" type="submit" name="button" value="  $lbl_save_button_label  " ></p>
		</form>
EOQ;

$the_form .= get_left_form_footer();
$the_form .= get_validate_record_js();

return $the_form;
}


function handleSave($prefix,$redirect=true, $useRequired=false){
	require_once('modules/Meetings/Meeting.php');
	require_once('include/logging.php');
	require_once('include/formbase.php');
	global $current_user;
	
	$local_log =& LoggerManager::getLogger('MeetingSaveForm');
	$focus =& new Meeting();
	if($useRequired &&  !checkRequired($prefix, array_keys($focus->required_fields))){
		return null;
	}
	if(isset($_POST['should_remind']) && $_POST['should_remind'] == '0'){
			$_POST['reminder_time'] = -1;
	}
	if(!isset($_POST['reminder_time'])){
		$_POST['reminder_time'] = $current_user->getPreference('reminder_time');
		if(empty($_POST['reminder_time'])){
			$_POST['reminder_time'] = -1;
		}
			
	}

	if (! empty($_POST[$prefix.'time_hour_start']) && empty($_POST['time_start']))
	{
    $_POST['time_start'] = $_POST[$prefix.'time_hour_start'].":".$_POST[$prefix.'time_minute_start'];
	}

	require_once('include/TimeDate.php');
	$timedate =& new TimeDate();
	if(isset($_POST[$prefix.'meridiem']) && !empty($_POST[$prefix.'meridiem'])){
		$_POST[$prefix.'time_start'] = $timedate->merge_time_meridiem($_POST[$prefix.'time_start'],$timedate->get_time_format(true), $_POST[$prefix.'meridiem']);
		
	}
	$focus = populateFromPost($prefix, $focus);

  if ( ! empty($_POST['user_invitees']) || ! empty($_POST['contact_invitees']))
  {
    $focus->mark_relationships_deleted($focus->id);
  }
  if ( ! empty($_POST['user_invitees']))
  {
    $existing_users =  array();
    if ( ! empty($_POST['existing_invitees']))
    {
     $existing_users =  explode(",",$_POST['existing_invitees']);
    }
    $focus->users_arr = explode(",",$_POST['user_invitees']);

  }


  if ( ! empty($_POST['contact_invitees']))
  {
    $existing_users =  array();
    if ( ! empty($_POST['existing_contact_invitees']))
    {
     $existing_contacts =  explode(",",$_POST['existing_contact_invitees']);
    }
    $this->contacts_arr = explode(",",$_POST['contact_invitees']);

  }


  $local_log->debug("Saved record with id of ".$return_id);
	$focus->save(true);
  $return_id = $focus->id;

  if ( ! is_array($focus->users_arr ))
  {
		$focus->users_arr = array();
  }
  foreach ($focus->users_arr as $user_id)
  {
      if (empty($user_id) || isset($existing_users[$user_id]))
      {
         continue;
      }

      $focus->set_meetings_user_invitee_relationship($focus->id, $user_id);
  }

  if ( ! is_array($focus->contacts_arr ))
  {
		$focus->contacts_arr = array();
  }

  foreach ($this->contacts_arr as $contact_id)
  {
      if (empty($contact_id) || isset($existing_contacts[$contact_id]))
      {
         continue;
      }

      $focus->set_meetings_contact_invitee_relationship($focus->id, $contact_id);
  }

  if($redirect){
	handleRedirect($return_id, 'Meetings');
  }else{
	return $focus;
  }
}






}

?>
