<?php
#Application name: PhpCollab
#Status page: 0
if ($projectSession != "" && $changeProject != "true") {
	$tmpquery = "WHERE pro.id = '$projectSession'";
	$projectDetail = new request();
	$projectDetail->openProjects($tmpquery);

$teamMember = "false";
$tmpquery = "WHERE tea.project = '$projectSession' AND tea.member = '$idSession'";
$memberTest = new request();
$memberTest->openTeams($tmpquery);
$comptMemberTest = count($memberTest->tea_id);
	if ($comptMemberTest == "0") {
		$teamMember = "false";
	} else {
		$teamMember = "true";
	}
if ($teamMember == "false") {
	headerFunction("index.php");
}
}

echo "$setDoctype
$setCopyright
<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=$setCharset\">
<meta name=\"robots\" content=\"none\">
<meta name=\"description\" content=\"$setDescription\">
<meta name=\"keywords\" content=\"$setKeywords\">

";
/*spatiallink:include title
<title>$setTitle - ";
if ($projectSession != "" && $changeProject != "true") {
	echo $projectDetail->pro_name[0];
}
if ($projectSession == "" || $changeProject == "true") {
	echo $strings["my_projects"];
}
echo "</title>\n";*/
include '/var/chroot/home/content/57/3881957/html/inc/inc_title.php';
/*done*/
echo "

<meta http-equiv=\"Content-Type\" content=\"text/html; charset=$setCharset\">
<link rel=\"stylesheet\" href=\"../themes/".THEME."/calendar.css\">
<link rel=\"stylesheet\" href=\"../themes/".THEME."/stylesheet.css\">
</head>
<body $bodyCommand>

<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\" background=\"bg_header.jpg\">
<tr><td align=\"left\"><img src=\"spacer_black.gif\" width=\"1\" height=\"24\" border=\"0\" alt=\"\"></td><td align=\"right\"><img src=\"spacer_black.gif\" width=\"1\" height=\"24\" border=\"0\" alt=\"\"></td></tr>
</table>

<table cellpadding=0 cellspacing=0 border=0 height=\"95%\" width=\"100%\">
<tr><td valign=\"middle\" width=\"150\" bgcolor=\"#5B7F93\" height=\"75\"><img src=\"../themes/".THEME."/spacer.gif\" width=\"150\" height=\"75\" alt=\"\"></td><td bgcolor=\"#EFEFEF\" height=\"75\">&nbsp;&nbsp;&nbsp;<b>$titlePage</b></td></tr>
<tr><td valign=\"top\" bgcolor=\"#C4D3DB\"><br>

<table cellspacing=\"2\" cellpadding=\"3\" border=\"0\">";



for ($i=0;$i<7;$i++) {
	if ($bouton[$i] == "") {
		$bouton[$i] = "normal";
	}
}

if ($projectSession != "" && $changeProject != "true") {
echo "<tr><td colspan=2><b>".$strings["project"]." :<br>".$projectDetail->pro_name[0]."</b></td></tr>
<tr><td><img src=\"ico_arrow_".$bouton[0].".gif\" border=\"0\" alt=\"\"></td><td><a href=\"home.php?$transmitSid\">".$strings["home"]."</a></td></tr>
<tr><td><img src=\"ico_arrow_".$bouton[1].".gif\" border=\"0\" alt=\"\"></td><td><a href=\"showallcontacts.php?$transmitSid\">".$strings["project_team"]."</a></td></tr>
<tr><td><img src=\"ico_arrow_".$bouton[2].".gif\" border=\"0\" alt=\"\"></td><td><a href=\"showallteamtasks.php?$transmitSid\">".$strings["team_tasks"]."</a></td></tr>";

if ($projectDetail->pro_organization[0] != "" && $projectDetail->pro_organization[0] != "1") {
	echo "<tr><td><img src=\"ico_arrow_".$bouton[3].".gif\" border=\"0\" alt=\"\"></td><td><a href=\"showallclienttasks.php?$transmitSid\">".$strings["client_tasks"]."</a></td></tr>";
}

if ($fileManagement == "true") {
	echo "<tr><td><img src=\"ico_arrow_".$bouton[4].".gif\" border=\"0\" alt=\"\"></td><td><a href=\"doclists.php?$transmitSid\">".$strings["document_list"]."</a></td></tr>";
}

echo "<tr><td><img src=\"ico_arrow_".$bouton[5].".gif\" border=\"0\" alt=\"\"></td><td><a href=\"showallthreadtopics.php?$transmitSid\">".$strings["bulletin_board"]."</a></td></tr>";

if ($enableHelpSupport == "true"){
	echo"<tr><td><img src=\"ico_arrow_".$bouton[6].".gif\" border=\"0\" alt=\"\"></td><td><a href=\"showallsupport.php?$transmitSid&project=$projectSession\">".$strings["support"]."</a></td></tr>";
}

//if mantis bug tracker enabled
	if ($enableMantis == "true") {
		include("navigation.php");
		echo"<tr><td><img src=\"ico_arrow_".$bouton[6].".gif\" border=\"0\" alt=\"\"></td><td><a href=\"javascript:onClick= document.login.submit();\">".$strings["bug"]."</a></td></tr></form>";
}

echo "</table>
<br><hr>";
}

echo "<table cellspacing=\"2\" cellpadding=\"3\" border=\"0\">
<tr><td><a href=\"home.php?$transmitSid&changeProject=true\"><img src=\"ico_folder.gif\" border=\"0\" alt=\"\"></a></td><td><a href=\"home.php?$transmitSid&changeProject=true\">".$strings["my_projects"]."</a></td></tr>";

echo "<tr><td colspan=2><br></td></tr>
<tr><td><a href=\"changepassword.php?$transmitSid&changeProject=true\"><img src=\"ico_prefs.gif\" border=\"0\" alt=\"\"></a></td><td><a href=\"changepassword.php?$transmitSid&changeProject=true\">".$strings["preferences"]."</a></td></tr>
<tr><td colspan=2><br></td></tr>
<tr><td><a href=\"../general/login.php?logout=true\"><img src=\"ico_logout.gif\" border=\"0\" alt=\"\"></a></td><td><a href=\"../general/login.php?logout=true\">".$strings["logout"]."</a></td></tr>
</table>

</td>
<td valign=\"top\" width=\"100%\">";

echo "<table cellpadding=20 cellspacing=0 border=0 width=\"100%\"><tr><td width=\"100%\">";
?>