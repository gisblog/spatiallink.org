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
include("../includes/notification.class.php");

$tmpquery = "WHERE mem.id = '$idSession'";
$userDetail = new request();
$userDetail->openMembers($tmpquery);

$tmpquery = "WHERE sr.member = '$idSession'";
$listRequests = new request();
$listRequests->openSupportRequests($tmpquery);
$comptListRequests = count($listRequests->sr_id);

if ($action == "add") {
	$sub = convertData($sub);
	$mes = convertData($mes);

	$tmpquery1 = "INSERT INTO ".$tableCollab["support_requests"]."(member,priority,subject,message,date_open,project,status) VALUES('$user','$pr','$sub','$mes','$dateheure','$project','0')";
	connectSql("$tmpquery1");
	$tmpquery = $tableCollab["support_requests"];
	last_id($tmpquery);
	$num = $lastId[0];
	unset($lastId);
	
	if ($notifications == "true") {
		if($supportType == "team"){
			
			$tmpquery = "WHERE tea.project = '$project'";
			$listTeam = new request();
			$listTeam->openTeams($tmpquery);
			$comptListTeam = count($listTeam->tea_id);
		
			for ($i=0;$i<$comptListTeam;$i++) {
			
					$mem = $listTeam->tea_mem_id[$i];
					$it = $num;
					$email = $listTeam->tea_mem_email_work[$i];
					$newSupport = new notification();
					if($idSession == $listTeam->tea_mem_id[$i]){
						$newSupport->supportNotification($mem,$it,$email,$sta,$num,"newrequest");
					}else{
						$newSupport->supportNotification($mem,$it,$email,$sta,$num,"newrequestTeam");
					}
			}
		
		}else{
			$mem = $userDetail->mem_id[0];
			$it = $num;
			$email = $userDetail->mem_email_work[0];
			$newSupport = new notification();
			$newSupport->supportNotification($mem,$it,$email,$sta,$num,"newrequest");
		}
	}	
	
	headerFunction("suprequestdetail.php?id=$num&".session_name()."=".session_id());
	exit;
}

$bouton[6] = "over";
$titlePage = $strings["support"];
include ("include_header.php");

echo "<form accept-charset=\"UNKNOWN\" method=\"POST\" action=\"../projects_site/addsupport.php?".session_name()."=".session_id()."&amp;action=add&amp;project=$projectSession#filedetailsAnchor\" name=\"addsupport\" enctype=\"multipart/form-data\">";

echo "<table cellspacing=\"0\" width=\"90%\" border=\"0\" cellpadding=\"3\">
<tr><th colspan=\"2\">".$strings["add_support_request"]."</th></tr>
<tr><th>".$strings["priority"]." :</th><td><select name=\"pr\">";

$comptPri = count($priority);
for ($i=0;$i<$comptPri;$i++) {
	if($i != 0){
		echo "<option value=\"$i\">$priority[$i]</option>";
	}
}
echo "</select></td></tr>
<tr><th>".$strings["subject"]."</th><td><input size=\"32\" value=\"$sub\" style=\"width: 250px\" name=\"sub\" maxlength=\"32\" type=\"TEXT\"></td></tr>
<tr><th>".$strings["message"]."</th><td><textarea rows=\"3\" style=\"width: 400px; height: 200px;\" name=\"mes\" cols=\"43\">$mes</textarea></td></tr>
<input type=\"hidden\" name=\"user\" value=\"$idSession\">
<tr><th>&nbsp;</th><td><input type=\"SUBMIT\" value=\"".$strings["submit"]."\"></td></tr>
</table>
</form>";

include ("include_footer.php");
?>