<?php
#Application name: PhpCollab
#Status page: 1
#Path by root: ../clients/listclients.php

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
$blockPage->itemBreadcrumbs($blockPage->buildLink("../clients/listclients.php?",$strings["organizations"],in));
$blockPage->itemBreadcrumbs($strings["organizations"]);
$blockPage->closeBreadcrumbs();

if ($msg != "") {
if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/includes/messages.php");
} else {
	include("../includes/messages.php");
}
	$blockPage->messagebox($msgLabel);
}

$blockPage->bornesNumber = "1";

$block1 = new block();

$block1->form = "clientList";
$block1->openForm("../clients/listclients.php?".session_name()."=".session_id()."#".$block1->form."Anchor");

$block1->heading($strings["organizations"]);

$block1->openPaletteIcon();
if ($profilSession == "0" || $profilSession == "1") {
	$block1->paletteIcon(0,"add",$strings["add"]);
	$block1->paletteIcon(1,"remove",$strings["delete"]);
}
$block1->paletteIcon(2,"info",$strings["view"]);
if ($profilSession == "0" || $profilSession == "1") {
	$block1->paletteIcon(3,"edit",$strings["edit"]);
}
$block1->closePaletteIcon();

$block1->borne = $blockPage->returnBorne("1");
$block1->rowsLimit = "20";

$block1->sorting("organizations",$sortingUser->sor_organizations[0],"org.name ASC",$sortingFields = array(0=>"org.name",1=>"org.phone",2=>"org.url"));

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
			$tmpquery = "WHERE org.id IN($clientsOk) AND org.id != '1' ORDER BY $block1->sortingValue";
		}
	}
} else if ($clientsFilter == "true" && $profilSession == "1") {
	$tmpquery = "WHERE org.owner = '$idSession' AND org.id != '1' ORDER BY $block1->sortingValue";
} else {
	$tmpquery = "WHERE org.id != '1' ORDER BY $block1->sortingValue";
}

$block1->recordsTotal = compt($initrequest["organizations"]." ".$tmpquery);

if ($listClients != "false") {
	$listOrganizations = new request();
	$listOrganizations->openOrganizations($tmpquery,$block1->borne,$block1->rowsLimit);
	$comptListOrganizations = count($listOrganizations->org_id);
} else {
	$comptListOrganizations = 0;
}

if ($comptListOrganizations != "0") {
$block1->openResults();
$block1->labels($labels = array(0=>$strings["name"],1=>$strings["phone"],2=>$strings["url"]),"false");

for ($i=0;$i<$comptListOrganizations;$i++) {
$block1->openRow();
$block1->checkboxRow($listOrganizations->org_id[$i]);
$block1->cellRow($blockPage->buildLink("../clients/viewclient.php?id=".$listOrganizations->org_id[$i],$listOrganizations->org_name[$i],in));
$block1->cellRow($listOrganizations->org_phone[$i]);
$block1->cellRow($blockPage->buildLink($listOrganizations->org_url[$i],$listOrganizations->org_url[$i],out));
$block1->closeRow();
}
$block1->closeResults();

$block1->bornesFooter("1",$blockPage->bornesNumber,"","");

} else {
$block1->noresults();
}
$block1->closeFormResults();

$block1->openPaletteScript();
if ($profilSession == "0" || $profilSession == "1") {
	$block1->paletteScript(0,"add","../clients/editclient.php?","true,false,false",$strings["add"]);
	$block1->paletteScript(1,"remove","../clients/deleteclients.php?","false,true,true",$strings["delete"]);
}
$block1->paletteScript(2,"info","../clients/viewclient.php?","false,true,false",$strings["view"]);
if ($profilSession == "0" || $profilSession == "1") {
	$block1->paletteScript(3,"edit","../clients/editclient.php?","false,true,false",$strings["edit"]);
}
$block1->closePaletteScript($comptListOrganizations,$listOrganizations->org_id);

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>