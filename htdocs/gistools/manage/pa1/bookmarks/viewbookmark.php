<?php
#Application name: PhpCollab
#Status page: 1
#Path by root: ../bookmarks/viewbookmark.php

$checkSession = "true";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	$postnukeIntegration = "true";
	include("modules/PhpCollab/includes/library.php");
} else {
	$postnukeIntegration = "false";
	include("../includes/library.php");
}

if ($action == "publish") {
	if ($addToSite == "true") {
		$tmpquery1 = "UPDATE ".$tableCollab["notes"]." SET published='0' WHERE id = '$id'";
		connectSql("$tmpquery1");
		$msg = "addToSite";
	}
	if ($removeToSite == "true") {
		$tmpquery1 = "UPDATE ".$tableCollab["notes"]." SET published='1' WHERE id = '$id'";
		connectSql("$tmpquery1");
		$msg = "removeToSite";
	}
}

$tmpquery = "WHERE boo.id = '$id'";
$bookmarkDetail = new request();
$bookmarkDetail->openBookmarks($tmpquery);

if ($bookmarkDetail->boo_users[0] != "") {
$pieces = explode("|",$bookmarkDetail->boo_users[0]);
$comptPieces = count($pieces);
$private = "false";
	for ($i=0;$i<$comptPieces;$i++) {
		if ($idSession == $pieces[$i]) {
			$private = "true";
		}
	}
}

if (($bookmarkDetail->boo_users[0] == "" && $bookmarkDetail->boo_owner[0] != $idSession && $bookmarkDetail->boo_shared[0] == "0") || ($private == "false" && $bookmarkDetail->boo_owner[0] != $idSession)) {
	headerFunction("../bookmarks/listbookmarks.php?view=my&msg=bookmarkOwner&".session_name()."=".session_id());
}

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/header.php");
} else {
	include("../themes/".THEME."/header.php");
}

$blockPage = new block();
$blockPage->openBreadcrumbs();
$blockPage->itemBreadcrumbs($blockPage->buildLink("../bookmarks/listbookmarks.php?view=$view",$strings["bookmarks"],in));
$blockPage->itemBreadcrumbs($bookmarkDetail->boo_name[0]);
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
$block1->form = "tdD";
$block1->openForm("../bookmarks/viewbookmark.php?".session_name()."=".session_id()."#".$block1->form."Anchor");
$block1->heading($strings["bookmark"]." : ".$bookmarkDetail->boo_name[0]);
if ($bookmarkDetail->boo_owner[0] == $idSession) {
	$block1->openPaletteIcon();
	$block1->paletteIcon(0,"remove",$strings["delete"]);

	/*if ($sitePublish == "true") {
		$block1->paletteIcon(2,"add_projectsite",$strings["add_project_site"]);
		$block1->paletteIcon(3,"remove_projectsite",$strings["remove_project_site"]);
	}*/
	$block1->paletteIcon(4,"edit",$strings["edit"]);
	$block1->closePaletteIcon();
}

$block1->openContent();
$block1->contentTitle($strings["info"]);

$block1->contentRow($strings["name"],$bookmarkDetail->boo_name[0]);
$block1->contentRow($strings["url"],$blockPage->buildLink($bookmarkDetail->boo_url[0],$bookmarkDetail->boo_url[0],out));
$block1->contentRow($strings["description"],nl2br($bookmarkDetail->boo_description[0]));

$block1->closeContent();
$block1->closeForm();

if ($bookmarkDetail->boo_owner[0] == $idSession) {
	$block1->openPaletteScript();
	$block1->paletteScript(0,"remove","../bookmarks/deletebookmarks.php?id=".$bookmarkDetail->boo_id[0]."","true,true,false",$strings["delete"]);
	/*if ($sitePublish == "true") {
		$block1->paletteScript(2,"add_projectsite","../bookmarks/viewbookmark.php?addToSite=true&id=".$noteDetail->note_id[0]."&action=publish","true,true,true",$strings["add_project_site"]);
		$block1->paletteScript(3,"remove_projectsite","../bookmarks/viewbookmark.php?removeToSite=true&id=".$noteDetail->note_id[0]."&action=publish","true,true,true",$strings["remove_project_site"]);
	}*/
	$block1->paletteScript(4,"edit","../bookmarks/editbookmark.php?id=".$bookmarkDetail->boo_id[0]."","true,true,false",$strings["edit"]);

	$block1->closePaletteScript("","");
}

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>