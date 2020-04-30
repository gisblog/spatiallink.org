<?php
#Application name: PhpCollab
#Status page: 0
#Path by root: ../topics/addpost.php

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

$tmpquery = "WHERE topic.id = '$id'";
$detailTopic = new request();
$detailTopic->openTopics($tmpquery);

$tmpquery = "WHERE pro.id = '".$detailTopic->top_project[0]."'";
$projectDetail = new request();
$projectDetail->openProjects($tmpquery);

if ($action == "add") {
	$tpm = convertData($tpm);
	autoLinks($tpm);
	$detailTopic->top_posts[0] = $detailTopic->top_posts[0] + 1;
	$tmpquery1 = "INSERT INTO ".$tableCollab["posts"]."(topic,member,created,message) VALUES('$id','$idSession','$dateheure','$newText')";
	connectSql("$tmpquery1");
	$tmpquery2 = "UPDATE ".$tableCollab["topics"]." SET last_post='$dateheure',posts='".$detailTopic->top_posts[0]."' WHERE id = '$id'";
	connectSql("$tmpquery2");

if ($notifications == "true") {
$tmpquery = "WHERE pos.topic = '".$detailTopic->top_id[0]."' ORDER BY mem.id";
$listPosts = new request();
$listPosts->openPosts($tmpquery);
$comptListPosts = count($listPosts->pos_id);

for ($i=0;$i<$comptListPosts;$i++) {
	if ($listPosts->pos_mem_id[$i] != $distinct) {
		$posters .= $listPosts->pos_mem_id[$i].",";
	}
	$distinct = $listPosts->pos_mem_id[$i];
}
if (substr($posters, -1) == ",") { $posters = substr($posters, 0, -1); }
//echo $posters;

$tmpquery = "WHERE noti.member IN($posters)";
$listNotifications = new request();
$listNotifications->openNotifications($tmpquery);
$comptListNotifications = count($listNotifications->not_id);

$newpost = new notification();

for ($i=0;$i<$comptListNotifications;$i++) {
	if ($listNotifications->not_newpost[$i] == "0" && $listNotifications->not_mem_email_work[$i] != "" && $listNotifications->not_mem_id[$i] != $idSession) {
		$newpost->discussionsNotification($listNotifications->not_mem_name[$i],$listNotifications->not_mem_email_work[$i],$listNotifications->not_mem_organization[$i],$id,"newpost");
	}
}
}
	headerFunction("../topics/viewtopic.php?id=$id&msg=add&".session_name()."=".session_id());
}

$idStatus = $detailTopic->top_status[0];
$idPublish = $detailTopic->top_published[0];

$tmpquery = "WHERE pos.topic = '".$detailTopic->top_id[0]."' ORDER BY pos.created DESC";
$listPosts = new request();
$listPosts->openPosts($tmpquery);
$comptListPosts = count($listPosts->pos_id);

if ($projectDetail->pro_org_id[0] == "1") {
	$projectDetail->pro_org_name[0] = $strings["none"];
}

$bodyCommand = "onLoad=\"document.ptTForm.tpm.focus();\"";
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
$blockPage->itemBreadcrumbs($detailTopic->top_subject[0]);
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

$block1->form = "ptT";
$block1->openForm("../topics/addpost.php?action=add&amp;id=".$detailTopic->top_id[0]."&amp;project=".$detailTopic->top_project[0]."&amp;".session_name()."=".session_id());

if ($error != "") {            
	$block1->headingError($strings["errors"]);
	$block1->contentError($error);
}

$block1->heading($strings["post_to_discussion"]." : ".$detailTopic->top_subject[0]);

$block1->openContent();
$block1->contentTitle($strings["info"]);

$block1->contentRow($strings["project"],$blockPage->buildLink("../projects/viewproject.php?id=".$projectDetail->pro_id[0],$projectDetail->pro_name[0]." (#".$projectDetail->pro_id[0].")",in));
$block1->contentRow($strings["organization"],$projectDetail->pro_org_name[0]);
$block1->contentRow($strings["owner"],$blockPage->buildLink("../users/viewuser.php?id=".$projectDetail->pro_mem_id[0],$projectDetail->pro_mem_name[0],in)." (".$blockPage->buildLink($projectDetail->pro_mem_email_work[0],$projectDetail->pro_mem_login[0],mail).")");

if ($sitePublish == "true") {
	$block1->contentRow($strings["published"],$statusPublish[$idPublish]);
}

$block1->contentRow($strings["retired"],$statusTopicBis[$idStatus]);
$block1->contentRow($strings["posts"],$detailTopic->top_posts[0]);
$block1->contentRow($strings["last_post"],createDate($detailTopic->top_last_post[0],$timezoneSession));

$block1->contentTitle($strings["details"]);

$block1->contentRow($strings["message"],"<textarea rows=\"10\" style=\"width: 400px; height: 160px;\" name=\"tpm\" cols=\"47\"></textarea>");
$block1->contentRow("","<input type=\"SUBMIT\" value=\"".$strings["save"]."\">");

$block1->contentTitle($strings["posts"]);

for ($i=0;$i<$comptListPosts;$i++) {
$block1->contentRow($strings["posted_by"],$blockPage->buildLink($listPosts->pos_mem_email_work[$i],$listPosts->pos_mem_name[$i],mail));

if ($listPosts->pos_created[$i] > $lastvisiteSession) {
	$block1->contentRow($strings["when"],"<b>".createDate($listPosts->pos_created[$i],$timezoneSession)."</b>");
} else {
	$block1->contentRow($strings["when"],createDate($listPosts->pos_created[$i],$timezoneSession));
}
$block1->contentRow("",nl2br($listPosts->pos_message[$i]));
$block1->contentRow("","","true");
}
	
$block1->closeContent();
$block1->closeForm();

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>