<!--include content-->
<?php
/*	$varrefpage check */
if ($_SERVER['HTTP_REFERER'] == "http://www.spatiallink.org/gistools/volunteer/gisvolunteer_search.php") {
	/*	authorized access */
	?>	
			<table width="760" border="0" align="left" cellspacing="0" cellpadding="0" bgcolor="#C2C0C0">
		<tr>
	<td class="medium">
	<br />
	&bull;&nbsp;Top 
	<?php
	$num_rows = $_POST[num_rows];
	print $num_rows;
	?> 
	result[s] out of 10 maximum permissible mapped by zipcode
	 <br />
	 <br />
	 Courtesy:: TIGER Map Service [Content does not express or imply sponsorship or endorsement by US Census Bureau]
	 <br />
	 <br />
	 </td>
	 	</tr>		
		<tr>
	<td class="medium">
	<?php
	/*	tms [wid=.01 close]:		
	when you do a *+LEFT JOIN, all fields from all joined tables get selected. to retrieve their values, simply ensure that you have DISTINCT fieldnames. here, as per our query, `sl1_gisvolunteer_profile`.ZIPCODE = `sl1_gisvolunteer_location`.ZIPCODE
	
	refer to tigerdirectmap.php. also, refer to-
	[http://tiger.census.gov/instruct.html]
	[http://tiger.census.gov/cgi-bin/mapbrowser]
	[http://tiger.census.gov/cgi-bin/mapsurfer]
	[http://www.census.gov/cgi-bin/gazetteer]
	[http://pubweb.parc.xerox.com/map] PARC
	<img src="http://terraserver-usa.com/image.aspx?S=12&T=1&lat=37.36611&lon=-81.10278" />
	<img src="http://terraserver-usa.com/image.aspx?S=12&T=1&lat=37.36611&lon=-81.10278" />
	
	createmarker file-
	#tms-marker
	-73.73,40.8:black5:New York S
	-118.23,34.05:black5:Los Angeles
	and then call it, like so-
	http://tiger.census.gov/cgi-bin/mapgen?lon=-80&lat=40&wid=5&ht=5&off=CITIES&murl=http://tiger.census.gov/tigerwww/mission2
	OR
	select top 5 using $sn and map them, like so-
	$one = $_POST['longitude1'].",".$_POST['latitude1'].",bluedot10,".$_POST['zipcode1'];
	
	note: map is centered at the $latitude / $longitude of search, while points are mapped at lat / lon of results */
	//	map: use POST instead of GET for button. note that POSTED zip, lat / lon are different from those in the results. also, note that POST data includes num_rows, latitude, longitude, map [wid, ht, NOT label], filename_distinct
	$latitude = $_POST[latitude];
	$longitude = $_POST[longitude];
	$wid = $_POST[wid];
	$ht = $_POST[ht];
	$filename_distinct = $_POST[filename_distinct];
	//	do NOT cache image because of the possibility of having to cache many images and the extra time and space that would take. refer to img_gisvolunteer.php
	print "<img src=\"http://tiger.census.gov/cgi-bin/mapgen?lon=".$longitude."&lat=".$latitude."&wid=".$wid."&ht=".$ht."&iwd=760&iht=760&on=GRID,states,counties,places&murl=http://www.spatiallink.org/txt/volunteer/tms_".$filename_distinct.".txt\" />";
	?>
	</td>
		</tr>
		<!--this TR needed for formatting-->
		<tr>
	<td class="medium">
	</td>
		</tr>			
			</table>
	<?php
	/*	done */
} else {
	/*	UNauthorized access: forward */
	?>
	<META HTTP-EQUIV="Refresh" CONTENT="0; URL=http://www.spatiallink.org/gistools/volunteer/">
	<?php
	/*	done */
}
?>
<!--include content-->