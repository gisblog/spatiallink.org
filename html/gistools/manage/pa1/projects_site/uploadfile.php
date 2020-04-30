<?php
#Application name: PhpCollab
#Status page: 0

$checkSession = "true";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	$postnukeIntegration = "true";
	include("modules/PhpCollab/includes/library.php");
} else {
	$postnukeIntegration = "false";
	include("../includes/library.php");
}

if ($action == "add") {
if ($maxCustom != "") {
$maxFileSize = $maxCustom;
}
if ($HTTP_POST_FILES['upload']['size']!=0) {$taille_ko=$HTTP_POST_FILES['upload']['size']/1024;} else {$taille_ko=0;}

if ($HTTP_POST_FILES['upload']['name'] == "") {$error.=$strings["no_file"]."<br>";}

if ($HTTP_POST_FILES['upload']['size']>$maxFileSize) {
	if($maxFileSize!=0) {$taille_max_ko=$maxFileSize/1024;}
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

if ($HTTP_POST_FILES['upload']['name'] != "" && $HTTP_POST_FILES['upload']['size']<$maxFileSize && $HTTP_POST_FILES['upload']['size'] != 0 && $send != "false") {
$copy = "true";
}
	if ($copy == "true") {
		$commentsField = convertData($commentsField);
		$tmpquery = "INSERT INTO ".$tableCollab["files"]."(owner,project,task,comments,upload,published,status,vc_version,vc_parent,phase) VALUES('$idSession','$projectSession','0','$commentsField','$dateheure','0','2','0.0','0','0')";
		connectSql("$tmpquery");
		$tmpquery = $tableCollab["files"];
		last_id($tmpquery);
		$num = $lastId[0];
		unset($lastId);
		uploadFile("files/$project", $HTTP_POST_FILES['upload']['tmp_name'], "$num--".$HTTP_POST_FILES['upload']['name']);
		$size = file_info_size("../files/".$project."/".$num."--".$HTTP_POST_FILES['upload']['name']);
		//$dateFile = file_info_date("files/".$project."/".$num."--".$HTTP_POST_FILES['upload']['name']);
		$chaine = strrev("../files/".$project."/".$num."--".$HTTP_POST_FILES['upload']['name']);
		$tab = explode(".",$chaine);
		$extension = strtolower(strrev($tab[0]));
		$name = $num."--".$HTTP_POST_FILES['upload']['name'];
		$tmpquery = "UPDATE ".$tableCollab["files"]." SET name='$name',date='$dateheure',size='$size',extension='$extension' WHERE id = '$num'";
		connectSql("$tmpquery");
		headerFunction("doclists.php?".session_name()."=".session_id());
		exit;
	}
}

$bouton[4] = "over";
$titlePage = $strings["upload_file"];
include ("include_header.php");

echo "<form accept-charset=\"UNKNOWN\" method=\"POST\" action=\"../projects_site/uploadfile.php?".session_name()."=".session_id()."&amp;action=add&amp;project=$projectSession&amp;task=$task#filedetailsAnchor\" name=\"feeedback\" enctype=\"multipart/form-data\"><input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"100000000\"><input type=\"hidden\" name=\"maxCustom\" value=\"".$projectDetail->pro_upload_max[0]."\">";

echo "<table cellpadding=\"3\" cellspacing=\"0\" border=\"0\"><tr><th colspan=\"2\">".$strings["upload_form"]."</th></tr>
<tr><th>".$strings["comments"]." :</th><td><textarea cols=\"60\" name=\"commentsField\" rows=\"6\">$commentsField</textarea></td></tr>
<tr><th>".$strings["upload"]." :</th><td><input size=\"35\" value=\"\" name=\"upload\" type=\"file\"></td></tr>
<tr><th>&nbsp;</th><td><input name=\"submit\" type=\"submit\" value=\"".$strings["save"]."\"><br><br>$error</td></tr></table>
</form>";

include ("include_footer.php");
?>