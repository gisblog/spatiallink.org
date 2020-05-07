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
 * $Id: Forms.php,v 1.5.2.1 2005/05/06 00:59:03 ajay Exp $
 * Description:  Contains a variety of utility functions used to display UI
 * components such as form headers and footers.  Intended to be modified on a per
 * theme basis.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

/**
 * Create javascript to validate the data entered into a record.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_validate_record_document_revision_js () {
global $mod_strings;
global $app_strings;

$lbl_version = $mod_strings['LBL_DOC_VERSION'];
$lbl_filename = $mod_strings['LBL_FILENAME'];


$err_missing_required_fields = $app_strings['ERR_MISSING_REQUIRED_FIELDS'];


$the_script  = <<<EOQ

<script type="text/javascript" language="Javascript">
<!--  to hide script contents from old browsers

function trim(s) {
	while (s.substring(0,1) == " ") {
		s = s.substring(1, s.length);
	}
	while (s.substring(s.length-1, s.length) == ' ') {
		s = s.substring(0,s.length-1);
	}

	return s;
}

function verify_data(form) {
	var isError = false;
	var errorMessage = "";
	if (trim(form.revision.value) == "") {
		isError = true;
		errorMessage += "\\n$lbl_version";
	}	
	if (trim(form.uploadfile.value) == "") {
		isError = true;
		errorMessage += "\\n$lbl_filename";
	}	

	if (isError == true) {
		alert("$err_missing_required_fields" + errorMessage);
		return false;
	}

	return true;
}

// end hiding contents from old browsers  -->
</script>

EOQ;

return $the_script;
}

function get_chooser_js()
{
$the_script  = <<<EOQ

<script type="text/javascript" language="Javascript">
<!--  to hide script contents from old browsers

function set_chooser()
{



var display_tabs_def = '';

for(i=0; i < object_refs['display_tabs'].options.length ;i++)
{
         display_tabs_def += "display_tabs[]="+object_refs['display_tabs'].options[i].value+"&";
}

document.EditView.display_tabs_def.value = display_tabs_def;



}
// end hiding contents from old browsers  -->
</script>
EOQ;

return $the_script;
}
function get_validate_record_js(){
	
global $mod_strings;
global $app_strings;

$lbl_name = $mod_strings['ERR_DOC_NAME'];
$lbl_start_date = $mod_strings['ERR_DOC_ACTIVE_DATE'];
$lbl_file_name = $mod_strings['ERR_FILENAME'];
$lbl_file_version=$mod_strings['ERR_DOC_VERSION'];

$err_missing_required_fields = $app_strings['ERR_MISSING_REQUIRED_FIELDS'];

if(isset($_REQUEST['record'])) {
//do not validate upload file
	$the_upload_script="";


} else 
{

$the_upload_script  = <<<EOQ

	if (trim(form.uploadfile.value) == "") {
		isError = true;
		errorMessage += "\\n$lbl_file_name";
	}
EOQ;

}

$the_script  = <<<EOQ

<script type="text/javascript" language="Javascript">
<!--  to hide script contents from old browsers

function trim(s) {
	while (s.substring(0,1) == " ") {
		s = s.substring(1, s.length);
	}
	while (s.substring(s.length-1, s.length) == ' ') {
		s = s.substring(0,s.length-1);
	}

	return s;
}

function verify_data(form) {
	var isError = false;
	var errorMessage = "";
	if (trim(form.document_name.value) == "") {
		isError = true;
		errorMessage += "\\n$lbl_name";
	}
	
	$the_upload_script
	
	if (trim(form.active_date.value) == "") {
		isError = true;
		errorMessage += "\\n$lbl_start_date";
	}
	if (trim(form.revision.value) == "") {
		isError = true;
		errorMessage += "\\n$lbl_file_version";
	}

	if (isError == true) {
		alert("$err_missing_required_fields" + errorMessage);
		return false;
	}
	
	//make sure start date is <= end_date

	return true;
}

// end hiding contents from old browsers  -->
</script>

EOQ;

return $the_script;
}

?>
