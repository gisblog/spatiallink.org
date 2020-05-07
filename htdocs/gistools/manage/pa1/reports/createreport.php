<?php
#Application name: PhpCollab
#Status page: 0
#Path by root: ../reports/createreport.php

$checkSession = "true";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	$postnukeIntegration = "true";
	include("modules/PhpCollab/includes/library.php");
} else {
	$postnukeIntegration = "false";
	include("../includes/library.php");
}

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/header.php");
} else {
	include("../themes/".THEME."/header.php");
}

$blockPage = new block();
$blockPage->openBreadcrumbs();
$blockPage->itemBreadcrumbs($blockPage->buildLink("../reports/createreport.php?",$strings["reports"],in));
$blockPage->itemBreadcrumbs($strings["create_report"]);
$blockPage->closeBreadcrumbs();

$block1 = new block();

$block1->form = "customsearch";
$block1->openForm("../reports/resultsreport.php?".session_name()."=".session_id());

$block1->heading($strings["create_report"]);
    
$block1->openContent();
$block1->contentTitle($strings["report_intro"]);

echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["clients"]." :</td><td>";

if ($clientsFilter == "true" && $profilSession == "2") {
$teamMember = "false";
$tmpquery = "WHERE tea.member = '$idSession'";
$memberTest = new request();
$memberTest->openTeams($tmpquery);
$comptMemberTest = count($memberTest->tea_id);
	if ($comptMemberTest == "0") {
		$listClients = "false";
	} else {
		for ($i=0;$i<$comptMemberTest;$i++) {
			$clientsOk .= $memberTest->tea_org2_id[$i];
			if ($comptMemberTest-1 != $i) {
				$clientsOk .= ",";
			}
		}
		if ($clientsOk == "") {
			$listClients = "false";
		} else {
			$tmpquery = "WHERE org.id IN($clientsOk) AND org.id != '1' ORDER BY org.name";
		}
	}
} else if ($clientsFilter == "true" && $profilSession == "1") {
$tmpquery = "WHERE org.owner = '$idSession' AND org.id != '1' ORDER BY org.name";
} else {
$tmpquery = "WHERE org.id != '1' ORDER BY org.name";
}

$listOrganizations = new request();
$listOrganizations->openOrganizations($tmpquery);
$comptListOrganizations = count($listOrganizations->org_id);

echo "<select name=\"S_ORGSEL[]\" size=\"4\" multiple><option selected value=\"ALL\">".$strings["select_all"]."</option>";

for ($i=0;$i<$comptListOrganizations;$i++) {
echo "<option value=\"".$listOrganizations->org_id[$i]."\">".$listOrganizations->org_name[$i]."</option>";
}

echo "</select></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["projects"]." :</td><td>";

if ($projectsFilter == "true") {
	$tmpquery = "LEFT OUTER JOIN ".$tableCollab["teams"]." teams ON teams.project = pro.id ";
	$tmpquery .= "WHERE pro.status IN(0,2,3) AND teams.member = '$idSession' ORDER BY pro.name";
} else {
	$tmpquery = "WHERE pro.status IN(0,2,3)  ORDER BY pro.name";
}
$listProjects = new request();
$listProjects->openProjects($tmpquery);
$comptListProjects = count($listProjects->pro_id);

echo "<select name=\"S_PRJSEL[]\" size=\"4\" multiple><option selected value=\"ALL\">".$strings["select_all"]."</option>";

for ($i=0;$i<$comptListProjects;$i++) {
echo "<option value=\"".$listProjects->pro_id[$i]."\">".$listProjects->pro_name[$i]."</option>";
}

echo "</select></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["assigned_to"]." :</td><td>";

if ($demoMode == "true") {
	$tmpquery = "ORDER BY mem.name";
} else {
	$tmpquery = "WHERE mem.id != '2' ORDER BY mem.name";
}
$listMembers = new request();
$listMembers->openMembers($tmpquery);
$comptListMembers = count($listMembers->mem_id);

echo "<select name=\"S_ATSEL[]\" size=\"4\" multiple><option selected value=\"ALL\">".$strings["select_all"]."</option>
<option value=\"0\">".$strings["unassigned"]."</option>";

for ($i=0;$i<$comptListMembers;$i++) {
echo "<option value=\"".$listMembers->mem_id[$i]."\">".$listMembers->mem_login[$i];

if ($listMembers->mem_profil[$i] == "3") {
	echo " (".$strings["client_user"].")";
}
echo "</option>";
}

echo "</select></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["due_date"]." :</td><td>";



if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/includes/calendar.php");
} else {
	include("../includes/calendar.php");
}

echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\">
<tr>
<td width=\"16\" align=\"center\" class=\"infovalue\"><input checked name=S_DUEDATE type=radio value=ALL></td>
<td align=\"left\" width=\"200\">".$strings["all_dates"]."</td>
</tr>
<tr>
<td width=\"16\" align=\"center\" class=\"infovalue\"><input  name=S_DUEDATE type=radio value=DATERANGE></td>
<td align=\"left\" width=\"200\">".$strings["between_dates"]."</td>
</tr>
</table>
<table border=0 cellpadding=2 cellspacing=0>
<tr><td width=18><img height=8 src=\"../themes/".THEME."/spacer.gif\" alt=\"\" width=18></td>
<td class=infoValue noWrap><input type=\"text\" name=\"S_SDATE\" id=\"sel1\" size=\"20\" value=\"\"><input type=\"reset\" value=\" ... \" onclick=\"return showCalendar('sel1', 'y-mm-dd');\"></td>
</tr>
<tr>
<td width=18>&nbsp;".$strings["and"]."&nbsp;<TD class=infoValue noWrap><input type=\"text\" name=\"S_EDATE\" id=\"sel3\" size=\"20\" value=\"\"><input type=\"reset\" value=\" ... \" onclick=\"return showCalendar('sel3', 'y-mm-dd');\"></TD>
</tr>
</table>";

echo "</td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["complete_date"]." :</td><td>";

echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\">
<tr>
<td width=\"16\" align=\"center\" class=\"infovalue\"><input checked name=S_COMPLETEDATE type=radio value=ALL></td>
<td align=\"left\" width=\"200\">".$strings["all_dates"]."</td>
</tr>
<tr>
<td width=\"16\" align=\"center\" class=\"infovalue\"><input  name=S_COMPLETEDATE type=radio value=DATERANGE></td>
<td align=\"left\" width=\"200\">".$strings["between_dates"]."</td>
</tr>
</table>
<table border=0 cellpadding=2 cellspacing=0>
<tr><td width=18><img height=8 src=\"../themes/".THEME."/spacer.gif\" alt=\"\" width=18></td>
<td class=infoValue noWrap><input type=\"text\" name=\"S_SDATE2\" id=\"sel5\" size=\"20\" value=\"\"><input type=\"reset\" value=\" ... \" onclick=\"return showCalendar('sel5', 'y-mm-dd');\"></td>
</tr>
<tr>
<td width=18>&nbsp;".$strings["and"]."&nbsp;<TD class=infoValue noWrap><input type=\"text\" name=\"S_EDATE2\" id=\"sel7\" size=\"20\" value=\"\"><input type=\"reset\" value=\" ... \" onclick=\"return showCalendar('sel7', 'y-mm-dd');\"></TD>
</tr>
</table>";

echo "</td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["status"]." :</td><td>";

$comptSta = count($status);

echo "<select name=\"S_STATSEL[]\" size=\"4\" multiple>
<option value=\"ALL\" selected>".$strings["select_all"]."</option>";

for ($i=0;$i<$comptSta;$i++) {
echo "<option value=\"$i\">$status[$i]</option>";
}

echo "</select></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["priority"]." :</td><td>";

$comptPri = count($priority);

echo "<select name=\"S_PRIOSEL[]\" size=\"4\" multiple>
<option value=\"ALL\" selected>".$strings["select_all"]."</option>";

for ($i=0;$i<$comptPri;$i++) {
echo "<option value=\"$i\">$priority[$i]</option>";
}


echo "</select></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">&nbsp;</td><td><input type=\"submit\" name=\"Save\" value=\"".$strings["create"]."\"></td></tr>";

$block1->closeContent();
$block1->closeForm();

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>