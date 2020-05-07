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
$global_registry_var_name = 'GLOBAL_REGISTRY';

//ignore notices
error_reporting(E_ALL ^ E_NOTICE );
$simple_log = true;
ob_start();

include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('modules/Users/User.php');
require_once('include/modules.php');
require_once('include/utils.php');

$log =& LoggerManager::getLogger('json');
$log->debug("JSON_SERVER:");


/* 
 * ADD NEW METHODS TO THIS ARRAY:
 * then create a function called "function json_$method($request_id,&$params)"
 * where $method is the method name
 */ 
$SUPPORTED_METHODS = array('retrieve','query','set_accept_status');

if (substr(phpversion(), 0, 1) == "5") {
$log->debug("JSON_SERVER: setting zend.ze1_compatibility_mode");
        ini_set("zend.ze1_compatibility_mode", "1");
}

// check for old config format.
if(empty($sugar_config) && isset($dbconfig['db_host_name']))
{
$log->debug("JSON_SERVER:make_sugar_config:");
   make_sugar_config($sugar_config);
}

function insert_charset_header()
{
  global $app_strings;
  global $sugar_config,$log;
  $charset = $sugar_config['default_charset'];

  if(isset($app_strings['LBL_CHARSET']))
  {
    $charset = $app_strings['LBL_CHARSET'];
		$log->debug("JSON_SERVER:insert_charset_header:".$charset);
  }

  header('Content-Type: text/html; charset='.$charset);
}

insert_charset_header();



if (!empty($sugar_config['session_dir'])) {
        session_save_path($sugar_config['session_dir']);
		$log->debug("JSON_SERVER:session_save_path:".$sugar_config['session_dir']);
}

session_start();
		$log->debug("JSON_SERVER:session started");


$current_language = 'en_us';

// create json parser
require_once("include/JSON.php");
$json = new JSON(JSON_LOOSE_TYPE);

  // if the language is not set yet, then set it to the default language.
  if(isset($_SESSION['authenticated_user_language']) && $_SESSION['authenticated_user_language'] != '')
  {
        $current_language = $_SESSION['authenticated_user_language'];
  }
  else
  {
        $current_language = $sugar_config['default_language'];
  }
		$log->debug("JSON_SERVER: current_language:".$current_language);

// if this is a get, than this is spitting out static javascript as if it was a file
if (strtolower($_SERVER['REQUEST_METHOD'])== 'get')
{
  $current_user = authenticate();
  if ( empty($current_user))
  {
		$log->debug("JSON_SERVER: current_user isn't set");
    print "";
    exit;
  }

  $str = '';
  $str .= getAppMetaJSON();
		$log->debug("JSON_SERVER:getAppMetaJSON");
  $str .= getFocusData();
		$log->debug("JSON_SERVER: getFocusData");
  $str .= getStringsJSON();
		$log->debug("JSON_SERVER:getStringsJSON");
  $str .= getUserConfigJSON();
		$log->debug("JSON_SERVER:getUserConfigJSON");
  print $str;
  exit;

} 
else
{
  // else act as a JSON-RPC server for SugarCRM
  // create result array
  $response = array();
  $response['result'] = null;
  $response['id'] = "-1";

  // authenticate user
  $current_user = authenticate();


  if ( empty($current_user))
  {
    $response['error'] = array("error_msg"=>"not logged in");
    print $json->encode($response);
    print "not logged in";
    exit;
  }


  // extract request
  $request = $json->decode($GLOBALS['HTTP_RAW_POST_DATA']);
//print $GLOBALS['HTTP_RAW_POST_DATA'];

  if (!is_array($request))
  {
    $response['error'] = array("error_msg"=>"malformed request");
    print $json->encode($response);
    exit;
  }

  // make sure required RPC fields are set
  if (empty($request['method']) || empty($request['id']))
  {
    $response['error'] = array("error_msg"=>"missing parameters");
    print $json->encode($response);
    exit;
  }

  $response['id'] = $request['id'];


  if ( in_array($request['method'],$SUPPORTED_METHODS))
  {

     call_user_func('json_'.$request['method'],$request['id'],$request['params']);
  }
  else {

    $response['error'] = array("error_msg"=>"method:".$request["method"]." not supported");
    print $json->encode($response);
    exit;
  }


}

                                                                                                            
ob_end_flush();


/// END OF SCRIPT.. the rest are the functions:
                                                                                                            


function authenticate()
{
 global $sugar_config,$log;
 $user_unique_key = (isset($_SESSION['unique_key'])) ? $_SESSION['unique_key'] : "";
 $server_unique_key = (isset($sugar_config['unique_key'])) ? $sugar_config['unique_key'] : "";
                                                                                                           
 if ($user_unique_key != $server_unique_key) {
		$log->debug("JSON_SERVER: user_unique_key:".$user_unique_key."!=".$server_unique_key);
        session_destroy();
        return null;
 }
                                                                                                           
 if(!isset($_SESSION['authenticated_user_id']))
 {
        // TODO change this to a translated string.
		$log->debug("JSON_SERVER: authenticated_user_id NOT SET. DESTROY");
        session_destroy();
        return null;
 }
                                                                                                           
 $current_user = new User();
                                                                                                           
 $result = $current_user->retrieve($_SESSION['authenticated_user_id']);
		$log->debug("JSON_SERVER: retrieved user from SESSION");

                                                                                                           
 if($result == null)
 {
		$log->debug("JSON_SERVER: could get a user from SESSION. DESTROY");
   session_destroy();
   return null;
 }

                                                                                                           
 return $result;
}



function json_retrieve(&$params)
{
  global $json,$response,$json_server;
	global $beanFiles,$beanList;
	//header('Content-type: text/xml');
	require_once($beanFiles[$beanList[$params['module']]]);
	$focus = new $beanList[$params['module']];
  if ( empty($params['module']) || empty($params['record']))
  {
    $response['error'] = array("error_msg"=>"method: retrieve: missing module or record as parameters");
    print $json->encode($response);
    exit;
  }

  $module_arr = meeting_retrieve($params['module'], $params['record']);

  $module_arr['request_id'] = $params['request_id'];
  $response['result'] = $module_arr;
  $json_response = $json->encode($response);
  print $json_response;
  exit;
}



// ONLY USED FOR MEETINGS
function meeting_retrieve($module,$record)
{
  global $json,$response,$log;
  global $beanFiles,$beanList;
  //header('Content-type: text/xml');
  require_once($beanFiles[$beanList[$module]]);
  $focus = new $beanList[$module];

  if ( empty($module) || empty($record))
  {
    $response['error'] = array("error_msg"=>"method: retrieve: missing module or record as parameters");
    print $json->encode($response);
    exit;
  }
                                                                                                           
  $focus->retrieve($record);
$log->debug("JSON_SERVER:retrieved meeting:");
  $module_arr = populateBean($focus);
                                                                                                           
  if ( $module == 'Meetings')
  {
    $users = $focus->get_meeting_users();
  } else if ( $module == 'Calls')
  {
    $users = $focus->get_call_users();
  }

  $module_arr['users_arr'] = array();

  foreach($users as $user)
  {
    array_push($module_arr['users_arr'],  populateBean($user));
  }
  $module_arr['orig_users_arr_hash'] = array();
  foreach($users as $user)
  {
   $module_arr['orig_users_arr_hash'][$user->id] = '1';
  }

  $module_arr['contacts_arr'] = array();

  $contacts = $focus->get_contacts();
  foreach($contacts as $contact)
  {
    array_push($module_arr['users_arr'], populateBean($contact));
  }

  return $module_arr;
}

// HAS MEETING SPECIFIC CODE:
function populateBean(&$focus)
{
  global $log;
  $all_fields = $focus->list_fields;
  // MEETING SPECIFIC
  $all_fields = array_merge($all_fields,array('required','accept_status'));
  //$all_fields = array_merge($focus->column_fields,$focus->additional_column_fields);
                                                                                                           
  $module_arr = array();

  $module_arr['module'] = $focus->object_name;

  $module_arr['fields'] = array();
                                                                                                           
  foreach($all_fields as $field)
  {
    if(isset($focus->$field))
    {
       $focus->$field =  from_html($focus->$field);
       $focus->$field =  preg_replace("/\r\n/","<BR>",$focus->$field);
       $focus->$field =  preg_replace("/\n/","<BR>",$focus->$field);
       $module_arr['fields'][$field] = $focus->$field;
    }
  }
$log->debug("JSON_SERVER:populate bean:");
  return $module_arr;
}

function construct_where(&$query_obj,$table='')
{
  if (! empty($table))
  {
    $table .= "."; 
  }
  $cond_arr = array();

	if (! is_array($query_obj['conditions']))
	{
		$query_obj['conditions'] = array();	
	}

  foreach($query_obj['conditions'] as $condition)
  {
     array_push($cond_arr,$table.$condition['name']." like '".$condition['value']."%'"); 
  }
	if ( $table == 'users.')
	{
    array_push($cond_arr,$table."status='Active'"); 
	}
  return implode(" {$query_obj['group']} ",$cond_arr);

}

function json_query($request_id,&$params)
{
  global $json,$response;
  $args = $params[0];

  global $beanFiles,$beanList;

 $list_return = array();

 if(! empty($args['module']))
 {
   $args['modules'] = array($args['module']);
   
 }
 foreach($args['modules'] as $module)
 {
  require_once($beanFiles[$beanList[$module]]);
  $focus = new $beanList[$module];

  $query_orderby = ''; 
  $query_where = construct_where($args,$focus->table_name); 
  $list_arr = array();

  $curlist = $focus->get_list($query_orderby, $query_where, 0, 0);
//print "\n$module>>".count($curlist['list']).'\n';
  $list_return = array_merge($list_return,$curlist['list']);
 }

//print count($list_return);
 for($i = 0;$i < count($list_return);$i++)
 {
   $list_arr[$i]= array();
   $list_arr[$i]['fields']= array();
   $list_arr[$i]['module']= $list_return[$i]->object_name;

   foreach($args['field_list'] as $field)
   {
      $list_arr[$i]['fields'][$field] = $list_return[$i]->$field;
//     print($response[$i]);
   }
 } 
  
  $response['id'] = $request_id;
  //$response['result'] = array( "request_id"=>$params['request_id'],"list"=>$list_arr);
  $response['result'] = array( "list"=>$list_arr);
  $json_response = $json->encode($response);
  print $json_response;
  exit;
}

function json_set_accept_status($request_id,&$params)
{
 global $json,$current_user;
 global $beanFiles,$beanList;

 require_once($beanFiles[$beanList[$params[0]['module']]]);
 $focus = new $beanList[$params[0]['module']];

 $focus->id = $params[0]['record'];
 $test = $focus->set_accept_status($current_user,$params[0]['accept_status']);
 $response = array();
 $response['id'] = $request_id;

 $response['result'] = array( "status"=>"success","record"=>$params[0]['record'],'accept_status'=>$params[0]['accept_status']);

  $json_response = $json->encode($response);

  print $json_response;
  exit;

}

function getUserJSON()
{



}
function getUserConfigJSON()
{
 global $current_user,$global_registry_var_name,$json,$_SESSION,$sugar_config;

 if(isset($_SESSION['authenticated_user_theme']) && $_SESSION['authenticated_user_theme'] != '')
 {
  $theme = $_SESSION['authenticated_user_theme'];
 }
 else
 {
   $theme = $sugar_config['default_theme'];
 }
 $user_arr = array();
 $user_arr['theme'] = $theme;
 $user_arr['fields'] = array();
 $user_arr['module'] = 'User';
 $user_arr['fields']['id'] = $current_user->id;
 $user_arr['fields']['user_name'] = $current_user->user_name;
 $user_arr['fields']['first_name'] = $current_user->first_name;
 $user_arr['fields']['last_name'] = $current_user->last_name;
 $user_arr['fields']['email'] = $current_user->email1;
 $str = "\n".$global_registry_var_name.".current_user = ".$json->encode($user_arr).";\n";
return $str;

}
function getAppMetaJSON()
{
 global $json,$global_registry_var_name,$sugar_config,$log;

  $str = "\nvar ".$global_registry_var_name." = new Object();\n";
  
  $sugar_config['site_url'] = preg_replace('/^http(s)?\:\/\/[^\/]+/',"http$1://".$_SERVER['HTTP_HOST'],$sugar_config['site_url']);

  if ( ! empty($_SERVER['SERVER_PORT']) &&  $_SERVER['SERVER_PORT'] == '443')
	{
    $sugar_config['site_url'] = preg_replace('/^http\:/','https:',$sugar_config['site_url']);
	}
  $str .= "\n".$global_registry_var_name.".config = {\"site_url\":\"".  $sugar_config['site_url']."\"};\n";

  $str .= $global_registry_var_name.".meta = new Object();\n";
  $str .= $global_registry_var_name.".meta.modules = new Object();\n";
  $modules_arr = array('Meetings','Calls');
  $meta_modules = array();

  global $beanFiles,$beanList;
  //header('Content-type: text/xml');
  foreach($modules_arr as $module)
  {
    require_once($beanFiles[$beanList[$module]]);
    $focus = new $beanList[$module];
    $meta_modules[$module] = array();
    $meta_modules[$module]['field_defs'] = $focus->field_defs;
  }

  $str .= $global_registry_var_name.".meta.modules = ". $json->encode($meta_modules)."\n";;
  return $str;

}
function getFocusData()
{
 global $json,$global_registry_var_name;

 if ( empty($_REQUEST['module']) )  
 {
   return '';
 }
 else if ( empty($_REQUEST['record'] ) )
 {
  // return ''; 
   return "\n".$global_registry_var_name.'["focus"] = {"module":"'.$_REQUEST['module'].'",users_arr:[],fields:{"id":"-1"}}'."\n";
 }

 $module_arr = meeting_retrieve($_REQUEST['module'], $_REQUEST['record']);
 return "\n".$global_registry_var_name."['focus'] = ". $json->encode($module_arr).";\n";;
}

function getStringsJSON()
{
                                                                                                  
  //set module and application string arrays based upon selected language
 // $app_strings = return_application_language($current_language);
  global $current_language;
  $currentModule = 'Calendar';
  $mod_list_strings = return_mod_list_strings_language($current_language,$currentModule);

 global $json,$global_registry_var_name;
   $str = "\n".$global_registry_var_name."['calendar_strings'] =  {\"dom_cal_month_long\":". $json->encode($mod_list_strings['dom_cal_month_long']).",\"dom_cal_weekdays_long\":". $json->encode($mod_list_strings['dom_cal_weekdays_long'])."}\n";
  if ( empty($_REQUEST['module']))
  {
   $_REQUEST['module'] = 'Home';
  }
  $currentModule = $_REQUEST['module'];
  $mod_strings = return_module_language($current_language,$currentModule);
   return  $str . "\n".$global_registry_var_name."['meeting_strings'] =  ". $json->encode($mod_strings)."\n";

}

exit();

?>
