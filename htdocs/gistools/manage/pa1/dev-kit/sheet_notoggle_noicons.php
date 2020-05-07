<?php
#Application name: PhpCollab
#Status page: 1
#Path by root: ../dev-kit/sheet_notoggle_noicons.php

$checkSession = "true";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	$postnukeIntegration = "true";
	include("modules/PhpCollab/includes/library.php");
} else {
	$postnukeIntegration = "false";
	include("../includes/library.php");
}

$id = returnGlobal('id','GET');

$tmpquery = "WHERE org.id = '$id'";
$clientDetail = new request();
$clientDetail->openOrganizations($tmpquery);
$comptClientDetail = count($clientDetail->org_id);

if ($comptClientDetail == "0") {
	headerFunction("../clients/listclients.php?msg=blankClient&".session_name()."=".session_id());
}

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/header.php");
} else {
	include("../themes/".THEME."/header.php");
}

$blockPage = new block();
$blockPage->openBreadcrumbs();
$blockPage->itemBreadcrumbs($blockPage->buildLink("../clients/listclients.php?",$strings["organizations"],in));
$blockPage->itemBreadcrumbs($strings["organizations"]);
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

$block1->form = "ecD";
$block1->openForm("../projects/listprojects.php?".session_name()."=".session_id()."#".$block1->form."Anchor");

$block1->heading($strings["organization"]." : ".$clientDetail->org_name[0]);

$block1->openContent();
$block1->contentTitle($strings["details"]);

$block1->contentRow($strings["name"],$clientDetail->org_name[0]);
$block1->contentRow($strings["address"],$clientDetail->org_address1[0]);
$block1->contentRow($strings["phone"],$clientDetail->org_phone[0]);
$block1->contentRow($strings["url"],$blockPage->buildLink($clientDetail->org_url[0],$clientDetail->org_url[0],out));
$block1->contentRow($strings["email"],$blockPage->buildLink($clientDetail->org_email[0],$clientDetail->org_email[0],mail));

$block1->contentTitle($strings["details"]);

$block1->contentRow($strings["comments"],nl2br($clientDetail->org_comments[0]));
$block1->contentRow($strings["created"],createDate($clientDetail->org_created[0],$timezoneSession));

$block1->closeContent();
$block1->closeForm();

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>