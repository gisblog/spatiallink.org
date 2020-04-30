<?php
#Application name: PhpCollab
#Status page: 0
#Path by root: ../linkedcontent/viewfile.php

$checkSession = "true";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	$postnukeIntegration = "true";
	include("modules/PhpCollab/includes/library.php");
} else {
	$postnukeIntegration = "false";
	include("../includes/library.php");
}

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/includes/files_types.php");
} else {
	include("../includes/files_types.php");
}

if ($action == "publish") {
if ($addToSiteFile == "true") {
$tmpquery1 = "UPDATE ".$tableCollab["files"]." SET published='0' WHERE id = '$file' OR vc_parent = '$file'";
connectSql("$tmpquery1");
$msg = "addToSite";
$id = $file;
}

if ($removeToSiteFile == "true") {
$tmpquery1 = "UPDATE ".$tableCollab["files"]." SET published='1' WHERE id = '$file' OR vc_parent = '$file'";
connectSql("$tmpquery1");
$msg = "removeToSite";
$id = $file;
}
}

$tmpquery = "WHERE fil.id = '$id'";
$fileDetail = new request();
$fileDetail->openFiles($tmpquery);
$comptFileDetail = count($fileDetail->fil_id);

$teamMember = "false";
$tmpquery = "WHERE tea.project = '".$fileDetail->fil_project[0]."' AND tea.member = '$idSession'";
$memberTest = new request();
$memberTest->openTeams($tmpquery);
$comptMemberTest = count($memberTest->tea_id);
	if ($comptMemberTest == "0") {
		$teamMember = "false";
	} else {
		$teamMember = "true";
	}

$tmpquery = "WHERE pro.id = '".$fileDetail->fil_project[0]."'";
$projectDetail = new request();
$projectDetail->openProjects($tmpquery);

if ($fileDetail->fil_task[0] != "0") {
	$tmpquery = "WHERE tas.id = '".$fileDetail->fil_task[0]."'";
	$taskDetail = new request();
	$taskDetail->openTasks($tmpquery);
}	
	
if ($projectDetail->pro_phase_set[0] != "0"){
$tmpquery = "WHERE pha.id = '".$fileDetail->fil_phase[0]."'";
$phaseDetail = new request();
$phaseDetail->openPhases($tmpquery);
}

$type = file_info_type($fileDetail->fil_extension[0]);
$displayname = $fileDetail->fil_name[0];

//---------------------------------------------------------------------------------------------------
//Update file code
if ($action == "update") {
if ($maxCustom != "") {
$maxFileSize = $maxCustom;
}
	if ($HTTP_POST_FILES['upload']['size']!=0) {
		$taille_ko=$HTTP_POST_FILES['upload']['size']/1024;
	} else {
		$taille_ko=0;
	}
	if ($HTTP_POST_FILES['upload']['name'] == "") {
		$error4.=$strings["no_file"]."<br>";
	}
	if ($HTTP_POST_FILES['upload']['size']>$maxFileSize) {
		if($maxFileSize!=0) {
			$taille_max_ko=$maxFileSize/1024;
		}
		$error4.=$strings["exceed_size"]." ($taille_max_ko $byteUnits[1])<br>";
	}
	
	$upload_name = $fileDetail->fil_name[0];
	$extension= strtolower( substr( strrchr($upload_name, ".") ,1) );
	
	//Add version number to the old copy's file name.
	$changename = str_replace("."," v".$fileDetail->fil_vc_version[0].".", $fileDetail->fil_name[0]);

	//Generate paths for use further down.
	if ($fileDetail->fil_task[0] != "0") {
	$path = "files/".$fileDetail->fil_project[0]."/".$fileDetail->fil_task[0]."/$upload_name";
	$path_source = "files/".$fileDetail->fil_project[0]."/".$fileDetail->fil_task[0]."/".$fileDetail->fil_name[0];
	$path_destination = "files/".$fileDetail->fil_project[0]."/".$fileDetail->fil_task[0]."/$changename";
	}
	else{
	$path = "files/".$fileDetail->fil_project[0]."/$upload_name";
	$path_source = "files/".$fileDetail->fil_project[0]."/".$fileDetail->fil_name[0];
	$path_destination = "files/".$fileDetail->fil_project[0]."/$changename";
	}
	
if ($allowPhp == "false") {
	$send = "";
	if ($HTTP_POST_FILES['upload']['name'] != "" && ($extension=="php" || $extension=="php3" || $extension=="phtml")) {
		$error4.=$strings["no_php"]."<br>";
		$send = "false";
	}
}

	if ($HTTP_POST_FILES['upload']['name'] != "" && $HTTP_POST_FILES['upload']['size']<$maxFileSize && $HTTP_POST_FILES['upload']['size']!=0 && $send != "false") {
	$copy = "true";
	}
		
	if ($copy == "true") {
		//Copy old file with a new file name
		moveFile($path_source,$path_destination);
		
		//Set variables from original files details.
		$copy_project = $fileDetail->fil_project[0];
		$copy_task = $fileDetail->fil_task[0];
		$copy_date = $fileDetail->fil_date[0];
		$copy_size = $fileDetail->fil_size[0];
		$copy_extension = $fileDetail->fil_extension[0];		
		$copy_comments = $fileDetail->fil_comments[0];
		$copy_comments_approval = $fileDetail->fil_comments_approval[0];
		$copy_approver = $fileDetail->fil_approver[0];
		$copy_date_approval = $fileDetail->fil_date_approval[0];
		$copy_upload = $fileDetail->fil_upload[0];
		$copy_pusblished = $fileDetail->fil_published[0];
		$copy_vc_parent = $fileDetail->fil_vc_parent[0];
		$copy_id = $fileDetail->fil_id[0];
		$copy_vc_version = $fileDetail->fil_vc_version[0];
				
		//Insert a new row for the copied file
		$copy_comments = convertData($copy_comments);
		$tmpquery = "INSERT INTO ".$tableCollab["files"]."(owner,project,task,name,date,size,extension,comments,comments_approval,approver,date_approval,upload,published,status,vc_status,vc_version,vc_parent) VALUES('$idSession','$copy_project','$copy_task','$changename','$copy_date','$copy_size','$copy_extension','$copy_comments','$copy_comments_approval','$copy_approver','$copy_date_approval','$copy_upload','$copy_pusblished','2','3','$copy_vc_version','$copy_id')";
		connectSql("$tmpquery");
		$tmpquery = $tableCollab["files"];
		last_id($tmpquery);
		$num = $lastId[0];
		unset($lastId);
	}
	
	//Insert details into Database
	if ($copy == "true") {
		uploadFile(".", $HTTP_POST_FILES['upload']['tmp_name'], $path);
		//$size = file_info_size($path);
		//$dateFile = file_info_date($path);
		$chaine = strrev("$path");
		$tab = explode(".",$chaine);
		$extension = strtolower(strrev($tab[0]));
	}
	
	$newversion = $fileDetail->fil_vc_version[0] + $change_file_version;
	if ($copy == "true") {
		$name = $upload_name;
		$tmpquery = "UPDATE ".$tableCollab["files"]." SET date='$dateheure',size='$size',comments='$c',comments_approval='',approver='',date_approval='',status='$statusField',vc_version='$newversion' WHERE id = '$id'";
		connectSql("$tmpquery");
		headerFunction("../linkedcontent/viewfile.php?id=".$fileDetail->fil_id[0]."&msg=addFile&".session_name()."=".session_id());
		exit;
	}	
}
//---------------------------------------------------------------------------------------------------



//---------------------------------------------------------------------------------------------------
//Add new revision code

if ($action == "add") {
if ($maxCustom != "") {
$maxFileSize = $maxCustom;
}
	if ($HTTP_POST_FILES['upload']['size']!=0) {
		$taille_ko=$HTTP_POST_FILES['upload']['size']/1024;
	} else {
		$taille_ko=0;
	}
	if ($HTTP_POST_FILES['upload']['name'] == "") {
		$error3.=$strings["no_file"]."<br>";
	}
	if ($HTTP_POST_FILES['upload']['size']>$maxFileSize) {
		if($maxFileSize!=0) {
			$taille_max_ko=$maxFileSize/1024;
		}
		$error3.=$strings["exceed_size"]." ($taille_max_ko $byteUnits[1])<br>";
	}
	
	$upload_name = $filename;
	//Add version and revision at the end of a file name but before the extension.
	$upload_name = str_replace("."," v$oldversion r$revision.", $upload_name);
	
	$extension= strtolower( substr( strrchr($upload_name, ".") ,1) );
	
	
if ($allowPhp == "false") {
	$send = "";
	if ($HTTP_POST_FILES['upload']['name'] != "" && ($extension=="php" || $extension=="php3" || $extension=="phtml")) {
		$error3.=$strings["no_php"]."<br>";
		$send = "false";
	}
}

	if ($HTTP_POST_FILES['upload']['name'] != "" && $HTTP_POST_FILES['upload']['size']<$maxFileSize && $HTTP_POST_FILES['upload']['size']!=0 && $send != "false") {
	$copy = "true";
	}
	
	//Insert details into Database
	if ($copy == "true") {
		$c = convertData($c);
		$tmpquery = "INSERT INTO ".$tableCollab["files"]."(owner,project,task,comments,upload,published,status,vc_status,vc_parent) VALUES('$idSession','$project','$task','$c','$dateheure','$published','2','0','$parent')";
		connectSql("$tmpquery");
		$tmpquery = $tableCollab["files"];
		last_id($tmpquery);
		$num = $lastId[0];
		unset($lastId);
	}

	if ($task != "0") {
		if ($copy == "true") {
			uploadFile("files/$project/$task", $HTTP_POST_FILES['upload']['tmp_name'], $upload_name);
			$size = file_info_size("../files/$project/$task/$upload_name");
			//$dateFile = file_info_date("../files/$project/$task/$upload_name");
			$chaine = strrev("../files/$project/$task/$upload_name");
			$tab = explode(".",$chaine);
			$extension = strtolower(strrev($tab[0]));
		}
	} else {
		if ($copy == "true") {
			uploadFile("files/$project", $HTTP_POST_FILES['upload']['tmp_name'], $upload_name);
			$size = file_info_size("../files/$project/$upload_name");

			//$dateFile = file_info_date("../files/$project/$upload_name");
			$chaine = strrev("../files/$project/$upload_name");
			$tab = explode(".",$chaine);
			$extension = strtolower(strrev($tab[0]));
		}
	}
	
	if ($copy == "true") {
		$name = $upload_name;
		$tmpquery = "UPDATE ".$tableCollab["files"]." SET name='$name',date='$dateheure',size='$size',extension='$extension',vc_version='$oldversion' WHERE id = '$num'";
		connectSql("$tmpquery");
		headerFunction("../linkedcontent/viewfile.php?id=$sendto&msg=addFile&".session_name()."=".session_id());
		exit;
	}
}

//---------------------------------------------------------------------------------------------------

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/header.php");
} else {
	include("../themes/".THEME."/header.php");
}

$blockPage = new block();
$blockPage->openBreadcrumbs();
$blockPage->itemBreadcrumbs($blockPage->buildLink("../projects/listprojects.php?",$strings["projects"],in));
$blockPage->itemBreadcrumbs($blockPage->buildLink("../projects/viewproject.php?id=".$fileDetail->fil_project[0],$projectDetail->pro_name[0],in));

if ($fileDetail->fil_phase[0] != "0" && $projectDetail->pro_phase_set[0] != "0"){
	$blockPage->itemBreadcrumbs($blockPage->buildLink("../phases/viewphase.php?id=".$phaseDetail->pha_id[0],$phaseDetail->pha_name[0],in));
}

if ($fileDetail->fil_task[0] != "0") {
	$blockPage->itemBreadcrumbs($blockPage->buildLink("../tasks/listtasks.php?project=".$fileDetail->fil_project[0],$strings["tasks"],in));
	$blockPage->itemBreadcrumbs($blockPage->buildLink("../tasks/viewtask.php?id=".$taskDetail->tas_id[0],$taskDetail->tas_name[0],in));
}

$blockPage->itemBreadcrumbs($fileDetail->fil_name[0]);
$blockPage->closeBreadcrumbs();



if ($msg != "") {
if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/includes/messages.php");
} else {
	include("../includes/messages.php");
}
	$blockPage->messagebox($msgLabel);
}

//------------------------------------------------------------------------------------------------
//Begining of Display code

//File details block
$block1 = new block();
$block1->form = "vdC";
$block1->openForm("../files/viewfile.php?".session_name()."=".session_id()."&amp;id=$id#".$block1->form."Anchor");

$block1->heading($strings["document"]);

if ($fileDetail->fil_owner[0]==$idSession) {
	$block1->openPaletteIcon();
	$block1->paletteIcon(0,"remove",$strings["ifc_delete_version"]);
	$block1->paletteIcon(1,"add_projectsite",$strings["add_project_site"]);
	$block1->paletteIcon(2,"remove_projectsite",$strings["remove_project_site"]);
	$block1->closePaletteIcon();
}
if ($error1 != "") {            
	$block1->headingError($strings["errors"]);
	$block1->contentError($error1);
}

$block1->openContent();
$block1->contentTitle($strings["details"]);

echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["type"]." :</td><td><img src=\"../interface/icones/$type\" border=\"0\" alt=\"\"></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["name"]." :</td><td>".$fileDetail->fil_name[0]."</td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["vc_version"]." :</td><td>".$fileDetail->fil_vc_version[0]."</td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["ifc_last_date"]." :</td><td>".$fileDetail->fil_date[0]."</td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["size"].":</td><td>".convertSize($fileDetail->fil_size[0])."</td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["owner"]." :</td><td>".$blockPage->buildLink("../users/viewuser.php?id=".$fileDetail->fil_mem_id[0],$fileDetail->fil_mem_name[0],in)." (".$blockPage->buildLink($fileDetail->fil_mem_email_work[0],$fileDetail->fil_mem_login[0],mail).")</td></tr>";

if ($fileDetail->fil_comments[0] != "") {
echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["comments"]." :</td><td>".nl2br($fileDetail->fil_comments[0])."&nbsp;</td></tr>";
}

$idPublish = $fileDetail->fil_published[0];
echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["published"]." :</td><td>$statusPublish[$idPublish]</td></tr>";

$idStatus = $fileDetail->fil_status[0];
echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["approval_tracking"]." :</td><td>$statusFile[$idStatus]</td></tr>";

if ($fileDetail->fil_mem2_id[0] != "") {

echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["approver"]." :</td><td>".$blockPage->buildLink("../users/viewuser.php?id=".$fileDetail->fil_mem2_id[0],$fileDetail->fil_mem2_name[0],in)." (".$blockPage->buildLink($fileDetail->fil_mem2_email_work[0],$fileDetail->fil_mem2_login[0],mail).")&nbsp;</td></tr>";
echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["approval_date"]." :</td><td>".$fileDetail->fil_date_approval[0]."&nbsp;</td></tr>";
}

if ($fileDetail->fil_comments_approval[0] != "") {
echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["approval_comments"]." :</td><td>".nl2br($fileDetail->fil_comments_approval[0])."&nbsp;</td></tr>";
}

//------------------------------------------------------------------

$tmpquery = "WHERE fil.id = '$id' OR fil.vc_parent = '$id' AND fil.vc_status = '3' ORDER BY fil.date DESC";
$listVersions = new request();
$listVersions->openFiles($tmpquery);
$comptListVersions = count($listVersions->fil_vc_parent);

echo"<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["ifc_version_history"]." :</td><td>

<table width=\"600\" cellpadding=\"0\" cellspacing=\"0\" class=\"tableRevision\">";

for ($i=0;$i<$comptListVersions;$i++) {

//Sort odds and evens for bg color
	if ($i == "0") {
		$class = "new";
	} else {
		$class = "old";
	}	
	echo "<tr class=\"$class\"><td>";
	if ($fileDetail->fil_owner[0]==$idSession && $listVersions->fil_id[$i] != $fileDetail->fil_id[0]){
	echo "<a href=\"javascript:MM_toggleItem(document.".$block1->form."Form, '".$listVersions->fil_id[$i]."', '".$block1->form."cb".$listVersions->fil_id[$i]."','".THEME."')\"><img name=\"".$block1->form."cb".$listVersions->fil_id[$i]."\" border=\"0\" src=\"../themes/".THEME."/checkbox_off_16.gif\" alt=\"\" vspace=\"0\"></a>";

	}
	echo"&nbsp;</td>

	<td>".$strings["vc_version"]." : ".$listVersions->fil_vc_version[$i]."</td>
	<td colspan=\"3\">$displayname&nbsp;&nbsp;";
	
	if ($listVersions->fil_task[$i] != "0") {
	if (file_exists("../files/".$listVersions->fil_project[$i]."/".$listVersions->fil_task[$i]."/".$listVersions->fil_name[$i])) {
		echo $blockPage->buildLink("../linkedcontent/accessfile.php?mode=view&amp;id=".$listVersions->fil_id[$i],$strings["view"],in);
		$folder = $listVersions->fil_project[$i]."/".$listVersions->fil_task[$i];
		$existFile = "true";
	}
	} else {
		if (file_exists("../files/".$listVersions->fil_project[$i]."/".$listVersions->fil_name[$i])) {
			echo $blockPage->buildLink("../linkedcontent/accessfile.php?mode=view&amp;id=".$listVersions->fil_id[$i],$strings["view"],in);
			$folder = $listVersions->fil_project[$i];
			$existFile = "true";
		}
	}
	if ($existFile == "true") {
		echo " ".$blockPage->buildLink("../linkedcontent/accessfile.php?mode=download&amp;id=".$listVersions->fil_id[$i],$strings["save"],in);
	} else {
		echo $strings["missing_file"];
	}

	echo"</td><td>".$strings["date"]." : ".$listVersions->fil_date[$i]."</td></tr>";
if ($listVersions->fil_mem2_id[$i] != "" || $listVersions->fil_comments_approval[$i] != "") {
$idStatus = $listVersions->fil_status[$i];
echo "<tr class=\"$class\"><td>&nbsp;</td><td colspan=5>";
if ($listVersions->fil_mem2_id[$i] != "") {
echo $strings["approver"]." : ".$blockPage->buildLink("../users/viewuser.php?id=".$listVersions->fil_mem2_id[$i],$listVersions->fil_mem2_name[$i],in)." (".$blockPage->buildLink($listVersions->fil_mem2_email_work[$i],$listVersions->fil_mem2_login[$i],mail).")<br>
".$strings["approval_tracking"]." :$statusFile[$idStatus]<br>";
echo $strings["approval_date"]." : ".$listVersions->fil_date_approval[$i]."&nbsp;";
}
if ($listVersions->fil_comments_approval[$i] != "") {
echo "<br>".$strings["approval_comments"]." : ".nl2br($listVersions->fil_comments_approval[$i])."&nbsp;";
}
echo "</td></tr>";
}
}
echo"</table></td></tr>";

//------------------------------------------------------------------
$block1->closeResults();
$block1->closeFormResults();



if ($fileDetail->fil_owner[0] == $idSession) {
	$block1->openPaletteScript();
	$block1->paletteScript(0,"remove","../linkedcontent/deletefiles.php?project=".$fileDetail->fil_project[0]."&task=".$fileDetail->fil_task[0]."&sendto=filedetails","false,true,true",$strings["ifc_delete_version"]);
	$block1->paletteScript(1,"add_projectsite","../linkedcontent/viewfile.php?addToSiteFile=true&file=".$fileDetail->fil_id[0]."&action=publish","true,true,true",$strings["add_project_site"]);
	$block1->paletteScript(2,"remove_projectsite","../linkedcontent/viewfile.php?removeToSiteFile=true&file=".$fileDetail->fil_id[0]."&action=publish","true,true,true",$strings["remove_project_site"]);
	$block1->closePaletteScript($comptFileDetail,$fileDetail->fil_id);
}

if ($peerReview == "true") {
//Revision list block
$block2 = new block();

$block2->form = "tdC";
$block2->openForm("../files/viewfile.php?".session_name()."=".session_id()."&amp;id=$id#".$block2->form."Anchor");
$block2->heading($strings["ifc_revisions"]);

if ($fileDetail->fil_owner[0] == $idSession) {
$block2->openPaletteIcon();
$block2->paletteIcon(0,"remove",$strings["ifc_delete_review"]);
$block2->closePaletteIcon();
}

if ($error2 != "") {            
	$block2->headingError($strings["errors"]);
	$block2->contentError($error2);
}
$block2->openContent();
$block2->contentTitle($strings["details"]);
echo"<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\"></td><td><br>";

$tmpquery = "WHERE fil.vc_parent = '$id' AND fil.vc_status != '3' ORDER BY fil.date";
$listReviews = new request();
$listReviews->openFiles($tmpquery);
$comptListReviews = count($listReviews->fil_vc_parent);
for ($i=0;$i<$comptListReviews;$i++) {

//Sort odds and evens for bg color
	if (!($i%2)) {
		$class = "odd";
		$highlightOff = $oddColor;
	} else {
		$class = "even";
		$highlightOff = $evenColor;
	}
	
//Calculate a revision number for display for each listing
	$displayrev = $i + 1;
	
	echo "<table width=\"600\" cellpadding=\"0\" cellspacing=\"0\" class=\"tableRevision\" onmouseover=\"this.style.backgroundColor='".$highlightOn."'\" onmouseout=\"this.style.backgroundColor='".$highlightOff."'\">
	<tr bgcolor=\"#D0D8DC\"><td>";
	if ($fileDetail->fil_owner[0]==$idSession){
	echo"<a href=\"javascript:MM_toggleItem(document.".$block2->form."Form, '".$listReviews->fil_id[$i]."', '".$block2->form."cb".$listReviews->fil_id[$i]."','".THEME."')\"><img name=\"".$block2->form."cb".$listReviews->fil_id[$i]."\" border=\"0\" src=\"../themes/".THEME."/checkbox_off_16.gif\" alt=\"\" vspace=\"0\"></a>";
	}
	echo"&nbsp;</td>
	<td colspan=\"3\">$displayname&nbsp;&nbsp;";
	if ($listReviews->fil_task[$i] != "0") {
		if (file_exists("../files/".$listReviews->fil_project[$i]."/".$listReviews->fil_task[$i]."/".$listReviews->fil_name[$i])) {
			echo $blockPage->buildLink("../linkedcontent/accessfile.php?mode=view&amp;id=".$listReviews->fil_id[$i],$strings["view"],in);
			$folder = $listReviews->fil_project[$i]."/".$listReviews->fil_task[$i];
			$existFile = "true";
		}
	} else {
		if (file_exists("../files/".$listReviews->fil_project[$i]."/".$listReviews->fil_name[$i])) {
			echo $blockPage->buildLink("../linkedcontent/accessfile.php?mode=view&amp;id=".$listReviews->fil_id[$i],$strings["view"],in);
			$folder = $listReviews->fil_project[$i];
			$existFile = "true";
		}
	}
	if ($existFile == "true") {
		echo " ".$blockPage->buildLink("../linkedcontent/accessfile.php?mode=download&amp;id=".$listReviews->fil_id[$i],$strings["save"],in);
	} else {
		echo $strings["missing_file"];
	}
	echo"</td><td align=\"right\">Revision: $displayrev&nbsp;&nbsp;</td></tr>
	<tr><td>&nbsp;</td><td width=\"30%\">".$strings["ifc_revision_of"]." : ".$listReviews->fil_vc_version[$i]."</td><td width=\"40%\">".$strings["owner"]." : ".$listReviews->fil_mem_name[$i]."</td><td colspan=\"2\" align=\"left\" width=\"30%\">".$strings["date"]." : ".$listReviews->fil_date[$i]."</td></tr>
	<tr><td>&nbsp;</td><td colspan=\"4\">".$strings["comments"]." : ".$listReviews->fil_comments[$i]."</td></tr>
	</table><br>";
}
if($i==0){echo"<tr class=\"odd\"><td></td><td>".$strings["ifc_no_revisions"]."</td></tr>";}
echo"</table></td></tr>";

$block2->closeResults();
$block2->closeFormResults();
if ($fileDetail->fil_owner[0] == $idSession) {
$block2->openPaletteScript();
$block2->paletteScript(0,"remove","../linkedcontent/deletefiles.php?project=".$fileDetail->fil_project[0]."&task=".$fileDetail->fil_task[0]."&sendto=filedetails","false,true,true",$strings["ifc_delete_review"]);
$block2->closePaletteScript($comptListReviews,$listReviews->fil_id);
}

if ($teamMember ==  "true" || $profilSession == "5") {
//Add new revision Block
$block3 = new block();
$block3->form = "filedetails";
echo "<a name=\"filedetailsAnchor\"></a>";
echo "<form accept-charset=\"UNKNOWN\" method=\"POST\" action=\"../linkedcontent/viewfile.php?action=add&amp;id=".$fileDetail->fil_id[0]."&amp;".session_name()."=".session_id()."#filedetailsAnchor\" name=\"filedetailsForm\" enctype=\"multipart/form-data\"><input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"100000000\"><input type=\"hidden\" name=\"maxCustom\" value=\"".$projectDetail->pro_upload_max[0]."\">";
if ($error3 != "") {
	$block3->headingError($strings["errors"]);
	$block3->contentError($error3);
}
$block3->heading($strings["ifc_add_revision"]);
$block3->openContent();
$block3->contentTitle($strings["details"]);

//Add one to the number of current revisions
$revision = $displayrev+1;

echo "
<input value=\"".$fileDetail->fil_id[0]."\" name=\"sendto\" type=\"hidden\">
<input value=\"".$fileDetail->fil_id[0]."\" name=\"parent\" type=\"hidden\">
<input value=\"$revision\" name=\"revision\" type=\"hidden\">
<input value=\"".$fileDetail->fil_vc_version[0]."\" name=\"oldversion\" type=\"hidden\">
<input value=\"".$fileDetail->fil_project[0]."\" name=\"project\" type=\"hidden\">
<input value=\"".$fileDetail->fil_task[0]."\" name=\"task\" type=\"hidden\">
<input value=\"".$fileDetail->fil_published[0]."\" name=\"published\" type=\"hidden\">
<input value=\"".$fileDetail->fil_name[0]."\" name=\"filename\" type=\"hidden\">

<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">* ".$strings["upload"]." :</td><td><input size=\"44\" style=\"width: 400px\" name=\"upload\" type=\"FILE\"></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["comments"]." :</td><td><textarea rows=\"3\" style=\"width: 400px; height: 50px;\" name=\"c\" cols=\"43\">$c</textarea></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">&nbsp;</td><td><input type=\"SUBMIT\" value=\"".$strings["save"]."\"></td></tr>";

$block3->closeContent();
$block3->closeForm();
}
}

//Update file Block
if ($fileDetail->fil_owner[0] == $idSession){
$block4 = new block();

$block4->form = "filedetails";
echo "<a name=\"filedetailsAnchor\"></a>";
echo "<form accept-charset=\"UNKNOWN\" method=\"POST\" action=\"../linkedcontent/viewfile.php?action=update&amp;id=".$fileDetail->fil_id[0]."&amp;".session_name()."=".session_id()."#filedetailsAnchor\" name=\"filedetailsForm\" enctype=\"multipart/form-data\"><input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"100000000\"><input type=\"hidden\" name=\"maxCustom\" value=\"".$projectDetail->pro_upload_max[0]."\">";

if ($error4 != "") {            
	$block4->headingError($strings["errors"]);
	$block4->contentError($error4);
}

$block4->heading($strings["ifc_update_file"]);

$block4->openContent();
$block4->contentTitle($strings["details"]);

	echo"
	<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\"></td><td class=\"odd\">".$strings["version_increm"]."<br>
	<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
	<tr><td align=\"right\">0.01</td><td width=\"30\" align=\"right\"><input name=\"change_file_version\" type=\"radio\" value=\"0.01\"></td></tr>
	<tr><td align=\"right\">0.1</td><td width=\"30\" align=\"right\"><input name=\"change_file_version\" type=\"radio\" value=\"0.1\" checked></td></tr>
	<tr><td align=\"right\">1.0</td><td width=\"30\" align=\"right\"><input name=\"change_file_version\" type=\"radio\" value=\"1.0\"></td></tr>
	</table>
	</td></tr>";

	
	echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["status"]." :</td><td><select name=\"statusField\">";
	$comptSta = count($statusFile);

	for ($i=0;$i<$comptSta;$i++) {
	if ($i == "2") {
	echo "<option value=\"$i\" selected>$statusFile[$i]</option>";

	} else {
	echo "<option value=\"$i\">$statusFile[$i]</option>";
	}
	}
	echo"</select></td></tr>";
	echo"<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">* ".$strings["upload"]." :</td><td><input size=\"44\" style=\"width: 400px\" name=\"upload\" type=\"FILE\"></td></tr>
	<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["comments"]." :</td><td><textarea rows=\"3\" style=\"width: 400px; height: 50px;\" name=\"c\" cols=\"43\">$c</textarea></td></tr>
	<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">&nbsp;</td><td><input type=\"SUBMIT\" value=\"".$strings["ifc_update_file"]."\"></td></tr>";
	
$block4->closeContent();
$block4->closeForm();
}
if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>