<?php
/* require $var AGAIN */
	//	check for db variables
	if (!$varhost) {
		require '/opt/bitnami/apache2/_sec/inc_db_variables.php';
	}
	//	check for db csu
	if (!$varconnect) {
		require '/opt/bitnami/apache2/htdocs/inc/inc_db_csu.php';
	}
	//	check for environment variables
	if (!$vartime) {
		require '/opt/bitnami/apache2/htdocs/inc/inc_environment.php';
	}
/* done */
//	get js variable: also, try var zipcodeValue = document.getElementById("zipcode").value
// $zipcode = "12345";
$xhr_zipcode = $_GET['param']; 
// done
/*	note that js has already checked the variable. at this point, the variable (zipcode) is:
	its length is NOT <= 4 AND it contains acceptable characters 1234567890 AND its value is NOT <= 209) || >= 99951
	but it can still be invalid [EX: 90000] and return NULL */
//	perform sql query1: array[0]
	$query1 = "SELECT * FROM `spatiallink`.`sl1_gisvolunteer_location` WHERE ZIPCODE = $xhr_zipcode";
//	done
// check for NULL
$result1 = mysqli_query($varconnect, $query1) or die(",,,");
if (mysqli_num_rows($result1) == 0) {
	// $query1 does NOT return data [OR it was NOT a SELECT], but the script still has to return an array. and it can ONLY return an array, NOT, say a js alert. so we will catch the invalid variable (zipcode) after HTTP response, but before submission
	print ",,,";
	exit;
	return;
	die;
}
//	done
// query1 MUST have returned data, so move on W/O mysqli_error($varconnect)
$result1 = mysqli_query($varconnect, $query1) or die(",,,");
while ($row1 = mysqli_fetch_array($result1)) {
	printf($row1['LATITUDE'].",".$row1['LONGITUDE'].",".$row1['PLACE']);
	// carryover: assignment MUST be inside while{} with the variable_whose_value_is_to_be_assigned on the left
	$xhr_latitude = $row1['LATITUDE'];
	$xhr_longitude = $row1['LONGITUDE'];
	$xhr_place = $row1['PLACE'];
	$xhr_state = $row1['STATE'];
}
//	done
//	print array separator
print ",";
//	done
// perform sql query2: array[1]
$query2 = "SELECT * FROM `spatiallink`.`sl1_gisvolunteer_fips` WHERE STATE = $xhr_state";
// query2 MUST have returned data, so move on W/O mysqli_error($varconnect)
$result2 = mysqli_query($varconnect, $query2) or die(",,,");
while ($row2 = mysqli_fetch_array($result2)) {
	// create array: assignment MUST be inside while{}
	$array = array($row2['ST_NAME']);		// EX: NYNYNYNY...
	/* you can slice the array, like so:
		$input = array("a b c d e");
		print_r(array_slice($input, 0, 1));	// a
		OR
		explode the array, like so:
		$input = array("a b c d e");
		$pieces = explode(" ", $input);
		print $pieces[0];							// a
		OR
		extract the end value, like so:
		$input = array("a b c d e");
		$array_end = end($array);
		print $array_end;							// e */
	}
	print $array{0};								// N
	print $array{1};								// Y
// done
?>