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

include("../includes/notification.class.php");

//case add task
if ($id == "") {

//case add task
	if ($action == "add") {

//concat values from date selector and replace quotes by html code in name
		$tn = convertData($tn);
		$d = convertData($d);
		$c = convertData($c);

		$tmpquery1 = "INSERT INTO ".$tableCollab["tasks"]."(project,name,description,owner,assigned_to,status,priority,start_date,due_date,estimated_time,actual_time,comments,created,published,completion) VALUES('$projectSession','$tn','$d','$idSession','0','2','$pr','$sd','$dd','$etm','$atm','$c','$dateheure','$pub','0')";
		connectSql("$tmpquery1");
		$tmpquery = $tableCollab["tasks"];
		last_id($tmpquery);
		$num = $lastId[0];
		unset($lastId);

		$tmpquery2 = "INSERT INTO ".$tableCollab["assignments"]."(task,owner,assigned_to,assigned) VALUES('$num','$idSession','$at','$dateheure')";
		connectSql("$tmpquery2");

//send task assignment mail if notifications = true
					if ($notifications == "true") {
						$clientaddtask = new notification();
						$clientaddtask->taskNotification($owner,$num,"clientaddtask");
					}

//create task sub-folder if filemanagement = true
			if ($fileManagement == "true") {
				createDir("../files/$projectSession/$num");
			}
		headerFunction("showallteamtasks.php?".session_name()."=".session_id());
	}

}

$bodyCommand="onload=\"document.etDForm.tn.focus();\"";

$bouton[2] = "over";
$titlePage = $strings["add_task"];
include ("include_header.php");

echo "<form accept-charset=\"UNKNOWN\" method=\"POST\" action=\"../projects_site/addteamtask.php?project=$projectSession&amp;action=add&amp;".session_name()."=".session_id()."#etDAnchor\" name=\"etDForm\" enctype=\"application/x-www-form-urlencoded\">";

echo "<table cellpadding=\"3\" cellspacing=\"0\" border=\"0\">
<tr><th colspan=\"2\">".$strings["add_task"]."</th></tr>
<tr><th>*&nbsp;".$strings["name"]." :</th><td><input size=\"44\" value=\"$tn\" style=\"width: 400px\" name=\"tn\" maxlength=\"100\" type=\"TEXT\"></td></tr>
<tr><th>".$strings["description"]." :</th><td><textarea rows=\"10\" style=\"width: 400px; height: 160px;\" name=\"d\" cols=\"47\">$d</textarea></td></tr>

<input type=\"hidden\" name=\"owner\" value=\"".$projectDetail->pro_owner[0]."\">
<input type=\"hidden\" name=\"at\" value=\"0\">
<input type=\"hidden\" name=\"st\" value=\"2\">
<input type=\"hidden\" name=\"completion\" value=\"0\">
<input type=\"hidden\" value=\"1\" name=\"pub\">
<tr><th>".$strings["priority"]." :</th><td><select name=\"pr\">";

$comptPri = count($priority);

for ($i=0;$i<$comptPri;$i++) {
	if ($taskDetail->tas_priority[0] == $i) {
		echo "<option value=\"$i\" selected>$priority[$i]</option>";
	} else {
		echo "<option value=\"$i\">$priority[$i]</option>";
	}
}

echo "</select></td></tr>";

include("../includes/calendar.php");

if ($sd == "") {
	$sd = $date;
}
if ($dd == "") {
	$dd = "--";
}

echo "<tr><th>".$strings["start_date"]." :</th><td><input type=\"text\" name=\"sd\" id=\"sel1\" size=\"20\" value=\"$sd\"><input type=\"reset\" value=\" ... \" onclick=\"return showCalendar('sel1', 'y-mm-dd');\"></td></tr>

<tr><th>".$strings["due_date"]." :</th><td><input type=\"text\" name=\"dd\" id=\"sel3\" size=\"20\" value=\"$dd\"><input type=\"reset\" value=\" ... \" onclick=\"return showCalendar('sel3', 'y-mm-dd');\"></td></tr>

<tr><th>".$strings["comments"]." :</th><td><textarea rows=\"10\" style=\"width: 400px; height: 160px;\" name=\"c\" cols=\"47\">$c</textarea></td></tr>
<tr><th>&nbsp;</th><td><input type=\"SUBMIT\" value=\"".$strings["save"]."\"></td></tr>
</table>
</form>
<p class=\"note\">".$strings["client_add_task_note"]."</p>";

include ("include_footer.php");
?>