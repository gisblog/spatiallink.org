<?php
#Application name: PhpCollab
#Status page: 1
#Path by root: ../general/license.php

$checkSession = "false";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	$postnukeIntegration = "true";
	include("modules/PhpCollab/includes/library.php");
} else {
	$postnukeIntegration = "false";
	include("../includes/library.php");
}

$notLogged = "true";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/header.php");
} else {
	include("../themes/".THEME."/header.php");
}

$blockPage = new block();
$blockPage->openBreadcrumbs();
$blockPage->itemBreadcrumbs("&nbsp;");
$blockPage->closeBreadcrumbs();

$block1 = new block();
$block1->heading("PhpCollab : License");

$block1->openContent();
$block1->contentTitle("License");

if ($postnukeIntegration == "true") {
$block1->contentRow("","<pre>".recupFile("modules/PhpCollab/docs/copying.txt")."</pre>");
} else {
$block1->contentRow("","<pre>".recupFile("../docs/copying.txt")."</pre>");
}

$block1->closeContent();

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>