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
require_once('modules/Contacts/Contact.php');
require_once('modules/Accounts/Account.php');
require_once('soap/SoapPortalHelper.php');
require_once('config.php');
/*************************************************************************************

THIS IS FOR PORTAL USERS


*************************************************************************************/
/*
this authenticates a user as a portal user and returns the session id or it returns false otherwise;
*/
$server->register(
        'portal_login',
        array('portal_auth'=>'tns:user_auth','user_name'=>'xsd:string', 'application_name'=>'xsd:string'),
        array('return'=>'tns:set_entry_result'),
        $NAMESPACE); 
        
function portal_login($portal_auth, $user_name, $application_name){
	$error =& new SoapError();
	$contact =& new Contact();
	$user =& new User();
	
	$user = $user->retrieve_by_string_fields(array('user_name'=>$portal_auth['user_name'],'user_hash'=>$portal_auth['password'], 'deleted'=>0, 'status'=>'Active', 'portal_only'=>1) );
	if($user != null){
		global $current_user;
		$current_user = $user;
	}else{
		$error->set_error('no_portal');
		return array('id'=>-1, 'error'=>$error->get_soap_array());	
	}
	
	
	if($user_name == 'lead'){
		session_start();
		$_SESSION['is_valid_session']= true;
		$_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
		$_SESSION['portal_id'] = $user->id;	
		$_SESSION['type'] = 'lead';
		login_success();
		return array('id'=>session_id(), 'error'=>$error->get_soap_array());	
	}else if($user_name == 'portal'){
		session_start();
		$_SESSION['is_valid_session']= true;
		$_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
		$_SESSION['portal_id'] = $user->id;	
		$_SESSION['type'] = 'portal';
		login_success();
		return array('id'=>session_id(), 'error'=>$error->get_soap_array());	
	}else{
	$contact = $contact->retrieve_by_string_fields(array('portal_name'=>$user_name, 'portal_active'=>'1', 'deleted'=>0) );
	if($contact != null){
		session_start();
		$_SESSION['is_valid_session']= true;
		$_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
		$_SESSION['user_id'] = $contact->id;
		$_SESSION['portal_id'] = $user->id;
		
		$_SESSION['type'] = 'contact';



		$_SESSION['assigned_user_id'] = $contact->assigned_user_id;
		login_success();
		build_relationship_tree($contact);
		return array('id'=>session_id(), 'error'=>$error->get_soap_array());	
	}
	}
	$error->set_error('invalid_login');
	return array('id'=>-1, 'error'=>$error->get_soap_array());
}
/*
this validates the session and starts the session;
*/
function portal_validate_authenticated($session_id){
	session_id($session_id);
	session_start();
	if(!empty($_SESSION['is_valid_session']) && $_SESSION['ip_address'] == $_SERVER['REMOTE_ADDR'] && ($_SESSION['type'] == 'contact' || $_SESSION['type'] == 'lead' || $_SESSION['type'] == 'portal')){
		global $current_user;
		$current_user = new User();
		$current_user->retrieve($_SESSION['portal_id']);
		login_success();
		return true;	
	}
	
	session_destroy();
	return false;
}


$server->register(
        'portal_logout',
        array('session'=>'xsd:string'),
        array('return'=>'tns:error_value'),
        $NAMESPACE); 
function portal_logout($session){
	$error = new SoapError();
	if(portal_validate_authenticated($session)){
		session_destroy();
		return $error->get_soap_array();
	}
	$error->set_error('no_session');
	return $error->get_soap_array();
}

$server->register(
        'portal_get_portal_id',
        array('session'=>'xsd:string'),
        array('return'=>'tns:set_entry_result'),
        $NAMESPACE);
function portal_get_sugar_id($session){
	$error = new SoapError();
	if(portal_validate_authenticated($session)){
		return array('id'=>$_SESSION['portal_id'], 'error'=>$error->get_soap_array());
	}
	$error->set_error('no_session');
	return array('id'=>-1, 'error'=>$error->get_soap_array());
	
}


$server->register(
    'portal_get_entry_list',
    array('session'=>'xsd:string', 'module_name'=>'xsd:string','where'=>'xsd:string', 'order_by'=>'xsd:string', 'select_fields'=>'tns:select_fields'),
    array('return'=>'tns:get_entry_list_result'),
    $NAMESPACE);

function portal_get_entry_list($session, $module_name,$where, $order_by, $select_fields){
	global $log, $beanList, $beanFiles, $portal_modules;
	$error = new SoapError();
	if(! portal_validate_authenticated($session)){
		$error->set_error('invalid_session');	
		return array('result_count'=>-1, 'entry_list'=>array(), 'error'=>$error->get_soap_array());
	}
	if($_SESSION['type'] == 'lead' ){
		$error->set_error('no_access');	
		return array('result_count'=>-1, 'entry_list'=>array(), 'error'=>$error->get_soap_array());	
	}
	if(empty($beanList[$module_name])){
		$error->set_error('no_module');	
		return array('result_count'=>-1, 'entry_list'=>array(), 'error'=>$error->get_soap_array());
	}
	if($module_name == 'Cases'){
		if(!isset($_SESSION['viewable'][$module_name])){
			get_cases_in_contacts(get_contacts_in());
			get_cases_in_accounts(get_accounts_in());
		}
		$sugar =& new aCase();	
		$list =  get_related_list(get_module_in($module_name), new aCase(), $where,$order_by);	

			
	}else if($module_name == 'Contacts'){
			$sugar =& new Contacts();	
			$list =  get_related_list(get_module_in($module_name), new Contact(), $where,$order_by);
	}else if($module_name == 'Accounts'){
			$sugar =& new Accounts();	
			$list =  get_related_list(get_module_in($module_name), new Account(), $where,$order_by);	
	}else if($module_name == 'Bugs'){
			if(!isset($_SESSION['viewable'][$module_name])){
				get_bugs_in_contacts(get_contacts_in());
				get_bugs_in_accounts(get_accounts_in());
			}
			
				$list = get_related_list(get_module_in($module_name), new Bug(), $where,$order_by);	
			
	}else{
		$error->set_error('no_module_support');	
		return array('result_count'=>-1, 'entry_list'=>array(), 'error'=>$error->get_soap_array());
		
	}
			

	$output_list = Array();
	$field_list = array();
	foreach($list as $value)
	{
		
		//$loga->fatal("Adding another account to the list");
		$output_list[] = get_return_value($value, $module_name);
		$_SESSION['viewable'][$module_name][$value->id] = $value->id;
	if(empty($field_list)){
			$field_list = get_field_list($value);	
		}
	}
	$output_list = filter_return_list($output_list, $select_fields, $module_name);
	$field_list = filter_field_list($field_list,$select_fields, $module_name);

	return array('result_count'=>sizeof($output_list), 'next_offset'=>0,'field_list'=>$field_list, 'entry_list'=>$output_list, 'error'=>$error->get_soap_array());
}

$server->register(
    'portal_get_entry',
    array('session'=>'xsd:string', 'module_name'=>'xsd:string', 'id'=>'xsd:string', 'select_fields'=>'tns:select_fields'),
    array('return'=>'tns:get_entry_result'),
    $NAMESPACE);

function portal_get_entry($session, $module_name, $id,$select_fields ){
	global $log, $beanList, $beanFiles;
	$error = new SoapError();
	if(! portal_validate_authenticated($session)){
		$error->set_error('invalid_session');	
		return array('result_count'=>-1, 'entry_list'=>array(), 'error'=>$error->get_soap_array());
	}
	if($_SESSION['type'] == 'lead' ){
		$error->set_error('no_access');	
		return array('result_count'=>-1, 'entry_list'=>array(), 'error'=>$error->get_soap_array());	
	}
	if(empty($beanList[$module_name])){
		$error->set_error('no_module');	
		return array('result_count'=>-1, 'entry_list'=>array(), 'error'=>$error->get_soap_array());
	}
	if(empty($_SESSION['viewable'][$module_name][$id])){
		
		$error->set_error('no_access');	
		return array('result_count'=>-1, 'entry_list'=>array(), 'error'=>$error->get_soap_array());	
	} 
	
	$class_name = $beanList[$module_name];
	require_once($beanFiles[$class_name]);
	$seed =& new $class_name();



	$seed->retrieve($id);
	
	
	$output_list = Array();
	
		
		//$loga->fatal("Adding another account to the list");
	$output_list[] = get_return_value($seed, $module_name);
	
	$output_list = filter_return_list($output_list, $select_fields, $module_name);
	$field_list = array();
	if(empty($field_list)){
			$field_list = get_field_list($value);	
	}
	$output_list = filter_return_list($output_list, $select_fields, $module_name);
	$field_list = filter_field_list($field_list,$select_fields, $module_name);

	return array('field_list'=>$field_list, 'entry_list'=>$output_list, 'error'=>$error->get_soap_array());
}
  
$server->register(
    'portal_set_entry',
    array('session'=>'xsd:string', 'module_name'=>'xsd:string',  'name_value_list'=>'tns:name_value_list'),
    array('return'=>'tns:set_entry_result'),
    $NAMESPACE);
    
function portal_set_entry($session,$module_name, $name_value_list){
	global $log, $beanList, $beanFiles, $valid_modules_for_contact;

	$error = new SoapError();
	if(!portal_validate_authenticated($session)){
		$error->set_error('invalid_session');	
		return array('id'=>-1,  'error'=>$error->get_soap_array());
	}
	if(empty($beanList[$module_name])){
		$error->set_error('no_module');	
		return array('id'=>-1, 'error'=>$error->get_soap_array());
	}
	if($_SESSION['type'] == 'lead' && $module_name != 'Leads'){
		$error->set_error('no_access');	
		return array('id'=>-1, 'error'=>$error->get_soap_array());	
	}
	
	if($_SESSION['type'] == 'contact' && !key_exists($module_name, $valid_modules_for_contact) ){
		$error->set_error('no_access');	
		return array('id'=>-1, 'error'=>$error->get_soap_array());	
	}
	
	
	$class_name = $beanList[$module_name];
	require_once($beanFiles[$class_name]);
	$seed = new $class_name();
	$is_update = false;
	$values_set = array();
	
	foreach($name_value_list as $value){
		if(isset($seed->$value['name']) && $seed->$value['name'] == 'id' && !empty($value['value'])){
			$is_update = true;	
		}
		$values_set[$value['name']] = $value['value'];
		$seed->$value['name'] = $value['value'];	
	}
	
	if(!isset($_SESSION['viewable'][$module_name])){
		$_SESSION['viewable'][$module_name] = array();	
	}
	
	if(!$is_update){





		if(isset($_SESSION['assigned_user_id']) && (!key_exists('assigned_user_id', $values_set) || empty($values_set['assigned_user_id']))){
			$seed->assigned_user_id = $_SESSION['assigned_user_id'];
		}	
		if(isset($_SESSION['account_id']) && (!key_exists('account_id', $values_set) || empty($values_set['account_id']))){
			$seed->account_id = $_SESSION['account_id'];	
		}
		$seed->portal_flag = 1;
	}



	$id = $seed->save();



	set_module_in(array('in'=>"('$id')", 'list'=>array($id)), $module_name);
	if($_SESSION['type'] == 'contact' && $module_name != 'Contacts' && !$is_update){
		if($module_name == 'Notes'){
			$seed->contact_id = $_SESSION['user_id'];
			if(isset( $_SESSION['account_id'])){
				$seed->parent_type = 'Accounts';
				$seed->parent_id = $_SESSION['account_id'];
				
			}
			$id = $seed->save();
		}else{
			$seed->set_contact_relationship($id, $_SESSION['user_id']);
			if(isset( $_SESSION['account_id'])){
				$seed->set_account_relationship($id,$_SESSION['account_id']); 
			}
		}
	}
	return array('id'=>$id, 'error'=>$error->get_soap_array());
	
}

 

/*

NOTE SPECIFIC CODE
*/
$server->register(
        'portal_set_note_attachment',
        array('session'=>'xsd:string','note'=>'tns:note_attachment'),
        array('return'=>'tns:set_entry_result'),
        $NAMESPACE);  

function portal_set_note_attachment($session,$note)
{
	$error = new SoapError();
	if(!portal_validate_authenticated($session)){
		$error->set_error('invalid_session');
		return array('id'=>'-1', 'error'=>$error->get_soap_array());
	}
	if($_SESSION['type'] == 'lead' || !isset($_SESSION['viewable']['Notes'][$note['id']])){
		$error->set_error('no_access');	
		return array('id'=>-1, 'error'=>$error->get_soap_array());	
	}
	require_once('modules/Notes/NoteSoap.php');
	$ns = new NoteSoap();
	$id = $ns->saveFile($note, true);
	return array('id'=>$id, 'error'=>$error->get_soap_array());

}

$server->register(
    'portal_get_note_attachment',
    array('session'=>'xsd:string', 'id'=>'xsd:string'),
    array('return'=>'tns:return_note_attachment'),
    $NAMESPACE);

function portal_get_note_attachment($session,$id)
{
	
	$error = new SoapError();
	if(! portal_validate_authenticated($session)){
		$error->set_error('invalid_session');	
		return array('result_count'=>-1, 'entry_list'=>array(), 'error'=>$error->get_soap_array());
	}
	if($_SESSION['type'] == 'lead' || !isset($_SESSION['viewable']['Notes'][$id])){
		$error->set_error('no_access');	
		return array('result_count'=>-1, 'entry_list'=>array(), 'error'=>$error->get_soap_array());	
	}
	$current_user = & $seed_user;
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
    'portal_relate_note_to_module',
    array('session'=>'xsd:string', 'note_id'=>'xsd:string', 'module_name'=>'xsd:string', 'module_id'=>'xsd:string'),
    array('return'=>'tns:error_value'),
    $NAMESPACE);

function portal_relate_note_to_module($session,$note_id, $module_name, $module_id){
	global $log, $beanList, $beanFiles, $current_user;
	$error = new SoapError();
	if(! portal_validate_authenticated($session)){
		$error->set_error('invalid_session');	
		return $error->get_soap_array();
		}
	if($_SESSION['type'] == 'lead' || !isset($_SESSION['viewable']['Notes'][$note_id]) || !isset($_SESSION['viewable'][$module_name][$module_id])){
		$error->set_error('no_access');	
		return $error->get_soap_array();
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
    'portal_get_related_notes',
    array('session'=>'xsd:string', 'module_name'=>'xsd:string', 'module_id'=>'xsd:string',  'select_fields'=>'tns:select_fields'),
    array('return'=>'tns:get_entry_result'),
    $NAMESPACE);
    
function portal_get_related_notes($session,$module_name, $module_id, $select_fields){
	global $log, $beanList, $beanFiles;
	$error = new SoapError();
	if(! portal_validate_authenticated($session)){
		$error->set_error('invalid_session');	
		return array('result_count'=>-1, 'entry_list'=>array(), 'error'=>$error->get_soap_array());
	}
	if($_SESSION['type'] == 'lead' ){
		$error->set_error('no_access');	
		return array('result_count'=>-1, 'entry_list'=>array(), 'error'=>$error->get_soap_array());	
	}
	if(empty($beanList[$module_name])){
		$error->set_error('no_module');	
		return array('result_count'=>-1, 'entry_list'=>array(), 'error'=>$error->get_soap_array());
	}
	if(empty($_SESSION['viewable'][$module_name][$module_id])){
		$error->set_error('no_access');	
		return array('result_count'=>-1, 'entry_list'=>array(), 'error'=>$error->get_soap_array());	
	}
	
	if($module_name =='Contacts'){
		if($_SESSION['user_id'] != $module_id){
			$error->set_error('no_access');	
			return array('result_count'=>-1, 'entry_list'=>array(), 'error'=>$error->get_soap_array());	
		}
		$list = get_notes_in_contacts("('$module_id')");
	}else{
		$list = get_notes_in_module("('$module_id')", $module_name);
	}

	
	
	$output_list = Array();
	$field_list = Array();
	foreach($list as $value)
	{
		
		//$loga->fatal("Adding another account to the list");
		$output_list[] = get_return_value($value, 'Notes');
		$_SESSION['viewable']['Notes'][$value->id] = $value->id;
	if(empty($field_list)){
			$field_list = get_field_list($value);	
		}
	}
	$output_list = filter_return_list($output_list, $select_fields, $module_name);
	$field_list = filter_field_list($field_list,$select_fields, $module_name);
	

	return array('result_count'=>sizeof($output_list), 'next_offset'=>0,'field_list'=>$field_list, 'entry_list'=>$output_list, 'error'=>$error->get_soap_array());
}

$server->register(
    'portal_get_module_fields',
    array('session'=>'xsd:string', 'module_name'=>'xsd:string'),
    array('return'=>'tns:module_fields'),
    $NAMESPACE);

function portal_get_module_fields($session, $module_name){
	global $log, $beanList, $beanFiles, $portal_modules, $valid_modules_for_contact;
	$error = new SoapError();
	$module_fields = array();
	if(! portal_validate_authenticated($session)){
		$error->set_error('invalid_session');	
		$error->description .=$session;
		return array('module_name'=>$module_name, 'module_fields'=>$module_fields, 'error'=>$error->get_soap_array());
	}
	if($_SESSION['type'] == 'lead'  && $module_name != 'Leads'){
		$error->set_error('no_access');	
		return array('module_name'=>$module_name, 'module_fields'=>$module_fields, 'error'=>$error->get_soap_array());
	}
	if(empty($beanList[$module_name])){
		$error->set_error('no_module');	
		return array('module_name'=>$module_name, 'module_fields'=>$module_fields, 'error'=>$error->get_soap_array());
	}
	if(($_SESSION['type'] == 'portal'||$_SESSION['type'] == 'contact') &&  !key_exists($module_name, $valid_modules_for_contact)){
		$error->set_error('no_module');	
		return array('module_name'=>$module_name, 'module_fields'=>$module_fields, 'error'=>$error->get_soap_array());
	}
	$class_name =& $beanList[$module_name];
	require_once($beanFiles[$class_name]);
	$seed =& new $class_name();
	return get_return_module_fields($seed, $module_name, $error->get_soap_array());
}





?>
