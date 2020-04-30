<?php
#Application name: PhpCollab
#Status page: 0
#Path by root: ../users/edituser.php

$checkSession = "true";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	$postnukeIntegration = "true";
	include("modules/PhpCollab/includes/library.php");
} else {
	$postnukeIntegration = "false";
	include("../includes/library.php");
}

if ($profilSession != "0") {
	headerFunction("../general/permissiondenied.php?".session_name()."=".session_id());
	exit;
}

//case update user
if ($id != "") {

if ($id == "1" && $idSession == "1") {
	headerFunction("../preferences/updateuser.php?".session_name()."=".session_id());
	exit;
}

//case update user
if ($action == "update") {
if ($htaccessAuth == "true") {
	include("../includes/htpasswd.class.php");
	$Htpasswd = new Htpasswd;
}
	if (!ereg("^[A-Za-z0-9]+$", $un)) {
		$error = $strings["alpha_only"];
	} else {

//test if login already exists
		$tmpquery = "WHERE mem.login = '$un' AND mem.login != '$unOld'";
		$existsUser = new request();
		$existsUser->openMembers($tmpquery);
		$comptExistsUser = count($existsUser->mem_id);
			if ($comptExistsUser != "0") {
				$error = $strings["user_already_exists"];
			} else {

//replace quotes by html code in name and address
	$fn = convertData($fn);
	$tit = convertData($tit);
	$c = convertData($c);
	$em = convertData($em);
	$wp = convertData($wp);
	$hp = convertData($hp);
	$mp = convertData($mp);
	$fax = convertData($fax);

	$tmpquery = "UPDATE ".$tableCollab["members"]." SET login='$un',name='$fn',title='$tit',email_work='$em',phone_work='$wp',phone_home='$hp',mobile='$mp',fax='$fax',comments='$c',profil='$perm' WHERE id = '$id'";
	connectSql("$tmpquery");

if ($htaccessAuth == "true") {
	if ($un != $unOld) {
	$tmpquery = "WHERE tea.member = '$id'";
	$listProjects = new request();
	$listProjects->openTeams($tmpquery);
	$comptListProjects = count($listProjects->tea_id);

		if ($comptListProjects != "0") {
			for ($i=0;$i<$comptListProjects;$i++) {
				$Htpasswd->initialize("../files/".$listProjects->tea_pro_id[$i]."/.htpasswd");
				$Htpasswd->renameUser($unOld,$un);
			}
		}
	}
}

//test if new password set
		if ($pw !=  "") {

//test if 2 passwords match
			if ($pw != $pwa || $pwa == "") {
				$error = $strings["new_password_error"];
			} else {
				$pw = get_password($pw);

if ($htaccessAuth == "true") {
	if ($un == $unOld) {
	$tmpquery = "WHERE tea.member = '$id'";
	$listProjects = new request();
	$listProjects->openTeams($tmpquery);
	$comptListProjects = count($listProjects->tea_id);
	}

	if ($comptListProjects != "0") {
		for ($i=0;$i<$comptListProjects;$i++) {
			$Htpasswd->initialize("../files/".$listProjects->tea_pro_id[$i]."/.htpasswd");
			$Htpasswd->changePass($un,$pw);
		}
	}
}
				$tmpquery = "UPDATE ".$tableCollab["members"]." SET password='$pw' WHERE id = '$id'";
				connectSql("$tmpquery");
//if mantis bug tracker enabled
				if ($enableMantis == "true") {
// Call mantis function for user changes..!!!
					$f_access_level = $team_user_level; // Developer
					include ("../mantis/user_update.php");				
				}

				headerFunction("../users/listusers.php?msg=update&".session_name()."=".session_id());
				exit;
			}
		} else {
//if mantis bug tracker enabled
			if ($enableMantis == "true") {
// Call mantis function for user changes..!!!
				$f_access_level = $team_user_level; // Developer
				include ("../mantis/user_update.php");				
			}
			headerFunction("../users/listusers.php?msg=update&".session_name()."=".session_id());
			exit;
		}
			}
	}
}
$tmpquery = "WHERE mem.id = '$id'";
$detailUser = new request();
$detailUser->openMembers($tmpquery);
$comptDetailUser = count($detailUser->mem_id);

//test exists selected user, redirect to list if not
	if ($comptDetailUser == "0") {
		headerFunction("../users/listusers.php?msg=blankUser&".session_name()."=".session_id());
		exit;
	}

//set values in form
$un = $detailUser->mem_login[0];
$fn = $detailUser->mem_name[0];
$tit = $detailUser->mem_title[0];
$em = $detailUser->mem_email_work[0];
$wp = $detailUser->mem_phone_work[0];
$hp = $detailUser->mem_phone_home[0];
$mp = $detailUser->mem_mobile[0];
$fax = $detailUser->mem_fax[0];
$c = $detailUser->mem_comments[0];
$perm = $detailUser->mem_profil[0];

//set radio button with permissions value
	if ($perm == "1") {
		$checked1 = "checked";
	}
	if ($perm == "2") {
		$checked2 = "checked";
	}
	if ($perm == "4") {
		$checked4 = "checked";
	}
	if ($perm == "5") {
		$checked5 = "checked";
	}
}

//case add user
if ($id == "") {
	$checked2 = "checked";

//case add user
	if ($action == "add") {
	if (!ereg("^[A-Za-z0-9]+$", $un)) {
		$error = $strings["alpha_only"];
	} else {

//test if login already exists
		$tmpquery = "WHERE mem.login = '$un'";
		$existsUser = new request();
		$existsUser->openMembers($tmpquery);
		$comptExistsUser = count($existsUser->mem_id);
			if ($comptExistsUser != "0") {
				$error = $strings["user_already_exists"];
			} else {

//test if 2 passwords match
				if ($pw != $pwa || $pw == "") {
					$error = $strings["new_password_error"];
				} else {

//replace quotes by html code in name and address
					$fn = convertData($fn);
					$tit = convertData($tit);
					$c = convertData($c);
					$pw = get_password($pw);
					$tmpquery1 = "INSERT INTO ".$tableCollab["members"]."(login,name,title,email_work,phone_work,phone_home,mobile,fax,comments,password,profil,created,organization,timezone) VALUES('$un','$fn','$tit','$em','$wp','$hp','$mp','$fax','$c','$pw','$perm','$dateheure','1','0')";
					connectSql("$tmpquery1");
					$tmpquery = $tableCollab["members"];
					last_id($tmpquery);
					$num = $lastId[0];
					unset($lastId);
					$tmpquery2 = "INSERT INTO ".$tableCollab["sorting"]."(member) VALUES('$num')";
					connectSql("$tmpquery2");
					$tmpquery3 = "INSERT INTO ".$tableCollab["notifications"]."(member,taskAssignment,removeProjectTeam,addProjectTeam,newTopic,newPost,statusTaskChange,priorityTaskChange,duedateTaskChange,clientAddTask) VALUES ('$num','0','0','0','0','0','0','0','0','0')";
					connectSql("$tmpquery3");
//if mantis bug tracker enabled
					if ($enableMantis == "true") {
// Call mantis function for user changes..!!!
						$f_access_level = $team_user_level; // Developer
						include ("../mantis/create_new_user.php");				
					}
					headerFunction("../users/listusers.php?msg=add&".session_name()."=".session_id());
					exit;
				}
			}
	}
	}
}

$bodyCommand = "onLoad=\"document.user_editForm.un.focus();\"";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/header.php");
} else {
	include("../themes/".THEME."/header.php");
}

$blockPage = new block();
$blockPage->openBreadcrumbs();
$blockPage->itemBreadcrumbs($blockPage->buildLink("../administration/admin.php?",$strings["administration"],in));
$blockPage->itemBreadcrumbs($blockPage->buildLink("../users/listusers.php?",$strings["user_management"],in));

if ($id == "") {
	$blockPage->itemBreadcrumbs($strings["add_user"]);
}
if ($id != "") {
	$blockPage->itemBreadcrumbs($blockPage->buildLink("../users/viewuser.php?id=$id",$detailUser->mem_login[0],in));
	$blockPage->itemBreadcrumbs($strings["edit_user"]);
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

if ($id == "") {
	$block1->form = "user_edit";
	$block1->openForm("../users/edituser.php?id=$id&amp;action=add&amp;".session_name()."=".session_id()."#".$block1->form."Anchor");
}
if ($id != "") {
	$block1->form = "user_edit";
	$block1->openForm("../users/edituser.php?id=$id&amp;action=update&amp;".session_name()."=".session_id()."#".$block1->form."Anchor");
}

if ($error != "") {            
	$block1->headingError($strings["errors"]);
	$block1->contentError($error);
}

if ($id == "") {
	$block1->heading($strings["add_user"]);
}
if ($id != "") {
	$block1->heading($strings["edit_user"]." : ".$detailUser->mem_login[0]);
}

$block1->openContent();

if ($id == "") {
	$block1->contentTitle($strings["enter_user_details"]);
}
if ($id != "") {
	$block1->contentTitle($strings["edit_user_details"]);
}

echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["user_name"]." :</td><td><input size=\"24\" style=\"width: 250px;\" maxlength=\"16\" type=\"text\" name=\"un\" value=\"$un\"><input type=\"hidden\" name=\"unOld\" value=\"$un\"></td>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["full_name"]." :</td><td><input size=\"24\" style=\"width: 250px;\" maxlength=\"64\" type=\"text\" name=\"fn\" value=\"$fn\"></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["title"]." :</td><td><input size=\"24\" style=\"width: 250px;\" maxlength=\"128\" type=\"text\" name=\"tit\" value=\"$tit\"></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["email"]." :</td><td><input size=\"24\" style=\"width: 250px;\" maxlength=\"128\" type=\"text\" name=\"em\" value=\"$em\"></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["work_phone"]." :</td><td><input size=\"14\" style=\"width: 150px;\" maxlength=\"32\" type=\"text\" name=\"wp\" value=\"$wp\"></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["home_phone"]." :</td><td><input size=\"14\" style=\"width: 150px;\" maxlength=\"32\" type=\"text\" name=\"hp\" value=\"$hp\"></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["mobile_phone"]." :</td><td><input size=\"14\" style=\"width: 150px;\" maxlength=\"32\" type=\"text\" name=\"mp\" value=\"$mp\"></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["fax"]." :</td><td><input size=\"14\" style=\"width: 150px;\" maxlength=\"32\" type=\"text\" name=\"fax\" value=\"$fax\"></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["comments"]." :</td><td><textarea style=\"width: 350px; height: 60px;\" name=\"c\" cols=\"45\" rows=\"5\">$c</textarea></td></tr>";

if ($id == "") {
	$block1->contentTitle($strings["enter_password"]);
}
if ($id != "") {
	$block1->contentTitle($strings["change_password_user"]);
}

echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["password"]." :</td><td><input size=\"24\" style=\"width: 250px;\" maxlength=\"15\" type=\"password\" name=\"pw\" value=\"\"></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["confirm_password"]." :</td><td><input size=\"24\" style=\"width: 250px;\" maxlength=\"16\" type=\"password\" name=\"pwa\" value=\"\"></td></tr>";

$block1->contentTitle($strings["select_permissions"]);

echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\"><input type=\"radio\" name=\"perm\" value=\"1\" $checked1></td><td><b>".$strings["project_manager_permissions"]."</b></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\"><input type=\"radio\" name=\"perm\" value=\"2\" $checked2></td><td><b>".$strings["user_permissions"]."</b></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\"><input type=\"radio\" name=\"perm\" value=\"4\" $checked4></td><td><b>".$strings["disabled_permissions"]."</b></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\"><input type=\"radio\" name=\"perm\" value=\"5\" $checked5></td><td><b>".$strings["project_manager_administrator_permissions"]."</b></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">&nbsp;</td><td><input type=\"submit\" name=\"Save\" value=\"".$strings["save"]."\"></td></tr>";

$block1->closeContent();
$block1->closeForm();

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>