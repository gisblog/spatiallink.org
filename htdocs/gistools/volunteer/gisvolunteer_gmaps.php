<?php
// include head
include '/opt/bitnami/apache2/htdocs/inc/inc_head.php';
/*	$varrefpage check */
if ($_SERVER['HTTP_REFERER'] == "http://www.spatiallink.org/gistools/volunteer/gisvolunteer_search.php") {
	/*	authorized access */
	//	include reference: f() defined below
	print "<body onload=\"spatiallink_gmaps();\">";
	// include headernobody
	include '/opt/bitnami/apache2/htdocs/inc/inc_headernobody.php'; 
	// do NOT include leftbar
	//	include '/opt/bitnami/apache2/htdocs/inc/inc_leftbar.php';
	//	include content here for cross-browser compatibility: refer to /gmaps/index.php
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
	 Courtesy:: Google Maps [Content does not express or imply sponsorship or endorsement by Google]
	 <br />
	 <br />
	 <div id="mapcentermessage">
	</div>
	 </td>
	 	</tr>
		<tr>
	<td class="medium">
	<?php
	//	map: to fetch $variables, use POST instead of GET for button. note that POSTED zip, lat / lon are different from those in the results. also, note that POST data includes num_rows, latitude, longitude, map [wid, ht, NOT label], filename_distinct
	$latitude = $_POST[latitude];
	$longitude = $_POST[longitude];
	$wid = $_POST[wid];
	$ht = $_POST[ht];
	$filename_distinct = $_POST[filename_distinct];
	//
	//	zoomlevel: $searchbuffer == 1-->$wid == 2 $searchbuffer == 2-->$wid == 4 $searchbuffer == 3-->$wid == 6
	switch ($wid) {
	case 2:
		$zoomlevel = 7;
		break;
	case 4:
		$zoomlevel = 9;
		break;
	case 6:
		$zoomlevel = 11;
		break;
	case 50:
		$zoomlevel = 13;
	}
	//
	//	map and read xml file
	?>
	<style type="text/css">
	v\:* {
		behavior:url(#default#VML);
	}
	</style>
	<script src="http://maps.google.com/maps?file=api&v=1&key=ABQIAAAAvs1RDRQjb2SY-mrRQA-afxQ92RnsTEVarc-T00QZwAMsiLac1xQqa60NGNLhGV2-LJGffeXRsDRa1g" type="text/javascript">
	</script>
	<div id="map" style="width:760px;height:760px;">
	</div>
	<script type="text/javascript">
		function spatiallink_gmaps() {
			//  if (GBrowserIsCompatible()) {
			//	var map = new GMap2(document.getElementById("map_canvas"));
			//	map.setCenter(new GLatLng(37.4419, -122.1419), 13);
			//  }		
			// create map
			var map = new GMap(document.getElementById("map"));
			map.addControl(new GLargeMapControl());
			map.addControl(new GMapTypeControl());
			//	map.setMapType(_SATELLITE_TYPE);
			//
			//	use event listener to echo lat / lng of map center after it is dragged or moved by user
			GEvent.addListener(map, "moveend", function() {
				var center = map.getCenterLatLng();
				var latLngStr = 'Approximate Map Center:: Lat ' + Math.round(center.y*100)/100 + ' / Lon ' + Math.round(center.x*100)/100;
				document.getElementById("mapcentermessage").innerHTML = latLngStr;
			});
			//
			//	center map at latitude / longitude of search and set zoomlevel		
			map.centerAndZoom(new GPoint(<?php print $longitude; ?>, <?php print $latitude; ?>), <?php print $zoomlevel; ?>);
			//
			//	create marker with info window: isNaN() evaluates an argument to determine if it is NotANumber. also, GMarker(point, icon) only takes in point and icon.		
			function createMarker(point, pointinfo) {
				//
				//	create marker
				var marker = new GMarker(point);
				//
				//	show marker info when it is clicked: the basic info method is openInfoWindow(), which takes a point and an HTML DOM element as an argument. openInfoWindowHtml() is similar, but it takes an HTML string as an argument rather than a DOM element. while openInfoWindowXslt() takes in an XML DOM element and the URL of an XSLT document to produce the info window contents. also, refer to var WINDOW_HTML.
				var htmlinfo = "<div id=\"mapbox\" style=\"width:75px;height:75px;font-size:12px;\" align=\"left\">" + pointinfo + "</div>";				
				GEvent.addListener(marker, "click", function() {
					marker.openInfoWindowHtml(htmlinfo);
				});
				return marker;
			}
			//
			//	load xml and map points at lat lon of results			
			var request = GXmlHttp.create();
			request.open("GET", "/txt/volunteer/gmaps_<?php print $filename_distinct; ?>.xml", true);
			request.onreadystatechange = function() {
			  if (request.readyState == 4) {
			    var xmlDoc = request.responseXML;
			    var markers = xmlDoc.documentElement.getElementsByTagName("marker");
			    for (var i = 0; i < markers.length; i++) {
				    var point = new GPoint(parseFloat(markers[i].getAttribute("lng")), parseFloat(markers[i].getAttribute("lat")));
				    var marker = createMarker(point, "[" + markers[i].getAttribute("sn") + "] " + markers[i].getAttribute("jobtitle"));				    
				    map.addOverlay(marker);
			    }
		    }
	    }
	    request.send(null);
	    }
	</script>
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
	<body>
	<META HTTP-EQUIV="Refresh" CONTENT="0; URL=http://www.spatiallink.org/gistools/volunteer/">
	<?php
}
//	do NOT include rightbar
//	include '/opt/bitnami/apache2/htdocs/inc/inc_rightbar.php';
//	include XHTML break all: may be NA if NOT sending header()
include '/opt/bitnami/apache2/htdocs/inc/inc_xhtmlbreakall.php'; 
// include footer
include '/opt/bitnami/apache2/htdocs/inc/inc_footer.php';
//	done
?>