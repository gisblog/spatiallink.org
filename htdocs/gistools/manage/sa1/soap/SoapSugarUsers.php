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
require_once('soap/SoapHelperFunctions.php');
require_once('soap/SoapTypes.php');
/*************************************************************************************

THIS IS FOR SUGARCRM USERS


*************************************************************************************/

$server->register(
        'login',
        array('user_auth'=>'tns:user_auth', 'application_name'=>'xsd:string'),
        array('return'=>'tns:set_entry_result'),
        $NAMESPACE); 
        
function login($user_auth, $application){
	$error = new SoapError();
	$user = new User();
	$user = $user->retrieve_by_string_fields(array('user_name'=>$user_auth['user_name'],'user_hash'=>$user_auth['password'], 'deleted'=>0, 'status'=>'Active', 'portal_only'=>0) );
	if($user != null){
		session_start();
		global $current_user;
		$current_user = $user;
		$_SESSION['is_valid_session']= true;
		$_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
		$_SESSION['user_id'] = $user->id;
		$_SESSION['type'] = 'user';
		login_success();
		return array('id'=>session_id(), 'error'=>$error);	
	}
	$error->set_error('invalid_login');
	return array('id'=>-1, 'error'=>$error);
	
}

/*
this validates the session and starts the session;
*/
function validate_authenticated($session_id){
	session_id($session_id);
	session_start();
	
	if(!empty($_SESSION['is_valid_session']) && $_SESSION['ip_address'] == $_SERVER['REMOTE_ADDR'] && $_SESSION['type'] == 'user'){
		global $current_user;
		require_once('modules/Users/User.php');
		$current_user = new User();
		$current_user->retrieve($_SESSION['user_id']);
		login_success();
		return true;	
	}
	
	session_destroy();
	return false;
}

$server->register(
    'seamless_login',
    array('session'=>'xsd:string'),
    array('return'=>'xsd:int'),
    $NAMESPACE);
    
    function seamless_login($session){
    		if(!validate_authenticated($session)){
    			return 0;
    		}
    		$_SESSION['seamless_login'] = true;
    		return 1;
    }

$server->register(
    'get_entry_list',
    array('session'=>'xsd:string', 'module_name'=>'xsd:string', 'query'=>'xsd:string', 'order_by'=>'xsd:string','offset'=>'xsd:int', 'select_fields'=>'tns:select_fields', 'max_results'=>'xsd:int'),
    array('return'=>'tns:get_entry_list_result'),
    $NAMESPACE);

function get_entry_list($session, $module_name, $query, $order_by,$offset, $select_fields, $max_results){
	global $log, $beanList, $beanFiles;
	$error = new SoapError();
	if(!validate_authenticated($session)){
		$error->set_error('invalid_login');	
		return array('result_count'=>-1, 'entry_list'=>array(), 'error'=>$error->get_soap_array());
	}
	if(empty($beanList[$module_name])){
		$error->set_error('no_module');	
		return array('result_count'=>-1, 'entry_list'=>array(), 'error'=>$error->get_soap_array());
	}
	if($max_results > 0){
		global $sugar_config;
		$sugar_config['list_max_entries_per_page'] = $max_results;	
	}
	

	$class_name = $beanList[$module_name];
	require_once($beanFiles[$class_name]);
	$seed = new $class_name();
	if($query == ''){
		$where = '';
	}
	if($offset == '' || $offset == -1){
		$offset = 0;
	}
	$response = $seed->get_list($order_by, $query, $offset);
	$list = $response['list'];
	
	
	$output_list = Array();


	$field_list = array();
	foreach($list as $value)
	{
		
		//$loga->fatal("Adding another account to the list");
		$output_list[] = get_return_value($value, $module_name);
		if(empty($field_list)){
			$field_list = get_field_list($value);	
		}
	}
	$output_list = filter_return_list($output_list, $select_fields, $module_name);
	$field_list = filter_field_list($field_list,$select_fields, $module_name);
	$next_offset = $offset + sizeof($output_list);

	return array('result_count'=>sizeof($output_list), 'next_offset'=>$next_offset,'field_list'=>$field_list, 'entry_list'=>$output_list, 'error'=>$error->get_soap_array());
}

$server->register(
    'get_entry',
    array('session'=>'xsd:string', 'module_name'=>'xsd:string', 'id'=>'xsd:string', 'select_fields'=>'tns:select_fields'),
    array('return'=>'tns:get_entry_result'),
    $NAMESPACE);

function get_entry($session, $module_name, $id,$select_fields ){
	global $log, $beanList, $beanFiles;
	$error = new SoapError();
	if(!validate_authenticated($session)){
		$error->set_error('invalid_login');	
		return array('result_count'=>-1, 'entry_list'=>array(), 'error'=>$error->get_soap_array());
	}
	if(empty($beanList[$module_name])){
		$error->set_error('no_module');	
		return array('result_count'=>-1, 'entry_list'=>array(), 'error'=>$error->get_soap_array());
	}
	
	$class_name = $beanList[$module_name];
	require_once($beanFiles[$class_name]);
	$seed = new $class_name();
	$seed->retrieve($id);
	
	
	$output_list = Array();
	
		
		//$loga->fatal("Adding another account to the list");
	$output_list[] = get_return_value($seed, $module_name);
	$field_list = array();
	if(empty($field_list)){
			$field_list = get_field_list($seed);	
		}
	
	$output_list = filter_return_list($output_list, $select_fields, $module_name);
	$field_list = filter_field_list($field_list,$select_fields, $module_name);

	return array( 'field_list'=>$field_list, 'entry_list'=>$output_list, 'error'=>$error->get_soap_array());
}
  
$server->register(
    'set_entry',
    array('session'=>'xsd:string', 'module_name'=>'xsd:string',  'name_value_list'=>'tns:name_value_list'),
    array('return'=>'tns:set_entry_result'),
    $NAMESPACE);
    
function set_entry($session,$module_name, $name_value_list){
	global $log, $beanList, $beanFiles;
	
	$error = new SoapError();
	if(!validate_authenticated($session)){
		$error->set_error('invalid_login');	
		return array('result_count'=>-1, 'entry_list'=>array(), 'error'=>$error->get_soap_array());
	}
	if(empty($beanList[$module_name])){
		$error->set_error('no_module');	
		return array('id'=>-1, 'error'=>$error->get_soap_array());
	}
	
	$class_name = $beanList[$module_name];
	require_once($beanFiles[$class_name]);
	$seed = new $class_name();
	foreach($name_value_list as $value){
		$seed->$value['name'] = $value['value'];	
	}
	$seed->save();
	return array('id'=>$seed->id, 'error'=>$error->get_soap_array());
	
}

/*

NOTE SPECIFIC CODE
*/
$server->register(
        'set_note_attachment',
        array('session'=>'xsd:string','note'=>'tns:note_attachment'),
        array('return'=>'tns:set_entry_result'),
        $NAMESPACE);  

function set_note_attachment($user_auth,$note)
{
	
	$error = new SoapError();
	if(!validate_authenticated($session)){
		$error->set_error('invalid_login');	
		return array('result_count'=>-1, 'entry_list'=>array(), 'error'=>$error->get_soap_array());
	}
	require_once('modules/Notes/NoteSoap.php');
	$ns = new NoteSoap();
	return array('id'=>$ns->saveFile($note), 'error'=>$error->get_soap_array());

}

$server->register(
    'get_note_attachment',
    array('session'=>'xsd:string', 'id'=>'xsd:string'),
    array('return'=>'tns:return_note_attachment'),
    $NAMESPACE);

function get_note_attachment($user_auth,$id)
{
	$error = new SoapError();
	if(!validate_authenticated($session)){
		$error->set_error('invalid_login');	
		return array('result_count'=>-1, 'entry_list'=>array(), 'error'=>$error->get_soap_array());
	}
	require_once('modules/Notes/Note.php');
	$note = new Note();
	$note->retrieve($id);
	require_once('modules/Notes/NoteSoap.php');
	$ns = new NoteSoap();
	if(!isset($note->filename)){
		$note->filename = '';
	}
	$file= $ns->retrieveFile($id,$note->filename);
	if($file == -1){
		$error->set_error('no_file');
		$file = '';
	}

	return array('note_attachment'=>array('id'=>$id, 'filename'=>$note->filename, 'file'=>$file), 'error'=>$error->get_soap_array());

}
$server->register(
    'relate_note_to_module',
    array('session'=>'xsd:string', 'note_id'=>'xsd:string', 'module_name'=>'xsd:string', 'module_id'=>'xsd:string'),
    array('return'=>'tns:error_value'),
    $NAMESPACE);

function relate_note_to_module($user_auth,$note_id, $module_name, $module_id){
	global $log, $beanList, $beanFiles;
	$error = new SoapError();
	if(!validate_authenticated($session)){
		$error->set_error('invalid_login');	
		return array('result_count'=>-1, 'entry_list'=>array(), 'error'=>$error->get_soap_array());
	}
	if(empty($beanList[$module_name])){
		$error->set_error('no_module');	
		return $error->get_soap_array();
	}
	$class_name = $beanList[$module_name];
	require_once($beanFiles[$class_name]);
	$seed = new $class_name();
	if($module_name == 'Cases'){
		$seed->set_note_relationship($module_id, $note_id);
	}else{
		$error->set_error('no_module_support');	
		$error->description .= ': '. $module_name;
	}
	return $error->get_soap_array();
	
}
$server->register(
    'get_related_notes',
    array('session'=>'xsd:string', 'module_name'=>'xsd:string', 'module_id'=>'xsd:string', 'select_fields'=>'tns:select_fields'),
    array('return'=>'tns:get_entry_result'),
    $NAMESPACE);
    
function get_related_notes($user_auth,$module_name, $module_id, $select_fields){
	global $log, $beanList, $beanFiles;
	$error = new SoapError();
	if(!validate_authenticated($session)){
		$error->set_error('invalid_login');	
		return array('result_count'=>-1, 'entry_list'=>array(), 'error'=>$error->get_soap_array());
	}
	if(empty($beanList[$module_name])){
		$error->set_error('no_module');	
		return array('result_count'=>-1, 'entry_list'=>array(), 'error'=>$error->get_soap_array());
	}
	$class_name = $beanList[$module_name];
	require_once($beanFiles[$class_name]);
	$seed = new $class_name();
	$seed->id = $module_id;
	$list = $seed->get_notes();

	
	
	$output_list = Array();
	$field_list = Array();
	foreach($list as $value)
	{
		
		//$loga->fatal("Adding another account to the list");
		$output_list[] = get_return_value($value, 'Notes');
	if(empty($field_list)){
			$field_list = get_field_list($value);	
		}
	}
	$output_list = filter_return_list($output_list, $select_fields, $module_name);
	$field_list = filter_field_list($field_list,$select_fields, $module_name);
	

	return array('result_count'=>sizeof($output_list), 'next_offset'=>0,'field_list'=>$field_list, 'entry_list'=>$output_list, 'error'=>$error->get_soap_array());
}

$server->register(
        'logout',
        array('session'=>'xsd:string'),
        array('return'=>'tns:error_value'),
        $NAMESPACE); 
        
function logout($session){
	$error = new SoapError();
	if(validate_authenticated($session)){
		session_destroy();
		return $error->get_soap_array();
	}
	$error->set_error('no_session');
	return $error;
}

$server->register(
    'get_module_fields',
    array('session'=>'xsd:string', 'module_name'=>'xsd:string'),
    array('return'=>'tns:module_fields'),
    $NAMESPACE);

function get_module_fields($session, $module_name){
	global $log, $beanList, $beanFiles;
	$error = new SoapError();
	$module_fields = array();
	if(! validate_authenticated($session)){
		$error->set_error('invalid_session');	
		return array('module_fields'=>$module_fields, 'error'=>$error->get_soap_array());
	}
	if(empty($beanList[$module_name])){
		$error->set_error('no_module');	
		return array('module_fields'=>$module_fields, 'error'=>$error->get_soap_array());
	}
	$class_name = $beanList[$module_name];
	require_once($beanFiles[$class_name]);
	$seed = new $class_name();
	return get_return_module_fields($seed, $module_name, $error);
}
$server->register(
    'update_portal_user',
    array('session'=>'xsd:string', 'portal_name'=>'xsd:string', 'name_value_list'=>'tns:name_value_list'),
    array('return'=>'tns:error_value'),
    $NAMESPACE);
function update_portal_user($session,$portal_name, $name_value_list){
	global $log, $beanList, $beanFiles;
	$error = new SoapError();
	if(! validate_authenticated($session)){
		$error->set_error('invalid_session');	
		return $error->get_soap_array();
	}
	$contact = new Contact();
	$searchBy = array('deleted'=>0);
	foreach($name_value_list as $name_value){
			$searchBy[$name_value['name']] = $name_value['value'];
	}
	if($contact->retrieve_by_string_fields($searchBy) != null){
		if(!$contact->duplicates_found){
			$contact->portal_name = $portal_name;
			$contact->portal_active = 1;
			$contact->save();
			return $error->get_soap_array();
		}	
		$error->set_error('duplicates');
		return $error->get_soap_array();
	}
	$error->set_error('no_records');
	return $error->get_soap_array();
	
	
}

$server->register(
    'test',
    array('string'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);
function test($string){
	return $string;	
}

$server->register(
    'get_user_id',
    array('session'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);
function get_user_id($session){
	if(validate_authenticated($session)){
		global $current_user;
		return $current_user->id;
	}else{
		return '-1';	
	}
}


?>
