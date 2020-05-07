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
function get_field_value($field,$type, $label, $required, $options_dom){
	return array('name'=>$field, 'type'=>$type, 'label'=>get_field_label_value($label), 'required'=>$required, 'options'=>get_field_options($options_dom));
}

function get_field_label_value($label){
	global $module_name;
	return translate($label, $module_name);
}

function get_field_options($options_dom){
	global $module_name,$app_list_strings;
	if(empty($options_dom)){
		return array();	
	}
	$options =  translate($options_dom, $module_name);
	$options_ret = array();
	foreach($options as $name=>$value){
			$options_ret[] =  array('name'=> $name , 'value'=>$value);
	}
	return $options_ret;
}

function get_field_list(&$value){
	$list = array();
	if(!empty($value->field_defs)){
		foreach($value->field_defs as $var){
			$required = 0;
			$options_dom = '';
			if(isset($value->$var['name'])){
				if(isset($value->required_fields) && key_exists($var['name'], $value->required_fields)){
					$required = 1;	
				}
				if(isset($var['options'])){
					$options_dom = $var['options'];	
				}
				$list[$var['name']] = get_field_value($var['name'], $var['type'], $var['vname'],$required, $options_dom);
			}
		}
	}
	return $list;	
	
}


function get_name_value($field,$value){
	return array('name'=>$field, 'value'=>$value);
}


function get_name_value_list(&$value){
	$list = array();
	if(!empty($value->field_defs)){
		foreach($value->field_defs as $var){
			if(isset($value->$var['name'])){
				$list[$var['name']] = get_name_value($var['name'], $value->$var['name']);
			}
		}
	}
	return $list;	
	
}

function get_return_value(&$value, $module){
	global $module_name;
	$module_name = $module;
	return Array('id'=>$value->id,
				'module_name'=> $module,
				'name_value_list'=>get_name_value_list($value)
				);	
}

function get_module_field_list(&$value){
	$list = array();
	if(!empty($value->field_defs)){
		foreach($value->field_defs as $var){
			$required = 0;
			$options_dom = '';
			
				if(isset($value->required_fields) && key_exists($var['name'], $value->required_fields)){
					$required = 1;	
				}
				if(isset($var['options'])){
					$options_dom = $var['options'];	
				}
				$list[$var['name']] = get_field_value($var['name'], $var['type'], $var['vname'],$required, $options_dom);
			
		}
		}
	
	return $list;	
}

function get_return_module_fields(&$value, $module, &$error){
	global $module_name;
	$module_name = $module;
	return Array('module_name'=>$module,
				'module_fields'=> get_module_field_list($value),
				'error'=>get_name_value_list($value)
				);	
}

function get_return_error_value($error_num, $error_name, $error_description){
	return Array('number'=>$error_num,
				'name'=> $error_name,
				'description'=>	$error_description
				);
}

function filter_field_list(&$field_list, &$select_fields, $module_name){
	$key_value_list = values_to_keys($select_fields);
	if($module_name == 'Contacts'){
		global $invalid_contact_fields;
		if(is_array($invalid_contact_fields)){
		foreach($invalid_contact_fields as $name=>$val){
			if(isset($field_list[$name])){
					unset($field_list[$name]);
			}
			
		}	
		}
	}
	if( is_array($field_list) && !empty($key_value_list) && is_array($key_value_list)){
		while($current_value = current($field_list)){
				if(!key_exists($current_value['name'], $key_value_list)){
					if(isset($field_list[key($field_list)])){
						unset($field_list[key($field_list)]);
					}
					
				}else{
					next($field_list);
				}
		}
	}
	
	return $field_list;
	
}
function filter_return_list(&$output_list, &$select_fields, $module_name){
	$key_value_list = values_to_keys($select_fields);
	
	for($sug = 0; $sug < sizeof($output_list) ; $sug++){
	$field_list = & $output_list[$sug]['field_list'];
	$name_list = & $output_list[$sug]['name_value_list'];
	if($module_name == 'Contacts'){
		global $invalid_contact_fields;
		if(is_array($invalid_contact_fields)){
		foreach($invalid_contact_fields as $name=>$val){
			if(isset($field_list[$name])){
						unset($field_list[$name]);
			}
			unset($name_list[$name]);
		}	
		}
	}
	if( is_array($name_list) && !empty($key_value_list) && is_array($key_value_list)){
		while($current_value = current($name_list)){
				if(!key_exists($current_value['name'], $key_value_list)){
					if(isset($field_list[key($name_list)])){
						unset($field_list[key($name_list)]);
					}
					unset($name_list[key($name_list)]);
					
				}else{
					next($name_list);
				}
		}
	}
	}
	return $output_list;
	
}

function login_success(){
	global $current_language, $sugar_config, $app_strings, $app_list_strings;
	$current_language = $sugar_config['default_language'];
	$app_strings = return_application_language($current_language);
	$app_list_strings = return_app_list_strings_language($current_language);	
}




?>
