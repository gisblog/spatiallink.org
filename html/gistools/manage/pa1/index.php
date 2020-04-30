<?php
#Application name: PhpCollab
#Status page: 0
#Path by root: index.php

$checkSession = "false";
$indexRedirect = "true";
include("includes/library.php");

//case session fails
global $session;
if ($session == "false") {
	if ($postnukeIntegration == "true") {
		headerFunction("../general/login.php?session=false");
	} else {
		headerFunction("general/login.php?session=false");
	}

//default case
} else {
	if ($postnukeIntegration == "true") {
		headerFunction("../general/login.php");
	} else {
		headerFunction("general/login.php");
	}
}
?>