<?php
#Application name: PhpCollab
#Status page: 0
#Path by root: ../users/addclientuser.php

$checkSession = "true";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	$postnukeIntegration = "true";
	include("modules/PhpCollab/includes/library.php");
} else {
	$postnukeIntegration = "false";
	include("../includes/library.php");
}

$tmpquery = "WHERE org.id = '$organization'";
$clientDetail = new request();
$clientDetail->openOrganizations($tmpquery);
$comptClientDetail = count($clientDetail->org_id);

//case add client user

//test if login already exists
if ($action == "add") {
if (!ereg("^[A-Za-z0-9]+$", $un)) {
	$error = $strings["alpha_only"];
} else {
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
				$tmpquery1 = "INSERT INTO ".$tableCollab["members"]."(organization,login,name,title,email_work,phone_work,phone_home,mobile,fax,comments,password,profil,created,timezone) VALUES('$clod','$un','$fn','$tit','$em','$wp','$hp','$mp','$fax','$c','$pw','3','$dateheure','0')";
				connectSql("$tmpquery1");
				$tmpquery = $tableCollab["members"];
				last_id($tmpquery);
				$num = $lastId[0];
				unset($lastId);
				$tmpquery3 = "INSERT INTO ".$tableCollab["notifications"]."(member,taskAssignment,removeProjectTeam,addProjectTeam,newTopic,newPost,statusTaskChange,priorityTaskChange,duedateTaskChange,clientAddTask) VALUES ('$num','0','0','0','0','0','0','0','0','0')";
				connectSql("$tmpquery3");
//if mantis bug tracker enabled
				if ($enableMantis == "true") {
// Call mantis function for new user creation!!!
					$f_access_level = $client_user_level; // Reporter
					include ("../mantis/create_new_user.php");
				}
				headerFunction("../clients/viewclient.php?id=$clod&msg=add&".session_name()."=".session_id());
				exit;
			}
		}
}
}

$bodyCommand = "onLoad=\"document.client_user_addForm.un.focus();\"";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/header.php");
} else {
	include("../themes/".THEME."/header.php");
}

$blockPage = new block();
$blockPage->openBreadcrumbs();
$blockPage->itemBreadcrumbs($blockPage->buildLink("../clients/listclients.php?",$strings["clients"],in));
$blockPage->itemBreadcrumbs($blockPage->buildLink("../clients/viewclient.php?id=".$clientDetail->org_id[0],$clientDetail->org_name[0],in));
$blockPage->itemBreadcrumbs($strings["add_client_user"]);
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

$block1->form = "client_user_add";
$block1->openForm("../users/addclientuser.php?organization=$organization&action=add&amp;".session_name()."=".session_id());

if ($error != "") {            
	$block1->headingError($strings["errors"]);
	$block1->contentError($error);
}

$block1->heading($strings["add_client_user"]);

$block1->openContent();
$block1->contentTitle($strings["enter_user_details"]);

echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["user_name"]." :</td><td><input size=\"24\" style=\"width: 250px;\" maxlength=\"16\" type=\"text\" name=\"un\" value=\"$un\"></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["full_name"]." :</td><td><input size=\"24\" style=\"width: 250px;\" maxlength=\"64\" type=\"text\" name=\"fn\" value=\"$fn\"></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["title"]." :</td><td><input size=\"24\" style=\"width: 250px;\" maxlength=\"64\" type=\"text\" name=\"tit\" value=\"$tit\"></td>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["organization"]." :</td><td><select name=\"clod\">";
                
$tmpquery = "WHERE org.id != '1' ORDER BY org.name";
$listOrganizations = new request();
$listOrganizations->openOrganizations($tmpquery);
$comptListOrganizations = count($listOrganizations->org_id);

for ($i=0;$i<$comptListOrganizations;$i++) {
	if ($organization == $listOrganizations->org_id[$i]) {
		echo "<option value=\"".$listOrganizations->org_id[$i]."\" selected>".$listOrganizations->org_name[$i]."</option>";
	} else {
		echo "<option value=\"".$listOrganizations->org_id[$i]."\">".$listOrganizations->org_name[$i]."</option>";
	}
}

echo "</select></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["email"]." :</td><td><input size=\"24\" style=\"width: 250px;\" maxlength=\"128\" type=\"text\" name=\"em\" value=\"$em\"></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["work_phone"]." :</td><td><input size=\"14\" style=\"width: 150px;\" maxlength=\"32\" type=\"text\" name=\"wp\" value=\"$wp\"></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["home_phone"]." :</td><td><input size=\"14\" style=\"width: 150px;\" maxlength=\"32\" type=\"text\" name=\"hp\" value=\"$hp\"></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["mobile_phone"]." :</td><td><input size=\"14\" style=\"width: 150px;\" maxlength=\"32\" type=\"text\" name=\"mp\" value=\"$mp\"></td>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["fax"]." :</td><td><input size=\"14\" style=\"width: 150px;\" maxlength=\"32\" type=\"text\" name=\"fax\" value=\"$fax\"></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["comments"]." :</td><td><textarea style=\"width: 400px; height: 50px;\" name=\"c\" cols=\"35\" rows=\"2\">$c</textarea></td></tr>";

$block1->contentTitle($strings["enter_password"]);

echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["password"]." :</td><td><input size=\"24\" style=\"width: 250px;\" maxlength=\"16\" type=\"password\" name=\"pw\" value=\"\"></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["confirm_password"]." :</td><td><input size=\"24\" style=\"width: 250px;\" maxlength=\"16\" type=\"password\" name=\"pwa\" value=\"\"></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">&nbsp;</td><td><input type=\"submit\" name=\"Save\" value=\"".$strings["save"]."\"></td></tr>";

$block1->closeContent();
$block1->closeForm();

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>