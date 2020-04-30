<?php
#Application name: PhpCollab
#Status page: 0
#Path by root: ../linkedcontent/addfile.php

$checkSession = "true";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	$postnukeIntegration = "true";
	include("modules/PhpCollab/includes/library.php");
} else {
	$postnukeIntegration = "false";
	include("../includes/library.php");
}

//set task to "0" for project main folder upload
if ($task == "") {
	$task = "0";
}

if ($action == "add") {
if ($maxCustom != "") {
	$maxFileSize = $maxCustom;
}
	if ($HTTP_POST_FILES['upload']['size'] != 0) {
		$taille_ko = $HTTP_POST_FILES['upload']['size']/1024;
	} else {
		$taille_ko=0;
	}
	if ($HTTP_POST_FILES['upload']['name'] == "") {
		$error.=$strings["no_file"]."<br>";
	}
	if ($HTTP_POST_FILES['upload']['size'] > $maxFileSize) {
		if($maxFileSize != 0) {
			$taille_max_ko = $maxFileSize/1024;
		}
		$error.=$strings["exceed_size"]." ($taille_max_ko $byteUnits[1])<br>";
	}

	$extension= strtolower( substr( strrchr($HTTP_POST_FILES['upload']['name'], ".") ,1) );

if ($allowPhp == "false") {
	$send = "";
	if ($HTTP_POST_FILES['upload']['name'] != "" && ($extension=="php" || $extension=="php3" || $extension=="phtml")) {
		$error.=$strings["no_php"]."<br>";
		$send = "false";
	}
}
	if ($HTTP_POST_FILES['upload']['name'] != "" && $HTTP_POST_FILES['upload']['size']<$maxFileSize && $HTTP_POST_FILES['upload']['size']!=0 && $send != "false") {
	$copy = "true";
	}
	if ($copy == "true") {

$match = strstr($versionFile,".");
if ($match == "") {
	$versionFile = $versionFile.".0";
}

if ($versionFile == "") {
	$versionFile = "0.0";
}
		$c = convertData($c);
		$tmpquery = "INSERT INTO ".$tableCollab["files"]."(owner,project,phase,task,comments,upload,published,status,vc_version,vc_parent) VALUES('$idSession','$project','$phase','$task','$c','$dateheure','1','$statusField','$versionFile','0')";
		connectSql("$tmpquery");
		$tmpquery = $tableCollab["files"];
		last_id($tmpquery);
		$num = $lastId[0];
		unset($lastId);
	}
	if ($task != "0") {
		if ($copy == "true") {
			uploadFile("files/$project/$task", $HTTP_POST_FILES['upload']['tmp_name'], "$num--".$HTTP_POST_FILES['upload']['name']);
			$size = file_info_size("../files/".$project."/".$task."/".$num."--".$HTTP_POST_FILES['upload']['name']);
			//$dateFile = file_info_date("../files/".$project."/".$task."/".$num."--".$HTTP_POST_FILES['upload']['name']);
			$chaine = strrev("../files/".$project."/".$task."/".$num."--".$HTTP_POST_FILES['upload']['name']);
			$tab = explode(".",$chaine);
			$extension = strtolower(strrev($tab[0]));
		}
	} else {
		if ($copy == "true") {
			uploadFile("files/$project", $HTTP_POST_FILES['upload']['tmp_name'], "$num--".$HTTP_POST_FILES['upload']['name']);
			$size = file_info_size("../files/".$project."/".$num."--".$HTTP_POST_FILES['upload']['name']);
			//$dateFile = file_info_date("../files/".$project."/".$num."--".$HTTP_POST_FILES['upload']['name']);
			$chaine = strrev("../files/".$project."/".$num."--".$HTTP_POST_FILES['upload']['name']);
			$tab = explode(".",$chaine);
			$extension = strtolower(strrev($tab[0]));
		}
	}
	if ($copy == "true") {
		$name = $num."--".$HTTP_POST_FILES['upload']['name'];
		$tmpquery = "UPDATE ".$tableCollab["files"]." SET name='$name',date='$dateheure',size='$size',extension='$extension' WHERE id = '$num'";
		connectSql("$tmpquery");
		headerFunction("../linkedcontent/viewfile.php?id=$num&msg=addFile&".session_name()."=".session_id());
	}
}

$tmpquery = "WHERE pro.id = '$project'";
$projectDetail = new request();
$projectDetail->openProjects($tmpquery);

if ($projectDetail->pro_phase_set[0] != "0"){
	$tmpquery = "WHERE pha.id = '$phase'";
	$phaseDetail = new request();
	$phaseDetail->openPhases($tmpquery);
}

if ($task != "0") {
	$tmpquery = "WHERE tas.id = '$task'";
	$taskDetail = new request();
	$taskDetail->openTasks($tmpquery);
}

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/header.php");
} else {
	include("../themes/".THEME."/header.php");
}

$blockPage = new block();
$blockPage->openBreadcrumbs();
$blockPage->itemBreadcrumbs($blockPage->buildLink("../projects/listprojects.php?",$strings["projects"],in));
$blockPage->itemBreadcrumbs($blockPage->buildLink("../projects/viewproject.php?id=$project",$projectDetail->pro_name[0],in));

if ($projectDetail->pro_phase_set[0] != "0" && $phase != 0){
	$blockPage->itemBreadcrumbs($blockPage->buildLink("../phases/viewphase.php?id=".$phaseDetail->pha_id[0],$phaseDetail->pha_name[0],in));
} 

if ($task != "0") {
	$blockPage->itemBreadcrumbs($blockPage->buildLink("../tasks/listtasks.php?$project=$project",$strings["tasks"],in));
	$blockPage->itemBreadcrumbs($blockPage->buildLink("../tasks/viewtask.php?id=$task",$taskDetail->tas_name[0],in));
}

$blockPage->itemBreadcrumbs($strings["add_file"]);
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


$block1->form = "filedetails";

echo "<a name=\"filedetailsAnchor\"></a>";

echo "<form accept-charset=\"UNKNOWN\" method=\"POST\" action=\"../linkedcontent/addfile.php?action=add&amp;project=$project&amp;task=$task&amp;phase=$phase&amp;".session_name()."=".session_id()."\" name=\"filedetailsForm\" enctype=\"multipart/form-data\"><input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"100000000\"><input type=\"hidden\" name=\"maxCustom\" value=\"".$projectDetail->pro_upload_max[0]."\">";

if ($error != "") {            
	$block1->headingError($strings["errors"]);
	$block1->contentError($error);
}

$block1->heading($strings["add_file"]);

$block1->openContent();
$block1->contentTitle($strings["details"]);

echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["status"]." :</td><td><select name=\"statusField\">";
	$comptSta = count($statusFile);

	for ($i=0;$i<$comptSta;$i++) {
	if ($i == "2") {
	echo "<option value=\"$i\" selected>$statusFile[$i]</option>";
	} else {
	echo "<option value=\"$i\">$statusFile[$i]</option>";
	}
	}
	echo"</select></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">* ".$strings["upload"]." :</td><td><input size=\"44\" style=\"width: 400px\" name=\"upload\" type=\"FILE\"></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["comments"]." :</td><td><textarea rows=\"3\" style=\"width: 400px; height: 50px;\" name=\"c\" cols=\"43\">$c</textarea></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["vc_version"]." :</td><td><input size=\"44\" style=\"width: 400px\" name=\"versionFile\" type=\"text\" value=\"0.0\"></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">&nbsp;</td><td><input type=\"SUBMIT\" value=\"".$strings["save"]."\"></td></tr>";

$block1->closeContent();
$block1->closeForm();

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>