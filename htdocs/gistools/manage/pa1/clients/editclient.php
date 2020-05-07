<?php
#Application name: PhpCollab
#Status page: 0
#Path by root: ../clients/editclient.php

$checkSession = "true";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	$postnukeIntegration = "true";
	include("modules/PhpCollab/includes/library.php");
} else {
	$postnukeIntegration = "false";
	include("../includes/library.php");
}

//case update client organization
if ($id != "") {

//test exists selected client organization, redirect to list if not
	$tmpquery = "WHERE org.id = '$id'";
	$clientDetail = new request();
	$clientDetail->openOrganizations($tmpquery);
	$comptClientDetail = count($clientDetail->org_id);
		if ($comptClientDetail == "0") {
			headerFunction("../clients/listclients.php?msg=blankClient&".session_name()."=".session_id());
			exit;
		}
}

//case update client organization
if ($id != "") {
	if ($action == "update") {
if ($logoDel == "on") {
	$tmpquery = "UPDATE ".$tableCollab["organizations"]." SET extension_logo='' WHERE id='$id'";
	connectSql("$tmpquery");
	@unlink("../logos_clients/".$id.".$extensionOld");
}
	$extension = strtolower( substr( strrchr($HTTP_POST_FILES['upload']['name'], ".") ,1) );
	if(@move_uploaded_file($HTTP_POST_FILES['upload']['tmp_name'], "../logos_clients/".$id.".$extension")) {
		$tmpquery = "UPDATE ".$tableCollab["organizations"]." SET extension_logo='$extension' WHERE id='$id'";
		connectSql("$tmpquery");
	}

//replace quotes by html code in name and address
		$cn = convertData($cn);
		$add = convertData($add);
		$c = convertData($c);
		$tmpquery = "UPDATE ".$tableCollab["organizations"]." SET name='$cn',address1='$add',phone='$wp',url='$url',email='$email',comments='$c',owner='$cown' WHERE id = '$id'";
		connectSql("$tmpquery");
		headerFunction("../clients/viewclient.php?id=$id&msg=update&".session_name()."=".session_id());
	}

//set value in form
$cn = $clientDetail->org_name[0];
$add = $clientDetail->org_address1[0];
$wp = $clientDetail->org_phone[0];
$url = $clientDetail->org_url[0];
$email = $clientDetail->org_email[0];
$c = $clientDetail->org_comments[0];
}

//case add client organization
if ($id == "") {
	if ($action == "add") {

//test if name blank
		if ($cn == "") {
			$error = $strings["blank_organization_field"];
		} else {

//replace quotes by html code in name and address
					$cn = convertData($cn);



					$add = convertData($add);
					$c = convertData($c);
//test if name already exists
			$tmpquery = "WHERE org.name = '$cn'";
			$existsClient = new request();
			$existsClient->openOrganizations($tmpquery);
			$comptExistsClient = count($existsClient->org_id);
				if ($comptExistsClient!= "0") {
					$error = $strings["organization_already_exists"];
				} else {

//insert into organizations and redirect to new client organization detail (last id)
					$tmpquery1 = "INSERT INTO ".$tableCollab["organizations"]."(name,address1,phone,url,email,comments,created,owner) VALUES('$cn','$add','$wp','$url','$email','$c','$dateheure','$cown')";

					connectSql("$tmpquery1");
					$tmpquery = $tableCollab["organizations"];
					last_id($tmpquery);
					$num = $lastId[0];
					unset($lastId);
	$extension = strtolower( substr( strrchr($upload_name, ".") ,1) );
	if (@move_uploaded_file($upload, "../logos_clients/".$num.".$extension")) {
		$tmpquery = "UPDATE ".$tableCollab["organizations"]." SET extension_logo='$extension' WHERE id='$num'";
		connectSql("$tmpquery");
	}
					headerFunction("../clients/viewclient.php?id=$num&msg=add&".session_name()."=".session_id());
				}
		}
	}
}

$bodyCommand = "onLoad=\"document.ecDForm.cn.focus();\"";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/header.php");
} else {
	include("../themes/".THEME."/header.php");
}

$blockPage = new block();
$blockPage->openBreadcrumbs();
$blockPage->itemBreadcrumbs($blockPage->buildLink("../clients/listclients.php?",$strings["clients"],in));

if ($id == "") {
	$blockPage->itemBreadcrumbs($strings["add_organization"]);
}
if ($id != "") {
	$blockPage->itemBreadcrumbs($blockPage->buildLink("../clients/viewclient.php?id=".$clientDetail->org_id[0],$clientDetail->org_name[0],in));
	$blockPage->itemBreadcrumbs($strings["edit_organization"]);
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
echo "<a name=\"".$this->form."Anchor\"></a>\n
<form accept-charset=\"UNKNOWN\" method=\"POST\" action=\"../clients/editclient.php?action=add&amp;".session_name()."=".session_id()."\" name=\"ecDForm\" enctype=\"multipart/form-data\"><input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"100000000\">\n";

}
if ($id != "") {
echo "<a name=\"".$this->form."Anchor\"></a>\n
<form accept-charset=\"UNKNOWN\" method=\"POST\" action=\"../clients/editclient.php?id=$id&amp;action=update&amp;".session_name()."=".session_id()."\" name=\"ecDForm\" enctype=\"multipart/form-data\"><input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"100000000\">\n";
}

if ($error != "") {            
	$block1->headingError($strings["errors"]);
	$block1->contentError($error);
}

if ($id == "") {
	$block1->heading($strings["add_organization"]);
}
if ($id != "") {
	$block1->heading($strings["edit_organization"]." : ".$clientDetail->org_name[0]);
}

$block1->openContent();
$block1->contentTitle($strings["details"]);

if ($clientsFilter == "true") {
echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["owner"]." :</td><td><select name=\"cown\">";

$tmpquery = "WHERE (mem.profil = '1' OR mem.profil = '0') AND mem.login != 'demo' ORDER BY mem.name";
$clientOwner = new request();
$clientOwner->openMembers($tmpquery);
$comptClientOwner = count($clientOwner->mem_id);

for ($i=0;$i<$comptClientOwner;$i++) {
	if ($clientDetail->org_owner[0] == $clientOwner->mem_id[$i] || $idSession == $clientOwner->mem_id[$i]) {
		echo "<option value=\"".$clientOwner->mem_id[$i]."\" selected>".$clientOwner->mem_login[$i]." / ".$clientOwner->mem_name[$i]."</option>";
	} else {
		echo "<option value=\"".$clientOwner->mem_id[$i]."\">".$clientOwner->mem_login[$i]." / ".$clientOwner->mem_name[$i]."</option>";
	}
}

echo "</select></td></tr>";
}
echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">* ".$strings["name"]." :</td><td><input size=\"44\" value=\"$cn\" style=\"width: 400px\" name=\"cn\" maxlength=\"100\" type=\"TEXT\"></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["address"]." :</td><td><textarea rows=\"3\" style=\"width: 400px; height: 50px;\" name=\"add\" cols=\"43\">$add</textarea></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["phone"]." :</td><td><input size=\"32\" value=\"$wp\" style=\"width: 250px\" name=\"wp\" maxlength=\"32\" type=\"TEXT\"></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["url"]." :</td><td><input size=\"44\" value=\"$url\" style=\"width: 400px\" name=\"url\" maxlength=\"2000\" type=\"TEXT\"></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["email"]." :</td><td><input size=\"44\" value=\"$email\" style=\"width: 400px\" name=\"email\" maxlength=\"2000\" type=\"TEXT\"></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["comments"]." :</td><td><textarea rows=\"3\" style=\"width: 400px; height: 50px;\" name=\"c\" cols=\"43\">$c</textarea></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["logo"]." :</td><td><input size=\"44\" style=\"width: 400px\" name=\"upload\" type=\"file\"></td></tr>";
if ($id != "") {
if (file_exists("../logos_clients/".$id.".".$clientDetail->org_extension_logo[0])) {
echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">&nbsp;</td><td><img src=\"../logos_clients/".$id.".".$clientDetail->org_extension_logo[0]."\"> <input name=\"extensionOld\" type=\"hidden\" value=\"".$clientDetail->org_extension_logo[0]."\"><input name=\"logoDel\" type=\"checkbox\" value=\"on\"> ".$strings["delete"]."</td></tr>";
}
}
echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">&nbsp;</td><td><input type=\"SUBMIT\" value=\"".$strings["save"]."\"></td></tr>";

$block1->closeContent();
$block1->closeForm();

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>