<?php
#Application name: PhpCollab
#Status page: 0
#Path by root: ../topics/deletepost.php

$checkSession = "true";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	$postnukeIntegration = "true";
	include("modules/PhpCollab/includes/library.php");
} else {
	$postnukeIntegration = "false";
	include("../includes/library.php");
}

$tmpquery = "WHERE topic.id = '$topic'";
$detailTopic = new request();
$detailTopic->openTopics($tmpquery);

if ($action == "delete") {
	$detailTopic->top_posts[0] = $detailTopic->top_posts[0] - 1;
	$tmpquery = "DELETE FROM ".$tableCollab["posts"]." WHERE id = '$id'";
	connectSql("$tmpquery");
	$tmpquery2 = "UPDATE ".$tableCollab["topics"]." SET posts='".$detailTopic->top_posts[0]."' WHERE id = '$topic'";
	connectSql("$tmpquery2");
	headerFunction("../topics/viewtopic.php?msg=delete&id=$topic&".session_name()."=".session_id());
	exit;
}

$tmpquery = "WHERE pos.id = '$id'";
$detailPost = new request();
$detailPost->openPosts($tmpquery);

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/header.php");
} else {
	include("../themes/".THEME."/header.php");
}

$blockPage = new block();
$blockPage->openBreadcrumbs();
$blockPage->itemBreadcrumbs($blockPage->buildLink("../projects/listprojects.php?",$strings["projects"],in));
$blockPage->itemBreadcrumbs($blockPage->buildLink("../projects/viewproject.php?id=".$detailTopic->top_pro_id[0],$detailTopic->top_pro_name[0],in));
$blockPage->itemBreadcrumbs($blockPage->buildLink("../topics/listtopics.php?topic=".$detailTopic->top_id[0],$strings["discussion"],in));
$blockPage->itemBreadcrumbs($blockPage->buildLink("../topics/viewtopic.php?id=".$detailTopic->top_id[0],$detailTopic->top_subject[0],in));
$blockPage->itemBreadcrumbs($strings["delete_messages"]);
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

$block1->form = "saP";
$block1->openForm("../topics/deletepost.php?id=$id&amp;topic=$topic&amp;action=delete&amp;".session_name()."=".session_id());

$block1->heading($strings["delete_messages"]);

$block1->openContent();
$block1->contentTitle($strings["delete_following"]);

echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">&nbsp;</td><td>".nl2br($detailPost->pos_message[0])."</td>

<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">&nbsp;</td><td><input type=\"submit\" name=\"delete\" value=\"".$strings["delete"]."\"> <input type=\"button\" name=\"cancel\" value=\"".$strings["cancel"]."\" onClick=\"history.back();\"></td></tr>";

$block1->closeContent();
$block1->closeForm();

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>