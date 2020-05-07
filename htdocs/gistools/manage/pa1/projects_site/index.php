<?php
#Application name: PhpCollab
#Status page: 0

$checkSession = "false";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	$postnukeIntegration = "true";
	include("modules/PhpCollab/includes/library.php");
} else {
	$postnukeIntegration = "false";
	include("../includes/library.php");
}
headerFunction("../index.php");

//case session fails
if ($session == "false") {
	session_start();
	session_destroy();
	headerFunction("../general/login.php?session=false");
	exit;

//case log out
} else if ($logout == "true") {
	session_start();
	session_destroy();
	headerFunction("../general/login.php?logout=true&login=$login");
	exit;

//default case
} else {
	headerFunction("../general/login.php");
	exit;
}
?>