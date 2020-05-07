<?php
#Application name: PhpCollab
#Status page: 1
#Path by root: ../projects/deleteprojectsite.php

$checkSession = "true";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	$postnukeIntegration = "true";
	include("modules/PhpCollab/includes/library.php");
} else {
	$postnukeIntegration = "false";
	include("../includes/library.php");
}

if ($action == "delete") {
	$tmpquery = "UPDATE ".$tableCollab["projects"]." SET published='1' WHERE id = '$project'";
	connectSql("$tmpquery");
	headerFunction("../projects/viewproject.php?id=$project&msg=removeProjectSite&".session_name()."=".session_id());
	exit;
}

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/header.php");
} else {
	include("../themes/".THEME."/header.php");
}

$tmpquery = "WHERE pro.id = '$project'";
$projectDetail = new request();
$projectDetail->openProjects($tmpquery);

$blockPage = new block();
$blockPage->openBreadcrumbs();
$blockPage->itemBreadcrumbs($blockPage->buildLink("../projects/listprojects.php?",$strings["projects"],in));
$blockPage->itemBreadcrumbs($blockPage->buildLink("../projects/viewproject.php?id=".$projectDetail->pro_id[0],$projectDetail->pro_name[0],in));
$blockPage->itemBreadcrumbs($blockPage->buildLink("../projects/viewprojectsite.php?id=".$projectDetail->pro_id[0],$strings["project_site"],in));
$blockPage->itemBreadcrumbs($strings["delete_projectsite"]);
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

$block1->form = "projectsite_delete";
$block1->openForm("../projects/deleteprojectsite.php?action=delete&amp;project=$project&amp;".session_name()."=".session_id());

$block1->heading($strings["delete_projectsite"]);

$block1->openContent();
$block1->contentTitle($strings["delete_following"]);

$block1->contentRow("",$projectDetail->pro_name[0]);

$block1->contentRow("","<input type=\"submit\" name=\"delete\" value=\"".$strings["delete"]."\"> <input type=\"button\" name=\"cancel\" value=\"".$strings["cancel"]."\" onClick=\"history.back();\">");

$block1->closeContent();
$block1->closeForm();

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>