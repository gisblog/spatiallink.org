<?php
if (isset($_POST[qsearchkeywords]) && $_SERVER['HTTP_REFERER'] == "http://www.spatiallink.org/") {
	/*	from form since $ exists. && condition to check if $ from withIN website:
	isset() should only be used for testing if the variable exists and NOT if the variable containes an empty "" string. empty() is designed for that. isset() returns TRUE if var exists; FALSE otherwise.	note that (empty($var)) is faster than ($var == '') */
	include '/var/chroot/home/content/57/3881957/html/inc_con/inc_con_indexed.php';
} else {
	/*	NOT from form since $ does NOT exist, OR $ NOT from withIN website */
	//	span can include its subsets, but outside of elements like form for XHTML compatibility
	//	http://www.apptools.com/examples/tableheight.php
	//	http://www.sitepoint.com/print/style-web-forms-css
	//	http://www.howtocreate.co.uk/tutorials/index.php?tut=0&part=16
	?>
	<!-- include content-->
	<style type="text/css">
		html, body {height:80%}
		#qsearch {width: 760px; height: 80%; background-color: #c2c0c0; font-family: verdana; font-size: 12px; font-weight: normal; color: #000000; text-align: left;}		
	</style>
			<table id="qsearch">
		<tr>
	<td width="120">
	</td>
	<td width="520" class="medium">
		<b>>> <a href="/gistools/volunteer/gisvolunteer_profile.php">Submit Profile</a> or <a href="/gistools/volunteer/gisvolunteer_search.php">Search, Map and Contact</a> Professionals and Volunteers</b>
		<br />
		<br />
		<li /><a href="http://www.fema.gov/sandy/">Hurricane Sandy</a>: Search for Disaster Recovery Center by <a href="sms:43362">texting "DRC" and your zip code to 43362 (4FEMA)</a>
		<br />
		<br />
		<li /><a href="http://maps.google.com/maps?q=haiti%20earthquake">Haiti Earthquake</a>: Donate $10 to the <a href="http://www.redcross.org/">Red Cross</a> by <a href="sms:90999">texting "Haiti" to 90999</a>
		<br />
		<br />
		<li />Volunteer for <a href="mailto:volunteer@spatiallink.org">Darfur relief efforts</a>
		<br />
		<br />
		<li />Selected Influenza A(H1N1) (Swine Flu) Maps: <a href="http://news.bbc.co.uk/1/hi/world/americas/8021547.stm">BBC</a>, <a href="http://www.nytimes.com/interactive/2009/04/27/us/20090427-flu-update-graphic.html">NYT</a>, <a href="http://www.washingtonpost.com/wp-srv/health/swineflu/map.html">POST</a>, <a href="http://www.google.org/flutrends/intl/en_mx/">Google</a>, <a href="http://www.healthmap.org/swineflu">HealthMap</a>, <a href="http://maps.google.com/maps/ms?ie=UTF8&hl=en&t=p&msa=0&msid=106484775090296685271.0004681a37b713f6b5950&source=embed&ll=32.398516,-107.885742&spn=16.66177,28.300781&z=5">Google My Maps</a>, <a href="http://news.geocommons.com/swineflu/">GeoCommons</a>
		<br />
		<br />
		<!-- building a global directory: explain search algorithm -->
		<?php
		print '>> Currently linking professionals and volunteers from ';
		$query = "SELECT DISTINCT STATE FROM `sl1_gisvolunteer_location` LEFT JOIN `sl1_gisvolunteer_profile` ON `sl1_gisvolunteer_location`.ZIPCODE = `sl1_gisvolunteer_profile`.ZIPCODE WHERE `sl1_gisvolunteer_profile`.SN_PROFILE > 3 AND `sl1_gisvolunteer_profile`.EMAIL NOT LIKE '%@SPATIALLINK.ORG' ORDER BY STATE ASC";
		$result = mysql_query($query) or die("Operation Failure :" . mysql_error());
		while ($row = mysql_fetch_array($result)) {
			//	fips - [SN COUNTY CO_NAME STATE ST_NAME]
			//	location - [SN LATITUDE LONGITUDE ZIPCODE PLACEname COUNTY STATE]
			//	profile - [SN_PROFILE ... ZIPCODE ...]
			//	include code2state
			include '/var/chroot/home/content/57/3881957/html/inc/inc_code2state.php';
		}
		//
		print 'and ';
		//
		$query_country = "SELECT DISTINCT COUNTRY FROM `sl1_gisvolunteer_location_co` LEFT JOIN `sl1_gisvolunteer_profile_co` ON `sl1_gisvolunteer_location_co`.COCODE = `sl1_gisvolunteer_profile_co`.COCODE WHERE `sl1_gisvolunteer_profile_co`.SN_PROFILE > 3 AND `sl1_gisvolunteer_profile_co`.EMAIL NOT LIKE '%@SPATIALLINK.ORG' ORDER BY COUNTRY ASC";
		$result_country = mysql_query($query_country) or die("Operation Failure :" . mysql_error());
		while ($row_country = mysql_fetch_array($result_country)) {
			//	fips - [SN COUNTY CO_NAME STATE ST_NAME]
			//	location - [SN LATITUDE LONGITUDE ZIPCODE PLACEname COUNTY STATE]
			//	profile - [SN_PROFILE ... ZIPCODE ...]
			printf($row_country['COUNTRY']."; ");
		}
		?>
		<br />
		<br />
		>> Sample Profile
		<?php
		//	include sampleprofile
		include '/var/chroot/home/content/57/3881957/html/inc_con/inc_con_gisvolunteer_sampleprofile.php';
		?>
		<br />
		<br /> 
		<?php
		//	include script
		?><script src="/scripts/scr_stringcomplex_c.js"></script><?php
		?><script src="/scripts/scr_qsearch.js"></script><?php
		//	random $qsearchkeywords_block: for ($i=0; $i<=8; $i++) {}. mapobject; smallworld; image processing; remote sensing; specialist; erdas; esri; lidar; photogrammetry;
		$qsearchkeywords_block0 = "\"gis consultant\" +smallworld";
		$qsearchkeywords_block1 = "+usda \"data acquisition\"";
		$qsearchkeywords_block2 = "transportation +penndot";
		$qsearchkeywords_block3 = "+digit*";
		$qsearchkeywords_block4 = "+parcel*";
		$qsearchkeywords_block5 = "+consult* +client*";
		$qsearchkeywords_block6 = "+cartographer";
		$qsearchkeywords_block7 = "+water";
		$qsearchkeywords_block8 = "-esri";
		$qsearchkeywords_block9 = "+americorps";
		//
		//	use rand (5, 15): 5 and 15 (INCLUSIVE)
		$qsearchkeywords_block = "qsearchkeywords_block".rand(0,9);
		?>
		<center>	
			<form method="post" name="qsearch" action="" onsubmit="return check_qsearch()">
			<input type="text" name="qsearchkeywords" value="<?php print $$qsearchkeywords_block; ?>" size="73" maxlength="73" />
			<br />
			<select name="location" size="1">
				<option value = "4" selected="selected">Inside United States</option>
				<option value = "5">Outside United States</option>
			</select>
			<br />
			<input type="hidden" name="javascript" value="" />
			<input type="submit" value="Link" />
			<input type="reset" value="Reset" />
			</form>
			<?php
			//	include script
			include '/var/chroot/home/content/57/3881957/html/inc_con/inc_con_index_disclaimer.php';
			print "<br /><br />";
			//	include board
			//	include '/var/chroot/home/content/57/3881957/html/inc_con/inc_con_index_board.php';
			?>
		</center>				
	</td>
	<td width="120">
	</td>
		</tr>
			</table>
	<!-- include content-->
	<?php
}
?>