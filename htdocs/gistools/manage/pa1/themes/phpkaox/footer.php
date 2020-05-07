<?php
#Application name: PhpCollab
#Status page: 0

/*spatiallink:echo "<p id=\"footer\">PhpCollab v$version";*/
echo "<p id=\"footer\">";

if ($notLogged != "true" && $blank != "true") {
echo "Connected users: $connectedUsers";
}

if ($footerDev == "true") {
	$parse_end = getmicrotime();
	$parse = $parse_end - $parse_start;
	$parse = round($parse,3);
	echo " - $parse secondes - databaseType $databaseType - select requests $comptRequest";
	echo " - <a href=\"http://validator.w3.org/check/referer\" target=\"w3c\">w3c</a> (in progress)&nbsp;&nbsp;&nbsp;";
}

echo "</p>\n\n";

if ($xoopsIntegration == "true") {
include(XOOPS_ROOT_PATH."/footer.php");
} else if ($postnukeIntegration == "true") {
include("footer.php");
} else {
echo "</body>
</html>";
}
?>