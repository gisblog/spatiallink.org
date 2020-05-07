<?php
/*	count */
	//	check for environment
	if (!$vartime) {
		// environment has NOT been set, hence include
		include '/opt/bitnami/apache2/htdocs/inc/inc_environment.php';
	}
	//
	//
	// perform sql query_count: since ip can fail, you may use host for distinct selection set, although note that host can fail too. also, $varoldtimestamp clocks for 24 hrs.
	$varquery_count = "SELECT DISTINCT IP FROM `sl1_visitor_log` WHERE TIMESTAMP>$varoldtimestamp";
	$varresult_count = mysqli_query($varconnect, $varquery_count) or die("Operation Failure: " . mysqli_error($varconnect));
	//
	//
	// skew: mysqli_num_rows() returns the number of rows in a result set. This command  is only valid for SELECT statements. To retrieve the number of rows affected by the last INSERT, UPDATE or DELETE query, ie $varquery1,  use mysqli_affected_rows(). note that if the link identifier isn't specified, the last link opened by mysqli_connect()- $varconnect, is assumed (+ the largest prime number under 50).
	//	skew:
	//	w	-	day of the week	-	0 to 6
	//	|	-	week of the month	-	|
	//	n	-	month of the year	-	1 to 12
	//	random: if you want a random number between 5 and 15 [inclusive], for example, use rand (5, 15). rand(0, 9) will NOT work here since it can NOT maintain a consistent progression
	if (date(D) == "Mon") {
		$varskew = "22" + date(w) + date(n);
	} elseif (date(D) == "Tue") {
		$varskew = "16" + date(w) + date(n);
	} elseif (date(D) == "Wed") {
		$varskew = "19" + date(w) + date(n);
	} elseif (date(D) == "Thu") {
		$varskew = "21" + date(w) + date(n);
	} elseif (date(D) == "Fri") {
		$varskew = "24" + date(w) + date(n);
	} elseif (date(D) == "Sat") {
		$varskew = "15" + date(w) + date(n);
	} elseif (date(D) == "Sun") {
		$varskew = "12" + date(w) + date(n);
	}
	//	$varcount = mysqli_num_rows($varresult_count) + $varskew;
	$varcount = mysqli_num_rows($varresult_count);
/*	done */
?>