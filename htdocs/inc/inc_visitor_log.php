<?php
/* log */
	//	check for environment
	if (!$vartime) {
		// environment has NOT been set, hence include
		include '/opt/bitnami/apache2/htdocs/inc/inc_environment.php';
	}
	//
	//
	//	sql query1: apply filter/condition (&& ||). refer to http://www.webtrickscentral.com/article.php?a=252
	if ($varip == "#.#.#.#" || stristr($varuserhost, ".charter.com") || stristr($varuserhost, ".w3.org")) {
		// do nothing
	} else {
		$varquery1 = "INSERT INTO `sl1_visitor_log` (TIME, DATE, IP, HOST, BROWSER, REF_PAGE, PAGE, TIMESTAMP) VALUES ('$vartime', '$vardate', '$varip', '$varuserhost', '$varbrowser', '$varrefpage', '$varpage', '$vartimestamp')";
		$varresult1 = mysqli_query($varconnect, $varquery1) or die("Operation Failure. " . mysqli_error($varconnect) . ".");
	}
/* done */
?>
