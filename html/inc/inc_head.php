<!--header-->
<head>
	<?php
	/* script time: 1 microsecond is a millionth of 1 second */
	$mtimestart = microtime(true);
	/* done */
	/* require: first set environment and db variables */
	require '/var/chroot/home/content/57/3881957/html/inc/inc_environment.php';
	require '/var/chroot/home/content/57/3881957/html/inc/inc_db_variables.php';
	require '/var/chroot/home/content/57/3881957/html/inc/inc_db_csu.php';
	// require '/var/chroot/home/content/57/3881957/html/inc/inc_access_deny.php';
	require '/var/chroot/home/content/57/3881957/html/inc/inc_title.php';
	/* done */
	?>
	<!-- meta/link: start -->
	<meta charset="utf-8" />
	<!-- mobile: -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="index, follow" />
	<meta name="robots" content="noarchive" />
	<meta name="title" content="spatiallink_org" />
	<meta name="author" content="spatiallink_org, website@spatiallink.org" />
	<meta name="copyright" content="<?php print $varyear; ?>, spatiallink_org" />
	<meta name="keywords" content="spatiallink, spatial, link, geospatial, geo, geographic, information, system, GIS, GPS, webGIS, discuss, manage, empathy, collaborate, share, coordinate, proactive, active, action, volunteer, volunteerism, distributed volunteering, smart volunteering, cyber volunteering, open, source, opensource, tool, gistool, resource, create, search, match, profile, news, blog, chat, forum, WAP, wiki, map, plan, Python, PHP, MySQL, XML, database, geodatabase, disaster, community, world, globe, planet, earth, US, USA, united states, north america, america" />
	<meta name="description" content="
	<?php
	/* require description */
	require '/var/chroot/home/content/57/3881957/html/txt/txt_description.txt';
	/*	done */
	?>
	" />
	<link rel="icon" href="http://www.spatiallink.org/favicon.ico" />
	<link rel="shortcut icon" href="/favicon.ico" />
	<!-- desktop: -->
	<link rel="stylesheet" type="text/css" href="/css/spatiallink.css" media="only screen and (min-device-width: 1224px)" />
	<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1/themes/base/jquery-ui.css" media="only screen and (min-device-width: 1224px)" />
	<!-- mobile: tablet, ipad etc - custom theme + no theme. see dudamobile.com and mobilize.js -->
	<link rel="stylesheet" href="/css/mobile/spatiallink.min.css" media="only screen and (min-device-width: 768px) and (max-device-width: 1024px)" />
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.0/jquery.mobile.structure-1.3.0.css" media="only screen and (min-device-width: 768px) and (max-device-width: 1024px)" />
	<!-- mobile: smartphone, iphone etc - default theme. -webkit-min-device-pixel-ratio: 1.5; min-device-pixel-ratio: 1.5 -->
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.0/jquery.mobile-1.3.0.css" media="only screen and (max-device-width: 480px)" />
	<!-- meta/link: end -->
	<?php
	// error-prone: ex in wiki
	// print "<base href=\"http://".$_SERVER['HTTP_HOST']."/\" />";
	//
	// scripts: start
	?>
	<!-- <script src = "http://code.jquery.com/jquery-latest.min.js"></script> -->
	<script src = "//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	<script src = "//ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
	<script src = "http://code.highcharts.com/highcharts.js"></script>
	<script src = "//ajax.googleapis.com/ajax/libs/swfobject/2/swfobject.js"></script>	
	<script src = "/scripts/scr_sort.js"></script>
	<?php
	// <script src="http://www.google-analytics.com/urchin.js" ></script>
	/*
	<script>
		// analytics:
		_uacct = "UA-206037-6";
		urchinTracker();
	</script>
	*/
	/*
	<script>
		// mobile:
		if ((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i))) {
			location.replace("http://url-to-send-them/iphone.html");
		}
	</script>
	*/
	/*
	<script>
		// desktop:
		if (screen.width <= 1024) {
			<?php
			print '<script src = "http://code.jquery.com/mobile/1.3.0/jquery.mobile-1.3.0.min.js"></script>';
			?>
		}
	</script>
	*/
	?>
	<!-- scripts: end -->
</head>
<!--header-->
