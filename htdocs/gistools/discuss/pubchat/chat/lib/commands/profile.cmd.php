<?php

$IsCommand = true;
$IsPopup = true;

// Define a table that contains JavaScript instructions to be ran
$jsTbl = array(
	"<SCRIPT TYPE=\"text/javascript\" LANGUAGE=\"JavaScript\">",
	"<!--",
	"// Lauch the profile popup",
	"window.open(\"edituser.php?L=$L&AUTH_USERNAME=$U&LIMIT=1\",\"edituser_popup\",\"width=350,height=470,resizable=yes,scrollbars=yes\");",
	"// -->",
	"</SCRIPT>"
);

?>