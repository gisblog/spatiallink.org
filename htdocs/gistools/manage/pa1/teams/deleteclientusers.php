<?php
#Application name: PhpCollab
#Status page: 1
#Path by root: ../teams/deleteclientusers.php

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
$comptProjectDetail = count($projectDetail->pro_id);

if ($comptProjectDetail == "0") {
	headerFunction("../projects/listprojects.php?msg=blank&".session_name()."=".session_id());
	exit;
}

if ($action == "delete") {
	$id = str_replace("**",",",$id);
	$pieces = explode(",",$id);

	if ($htaccessAuth == "true") {
		include("../includes/htpasswd.class.php");
		$Htpasswd = new Htpasswd;
		$Htpasswd->initialize("../files/".$projectDetail->pro_id[0]."/.htpasswd");

		$tmpquery = "WHERE mem.id IN($id)";
		$listMembers = new request();
		$listMembers->openMembers($tmpquery);
		$comptListMembers = count($listMembers->mem_id);

		for ($i=0;$i<$comptListMembers;$i++) {
			$Htpasswd->deleteUser($listMembers->mem_login[$i]);
		}
	}

//if mantis bug tracker enabled	
	if ($enableMantis == "true") {
	//  include mantis library
		include( "../mantis/core_API.php" );
	}
	$compt = count($pieces);
	for ($i=0;$i<$compt;$i++) {
		$tmpquery1 = "DELETE FROM ".$tableCollab["teams"]." WHERE member = '$pieces[$i]'";
		connectSql("$tmpquery1");
//if mantis bug tracker enabled
		if ($enableMantis == "true") {
// Unassign user from this project in mantis
			$f_project_id = $project;
			$f_user_id = $pieces[$i];
			include("../mantis/user_proj_delete.php");
		}
	}
if ($notifications == "true") {
$tmpquery = "WHERE noti.member IN($id)";
$listNotifications = new request();
$listNotifications->openNotifications($tmpquery);
$comptListNotifications = count($listNotifications->not_id);

$removeprojectteam = new notification();

for ($i=0;$i<$comptListNotifications;$i++) {
	if ($listNotifications->not_removeprojectteam[$i] == "0" && $listNotifications->not_member[$i] != $projectDetail->pro_owner[0] && $listNotifications->not_mem_email_work[$i] != "") {
		$removeprojectteam->projectteamNotification($listNotifications->not_mem_name[$i],$listNotifications->not_mem_email_work[$i],$listNotifications->not_mem_organization[$i],$project,"removeprojectteam");
	}
}
}
	headerFunction("../projects/viewprojectsite.php?id=$project&msg=removeClientToSite&".session_name()."=".session_id());
	exit;
}

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/header.php");
} else {
	include("../themes/".THEME."/header.php");
}

$blockPage = new block();
$blockPage->openBreadcrumbs();
$blockPage->itemBreadcrumbs($blockPage->buildLink("../projects/listprojects.php?",$strings["projects"],in));
$blockPage->itemBreadcrumbs($blockPage->buildLink("../projects/viewproject.php?id=".$projectDetail->pro_id[0],$projectDetail->pro_name[0],in));
$blockPage->itemBreadcrumbs($blockPage->buildLink("../projects/viewprojectsite.php?id=".$projectDetail->pro_id[0],$strings["project_site"],in));
$blockPage->itemBreadcrumbs($strings["remove_team_client"]);
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

$block1->form = "crM";
$block1->openForm("../teams/deleteclientusers.php?project=$project&amp;action=delete&amp;id=$id&amp;".session_name()."=".session_id());

$block1->heading($strings["remove_team_client"]);

$block1->openContent();
$block1->contentTitle($strings["remove_team_info"]);

$id = str_replace("**",",",$id);
$tmpquery = "WHERE mem.id IN($id) ORDER BY mem.name";
$listMembers = new request();
$listMembers->openMembers($tmpquery);
$comptListMembers = count($listMembers->mem_id);

for ($i=0;$i<$comptListMembers;$i++) {
$block1->contentRow("#".$listMembers->mem_id[$i],$listMembers->mem_login[$i]." (".$listMembers->mem_name[$i].")");
}

$block1->contentRow("","<input type=\"SUBMIT\" value=\"".$strings["delete"]."\">&#160;<input type=\"BUTTON\" value=\"".$strings["remove"]."\" onClick=\"history.back();\">");

$block1->closeContent();
$block1->closeForm();

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>