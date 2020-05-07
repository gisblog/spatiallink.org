<?php
#Application name: PhpCollab
#Status page: 1
#Path by root: ../teams/listusers.php

$checkSession = "true";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	$postnukeIntegration = "true";
	include("modules/PhpCollab/includes/library.php");
} else {
	$postnukeIntegration = "false";
	include("../includes/library.php");
}

$tmpquery = "WHERE pro.id = '$id'";
$projectDetail = new request();
$projectDetail->openProjects($tmpquery);
$comptProjectDetail = count($projectDetail->pro_id);

if ($comptPro == "0") {
	headerFunction("../projects/listprojects.php?msg=blank&".session_name()."=".session_id());
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
$blockPage->itemBreadcrumbs($strings["team_members"]);
$blockPage->closeBreadcrumbs();

$block1 = new block();

$block1->form = "saM";
$block1->openForm("../teams/listusers.php?".session_name()."=".session_id()."&amp;id=$id#".$block1->form."Anchor");

$block1->heading($strings["team_members"]);

$block1->openPaletteIcon();
$block1->paletteIcon(0,"add",$strings["add"]);
$block1->paletteIcon(1,"remove",$strings["delete"]);
$block1->closePaletteIcon();

$block1->sorting("team",$sortingUser->sor_team[0],"mem.name ASC",$sortingFields = array(0=>"mem.name",1=>"mem.title",2=>"mem.login",3=>"mem.phone_work",4=>"log.connected",5=>"tea.published"));

$tmpquery = "WHERE tea.project = '$id' AND mem.profil != '3' ORDER BY $block1->sortingValue";
$listTeam = new request();
$listTeam->openTeams($tmpquery);
$comptListTeam = count($listTeam->tea_id);

	$block1->openResults();

	$block1->labels($labels = array(0=>$strings["full_name"],1=>$strings["title"],2=>$strings["user_name"],3=>$strings["work_phone"],4=>$strings["connected"],5=>$strings["published"]),"true");

for ($i=0;$i<$comptListTeam;$i++) {
if ($listTeam->tea_mem_phone_work[$i] == "") {
	$listTeam->tea_mem_phone_work[$i] = $strings["none"];

}
$idPublish = $listTeam->tea_published[$i];
$block1->openRow();
$block1->checkboxRow($listTeam->tea_mem_id[$i]);
$block1->cellRow($blockPage->buildLink("../users/viewuser.php?id=".$listTeam->tea_mem_id[$i],$listTeam->tea_mem_name[$i],in));
$block1->cellRow($listTeam->tea_mem_title[$i]);
$block1->cellRow($blockPage->buildLink($listTeam->tea_mem_email_work[$i],$listTeam->tea_mem_login[$i],mail));
$block1->cellRow($listTeam->tea_mem_phone_work[$i]);

if ($listTeam->tea_log_connected[$i] > $dateunix-5*60) {
	$block1->cellRow($strings["yes"]." ".$z);
} else {
	$block1->cellRow($strings["no"]);
}
if ($sitePublish == "true") {
$block1->cellRow($statusPublish[$idPublish]);
}
$block1->closeRow();
}
$block1->closeResults();

$block1->closeFormResults();

$block1->openPaletteScript();
$block1->paletteScript(0,"add","../teams/adduser.php?project=".$projectDetail->pro_id[0]."","true,true,true",$strings["add"]);
$block1->paletteScript(1,"remove","../teams/deleteusers.php?project=".$projectDetail->pro_id[0]."","false,true,true",$strings["delete"]);
$block1->closePaletteScript($comptListTeam,$listTeam->tea_mem_id);

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>