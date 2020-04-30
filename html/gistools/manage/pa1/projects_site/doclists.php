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

$bouton[4] = "over";
$titlePage = $strings["document_list"];
include ("include_header.php");

$tmpquery = "WHERE fil.project = '$projectSession' AND fil.published = '0' AND fil.vc_parent = '0' ORDER BY fil.name";
$listFiles = new request();
$listFiles->openFiles($tmpquery);
$comptListFiles = count($listFiles->fil_id);

$block1 = new block();

$block1->heading($strings["document_list"]);

if ($comptListFiles != "0") {
echo "<table cellspacing=\"0\" width=\"90%\" border=\"0\" cellpadding=\"3\" cols=\"4\" class=\"listing\">
<tr><th class=\"active\">".$strings["name"]."</th><th>".$strings["topic"]."</th><th>".$strings["date"]."</th><th>".$strings["approval_tracking"]."</th></tr>";

for ($i=0;$i<$comptListFiles;$i++) {
	if (!($i%2)) {
		$class = "odd";
		$highlightOff = $block1->oddColor;
	} else {
		$class = "even";
		$highlightOff = $block1->evenColor;
	}
$idStatus = $listFiles->fil_status[$i];
echo "<tr class=\"$class\" onmouseover=\"this.style.backgroundColor='".$block1->highlightOn."'\" onmouseout=\"this.style.backgroundColor='".$highlightOff."'\"><td>";
if ($listFiles->fil_task[$i] != "0") {
echo "<a href=\"clientfiledetail.php?$transmitSid&id=".$listFiles->fil_id[$i]."\">".$listFiles->fil_name[$i]."</a>";
$folder = $listFiles->fil_project[0]."/".$listFiles->fil_task[0];
} else {
echo "<a href=\"clientfiledetail.php?$transmitSid&id=".$listFiles->fil_id[$i]."\">".$listFiles->fil_name[$i]."</a>";
$folder = $listFiles->fil_project[0];
}
echo " </td><td><a href=\"createthread.php?$transmitSid&topicField=".$listFiles->fil_name[$i]."\">".$strings["create"]."</a></td><td>".$listFiles->fil_date[$i]."</td><td width=\"20%\" class=\"$class\"><a href=\"docitemapproval.php?$transmitSid&id=".$listFiles->fil_id[$i]."\">$statusFile[$idStatus]</a></td></tr>";
}
echo "</table>
<hr />\n";
} else {
echo "<table cellspacing=\"0\" border=\"0\" cellpadding=\"2\"><tr><td colspan=\"4\" class=\"listOddBold\">".$strings["no_items"]."</td></tr></table><hr>";
}

echo "<br><br>

<a href=\"uploadfile.php?$transmitSid\" class=\"FooterCell\">".$strings["upload_file"]."</a>";

include ("include_footer.php");
?>