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

if ($enable_cvs == "true") {
if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/includes/cvslib.php");
} else {
	include("../includes/cvslib.php");
}
}

if ($action == "update") {
$r = substr($opw, 0, 2); 
$opw = crypt($opw, $r);
if ($opw != $passwordSession) {
	$error = $strings["old_password_error"];
} else {
	if ($npw != $pwa || $npw == "") {
		$error = $strings["new_password_error"];
	} else {
		$cnpw = get_password($npw);

if ($htaccessAuth == "true") {
	include("includes/htpasswd.class.php");
	$Htpasswd = new Htpasswd;
	$tmpquery = "WHERE tea.member = '$idSession'";
	$listProjects = new request();
	$listProjects->openTeams($tmpquery);
	$comptListProjects = count($listProjects->tea_id);

	if ($comptListProjects != "0") {
		for ($i=0;$i<$comptListProjects;$i++) {
			$Htpasswd->initialize("files/".$listProjects->tea_pro_id[$i]."/.htpasswd");
			$Htpasswd->changePass($loginSession,$cnpw);
		}
	}
}

		$tmpquery = "UPDATE ".$tableCollab["members"]." SET password='$cnpw' WHERE id = '$idSession'";
		connectSql("$tmpquery");

//if CVS repository enabled
		if ($enable_cvs == "true") {
			$query = "WHERE tea.member = '$idSession'";
			$cvsMembers = new request();
			$cvsMembers->openTeams($query);

//change the password in every repository
			for ($i=0;$i<(count($cvsMembers->tea_id));$i++) {
				cvs_change_password($cvsMembers->tea_mem_login[$i], $cnpw, $cvsMembers->tea_pro_id[$i]);
			}
		}
		$r = substr($npw, 0, 2); 
		$npw = crypt($npw, $r);
		$passwordSession = $npw;
		session_register("passwordSession");
		headerFunction("changepassword.php?msg=update&".session_name()."=".session_id());
		exit;
	}
}
}

$tmpquery = "WHERE mem.id = '$idSession'";
$userDetail = new request();
$userDetail->openMembers($tmpquery);
$comptUserDetail = count($userDetail->mem_id);

if ($comptUserDetail == "0") {
	headerFunction("userlist.php?msg=blankUser&".session_name()."=".session_id());
	exit;
}

$titlePage = $strings["change_password"];
include ("include_header.php");

if ($msg != "") {
if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/includes/messages.php");
} else {
	include("../includes/messages.php");
}
	$blockPage->messagebox($msgLabel);
}

echo "<form accept-charset=\"UNKNOWN\" method=\"POST\" action=\"../projects_site/changepassword.php?".session_name()."=".session_id()."&amp;action=update\" name=\"changepassword\" enctype=\"application/x-www-form-urlencoded\">";

echo "<table cellspacing=\"0\" width=\"90%\" border=\"0\" cellpadding=\"3\">
<tr><th colspan=\"2\">".$strings["change_password"]."</th></tr>
<tr><th>*&nbsp;".$strings["old_password"]." :</th><td><input style=\"width: 150px;\" type=\"password\" name=\"opw\" value=\"\"></td></tr>
<tr><th>*&nbsp;".$strings["new_password"]." :</th><td><input style=\"width: 150px;\" type=\"password\" name=\"npw\" value=\"\"></td></tr>
<tr><th>*&nbsp;".$strings["confirm_password"]." :</th><td><input style=\"width: 150px;\" type=\"password\" name=\"pwa\" value=\"\"></td></tr>
<tr><th>&nbsp;</th><td colspan=\"2\"><input name=\"submit\" type=\"submit\" value=\"".$strings["save"]."\"><br><br>$error</td></tr>
</table>
</form>";

include ("include_footer.php");
?>