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
 * $Id: NoteFormBase.php,v 1.23 2005/04/28 02:57:45 ajay Exp $
 * Description:  Base Form For Notes
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/


class NoteFormBase{

function getFormBody($prefix, $mod='',$formname='', $size='30',$script=true){
global $mod_strings;
$temp_strings = $mod_strings;
if(!empty($mod)){
	global $current_language;
	$mod_strings = return_module_language($current_language, $mod);
}
			global $app_strings;
			global $app_list_strings;

		$lbl_required_symbol = $app_strings['LBL_REQUIRED_SYMBOL'];
		$lbl_note_subject = $mod_strings['LBL_NOTE_SUBJECT'];
		$lbl_note_description = $mod_strings['LBL_NOTE'];
		$default_parent_type= $app_list_strings['record_type_default_key'];

			$form = <<<EOF
				<input type="hidden" name="${prefix}record" value="">
				<input type="hidden" name="${prefix}parent_type" value="${default_parent_type}">
<p>				<table cellspacing="0" cellpadding="0" border="0">
				<tr>
				    <td class="dataLabel">$lbl_note_subject <span class="required">$lbl_required_symbol</span></td>
				</tr>
				<tr>
				    <td class="dataField"><input name='${prefix}name' size='${size}' maxlength='255' type="text" value=""></td>
				</tr>
				<tr>
				    <td class="dataLabel">$lbl_note_description</td>
				</tr>
				<tr>
				    <td class="dataField"><textarea name='${prefix}description' cols='${size}' rows='4' ></textarea></td>
				</tr>
				</table></p>


EOF;
if ($script) {
	require_once('include/javascript/javascript.php');
	require_once('modules/Notes/Note.php');
	$javascript = new javascript();
	$javascript->setFormName($formname);
	$javascript->setSugarBean(new Note());
	$javascript->addRequiredFields($prefix);
	$form .=$javascript->getScript();
}
$mod_strings = $temp_strings;
return $form;
}

function getForm($prefix, $mod=''){
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

			<form name="${prefix}NoteSave" onSubmit="return check_form('${prefix}NoteSave')" method="POST" action="index.php">
				<input type="hidden" name="${prefix}module" value="Notes">
				<input type="hidden" name="${prefix}action" value="Save">
EOQ;
	$the_form .= $this->getFormBody($prefix, $mod, "${prefix}NoteSave", "20");
	$the_form .= <<<EOQ
			<p><input title="$lbl_save_button_title" accessKey="$lbl_save_button_key" class="button" type="submit" name="button" value="  $lbl_save_button_label  " ></p>
			</form>

EOQ;

	$the_form .= get_left_form_footer();
	$the_form .= get_validate_record_js();

	
	return $the_form;
}


function handleSave($prefix,$redirect=true, $useRequired=false){
	require_once('modules/Notes/Note.php');
	require_once('include/logging.php');
	require_once('include/formbase.php');
	require_once('include/upload_file.php');

	$local_log =& LoggerManager::getLogger('NoteSaveForm');
	$focus =& new Note();
	if($useRequired &&  !checkRequired($prefix, array_keys($focus->required_fields))){
		return null;
	}
	$focus = populateFromPost($prefix, $focus);

	if (!isset($_REQUEST['date_due_flag'])) $focus->date_due_flag = 'off';
	if (!isset($_REQUEST['portal_flag'])) $focus->portal_flag = '0';
	
	$upload_file = new UploadFile('uploadfile');

	$do_final_move = 0;

	if (isset($_FILES['uploadfile']) && $upload_file->confirm_upload())
	{

       		 if (!empty($focus->id) && !empty($_REQUEST['old_filename']) )
        	{
       	         $upload_file->unlink_file($focus->id,$_REQUEST['old_filename']);
       	 	}

	        $focus->filename = $upload_file->get_stored_file_name();
	        $focus->file_mime_type = $upload_file->mime_type;

       	 $do_final_move = 1;
	}
	else if ( isset( $_REQUEST['old_filename']))
	{
       	 $focus->filename = $_REQUEST['old_filename'];
	}


	$return_id = $focus->save();


	if ($do_final_move)
	{
       	 $upload_file->final_move($focus->id);
	}
	else if ( ! empty($_REQUEST['old_id']))
	{
       	 $upload_file->duplicate_file($_REQUEST['old_id'], $focus->id, $focus->filename);
	}


	if($redirect){
	$local_log->debug("Saved record with id of ".$return_id);
		handleRedirect($return_id, "Notes");
	}else{
		return $focus;
	}
}








}
?>
