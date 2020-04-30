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

if ($enableHelpSupport != "true") {
	headerFunction("../general/permissiondenied.php?".session_name()."=".session_id());
	exit;
}

if ($supportType == "admin") {
	if ($profilSession != "0") {
		headerFunction("../general/permissiondenied.php?".session_name()."=".session_id());
		exit;
	}
}

if ($notifications == "true") {
if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/includes/notification.class.php");
} else {
	include("../includes/notification.class.php");
}
}

$tmpquery = "WHERE sr.id = '$id'";
$requestDetail = new request();
$requestDetail->openSupportRequests($tmpquery);

$tmpquery = "WHERE mem.id = '".$requestDetail->sr_user[0]."'";
$userDetail = new request();
$userDetail->openMembers($tmpquery);

if($action == "edit"){

	if ($notifications == "true") {
		if ($requestDetail->sr_status[0] != $sta){
			$mem = $userDetail->mem_id[0];
			$it = $requestDetail->sr_id[0];
			$email = $userDetail->mem_email_work[0];
			$statusChange = new notification();
			$num = 0;
			$statusChange->supportNotification($mem,$it,$email,$sta,$num,"statuschange");
		}
	}

	if ($sta == 2){
		$tmpquery2 = "UPDATE ".$tableCollab["support_requests"]." SET status='$sta',date_close='$dateheure' WHERE id = '$id'";
		connectSql("$tmpquery2");
	}else{
		$tmpquery2 = "UPDATE ".$tableCollab["support_requests"]." SET status='$sta',date_close='--' WHERE id = '$id'";
		connectSql("$tmpquery2");
	}
	
	headerFunction("../support/viewrequest.php?id=$id&".session_name()."=".session_id());
	exit;
}

if($action == "add"){
	$mes = convertData($mes);

	$tmpquery1 = "INSERT INTO ".$tableCollab["support_posts"]."(request_id,message,date,owner,project) VALUES('$id','$mes','$dateheure','$idSession','".$requestDetail->sr_project[0]."')";
	connectSql("$tmpquery1");
	$tmpquery = $tableCollab["support_posts"];
	last_id($tmpquery);

	$num = $lastId[0];
	unset($lastId);
	
	if($requestDetail->sr_user[0] != $idSession){
		if ($notifications == "true") {
			if ($mes != ""){
				$mem = $userDetail->mem_id[0];
				$it = $requestDetail->sr_id[0];
				$email = $userDetail->mem_email_work[0];
				$phaseStatusChange = new notification();
				$phaseStatusChange->supportNotification($mem,$it,$email,$sta,$num,"response");
			}
		}
	}
	
	headerFunction("../support/viewrequest.php?id=$id&".session_name()."=".session_id());
	exit;
}


if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/header.php");
} else {
	include("../themes/".THEME."/header.php");
}

$blockPage = new block();
$blockPage->openBreadcrumbs();

if ($supportType == "team") {
	$blockPage->itemBreadcrumbs($blockPage->buildLink("../projects/listprojects.php?",$strings["projects"],in));
	$blockPage->itemBreadcrumbs($blockPage->buildLink("../projects/viewproject.php?id=".$requestDetail->sr_project[0],$requestDetail->sr_pro_name[0],in));
	$blockPage->itemBreadcrumbs($blockPage->buildLink("../support/listrequests.php?id=".$requestDetail->sr_project[0],$strings["support_requests"],in));
	$blockPage->itemBreadcrumbs($blockPage->buildLink("../support/viewrequest.php?id=".$requestDetail->sr_id[0],$requestDetail->sr_subject[0],in));
	if ($action == "status") {
		$blockPage->itemBreadcrumbs($strings["edit_status"]);
	} else {
		$blockPage->itemBreadcrumbs($strings["add_support_response"]);
	}
} else if ($supportType == "admin") {
	$blockPage->itemBreadcrumbs($blockPage->buildLink("../administration/admin.php?",$strings["administration"],in));
	$blockPage->itemBreadcrumbs($blockPage->buildLink("../administration/support.php?",$strings["support_management"],in));
	$blockPage->itemBreadcrumbs($blockPage->buildLink("../support/listrequests.php?id=".$requestDetail->sr_project[0],$strings["support_requests"],in));
	$blockPage->itemBreadcrumbs($blockPage->buildLink("../support/viewrequest.php?id=".$requestDetail->sr_id[0],$requestDetail->sr_subject[0],in));
	if ($action == "status"){
		$blockPage->itemBreadcrumbs($strings["edit_status"]);
	} else {
		$blockPage->itemBreadcrumbs($strings["add_support_response"]);
	}
}
$blockPage->closeBreadcrumbs();

if ($msg != "") {
if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/includes/messages.php");
} else {
	include("../includes/messages.php");
}
	$blockPage->messagebox($msgLabel);
}


$block2 = new block();

	$block2->form = "sr";
	if ($action == "status"){
		$block2->openForm("../support/addpost.php?action=edit&amp;id=$id&amp;".session_name()."=".session_id()."#".$block2->form."Anchor");
	}else{
		$block2->openForm("../support/addpost.php?action=add&amp;id=$id&amp;".session_name()."=".session_id()."#".$block2->form."Anchor");
	}
if ($error != "") {            
	$block2->headingError($strings["errors"]);
	$block2->contentError($error);
}

$block2->heading($strings["add_support_respose"]);

$block2->openContent();
$block2->contentTitle($strings["details"]);
if ($action == "status"){
echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["status"]." :</td><td><select name=\"sta\">";

$comptSta = count($requestStatus);
for ($i=0;$i<$comptSta;$i++) {
	if ($requestDetail->sr_status[0] == $i) {
		echo "<option value=\"$i\" selected>$requestStatus[$i]</option>";
	}else{
		echo "<option value=\"$i\">$requestStatus[$i]</option>";
	}
}
echo "</select></td></tr>";
}else{
echo"<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["message"]."</td><td><textarea rows=\"3\" style=\"width: 400px; height: 200px;\" name=\"mes\" cols=\"43\">$mes</textarea></td></tr>
<input type=\"hidden\" name=\"user\" value=\"$idSession\">";
}
echo"<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">&nbsp;</td><td><input type=\"SUBMIT\" value=\"".$strings["submit"]."\"></td></tr>";

$block2->closeContent();

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>