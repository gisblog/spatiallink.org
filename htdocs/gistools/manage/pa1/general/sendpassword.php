<?php
#Application name: PhpCollab
#Status page: 0
#Path by root: ../general/sendpassword.php

$checkSession = "false";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	$postnukeIntegration = "true";
	include("modules/PhpCollab/includes/library.php");
} else {
	$postnukeIntegration = "false";
	include("../includes/library.php");
}

//test send query
if ($action == "send") {
$footer = "---
".$strings["noti_foot1"]."\n
".$strings["noti_foot2"]."
$root/";
	$tmpquery = "WHERE mem.login = '$loginForm'";
	$userDetail = new request();
	$userDetail->openMembers($tmpquery);
	$comptUserDetail = count ($userDetail->mem_id);

//test if user exists
		if ($comptUserDetail == "0") {
			$error = $strings["no_login"];

//test if email of user exists
		} else if ($userDetail->mem_email_work[0] != "") {
			password_generator();
			$pw = get_password($pass_g);
			$tmpquery = "UPDATE ".$tableCollab["members"]." SET password='$pw' WHERE login = '$loginForm'";
			connectSql("$tmpquery");
			$message .= $strings["user_name"]." : ".$userDetail->mem_login[0]."\n\n".$strings["password"]." : $pass_g\n\n".$footer; 
			$headers = "Content-type:text/plain;charset=\"iso-8859-1\"\nX-Priority: 1\nX-Mailer: PhpCollab $version"; 
			$subject = "PhpCollab ".$strings["password"];
			mail($userDetail->mem_email_work[0], $subject, $message, $headers) or die("Error Mail server"); 
			$msg = "email_pwd";
		} else {
			$error = $strings["no_email"];
		}
	$send = "on";
}

$notLogged = "true";
$bodyCommand = "onLoad=\"document.sendForm.loginForm.focus();\"";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/header.php");
} else {
	include("../themes/".THEME."/header.php");
}

$blockPage = new block();
$blockPage->openBreadcrumbs();
$blockPage->itemBreadcrumbs("&nbsp;");
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

$block1->form = "send";
$block1->openForm("../general/sendpassword.php?action=send&amp;".session_name()."=".session_id());

if ($error != "") {            
	$block1->headingError($strings["errors"]);
	$block1->contentError($error);
}

$block1->heading("PhpCollab : ".$strings["password"]);

$block1->openContent();
$block1->contentTitle($strings["enter_login"]);

echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">* ".$strings["user_name"]."</td><td><input style=\"width: 125px\" maxlength=\"16\" size=\"16\" value=\"$loginForm\" type=\"text\" name=\"loginForm\"></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">&nbsp;</td><td><input type=submit name=\"send\" value=\"".$strings["send"]."\"></td></tr>";

$block1->closeContent();
$block1->closeForm();

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>