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
require_once('modules/Users/User.php');
require_once('modules/Leads/LeadFormBase.php');
require_once('include/logging.php');

$app_list_strings['record_type_module'] = array('Contact'=>'Contacts', 'Account'=>'Accounts', 'Opportunity'=>'Opportunities', 'Case'=>'Cases', 'Note'=>'Notes', 'Call'=>'Calls', 'Email'=>'Emails', 'Meeting'=>'Meetings', 'Task'=>'Tasks', 'Lead'=>'Leads','Bug'=>'Bugs',



);

$log =& LoggerManager::getLogger('index');

$users = array(
	'cheeto' => array('name'=>'admin', 'pass'=>'6958b4fa4bfc56342634184c21085543'),
	'test' => array('name'=>'nate', 'pass'=>'d61aac88ad7b9c8981028e20308d7ba2'),
	'orbit' => array('name'=>'amielead', 'pass'=>'ad2dd4a1c1ea261364dfb6e44bf72646')
);

$current_user = new User();
$current_user->user_name = $users[$_POST['user']]['name'];
if($current_user->authenticate_user($users[$_POST['user']]['pass'])){
	$userid = $current_user->retrieve_user_id($users[$_REQUEST['user']]['name']);
	$current_user->retrieve($userid);
	$leadForm = new LeadFormBase();
	$prefix = '';
	if(isset($_POST['prefix']) && !empty($_POST['prefix'])){
		$prefix = 	$_POST['prefix'];
	}
	
	if( !isset($_POST['assigned_user_id']) || !empty($_POST['assigned_user_id']) ){
		$_POST['prefix'] = $userid;
	}

	$_POST['record'] ='';

	if( isset($_POST['_splitName']) ) {
		$name = explode(' ',$_POST['name']);
		if(sizeof($name) == 1) { $_POST['first_name'] = '';  $_POST['last_name'] = $name[0]; }
		else { $_POST['first_name'] = $name[0];  $_POST['last_name'] = $name[1]; }

//		die('first name is: '.$_POST['first_name'].'<br/>Last name is:'.$_POST['last_name'].'<br/>');
	}

	$return_val = $leadForm->handleSave($prefix, false, true);

	if(isset($_POST['redirect']) && !empty($_POST['redirect'])){

		//header("Location: ".$_POST['redirect']);	
		echo '<html><head><title>SugarCRM</title></head><body>';
		echo '<form name="redirect" action="' .$_POST['redirect']. '" method="POST">';

	 	foreach($_POST as $param => $value) {
	 		
			if($param != 'redirect') {
	 			echo '<input type="hidden" name="'.$param.'" value="'.$value.'">';
			}

	 	}
		
	 	if( ($return_val == '') || ($return_val  == 0) || ($return_val < 0) ) {
	 		echo '<input type="hidden" name="error" value="1">';
	 	}
	 	echo '</form><script language="javascript" type="text/javascript">document.redirect.submit();</script>';
	 	echo '</body></html>';
	 	die();

	}else{
		echo "Thank You For Your Submission.";	
	}	
}
?>
