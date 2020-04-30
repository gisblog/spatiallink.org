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

require_once('modules/DynamicFields/DynamicField.php');

$module = $_REQUEST['module_name'];
$custom_fields = new DynamicField($module);
if(!empty($module)){
			$class_name = $beanList[$module];
			$class_file = $class_name;
			if($class_file == 'aCase'){
				$class_file = 'Case';	
			}
			require_once("modules/$module/$class_file.php");
			$mod = new $class_name();
			$custom_fields->setup($mod);
}else{
	echo "\nNo Module Included Could Not Save";	
}
$name = $_REQUEST['field_label'];
$options = '';
if($_REQUEST['field_type'] == 'enum'){		
	$options = $_REQUEST['options'];
}
$default_value = '';

$custom_fields->addField($name,$name, $_REQUEST['field_type'],'255','optional', $default_value, $options, '', '' );
$html = $custom_fields->getFieldHTML($name, $_REQUEST['file_type']);

set_register_value('dyn_layout', 'field_counter', $_REQUEST['field_count']);
$label = $custom_fields->getFieldLabelHTML($name, $_REQUEST['field_type']);
require_once('modules/DynamicLayout/AddField.php');
$af = new AddField();
$af->add_field($name, $html,$label, 'window.opener.');
echo $af->get_script('window.opener.');
echo "\n<script>window.close();</script>";

?>
