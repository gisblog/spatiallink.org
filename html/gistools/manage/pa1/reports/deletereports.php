<?php
#Application name: PhpCollab
#Status page: 0
#Path by root: ../reports/deletereports.php

$checkSession = "true";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	$postnukeIntegration = "true";
	include("modules/PhpCollab/includes/library.php");
} else {
	$postnukeIntegration = "false";
	include("../includes/library.php");
}

if ($action == "delete") {
	$id = str_replace("**",",",$id);
	$tmpquery1 = "DELETE FROM ".$tableCollab["reports"]." WHERE id IN($id)";
	connectSql("$tmpquery1");
	headerFunction("../general/home.php?msg=deleteReport&".session_name()."=".session_id());
	exit;
}

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/header.php");
} else {
	include("../themes/".THEME."/header.php");
}

$blockPage = new block();
$blockPage->openBreadcrumbs();
$blockPage->itemBreadcrumbs($blockPage->buildLink("../reports/listreports.php?",$strings["my_reports"],in));
$blockPage->itemBreadcrumbs($strings["delete_reports"]);
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

$block1->form = "saS";
$block1->openForm("../reports/deletereports.php?action=delete&amp;id=$id&amp;".session_name()."=".session_id());

$block1->heading($strings["delete_reports"]);

$block1->openContent();
$block1->contentTitle($strings["delete_following"]);

$id = str_replace("**",",",$id);
$tmpquery = "WHERE rep.id IN($id) ORDER BY rep.name";
$listReports = new request();
$listReports->openReports($tmpquery);
$comptListReports = count($listReports->rep_id);

for ($i=0;$i<$comptListReports;$i++) {
$block1->contentRow("#".$listReports->rep_id[$i],$listReports->rep_name[$i]);
}

$block1->contentRow("","<input type=\"submit\" name=\"delete\" value=\"".$strings["delete"]."\"> <input type=\"button\" name=\"cancel\" value=\"".$strings["cancel"]."\" onClick=\"history.back();\">");

$block1->closeContent();
$block1->closeForm();

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>