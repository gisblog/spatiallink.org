<?php
/* log */
	//	check for environment
	if (!$vartime) {
		// environment has NOT been set, hence include
		include '/var/chroot/home/content/57/3881957/html/inc/inc_environment.php';
	}
	//
	//
	//	sql query1: apply filter/condition (&& ||). refer to http://www.webtrickscentral.com/article.php?a=252
	if ($varip == "170.215.164.52" || stristr($varuserhost, ".wv.charter.com") || stristr($varuserhost, ".w3.org")) {
		// do nothing
	} else {
		$varquery1 = "INSERT INTO `sl1_visitor_log` (SN, TIME, DATE, IP, HOST, BROWSER, REF_PAGE, PAGE, TIMESTAMP) VALUES ('', '$vartime', '$vardate', '$varip', '$varuserhost', '$varbrowser', '$varrefpage', '$varpage', '$vartimestamp')";
		$varresult1 = mysql_query($varquery1) or die("Operation Failure. " . mysql_error() . ".");
	}
/* done */
?>