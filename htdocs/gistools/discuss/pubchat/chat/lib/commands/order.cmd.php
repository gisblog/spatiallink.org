<?php

if ($Ver == "L")
{
	$O = 1 - $O;
	setcookie("CookieMsgOrder", $O, time() + 60*60*24*365);		// cookie expires in one year
	$IsCommand = true;
	$RefreshMessages = true;
}

?>