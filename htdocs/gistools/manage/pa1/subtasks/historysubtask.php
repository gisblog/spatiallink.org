<?php
#Application name: PhpCollab
#Status page: 0
#Path by root: ../topics/viewtopic.php

$checkSession = "true";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	$postnukeIntegration = "true";
	include("modules/PhpCollab/includes/library.php");
} else {
	$postnukeIntegration = "false";
	include("../includes/library.php");
}

if ($type == "2") {
$tmpquery = "WHERE subtas.id = '$item'";
$subtaskDetail = new request();
$subtaskDetail->openSubtasks($tmpquery);

$tmpquery = "WHERE tas.id = '".$subtaskDetail->subtas_task[0]."'";
$taskDetail = new request();
$taskDetail->openTasks($tmpquery);

$tmpquery = "WHERE pro.id = '".$taskDetail->tas_project[0]."'";
$projectDetail = new request();
$projectDetail->openProjects($tmpquery);

if ($projectDetail->pro_enable_phase[0] != "0"){
	$tPhase = $taskDetail->tas_parent_phase[0];
	$tmpquery = "WHERE pha.project_id = '".$taskDetail->tas_project[0]."' AND pha.order_num = '$tPhase'";
	$targetPhase = new request();
	$targetPhase->openPhases($tmpquery);
}
}

if ($type == "1") {
$tmpquery = "WHERE tas.id = '$item'";
$taskDetail = new request();
$taskDetail->openTasks($tmpquery);

$tmpquery = "WHERE pro.id = '".$taskDetail->tas_project[0]."'";
$projectDetail = new request();
$projectDetail->openProjects($tmpquery);

if ($projectDetail->pro_enable_phase[0] != "0"){
	$tPhase = $taskDetail->tas_parent_phase[0];
	$tmpquery = "WHERE pha.project_id = '".$taskDetail->tas_project[0]."' AND pha.order_num = '$tPhase'";
	$targetPhase = new request();
	$targetPhase->openPhases($tmpquery);
}
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

if ($projectDetail->pro_phase_set[0] != "0"){
	$blockPage->itemBreadcrumbs($blockPage->buildLink("../phases/listphases.php?id=".$projectDetail->pro_id[0],$strings["phases"],in));
	$blockPage->itemBreadcrumbs($blockPage->buildLink("../phases/viewphase.php?id=".$targetPhase->pha_id[0],$targetPhase->pha_name[0],in));
}

$blockPage->itemBreadcrumbs($blockPage->buildLink("../tasks/listtasks.php?project=".$projectDetail->pro_id[0],$strings["tasks"],in));
$blockPage->itemBreadcrumbs($blockPage->buildLink("../tasks/viewtask.php?id=".$taskDetail->tas_id[0],$taskDetail->tas_name[0],in));

if ($type == "2") {
$blockPage->itemBreadcrumbs($blockPage->buildLink("../subtasks/viewsubtask.php?task=".$taskDetail->tas_id[0]."&amp;id=".$subtaskDetail->subtas_id[0],$subtaskDetail->subtas_name[0],in));
$blockPage->itemBreadcrumbs($strings["updates_subtask"]);
}

if ($type == "1") {
$blockPage->itemBreadcrumbs($strings["updates_task"]);
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

$block1->form = "tdP";
$block1->openForm("");

if ($error != "") {            
	$block1->headingError($strings["errors"]);
	$block1->contentError($error);
}

if ($type == "1") {
$block1->heading($strings["task"]." : ".$taskDetail->tas_name[0]);
}
if ($type == "2") {
$block1->heading($strings["subtask"]." : ".$subtaskDetail->subtas_name[0]);
}

$block1->openContent();
$block1->contentTitle($strings["details"]);

$tmpquery = "WHERE upd.type='$type' AND upd.item = '$item' ORDER BY upd.created DESC";
$listUpdates = new request();
$listUpdates->openUpdates($tmpquery);
$comptListUpdates=count($listUpdates->upd_id);

for ($i=0;$i<$comptListUpdates;$i++) {
$block1->contentRow($strings["posted_by"],$blockPage->buildLink($listUpdates->upd_mem_email_work[$i],$listUpdates->upd_mem_name[$i],mail));
if ($listUpdates->upd_created[$i] > $lastvisiteSession) {
	$block1->contentRow($strings["when"],"<b>".createDate($listUpdates->upd_created[$i],$timezoneSession)."</b>");
} else {
	$block1->contentRow($strings["when"],createDate($listUpdates->upd_created[$i],$timezoneSession));
}
$block1->contentRow("",nl2br($listUpdates->upd_comments[$i]));
$block1->contentRow("","","true");
}

$block1->closeContent();

$block1->closeForm();

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>