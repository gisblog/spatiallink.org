<?php
#Application name: PhpCollab
#Status page: 0
#Path by root: ../reports/listreports.php

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
$blockPage->itemBreadcrumbs($strings["my_reports"]);
$blockPage->closeBreadcrumbs();

$block1 = new block();

$block1->form = "wbSe";
$block1->openForm("../reports/listreports.php?".session_name()."=".session_id()."#".$block1->form."Anchor");

$block1->heading($strings["my_reports"]);

$block1->openPaletteIcon();
$block1->paletteIcon(0,"add",$strings["add"]);
$block1->paletteIcon(1,"remove",$strings["delete"]);
$block1->closePaletteIcon();

$block1->sorting("reports",$sortingUser->sor_reports[0],"rep.name ASC",$sortingFields = array(0=>"rep.name",1=>"rep.created"));

$tmpquery = "WHERE rep.owner = '$idSession' ORDER BY $block1->sortingValue";
$listReports = new request();
$listReports->openReports($tmpquery);
$comptListReports = count($listReports->rep_id);

if ($comptListReports != "0") {
	$block1->openResults();

	$block1->labels($labels = array(0=>$strings["name"],1=>$strings["created"]),"false");

for ($i=0;$i<$comptListReports;$i++) {
$block1->openRow();
$block1->checkboxRow($listReports->rep_id[$i]);
$block1->cellRow($blockPage->buildLink("../reports/resultsreport.php?id=".$listReports->rep_id[$i],$listReports->rep_name[$i],in));
$block1->cellRow(createDate($listReports->rep_created[$i],$timezoneSession));
}
$block1->closeResults();
} else {
$block1->noresults();
}
$block1->closeFormResults();


$block1->openPaletteScript();
$block1->paletteScript(0,"add","../reports/createreport.php?","true,true,true",$strings["add"]);
$block1->paletteScript(1,"remove","../reports/deletereports.php?","false,true,true",$strings["delete"]);
$block1->closePaletteScript($comptListReports,$listReports->rep_id);

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>