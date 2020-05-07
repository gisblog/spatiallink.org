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
// $Id: Delete.php,v 1.3.2.1 2005/05/17 22:29:54 majed Exp $

require_once('modules/EditCustomFields/EditCustomFields.php');
require_once('modules/DynamicFields/DynamicField.php');
require_once('include/logging.php');
require_once('modules/DynamicFields/DynamicField.php');


if(!empty($_REQUEST['record']))
{
$fields_meta_data =& new FieldsMetaData($_REQUEST['record']);
$fields_meta_data->retrieve($_REQUEST['record']);
$module = $fields_meta_data->custom_module;
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
	echo "\nNo Module Included Could Not Delete";	
}



$custom_fields->dropField($fields_meta_data->name);
$fields_meta_data->mark_deleted($_REQUEST['record']);
	
	
}

$return_module = empty($_REQUEST['return_module']) ? 'EditCustomFields'
	: $_REQUEST['return_module'];

$return_action = empty($_REQUEST['return_action']) ? 'index'
	: $_REQUEST['return_action'];

$return_module_select = empty($_REQUEST['module_name']) ? 0
	: $_REQUEST['module_name'];

header("Location: index.php?action=$return_action&module=$return_module&module_name=$return_module_select");


?>
