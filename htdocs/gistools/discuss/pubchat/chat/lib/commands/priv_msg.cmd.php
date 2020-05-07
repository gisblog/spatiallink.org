<?php

// Check for invalid characters in the addressee name
if (ereg("[\, ]", stripslashes($Cmd[2])))
{
	$Error = L_ERR_USR_16;
}
elseif (trim($Cmd[2]) != "" && trim($Cmd[3]) != "")
{
	// Check for swear words in the message if necessary
	if (C_NO_SWEAR == 1)
	{
		include("./lib/swearing.lib.php");
		$Cmd[3] = checkwords($Cmd[3], false);
	}
 	AddMessage(stripslashes($Cmd[3]), $T, $R, $U, $C, $Cmd[2]);
	$IsCommand = true;
	$RefreshMessages = true;
}

?>