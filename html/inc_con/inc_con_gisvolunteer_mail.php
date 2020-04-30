<!--include content: @SL1 can submit profile, b/not display in search result, and thus, c/not be tested. using @TEST also contacts @SL1 -->
		<table width="760" border="0" align="left" cellspacing="0" cellpadding="0" bgcolor="#c2c0c0">
	<tr>
<td class="medium">
<?php
/*	$varrefpage check */
if ($_SERVER['HTTP_REFERER'] == "http://www.spatiallink.org/gistools/volunteer/gisvolunteer_search.php") {
	/*	include content */
	//	set variables: NO need to capture IP for text filename since using $varip
	$cocode = $_GET[cocode];
	$email = $_GET[email];
	$sn = $_GET[sn];
	//	query6
	$query6 = "SELECT * FROM `sl1_gisvolunteer_profile` WHERE SN_PROFILE = $sn";
	$result6 = mysql_query($query6) or die("Operation Failure :".mysql_error());
	while ($row6 = mysql_fetch_array($result6)) {
		//	retrieve professional/volunteer profile variables for automail and carryover: assignment MUST be inside while{} with the variable_whose_value_is_to_be_assigned on the left
		$gisvolunteer_email = $row6['EMAIL'];
		$gisvolunteer_firstname = $row6['FIRSTNAME'];
		$gisvolunteer_lastname = $row6['LASTNAME'];
	}
	//	check for NULL
	$num_rows = mysql_num_rows($result6);
	//	{send email} else {display error}
	if ($num_rows == 1) {
		//	{send email}: query6 returns data as expected
		//	job [13] variables from text file
			$filehandle1 = fopen("/var/chroot/home/content/57/3881957/html/txt/volunteer/automail_".$email."_".$varip.".txt", "r");
			/*	fscanf: scan file as parts
			
			** fscanf works a little awkwardly. a seemingly perfect function call like-
			fscanf($fh, "%s::%s");
			with a file like-
			user::password
			will not work. When fscanf looks for a string, it will look and stop at nothing except for a whitespace so :: and everything except whitespace is considered part of that string, however you can make it a little smarter by-
			fscanf($fh, "%[a-zA-Z0-9,. ]::%[a-zA-Z0-9,. ]" $var1, $var2); 
			which tells it that it can only accept a through z A through Z 0 through 9 a comma a period and a whitespace as input to the string, everything else cause it to stop taking in as input and continue parsing the line. This is very useful if you want to get a sentence into the string and you're not sure of exactly how many words to add, etc. 
			
			** also, instead of trying to think of every character that might be in your file, excluding the delimiter would be much easier. for example, if your delimiter was a comma use-
			%[^,]
			instead of-
			%[a-zA-Z0-9.| ... ]
			just make sure to use %[^,\n] on your last entry so you don't include the newline.
				
			** also, if you want to read text files in csv format or the like(no matter what character the fields are separated with), you should use fgetcsv() instead. When a text for a field is blank, fscanf() may skip it and fill it with the next text, whereas fgetcsv() correctly regards it as a blank field. 
				
			refer to http://us4.php.net/fscanf */
			//
			if ($cocode == "256") {
				//	for search INside US && FROM US- 13
				while ($filelog1 = fscanf($filehandle1, "%[^::]::%[^::]::%[^::]::%[^::]::%[^::]::%[^::]::%[^::]::%[^::]::%[^::]::%[^::]::%[^::]::%[^::]::%[^::]")) {
					// while you scan, list variables to the parts [values]. you may also list variables in fscanf, like so- fscanf($filehandle1, "%[a-zA-Z0-9,. ]::%[a-zA-Z0-9,. ]" $var1, $var2);
					list ($job_firstname,$job_lastname,$job_email,$job_jobtitle,$job_officename,$job_officestatus,$job_zipcode,$job_place,$job_state,$job_jobestimate,$job_joburgency,$job_vardate,$job_varip) = $filelog1;
				}
			} else {
				//	for search INside US && FROM NON-US- 11
				while ($filelog1 = fscanf($filehandle1, "%[^::]::%[^::]::%[^::]::%[^::]::%[^::]::%[^::]::%[^::]::%[^::]::%[^::]::%[^::]::%[^::]")) {
					// while you scan, list variables to the parts [values]. you may also list variables in fscanf, like so- fscanf($filehandle1, "%[a-zA-Z0-9,. ]::%[a-zA-Z0-9,. ]" $var1, $var2);
					list ($job_firstname,$job_lastname,$job_email,$job_jobtitle,$job_officename,$job_officestatus,$job_zipcode,$job_jobestimate,$job_joburgency,$job_vardate,$job_varip) = $filelog1;
				}
			}
			fclose($filehandle1);
			//
			//	convert variables
			if ($job_officestatus == 1) {
				$job_officestatus_conversion = "For-profit";
			} else if ($job_officestatus == 2) {
				$job_officestatus_conversion = "Government";
			} else {
				$job_officestatus_conversion = "Non-profit";
			}
			//
			if ($job_joburgency == 1) {
				$job_joburgency_conversion = "Low";
			} else if ($job_joburgency == 2) {
				$job_joburgency_conversion = "Medium";
			} else {
				$job_joburgency_conversion = "High";
			}
			//
			//	send automail: refer to urlencode and rawurlencode. self.
			$to = $gisvolunteer_email;
			$to_self = "volunteer@spatiallink.org";
			$subject = "spatiallink_org:: Professional/Volunteer Auto-Alert";
			if ($cocode == "256") {
				//	for search INside US && FROM US- 13
				$body = "Dear $gisvolunteer_firstname $gisvolunteer_lastname:\r\n\r\n You have been selected as a professional/volunteer by $job_firstname $job_lastname, $job_jobtitle, $job_officename. This contract job is expected to last approximately $job_jobestimate week[s] from $job_vardate [Year/Month/Date], and has been classified by $job_firstname as having \"".$job_joburgency_conversion."\" urgency. $job_firstname describes $job_officename as a \"".$job_officestatus_conversion."\" office located in $job_place $job_state. You can email $job_firstname at $job_email to know more about this contract job.\r\n\r\n Related Information:\r\n http://factfinder.census.gov/servlet/SAFFFacts?_event=Search&amp;_zip=$job_zipcode&amp;_lang=en&amp;_sse=on \r\n http://www.google.com/local?sc=1&amp;hl=en&amp;q=".urlencode($job_officename)."&amp;near=$job_zipcode&amp;btnG=Google+Search&amp;rl=1 \r\n\r\n Sincerely \r\n Auto-Volunteer \r\n spatiallink_org:: Linking Spatial Professionals and Volunteers Through Search, Profile, News, Blog, Forum, Map, Chat, WIKI, WAP and Other GIS Tools\r\n http://www.spatiallink.org/\r\n\r\n PS: You agreed to receive Auto-Alerts when you submitted your profile at http://www.spatiallink.org/. If you no longer wish to receive such Auto-Alerts, or have received this Auto-Alert in error, simply forward this email keeping the body intact to contact@spatiallink.org with the subject \"DELETE PROFILE\".";
			} else {
				//	for search INside US && FROM NON-US- 11
				$body = "Dear $gisvolunteer_firstname $gisvolunteer_lastname:\r\n\r\n You have been selected as a professional/volunteer by $job_firstname $job_lastname, $job_jobtitle, $job_officename. This contract job is expected to last approximately $job_jobestimate week[s] from $job_vardate [Year/Month/Date], and has been classified by $job_firstname as having \"".$job_joburgency_conversion."\" urgency. $job_firstname describes $job_officename as a \"".$job_officestatus_conversion."\" office located in $job_zipcode. You can email $job_firstname at $job_email to know more about this contract job.\r\n\r\n Sincerely \r\n Auto-Volunteer \r\n spatiallink_org:: Linking Spatial Professionals and Volunteers Through Search, Profile, News, Blog, Forum, Map, Chat, WIKI, WAP and Other GIS Tools\r\n http://www.spatiallink.org/\r\n\r\n PS: You agreed to receive Auto-Alerts when you submitted your profile at http://www.spatiallink.org/. If you no longer wish to receive such Auto-Alerts, or have received this Auto-Alert in error, simply forward this email keeping the body intact to contact@spatiallink.org with the subject \"DELETE PROFILE\".";
			}
			ini_set("sendmail_from", "volunteer@spatiallink.org");
			ini_set("SMTP", "spatiallink.org");
			if (mail($to, $subject, $body) && mail($to_self, $subject, $body)) {
				//	{send email}: sent
			?>
			<br />
			<br />
			&bull;&nbsp;Contact Initiated
			<br />
			<br />
			An email has been sent to the selected professional/volunteer with a brief description of the job. The professional/volunteer may respond as needed.
			<!--[<a href="javascript:self.close()">Close this window</a>]-->
			<br />
			<br />
			<?php
		} else {
			//	{send email}: NOT sent
			?>
			<br />
			<br />
			&bull;&nbsp;Contact Not Initiated
			<br />
			<br />
			The email could not be sent to the selected professional/volunteer. Please try again later. If this problem persists, please contact us with a brief description of the error.
			<br />
			<br />
			<?php
		}
	}
	//	{display error}: query6 does NOT return data [OR it was NOT a SELECT], OR it returns more than 1 email!
	/*	done */
} else {
	/*	do NOT include content: unauthorized access */
	?>
	<meta http-equiv="refresh" content="0; url=http://www.spatiallink.org/gistools/volunteer/" />
	<?php
	/*	done */
}
/* done */
?>	
</td>
	</tr>
		</table>
<?php
//	include XHTML break all: NA if NOT sending header()
include '/var/chroot/home/content/57/3881957/html/inc/inc_xhtmlbreakall.php';
?>
<!--include content-->