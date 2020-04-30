<?php
/*	for search OUTside US: ...&& FROM US OR ...&& FROM NON-US */
//	$searchbuffer == "5" and bounding box == NA. no $zipcode, no tms, just get $latitude, $longitude.
$query_country = "SELECT COUNTRY FROM `spatiallink`.`sl1_gisvolunteer_location_co` WHERE COCODE = $cocode";
//	query MUST return [1] result/data
$result_country = mysql_query($query_country) or die("Operation Failure :" . mysql_error());
while ($row_country = mysql_fetch_array($result_country)) {
	//	searchmonitor_co.txt
	$country = $row_country['COUNTRY'];
}
//
$x = 80;
$y = 180;
//	map: NO tms-->NO $wid, $ht and $label
//	$wid = 50;
//	$ht = 20;
//	$label = "smalldot";
//	map re-center: Lat 4.83 / Lon 1.65
$latitude = 0;
$longitude = 0;
//	map extent
$latitude_xleft = $latitude - $x;
$latitude_xright = $latitude + $x;
$longitude_ybottom = $longitude - $y;
$longitude_ytop = $longitude + $y;
//
//	monitor whether searchkeyword[s] return data or not: optionally, may send automail to self
$filehandle0 = fopen("/var/chroot/home/content/57/3881957/html/txt/volunteer/searchmonitor.txt", "a");
$filecontent0 = " || $vardate::$varip::$country::$searchkeywords\r\n";
fwrite($filehandle0, $filecontent0);
fclose($filehandle0);
//
/* done */
/*	query: refer to inc_con_gisvolunteer_searched.php */
$query_search_country = "SELECT *, MATCH(JOBHISTORY, JOBTITLE) AGAINST ('".$searchkeywords."') AS relevance FROM `sl1_gisvolunteer_profile_co` LEFT JOIN `sl1_gisvolunteer_location_co` ON `sl1_gisvolunteer_profile_co`.COCODE = `sl1_gisvolunteer_location_co`.COCODE WHERE `sl1_gisvolunteer_profile_co`.SN_PROFILE > 3 AND `sl1_gisvolunteer_profile_co`.EMAIL NOT LIKE '%@SPATIALLINK.ORG' AND `sl1_gisvolunteer_location_co`.LATITUDE >= $latitude_xleft AND `sl1_gisvolunteer_location_co`.LATITUDE <= $latitude_xright AND `sl1_gisvolunteer_location_co`.LONGITUDE >= $longitude_ybottom AND `sl1_gisvolunteer_location_co`.LONGITUDE <= $longitude_ytop AND MATCH(JOBHISTORY, JOBTITLE) AGAINST ('".$searchkeywords."' IN BOOLEAN MODE) ORDER BY relevance DESC, `sl1_gisvolunteer_profile_co`.EXPERIENCE DESC, `sl1_gisvolunteer_profile_co`.EDUCATION DESC, `sl1_gisvolunteer_profile_co`.VEXPERIENCE DESC, `sl1_gisvolunteer_profile_co`.PROFICIENCY DESC LIMIT 10";
$result_search_country = mysql_query($query_search_country) or die("Operation Failure :" . mysql_error());
/*	done */
/*	script time OR substr($mtimeend - $mtimestart,0,4) OR number_format($num, 10, '.', '') */
$timeend = microtime(true);
$timedifference = round(abs($timeend - $timestart),2);
/*	done */
/*	check for NULL and print search results */
$num_rows = mysql_num_rows($result_search_country);
if ($num_rows == 0) {
	// $query0 does NOT return data [OR it was NOT a SELECT]:
	?>
		<tr>
	<td class="medium">
	<br />
	&bull;&nbsp;Your search did not match any profiles
	<br />
	Please try more general keywords and make sure they are spelled correctly, or expand the buffer, then <a href="javascript:history.back()">search again</a>.
	<br />
	<br />
	</td>
		</tr>
			</table>
	<?php
} else {
	/*	$query_search_country MUST have returned data: refer to inc_con_gisvolunteer_searched.php */
	$filehandle1 = fopen("/var/chroot/home/content/57/3881957/html/txt/volunteer/automail_".$_POST[email]."_".$varip.".txt", "w");
	//	except for [latitude], [longitude], [searchkeywords]			
	if ($cocode == "256") {
		//	for search INside US && FROM US- 13
		$filecontent1 = "$_POST[firstname]::$_POST[lastname]::$_POST[email]::$_POST[jobtitle]::$_POST[officename]::$_POST[officestatus]::$zipcode::$_POST[place]::$_POST[state]::$_POST[jobestimate]::$_POST[joburgency]::$vardate::$varip";
	} else {
		//	for search INside US && FROM NON-US- 11: $zipcode is country for automail
		$filecontent1 = "$_POST[firstname]::$_POST[lastname]::$_POST[email]::$_POST[jobtitle]::$_POST[officename]::$_POST[officestatus]::$country::$_POST[jobestimate]::$_POST[joburgency]::$vardate::$varip";
	}
	fwrite($filehandle1, $filecontent1);
	fclose($filehandle1);	
	/*	done */
	?>
		<tr>
	<td class="medium">
	<br />
	&bull;&nbsp;Top <?php print $num_rows; ?> result[s] out of 10 maximum permissible ordered by relevance [<?php print $timedifference; ?> seconds][<a href="javascript:history.back()">Search again</a>]
	<br />
	<br />
	Map Search Results::
	<br />
	<br />
	</td>
		</tr>
		
		<tr>
	<td class="medium">
	<!--include cell table: layout. class needs to be defined for every cell table or else IE will NOT render correctly-->
				<table width="750" border="0" align="left" cellspacing="0" cellpadding="0" bgcolor="#C2C0C0">
		<?php		
		//	set microtime() once for both forms to avoid "no such file or directory" error
		$filename_distinct_space = microtime(true);
		//	note: in gmaps-->request.open(), js does NOT like space in filename/path. refer to http://jennifermadden.com/javascript/stringEscape.html
		$filename_distinct = str_replace(" ", "_", $filename_distinct_space);		
		?>
		<!--gmaps-->
		<form method="post" name="gisvolunteer_gmaps" action="gisvolunteer_gmaps_co.php" target="_blank">
		<input type="submit" value="Google" />
		<?php
		//	map: use POST instead of GET for button. note that POSTED zip, lat / lon are different from those in the results. also, note that POST data includes num_rows, latitude, longitude, map [wid, ht, NOT label], filename_distinct
		print "<input readonly=\"readonly\" type=\"hidden\" name=\"num_rows\" id=\"num_rows\" value=\"".$num_rows."\" size=\"2\" maxlength=\"2\" /><input readonly=\"readonly\" type=\"hidden\" name=\"filename_distinct\" id=\"filename_distinct\" value=\"".$filename_distinct."\" size=\"25\" maxlength=\"25\" />";
		?>
		</form>
		<!--gmaps-->
		<!--tms-->
		<!--tms-->
		<br />
		<br />
		<?php
		//	sn
		$sn = 1;
		//
		//	gmaps
		$filehandle2a = fopen("/var/chroot/home/content/57/3881957/html/txt/volunteer/gmaps_".$filename_distinct.".xml", "a");
		$filecontent2a = "<markers>\r\n";
		fwrite($filehandle2a, $filecontent2a);	
		//
		while ($row_search_country = mysql_fetch_array($result_search_country)) {
			/*	mysql_fetch_array(): refer to inc_con_gisvolunteer_searched_co.php	*/
			//	gmaps
			$filehandle2b = fopen("/var/chroot/home/content/57/3881957/html/txt/volunteer/gmaps_".$filename_distinct.".xml", "a");
			$filecontent2b = "<marker sn=\"".$sn."\" lat=\"".round($row_search_country['LATITUDE'],2)."\" lng=\"".round($row_search_country['LONGITUDE'],2)."\" country=\"".$row_search_country['COUNTRY']."\" jobtitle=\"".$row_search_country['JOBTITLE']."\" />\r\n";
			fwrite($filehandle2b, $filecontent2b);
			//	do NOT close
			//	tms
			//	tms
			//
			//	fee
			if ($row_search_country['FEE'] == '1') {
				$fee = 'Volunteer';
			} elseif ($row_search_country['FEE'] == '2') {
				$fee = 'USD $1 - USD $25';
			} elseif ($row_search_country['FEE'] == '3') {
				$fee = 'USD $26 - USD $50';
			} elseif ($row_search_country['FEE'] == '4') {
				$fee = 'USD $51 - USD $75';
			} elseif ($row_search_country['FEE'] == '5') {
				$fee = 'USD $76 - USD $100';
			} elseif ($row_search_country['FEE'] == '6') {
				$fee = 'USD $101+';
			}					
			?>
				<tr>
			<td class="medium">	
			<?php
			//	using print() to avoid printf() "Too few arguments"
			print("[".$sn."] ".$row_search_country['JOBTITLE'].":: [Fee Estimate = ".$fee." Per Hour]["."<a href=\"gisvolunteer_mail.php?cocode=".$cocode."&email=".$_POST[email]."&sn=".$row_search_country['SN_PROFILE']."\" target=\"_blank\">Contact</a>]<br /><div class=\"gisvolunteer\">".fct_cleanstring($row_search_country['JOBHISTORY'])."</div>");
			?>
			<br />
			<br />
			</td>
				</tr>
			<?php
		//	sn
		$sn++;
		//
		}
		//	gmaps
		$filehandle2c = fopen("/var/chroot/home/content/57/3881957/html/txt/volunteer/gmaps_".$filename_distinct.".xml", "a");
		$filecontent2c = "</markers>";
		fwrite($filehandle2c, $filecontent2c);
		fclose($filehandle2c);		
		?>
					</table>
	<!--include cell table-->			
	</td>
		</tr>		
			</table>
	<?php
}
/*	done */
?>
<!--include content-->