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

include ("../includes/jpgraph/jpgraph.php");
include ("../includes/jpgraph/jpgraph_gantt.php");

$tmpquery = "WHERE id = '".$report."'";
$reportDetail = new request();
$reportDetail->openReports($tmpquery);
$S_ORGSEL = $reportDetail->rep_clients[0];
$S_PRJSEL = $reportDetail->rep_projects[0];
$S_ATSEL = $reportDetail->rep_members[0];
$S_STATSEL = $reportDetail->rep_status[0];
$S_PRIOSEL = $reportDetail->rep_priorities[0];
$S_SDATE = $reportDetail->rep_date_due_start[0];
$S_EDATE = $reportDetail->rep_date_due_end[0];
if ($S_SDATE == "" && $S_EDATE == "") {
	$S_DUEDATE = "ALL";
}

//echo "$S_PRJSEL + $S_ORGSEL + $S_ATSEL + $S_STATSEL + $S_PRIOSEL + $S_SDATE + $S_EDATE";

if ($S_ORGSEL != "ALL" || $S_PRJSEL != "ALL" || $S_ATSEL != "ALL" || $S_STATSEL != "ALL" || $S_PRIOSEL != "ALL" || $S_DUEDATE != "ALL") {
$queryStart = "WHERE (";
if ($S_PRJSEL != "ALL" && $S_PRJSEL != "") {
	$query = "tas.project IN($S_PRJSEL)";
}
if ($S_ORGSEL != "ALL" && $S_ORGSEL != "") {
	if ($query != "") {
		$query .= " AND org.id IN($S_ORGSEL)";
	} else {
		$query .= "org.id IN($S_ORGSEL)";
	}
}
if ($S_ATSEL != "ALL" && $S_ATSEL != "") {
	if ($query != "") {
		$query .= " AND tas.assigned_to IN($S_ATSEL)";
	} else {
		$query .= "tas.assigned_to IN($S_ATSEL)";
	}
}
if ($S_STATSEL != "ALL" && $S_STATSEL != "") {
	if ($query != "") {
		$query .= " AND tas.status IN($S_STATSEL)";
	} else {
		$query .= "tas.status IN($S_STATSEL)";
	}
}
if ($S_PRIOSEL != "ALL" && $S_PRIOSEL != "") {
	if ($query != "") {
		$query .= " AND tas.priority IN($S_PRIOSEL)";
	} else {
		$query .= "tas.priority IN($S_PRIOSEL)";
	}
}
if ($S_DUEDATE != "ALL" && $S_SDATE != "--") {
	if ($query != "") {
		$query .= " AND tas.due_date >= '$S_SDATE'";
	} else {
		$query .= "tas.due_date >= '$S_SDATE'";
	}
}
if ($S_DUEDATE != "ALL" && $S_EDATE != "--") {
	if ($query != "") {
		$query .= " AND tas.due_date <= '$S_EDATE'";
	} else {
		$query .= "tas.due_date <= '$S_EDATE'";

	}
}

$query .= ")";
}
$reportDetail->rep_created[0] = createDate($reportDetail->rep_created[0],$timezoneSession);

$graph = new GanttGraph();
$graph->SetBox();
$graph->SetMarginColor("white");
$graph->SetColor("white");
$graph->title->Set($strings["report"]." ".$reportDetail->rep_name[0]);
$graph->subtitle->Set("(".$strings["created"].": ".$reportDetail->rep_created[0].")");
$graph->title->SetFont(FF_FONT1);
$graph->SetColor("white");
$graph->ShowHeaders(GANTT_HYEAR | GANTT_HMONTH | GANTT_HDAY | GANTT_HWEEK);
$graph->scale->week->SetStyle(WEEKSTYLE_FIRSTDAY);
$graph->scale->week->SetFont(FF_FONT0);
$graph->scale->year->SetFont(FF_FONT1);

$tmpquery = "$queryStart $query ORDER BY tas.name";
$listTasks = new request();
$listTasks->openTasks($tmpquery);
$comptListTasks = count($listTasks->tas_id);

for ($i=0;$i<$comptListTasks;$i++) {
$listTasks->tas_name[$i] = str_replace('&quot;','"',$listTasks->tas_name[$i]);
$listTasks->tas_name[$i] = str_replace("&#39;","'",$listTasks->tas_name[$i]);
$listTasks->tas_pro_name[$i] = str_replace('&quot;','"',$listTasks->tas_pro_name[$i]);
$listTasks->tas_pro_name[$i] = str_replace("&#39;","'",$listTasks->tas_pro_name[$i]);
$progress = round($listTasks->tas_completion[$i]/10,2);
$printProgress = $listTasks->tas_completion[$i]*10;
$activity = new GanttBar($i,$listTasks->tas_pro_name[$i]." / ".$listTasks->tas_name[$i],$listTasks->tas_start_date[$i],$listTasks->tas_due_date[$i]);
//$activity = new GanttBar($i,$strings["project"].": ".$listTasks->tas_pro_name[$i]." / ".$strings["task"].": ".$listTasks->tas_name[$i],$listTasks->tas_start_date[$i],$listTasks->tas_due_date[$i]);
$activity->SetPattern(BAND_LDIAG,"yellow");
$activity->caption->Set($listTasks->tas_mem_login[$i]." (".$printProgress."%)");
$activity->SetFillColor("gray");
if ($listTasks->tas_priority[$i] == "4" || $listTasks->tas_priority[$i] == "5") {
	$activity->progress->SetPattern(BAND_SOLID,"#BB0000");
} else {
	$activity->progress->SetPattern(BAND_SOLID,"#0000BB");
}
$activity->progress->Set($progress);
$graph->Add($activity);
}

$graph->Stroke();
?>