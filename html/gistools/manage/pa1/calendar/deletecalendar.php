<?php
#Application name: PhpCollab
#Status page: 0
#Path by root: ../calendar/deletecalendar.php

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
	$tmpquery1 = "DELETE FROM ".$tableCollab["calendar"]." WHERE id IN($id)";
	connectSql("$tmpquery1");
	headerFunction("../calendar/viewcalendar.php?msg=delete&".session_name()."=".session_id());
	exit;
}

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/header.php");
} else {
	include("../themes/".THEME."/header.php");
}

$blockPage = new block();
$blockPage->openBreadcrumbs();
$blockPage->itemBreadcrumbs($blockPage->buildLink("../calendar/viewcalendar.php?",$strings["calendar"],in));
$blockPage->itemBreadcrumbs($strings["delete"]);
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
$block1->form = "saP";
$block1->openForm("../calendar/deletecalendar.php?project=$project&amp;action=delete&amp;id=$id&amp;".session_name()."=".session_id());

$block1->heading($strings["delete"]);

$block1->openContent();
$block1->contentTitle($strings["delete_following"]);

$id = str_replace("**",",",$id);
$tmpquery = "WHERE cal.id IN($id) ORDER BY cal.subject";
$listCalendar = new request();
$listCalendar->openCalendar($tmpquery);
$comptListCalendar = count($listCalendar->cal_id);

for ($i=0;$i<$comptListCalendar;$i++) {
echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">#".$listCalendar->cal_id[$i]."</td><td>".$listCalendar->cal_subject[$i]."</td></tr>";
}

echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">&nbsp;</td><td><input type=\"submit\" name=\"delete\" value=\"".$strings["delete"]."\"> <input type=\"button\" name=\"cancel\" value=\"".$strings["cancel"]."\" onClick=\"history.back();\"></td></tr>";

$block1->closeContent();
$block1->closeForm();

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>