<?php
// checkjs
if ($_POST[javascript] != "enabled") {
	//	include_once noscript
	include_once("/var/chroot/home/content/57/3881957/html/inc/inc_noscript.php");
	exit;
	return;
	die;
}
//	done
// clean POST
function safeEscapeString($string){
	if (get_magic_quotes_gpc()) {
		return $string;
	} else {
		return mysql_real_escape_string($string);
	}
}
function cleanVar($string){
	$string = trim($string);
	$string = safeEscapeString($string);
	$string = htmlentities($string);
	return $string;
}
foreach($_POST as $name => $value){
	$_POST[$name] = cleanVar($value);
}
foreach($_GET as $name => $value){
	$_GET[$name] = cleanVar($value);
}
foreach($_COOKIE as $name => $value){
	$_COOKIE[$name] = cleanVar($value);
}
foreach($_REQUEST as $name => $value){
	$_REQUEST[$name] = cleanVar($value);
}
//	done
?>