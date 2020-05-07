<?php
#Application name: PhpCollab
#Status page: 1
#Path by root: ../bookmarks/deletebookmarks.php

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
	$tmpquery1 = "DELETE FROM ".$tableCollab["bookmarks"]." WHERE id IN($id)";
	connectSql("$tmpquery1");
	headerFunction("../bookmarks/listbookmarks.php?view=my&msg=delete&".session_name()."=".session_id());
	exit;
}

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/header.php");
} else {
	include("../themes/".THEME."/header.php");
}

$blockPage = new block();
$blockPage->openBreadcrumbs();
$blockPage->itemBreadcrumbs($blockPage->buildLink("../bookmarks/listbookmarks.php?view=all",$strings["bookmarks"],in));
$blockPage->itemBreadcrumbs($strings["delete_bookmarks"]);
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
$block1->openForm("../bookmarks/deletebookmarks.php?action=delete&amp;id=$id&amp;".session_name()."=".session_id());

$block1->heading($strings["delete_bookmarks"]);

$block1->openContent();
$block1->contentTitle($strings["delete_following"]);

$id = str_replace("**",",",$id);
$tmpquery = "WHERE boo.id IN($id) ORDER BY boo.name";
$listBookmarks = new request();
$listBookmarks->openBookmarks($tmpquery);
$comptListBookmarks = count($listBookmarks->boo_id);

for ($i=0;$i<$comptListBookmarks;$i++) {
$block1->contentRow("#".$listBookmarks->boo_id[$i],$listBookmarks->boo_name[$i]);
}

$block1->contentRow("","<input type=\"submit\" name=\"delete\" value=\"".$strings["delete"]."\"> <input type=\"button\" name=\"cancel\" value=\"".$strings["cancel"]."\" onClick=\"history.back();\">");

$block1->closeContent();
$block1->closeForm();

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>