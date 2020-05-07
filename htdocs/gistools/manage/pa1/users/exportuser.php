<?php
#Application name: PhpCollab
#Status page: 0
#Path by root: ../users/exportuser.php

$export = "true";

$checkSession = "false";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	$postnukeIntegration = "true";
	include("modules/PhpCollab/includes/library.php");
} else {
	$postnukeIntegration = "false";
	include("../includes/library.php");
}

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/includes/vcard.class.php");
} else {
	include("../includes/vcard.class.php");
}

$tmpquery = "WHERE mem.id = '$id'";
$userDetail = new request();
$userDetail->openMembers($tmpquery);

$v = new vCard();

$v->setPhoneNumber($userDetail->mem_phone_work[0]);

$v->setName($userDetail->mem_name[0]);

$v->setTitle($userDetail->mem_title[0]);

$v->setOrganization($userDetail->mem_org_name[0]);

$v->setEmail($userDetail->mem_email_work[0]);

$v->setPhoneNumber($userDetail->mem_phone_work[0],"WORK;VOICE");

$v->setPhoneNumber($userDetail->mem_phone_home[0],"HOME;VOICE");

$v->setPhoneNumber($userDetail->mem_mobile[0],"CELL;VOICE");

$v->setPhoneNumber($userDetail->mem_fax[0],"WORK;FAX");

$output = $v->getVCard();
$filename = $v->getFileName();

Header("Content-Disposition: attachment; filename=$filename");
Header("Content-Length: ".strlen($output));
Header("Connection: close");
Header("Content-Type: text/x-vCard; name=$filename");

echo $output;
?>