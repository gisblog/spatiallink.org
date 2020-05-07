<?php
// require rss: require_once if multiple feeds
require_once '/opt/bitnami/apache2/htdocs/inc_con/rss/magpierss061/rss_fetch.inc';
//	include scripts_fct()
include '/opt/bitnami/apache2/htdocs/scripts_fct/scr_fct_cleanstring.php';
//	include line
//	include '/opt/bitnami/apache2/htdocs/inc/inc_line.php';
// done
?>
	
	<?php
	// include rss
	include '/opt/bitnami/apache2/htdocs/inc_con/rss/rss_volunteer.fema.php';
	?>
	<!--<br />-->
	<?php
	// include rss: multiple notes for 50 states W/O gradation, hence skipping http://www.weather.gov/alerts/us.rss in '/opt/bitnami/apache2/htdocs/inc_con/rss/rss_volunteer.noaa.php';
	?>
	<!--<br />-->
	&#186;&nbsp;<a href="http://www.spc.noaa.gov/products/wwa/">Storm Prediction Center - NOAA</a> [updated every 10 minutes]
	<br />
	<?php
	// include rss
	include '/opt/bitnami/apache2/htdocs/inc_con/rss/rss_volunteer.usgs.php';
	?>
	<!--<br />-->
	&#186;&nbsp;<a href="http://waterdata.usgs.gov/nwis/rt">Daily Streamflow Conditions - USGS</a> [updated every 1-4 hours]
	<br />
	&#186;&nbsp;<a href="http://www.afws.net/">Automated Flood Warning System - AFWS</a>
	<br />
	&#186;&nbsp;<a href="http://www.earthsat.com/wx/flooding/floodthreat.html">National Flash-Flood Status - EarthSat</a> [updated every 12 hours]
	<br />
	<?php
	// include rss
	include '/opt/bitnami/apache2/htdocs/inc_con/rss/rss_volunteer.google.php';
	?>