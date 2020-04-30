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
require_once('modules/EmailMan/EmailMan.php');
require_once('modules/Users/User.php');
require_once('include/phpmailer/class.phpmailer.php');
require_once("modules/Administration/Administration.php");
$mail =& new PHPMailer();
$admin =& new Administration();
$admin->retrieveSettings();
$emailsPerSecond = 10;
if ($admin->settings['mail_sendtype'] == "SMTP") {
	$mail->Host = $admin->settings['mail_smtpserver'];
	$mail->Port = $admin->settings['mail_smtpport'];
	

	if ($admin->settings['mail_smtpauth_req']) {
		$mail->SMTPAuth = TRUE;
		$mail->Username = $admin->settings['mail_smtpuser'];
		$mail->Password = $admin->settings['mail_smtppass'];
	}
	$mail->Mailer   = "smtp";
	$mail->SMTPKeepAlive = true;
}

$mail->From     = "no-reply@example.com";
$mail->FromName = "no-reply";

$db =& new PearDatabase();
$emailman =& new EmailMan();
$emailCount = 0;
$result = $db->query("SELECT * FROM $emailman->table_name WHERE send_date_time <= '". date('Y-m-d H:i:s') . "' AND (in_queue ='0' OR ( in_queue ='1' AND in_queue_date <= '" .date('Y-m-d H:i:s', strtotime("-1 day"))."')) ORDER BY user_id, list_id");
$db->query("UPDATE $emailman->table_name SET in_queue='1' , in_queue_date='". date('Y-m-d H:i:s') ."' WHERE send_date_time <= '". date('Y-m-d H:i:s') . "' AND (in_queue ='0' OR (in_queue ='1' AND in_queue_date <= '" .date('Y-m-d H:i:s', strtotime("-1 day"))."'))");
$simplelog = fopen('emailman.log', 'a');
if(isset($current_user)){
	$temp_user = $current_user;
}	
$current_user =& new User();
$startTime = microtime();
	while($row = $db->fetchByAssoc($result)){
		if($row['user_id'] != $current_user->id){
			$current_user->retrieve($row['user_id']);
		}
		 foreach($row as $name=>$value){
		 	$emailman->$name = $value;
		 }
		 if(!$emailman->sendEmail()){
		 	emaillog("FAILURE:");		
		 }else{
		 	emaillog("SUCCESS:");	
		 }

		emaillog($emailman->toString());
		if($mail->isError()){
			emaillog($mail->ErrorInfo);
		 }
		$emailCount++;
		$curTime = microtime();
		$delta = microtime_diff($startTime,$curTime );
		if($emailCount >= $emailsPerSecond && $delta < 1){
			usleep((1 - $delta) * 1000000);
			emaillog("exceded max emails per second ($emailsPerSecond) - sleeping\n");
			$delta = 2;
			
		}
		if($delta > 1){
			$startTime = microtime();
			$emailCount = 0;
		}
			
	}
if ($admin->settings['mail_sendtype'] == "SMTP") {
	$mail->SMTPClose();
}
if(isset($temp_user)){
	$current_user = $temp_user;	
}
fclose($simplelog);	
function emaillog($text){
	global $simplelog;
	if(!empty($_REQUEST['verbose'])){
		echo $text . '<br>';
	}
	if($simplelog)
		fwrite($simplelog, $text);;
	
}


?>
