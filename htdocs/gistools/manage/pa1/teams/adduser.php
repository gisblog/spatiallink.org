<?php
#Application name: PhpCollab
#Status page: 1
#Path by root: ../teams/adduser.php

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

if ($comptProjectDetail == "0") {
	headerFunction("../projects/listprojects.php?msg=blank&".session_name()."=".session_id());
	exit;
}

if($action == "add") {
	$pieces = explode("**", $id);
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
			$Htpasswd->addUser($listMembers->mem_login[$i],$listMembers->mem_password[$i]);
		}
	}
//if mantis bug tracker enabled	
	if ($enableMantis == "true") {
	//  include mantis library
		include( "../mantis/core_API.php" );
	}

	$comptAjout = count($pieces);
	for($i=0;$i<$comptAjout;$i++) {
		$tmpquery="INSERT INTO ".$tableCollab["teams"]."(project, member,published,authorized) VALUES ('".$projectDetail->pro_id[0]."','$pieces[$i]','1','0')";
		connectSql("$tmpquery");
//if mantis bug tracker enabled
		if ($enableMantis == "true") {
			// Assign user to this project in mantis
			$f_access_level	= $team_user_level; // Developer access
			$f_project_id = $projectDetail->pro_id[0];
			$f_user_id = $pieces[$i];
			include("../mantis/user_proj_add.php");
		}	

//if CVS repository enabled
		if ($enable_cvs == "true") {
			$user_query = "WHERE mem.id = '$pieces[$i]'";
			$cvsMembers = new request();
			$cvsMembers->openMembers($user_query);
			cvs_add_user($cvsMembers->mem_login[$i], $cvsMembers->mem_password[$i], $projectDetail->pro_id[0]);
		}
	}

if ($notifications == "true") {
$tmpquery = "WHERE noti.member IN($id)";
$listNotifications = new request();
$listNotifications->openNotifications($tmpquery);
$comptListNotifications = count($listNotifications->not_id);

$addprojectteam = new notification();

for ($i=0;$i<$comptListNotifications;$i++) {
	if ($listNotifications->not_addprojectteam[$i] == "0" && $listNotifications->not_mem_email_work[$i] != "") {
		$addprojectteam->projectteamNotification($listNotifications->not_mem_name[$i],$listNotifications->not_mem_email_work[$i],$listNotifications->not_mem_organization[$i],$project,"addprojectteam");
	}
}
}
	headerFunction("../projects/viewproject.php?".session_name()."=".session_id()."&id=".$projectDetail->pro_id[0]."&msg=add");
}

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/header.php");
} else {
	include("../themes/".THEME."/header.php");
}

//echo "$tmpquery<br>$comptMulti<br>";

$blockPage = new block();
$blockPage->openBreadcrumbs();
$blockPage->itemBreadcrumbs($blockPage->buildLink("../projects/listprojects.php?",$strings["projects"],in));
$blockPage->itemBreadcrumbs($blockPage->buildLink("../projects/viewproject.php?id=".$projectDetail->pro_id[0],$projectDetail->pro_name[0],in));
$blockPage->itemBreadcrumbs($blockPage->buildLink("../teams/listusers.php?id=".$projectDetail->pro_id[0],$strings["team_members"],in));
$blockPage->itemBreadcrumbs($strings["add_team"]);
$blockPage->closeBreadcrumbs();

$block1 = new block();

$block1->form = "atpt";
$block1->openForm("../teams/adduser.php?".session_name()."=".session_id()."&amp;project=$project#".$block1->form."Anchor");

$block1->heading($strings["add_team"]);

$block1->openPaletteIcon();
$block1->paletteIcon(0,"add",$strings["add"]);
$block1->paletteIcon(1,"info",$strings["view"]);
$block1->paletteIcon(2,"edit",$strings["edit"]);
$block1->closePaletteIcon();

$block1->sorting("users",$sortingUser->sor_users[0],"mem.name ASC",$sortingFields = array(0=>"mem.name",1=>"mem.title",2=>"mem.login",3=>"mem.phone_work",4=>"log.connected"));

$tmpquery = "WHERE tea.project = '$project' AND mem.profil != '3'";
$concatMembers = new request();
$concatMembers->openTeams($tmpquery);
$comptConcatMembers = count($concatMembers->tea_id);
for ($i=0;$i<$comptConcatMembers;$i++) {
	$membersTeam .= $concatMembers->tea_mem_id[$i];
		if ($i < $comptConcatMembers-1) {
			$membersTeam .= ",";
		} 
}

if ($demoMode == "true") {
$tmpquery = "WHERE mem.id NOT IN($membersTeam) AND mem.profil != '3' ORDER BY $block1->sortingValue";
} else {
$tmpquery = "WHERE mem.id NOT IN($membersTeam) AND mem.profil != '3' AND mem.id != '2' ORDER BY $block1->sortingValue";
}
$listMembers = new request();
$listMembers->openMembers($tmpquery);
$comptListMembers = count($listMembers->mem_id);

if ($comptListMembers != "0") {
	$block1->openResults();

	$block1->labels($labels = array(0=>$strings["full_name"],1=>$strings["title"],2=>$strings["user_name"],3=>$strings["work_phone"],4=>$strings["connected"]),"false");

for ($i=0;$i<$comptListMembers;$i++) {
if ($listMembers->mem_phone_work[$i] == "") {
	$listMembers->mem_phone_work[$i] = $strings["none"];
}
$block1->openRow();
$block1->checkboxRow($listMembers->mem_id[$i]);
$block1->cellRow($blockPage->buildLink("../users/viewuser.php?id=".$listMembers->mem_id[$i],$listMembers->mem_name[$i],in));
$block1->cellRow($listMembers->mem_title[$i]);
$block1->cellRow($blockPage->buildLink($listMembers->mem_email_work[$i],$listMembers->mem_login[$i],in));
$block1->cellRow($listMembers->mem_phone_work[$i]);
if ($listMembers->mem_log_connected[$i] > $dateunix-5*60) {
	$block1->cellRow($strings["yes"]." ".$z);
} else {
	$block1->cellRow($strings["no"]);
}
$block1->closeRow();
}
$block1->closeResults();
} else {
$block1->noresults();
}
$block1->closeFormResults();

$block1->openPaletteScript();
$block1->paletteScript(0,"add","../teams/adduser.php?project=$project&action=add","false,true,true",$strings["add"]);
$block1->paletteScript(1,"info","../users/viewuser.php?","false,true,false",$strings["view"]);
$block1->paletteScript(2,"edit","../users/edituser.php?","false,true,false",$strings["edit"]);
$block1->closePaletteScript($comptListMembers,$listMembers->mem_id);

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>