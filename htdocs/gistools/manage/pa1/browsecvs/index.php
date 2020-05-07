<?php
#Application name: PhpCollab
#Status page: 2
#Path by root: ../browsecvs/index.php

$checkSession = "false";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	$postnukeIntegration = "true";
	include("modules/PhpCollab/includes/library.php");
} else {
	$postnukeIntegration = "false";
	include("../includes/library.php");
}
headerFunction("../index.php");
exit;
?>