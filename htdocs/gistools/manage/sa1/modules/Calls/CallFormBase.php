<?PHP
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
 * $Id: CallFormBase.php,v 1.41 2005/04/29 01:04:04 robert Exp $
 * Description: Call Form Base
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

class CallFormBase{

function getFormBody($prefix, $mod='', $formname='',$cal_date='',$cal_time=''){

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


$lbl_subject = $mod_strings['LBL_SUBJECT'];
// Unimplemented until jscalendar language files are fixed
// global $current_language;
// global $default_language;
// global $cal_codes;
// Unimplemented until jscalendar language files are fixed
// $cal_lang = (empty($cal_codes[$current_language])) ? $cal_codes[$default_language] : $cal_codes[$current_language];

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
$date = date('Y-m-d H:i:s');
$default_date_start = $timedate->to_display_date($date);
$default_time_start = $timedate->to_display_time($date);
$time_ampm = $timedate->AMPMMenu($prefix,$default_time_start);
$lbl_save_button_title = $app_strings['LBL_SAVE_BUTTON_TITLE'];
$lbl_save_button_key = $app_strings['LBL_SAVE_BUTTON_KEY'];
$lbl_save_button_label = $app_strings['LBL_SAVE_BUTTON_LABEL'];
	$form =	<<<EOQ
			<form name="${formname}" onSubmit="return check_form('${formname}') "method="POST" action="index.php">
			<input type="hidden" name="${prefix}module" value="Calls">
			<input type="hidden" name="${prefix}action" value="Save">
				<input type="hidden" name="${prefix}record" value="">
			<input type="hidden"  name="${prefix}direction" value="Outbound">
			<input type="hidden" name="${prefix}status" value="${default_status}">
			<input type="hidden" name="${prefix}parent_type" value="${default_parent_type}">
			<input type="hidden" name="${prefix}assigned_user_id" value='${user_id}'>
			<input type="hidden" name="${prefix}duration_hours" value="1">
			<input type="hidden" name="${prefix}duration_minutes" value="0">
			<input type="hidden" name="${prefix}user_id" value="${user_id}">

		<table cellspacing="1" cellpadding="0" border="0">
<tr>
    <td colspan="2"><input type='radio' name='appointment' value='Call' class='radio' onchange='document.${formname}.module.value="Calls";' style='vertical-align: middle;' checked> <span class="dataLabel">${mod_strings['LNK_NEW_CALL']}</span>
&nbsp;
&nbsp;
<input type='radio' name='appointment' value='Meeting' class='radio' onchange='document.${formname}.module.value="Meetings";'><span class="dataLabel">${mod_strings['LNK_NEW_MEETING']}</span></td>
</tr>
<tr>
    <td colspan="2"><span class="dataLabel">$lbl_subject</span>&nbsp;<span class="required">$lbl_required_symbol</span></td>
</tr>
<tr><td valign=top><input name='${prefix}name' size='30' maxlength='255' type="text"></td>
    <input name='${prefix}date_start' id='${formname}jscal_field' maxlength='10' type="hidden" value="${cal_date}"></td>
    <input name='${prefix}time_start' type="hidden" maxlength='10' value="{$cal_time}"></td>

			<script type="text/javascript">
//		Calendar.setup ({
//			inputField : "${formname}jscal_field", ifFormat : "$cal_dateformat", showsTime : false, button : "${formname}jscal_trigger", singleClick : true, step : 1
//		});
		</script>



EOQ;

require_once('include/javascript/javascript.php');
require_once('modules/Calls/Call.php');
$javascript = new javascript();
$javascript->setFormName($formname);
$javascript->setSugarBean(new Call());
$javascript->addRequiredFields($prefix);
$form .=$javascript->getScript();
$form .= "<td align=\"left\" valign=top><input title='$lbl_save_button_title' accessKey='$lbl_save_button_key' class='button' type='submit' name='button' value=' $lbl_save_button_label ' ></td></form></tr></table>";
$mod_strings = $temp_strings;
return $form;

}
function getFormHeader($prefix, $mod='', $title=''){
	if(!empty($mod)){
	global $current_language;
	$mod_strings = return_module_language($current_language, $mod);
}else global $mod_strings;






if(!empty($title)){
	$the_form = get_left_form_header($title);
}else{
	$the_form = get_left_form_header($mod_strings['LBL_NEW_FORM_TITLE']);
}
$the_form .= <<<EOQ
		<form name="${prefix}CallSave" onSubmit="return check_form('${prefix}CallSave') "method="POST" action="index.php">
			<input type="hidden" name="${prefix}module" value="Calls">
			<input type="hidden" name="${prefix}action" value="Save">

EOQ;
return $the_form;
}
function getFormFooter($prefic, $mod=''){
global $app_strings;
global $app_list_strings;
$lbl_save_button_title = $app_strings['LBL_SAVE_BUTTON_TITLE'];
$lbl_save_button_key = $app_strings['LBL_SAVE_BUTTON_KEY'];
$lbl_save_button_label = $app_strings['LBL_SAVE_BUTTON_LABEL'];
$the_form = "	<p><input title='$lbl_save_button_title' accessKey='$lbl_save_button_key' class='button' type='submit' name='button' value=' $lbl_save_button_label ' ></p></form>";
$the_form .= get_left_form_footer();
$the_form .= get_validate_record_js();
return $the_form;
}

function getForm($prefix, $mod=''){
$the_form = $this->getFormHeader($prefix, $mod);
$the_form .= $this->getFormBody($prefix, $mod, "${prefix}CallSave");
$the_form .= $this->getFormFooter($prefix, $mod);

return $the_form;
}


function handleSave($prefix,$redirect=true, $useRequired=false){
	global $current_user;
	require_once('modules/Calls/Call.php');
	require_once('include/logging.php');
	require_once('include/formbase.php');
	require_once('include/TimeDate.php');
	$timedate =& new TimeDate();
	
	
	
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

	if(isset($_POST[$prefix.'meridiem']) && !empty($_POST[$prefix.'meridiem'])){
		$_POST[$prefix.'time_start'] = $timedate->merge_time_meridiem($_POST[$prefix.'time_start'],$timedate->get_time_format(true), $_POST[$prefix.'meridiem']);
		
	}
	
	$local_log =& LoggerManager::getLogger('CallSaveForm');
	$focus =& new Call();
	if($useRequired &&  !checkRequired($prefix, array_keys($focus->required_fields))){
		return null;
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
    $focus->contacts_arr = explode(",",$_POST['contact_invitees']);

  }

	$focus->save(true);

	$return_id = $focus->id;
  
	if (!empty($focus->users_arr) && is_array($focus->users_arr )) {
	  	foreach ($focus->users_arr as $user_id)
  		{
      	if (empty($user_id) || isset($existing_users[$user_id]))
      	{
         	continue;
      	}
	      $focus->set_calls_user_invitee_relationship($focus->id, $user_id);
  		}
	}

	if (!empty($focus->contacts_arr) && is_array($focus->contacts_arr )) {
  		foreach ($focus->contacts_arr as $contact_id)
  		{
      	if (empty($contact_id) || isset($existing_contacts[$contact_id]))
      	{
         	continue;
      	}

      	$focus->set_calls_contact_invitee_relationship($focus->id, $contact_id);
  		}
	}

	$local_log->debug("Saved record with id of ".$return_id);
	if($redirect){
		handleRedirect($return_id,'Calls');
	}else{
		return $focus;
	}
}



function getWideFormBody ($prefix, $mod='', $formname='', $wide =true){
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

$lbl_subject = $mod_strings['LBL_SUBJECT'];
// Unimplemented until jscalendar language files are fixed
// global $current_language;
// global $default_language;
// global $cal_codes;
// Unimplemented until jscalendar language files are fixed
// $cal_lang = (empty($cal_codes[$current_language])) ? $cal_codes[$default_language] : $cal_codes[$current_language];
$cal_lang = "en";


$lbl_required_symbol = $app_strings['LBL_REQUIRED_SYMBOL'];
	$lbl_date = $mod_strings['LBL_DATE'];
$lbl_time = $mod_strings['LBL_TIME'];
require_once('include/TimeDate.php');
$timedate = new TimeDate();
$ntc_date_format = '('.$timedate->get_user_date_format(). ')';
$ntc_time_format = '('.$timedate->get_user_time_format(). ')';
$cal_dateformat = $timedate->get_cal_date_format();

	$user_id = $current_user->id;
$default_status = $mod_strings['LBL_DEFAULT_STATUS'];
$default_parent_type= $app_list_strings['record_type_default_key'];
$default_date_start = $timedate->to_display_date(date('Y-m-d'));
$default_time_start =  $timedate->to_display_time(date('H:i:s'), true, false);
$time_ampm = $timedate->AMPMMenu($prefix,$default_time_start);
	$form =	<<<EOQ
			<input type="hidden"  name="${prefix}direction" value="Outbound">
			<input type="hidden" name="${prefix}record" value="">
			<input type="hidden" name="${prefix}status" value="${default_status}">
			<input type="hidden" name="${prefix}parent_type" value="${default_parent_type}">
			<input type="hidden" name="${prefix}assigned_user_id" value='${user_id}'>
			<input type="hidden" name="${prefix}duration_hours" value="1">
			<input type="hidden" name="${prefix}duration_minutes" value="0">
			<input type="hidden" name="${prefix}user_id" value="${user_id}">

		<table cellspacing='0' cellpadding='0' border='0' width="100%">
<tr>
EOQ;

if($wide){
$form .= <<<EOQ
<td class='dataLabel' width="20%"><input type='radio' name='appointment' value='Call' class='radio' checked> ${mod_strings['LNK_NEW_CALL']}</td>
<td class='dataLabel' width="80%">${mod_strings['LBL_DESCRIPTION']}</td>
</tr>

<tr>
<td class='dataLabel'><input type='radio' name='appointment' value='Meeting' class='radio'> ${mod_strings['LNK_NEW_MEETING']}</td>

<td rowspan='8' class='dataField'><textarea name='Appointmentsdescription' cols='50' rows='5'></textarea></td>
</tr>
EOQ;
}else{
		$form .= <<<EOQ
<td class='dataLabel' width="20%"><input type='radio' name='appointment' value='Call' class='radio' onchange='document.$formname.module.value="Calls";' checked> ${mod_strings['LNK_NEW_CALL']}</td>
</tr>

<tr>
<td class='dataLabel'><input type='radio' name='appointment' value='Meeting' class='radio' onchange='document.$formname.module.value="Meetings";'> ${mod_strings['LNK_NEW_MEETING']}</td>
</tr>
EOQ;
}
$form .=	<<<EOQ


<tr>
<td class='dataLabel'>$lbl_subject&nbsp;<span class="required">$lbl_required_symbol</span></td>
</tr>

<tr>
<td class='dataField'><input name='${prefix}name' maxlength='255' type="text"></td>
</tr>

<tr>
<td class='dataLabel'>$lbl_date&nbsp;<span class="required">$lbl_required_symbol</span>&nbsp;<span class="dateFormat">$ntc_date_format</span></td>
</tr>
<tr>
<td class='dataField'><input name='${prefix}date_start' size="12" id='${prefix}jscal_field' maxlength='10' type="text" value="${default_date_start}"> <img src="themes/$theme/images/jscalendar.gif" alt="{$app_strings['LBL_ENTER_DATE']}"  id="${prefix}jscal_trigger" align="absmiddle"></td>
</tr>

<tr>
<td class='dataLabel'>$lbl_time&nbsp;<span class="required">$lbl_required_symbol</span>&nbsp;<span class="dateFormat">$ntc_time_format</span></td>
</tr>
<tr>
<td class='dataField'><input name='${prefix}time_start' size="12" type="text" maxlength='5' value="{$default_time_start}">$time_ampm</td>
</tr>

</table>

		<script type="text/javascript">
		Calendar.setup ({
			inputField : "${prefix}jscal_field", ifFormat : "$cal_dateformat", showsTime : false, button : "${prefix}jscal_trigger", singleClick : true, step : 1
		});
		</script>
EOQ;
require_once('include/javascript/javascript.php');
require_once('modules/Calls/Call.php');
$javascript = new javascript();
$javascript->setFormName($formname);
$javascript->setSugarBean(new Call());
$javascript->addRequiredFields($prefix);
$form .=$javascript->getScript();
$mod_strings = $temp_strings;
return $form;

}



}
?>
