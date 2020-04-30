<?php
#Application name: PhpCollab
#Status page: 0
#Path by root: ../tasks/edittask.php

$checkSession = "true";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	$postnukeIntegration = "true";
	include("modules/PhpCollab/includes/library.php");
} else {
	$postnukeIntegration = "false";
	include("../includes/library.php");
}

if ($notifications == "true") {
if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/includes/notification.class.php");
} else {
	include("../includes/notification.class.php");
}
}

//case multiple edit tasks
$multi = strstr($id,"**");
if ($multi != "") {
	headerFunction("../tasks/updatetasks.php?report=$report&project=$project&id=$id&".session_name()."=".session_id());
	exit;
}

if ($id != "" && $action != "update" && $action != "add") {
	$tmpquery = "WHERE tas.id = '$id'";
	$taskDetail = new request();
	$taskDetail->openTasks($tmpquery);
	$tmpquery = "WHERE pro.id = '".$taskDetail->tas_project[0]."'";
	$project = $taskDetail->tas_project[0];
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

		if ($teamMember == "false" && $profilSession != "5") {
			headerFunction("../tasks/listtasks.php?project=$project&msg=taskOwner&".session_name()."=".session_id());
			exit;
		}

//case update or copy task
if ($id != "") {

//case update or copy task
if ($action == "update") {

//concat values from date selector and replace quotes by html code in name
	$tn = convertData($tn);
	$d = convertData($d);
	$c = convertData($c);

//case copy task
		if ($copy == "true") {

//Change task status if parent phase is suspended, complete or not open.			
			if ($projectDetail->pro_phase_set[0] != "0"){
				$tmpquery = "WHERE pha.project_id = '$project' AND pha.order_num = '$pha'";
				$currentPhase = new request();
				$currentPhase->openPhases($tmpquery);
				if ($st == 3 && $currentPhase->pha_status[0] != 1) {
					$st = 4;
				}
			}

if ($compl == "10") {
	$st = "1";
}
if ($pub == "") {
	$pub = "1";
}
//Insert Task details with or without parent phase			
			if ($projectDetail->pro_phase_set[0] != "0"){
				$tmpquery1 = "INSERT INTO ".$tableCollab["tasks"]."(project,name,description,owner,assigned_to,status,priority,start_date,due_date,estimated_time,actual_time,comments,created,published,completion,parent_phase) VALUES('$project','$tn','$d','$idSession','$at','$st','$pr','$sd','$dd','$etm','$atm','$c','$dateheure','$pub','$compl','$pha')";
			}else{
				$tmpquery1 = "INSERT INTO ".$tableCollab["tasks"]."(project,name,description,owner,assigned_to,status,priority,start_date,due_date,estimated_time,actual_time,comments,created,published,completion) VALUES('$project','$tn','$d','$idSession','$at','$st','$pr','$sd','$dd','$etm','$atm','$c','$dateheure','$pub','$compl')";			
			}
			connectSql("$tmpquery1");
			$tmpquery = $tableCollab["tasks"];
			last_id($tmpquery);
			$num = $lastId[0];
			unset($lastId);

		if ($st == "1" && $cd != "--") {
			$tmpquery6 = "UPDATE ".$tableCollab["tasks"]." SET complete_date='$date' WHERE id = '$num'";
			connectSql($tmpquery6);
		}

//if assigned_to not blank, set assigned date
				if ($at != "0") {
					$tmpquery6 = "UPDATE ".$tableCollab["tasks"]." SET assigned='$dateheure' WHERE id = '$num'";
					connectSql($tmpquery6);
				}
			$tmpquery2 = "INSERT INTO ".$tableCollab["assignments"]."(task,owner,assigned_to,assigned) VALUES('$num','$idSession','$at','$dateheure')";
			connectSql("$tmpquery2");

//if assigned_to not blank, add to team members (only if doesn't already exist)
				if ($at != "0") {
					$tmpquery = "WHERE tea.project = '$project' AND tea.member = '$at'";
					$testinTeam = new request();
					$testinTeam->openTeams($tmpquery);
					$comptTestinTeam = count($testinTeam->tea_id);
						if ($comptTestinTeam == "0") {
							$tmpquery3 = "INSERT INTO ".$tableCollab["teams"]."(project,member,published,authorized) VALUES('$project','$at','1','0')";
							connectSql("$tmpquery3");
						}
//send task assignment mail if notifications = true
						if ($notifications == "true") {
							$taskassignment = new notification();
							$taskassignment->taskNotification($at,$num,"taskassignment");
						}
				}

//create task sub-folder if filemanagement = true
				if ($fileManagement == "true") {
					createDir("files/$project/$num");
				}
			headerFunction("../tasks/viewtask.php?id=$num&msg=addAssignment&".session_name()."=".session_id());
			exit;


//case update task
		} else {

//Change task status if parent phase is suspended, complete or not open.			
			if ($projectDetail->pro_phase_set[0] != "0"){
				$tmpquery = "WHERE pha.project_id = '$project' AND pha.order_num = '$pha'";
				$currentPhase = new request();
				$currentPhase->openPhases($tmpquery);
				if ($st == 3 && $currentPhase->pha_status[0] != 1) {
					$st = 4;
				}
			}

if ($pub == "") {
	$pub = "1";
}
if ($compl == "10") {
	$st = "1";
}

//Update task with our without parent phase
			if ($projectDetail->pro_phase_set[0] != "0"){
				$tmpquery5 = "UPDATE ".$tableCollab["tasks"]." SET name='$tn',description='$d',assigned_to='$at',status='$st',priority='$pr',start_date='$sd',due_date='$dd',estimated_time='$etm',actual_time='$atm',comments='$c',modified='$dateheure',completion='$compl',parent_phase='$pha',published='$pub' WHERE id = '$id'";
			}else{		
				$tmpquery5 = "UPDATE ".$tableCollab["tasks"]." SET name='$tn',description='$d',assigned_to='$at',status='$st',priority='$pr',start_date='$sd',due_date='$dd',estimated_time='$etm',actual_time='$atm',comments='$c',modified='$dateheure',completion='$compl',published='$pub' WHERE id = '$id'";
			}	

		if ($st == "1" && $cd == "--") {
			$tmpquery6 = "UPDATE ".$tableCollab["tasks"]." SET complete_date='$date' WHERE id = '$id'";
			connectSql($tmpquery6);
		} else {
			$tmpquery6 = "UPDATE ".$tableCollab["tasks"]." SET complete_date='$cd' WHERE id = '$id'";
			connectSql($tmpquery6);
		}
		if ($old_st == "1" && $st != $old_st) {


			$tmpquery6 = "UPDATE ".$tableCollab["tasks"]." SET complete_date='' WHERE id = '$id'";
			connectSql($tmpquery6);
		}

//if project different from past value, set project number in tasks table
		if ($project != $old_project) {
			$tmpquery6 = "UPDATE ".$tableCollab["tasks"]." SET project='$project' WHERE id = '$id'";
			connectSql($tmpquery6);
			$tmpquery7 = "UPDATE ".$tableCollab["files"]." SET project='$project' WHERE task = '$id'";
			connectSql($tmpquery7);
			createDir("files/$project/$id");
$dir = opendir("../files/$old_project/$id");
if (is_resource($dir)) {
	while($v = readdir($dir)) {
		if ($v != '.' && $v != '..') {
			copy("../files/$old_project/$id/".$v,"../files/$project/$id/".$v);
			@unlink("../files/$old_project/$id/".$v);
		}
	}
}

		}

//if assigned_to not blank and past assigned value blank, set assigned date
				if ($at != "0" && $old_assigned == "") {
					$tmpquery6 = "UPDATE ".$tableCollab["tasks"]." SET assigned='$dateheure' WHERE id = '$id'";
					connectSql($tmpquery6);
				}

//if assigned_to different from past value, insert into assignment
//add new assigned_to in team members (only if doesn't already exist)
				if ($at != $old_at) {
					$tmpquery2 = "INSERT INTO ".$tableCollab["assignments"]."(task,owner,assigned_to,assigned) VALUES('$id','$idSession','$at','$dateheure')";
					connectSql("$tmpquery2");
					$tmpquery = "WHERE tea.project = '$project' AND tea.member = '$at'";
					$testinTeam = new request();
					$testinTeam->openTeams($tmpquery);
					$comptTestinTeam = count($testinTeam->tea_id);
						if ($comptTestinTeam == "0") {
							$tmpquery3 = "INSERT INTO ".$tableCollab["teams"]."(project,member,published,authorized) VALUES('$project','$at','1','0')";
							connectSql("$tmpquery3");
						}
					$msg = "updateAssignment";
					connectSql("$tmpquery5");
					$tmpquery = "WHERE tas.id = '$id'";
					$taskDetail = new request();
					$taskDetail->openTasks($tmpquery);

//send task assignment mail if notifications = true
						if ($notifications == "true") {
							$taskassignment = new notification();


							$taskassignment->taskNotification($at,$id,"taskassignment");
						}
				} else {
					$msg = "update";
					connectSql("$tmpquery5");
					$tmpquery = "WHERE tas.id = '$id'";
					$taskDetail = new request();
					$taskDetail->openTasks($tmpquery);

//send status task change mail if notifications = true
					if ($at != "0" && $st != $old_st) {
						if ($notifications == "true") {
							$statustaskchange = new notification();

							$statustaskchange->taskNotification($at,$id,"statustaskchange");
						}
					}

//send priority task change mail if notifications = true
					if ($at != "0" && $pr != $old_pr) {
						if ($notifications == "true") {
							$prioritytaskchange = new notification();
							$prioritytaskchange->taskNotification($at,$id,"prioritytaskchange");
						}
					}

//send due date task change mail if notifications = true
					if ($at != "0" && $dd != $old_dd) {
						if ($notifications == "true") {
							$duedatetaskchange = new notification();
							$duedatetaskchange->taskNotification($at,$id,"duedatetaskchange");
						}
					}
				}

			if ($cUp != "") {
				$cUp = convertData($cUp);
				$tmpquery6 = "INSERT INTO ".$tableCollab["updates"]."(type,item,member,comments,created) VALUES ('1','$id','$idSession','$cUp','$dateheure')";
				connectSql($tmpquery6);
			}
			headerFunction("../tasks/viewtask.php?id=$id&msg=$msg&".session_name()."=".session_id());
		}
}

//set value in form
$tn = $taskDetail->tas_name[0];
$d = $taskDetail->tas_description[0];
$sd = $taskDetail->tas_start_date[0];
$dd = $taskDetail->tas_due_date[0];
$cd = $taskDetail->tas_complete_date[0];
$etm = $taskDetail->tas_estimated_time[0];
$atm = $taskDetail->tas_actual_time[0];
$c = $taskDetail->tas_comments[0];
$pub = $taskDetail->tas_published[0];
	if ($pub == "0") {
		$checkedPub = "checked";
	}
}

//case add task
if ($id == "") {

//case add task
	if ($action == "add") {

//concat values from date selector and replace quotes by html code in name
		$tn = convertData($tn);
		$d = convertData($d);
		$c = convertData($c);

//Change task status if parent phase is suspended, complete or not open.	
		if ($projectDetail->pro_enable_phase[0] == "1"){
			$tmpquery = "WHERE pha.project_id = '$project' AND pha.order_num = '$pha'";
			$currentPhase = new request();
			$currentPhase->openPhases($tmpquery);
			if ($st == 3 && $currentPhase->pha_status[0] != 1) {
				$st = 4;
			}
		}

if ($compl == "10") {
	$st = "1";
}
if ($pub == "") {
	$pub = "1";
}

//Insert task with our without parent phase
		if ($projectDetail->pro_phase_set[0] != "0") {
			$tmpquery1 = "INSERT INTO ".$tableCollab["tasks"]."(project,name,description,owner,assigned_to,status,priority,start_date,due_date,estimated_time,actual_time,comments,created,published,completion,parent_phase) VALUES('$project','$tn','$d','$idSession','$at','$st','$pr','$sd','$dd','$etm','$atm','$c','$dateheure','$pub','$compl','$pha')";
		} else{
			$tmpquery1 = "INSERT INTO ".$tableCollab["tasks"]."(project,name,description,owner,assigned_to,status,priority,start_date,due_date,estimated_time,actual_time,comments,created,published,completion) VALUES('$project','$tn','$d','$idSession','$at','$st','$pr','$sd','$dd','$etm','$atm','$c','$dateheure','$pub','$compl')";
		}
		connectSql("$tmpquery1");
		$tmpquery = $tableCollab["tasks"];
		last_id($tmpquery);
		$num = $lastId[0];
		unset($lastId);

		if ($st == "1") {
			$tmpquery6 = "UPDATE ".$tableCollab["tasks"]." SET complete_date='$date' WHERE id = '$num'";
			connectSql($tmpquery6);
		}

//if assigned_to not blank, set assigned date
			if ($at != "0") {
				$tmpquery6 = "UPDATE ".$tableCollab["tasks"]." SET assigned='$dateheure' WHERE id = '$num'";
				connectSql($tmpquery6);
			}
		$tmpquery2 = "INSERT INTO ".$tableCollab["assignments"]."(task,owner,assigned_to,assigned) VALUES('$num','$idSession','$at','$dateheure')";
		connectSql($tmpquery2);

//if assigned_to not blank, add to team members (only if doesn't already exist)
//add assigned_to in team members (only if doesn't already exist)
			if ($at != "0") {
				$tmpquery = "WHERE tea.project = '$project' AND tea.member = '$at'";
				$testinTeam = new request();
				$testinTeam->openTeams($tmpquery);
				$comptTestinTeam = count($testinTeam->tea_id);
					if ($comptTestinTeam == "0") {
						$tmpquery3 = "INSERT INTO ".$tableCollab["teams"]."(project,member,published,authorized) VALUES('$project','$at','1','0')";
						connectSql($tmpquery3);
					}
				$tmpquery = "WHERE tas.id = '$num'";
				$taskDetail = new request();
				$taskDetail->openTasks($tmpquery);

//send task assignment mail if notifications = true
					if ($notifications == "true") {
						$taskassignment = new notification();
						$taskassignment->taskNotification($at,$num,"taskassignment");
					}
			}

//create task sub-folder if filemanagement = true
			if ($fileManagement == "true") {
				createDir("files/$project/$num");
			}
		headerFunction("../tasks/viewtask.php?id=$num&msg=addAssignment&".session_name()."=".session_id());
	}

//set default values
$taskDetail->tas_assigned_to[0] = "0";
$taskDetail->tas_priority[0] = $projectDetail->pro_priority[0];
$taskDetail->tas_status[0] = "2";
}

if ($projectDetail->pro_org_id[0] == "1") {
	$projectDetail->pro_org_name[0] = $strings["none"];
}

if ($projectDetail->pro_phase_set[0] != "0"){
	if ($id != "") {
		$tPhase = $taskDetail->tas_parent_phase[0];
		$tmpquery = "WHERE pha.project_id = '".$taskDetail->tas_project[0]."' AND pha.order_num = '$tPhase'";
	}
	if ($id == "") {
		$tPhase = $phase;
		$tmpquery = "WHERE pha.project_id = '$project' AND pha.order_num = '$tPhase'";
	}
	$targetPhase = new request();
	$targetPhase->openPhases($tmpquery);
}

$bodyCommand="onload=\"document.etDForm.compl.value = document.etDForm.completion.selectedIndex;document.etDForm.tn.focus();\"";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/header.php");
} else {
	include("../themes/".THEME."/header.php");
}

$blockPage = new block();
$blockPage->openBreadcrumbs();
$blockPage->itemBreadcrumbs($blockPage->buildLink("../projects/listprojects.php?",$strings["projects"],in));
$blockPage->itemBreadcrumbs($blockPage->buildLink("../projects/viewproject.php?id=".$projectDetail->pro_id[0],$projectDetail->pro_name[0],in));

if ($projectDetail->pro_phase_set[0] != "0"){
	$blockPage->itemBreadcrumbs($blockPage->buildLink("../phases/listphases.php?id=".$projectDetail->pro_id[0],$strings["phases"],in));
	$blockPage->itemBreadcrumbs($blockPage->buildLink("../phases/viewphase.php?id=".$targetPhase->pha_id[0],$targetPhase->pha_name[0],in));
}
$blockPage->itemBreadcrumbs($blockPage->buildLink("../tasks/listtasks.php?project=".$projectDetail->pro_id[0],$strings["tasks"],in));

if ($id == "") {
	$blockPage->itemBreadcrumbs($strings["add_task"]);
}
if ($id != "") {
	$blockPage->itemBreadcrumbs($blockPage->buildLink("../tasks/viewtask.php?id=".$taskDetail->tas_id[0],$taskDetail->tas_name[0],in));
	$blockPage->itemBreadcrumbs($strings["edit_task"]);
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
	$block1->openForm("../tasks/edittask.php?project=$project&amp;action=add&amp;".session_name()."=".session_id()."#".$block1->form."Anchor");
}
if ($id != "") {
	$block1->form = "etD";
	$block1->openForm("../tasks/edittask.php?project=$project&amp;id=$id&amp;action=update&amp;copy=$copy&amp;".session_name()."=".session_id()."#".$block1->form."Anchor");
	echo "<input type=\"hidden\" name=\"old_at\" value=\"".$taskDetail->tas_assigned_to[0]."\"><input type=\"hidden\" name=\"old_assigned\" value=\"".$taskDetail->tas_assigned[0]."\"><input type=\"hidden\" name=\"old_pr\" value=\"".$taskDetail->tas_priority[0]."\"><input type=\"hidden\" name=\"old_st\" value=\"".$taskDetail->tas_status[0]."\"><input type=\"hidden\" name=\"old_dd\" value=\"".$taskDetail->tas_due_date[0]."\"><input type=\"hidden\" name=\"old_project\" value=\"".$taskDetail->tas_project[0]."\">";
}

if ($error != "") {            
	$block1->headingError($strings["errors"]);
	$block1->contentError($error);
}

if ($id == "") {
	$block1->heading($strings["add_task"]);
}
if ($id != "") {

	if ($copy == "true") {
		$block1->heading($strings["copy_task"]." : ".$taskDetail->tas_name[0]);

	} else {
		$block1->heading($strings["edit_task"]." : ".$taskDetail->tas_name[0]);
	}
}

$block1->openContent();
$block1->contentTitle($strings["info"]);

echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["project"]." :</td><td><select name=\"project\">";

if ($projectsFilter == "true") {
	$tmpquery = "LEFT OUTER JOIN ".$tableCollab["teams"]." teams ON teams.project = pro.id ";
	$tmpquery .= "WHERE teams.member = '$idSession'";
} else {
	$tmpquery = "";
}
$listProjects = new request();
$listProjects->openProjects($tmpquery);
$comptListProjects = count($listProjects->pro_id);

for ($i=0;$i<$comptListProjects;$i++) {
        if($listProjects->pro_id[$i] == $projectDetail->pro_id[0])  {
                echo "<option value=\"".$listProjects->pro_id[$i]."\" selected>".$listProjects->pro_name[$i]."</option>";
        } else {
                echo "<option value=\"".$listProjects->pro_id[$i]."\">".$listProjects->pro_name[$i]."</option>";
        }
}
echo "</select></td></tr>";

//Display task's phase
if ($projectDetail->pro_phase_set[0] != "0"){
	echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["phase"]." :</td><td>".$blockPage->buildLink("../phases/viewphase.php?id=".$targetPhase->pha_id[0],$targetPhase->pha_name[0],in)."</td></tr>";
}
echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["organization"]." :</td><td>".$projectDetail->pro_org_name[0]."</td></tr>";

$block1->contentTitle($strings["details"]);

echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["name"]." :</td><td><input size=\"44\" value=\"";

if ($copy == "true") {
	echo $strings["copy_of"];
}

echo "$tn\" style=\"width: 400px\" name=\"tn\" maxlength=\"100\" type=\"TEXT\"></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["description"]." :</td><td><textarea rows=\"10\" style=\"width: 400px; height: 160px;\" name=\"d\" cols=\"47\">$d</textarea></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["assigned_to"]." :</td><td><select name=\"at\">";

if ($taskDetail->tas_assigned_to[0] == "0") {
	echo "<option value=\"0\" selected>".$strings["unassigned"]."</option>";
} else {
	echo "<option value=\"0\">".$strings["unassigned"]."</option>";
}

$tmpquery = "WHERE tea.project = '$project' ORDER BY mem.name";
$assignto = new request();
$assignto->openTeams($tmpquery);
$comptAssignto = count($assignto->tea_mem_id);

for ($i=0;$i<$comptAssignto;$i++) {
$clientUser = "";
if ($assignto->tea_mem_profil[$i] == "3") {
	$clientUser = " (".$strings["client_user"].")";
}
	if ($taskDetail->tas_assigned_to[0] == $assignto->tea_mem_id[$i]) {
		echo "<option value=\"".$assignto->tea_mem_id[$i]."\" selected>".$assignto->tea_mem_login[$i]." / ".$assignto->tea_mem_name[$i]."$clientUser</option>";
	} else {
		echo "<option value=\"".$assignto->tea_mem_id[$i]."\">".$assignto->tea_mem_login[$i]." / ".$assignto->tea_mem_name[$i]."$clientUser</option>";
	}
}

echo "</select></td></tr>";

//Select phase
if ($projectDetail->pro_phase_set[0] != "0"){
echo"<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["phase"]." :</td><td><select name=\"pha\">";

$projectTarget = $projectDetail->pro_id[0];
$tmpquery = "WHERE pha.project_id = '$projectTarget' ORDER BY pha.order_num";
$projectPhaseList = new request();
$projectPhaseList->openPhases($tmpquery);

$comptlistPhase = count($projectPhaseList->pha_id);
for ($i=0;$i<$comptlistPhase;$i++) {
	 $phaseNum = $projectPhaseList->pha_order_num[$i];
		if ($taskDetail->tas_parent_phase[0] == $phaseNum || $phase == $phaseNum){
			echo "<option value=\"$phaseNum\" selected>".$projectPhaseList->pha_name[$i]."</option>";
		} else {
	 		echo "<option value=\"$phaseNum\">".$projectPhaseList->pha_name[$i]."</option>";

		}
}
echo "</select></td></tr>";
}

echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["status"]." :</td><td><select name=\"st\" onchange=\"changeSt(this)\">";

$comptSta = count($status);

for ($i=0;$i<$comptSta;$i++) {
	if ($taskDetail->tas_status[0] == $i) {
		echo "<option value=\"$i\" selected>$status[$i]</option>";
	} else {
		echo "<option value=\"$i\">$status[$i]</option>";
	}
}

echo "</select></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["completion"]." :</td><td><input name=\"compl\" type=\"hidden\" value=\"".$taskDetail->tas_completion[0]."\"><select name=\"completion\" onchange=\"changeCompletion(this)\">";

for ($i=0;$i<11;$i++) {
	$complValue = ($i>0) ? $i."0 %": $i." %"; 
	if ($taskDetail->tas_completion[0] == $i) {
		echo "<option value=\"".$i."\" selected>".$complValue."</option>";
	} else {
		echo "<option value=\"".$i."\">".$complValue."</option>";
	}
}

echo "</select></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["priority"]." :</td><td><select name=\"pr\">";

$comptPri = count($priority);

for ($i=0;$i<$comptPri;$i++) {
	if ($taskDetail->tas_priority[0] == $i) {
		echo "<option value=\"$i\" selected>$priority[$i]</option>";
	} else {
		echo "<option value=\"$i\">$priority[$i]</option>";
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
if ($dd == "") {
	$dd = "--";
}
if ($cd == "") {
	$cd = "--";
}

$block1->contentRow($strings["start_date"],"<input type=\"text\" name=\"sd\" id=\"sel1\" size=\"20\" value=\"$sd\"><input type=\"reset\" value=\" ... \" onclick=\"return showCalendar('sel1', 'y-mm-dd');\">");

$block1->contentRow($strings["due_date"],"<input type=\"text\" name=\"dd\" id=\"sel3\" size=\"20\" value=\"$dd\"><input type=\"reset\" value=\" ... \" onclick=\"return showCalendar('sel3', 'y-mm-dd');\">");

if ($id != "") {
$block1->contentRow($strings["complete_date"],"<input type=\"text\" name=\"cd\" id=\"sel5\" size=\"20\" value=\"$cd\"><input type=\"reset\" value=\" ... \" onclick=\"return showCalendar('sel5', 'y-mm-dd');\">");
}

echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["estimated_time"]." :</td><td><input size=\"32\" value=\"$etm\" style=\"width: 250px\" name=\"etm\" maxlength=\"32\" type=\"TEXT\">&nbsp;".$strings["hours"]."</td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["actual_time"]." :</td><td><input size=\"32\" value=\"$atm\" style=\"width: 250px\" name=\"atm\" maxlength=\"32\" type=\"TEXT\">&nbsp;".$strings["hours"]."</td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["comments"]." :</td><td><textarea rows=\"10\" style=\"width: 400px; height: 160px;\" name=\"c\" cols=\"47\">$c</textarea></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["published"]." :</td><td><input size=\"32\" value=\"0\" name=\"pub\" type=\"checkbox\" $checkedPub></td></tr>";

if ($id != "") {
$block1->contentTitle($strings["updates_task"]);
echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["comments"]." :</td><td><textarea rows=\"10\" style=\"width: 400px; height: 160px;\" name=\"cUp\" cols=\"47\"></textarea></td></tr>";
}

echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">&nbsp;</td><td><input type=\"SUBMIT\" value=\"".$strings["save"]."\"></td></tr>";

$block1->closeContent();
$block1->closeForm();

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>
<script>
function changeSt(theObj, firstRun){
	if (theObj.selectedIndex==3) {

		if (firstRun!=true) document.etDForm.completion.selectedIndex=0;
		document.etDForm.compl.value=0;
		document.etDForm.completion.disabled=false;
	} else {
		if (theObj.selectedIndex==0 || theObj.selectedIndex==1) {
			document.etDForm.completion.selectedIndex=10;

			document.etDForm.compl.value=10;


		} else {
			document.etDForm.completion.selectedIndex=0;
			document.etDForm.compl.value=0;
		}
		document.etDForm.completion.disabled=true;
	}
}

function changeCompletion(){
	document.etDForm.compl.value = document.etDForm.completion.selectedIndex;
}

changeSt(document.etDForm.st, true);
</script>