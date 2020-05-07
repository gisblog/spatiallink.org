<?php
/*	$varrefpage check: NOT needed since there is no file named [gisvolunteer_profiled.php]. hence, [http://www.spatiallink.org/gistools/volunteer/gisvolunteer_profiled.php] will cause Error 404. */
//	require checkjs_clean here
// require '/opt/bitnami/apache2/htdocs/inc/inc_checkjs_clean.php';
?>
<!--include content-->
		<table width="760" border="0" align="left" cellspacing="0" cellpadding="0" bgcolor="#C2C0C0">
	<tr>
<td class="medium">
<?php
//	note: do NOT include imgmenu since it reloads this page on submission
//	do NOT include scripts_fct(): can NOT redeclare f()
/*	check email: email will be the username. js has already checked the variable, but EMAIL can be a duplicate and still result in INSERT failure. note the quotes- EMAIL must NOT be 'EMAIL', and '$_POST[email]' must NOT be $_POST[email] */
	// check for NULL
	$query_check = "SELECT * FROM `sl1_gisvolunteer_profile` WHERE EMAIL LIKE '$_POST[email]'";
	$result_check = mysqli_query($varconnect, $query_check) or die("Operation Failure. " . mysqli_error($varconnect));
	$num_rows = mysqli_num_rows($result_check);
	// check for NULL
	$query_check_co = "SELECT * FROM `sl1_gisvolunteer_profile_co` WHERE EMAIL LIKE '$_POST[email]'";
	$result_check_co = mysqli_query($varconnect, $query_check_co) or die("Operation Failure. " . mysqli_error($varconnect));
	$num_rows_co = mysqli_num_rows($result_check_co);
	//
	if ($num_rows == 0 && $num_rows_co == 0) {
		/*	$query0 does NOT return data [OR it was NOT a SELECT]-->UNIQUE-->OK TO INSERT. perform cache/query_insert: note that $VARquery is used for super-queries; query1 [get city and state for zipcode]; query2 [get unique state].
		
		Note 1 on MySQL cache:
		If a table changes, then all cached queries that use that table become invalid and are removed from the cache. When in use, the query cache stores the text of a SELECT query together with the corresponding result that was sent to the client 
		
		ZIPCODE:
		INTEGER TYPE = UNSIGNED ZEROFILL since zipcodes can have leading zeros. also, LPAD(string, length, pad_string)- mysql will automatically convert your integer to a string if used in string context-
		SELECT LPAD(int_col, 3, '0') FROM your_table
		LPAD() is described @ [http://dev.mysql.com/doc/mysql/en/string-functions.html]. however, if the input string is longer than the given length, the string gets truncated on the right.
		
		Note 2 on MySQL cache:
		Effects specified row, or last row if unspecified.
		
		INSERT INTO `sl1_gisvolunteer_profile` (FEE) VALUES (1);

		The UPDATE statement updates columns in existing table rows with new values. The SET  clause indicates which columns to modify and the values they should be given. The WHERE clause, if given, specifies which rows should be updated. Otherwise, all rows are updated. If the ORDER BY clause is specified, the rows are updated in the order that is specified. The LIMIT clause places a limit on the number of rows that can be updated.

		UPDATE `sl1_gisvolunteer_profile` SET FEE=1; */
		//	common variables
		$cocode = $_POST['cocode'];
		if ($cocode == "256") {
			//	US professional/volunteer
			$table = "sl1_gisvolunteer_profile";
			$code = "ZIPCODE";
			$code_value = $_POST['zipcode'];
		} else {
			//	NON-US professional/volunteer
			$table = "sl1_gisvolunteer_profile_co";
			$code = "COCODE";
			$code_value = $cocode;
		}
		# # # $query_insert = "INSERT INTO $table (SN_PROFILE, FIRSTNAME, LASTNAME, EMAIL, JOBTITLE, $code, EXPERIENCE, PROFICIENCY, EDUCATION, VEXPERIENCE, FEE, JOBHISTORY, CREATED, IP, HOST, REF_PAGE) VALUES ('', '$_POST[firstname]', '$_POST[lastname]', '$_POST[email]', '$_POST[jobtitle]', '$code_value', '$_POST[experience]', '$_POST[proficiency]', '$_POST[education]', '$_POST[vexperience]', $_POST[fee], '$_POST[jobhistory]', '$vardate', '$varip', '$varhost', '$varrefpage')";
		$query_insert = "INSERT INTO $table (FIRSTNAME, LASTNAME, EMAIL, JOBTITLE, $code, EXPERIENCE, PROFICIENCY, EDUCATION, VEXPERIENCE, FEE, JOBHISTORY, CREATED, IP, HOST, REF_PAGE) VALUES ('$_POST[firstname]', '$_POST[lastname]', '$_POST[email]', '$_POST[jobtitle]', '$code_value', '$_POST[experience]', '$_POST[proficiency]', '$_POST[education]', '$_POST[vexperience]', $_POST[fee], '$_POST[jobhistory]', '$vardate', '$varip', '$varhost', '$varrefpage')";
			$result_insert = mysqli_query($varconnect, $query_insert) or die("Operation Failure. " . mysqli_error($varconnect));
		// do NOT close/free before footer
		?>
		<br />
		<br />
		&bull;&nbsp;Profile Saved
		<br />
		<br />
		You are now being forwarded to <a href="http://www.spatiallink.org/gistools/volunteer/">http://www.spatiallink.org/gistools/volunteer/</a>
		<meta http-equiv="refresh" content="2; url=http://www.spatiallink.org/gistools/volunteer/" />
		<br />
		<br />
		</td>
			</tr>
				</table>
		<?php
	} else {
		//	$query0 MUST have returned data, so move on W/O mysqli_error($varconnect)-->DUPLICATE-->PROMPT: this page WILL load completely, but ONLY after the prompt has been satisfied
		?>
		<script type="text/javascript">
		alert('Please Enter Unique Email');
		</script>
		<meta http-equiv="refresh" content="0; url=javascript:history.back()" />
		</td>
			</tr>
				</table>
		<?php
	}
/*	done */
?>
<!--include content-->
