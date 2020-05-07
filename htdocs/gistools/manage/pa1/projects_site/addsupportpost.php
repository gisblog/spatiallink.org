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

include ("../includes/notification.class.php");

$tmpquery = "WHERE sr.id = '$id'";
$requestDetail = new request();
$requestDetail->openSupportRequests($tmpquery);

if ($requestDetail->sr_project[0] != $projectSession || $requestDetail->sr_user[0] != $idSession) {
headerFunction("index.php");
}

$tmpquery = "WHERE mem.id = '".$requestDetail->sr_user[0]."'";
$userDetail = new request();
$userDetail->openMembers($tmpquery);

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
	
	headerFunction("suprequestdetail.php?id=$id&".session_name()."=".session_id());
	exit;
}


$bouton[6] = "over";
$titlePage = $strings["support"];
include ("include_header.php");

echo "<form accept-charset=\"UNKNOWN\" method=\"POST\" action=\"../projects_site/addsupportpost.php?id=$id&amp;".session_name()."=".session_id()."&amp;action=add&amp;project=$projectSession#filedetailsAnchor\" name=\"addsupport\" enctype=\"multipart/form-data\">";

echo "<table cellspacing=\"0\" width=\"90%\" border=\"0\" cellpadding=\"3\">
<tr><th colspan=\"2\">".$strings["add_support_response"]."</th></tr>
<tr><th>".$strings["message"]."</th><td><textarea rows=\"3\" style=\"width: 400px; height: 200px;\" name=\"mes\" cols=\"43\">$mes</textarea></td></tr>
<input type=\"hidden\" name=\"user\" value=\"$idSession\">";

echo "<tr><th>&nbsp;</th><td><input type=\"SUBMIT\" value=\"".$strings["submit"]."\"></td></tr>
</table>
</form>";

include ("include_footer.php");
?>