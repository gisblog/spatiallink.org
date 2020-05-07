<?php
#Application name: PhpCollab
#Status page: 0
#Path by root: ../search/createsearch.php

$checkSession = "true";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	$postnukeIntegration = "true";
	include("modules/PhpCollab/includes/library.php");
} else {
	$postnukeIntegration = "false";
	include("../includes/library.php");
}

//test required field searchfor
if ($action == "search") {

//if searchfor blank, $error set
	if ($searchfor == "") {
		$error = $strings["search_note"];

//if searchfor not blank, redirect to searchresults
	} else {
		$searchfor = urlencode($searchfor);
		headerFunction("../search/resultssearch.php?searchfor=$searchfor&heading=$heading&".session_name()."=".session_id());
		exit;
	}
}

$bodyCommand = "onLoad=\"document.searchForm.searchfor.focus()\"";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/header.php");
} else {
	include("../themes/".THEME."/header.php");
}

$blockPage = new block();
$blockPage->openBreadcrumbs();
$blockPage->itemBreadcrumbs($blockPage->buildLink("../search/createsearch.php?",$strings["search"],in));
$blockPage->itemBreadcrumbs($strings["search_options"]);
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

$block1->form = "search";
$block1->openForm("../search/createsearch.php?action=search&amp;".session_name()."=".session_id());

if ($error != "") {            
	$block1->headingError($strings["errors"]);
	$block1->contentError($error);
}

$block1->heading($strings["search"]);

$block1->openContent();
$block1->contentTitle($strings["enter_keywords"]);

echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">* ".$strings["search_for"]." :</td><td><input value=\"\" type=\"text\" name=searchfor style=\"width: 200px;\" size=\"30\" maxlength=\"64\">
<select name=\"heading\">
		<option selected value=\"ALL\">".$strings["all_content"]."</option>
		<option value=\"notes\">".$strings["notes"]."</option>
		<option value=\"organizations\">".$strings["organizations"]."</option>
		<option value=\"projects\">".$strings["projects"]."</option>
		<option value=\"tasks\">".$strings["tasks"]."</option>
		<option value=\"discussions\">".$strings["discussions"]."</option>
		<option value=\"members\">".$strings["users"]."</option>
</select>
</td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">&nbsp;</td><td><input type=\"submit\" name=\"Save\" value=\"".$strings["search"]."\"></td></tr>";

$block1->closeContent();
$block1->closeForm();


if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>