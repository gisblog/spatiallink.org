<?php
#Application name: PhpCollab
#Status page: 0
#Path by root: ../administration/mycompany.php

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

$action = returnGlobal('action','GET');
if ($action == "update") {
$extension = returnGlobal('extension','POST');
$extensionOld = returnGlobal('extensionOld','POST');
$cn = returnGlobal('cn','POST');
$add = returnGlobal('add','POST');
$wp = returnGlobal('wp','POST');
$url = returnGlobal('url','POST');
$email = returnGlobal('email','POST');
$c = returnGlobal('c','POST');
$logoDel = returnGlobal('logoDel','POST');

if ($logoDel == "on") {
	$tmpquery = "UPDATE ".$tableCollab["organizations"]." SET extension_logo='' WHERE id='1'";
	connectSql("$tmpquery");
	@unlink("../logos_clients/1.$extensionOld");
}
	$extension = strtolower( substr( strrchr($HTTP_POST_FILES['upload']['name'], ".") ,1) );
	if(@move_uploaded_file($HTTP_POST_FILES['upload']['tmp_name'], "../logos_clients/1.$extension")) {
		$tmpquery = "UPDATE ".$tableCollab["organizations"]." SET extension_logo='$extension' WHERE id='1'";
		connectSql("$tmpquery");
	}
	$cn = convertData($cn);
	$add = convertData($add);
	$c = convertData($c);
	$tmpquery = "UPDATE ".$tableCollab["organizations"]." SET name='$cn',address1='$add',phone='$wp',url='$url',email='$email',comments='$c' WHERE id = '1'";
	connectSql("$tmpquery");
	headerFunction("../administration/mycompany.php?".session_name()."=".session_id());
}
$tmpquery = "WHERE org.id = '1'";
$clientDetail = new request();
$clientDetail->openOrganizations($tmpquery);

$cn = $clientDetail->org_name[0];
$add = $clientDetail->org_address1[0];
$wp = $clientDetail->org_phone[0];
$url = $clientDetail->org_url[0];

$email = $clientDetail->org_email[0];
$c = $clientDetail->org_comments[0];

$bodyCommand = "onLoad=\"document.adminDForm.cn.focus();\"";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/header.php");
} else {
	include("../themes/".THEME."/header.php");
}

$blockPage = new block();
$blockPage->openBreadcrumbs();
$blockPage->itemBreadcrumbs($blockPage->buildLink("../administration/admin.php?",$strings["administration"],in));
$blockPage->itemBreadcrumbs($strings["company_details"]);
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

echo "<a name=\"".$this->form."Anchor\"></a>\n
<form accept-charset=\"UNKNOWN\" method=\"POST\" action=\"../administration/mycompany.php?action=update&amp;".session_name()."=".session_id()."\" name=\"adminDForm\" enctype=\"multipart/form-data\"><input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"100000000\">\n";

if ($error != "") {            
	$block1->headingError($strings["errors"]);
	$block1->contentError($error);
}

$block1->heading($strings["company_details"]);

$block1->openContent();
$block1->contentTitle($strings["company_info"]);

echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["name"]." :</td><td><input size=\"44\" value=\"$cn\" style=\"width: 400px\" name=\"cn\" maxlength=\"100\" type=\"TEXT\"></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["address"]." :</td><td><textarea rows=\"3\" style=\"width: 400px; height: 50px;\" name=\"add\" cols=\"43\">$add</textarea></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["phone"]." :</td><td><input size=\"32\" value=\"$wp\" style=\"width: 250px\" name=\"wp\" maxlength=\"32\" type=\"TEXT\"></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["url"]." :</td><td><input size=\"44\" value=\"$url\" style=\"width: 400px\" name=\"url\" maxlength=\"2000\" type=\"TEXT\"></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["email"]." :</td><td><input size=\"44\" value=\"$email\" style=\"width: 400px\" name=\"email\" maxlength=\"2000\" type=\"TEXT\"></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["comments"]." :</td><td><textarea rows=\"3\" style=\"width: 400px; height: 50px;\" name=\"c\" cols=\"43\">$c</textarea></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["logo"].$blockPage->printHelp("mycompany_logo")." :</td><td><input size=\"44\" style=\"width: 400px\" name=\"upload\" type=\"file\"></td></tr>";
if (file_exists("../logos_clients/1.".$clientDetail->org_extension_logo[0])) {
echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">&nbsp;</td><td><img src=\"../logos_clients/1.".$clientDetail->org_extension_logo[0]."\" border=\"0\" alt=\"".$clientDetail->org_name[0]."\"> <input name=\"extensionOld\" type=\"hidden\" value=\"".$clientDetail->org_extension_logo[0]."\"><input name=\"logoDel\" type=\"checkbox\" value=\"on\"> ".$strings["delete"]."</td></tr>";
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