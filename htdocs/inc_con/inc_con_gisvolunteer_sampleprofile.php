<?php
/*	get sampleprofilenumber */
//	get samplenumber: also refer to gmp_random() and gmp_strval(). rand() . "\n"; rand() . "\n";
$query10 = "SELECT SN_PROFILE FROM `sl1_gisvolunteer_profile`";
$result10 = mysqli_query($varconnect, $query10) or die("Operation Failure :" . mysqli_error($varconnect));
$num_rows = mysqli_num_rows($result10);
//	exclude 1-to-3
$sampleprofilenumber = rand(4, $num_rows);
/*	done */
/*	get sampleprofile */
	//	query
	$query11 = "SELECT * FROM `sl1_gisvolunteer_profile` WHERE SN_PROFILE = ".$sampleprofilenumber." AND EMAIL NOT LIKE '%@SPATIALLINK.ORG'";
	$result11 = mysqli_query($varconnect, $query11) or die("Operation Failure :" . mysqli_error($varconnect));
	$num_rows = mysqli_num_rows($result11);
	if ($num_rows == 0) {
		// $query11 does NOT return data [OR it was NOT a SELECT]:
		printf("<div class=\"gisvolunteer_sampleprofile\"><img src=\"/images/img_clear.gif\" width=\"1\" height=\"2\" /></div>");
	} else {
		//	include scripts_fct(): can NOT redeclare f()
		include '/opt/bitnami/apache2/htdocs/scripts_fct/scr_fct_cleanstring.php';
		while ($row11 = mysqli_fetch_array($result11)) {
			printf("<div class=\"gisvolunteer_sampleprofile\">".fct_cleanstring($row11['JOBHISTORY'])."</div>");
		}
	}
/*	done */
?>