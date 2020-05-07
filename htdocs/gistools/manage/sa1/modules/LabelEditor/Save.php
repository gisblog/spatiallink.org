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
global $current_language;
$module_name = $_REQUEST['module_name'];
if(isset($_REQUEST['multi_edit'])){
	unset($_REQUEST['action']);
	unset($_REQUEST['module_name']);
	unset($_REQUEST['module']);
	$the_strings = return_module_language($current_language, $module_name);
	foreach($_REQUEST as $key=>$value){
		if(isset($the_strings[$key])){
			create_field_label($module_name, $current_language, $key, $value, true);
		}
	}
	$location = "index.php?action=LabelList&module=LabelEditor&refreshparent=1&sugar_body_only=1";
	header("Location:$location" );
}else{
	create_field_label($module_name, $current_language, $_REQUEST['record'], $_REQUEST['value'], true);
		$location = "index.php?action=". $_REQUEST['return_action']."&module=". $_REQUEST['return_module'];
	if(isset($_REQUEST['module_name'])){
		$location .= "&module_name=" . $_REQUEST['module_name'];
	}
	if(isset($_REQUEST['sugar_body_only'])){
		$location .= "&sugar_body_only=" . $_REQUEST['sugar_body_only'];
	}
	if(isset($_REQUEST['style']) && $_REQUEST['style'] == 'popup'){
		$location .= '&refreshparent=1';	
	}
	header("Location:$location" );
}


?>
