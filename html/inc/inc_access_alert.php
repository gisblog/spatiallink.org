<?php
/*	alert: first check if environment variables have been included. 
	include(): If there are functions defined in the included file, they can be used in the main file independent if they are before return() or after. If the file is included twice, PHP 5 issues fatal error because functions were already declared, while PHP 4 doesn't complain about functions defined after return(). It is recommended to use include_once() instead of checking if the file was already included and conditionally return inside the included file.
	include_once(): The include_once() statement includes and evaluates the specified file during the execution of the script. This is a behavior similar to the include() statement, with the only difference being that if the code from a file has already been included, it will not be included again. As the name suggests, it will be included just once. 
	include_once() should be used in cases where the same file might be included and evaluated more than once during a particular execution of a script, and you want to be sure that it is included exactly once to avoid problems with function redefinitions, variable value reassignments, etc. 
	note: When you use include_once and the data that you include falls out of scope, if you use include_once again later it will not include despite the fact that what you included is no longer available. */
if (!$vartime) {
	// environment variables have NOT been set, hence include
	include '/var/chroot/home/content/57/3881957/html/inc/inc_environment.php';
}
//	apply filter/condition (&& ||): refer to preg_match, stristr. also, refer to http://www.webtrickscentral.com/article.php?a=252.
if ($varip == "66.190.132.38" || ereg("68.142.", $varip) || ereg("w3.org", $varuserhost)) {
	// do nothing
} else {
	//	send auto-mail
	$varto = "spatiallink@spatiallink.org";
	$varsubject = "Access Alert (auto-mail)";
	$varbody = "Time:: ".$vartime." // Date:: ".$vardate." // Requesting IP:: ".$varip." // WHOIS:: http://www.whois.sc/".$varip." // Requesting Host:: ".$varhost." // ARIN:: http://ws.arin.net/cgi-bin/whois.pl?queryinput=".$varhost." // HOST:: http://".$varhost." // Requested File Name:: ".$varfilename." // Requested URL:: ".$varurl." // Referral Site:: ".$varrefpage."";

	ini_set("sendmail_from", "spatiallink@linhost119.prod.mesa1.secureserver.net"); 
	ini_set("SMTP", "linhost119.prod.mesa1.secureserver.net");
	
	if (mail($varto, $varsubject, $varbody)) {
		print ("");
	} else {
		print ("");
	}
}
/*	done */
?>