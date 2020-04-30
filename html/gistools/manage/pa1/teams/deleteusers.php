<?php
#Application name: PhpCollab
#Status page: 1
#Path by root: ../teams/deleteusers.php

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

if ($enable_cvs == "true") {
if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/includes/cvslib.php");
} else {
	include("../includes/cvslib.php");
}
}

$tmpquery = "WHERE pro.id = '$project'";
$projectDetail = new request();
$projectDetail->openProjects($tmpquery);
$comptProjectDetail = count($projectDetail->pro_id);

//test exists selected project, redirect to list if not
if ($comptProjectDetail == "0") {
	headerFunction("../projects/listprojects.php?msg=blank&".session_name()."=".session_id());
	exit;
}

if ($action == "delete") {
$id = str_replace("**",",",$id);

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

$multi = strstr($id,",");
	if ($multi != "") {
	$pieces = explode(",",$id);
	$compt = count($pieces);
		for ($i=0;$i<$compt;$i++) {
			if ($projectDetail->pro_owner[0] != $pieces[$i]) {
				$tmpquery1 = "DELETE FROM ".$tableCollab["teams"]." WHERE member = '$pieces[$i]' AND project = '$project'";
				connectSql("$tmpquery1");

//if mantis bug tracker enabled
			if ($enableMantis == "true") {
// Unassign multiple user from this project in mantis
				$f_project_id = $project;
				$f_user_id = $pieces[$i];
				include("../mantis/user_proj_delete.php");
			}

//if CVS repository enabled
				if ($enable_cvs == "true") {
					$user_query = "WHERE mem.id = '$pieces[$i]'";
					$cvsMember = new request();
					$cvsMember->openMembers($user_query);








					cvs_delete_user($cvsMember->mem_login[$i], $project);
				}
			}
			if ($projectDetail->pro_owner[0] == $pieces[$i]) {
				$foundOwner = "true";
			}
		}
		if ($foundOwner == "true") {
			$msg = "deleteTeamOwnerMix";
		} else {
			$msg = "delete";
		}
	} else {
		$tmpquery1 = "DELETE FROM ".$tableCollab["teams"]." WHERE member = '$id' AND project = '$project'";
		if ($projectDetail->pro_owner[0] == $id) {
			$msg = "deleteTeamOwner";
		} else {
			connectSql("$tmpquery1");
			$msg = "delete";

//if mantis bug tracker enabled
		if ($enableMantis == "true") {
// Unassign single user from this project in mantis
			$f_project_id = $project;
			$f_user_id = $id;
			include("../mantis/user_proj_delete.php");
		}

//if CVS repository enabled
			if ($enable_cvs == "true") {
				$user_query = "WHERE mem.id = '$id'";
				$cvsMember = new request();
				$cvsMember->openMembers($user_query);
				cvs_delete_user($cvsMember->mem_login[0], $project);
			}
		}
	}

//$tmpquery3 = "UPDATE ".$tableCollab["tasks"]." SET assigned_to='0' WHERE assigned_to IN($id) AND assigned_to != '$projectDetail->pro_owner[0]'";
//connectSql("$tmpquery3");

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
	headerFunction("../projects/viewproject.php?id=$project&msg=$msg&".session_name()."=".session_id());
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
$blockPage->itemBreadcrumbs($strings["remove_team"]);
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
$block1->openForm("../teams/deleteusers.php?project=$project&amp;action=delete&amp;id=$id&amp;".session_name()."=".session_id());

$block1->heading($strings["remove_team"]);

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

$block1->contentRow("","<input type=\"SUBMIT\" value=\"".$strings["remove"]."\">&#160;<input type=\"BUTTON\" value=\"".$strings["cancel"]."\" onClick=\"history.back();\">");

$block1->closeContent();
$block1->closeForm();

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>