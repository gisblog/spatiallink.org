<?php
#Application name: PhpCollab
#Status page: 0
#Path by root: ../reports/resultsreport.php

$checkSession = "true";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	$postnukeIntegration = "true";
	include("modules/PhpCollab/includes/library.php");
} else {
	$postnukeIntegration = "false";
	include("../includes/library.php");
}

	if ($action == "add") {
		$S_SAVENAME = convertData($S_SAVENAME);
		$tmpquery1 = "INSERT INTO ".$tableCollab["reports"]."(owner,name,projects,clients,members,priorities,status,date_due_start,date_due_end,date_complete_start,date_complete_end,created) VALUES('$idSession','$S_SAVENAME','$S_PRJSEL','$S_ORGSEL','$S_ATSEL','$S_PRIOSEL','$S_STATSEL','$S_SDATE','$S_EDATE','$S_SDATE2','$S_EDATE2','$dateheure')";
		connectSql("$tmpquery1");
		headerFunction("../general/home.php?msg=addReport&".session_name()."=".session_id());
	}

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/header.php");
} else {
	include("../themes/".THEME."/header.php");
}

if ($id == "" && $tri != "true") {
$compt1 = count($S_PRJSEL);
$S_pro = "";
	for($i=0; $i<$compt1; $i++) {
		if($S_PRJSEL[$i] == "ALL") {
		$S_pro = "ALL";
		break;
		}
		if ($i != $compt1-1) {
		$S_pro .= $S_PRJSEL[$i].",";
		} else {
		$S_pro .= $S_PRJSEL[$i];
		}
	}
$compt2 = count($S_ATSEL);
$S_mem = "";
	for($i=0; $i<$compt2; $i++) {
		if($S_ATSEL[$i] == "ALL") {
		$S_mem = "ALL";
		break;
		}
		if ($i != $compt2-1) {
		$S_mem .= $S_ATSEL[$i].",";
		} else {
		$S_mem .= $S_ATSEL[$i];
		}
	}
$compt3 = count($S_STATSEL);
$S_sta = "";
	for($i=0; $i<$compt3; $i++) {
		if($S_STATSEL[$i] == "ALL") {
		$S_sta = "ALL";
		break;
		}
		if ($i != $compt3-1) {
		$S_sta .= $S_STATSEL[$i].",";
		} else {
		$S_sta .= $S_STATSEL[$i];
		}
	}
$compt4 = count($S_PRIOSEL);
$S_pri = "";
	for($i=0; $i<$compt4; $i++) {
		if($S_PRIOSEL[$i] == "ALL") {
		$S_pri = "ALL";
		break;
		}
		if ($i != $compt4-1) {
		$S_pri .= $S_PRIOSEL[$i].",";
		} else {
		$S_pri .= $S_PRIOSEL[$i];
		}
	}
$compt5 = count($S_ORGSEL);
$S_org = "";
	for($i=0; $i<$compt5; $i++) {
		if($S_ORGSEL[$i] == "ALL") {
		$S_org = "ALL";
		break;
		}
		if ($i != $compt5-1) {
		$S_org .= $S_ORGSEL[$i].",";
		} else {
		$S_org .= $S_ORGSEL[$i];
		}
	}

//echo "$S_pro - $S_org - $S_mem - $S_sta - $S_pri";

$S_ORGSEL = $S_org;
$S_PRJSEL = $S_pro;
$S_ATSEL = $S_mem;

$S_STATSEL = $S_sta;
$S_PRIOSEL = $S_pri;
}

if ($id != "") {
$tmpquery = "WHERE id = '$id'";
$reportDetail = new request();
$reportDetail->openReports($tmpquery);
$S_ORGSEL = $reportDetail->rep_clients[0];
$S_PRJSEL = $reportDetail->rep_projects[0];
$S_ATSEL = $reportDetail->rep_members[0];
$S_STATSEL = $reportDetail->rep_status[0];
$S_PRIOSEL = $reportDetail->rep_priorities[0];
$S_SDATE = $reportDetail->rep_date_due_start[0];
$S_EDATE = $reportDetail->rep_date_due_end[0];
$S_SDATE2 = $reportDetail->rep_date_complete_start[0];
$S_EDATE2 = $reportDetail->rep_date_complete_end[0];
if ($S_SDATE == "" && $S_EDATE == "") {
	$S_DUEDATE = "ALL";
}
if ($S_SDATE2 == "" && $S_EDATE2 == "") {
	$S_COMPLETEDATE = "ALL";
}
}

//echo "$S_PRJSEL + $S_ORGSEL + $S_ATSEL + $S_STATSEL + $S_PRIOSEL + $S_SDATE + $S_EDATE + $S_SDATE2 + $S_EDATE2";

if ($S_PRJSEL != "ALL" || $S_ORGSEL != "ALL" || $S_ATSEL != "ALL" || $S_STATSEL != "ALL" || $S_PRIOSEL != "ALL" || $S_DUEDATE != "ALL" || $S_COMPLETEDATE != "ALL") {
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
if ($S_DUEDATE != "ALL" && $S_SDATE != "") {
	if ($query != "") {
		$query .= " AND tas.due_date >= '$S_SDATE'";
	} else {
		$query .= "tas.due_date >= '$S_SDATE'";
	}
}
if ($S_DUEDATE != "ALL" && $S_EDATE != "") {
	if ($query != "") {
		$query .= " AND tas.due_date <= '$S_EDATE'";
	} else {
		$query .= "tas.due_date <= '$S_EDATE'";
	}
}
if ($S_COMPLETEDATE != "ALL" && $S_SDATE2 != "") {
	if ($query != "") {
		$query .= " AND tas.complete_date >= '$S_SDATE2'";
	} else {
		$query .= "tas.complete_date >= '$S_SDATE2'";
	}
}
if ($S_COMPLETEDATE != "ALL" && $S_EDATE2 != "") {
	if ($query != "") {
		$query .= " AND tas.complete_date <= '$S_EDATE2'";
	} else {
		$query .= "tas.complete_date <= '$S_EDATE2'";
	}
}
$query .= ")";
}

$blockPage = new block();
$blockPage->openBreadcrumbs();
$blockPage->itemBreadcrumbs($blockPage->buildLink("../reports/createreport.php?",$strings["reports"],in));
if ($id != "") {
$blockPage->itemBreadcrumbs($reportDetail->rep_name[0]);
} else {
$blockPage->itemBreadcrumbs($strings["report_results"]);
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

$block1->sorting("report_tasks",$sortingUser->sor_report_tasks[0],"tas.name ASC",$sortingFields = array(0=>"tas.name",1=>"tas.priority",2=>"tas.status",3=>"tas.due_date",4=>"tas.complete_date",5=>"mem.login",6=>"tas.project",7=>"tas.published"));

if ($projectsFilter == "true") {
	$tmpquery = "LEFT OUTER JOIN ".$tableCollab["teams"]." teams ON teams.project = pro.id ";
	$tmpquery .= "WHERE pro.status IN(0,2,3) AND teams.member = '$idSession' ORDER BY pro.id";

	$listProjectsTasks = new request();
	$listProjectsTasks->openProjects($tmpquery);
	$comptListProjectsTasks = count($listProjectsTasks->pro_id);

	if ($comptListProjectsTasks != "0") {
		for ($i=0;$i<$comptListProjectsTasks;$i++) {
			$filterTasks .= $listProjectsTasks->pro_id[$i];
			if ($comptListProjectsTasks-1 != $i) {
				$filterTasks .= ",";
			}
		}
		if ($query != "") {
			$tmpquery = "$queryStart $query AND pro.id IN($filterTasks) ORDER BY $block1->sortingValue";
		} else {
			$tmpquery = "WHERE pro.id IN($filterTasks) ORDER BY $block1->sortingValue";
		}
	} else {
		$validTasks = "false";
	}
} else {
	$tmpquery = "$queryStart $query ORDER BY $block1->sortingValue";
}
$listTasks = new request();
$listTasks->openTasks($tmpquery);
$comptListTasks = count($listTasks->tas_id);
   
$block0 = new block();

$block0->openContent();
$block0->contentTitle($strings["report_results"]);

if ($comptListTasks == "0") {
$block1->contentRow("","0 ".$strings["matches"]."<br>".$strings["no_results_report"]);
}
if ($comptListTasks == "1") {
$block1->contentRow("","1 ".$strings["match"]);
}
if ($comptListTasks > "1") {
$block1->contentRow("",$comptListTasks." ".$strings["matches"]);
}

$block0->closeContent();

$block1->form = "Tasks";
$block1->openForm("../reports/resultsreport.php?".session_name()."=".session_id()."&amp;tri=true&amp;id=$id#".$block1->form."Anchor");

$block1->heading($strings["report_results"]);

if ($comptListTasks != "0") {
$block1->openPaletteIcon();
//$block1->paletteIcon(0,"export",$strings["export"]);
$block1->paletteIcon(1,"edit",$strings["edit"]);
$block1->closePaletteIcon();

	$block1->openResults();

	$block1->labels($labels = array(0=>$strings["task"],1=>$strings["priority"],2=>$strings["status"],3=>$strings["due_date"],4=>$strings["complete_date"],5=>$strings["assigned_to"],6=>$strings["project"],7=>$strings["published"]),"true");

for ($i=0;$i<$comptListTasks;$i++) {


$idStatus = $listTasks->tas_status[$i];
$idPriority = $listTasks->tas_priority[$i];
$idPublish = $listTasks->tas_published[$i];
$block1->openRow();
$block1->checkboxRow($listTasks->tas_id[$i]);
$block1->cellRow($blockPage->buildLink("../tasks/viewtask.php?id=".$listTasks->tas_id[$i],$listTasks->tas_name[$i],in));
$block1->cellRow($priority[$idPriority]);
$block1->cellRow($status[$idStatus]);
if ($listTasks->tas_due_date[$i] <= $date && $listTasks->tas_completion[$i] != "10") {
	$block1->cellRow("<b>".$listTasks->tas_due_date[$i]."</b>");
} else {
	$block1->cellRow($listTasks->tas_due_date[$i]);
}
if ($listTasks->tas_start_date[$i] != "--" && $listTasks->tas_due_date[$i] != "--") {
$gantt = "true";
}
$block1->cellRow($listTasks->tas_complete_date[$i]);
if ($listTasks->tas_assigned_to[$i] == "0") {
$block1->cellRow($strings["unassigned"]);
} else {
$block1->cellRow($blockPage->buildLink($listTasks->tas_mem_email_work[$i],$listTasks->tas_mem_login[$i],mail));
}
$block1->cellRow($blockPage->buildLink("../projects/viewproject.php?id=".$listTasks->tas_project[$i],$listTasks->tas_pro_name[$i],in));
if ($sitePublish == "true") {
$block1->cellRow($statusPublish[$idPublish]);
}
$block1->closeRow();
}
$block1->closeResults();

if ($activeJpgraph == "true" && $gantt == "true" && $id != "") {
echo "<img src=\"graphtasks.php?".session_name()."=".session_id()."&amp;report=$id\" alt=\"\"><br>
<span class=\"listEvenBold\">".$blockPage->buildLink("http://www.aditus.nu/jpgraph/","JpGraph",powered)."</span>";
}

echo "<input type=hidden name=S_ORGSEL value=\"$S_ORGSEL\">
<input type=hidden name=S_PRJSEL value=\"$S_PRJSEL\">
<input type=hidden name=S_ATSEL value=\"$S_ATSEL\">
<input type=hidden name=S_STATSEL value=\"$S_STATSEL\">
<input type=hidden name=S_PRIOSEL value=\"$S_PRIOSEL\">";

$block1->closeFormResults();

$block1->openPaletteScript();
//$block1->paletteScript(0,"export","export.php?","false,true,true",$strings["export"]);
$block1->paletteScript(1,"edit","../tasks/edittask.php?report=$id","false,true,false",$strings["edit"]);
$block1->closePaletteScript($comptListTasks,$listTasks->tas_id);
}

$block2 = new block();

$block2->form = "save_report";
$block2->openForm("../reports/resultsreport.php?action=add&amp;".session_name()."=".session_id());

if ($error != "") {            
	$block2->headingError($strings["errors"]);
	$block2->contentError($error);
}

$block2->openContent();
$block2->contentTitle($strings["report_save"]);

echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["report_name"]." :</td><td><input type=\"text\" name=\"S_SAVENAME\" value=\"\" style=\"width: 200px;\" maxlength=\"64\"></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">&nbsp;</td><td><input type=\"submit\" name=\"".$strings["save"]."\" value=\"".$strings["save"]."\">
<input type=hidden name=S_ORGSEL value=\"$S_ORGSEL\">
<input type=hidden name=S_PRJSEL value=\"$S_PRJSEL\">
<input type=hidden name=S_ATSEL value=\"$S_ATSEL\">
<input type=hidden name=S_STATSEL value=\"$S_STATSEL\">
<input type=hidden name=S_PRIOSEL value=\"$S_PRIOSEL\">
<input type=hidden name=S_SDATE value=\"$S_SDATE\">
<input type=hidden name=S_EDATE value=\"$S_EDATE\">
<input type=hidden name=S_SDATE2 value=\"$S_SDATE2\">
<input type=hidden name=S_EDATE2 value=\"$S_EDATE2\">
</td></tr>";

$block2->closeContent();
$block2->closeForm();

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>