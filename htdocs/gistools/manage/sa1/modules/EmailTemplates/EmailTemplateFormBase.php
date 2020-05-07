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
 * $Header: /var/cvsroot/sugarcrm/modules/EmailTemplates/EmailTemplateFormBase.php,v 1.1 2005/02/09 08:23:52 robert Exp $
 * Description:  Base Form For Notes
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/


class EmailTemplateFormBase{

function getFormBody($prefix, $mod='',$formname='', $size='30'){
global $mod_strings;
$temp_strings = $mod_strings;
if(!empty($mod)){
	global $current_language;
	$mod_strings = return_module_language($current_language, $mod);
}
			global $app_strings;
			global $app_list_strings;

		$lbl_required_symbol = $app_strings['LBL_REQUIRED_SYMBOL'];
		$lbl_subject = $mod_strings['LBL_NOTE_SUBJECT'];
		$lbl_description = $mod_strings['LBL_NOTE'];
		$default_parent_type= $app_list_strings['record_type_default_key'];

			$form = <<<EOF
				<input type="hidden" name="${prefix}record" value="">
				<input type="hidden" name="${prefix}parent_type" value="${default_parent_type}">
<p>				<table cellspacing="0" cellpadding="0" border="0">
				<tr>
				    <td class="dataLabel">$lbl_subject <span class="required">$lbl_required_symbol</span></td>
				</tr>
				<tr>
				    <td class="dataField"><input name='${prefix}name' size='${size}' maxlength='255' type="text" value=""></td>
				</tr>
				<tr>
				    <td class="dataLabel">$lbl_description</td>
				</tr>
				<tr>
				    <td class="dataField"><textarea name='${prefix}description' cols='${size}' rows='4' ></textarea></td>
				</tr>
				</table></p>


EOF;
require_once('include/javascript/javascript.php');
require_once('modules/EmailTemplates/EmailTemplate.php');
$javascript = new javascript();
$javascript->setFormName($formname);
$javascript->setSugarBean(new EmailTemplate());
$javascript->addRequiredFields($prefix);
$form .=$javascript->getScript();
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

			<form name="${prefix}EmailTemplateSave" onSubmit="return check_form('${prefix}EmailTemplateSave')" method="POST" action="index.php">
				<input type="hidden" name="${prefix}module" value="EmailTemplates">
				<input type="hidden" name="${prefix}action" value="Save">
EOQ;
	$the_form .= $this->getFormBody($prefix, $mod, "${prefix}EmailTemplateSave", "20");
	$the_form .= <<<EOQ
			<p><input title="$lbl_save_button_title" accessKey="$lbl_save_button_key" class="button" type="submit" name="button" value="  $lbl_save_button_label  " ></p>
			</form>

EOQ;

	$the_form .= get_left_form_footer();
	$the_form .= get_validate_record_js();

	
	return $the_form;
}


function handleSave($prefix,$redirect=true, $useRequired=false){
	require_once('modules/EmailTemplates/EmailTemplate.php');
	require_once('include/logging.php');
	require_once('include/formbase.php');
	require_once('include/upload_file.php');
	global $upload_maxsize, $upload_dir;

	$local_log =& LoggerManager::getLogger('EmailTemplateSaveForm');
	$focus = new EmailTemplate();
	if($useRequired &&  !checkRequired($prefix, array_keys($focus->required_fields))){
		return null;
	}
	$focus = populateFromPost($prefix, $focus);

	if (!isset($_REQUEST['published'])) $focus->published = 'off';
	

	$return_id = $focus->save();




	if($redirect){
	$local_log->debug("Saved record with id of ".$return_id);
		handleRedirect($return_id, "EmailTemplates");
	}else{
		return $focus;
	}
}








}
?>
