<?php
#Application name: PhpCollab
#Status page: 0
#Path by root: ../topics/addtopic.php

$checkSession = "true";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	$postnukeIntegration = "true";
	include("modules/PhpCollab/includes/library.php");
} else {
	$postnukeIntegration = "false";
	include("../includes/library.php");
}

if ($notifications == "true") {
if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/includes/notification.class.php");
} else {
	include("../includes/notification.class.php");
}
}

$tmpquery = "WHERE pro.id = '$project'";
$projectDetail = new request();
$projectDetail->openProjects($tmpquery);

if ($projectDetail->pro_org_id[0] == "1") {
	$projectDetail->pro_org_name[0] = $strings["none"];
}

if ($action == "add") {
if ($pub == "") {
	$pub = "1";
}
	$ttt = convertData($ttt);
	$tpm = convertData($tpm);
	$tmpquery1 = "INSERT INTO ".$tableCollab["topics"]."(project,owner,subject,status,last_post,posts,published) VALUES('$project','$idSession','$ttt','1','$dateheure','1','$pub')";
	connectSql("$tmpquery1");
	$tmpquery = $tableCollab["topics"];
	last_id($tmpquery);
	$num = $lastId[0];
	unset($lastId);
	autoLinks($tpm);
	$tmpquery2 = "INSERT INTO ".$tableCollab["posts"]."(topic,member,created,message) VALUES('$num','$idSession','$dateheure','$newText')";
	connectSql("$tmpquery2");

if ($notifications == "true") {
$tmpquery = "WHERE topic.id = '$num'";
$detailTopic = new request();
$detailTopic->openTopics($tmpquery);

$tmpquery = "WHERE tea.project = '$project' AND tea.member != '$idSession' ORDER BY mem.id";
$listTeam = new request();
$listTeam->openTeams($tmpquery);
$comptListTeam = count($listTeam->tea_id);

for ($i=0;$i<$comptListTeam;$i++) {
	$posters .= $listTeam->tea_member[$i].",";
}
if (substr($posters, -1) == ",") { $posters = substr($posters, 0, -1); }
//echo $posters;

if ($posters != "") {
$tmpquery = "WHERE noti.member IN($posters)";
$listNotifications = new request();
$listNotifications->openNotifications($tmpquery);
$comptListNotifications = count($listNotifications->not_id);

$newtopic = new notification();

for ($i=0;$i<$comptListNotifications;$i++) {
	if ($listNotifications->not_newtopic[$i] == "0" && $listNotifications->not_mem_email_work[$i] != "" && $listNotifications->not_mem_id[$i] != $idSession && $listNotifications->not_mem_profil[$i] != "3") {
		$newtopic->discussionsNotification($listNotifications->not_mem_name[$i],$listNotifications->not_mem_email_work[$i],$listNotifications->not_mem_organization[$i],$num,"newtopic");
	}
	if ($listNotifications->not_newtopic[$i] == "0" && $listNotifications->not_mem_email_work[$i] != "" && $listNotifications->not_mem_id[$i] != $idSession && $listNotifications->not_mem_profil[$i] == "3" && $detailTopic->top_published[0] == "0") {
		$newtopic->discussionsNotification($listNotifications->not_mem_name[$i],$listNotifications->not_mem_email_work[$i],$listNotifications->not_mem_organization[$i],$num,"newtopic");
	}
}
}
}
	headerFunction("../topics/viewtopic.php?project=$project&id=$num&msg=add&".session_name()."=".session_id());
}

$bodyCommand = "onLoad=\"document.ctTForm.ttt.focus();\"";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/header.php");
} else {
	include("../themes/".THEME."/header.php");
}

$blockPage = new block();
$blockPage->openBreadcrumbs();
$blockPage->itemBreadcrumbs($blockPage->buildLink("../projects/listprojects.php?",$strings["projects"],in));
$blockPage->itemBreadcrumbs($blockPage->buildLink("../projects/viewproject.php?id=".$projectDetail->pro_id[0],$projectDetail->pro_name[0],in));
$blockPage->itemBreadcrumbs($blockPage->buildLink("../topics/listtopics.php?project=".$projectDetail->pro_id[0],$strings["discussions"],in));
$blockPage->itemBreadcrumbs($strings["add_discussion"]);
$blockPage->closeBreadcrumbs();

if ($msg != "") {
if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/includes/messages.php");
} else {
	include("../includes/messages.php");
}
	$blockPage->messagebox($msgLabel);
}

$block1 = new block();

$block1->form = "ctT";
$block1->openForm("../topics/addtopic.php?project=".$projectDetail->pro_id[0]."&amp;action=add&amp;".session_name()."=".session_id());

if ($error != "") {            
	$block1->headingError($strings["errors"]);
	$block1->contentError($error);
}

$block1->heading($strings["add_discussion"]);

$block1->openContent();
$block1->contentTitle($strings["info"]);

$block1->contentRow($strings["project"],$blockPage->buildLink("../projects/viewproject.php?id=".$projectDetail->pro_id[0],$projectDetail->pro_name[0]." (#".$projectDetail->pro_id[0].")",in));
$block1->contentRow($strings["organization"],$projectDetail->pro_org_name[0]);
$block1->contentRow($strings["owner"],$blockPage->buildLink("../users/viewuser.php?id=".$projectDetail->pro_mem_id[0],$projectDetail->pro_mem_name[0],in)." (".$blockPage->buildLink($projectDetail->pro_mem_email_work[0],$projectDetail->pro_mem_login[0],mail).")");

$block1->contentTitle($strings["details"]);

$block1->contentRow($strings["topic"],"<input size=\"44\" value=\"$ttt\" style=\"width: 400px\" name=\"ttt\" maxlength=\"64\" type=\"TEXT\">");
$block1->contentRow($strings["message"],"<textarea rows=\"10\" style=\"width: 400px; height: 160px;\" name=\"tpm\" cols=\"47\">$tpm</textarea>");
$block1->contentRow($strings["published"],"<input size=\"32\" value=\"0\" name=\"pub\" type=\"checkbox\">");
$block1->contentRow("","<input type=\"SUBMIT\" value=\"".$strings["save"]."\">");

$block1->closeContent();
$block1->closeForm();

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>