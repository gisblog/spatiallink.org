<?php
/*	$varrefpage check: NOT needed since there is no file named [gisvolunteer_searched.php]. hence, [http://www.spatiallink.org/gistools/volunteer/gisvolunteer_searched.php] will cause Error 404. */
//	require checkjs_clean here
require '/var/chroot/home/content/57/3881957/html/inc/inc_checkjs_clean.php';
?>
<!--include content-->
		<table width="760" border="0" align="left" cellspacing="0" cellpadding="0" bgcolor="#C2C0C0">
	<tr>
<td class="medium_bold">
<span class="medium_bold">
>> Search, Map and Contact
</span>
</td>
	</tr>		
<?php
//	note: do NOT include imgmenu since it reloads this page on submission
//	include scripts_fct(): can NOT redeclare f()
include '/var/chroot/home/content/57/3881957/html/scripts_fct/scr_fct_cleanstring.php';
/*	script time */
$timestart = microtime(true);
/*	done */
/*	common variables */
$cocode = $_POST[cocode];
$searchbuffer = $_POST[searchbuffer];
// to hide "skew" searchkeywords, do NOT add them to [JOBTITLE][JOBHISTORY] here
$searchkeywords = $_POST[searchkeywords];

if ($searchbuffer == "5") {
	//	for search OUTside US
	include '/var/chroot/home/content/57/3881957/html/inc_con/inc_con_gisvolunteer_searched_co.php';
} else {
	//	for search INside US
	if ($cocode == "256") {
		//	...&& FROM US: POSTED zip, lat / lon are different from those in the results. $zipcode used in searchmonitor.txt; $latitude and $longitude used to re-center map [gmap, tms].
		$zipcode = $_POST[zipcode];
		$latitude = $_POST[latitude];
		$longitude = $_POST[longitude];
	} else {
		//	...&& FROM NON-US: NO $_POST[zipcode]-->GET $cocode. $searchbuffer MUST be "4"-->map re-center @ 38 / -96.
		//	http://www.quirksmode.org/js/forms.html#sselect
		$query_country = "SELECT COUNTRY FROM `spatiallink`.`sl1_gisvolunteer_location_co` WHERE COCODE = $cocode";
		$result_country = mysql_query($query_country) or die("Operation Failure :" . mysql_error());
		//	check for NULL: NA
		//	print search results
		while ($row_country = mysql_fetch_array($result_country)) {
			// $zipcode for map
			$zipcode = $row_country['COUNTRY'];
		}
	}
	/*	calculate bounding box: note that any such calculations would be performed across 42,192 rows of data.
	
		1* of latitude converts to ~70 miles. however the conversion of miles to longitude varies depending on location [http://www.experts-exchange.com/Databases/GIS_GPS/Q_20629088.html].
				
		The radius of the Earth is assumed to be 6 378.1 km, or 3,963.0 miles. Thus, approximate distance in miles:
		sqrt(x * x + y * y)
		where x = 69.1 * (lat2 - lat1) 
		and y = 53.0 * (lon2 - lon1) 
	
		You can improve the accuracy of this approximate distance calculation by adding the cosine math function: 
		sqrt(x * x + y * y)
		where x = 69.1 * (lat2 - lat1) 
		and y = 69.1 * (lon2 - lon1) * cos(lat1/57.3) 
		[http://www.meridianworlddata.com/Distance-Calculation.asp]
		
		ZIPCODE	|			|			PLACE			STATE
		25301		38.328948	-81.605094	CHARLESTON	WV
		25801		37.767248	-81.216446	BECKLEY			WV
		24740		37.379876	-81.117475	PRINCETON 		WV
		24701		37.332725 	-81.160066	BLUEFIELD 		WV
		24060		37.256283	-80.43473	BLACKSBURG		VA
		24001		37.274175	-79.95786 	ROANOKE 		VA
		20001		38.911936	-77.016719	WASHINGTON	DC
	
		thus,
		latitude difference --- between princeton and beckley, is 0.387372~0.400000~0.500000.
		longitude difference ||| between princeton and blacksburg, is 0.682745~0.700000.
	
		tms-
		longitude <-67 and -125 degrees>
		latitude <24 and 49 degrees>
			
		also, refer to [http://quake.geo.berkeley.edu/convert/distance.html].
		
		you can also compare by zipcode by first splitting the zipcode. note that since $zipcode is not an array [only 1 entry], array, array_slice or array_end will NOT work; and to explode, you need a delimiter. also, refer to xhr. $zipcodestring = $zipcode{0}.$zipcode{1}.$zipcode{2};
		
		THUS:
		[0.75, 1]	[1.5, 2]		[3, 4]
		[50 miles]	[100 miles]	[200 miles]
		[1 hour]		[2 hours]	[4 hours]
		
		so, for [0.75, 1]-
		|<--52 miles--*--52 miles-->|
		
		| 53 miles
		-
		| 53 miles
		OR
		Find members with this zipcodeid and calculate distance-
		SELECT zipcode.zipcodeid, 3963 * ACOS(COS(RADIANS(90-40.615000)) * COS(RADIANS(90-zipcode.latitude)) + SIN(RADIANS(90-40.615000)) * SIN(RADIANS(90-zipcode.latitude)) * COS(RADIANS(-111.891300-zipcode.longitude))) AS dist, members.*, photos.* FROM members LEFT JOIN zipcode ON members.zipcodeid = zipcode.zipcodeid WHERE members.zipcodeid LIKE '28063' ORDER BY dist;
	
		also, refer to-
		[http://forums.devshed.com/t209720/s.html&highlight=zipcode]
		[http://forums.devshed.com/t136261/s.html&highlight=zipcode]
	//
	//	tms [wid=.01 close]. wid / ht=number, the desired width in decimal degrees of lat / lon of map coverage [contiguous country- 48 states]:
	//				 ____49, -67
	//				|____|
	//	24, -125
	//	lat	|<--12--*--12-->|
	//	lon	|<--28--*--28-->|
	//
	//	map coverage, including alaska and hawaii [entire country- 50 states]:
	//				 ____70, -70
	//				|____|
	//	20, -170
	//	lat	|<--25--*--25-->|
	//	lon	|<--50--*--50-->| */
	if ($searchbuffer == 1) {
		//	50 miles
		$x = 0.75;
		$y = 1;
		// map
		$wid = 2;
		$ht = 2;
		$label = "redball";
	} elseif ($searchbuffer == 2) {
		//	100 miles
		$x = 1.5;
		$y = 2;
		// map
		$wid = 4;
		$ht = 4;
		$label = "reddot10";
	} elseif ($searchbuffer == 3) {
		//	200 miles
		$x = 3;
		$y = 4;
		// map
		$wid = 6;
		$ht = 6;
		$label = "smalldot";
	} elseif ($searchbuffer == 4) {
		//	inside united states
		$x = 12;
		$y = 28;
		// map
		$wid = 50;
		$ht = 20;
		$label = "smalldot";
		//	map re-center
		$latitude = 38;
		$longitude = -96;
	}
	//	http://tiger.census.gov/instruct.html
	//	http://tiger.census.gov/cgi-bin/mapgen/.gif?lat=36&lon=-96&wid=50&ht=24&iht=230&iwd=400
	$latitude_xleft = $latitude - $x;
	$latitude_xright = $latitude + $x;
	$longitude_ybottom = $longitude - $y;
	$longitude_ytop = $longitude + $y;
	/* done */
	//
	//	monitor whether searchkeyword[s] return data or not: optionally, may send automail to self
	$filehandle0 = fopen("/var/chroot/home/content/57/3881957/html/txt/volunteer/searchmonitor.txt", "a");
	$filecontent0 = " || $vardate::$varip::$zipcode::$searchkeywords\r\n";
	fwrite($filehandle0, $filecontent0);
	fclose($filehandle0);	
	//
	/*	query: FULLTEXT+JOIN [1] SELECT/FROM/LEFT JOIN ON AND [condition]/WHERE [condition] AND MATCH AGAINST-
	
		FULLTEXT+JOIN [2] SELECT AS/MATCH AGAINST/FROM/LEFT JOIN ON/WHERE [condition] AND MATCH AGAINST-
	
		FULLTEXT+JOIN [3] SELECT/FROM/LEFT JOIN ON/WHERE [condition] AND MATCH AGAINST [http://lists.mysql.com/mysql/179094]-
	
		FULLTEXT+JOIN [4] SELECT *,/MATCH AGAINST/FROM/LEFT JOIN/WHERE [condition] AND MATCH AGAINST- see below
		
		note that only [2] and [4] will give you relevance [AS relevance ORDER BY relevance DESC LIMIT 10]. you can also print match relevance as $row4['relevance']. This is more complex. The query returns the relevance values and it also sorts the rows in order of decreasing relevance. To achieve this result, you should specify MATCH() twice: once in the SELECT list and once in the WHERE clause. This causes no additional overhead, because the MySQL optimizer notices that the two MATCH() calls are identical and invokes the full-text search code only once.
		
		also, see GROUP BY. refer to mysql.1.sql
		
		TEST:
		$searchkeywords: "nasdaq building voip"
		-mtc-	-rlv-					-vex-	-exp-	-edu-	-pro-
		YY 		1.16 building*3	2			2			2			2
		AA 		0.6 Nasdaq*1		3			4			4			3
		ZZ		0.0 voip*2			3			3			3			3
		XX		0.0 voip*1			1			1			1			1 
		
		WAY TO MAKE A FULLTEXT SEARCH FOR A LARGE NUMBER OF TABLES FASTER:
		1. For example, we have 4 tables with fulltext index. We need to perform fast search on them.
		2. Do the following:
			CREATE TEMPORARY TABLE xxx SELECT id, name, MATCH(name) AGAINST ('search_string') AS relevancy FROM table1;
			INSERT INTO xxx SELECT id, name, MATCH(name) AGAINST ('search_string') AS relevancy FROM table2 ...
		3. Then, when the temporary table is filled with the data, do the simple select from it:
			SELECT id, name FROM xxx ORDER BY relevancy DESC */
	//	also, note the *,
	//	add "skew" searchkeywords to [JOBTITLE][JOBHISTORY] here, like so:
	//		MATCH(JOBHISTORY, JOBTITLE) AGAINST ('".$searchkeywords." volunteer')
	//		MATCH(JOBHISTORY, JOBTITLE) AGAINST ('".$searchkeywords." volunteer' IN BOOLEAN MODE)	
	$query_search = "SELECT *, MATCH(JOBHISTORY, JOBTITLE) AGAINST ('".$searchkeywords."') AS relevance FROM `sl1_gisvolunteer_profile` LEFT JOIN `sl1_gisvolunteer_location` ON `sl1_gisvolunteer_profile`.ZIPCODE = `sl1_gisvolunteer_location`.ZIPCODE WHERE `sl1_gisvolunteer_profile`.SN_PROFILE > 3 AND `sl1_gisvolunteer_profile`.EMAIL NOT LIKE '%@SPATIALLINK.ORG' AND `sl1_gisvolunteer_location`.LATITUDE >= $latitude_xleft AND `sl1_gisvolunteer_location`.LATITUDE <= $latitude_xright AND `sl1_gisvolunteer_location`.LONGITUDE >= $longitude_ybottom AND `sl1_gisvolunteer_location`.LONGITUDE <= $longitude_ytop AND MATCH(JOBHISTORY, JOBTITLE) AGAINST ('".$searchkeywords."' IN BOOLEAN MODE) ORDER BY relevance DESC, `sl1_gisvolunteer_profile`.EXPERIENCE DESC, `sl1_gisvolunteer_profile`.EDUCATION DESC, `sl1_gisvolunteer_profile`.VEXPERIENCE DESC, `sl1_gisvolunteer_profile`.PROFICIENCY DESC LIMIT 10";
	$result_search = mysql_query($query_search) or die("Operation Failure :" . mysql_error());
	/*	done */
	/*	script time OR substr($mtimeend - $mtimestart,0,4) OR number_format($num, 10, '.', '') */
	$timeend = microtime(true);
	$timedifference = round(abs($timeend - $timestart),2);
	/*	done */
	/*	check for NULL and print search results */
	$num_rows = mysql_num_rows($result_search);
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
		/*	$query0 MUST have returned data */
		/*	textfile */
		//	'w' Open for writing only; place the file pointer at the beginning of the file and truncate the file to zero length. If the file does not exist, attempt to create it.
		//	'w+' Open for reading and writing; place the file pointer at the beginning of the file and truncate the file to zero length. If the file does not exist, attempt to create it.
		//	'a' Open for writing only; place the file pointer at the end of the file. If the file does not exist, attempt to create it.
		//	'a+' Open for reading and writing; if the file does not exist, attempt to create it. also, refer to file_put_contents
		//	+ip if email is dummy and NOT distinct, or use file_exists	
		//	POSTED zip, lat / lon are different from those in the results
		$filehandle1 = fopen("/var/chroot/home/content/57/3881957/html/txt/volunteer/automail_".$_POST[email]."_".$varip.".txt", "w");
		//	except for [latitude], [longitude], [searchkeywords]			
		if ($cocode == "256") {
			//	for search INside US && FROM US- 13
			$filecontent1 = "$_POST[firstname]::$_POST[lastname]::$_POST[email]::$_POST[jobtitle]::$_POST[officename]::$_POST[officestatus]::$zipcode::$_POST[place]::$_POST[state]::$_POST[jobestimate]::$_POST[joburgency]::$vardate::$varip";
		} else {
			//	for search INside US && FROM NON-US- 11: $zipcode is country for automail
			$filecontent1 = "$_POST[firstname]::$_POST[lastname]::$_POST[email]::$_POST[jobtitle]::$_POST[officename]::$_POST[officestatus]::$zipcode::$_POST[jobestimate]::$_POST[joburgency]::$vardate::$varip";
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
			<form method="post" name="gisvolunteer_gmaps" action="gisvolunteer_gmaps.php" target="_blank">
			<input type="submit" value="Google" />
			<?php
			//	map: use POST instead of GET for button. note that POSTED zip, lat / lon are different from those in the results. also, note that POST data includes num_rows, latitude, longitude, map [wid, ht, NOT label], filename_distinct
			print "<input readonly=\"readonly\" type=\"hidden\" name=\"num_rows\" id=\"num_rows\" value=\"".$num_rows."\" size=\"2\" maxlength=\"2\" /><input readonly=\"readonly\" type=\"hidden\" name=\"latitude\" id=\"latitude\" value=\"".$latitude."\" size=\"11\" maxlength=\"11\" /><input readonly=\"readonly\" type=\"hidden\" name=\"longitude\" id=\"longitude\" value=\"".$longitude."\" size=\"11\" maxlength=\"11\" /><input readonly=\"readonly\" type=\"hidden\" name=\"wid\" id=\"wid\" value=\"".$wid."\" size=\"1\" maxlength=\"1\" /><input readonly=\"readonly\" type=\"hidden\" name=\"ht\" id=\"ht\" value=\"".$ht."\" size=\"1\" maxlength=\"1\" /><input readonly=\"readonly\" type=\"hidden\" name=\"filename_distinct\" id=\"filename_distinct\" value=\"".$filename_distinct."\" size=\"25\" maxlength=\"25\" />";
			?>
			</form>
			<!--gmaps-->
			<!--tms-->
			<form method="post" name="gisvolunteer_tms" action="gisvolunteer_tms.php" target="_blank">
			<input type="submit" value="US Census Bureau" />
			<?php
			print "<input readonly=\"readonly\" type=\"hidden\" name=\"num_rows\" id=\"num_rows\" value=\"".$num_rows."\" size=\"2\" maxlength=\"2\" /><input readonly=\"readonly\" type=\"hidden\" name=\"latitude\" id=\"latitude\" value=\"".$latitude."\" size=\"11\" maxlength=\"11\" /><input readonly=\"readonly\" type=\"hidden\" name=\"longitude\" id=\"longitude\" value=\"".$longitude."\" size=\"11\" maxlength=\"11\" /><input readonly=\"readonly\" type=\"hidden\" name=\"wid\" id=\"wid\" value=\"".$wid."\" size=\"1\" maxlength=\"1\" /><input readonly=\"readonly\" type=\"hidden\" name=\"ht\" id=\"ht\" value=\"".$ht."\" size=\"1\" maxlength=\"1\" /><input readonly=\"readonly\" type=\"hidden\" name=\"filename_distinct\" id=\"filename_distinct\" value=\"".$filename_distinct."\" size=\"25\" maxlength=\"25\" />";
			?>
			</form>
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
			while ($row_search = mysql_fetch_array($result_search)) {
				/*	mysql_fetch_array() consists of many arrays, like $zipcode. within the while{}, you can extract some individual row values. outside the while{}, $zipcodepieces will return the last value assigned:
				
				$zipcodepieces = $zipcode{0}.$zipcode{1}.$zipcode{2}.$zipcode{3}.$zipcode{4};
				print $zipcodepieces; 
				OR
				use $sn with input/hidden to GET/POST fieldvalue, like so-
				$sn=1;
				while {
					$sn = $row['FIELDVALUE'];
					$sn++;
				}
				OR
				fwrite()-->murl
					
				refer below and [http://us4.php.net/mysql_fetch_array] */
				//	note that printf does NOT work for JOBHISTORY. also, here we are using td>>div to limit text overflow. it can also be limited using php sprintf or js.
				//	append to murl file for tms: for murl, tms requires lat / lon in 2 decimals ONLY. also, note "\r\n" and refer to http://www.lookuptables.com
				//	POSTED zip, lat / lon are different from those in the results
				//
				//	gmaps
				$filehandle2b = fopen("/var/chroot/home/content/57/3881957/html/txt/volunteer/gmaps_".$filename_distinct.".xml", "a");
				$filecontent2b = "<marker sn=\"".$sn."\" lat=\"".round($row_search['LATITUDE'],2)."\" lng=\"".round($row_search['LONGITUDE'],2)."\" jobtitle=\"".$row_search['JOBTITLE']."\" />\r\n";
				fwrite($filehandle2b, $filecontent2b);
				//
				//	tms
				$filehandle3 = fopen("/var/chroot/home/content/57/3881957/html/txt/volunteer/tms_".$filename_distinct.".txt", "a");
				$filecontent3 = round($row_search['LONGITUDE'],2).",".round($row_search['LATITUDE'],2).":".$label.":[".$sn."] ".$row_search['JOBTITLE']."\r\n";
				fwrite($filehandle3, $filecontent3);
				fclose($filehandle3);
				//
				//	fee
				if ($row_search['FEE'] == '1') {
					$fee = 'Volunteer';
				} elseif ($row_search['FEE'] == '2') {
					$fee = 'USD $1 - USD $25';
				} elseif ($row_search['FEE'] == '3') {
					$fee = 'USD $26 - USD $50';
				} elseif ($row_search['FEE'] == '4') {
					$fee = 'USD $51 - USD $75';
				} elseif ($row_search['FEE'] == '5') {
					$fee = 'USD $76 - USD $100';
				} elseif ($row_search['FEE'] == '6') {
					$fee = 'USD $101+';
				}					
				?>
					<tr>
				<td class="medium">	
				<?php
				//	using print() to avoid printf() "Too few arguments":
				// xml and &: &amp;, %26
				print("[".$sn."] ".$row_search['JOBTITLE'].":: [Fee Estimate = ".$fee." Per Hour]["."<a href=\"gisvolunteer_mail.php?cocode=".$cocode."&amp;email=".$_POST[email]."&amp;sn=".$row_search['SN_PROFILE']."\" target=\"_blank\">Contact</a>]<br /><div class=\"gisvolunteer\">".fct_cleanstring($row_search['JOBHISTORY'])."</div>");
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
}
/*	done */
?>
<!--include content-->