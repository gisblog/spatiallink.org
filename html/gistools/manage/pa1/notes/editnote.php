<?php
#Application name: PhpCollab
#Status page: 0
#Path by root: ../notes/editnote.php

$checkSession = "true";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	$postnukeIntegration = "true";
	include("modules/PhpCollab/includes/library.php");
} else {
	$postnukeIntegration = "false";
	include("../includes/library.php");
}
if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/includes/customvalues.php");
} else {
	include("../includes/customvalues.php");
}

if ($id != "" && $action != "add") {
	$tmpquery = "WHERE note.id = '$id'";
	$noteDetail = new request();
	$noteDetail->openNotes($tmpquery);
	$tmpquery = "WHERE pro.id = '".$noteDetail->note_project[0]."'";
	$project = $noteDetail->note_project[0];
if ($noteDetail->note_owner[0] != $idSession) {
	headerFunction("../notes/listnotes.php?project=$project&msg=noteOwner&".session_name()."=".session_id());
	exit;
}

} else {
	$tmpquery = "WHERE pro.id = '$project'";
}

$projectDetail = new request();
$projectDetail->openProjects($tmpquery);

$teamMember = "false";
$tmpquery = "WHERE tea.project = '$project' AND tea.member = '$idSession'";
$memberTest = new request();
$memberTest->openTeams($tmpquery);
$comptMemberTest = count($memberTest->tea_id);
if ($comptMemberTest == "0") {
	$teamMember = "false";
} else {
	$teamMember = "true";
}

//case update note entry
if ($id != "") {
	//case update note entry
	if ($action == "update") {
		$subject = convertData($subject);
		$description = convertData($description);
		$tmpquery5 = "UPDATE ".$tableCollab["notes"]." SET project='$projectMenu',topic='$topic',subject='$subject',description='$description',date='$dd',owner='$idSession' WHERE id = '$id'";
		$msg = "update";
		connectSql("$tmpquery5");
		headerFunction("../notes/viewnote.php?id=$id&msg=$msg&".session_name()."=".session_id());
		exit;
	}
	
	//set value in form
	$dd = $noteDetail->note_date[0];
	$subject = $noteDetail->note_subject[0];
	$description = $noteDetail->note_description[0];
	$topic = $noteDetail->note_topic[0];
}

//case add note entry
if ($id == "") {

	//case add note entry
	if ($action == "add") {
		$subject = convertData($subject);
		$description = convertData($description);
		$tmpquery1 = "INSERT INTO ".$tableCollab["notes"]."(project,topic,subject,description,date,owner,published) VALUES('$projectMenu','$topic','$subject','$description','$dd','$idSession','1')";
		connectSql("$tmpquery1");
		$tmpquery = $tableCollab["notes"];
		last_id($tmpquery);
		$num = $lastId[0];
		unset($lastId);
		headerFunction("../notes/viewnote.php?id=$num&msg=add&".session_name()."=".session_id());
		exit;
	}

}

$bodyCommand = "onLoad=\"document.etDForm.subject.focus();\"";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/header.php");
} else {
	include("../themes/".THEME."/header.php");
}

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/includes/calendar.php");
} else {
	include("../includes/calendar.php");
}

$blockPage = new block();
$blockPage->openBreadcrumbs();
$blockPage->itemBreadcrumbs($blockPage->buildLink("../projects/listprojects.php?",$strings["projects"],in));
$blockPage->itemBreadcrumbs($blockPage->buildLink("../projects/viewproject.php?id=".$projectDetail->pro_id[0],$projectDetail->pro_name[0],in));
$blockPage->itemBreadcrumbs($blockPage->buildLink("../notes/listnotes.php?project=".$projectDetail->pro_id[0],$strings["notes"],in));
if ($id == "") {
	$blockPage->itemBreadcrumbs($strings["add_note"]);
}
if ($id != "") {
	$blockPage->itemBreadcrumbs($blockPage->buildLink("../notes/viewnote.php?id=".$noteDetail->note_id[0],$noteDetail->note_subject[0],in));
	$blockPage->itemBreadcrumbs($strings["edit_note"]);
}
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
if ($id == "") {
	$block1->form = "etD";
	$block1->openForm("../notes/editnote.php?project=$project&amp;id=$id&amp;action=add&amp;".session_name()."=".session_id()."#".$block1->form."Anchor");
}
if ($id != "") {
	$block1->form = "etD";
	$block1->openForm("../notes/editnote.php?project=$project&amp;id=$id&amp;action=update&amp;".session_name()."=".session_id()."#".$block1->form."Anchor");
}
if ($error != "") {            
	$block1->headingError($strings["errors"]);
	$block1->contentError($error);
}
if ($id == "") {
	$block1->heading($strings["add_note"]);
}
if ($id != "") {
	$block1->heading($strings["edit_note"]." : ".$noteDetail->note_subject[0]);
}

$block1->openContent();
$block1->contentTitle($strings["details"]);


echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["project"]." :</td><td><select name=\"projectMenu\">";

$tmpquery = "WHERE tea.member = '$idSession' ORDER BY pro.name";
$listProjects = new request();
$listProjects->openTeams($tmpquery);
$comptListProjects = count($listProjects->tea_id);

for ($i=0;$i<$comptListProjects;$i++) {
	if ($listProjects->tea_pro_id[$i] == $noteDetail->note_project[0] || $project == $listProjects->tea_pro_id[$i]) {
		echo "<option value=\"".$listProjects->tea_pro_id[$i]."\" selected>".$listProjects->tea_pro_name[$i]."</option>";
	} else {
		echo "<option value=\"".$listProjects->tea_pro_id[$i]."\">".$listProjects->tea_pro_name[$i]."</option>";
	}
}

echo "</select></td></tr>";

$block1->contentRow($strings["date"],"<input type=\"text\" name=\"dd\" id=\"sel3\" size=\"20\" value=\"$dd\"><input type=\"reset\" value=\" ... \" onclick=\"return showCalendar('sel3', 'y-mm-dd');\">");

$comptTopic = count($topicNote);

if ($comptTopic != "0") {
echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["topic"]." :</td><td><select name=\"topic\"><option value=\"\">".$strings["choice"]."</option>";

for ($i=1;$i<=$comptTopic;$i++) {
	if ($topic == $i) {
		echo "<option value=\"$i\" selected>$topicNote[$i]</option>";
	} else {
		echo "<option value=\"$i\">$topicNote[$i]</option>";
	}
}
echo "</select></td></tr>";
}

$block1->contentRow($strings["subject"],"<input size=\"44\" value=\"$subject\" style=\"width: 400px\" name=\"subject\" maxlength=\"100\" type=\"TEXT\">");
$block1->contentRow($strings["description"],"<textarea rows=\"10\" style=\"width: 400px; height: 160px;\" name=\"description\" cols=\"47\">$description</textarea>");
$block1->contentRow("","<input type=\"SUBMIT\" value=\"".$strings["save"]."\">");
$block1->closeContent();
$block1->closeForm();

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>