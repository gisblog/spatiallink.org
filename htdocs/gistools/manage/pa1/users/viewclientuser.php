<?php
#Application name: PhpCollab
#Status page: 1
#Path by root: ../users/viewclientuser.php

$checkSession = "true";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	$postnukeIntegration = "true";
	include("modules/PhpCollab/includes/library.php");
} else {
	$postnukeIntegration = "false";
	include("../includes/library.php");
}

$tmpquery = "WHERE mem.id = '$id'";
$userDetail = new request();
$userDetail->openMembers($tmpquery);
$comptUserDetail = count($userDetail->mem_id);

if ($comptUserDetail == "0") {
	headerFunction("../clients/viewclient.php?msg=blankUser&id=$organization&".session_name()."=".session_id());
	exit;
}
$organization = $userDetail->mem_organization[0];

if ($clientsFilter == "true" && $profilSession == "2") {
$teamMember = "false";
$tmpquery = "WHERE tea.member = '$idSession' AND org2.id = '$organization'";
$memberTest = new request();
$memberTest->openTeams($tmpquery);
$comptMemberTest = count($memberTest->tea_id);
	if ($comptMemberTest == "0") {
		headerFunction("../clients/listclients.php?msg=blankClient&".session_name()."=".session_id());
	} else {
	}
} else if ($clientsFilter == "true" && $profilSession == "1") {
	$tmpquery = "WHERE org.owner = '$idSession' AND org.id = '$organization'";
} else {
	$tmpquery = "WHERE org.id = '$organization'";
}

$comptDetailClient = "0";
$detailClient = new request();
$detailClient->openOrganizations($tmpquery);
$comptDetailClient = count($detailClient->org_id);

if ($comptDetailClient == "0") {
	headerFunction("../clients/listclients.php?msg=blankClient&".session_name()."=".session_id());
	exit;
}

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/header.php");
} else {
	include("../themes/".THEME."/header.php");
}

$blockPage = new block();
$blockPage->openBreadcrumbs();
$blockPage->itemBreadcrumbs($blockPage->buildLink("../clients/listclients.php?",$strings["clients"],in));
$blockPage->itemBreadcrumbs($blockPage->buildLink("../clients/viewclient.php?id=$organization",$detailClient->org_name[0],in));
$blockPage->itemBreadcrumbs($userDetail->mem_login[0]);
$blockPage->closeBreadcrumbs();

$block1 = new block();

$block1->form = "cuserD";
$block1->openForm("../users/viewclientuser.php?".session_name()."=".session_id()."#".$block1->form."Anchor");

if ($error != "") {            
	$block1->headingError($strings["errors"]);
	$block1->contentError($error);
}

$block1->heading($strings["client_user"]);

$block1->openPaletteIcon();
if ($profilSession == "0" || $profilSession == "1") {
$block1->paletteIcon(0,"remove",$strings["delete"]);
$block1->paletteIcon(1,"edit",$strings["edit"]);
}
$block1->paletteIcon(2,"export",$strings["export"]);
$block1->closePaletteIcon();

$block1->openContent();
$block1->contentTitle($strings["user_details"]);

$block1->contentRow($strings["user_name"],$userDetail->mem_login[0]);
$block1->contentRow($strings["full_name"],$userDetail->mem_name[0]);
$block1->contentRow($strings["organization"],$userDetail->mem_org_name[0]);
$block1->contentRow($strings["email"],$userDetail->mem_email_work[0]);
$block1->contentRow($strings["work_phone"],$userDetail->mem_phone_work[0]);
$block1->contentRow($strings["home_phone"],$userDetail->mem_phone_home[0]);
$block1->contentRow($strings["mobile_phone"],$userDetail->mem_mobile[0]);
$block1->contentRow($strings["fax"],$userDetail->mem_fax[0]);
$block1->contentRow($strings["comments"],nl2br($userDetail->mem_comments[0]));
$block1->contentRow($strings["account_created"],createDate($userDetail->mem_created[0],$timezoneSession));

$block1->contentTitle($strings["information"]);

$tmpquery = "SELECT tas.id FROM ".$tableCollab["tasks"]." tas LEFT OUTER JOIN ".$tableCollab["projects"]." pro ON pro.id = tas.project WHERE tas.assigned_to = '".$userDetail->mem_id[0]."' AND tas.status IN(0,2,3) AND pro.status IN(0,2,3)";
compt($tmpquery);
$valueTasks = $countEnregTotal;

$block1->contentRow($strings["tasks"],$valueTasks);

$z = "(Client on project site)";
if ($userDetail->mem_log_connected[0] > $dateunix-5*60) {
	$connected_result = $strings["yes"]." ".$z;
} else {
	$connected_result = $strings["no"];
}
$block1->contentRow($strings["connected"],$connected_result);

$block1->closeContent();
$block1->closeForm();

$block1->openPaletteScript();
if ($profilSession == "0" || $profilSession == "1") {
$block1->paletteScript(0,"remove","../users/deleteclientusers.php?".session_name()."=".session_id()."&id=$id&organization=$organization","true,true,true",$strings["delete"]);
$block1->paletteScript(1,"edit","../users/updateclientuser.php?".session_name()."=".session_id()."&id=$id&organization=$organization","true,true,true",$strings["edit"]);
}
$block1->paletteScript(2,"export","../users/exportuser.php?id=$id","true,true,true",$strings["export"]);
$block1->closePaletteScript("","");

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>