<?php
#Application name: PhpCollab
#Status page: 1
#Path by root: ../general/error.php

include("../includes/settings.php");

$blank = "true";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/header.php");
} else {
	include("../themes/".THEME."/header.php");
}
include("../themes/".THEME."/block.class.php");

$blockPage = new block();
$blockPage->openBreadcrumbs();
$blockPage->itemBreadcrumbs("&nbsp;");
$blockPage->closeBreadcrumbs();

$block1 = new block();
$block1->heading("PhpCollab : Error");

$block1->openContent();

if ($databaseType == "mysql") {
	$block1->contentTitle("MySql Error");
}
if ($databaseType == "sqlserver") {
	$block1->contentTitle("Sql Server Error");
}

if ($type == "myserver") {
$block1->contentRow("",$strings["error_server"]);
}
if ($type == "mydatabase") {
$block1->contentRow("",$strings["error_database"]);
}

$block1->closeContent();

$footerDev = "false";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>