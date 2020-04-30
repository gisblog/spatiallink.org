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


$custom_fields = new CustomFields();
$newField = $custom_fields->save_field($_REQUEST);
$name = $newField['name'];

if($_REQUEST['file_type'] == 'edit' || $_REQUEST['file_type'] == 'search'){
	$html = $custom_fields->get_edit_html($_REQUEST['module_name'], $name);
}else{
	$html = $custom_fields->get_detail_html($_REQUEST['module_name'], $name);	
}
set_register_value('dyn_layout', 'field_counter', $_REQUEST['field_count']);
$label = $custom_fields->get_html_label($_REQUEST['module_name'], $name);
require_once('modules/DynamicLayout/AddField.php');
$af = new AddField();
$af->add_field($name, $html,$label, 'window.opener.');
echo $af->get_script('window.opener.');
echo "\n<script>window.close();</script>";

?>
