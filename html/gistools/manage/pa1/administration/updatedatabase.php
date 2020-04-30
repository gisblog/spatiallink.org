<?php
#Application name: PhpCollab
#Status page: 0
#Path by root: ../administration/updatedatabase.php

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

$versionNew = "2.4";

if ($action == "printSetup") {
if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/includes/db_var.inc.php");
	include("modules/PhpCollab/includes/setup_db.php");
} else {
	include("../includes/db_var.inc.php");
	include("../includes/setup_db.php");
}
	for($con = 0; $con < count($SQL); $con++){
		echo $SQL[$con] . ';<br>';
	}
}
if ($action == "printUpdate") {
if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/includes/db_var.inc.php");
	include("modules/PhpCollab/includes/update_db.php");
} else {
	include("../includes/db_var.inc.php");
	include("../includes/update_db.php");
}
	for($con = 0; $con < count($SQL); $con++){
		echo $SQL[$con] . '<br>';
	}
}

if ($action == "generate") {
if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/includes/db_var.inc.php");
	include("modules/PhpCollab/includes/update_db.php");
} else {
	include("../includes/db_var.inc.php");
	include("../includes/update_db.php");
}
	if ($databaseType == "mysql") {
	$my = @mysql_connect(MYSERVER, MYLOGIN, MYPASSWORD);
	if (mysql_errno() != 0){ exit('<br><b>PANIC! <br> Error during connection on server MySQL.</b><br>'); }
	mysql_select_db(MYDATABASE, $my);
	if (mysql_errno() != 0){ exit('<br><b>PANIC! <br> Error during selection database.</b><br>'); }
	for($con = 0; $con < count($SQL); $con++){
	    mysql_query($SQL[$con]);
	    //echo $SQL[$con] . '<br>';
	    if (mysql_errno() != 0){ exit('<br><b>PANIC! <br> Error during the update of the database.</b><br> Error: '. mysql_error()); }
	}
	}
	if ($databaseType == "sqlserver") {
	$my = @mssql_connect(MYSERVER, MYLOGIN, MYPASSWORD);
	if (mssql_get_last_message() != 0){ exit('<br><b>PANIC! <br> Error during connection on server SQl Server.</b><br>'); }
	mssql_select_db(MYDATABASE, $my);
	if (mssql_get_last_message() != 0){ exit('<br><b>PANIC! <br> Error during selection database.</b><br>'); }
	for($con = 0; $con < count($SQL); $con++){
	    mssql_query($SQL[$con]);
	    //echo $SQL[$con] . '<br>';
	    if (mssql_get_last_message() != 0){ exit('<br><b>PANIC! <br> Error during the update of the database.</b><br> Error: '. mssql_get_last_message()); }







	}
	}
	headerFunction("../administration/admin.php?msg=update&".session_name()."=".session_id());
}



if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/header.php");
} else {
	include("../themes/".THEME."/header.php");
}

$blockPage = new block();
$blockPage->openBreadcrumbs();
$blockPage->itemBreadcrumbs($blockPage->buildLink("../administration/admin.php?",$strings["administration"],in));
$blockPage->itemBreadcrumbs($strings["edit_database"]);
$blockPage->closeBreadcrumbs();

$block1 = new block();

$block1->heading($strings["edit_database"]);

$block1->openContent();
$block1->contentTitle("Details");
$block1->form = "settings";
$block1->openForm("../administration/updatedatabase.php?action=generate&amp;".session_name()."=".session_id());


if ($version == $versionNew) {
	if ($versionOld == "") {
		$versionOld = $version;
	}
	echo "<input value=\"$versionOld\" name=\"versionOldNew\" type=\"hidden\">";
} else {
	echo "<input value=\"$version\" name=\"versionOldNew\" type=\"hidden\">";
}

echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">&nbsp;</td><td>Old version $versionOld<br>";
$comptUpdateDatabase = count($updateDatabase);
for ($i=0;$i<$comptUpdateDatabase;$i++) {
	if ($versionOld < $updateDatabase[$i]) {
		echo "<input type=\"checkbox\" value=\"1\" name=\"dumpVersion[$updateDatabase[$i]]\" checked>$updateDatabase[$i]";
		$submit = "true";
	}
}

echo "<br>New version $version</td></tr>";

if ($submit == "true") {
echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">&nbsp;</td><td><input type=\"SUBMIT\" value=\"".$strings["save"]."\"></td></tr>";
}

$block1->closeContent();
$block1->closeForm();

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>