<?php
/*	image map [http://www.census.gov/geo/www/tiger/tigermap.html]:

	try fopen, like so-
	$filehandle = fopen("http://www.gis.regiononepdc.org/images/webfocus.gif", "r");
	$filecontent = fread($filehandle);
	fwrite($filehandle,$filecontent);
	fclose($filehandle);
	
	zipcode	latitude		longitude
	10001	40.750422	-73.996328
	20001	38.911936	-77.016719
	90001	33.973951	-118.248405 
	
	notes: the issue here is to reduce the time taken to fetch requested image from TMS. you can trigger the start in xhr.php, but until the image is returned js variable values do NOT get returned. so...
	
	also, refer to img.1.php */
//	$xhr_latitude = $row1['LATITUDE'];
//	$xhr_longitude = $row1['LONGITUDE'];
$xhr_latitude = 30.728388;
$xhr_longitude = -55.945754;

if ((!$xhr_latitude) || (!$xhr_longitude)) {
	print "No Lat / Lon Available";
} else {
	if (file_exists("/opt/bitnami/apache2/htdocs/images/volunteer/".$xhr_latitude.$xhr_longitude.".gif")) {
		/*	image exists */
		// print image
		print "<img src=\"/images/volunteer/".$xhr_latitude.$xhr_longitude.".gif\" alt=\"spatiallink_org\" width=\"100\" height=\"100\" align=\"bottom\" border=\"0\" />";
		/*	done */
	} else {
		// image does NOT exist
		/*	script time: 1 microsecond is a millionth of 1 second.
		
			The default time limit is 30 seconds or the value of max_execution_time() , which can be specified in the PHP initialization file. A zero (0) value means that the script will not time out at all.
			
			Also, refer to sleep, usleep, set_time_limit(seconds). set_time_limit() has no effect when PHP is running in safe mode. There is no workaround other than turning off safe mode or changing the time limit in the php.ini. When called, set_time_limit() restarts the timeout counter from zero. In other words, if the timeout is the default 30 seconds, and 25 seconds into script execution a call such as set_time_limit(20) is made, the script will run for a total of 45 seconds before timing out. Safe_mode() is off for SL1 */
		// set_time_limit does NOT work
		set_time_limit(1);
		$timestart = microtime(true);
		// create image
		$img_tms = imagecreatefromgif("http://tiger.census.gov/cgi-bin/mapgen/.gif?lon=".$xhr_longitude."&lat=".$xhr_latitude."&wid=.10&ht=.10&iwd=100&iht=100&mark=".$xhr_longitude.",".$xhr_latitude.",bluestar");
		// create image path
		$img_tms_path= "/opt/bitnami/apache2/htdocs/images/volunteer/".$xhr_latitude.$xhr_longitude.".gif";
		// save image to path once: this reduces reloading time
		imagegif($img_tms,$img_tms_path);
		// print image
		print "<img src=\"/images/volunteer/".$xhr_latitude.$xhr_longitude.".gif\" alt=\"spatiallink_org\" width=\"100\" height=\"100\" align=\"bottom\" border=\"0\" />";
		//	script time OR substr($mtimeend - $mtimestart,0,4) OR number_format($num, 10, '.', '')
		$timeend = microtime(true);
		$timedifference = round(abs($timeend - $timestart),2);
		print "$timedifference seconds";
		/*	done */
	}
}	
/*	done */
?>