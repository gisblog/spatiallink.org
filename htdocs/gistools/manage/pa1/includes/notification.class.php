<?php 
#Application name: PhpCollab
#Status page: 2
#Path by root: ../includes/notification.class.php

class notification {
function notification() {
}

function discussionsNotification($name,$email,$organization,$item,$case) {
global $projectDetail,$detailTopic,$strings,$root,$nameSession,$loginSession,$version,$setCharset;

switch ($case) {
case "newpost":
$partSubject = $strings["noti_newpost1"];
$partMessage = $strings["noti_newpost2"];
break;
case "newtopic":
$partSubject = $strings["noti_newtopic1"];
$partMessage = $strings["noti_newtopic2"];
break;
}

$footer = "---\n".$strings["noti_foot1"]."\n\n".$strings["noti_foot2"]."\n$root/";

if ($email != "") {
	if ($projectDetail->pro_org_id[0] == "1") {
		$projectDetail->pro_org_name[0] = $strings["none"];
	}
	$message = $partMessage."\n\n".$strings["discussion"]." : ".$detailTopic->top_subject[0]."\n".$strings["posted_by"]." : ".$nameSession." (".$loginSession.")\n\n".$strings["project"]." : ".$projectDetail->pro_name[0]." (".$projectDetail->pro_id[0].")\n".$strings["organization"]." : ".$projectDetail->pro_org_name[0]."\n\n".$strings["noti_moreinfo"]."\n"; 
	if ($organization == "1") { 
		$message .= "$root/general/login.php?url=topics/viewtopic.php%3Fid=$item";
		$send = "true";
	} else if ($organization != "1" && $projectDetail->pro_published[0] == "0") { 
		$message .= "$root/general/login.php?url=general/home.php%3Fproject=".$projectDetail->pro_id[0]; 
		$send = "true";
	} 
	$priorityMail = "3"; 
	$message .= "\n\n".$footer;
	$headers = "Content-type:text/plain;charset=\"$setCharset\"\nFrom: \"".$projectDetail->pro_mem_name[0]."\" <".$projectDetail->pro_mem_email_work[0].">\nX-Priority: $priorityMail\nX-Mailer: PhpCollab $version"; 
	$subject = $partSubject." ".$detailTopic->top_subject[0];
	if ($send == "true") {
	@mail($email, $subject, $message, $headers); 
	//echo $email."<br>$subject<br>$message<br>$headers<br>";
	}
}
}

function projectteamNotification($name,$email,$organization,$item,$case) {
global $projectDetail,$strings,$root,$version,$setCharset;



switch ($case) {
case "addprojectteam":
$partSubject = $strings["noti_addprojectteam1"];
$partMessage = $strings["noti_addprojectteam2"];
break;

case "removeprojectteam":
$partSubject = $strings["noti_removeprojectteam1"];
$partMessage = $strings["noti_removeprojectteam2"];
break;
}

$footer = "---\n".$strings["noti_foot1"]."\n\n".$strings["noti_foot2"]."\n$root/";

if ($email != "") {
	if ($projectDetail->pro_org_id[0] == "1") {
		$projectDetail->pro_org_name[0] = $strings["none"];
	}
	$message = $partMessage."\n\n".$strings["project"]." : ".$projectDetail->pro_name[0]." (".$projectDetail->pro_id[0].")\n".$strings["organization"]." : ".$projectDetail->pro_org_name[0]."\n\n".$strings["noti_moreinfo"]."\n"; 
	if ($organization == "1") { 
		$message .= "$root/general/login.php?url=projects/viewproject.php%3Fid=$item";
		$send = "true";
	} else if ($organization != "1" && $projectDetail->pro_published[0] == "0") { 
		if ($case == "addprojectteam") {
		$message .= "$root/general/login.php?url=general/home.php%3Fproject=".$projectDetail->pro_id[0]; 
		} else if ($case == "removeprojectteam") {
		$message .= "$root"; 
		}
		$send = "true";
	} 
	$priorityMail = "3"; 
	$message .= "\n\n".$footer;
	$headers = "Content-type:text/plain;charset=\"$setCharset\"\nFrom: \"".$projectDetail->pro_mem_name[0]."\" <".$projectDetail->pro_mem_email_work[0].">\nX-Priority: $priorityMail\nX-Mailer: PhpCollab $version"; 
	$subject = $partSubject." ".$projectDetail->pro_name[0];
	if ($send == "true") {
	@mail($email, $subject, $message, $headers); 
	//echo $email."<br>$subject<br>$message<br>$headers<br>";
	}
}
}

function taskNotification($member,$item,$case) {
global $strings,$root,$priority,$status,$version,$setCharset;

$tmpquery = "WHERE tas.id = '$item'";
$taskNoti = new request();
$taskNoti->openTasks($tmpquery);

$tmpquery = "WHERE pro.id = '".$taskNoti->tas_project[0]."'";
$projectNoti = new request();
$projectNoti->openProjects($tmpquery);

$tmpquery = "WHERE noti.member = '$member'";
$userNotifications = new request();
$userNotifications->openNotifications($tmpquery); 

$send = "false";
$validNotification = "false";
switch ($case) {
case "taskassignment":
$partSubject = $strings["noti_taskassignment1"];
$partMessage = $strings["noti_taskassignment2"];
if ($userNotifications->not_taskassignment[0] == "0") {
	$validNotification = "true";
	$emailTo = $taskNoti->tas_mem_email_work[0];
}
break;

case "statustaskchange":
$partSubject = $strings["noti_statustaskchange1"];
$partMessage = $strings["noti_statustaskchange2"];
if ($userNotifications->not_statustaskchange[0] == "0") {
	$validNotification = "true";
	$emailTo = $taskNoti->tas_mem_email_work[0];
}
break;

case "prioritytaskchange":
$partSubject = $strings["noti_prioritytaskchange1"];
$partMessage = $strings["noti_prioritytaskchange2"];
if ($userNotifications->not_prioritytaskchange[0] == "0") {
	$validNotification = "true";
	$emailTo = $taskNoti->tas_mem_email_work[0];
}
break;

case "duedatetaskchange":
$partSubject = $strings["noti_duedatetaskchange1"];
$partMessage = $strings["noti_duedatetaskchange2"];
if ($userNotifications->not_duedatetaskchange[0] == "0") {
	$validNotification = "true";
	$emailTo = $taskNoti->tas_mem_email_work[0];
}
break;

case "clientaddtask":
$partSubject = $strings["noti_clientaddtask1"];
$partMessage = $strings["noti_clientaddtask2"];
if ($userNotifications->not_clientaddtask[0] == "0") {
	$validNotification = "true";
	$emailTo = $projectNoti->pro_mem_email_work[0];
}
break;
}

$footer = "---\n".$strings["noti_foot1"]."\n\n".$strings["noti_foot2"]."\n$root/";

if ($validNotification == "true" && $emailTo != "") {
	if ($projectNoti->pro_org_id[0] == "1") {
		$projectNoti->pro_org_name[0] = $strings["none"];
	}
	$complValue = ($taskNoti->tas_completion[0]>0) ? $taskNoti->tas_completion[0]."0 %": $taskNoti->tas_completion[0]." %"; 
	$idStatus = $taskNoti->tas_status[0];
	$idPriority = $taskNoti->tas_priority[0];
	$message = $partMessage."\n\n".$strings["task"]." : ".$taskNoti->tas_name[0]."\n".$strings["start_date"]." : ".$taskNoti->tas_start_date[0]."\n".$strings["due_date"]." : ".$taskNoti->tas_due_date[0]."\n".$strings["completion"]." : ".$complValue."\n".$strings["priority"]." : $priority[$idPriority]\n".$strings["status"]." : $status[$idStatus]\n".$strings["description"]." : ".$taskNoti->tas_description[0]."\n\n".$strings["project"]." : ".$projectNoti->pro_name[0]." (".$projectNoti->pro_id[0].")\n".$strings["organization"]." : ".$projectNoti->pro_org_name[0]."\n\n".$strings["noti_moreinfo"]."\n"; 
	if ($taskNoti->tas_mem_organization[0] == "1" || $case == "clientaddtask") { 
		$message .= "$root/general/login.php?url=tasks/viewtask.php%3Fid=$item";
		$send = "true";
	} else if ($taskNoti->tas_mem_organization[0] != "1" && $projectNoti->pro_published[0] == "0" && $taskNoti->tas_published[0] == "0") { 
		$message .= "$root/general/login.php?url=general/home.php%3Fproject=".$projectNoti->pro_id[0];
		$send = "true";
	} 
	if ($taskNoti->tas_priority[0] == "4" || $taskNoti->tas_priority[0] == "5") { 
		$priorityMail = "1"; 
	} else { 
		$priorityMail = "3"; 
	} 
	$message .= "\n\n".$footer;
	$headers = "Content-type:text/plain;charset=\"$setCharset\"\nFrom: \"".$projectNoti->pro_mem_name[0]."\" <".$projectNoti->pro_mem_email_work[0].">\nX-Priority: $priorityMail\nX-Mailer: PhpCollab $version"; 
	$subject = $partSubject." ".$taskNoti->tas_name[0];
	if ($send == "true") {
	@mail($emailTo, $subject, $message, $headers); 
	//echo $emailTo."<br>$subject<br>$message<br>$headers<br>";
	}
} 
}

function subtaskNotification($member,$item,$case) {
global $strings,$root,$priority,$status,$version,$setCharset;

$tmpquery = "WHERE subtas.id = '$item'";
$subtaskNoti = new request();
$subtaskNoti->openSubtasks($tmpquery);

$tmpquery = "WHERE tas.id = '".$subtaskNoti->subtas_task[0]."'";
$taskNoti = new request();
$taskNoti->openTasks($tmpquery);

$tmpquery = "WHERE pro.id = '".$taskNoti->tas_project[0]."'";
$projectNoti = new request();
$projectNoti->openProjects($tmpquery);






$tmpquery = "WHERE noti.member = '$member'";
$userNotifications = new request();
$userNotifications->openNotifications($tmpquery); 

$send = "false";
$validNotification = "false";
switch ($case) {
case "taskassignment":
$partSubject = $strings["noti_taskassignment1"];
$partMessage = $strings["noti_taskassignment2"];
if ($userNotifications->not_taskassignment[0] == "0") {
	$validNotification = "true";
	$emailTo = $subtaskNoti->subtas_mem_email_work[0];
}
break;

case "statustaskchange":
$partSubject = $strings["noti_statustaskchange1"];
$partMessage = $strings["noti_statustaskchange2"];
if ($userNotifications->not_statustaskchange[0] == "0") {
	$validNotification = "true";
	$emailTo = $subtaskNoti->subtas_mem_email_work[0];
}
break;

case "prioritytaskchange":
$partSubject = $strings["noti_prioritytaskchange1"];
$partMessage = $strings["noti_prioritytaskchange2"];
if ($userNotifications->not_prioritytaskchange[0] == "0") {
	$validNotification = "true";
	$emailTo = $subtaskNoti->subtas_mem_email_work[0];
}
break;

case "duedatetaskchange":
$partSubject = $strings["noti_duedatetaskchange1"];
$partMessage = $strings["noti_duedatetaskchange2"];
if ($userNotifications->not_duedatetaskchange[0] == "0") {
	$validNotification = "true";
	$emailTo = $subtaskNoti->subtas_mem_email_work[0];
}
break;

}

$footer = "---\n".$strings["noti_foot1"]."\n\n".$strings["noti_foot2"]."\n$root/";

if ($validNotification == "true" && $emailTo != "") {
	if ($projectNoti->pro_org_id[0] == "1") {
		$projectNoti->pro_org_name[0] = $strings["none"];
	}
	$complValue = ($subtaskNoti->subtas_completion[0]>0) ? $subtaskNoti->subtas_completion[0]."0 %": $subtaskNoti->subtas_completion[0]." %"; 
	$idStatus = $subtaskNoti->subtas_status[0];
	$idPriority = $subtaskNoti->subtas_priority[0];
	$message = $partMessage."\n\n".$strings["subtask"]." : ".$subtaskNoti->subtas_name[0]."\n".$strings["start_date"]." : ".$subtaskNoti->subtas_start_date[0]."\n".$strings["due_date"]." : ".$subtaskNoti->subtas_due_date[0]."\n".$strings["completion"]." : ".$complValue."\n".$strings["priority"]." : $priority[$idPriority]\n".$strings["status"]." : $status[$idStatus]\n".$strings["description"]." : ".$subtaskNoti->subtas_description[0]."\n\n".$strings["project"]." : ".$projectNoti->pro_name[0]." (".$projectNoti->pro_id[0].")\n".$strings["task"]." : ".$taskNoti->tas_name[0]." (".$taskNoti->tas_id[0].")\n".$strings["organization"]." : ".$projectNoti->pro_org_name[0]."\n\n".$strings["noti_moreinfo"]."\n"; 
	if ($subtaskNoti->subtas_mem_organization[0] == "1" || $case == "clientaddtask") { 
		$message .= "$root/general/login.php?url=subtasks/viewsubtask.php%3Fid=$item%26task=".$taskNoti->tas_id[0];
		$send = "true";
	} else if ($subtaskNoti->subtas_mem_organization[0] != "1" && $projectNoti->pro_published[0] == "0" && $subtaskNoti->subtas_published[0] == "0") { 
		$message .= "$root/general/login.php?url=general/home.php%3Fproject=".$projectNoti->pro_id[0];
		$send = "true";
	} 
	if ($subtaskNoti->subtas_priority[0] == "4" || $subtaskNoti->subtas_priority[0] == "5") { 
		$priorityMail = "1"; 
	} else { 
		$priorityMail = "3"; 
	} 
	$message .= "\n\n".$footer;
	$headers = "Content-type:text/plain;charset=\"$setCharset\"\nFrom: \"".$projectNoti->pro_mem_name[0]."\" <".$projectNoti->pro_mem_email_work[0].">\nX-Priority: $priorityMail\nX-Mailer: PhpCollab $version"; 
	$subject = $partSubject." ".$subtaskNoti->subtas_name[0];
	if ($send == "true") {
	@mail($emailTo, $subject, $message, $headers); 
	//echo $emailTo."<br>$subject<br>$message<br>$headers<br>";
	}
} 
}

function supportNotification($member,$item,$email,$status,$num,$case) {
global $strings,$root,$nameSession,$loginSession,$requestStatus,$supportEmail,$setCharset;

$tmpquery = "WHERE sr.id = '$item'";
$requestDetail = new request();
$requestDetail->openSupportRequests($tmpquery);

$tmpquery = "WHERE mem.id = '$member'";
$userDetail = new request();
$userDetail->openMembers($tmpquery);

$comptSupStatus = count($requestStatus);
for ($i=0;$i<$comptSupStatus;$i++) {
	if ($status == $i) {
		$requestStatus = $requestStatus[$i];
	}
}

switch ($case) {
case "newrequest":
$partSubject = $strings["support"]." ".$strings["support_id"];
$partMessage = $strings["noti_support_request_new2"];
break;
}

switch ($case){
case "newrequestTeam":
$partSubject = $strings["support"]." ".$strings["support_id"];
$partMessage = $strings["noti_support_team_new2"];
break;
}

switch ($case) {
case "response":
$partSubject = $strings["support"]." ".$strings["support_id"];
$partMessage = $strings["noti_support_post2"];

$tmpquery = "WHERE sp.id = '$num'";
$postDetail = new request();
$postDetail->openSupportPosts($tmpquery);
break;
}

switch ($case) {
case "statuschange":
$partSubject = $strings["support"]." ".$strings["support_id"];
$partMessage = $strings["noti_support_status2"];
break;
}

$footer = "---\n".$strings["noti_foot1"]."\n\n".$strings["noti_foot2"]."\n$root/";

if ($email != "") {

	$message = $partMessage."";
	
	if ($case == "newrequest"){
		$message .= "".$requestDetail->sr_subject[0]."";
	}
	
	if($case == "newrequestTeam"){
		$message .= "".$requestDetail->sr_pro_name[0]."";
	}
	
	$message .= "\n\n".$strings["id"]." : ".$requestDetail->sr_id[0]."\n".$strings["subject"]." : ".$requestDetail->sr_subject[0]."\n".$strings["status"]." : $requestStatus\n".$strings["details"]." : "; 
	
	if ($userDetail->mem_profil[0] == 3){
		$message .= "$root/general/login.php?url=general/home.php%3Fproject=".$requestDetail->sr_project[0]."\n\n";
	} else {
		$message .= "$root/general/login.php?url=support/viewrequest.php%3Fid=$item \n\n";
	}
	if ($case == "response"){
		$message .= $strings["message"]." : ".$postDetail->sp_message[0]."";
	}
	$send = "true";
	$priorityMail = "3";
	$message .= "\n\n".$footer;
	$headers = "Content-type:text/plain;charset=\"$setCharset\"\nFrom: \"".$strings["support"]."\" <".$supportEmail.">\nX-Priority: $priorityMail\nX-Mailer: PhpCollab $version";
	$subject = $partSubject.": ".$requestDetail->sr_id[0];
	if ($send == "true") {
		@mail($email, $subject, $message, $headers);
		//echo $email."<br>$subject<br>$message<br>$headers<br>";
	}
}
}

}
?>