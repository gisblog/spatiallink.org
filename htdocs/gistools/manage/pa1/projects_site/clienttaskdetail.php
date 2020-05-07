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

if ($action == "update") {
$comments = convertData($comments);
if ($checkbox != "") {
	$tmpquery = "UPDATE ".$tableCollab["tasks"]." SET comments='$comments',status='0',modified='$dateheure' WHERE id = '$id'";
} else {
	$tmpquery = "UPDATE ".$tableCollab["tasks"]." SET comments='$comments',status='3',modified='$dateheure' WHERE id = '$id'";
}
	connectSql("$tmpquery");
	headerFunction("showallclienttasks.php?".session_name()."=".session_id());
	exit;
}

$tmpquery = "WHERE tas.id = '$id'";
$taskDetail = new request();
$taskDetail->openTasks($tmpquery);

if ($taskDetail->tas_published[0] == "1" || $taskDetail->tas_project[0] != $projectSession) {
headerFunction("index.php");
}

$bouton[3] = "over";
$titlePage = $strings["client_task_details"];
include ("include_header.php");

$block1 = new block();

$block1->heading($strings["client_task_details"]);

echo "<table cellspacing=\"0\" cellpadding=\"3\">";
if ($taskDetail->tas_name[0] != "") {
echo "<tr><td>".$strings["name"]." :</td><td>".$taskDetail->tas_name[0]."</td></tr>";
}
if ($taskDetail->tas_description[0] != "") {
echo "<tr><td>".$strings["description"]." :</td><td>".nl2br($taskDetail->tas_description[0])."</td></tr>";
}
$complValue = ($taskDetail->tas_completion[0]>0) ? $taskDetail->tas_completion[0]."0 %": $taskDetail->tas_completion[0]." %"; 
echo "<tr><td>".$strings["completion"]." :</td><td>".$complValue."</td></tr>";
if ($taskDetail->tas_mem_name[0] != "") {
echo "<tr><td>".$strings["assigned_to"]." :</td><td>".$taskDetail->tas_mem_name[0]."</td></tr>";
}
if ($taskDetail->tas_comments[0] != "") {
echo "<tr><td>".$strings["comments"]." :</td><td>".nl2br($taskDetail->tas_comments[0])."</td></tr>";
}
if ($taskDetail->tas_start_date[0] != "") {
echo "<tr><td>".$strings["start_date"]." :</td><td>".$taskDetail->tas_start_date[0]."</td></tr>";
}
if ($taskDetail->tas_due_date[0] != "") {
echo "<tr><td>".$strings["due_date"]." :</td><td>".$taskDetail->tas_due_date[0]."</td></tr>";
}
echo "<tr><td>".$strings["updates_task"]." :</td><td>";
$tmpquery = "WHERE upd.type='1' AND upd.item = '$id' ORDER BY upd.created DESC";
$listUpdates = new request();
$listUpdates->openUpdates($tmpquery);
$comptListUpdates=count($listUpdates->upd_id);

if ($comptListUpdates != "0") {
$j = 1;
for ($i=0;$i<$comptListUpdates;$i++) {
	echo "<b>".$j.".</b> <i>".createDate($listUpdates->upd_created[$i],$timezoneSession)."</i><br>".nl2br($listUpdates->upd_comments[$i]);
	echo "<br>";
$j++;
}
} else {
echo $strings["no_items"];
}

echo "</td></tr>
</table>
<hr>";

$tmpquery = "WHERE subtas.task = '$id' AND subtas.published = '0' ORDER BY subtas.name";
$listSubtasks = new request();
$listSubtasks->openSubtasks($tmpquery);
$comptListSubtasks = count($listSubtasks->subtas_id);

$block2 = new block();

$block2->heading($strings["subtasks"]);

if ($comptListSubtasks != "0") {
echo "<table cellspacing=\"0\" width=\"90%\" border=\"0\" cellpadding=\"3\" cols=\"4\" class=\"listing\">
<tr><th class=\"active\">".$strings["name"]."</th><th>".$strings["description"]."</th><th>".$strings["status"]."</th><th>".$strings["due"]."</th></tr>";

for ($i=0;$i<$comptListSubtasks;$i++) {
	if (!($i%2)) {
		$class = "odd";
		$highlightOff = $block2->oddColor;
	} else {
		$class = "even";
		$highlightOff = $block2->evenColor;
	}
$idStatus = $listSubtasks->subtas_status[$i];
echo "<tr class=\"$class\" onmouseover=\"this.style.backgroundColor='".$block2->highlightOn."'\" onmouseout=\"this.style.backgroundColor='".$highlightOff."'\"><td><a href=\"clientsubtaskdetail.php?$transmitSid&task=$id&id=".$listSubtasks->subtas_id[$i]."\">".$listSubtasks->subtas_name[$i]."</a></td><td>".nl2br($listSubtasks->subtas_description[$i])."</td><td>$status[$idStatus]</td><td>".$listSubtasks->subtas_due_date[$i]."</td></tr>";
}
echo "</table>
<hr>\n";
} else {
echo "<table cellspacing=\"0\" border=\"0\" cellpadding=\"2\"><tr><td colspan=\"4\" class=\"listOddBold\">".$strings["no_items"]."</td></tr></table><hr>";
}

echo "<form accept-charset=\"UNKNOWN\" method=\"post\" action=\"../projects_site/clienttaskdetail.php?".session_name()."=".session_id()."&amp;action=update\" name=\"clientTaskUpdate\" enctype=\"multipart/form-data\"><input name=\"id\" type=\"HIDDEN\" value=\"$id\">";

echo "<table cellspacing=\"0\" cellpadding=\"3\">
<tr><th colspan=\"2\">".$strings["client_change_status"]."</th></tr>
<tr><td>".$strings["status"]." :</td><td>";

if ($taskDetail->tas_status[0] == "0") {
	echo "<input checked value=\"checkbox\" name=\"checkbox\" type=\"checkbox\">";
} else {
	echo "<input value=\"checkbox\" name=\"checkbox\" type=\"checkbox\">";
}

echo "&nbsp;$status[0]</td></tr>
<tr valign=\"top\"><td>".$strings["comments"]." :</td><td><textarea cols=\"40\" name=\"comments\" rows=\"5\">".$taskDetail->tas_comments[0]."</textarea></td></tr><tr align=\"top\"><td>&#160;</td><td><input name=\"submit\" type=\"submit\" value=\"".$strings["save"]."\"></td></tr>
</table>
</form>";

echo "<br><br>
<a href=\"showallclienttasks.php?$transmitSid\">".$strings["show_all"]."</a>";

include ("include_footer.php");
?>