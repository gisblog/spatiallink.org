<?php
#Application name: PhpCollab
#Status page: 1
#Path by root: ../general/systemrequirements.php

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
$block1->heading("PhpCollab : ".$strings["requirements"]);

$block1->openContent();
$block1->contentTitle($strings["requirements"]);

$block1->contentRow("Windows","- Internet Explorer 5.x, 6.x<br>- Netscape 6.x");
$block1->contentRow("Macintosh","- Internet Explorer 5.x");
$block1->contentRow("Linux","- Mozilla<br>- Galeon");

$block1->closeContent();

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>