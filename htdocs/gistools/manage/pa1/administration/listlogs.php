<?php
#Application name: PhpCollab
#Status page: 1
#Path by root: ../administration/listlogs.php

$checkSession = "true";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	$postnukeIntegration = "true";
	include("modules/PhpCollab/includes/library.php");
} else {
	$postnukeIntegration = "false";
	include("../includes/library.php");
}

if ($profilSession != "0") {
	headerFunction("../general/permissiondenied.php?".session_name()."=".session_id());
	exit;
}

if ($action == "delete") {
	$tmpquery = "DELETE FROM ".$tableCollab["logs"];
	connectSql("$tmpquery");
}

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/header.php");
} else {
	include("../themes/".THEME."/header.php");
}

$blockPage = new block();
$blockPage->openBreadcrumbs();
$blockPage->itemBreadcrumbs($blockPage->buildLink("../administration/admin.php?",$strings["administration"],in));
$blockPage->itemBreadcrumbs($strings["logs"]);
$blockPage->closeBreadcrumbs();

$block1 = new block();

$block1->form = "adminD";
$block1->openForm("../administration/listlogs.php?action=delete&amp;".session_name()."=".session_id()."&amp;id=$id#".$block1->form."Anchor");

$block1->heading($strings["logs"]);
		  
$block1->openResults($checkbox="false");
$block1->labels($labels = array(0=>$strings["user_name"],1=>$strings["ip"],2=>$strings["session"],3=>$strings["compteur"],4=>$strings["last_visit"],5=>$strings["connected"]),"false",$sorting="false",$sortingOff = array(0=>"4",1=>"DESC"));

$tmpquery = "ORDER BY last_visite DESC";
$listLogs = new request();
$listLogs->openLogs($tmpquery);
$comptListLogs = count($listLogs->log_id);

$dateunix=date("U");

for ($i=0;$i<$comptListLogs;$i++) {
$block1->openRow();
$block1->checkboxRow($listLogs->log_id[$i],$checkbox="false");
$block1->cellRow($listLogs->log_login[$i]);
$block1->cellRow($listLogs->log_ip[$i]);
$block1->cellRow($listLogs->log_session[$i]);
$block1->cellRow($listLogs->log_compt[$i]);
$block1->cellRow(createDate($listLogs->log_last_visite[$i],$timezoneSession));
if ($listLogs->log_mem_profil[$i] == "3") {
	$z = "(Client on project site)";
} else {
	$z = "";
}
if ($listLogs->log_connected[$i] > $dateunix-5*60) {
$block1->cellRow($strings["yes"]." ".$z);
} else {
$block1->cellRow($strings["no"]);
}
$block1->closeRow();
}

$block1->closeResults();

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>