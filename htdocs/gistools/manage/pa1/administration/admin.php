<?php
#Application name: PhpCollab
#Status page: 1
#Path by root: ../administration/admin.php

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

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/header.php");
} else {
	include("../themes/".THEME."/header.php");
}

$blockPage = new block();
$blockPage->openBreadcrumbs();
$blockPage->itemBreadcrumbs($blockPage->buildLink("../administration/admin.php?",$strings["administration"],in));
$blockPage->itemBreadcrumbs($strings["admin_intro"]);
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

$block1->heading($strings["administration"]);

$block1->openContent();
$block1->contentTitle($strings["admin_intro"]);

$block1->contentRow("",$blockPage->buildLink("../users/listusers.php?",$strings["user_management"],in));

if ($supportType == "admin") {
$block1->contentRow("",$blockPage->buildLink("../administration/support.php?",$strings["support_management"],in));
}

if ($databaseType == "mysql") {
$block1->contentRow("",$blockPage->buildLink("../administration/phpmyadmin.php?",$strings["database"],in));
}
if ($databaseType == "postgresql") {
$block1->contentRow("",$blockPage->buildLink("../administration/phppgadmin.php?",$strings["database"],in));
}

$block1->contentRow("",$blockPage->buildLink("../administration/systeminfo.php?",$strings["system_information"],in));
$block1->contentRow("",$blockPage->buildLink("../administration/mycompany.php?",$strings["company_details"],in));
$block1->contentRow("",$blockPage->buildLink("../administration/listlogs.php?",$strings["logs"],in));
$block1->contentRow($strings["update"].$blockPage->printHelp("admin_update"),"1. ".$blockPage->buildLink("../administration/updatesettings.php?",$strings["edit_settings"],in)." 2. ".$blockPage->buildLink("../administration/updatedatabase.php?",$strings["edit_database"],in));

if ($updateChecker == "true" && $installationType == "online") {
	$block1->contentRow("",updatechecker($version));
}

if (file_exists("modules/PhpCollab/pnversion.php") && file_exists("modules/PhpCollab/installation/setup.php")) {
	$block1->contentRow("","<b>".$strings["attention"]."</b> : ".$strings["setup_erase"]);
}
if (file_exists("../installation/setup.php")) {
	$block1->contentRow("","<b>".$strings["attention"]."</b> : ".$strings["setup_erase"]);
}

$block1->closeContent();

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>