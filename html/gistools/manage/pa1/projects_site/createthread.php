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

include("../includes/notification.class.php");

	if ($action == "add") {
		$topicField = convertData($topicField);
		$messageField = convertData($messageField);
		$tmpquery1 = "INSERT INTO ".$tableCollab["topics"]."(project,owner,subject,status,last_post,posts,published) VALUES('$projectSession','$idSession','$topicField','1','$dateheure','1','0')";
		connectSql("$tmpquery1");
$tmpquery = $tableCollab["topics"];
last_id($tmpquery);
$num = $lastId[0];
unset($lastId);
autoLinks($messageField);
	$tmpquery2 = "INSERT INTO ".$tableCollab["posts"]."(topic,member,created,message) VALUES('$num','$idSession','$dateheure','$newText')";
	connectSql("$tmpquery2");

if ($notifications == "true") {
$tmpquery = "WHERE pro.id = '$projectSession'";
$projectDetail = new request();
$projectDetail->openProjects($tmpquery);

$tmpquery = "WHERE topic.id = '$num'";
$detailTopic = new request();
$detailTopic->openTopics($tmpquery);

$tmpquery = "WHERE tea.project = '$projectSession' AND tea.member != '$idSession' ORDER BY mem.id";
$listTeam = new request();
$listTeam->openTeams($tmpquery);
$comptListTeam = count($listTeam->tea_id);

for ($i=0;$i<$comptListTeam;$i++) {
	$posters .= $listTeam->tea_member[$i].",";
}
if (substr($posters, -1) == ",") { $posters = substr($posters, 0, -1); }
//echo $posters;

$tmpquery = "WHERE noti.member IN($posters)";
$listNotifications = new request();
$listNotifications->openNotifications($tmpquery);
$comptListNotifications = count($listNotifications->not_id);

$newtopic = new notification();

for ($i=0;$i<$comptListNotifications;$i++) {
	if ($listNotifications->not_newtopic[$i] == "0" && $listNotifications->not_mem_email_work[$i] != "" && $listNotifications->not_mem_id[$i] != $idSession) {
		$newtopic->discussionsNotification($listNotifications->not_mem_name[$i],$listNotifications->not_mem_email_work[$i],$listNotifications->not_mem_organization[$i],$num,"newtopic");
	}
}
}
		headerFunction("showallthreadtopics.php?".session_name()."=".session_id());
	}

$bodyCommand="onload=\"document.createThreadTopic.topicField.focus();\"";

$bouton[5] = "over";
$titlePage = $strings["create_topic"];
include ("include_header.php");

echo "<form accept-charset=\"UNKNOWN\" method=\"post\" action=\"../projects_site/createthread.php?project=$projectSession&amp;action=add&amp;".session_name()."=".session_id()."&amp;id=$id\" name=\"createThreadTopic\" enctype=\"application/x-www-form-urlencoded\">";

echo "<table cellspacing=\"0\" width=\"90%\" border=\"0\" cellpadding=\"3\">
<tr><th colspan=\"2\">".$strings["create_topic"]."</th></tr>
<tr><th>*&nbsp;".$strings["topic"]." :</th><td><input size=\"35\" value=\"$topicField\" name=\"topicField\" type=\"text\"></td></tr>
<tr><th colspan=\"2\">".$strings["enter_message"]."</th></tr>
<tr><th>*&nbsp;".$strings["message"]." :</th><td colspan=\"2\"><textarea rows=\"3\" name=\"messageField\" cols=\"43\"></textarea></td></tr>
<tr><th>&nbsp;</th><td colspan=\"2\"><input name=\"submit\" type=\"submit\" value=\"".$strings["save"]."\"></td></tr>
</table>
</form>";

include ("include_footer.php");
?>