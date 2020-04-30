<?php
#Application name: PhpCollab
#Status page: 1
#Path by root: ../clients/deleteclients.php

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
	$tmpquery = "WHERE org.id IN($id)";
	$listOrganizations = new request();
	$listOrganizations->openOrganizations($tmpquery);
	$comptListOrganizations = count($listOrganizations->org_id);
	for ($i=0;$i<$comptListOrganizations;$i++) {
		if (file_exists("logos_clients/".$listOrganizations->org_id[$i].".".$listOrganizations->org_extension_logo[$i])) {
			@unlink("logos_clients/".$listOrganizations->org_id[$i].".".$listOrganizations->org_extension_logo[$i]);
		}
	}
	$tmpquery1 = "DELETE FROM ".$tableCollab["organizations"]." WHERE id IN($id)";
	$tmpquery2 = "UPDATE ".$tableCollab["projects"]." SET organization='1' WHERE organization IN($id)";
	$tmpquery3 = "DELETE FROM ".$tableCollab["members"]." WHERE organization IN($id)";
	connectSql("$tmpquery1");
	connectSql("$tmpquery2");
	connectSql("$tmpquery3");
	headerFunction("../clients/listclients.php?msg=delete&".session_name()."=".session_id());
}

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/header.php");
} else {
	include("../themes/".THEME."/header.php");
}

$blockPage = new block();
$blockPage->openBreadcrumbs();
$blockPage->itemBreadcrumbs($blockPage->buildLink("../clients/listclients.php?",$strings["clients"],in));
$blockPage->itemBreadcrumbs($strings["delete_organizations"]);
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
$block1->openForm("../clients/deleteclients.php?action=delete&amp;id=$id&amp;".session_name()."=".session_id());

$block1->heading($strings["delete_organizations"]);

$block1->openContent();
$block1->contentTitle($strings["delete_following"]);

$id = str_replace("**",",",$id);
$tmpquery = "WHERE org.id IN($id) ORDER BY org.name";
$listOrganizations = new request();
$listOrganizations->openOrganizations($tmpquery);
$comptListOrganizations = count($listOrganizations->org_id);

for ($i=0;$i<$comptListOrganizations;$i++) {
$block1->contentRow("#".$listOrganizations->org_id[$i],$listOrganizations->org_name[$i]);
}

$block1->contentRow("","<input type=\"submit\" name=\"delete\" value=\"".$strings["delete"]."\"> <input type=\"button\" name=\"cancel\" value=\"".$strings["cancel"]."\" onClick=\"history.back();\">");

$block1->closeContent();
$block1->closeForm();

$block1->note($strings["delete_organizations_note"]);

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>