<?php
#Application name: PhpCollab
#Status page: 0
#Path by root: ../subtasks/deletesubtasks.php

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
	$tmpquery1 = "DELETE FROM ".$tableCollab["subtasks"]." WHERE id IN($id)";
	$tmpquery2 = "DELETE FROM ".$tableCollab["assignments"]." WHERE subtask IN($id)";

	/*
	$tmpquery = "WHERE tas.id IN($id)";
	$listTasks = new request();
	$listTasks->openTasks($tmpquery);
	$comptListTasks = count($listTasks->tas_id);
		for ($i=0;$i<$comptListTasks;$i++) {
			if ($fileManagement == "true") {
				delDir("../files/".$listTasks->tas_project[$i]."/".$listTasks->tas_id[$i]);
			}
		}
	*/
	connectSql($tmpquery1);
	connectSql($tmpquery2);
	if ($task != "") {	
		headerFunction("../tasks/viewtask.php?id=$task&msg=delete&".session_name()."=".session_id());
		exit;
	} else {
		headerFunction("../general/home.php?msg=delete&".session_name()."=".session_id());
		exit;
	}
}

$tmpquery = "WHERE tas.id = '$task'";
$taskDetail = new request();
$taskDetail->openTasks($tmpquery);
$project = $taskDetail->tas_project[0];

$tmpquery = "WHERE pro.id = '$project'";
$projectDetail = new request();
$projectDetail->openProjects($tmpquery);

if ($projectDetail->pro_enable_phase[0] != "0"){
	$tPhase = $taskDetail->tas_parent_phase[0];
	$tmpquery = "WHERE pha.project_id = '".$taskDetail->tas_project[0]."' AND pha.order_num = '$tPhase'";
	$targetPhase = new request();
	$targetPhase->openPhases($tmpquery);
}	

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/header.php");
} else {
	include("../themes/".THEME."/header.php");
}

$blockPage = new block();
$blockPage->openBreadcrumbs();
if ($task != "") {	
$blockPage->itemBreadcrumbs($blockPage->buildLink("../projects/listprojects.php?",$strings["projects"],in));
$blockPage->itemBreadcrumbs($blockPage->buildLink("../projects/viewproject.php?id=".$projectDetail->pro_id[0],$projectDetail->pro_name[0],in));

if ($projectDetail->pro_phase_set[0] != "0"){
	$blockPage->itemBreadcrumbs($blockPage->buildLink("../phases/listphases.php?id=".$projectDetail->pro_id[0],$strings["phases"],in));
	$blockPage->itemBreadcrumbs($blockPage->buildLink("../phases/viewphase.php?id=".$targetPhase->pha_id[0],$targetPhase->pha_name[0],in));
}

$blockPage->itemBreadcrumbs($blockPage->buildLink("../tasks/listtasks.php?project=".$projectDetail->pro_id[0],$strings["tasks"],in));
$blockPage->itemBreadcrumbs($blockPage->buildLink("../tasks/viewtask.php?id=".$taskDetail->tas_id[0],$taskDetail->tas_name[0],in));
$blockPage->itemBreadcrumbs($strings["delete_subtasks"]);
} else {
$blockPage->itemBreadcrumbs($blockPage->buildLink("../general/home.php?",$strings["home"],in));
$blockPage->itemBreadcrumbs($strings["my_tasks"]);
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

$block1->form = "saP";
$block1->openForm("../subtasks/deletesubtasks.php?task=$task&amp;action=delete&amp;id=$id&amp;".session_name()."=".session_id());

$block1->heading($strings["delete_subtasks"]);

$block1->openContent();
$block1->contentTitle($strings["delete_following"]);

$id = str_replace("**",",",$id);
$tmpquery = "WHERE subtas.id IN($id) ORDER BY subtas.name";
$listSubtasks = new request();
$listSubtasks->openSubtasks($tmpquery);
$comptListSubtasks = count($listSubtasks->subtas_id);

for ($i=0;$i<$comptListSubtasks;$i++) {
echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">#".$listSubtasks->subtas_id[$i]."</td><td>".$listSubtasks->subtas_name[$i]."</td></tr>";
}

echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">&nbsp;</td><td><input type=\"submit\" name=\"delete\" 
value=\"".$strings["delete"]."\"> <input type=\"button\" name=\"cancel\" value=\"".$strings["cancel"]."\" 
onClick=\"history.back();\"></td></tr>";

$block1->closeContent();
$block1->closeForm();

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>
