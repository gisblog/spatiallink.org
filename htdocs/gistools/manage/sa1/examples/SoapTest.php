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
$user_name ='';
$user_password = '';
foreach($_POST as $name=>$value){
		$$name = $value;
}
echo <<<EOQ
<form name='test' method='POST'>
<table width ='800'><tr>
<tr><th colspan='6'>Enter  SugarCRM  User Information - this is the same info entered when logging into sugarcrm</th></tr>
<td >USER NAME:</td><td><input type='text' name='user_name' value='$user_name'></td><td>USER PASSWORD:</td><td><input type='password' name='user_password' value='$user_password'></td>
</tr>

<tr><td><input type='submit' value='Submit'></td></tr>
</table>
</form>


EOQ;
if(!empty($user_name)){
$offset = 0;
if(isset($_REQUEST['offset'])){
	$offset = $_REQUEST['offset'] + 20;
	echo $offset;
}
require_once('../include/nusoap/nusoap.php');  //must also have the nusoap code on the ClientSide.
$soapclient = new soapclient('http://localhost/sugarcrm/soap.php');  //define the SOAP Client an

echo '<b>LOGIN:</b><BR>';
$result = $soapclient->call('login',array('user_auth'=>array('user_name'=>$user_name,'password'=>md5($user_password), 'version'=>'.01'), 'application_name'=>'SoapTest'));
echo '<b>HERE IS ERRORS:</b><BR>';
echo $soapclient->error_str;;

echo '<BR><BR><b>HERE IS RESPONSE:</b><BR>';
echo $soapclient->response;

echo '<BR><BR><b>HERE IS RESULT:</b><BR>';
echo print_r($result);
$session = $result['id'];

echo '<br><br><b>GET Case fields:</b><BR>';
$result = $soapclient->call('get_module_fields',array('session'=>$session , 'module_name'=>'Cases'));
echo '<b>HERE IS ERRORS:</b><BR>';
echo $soapclient->error_str;;

echo '<BR><BR><b>HERE IS RESPONSE:</b><BR>';
echo $soapclient->response;

echo '<BR><BR><b>HERE IS RESULT:</b><BR>';
echo print_r($result);

echo '<br><br><b>Update a portal user fields:</b><BR>';
$result = $soapclient->call('update_portal_user',array('session'=>$session,'portal_name'=>'dan','name_value_list'=>array(array('name'=>'email1', 'value'=>'Dan_Aarons@company.com'))));
echo '<b>HERE IS ERRORS:</b><BR>';
echo $soapclient->error_str;;

echo '<BR><BR><b>HERE IS RESPONSE:</b><BR>';
echo $soapclient->response;

echo '<BR><BR><b>HERE IS RESULT:</b><BR>';
echo print_r($result);

echo '<br><br><b>Get list of contacts:</b><BR>';
$result = $soapclient->call('get_entry_list',array('session'=>$session,'module_name'=>'Contacts','query'=>'','order_by'=>'contacts.last_name asc','offset'=>$offset, 'select_fields'=>array(), 'max_results'=>'5'));
echo '<b>HERE IS ERRORS:</b><BR>';
echo $soapclient->error_str;;

echo '<BR><BR><b>HERE IS RESPONSE:</b><BR>';
echo $soapclient->response;

echo '<BR><BR><b>HERE IS RESULT:</b><BR>';
echo print_r($result);

echo '<br><br><b>LOGOUT:</b><BR>';
$result = $soapclient->call('logout',array('session'=>$session));
echo '<b>HERE IS ERRORS:</b><BR>';
echo $soapclient->error_str;;

echo '<BR><BR><b>HERE IS RESPONSE:</b><BR>';
echo $soapclient->response;

echo '<BR><BR><b>HERE IS RESULT:</b><BR>';
echo print_r($result);

}
?>   
