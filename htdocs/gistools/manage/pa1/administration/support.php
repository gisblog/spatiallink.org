<?php
#Application name: PhpCollab
#Status page: 0
#Path by root: ../administration/support.php

$checkSession = "true";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	$postnukeIntegration = "true";
	include("modules/PhpCollab/includes/library.php");
} else {
	$postnukeIntegration = "false";
	include("../includes/library.php");
}

if ($profilSession != "0" || $enableHelpSupport != "true") {
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
$blockPage->itemBreadcrumbs($strings["support_management"]);
$blockPage->closeBreadcrumbs();

if ($msg != "") {
if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/includes/messages.php");
} else {
	include("../includes/messages.php");
}
	$blockPage->messagebox($msgLabel);
}

if ($enableHelpSupport == "true"){
$tmpquery = "WHERE sr.status = '0'";
$listNewRequests = new request();
$listNewRequests->openSupportRequests($tmpquery);
$comptListNewRequests = count($listNewRequests->sr_id);

$tmpquery = "WHERE sr.status = '1'";
$listOpenRequests = new request();
$listOpenRequests->openSupportRequests($tmpquery);
$comptListOpenRequests = count($listOpenRequests->sr_id);

$tmpquery = "WHERE sr.status = '2'";
$listCompleteRequests = new request();
$listCompleteRequests->openSupportRequests($tmpquery);
$comptListCompleteRequests = count($listCompleteRequests->sr_id);

$block1 = new block();
$block1->form = "help";
if ($error != "") {            
	$block1->headingError($strings["errors"]);
	$block1->contentError($error);
}
$block1->heading($strings["support_requests"]);
$block1->openContent();
$block1->contentTitle($strings["information"]);

$block1->contentRow($strings["new_requests"],"$comptListNewRequests - ".$blockPage->buildLink("../support/support.php?action=new",$strings["manage_new_requests"],in)."<br><br>");
$block1->contentRow($strings["open_requests"],"$comptListOpenRequests - ".$blockPage->buildLink("../support/support.php?action=open",$strings["manage_open_requests"],in)."<br><br>");
$block1->contentRow($strings["closed_requests"],"$comptListCompleteRequests - ".$blockPage->buildLink("../support/support.php?action=complete",$strings["manage_closed_requests"],in)."<br><br>");
$block1->closeContent();
}

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>