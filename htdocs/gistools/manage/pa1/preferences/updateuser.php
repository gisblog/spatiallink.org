<?php
#Application name: PhpCollab
#Status page: 0
#Path by root: ../preferences/updateuser.php

$checkSession = "true";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	$postnukeIntegration = "true";
	include("modules/PhpCollab/includes/library.php");
} else {
	$postnukeIntegration = "false";
	include("../includes/library.php");
}

if ($action == "update") {
	if (($logout_time < "30" && $logout_time != "0") || !is_numeric($logout_time)) {
		$logout_time = "30";
	}
	$fn = convertData($fn);
	$tit = convertData($tit);
	$em = convertData($em);
	$wp = convertData($wp);
	$hp = convertData($hp);
	$mp = convertData($mp);
	$fax = convertData($fax);
	$logout_time = convertData($logout_time);
	$tmpquery = "UPDATE ".$tableCollab["members"]." SET name='$fn',title='$tit',email_work='$em',phone_work='$wp',phone_home='$hp',mobile='$mp',fax='$fax',logout_time='$logout_time',timezone='$tz' WHERE id = '$idSession'";
	connectSql("$tmpquery");
	$timezoneSession = $tz;
	$logouttimeSession = $logout_time;
	$dateunixSession = date("U");
	$nameSession = $fn;
	session_register("logouttimeSession","timezoneSession","dateunixSession","nameSession");

//if mantis bug tracker enabled
		if ($enableMantis == "true") {
// Call mantis function for user profile changes..!!!
			include ("../mantis/user_profile.php");				
		}
	headerFunction("../preferences/updateuser.php?msg=update&".session_name()."=".session_id());
}

$tmpquery = "WHERE mem.id = '$idSession'";
$userPrefs = new request();
$userPrefs->openMembers($tmpquery);
$comptUserPrefs = count($userPrefs->mem_id);

if ($comptUserPrefs == "0") {
	headerFunction("../users/listusers.php?msg=blankUser&".session_name()."=".session_id());
	exit;
}

$bodyCommand = "onLoad=\"document.user_edit_profileForm.fn.focus();\"";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/header.php");
} else {
	include("../themes/".THEME."/header.php");
}

$blockPage = new block();
$blockPage->openBreadcrumbs();
$blockPage->itemBreadcrumbs($strings["preferences"]);
if ($notifications == "true") {
$blockPage->itemBreadcrumbs($strings["user_profile"]." | ".$blockPage->buildLink("../preferences/updatepassword.php?",$strings["change_password"],in)." | ".$blockPage->buildLink("../preferences/updatenotifications.php?",$strings["notifications"],in));
} else {
$blockPage->itemBreadcrumbs($strings["user_profile"]." | ".$blockPage->buildLink("../preferences/updatepassword.php?",$strings["change_password"],in));
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

$block1 = new block();

$block1->form = "user_edit_profile";
$block1->openForm("../preferences/updateuser.php?".session_name()."=".session_id());
echo "<input type=\"hidden\" name=\"action\" value=\"update\">";

if ($error != "") {            
	$block1->headingError($strings["errors"]);
	$block1->contentError($error);
}

$block1->heading($strings["user_profile"]." : ".$userPrefs->mem_login[0]);

$block1->openPaletteIcon();
$block1->paletteIcon(0,"export",$strings["export"]);
$block1->closePaletteIcon();

$block1->openContent();
$block1->contentTitle($strings["edit_user_account"]);

echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["full_name"]." :</td><td><input size=\"24\" style=\"width: 250px;\" type=\"text\" name=\"fn\" value=\"".$userPrefs->mem_name[0]."\"></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["title"]." :</td><td><input size=\"24\" style=\"width: 250px;\" type=\"text\" name=\"tit\" value=\"".$userPrefs->mem_title[0]."\"></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["email"]." :</td><td><input size=\"24\" style=\"width: 250px;\" type=\"text\" name=\"em\" value=\"".$userPrefs->mem_email_work[0]."\"></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["work_phone"]." :</td><td><input size=\"14\" style=\"width: 150px;\" type=\"text\" name=\"wp\" value=\"".$userPrefs->mem_phone_work[0]."\"></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["home_phone"]." :</td><td><input size=\"14\" style=\"width: 150px;\" type=\"text\" name=\"hp\" value=\"".$userPrefs->mem_phone_home[0]."\"></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["mobile_phone"]." :</td><td><input size=\"14\" style=\"width: 150px;\" type=\"text\" name=\"mp\" value=\"".$userPrefs->mem_mobile[0]."\"></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["fax"]." :</td><td><input size=\"14\" style=\"width: 150px;\" type=\"text\" name=\"fax\" value=\"".$userPrefs->mem_fax[0]."\"></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["logout_time"].$blockPage->printHelp("user_autologout")." :</td><td><input size=\"14\" style=\"width: 150px;\" type=\"text\" name=\"logout_time\" value=\"".$userPrefs->mem_logout_time[0]."\"> sec.</td></tr>";

if ($gmtTimezone == "true") {
echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["user_timezone"].$blockPage->printHelp("user_timezone")." :</td><td><select name=\"tz\">";
	for ($i=-12;$i<=+12;$i++) {
		if ($userPrefs->mem_timezone[0] == $i) {
			echo "<option value=\"$i\" selected>$i</option>";
		} else {
			echo "<option value=\"$i\">$i</option>";
		}
	}
echo "</select></td></tr>";
}

echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["permissions"]." :</td><td>";

if ($userPrefs->mem_profil[0] == "0") {
	echo $strings["administrator_permissions"];
} else if ($userPrefs->mem_profil[0] == "1") {
	echo $strings["project_manager_permissions"];
} else if ($userPrefs->mem_profil[0] == "2") {
	echo $strings["user_permissions"];
} else if ($userPrefs->mem_profil[0] == "5") { 
	echo $strings["project_manager_administrator_permissions"]; 
}

echo "</td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["account_created"]." :</td><td>".createDate($userPrefs->mem_created[0],$timezoneSession)."</td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">&nbsp;</td><td><input type=\"submit\" name=\"Save\" value=\"".$strings["save"]."\"></td></tr>";

$block1->closeContent();
$block1->closeForm();

$block1->openPaletteScript();
$block1->paletteScript(0,"export","../users/exportuser.php?id=$idSession","true,true,true",$strings["export"]);
$block1->closePaletteScript("","");

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>