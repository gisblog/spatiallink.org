<?php
#Application name: PhpCollab
#Status page: 0
// no caching to keep phpCollab 2.0 behaviour
@session_cache_limiter('none');   // suppress error messages for PHP version < 4.0.2
error_reporting(0);
$checkSession = "true";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	$postnukeIntegration = "true";
	include("modules/PhpCollab/includes/library.php");
} else {
	$postnukeIntegration = "false";
	include("../includes/library.php");
}		// starts session and writes session cache headers

$tmpquery = "WHERE fil.id = '$id'";
$fileDetail = new request();
$fileDetail->openFiles($tmpquery);

// test if file is published and part of the current project
if ($fileDetail->fil_published[0] == "1" || $fileDetail->fil_project[0] != $projectSession) {
	headerFunction("index.php");
}

// serve the requested file
include("../includes/download.php");

?>