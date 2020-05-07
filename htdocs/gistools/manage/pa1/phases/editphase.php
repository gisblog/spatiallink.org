<?php
#Application name: PhpCollab
#Status page: 0

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

$tmpquery = "WHERE pha.id = '$id'";
$phaseDetail = new request();
$phaseDetail->openPhases($tmpquery);
$project = $phaseDetail->pha_project_id[0];

$tmpquery = "WHERE pro.id = '$project'";
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

if ($action == "update") {
	$c = convertData($c);
	
	if ($st == 0) {
		$ed = "--";
	}
	
	if ($st == 1){
		$ed = "--";
	}	
	
	if ($st == 2 && $ed == "--") {
		$ed = date('Y-m-d');
	}	
	
	$tmpquery = "UPDATE ".$tableCollab["phases"]." SET status='$st', date_start='$sd', date_end='$ed', comments='$c' WHERE id = '$id'";
	connectSql("$tmpquery");
	
	if ($st != 1) {
		$tmpquery = "WHERE tas.parent_phase = '$id' AND tas.status = '3'";
		$changeTasks = new request();
		$changeTasks->openTasks($tmpquery);
		$comptchangeTasks = count($changeTasks->tas_id);
		for ($i=0;$i<$comptchangeTasks;$i++) {
			$taskID = $changeTasks->tas_id[$i];
			$tmpquery = "UPDATE ".$tableCollab["tasks"]." SET status='4' WHERE id = '$taskID'";
			connectSql("$tmpquery");
		}
	}
	headerFunction("../phases/viewphase.php?id=$id&".session_name()."=".session_id());
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
$blockPage->itemBreadcrumbs($phaseDetail->pha_name[0]);
$blockPage->closeBreadcrumbs();

if ($msg != "") {
if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/includes/messages.php");
} else {
	include("../includes/messages.php");
}
	$blockPage->messagebox($msgLabel);
}

//set value in form
$sd = $phaseDetail->pha_date_start[0];

$ed = $phaseDetail->pha_date_end[0];
$c = $phaseDetail->pha_comments[0];

$block1 = new block();
$block1->form = "pdD";
$block1->headingToggle($strings["phase"]." : ".$phaseDetail->pha_name[0]);
$block1->openContent();
$block1->contentTitle($strings["details"]);
$block1->form = "filedetails";
echo "<a name=\"filedetailsAnchor\"></a>";
echo "<form accept-charset=\"UNKNOWN\" method=\"POST\" action=\"../phases/editphase.php?id=$id&amp;action=update&amp;".session_name()."=".session_id()."#filedetailsAnchor\" name=\"filedetailsForm\" enctype=\"multipart/form-data\"><input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"100000000\"><input type=\"hidden\" name=\"maxCustom\" value=\"".$projectDetail->pro_upload_max[0]."\">";


echo"<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["name"]." :</td><td>".$phaseDetail->pha_name[0]."</td></tr>";
echo"<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["phase_id"]." :</td><td>".$phaseDetail->pha_id[0]."</td></tr>";


echo"<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["status"]." :</td><td><select name=\"st\">";

$comptSta = count($phaseStatus);

for ($i=0;$i<$comptSta;$i++) {
	if ($phaseDetail->pha_status[0] == $i) {
		echo "<option value=\"$i\" selected>$phaseStatus[$i]</option>";
	} else {
		echo "<option value=\"$i\">$phaseStatus[$i]</option>";
	}
}

echo "</select></td></tr>";

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/includes/calendar.php");
} else {
	include("../includes/calendar.php");
}

if ($sd == "") {
	$sd = $date;
}
if ($ed == "") {
	$ed = "--";
}

$block1->contentRow($strings["date_start"],"<input type=\"text\" name=\"sd\" id=\"sel1\" size=\"20\" value=\"$sd\"><input type=\"reset\" value=\" ... \" onclick=\"return showCalendar('sel1', 'y-mm-dd');\">");

$block1->contentRow($strings["date_end"],"<input type=\"text\" name=\"ed\" id=\"sel3\" size=\"20\" value=\"$ed\"><input type=\"reset\" value=\" ... \" onclick=\"return showCalendar('sel3', 'y-mm-dd');\">");

echo"<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["comments"]." :</td><td><textarea rows=\"3\" style=\"width: 400px; height: 100px;\" name=\"c\" cols=\"43\">$c</textarea></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">&nbsp;</td><td><input type=\"SUBMIT\" value=\"".$strings["save"]."\"></td></tr>";
$block1->closeContent();
$block1->closeToggle();
$block1->closeForm();

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>