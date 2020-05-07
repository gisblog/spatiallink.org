<?php
// intro xhtml: goes before anything else
/*
if (stristr($_SERVER['HTTP_ACCEPT'], "application/xhtml+xml")) 
  {
    header("content-Type: application/xhtml+xml; charset=utf-8");
  }
  else
  {
    header("content-type: text/html; charset=utf-8");
  }
*/
print "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
print "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"DTD/xhtml1-transitional.dtd\"	>";
print "<html	xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\">";
// done
?>

<?php
// include head
include '/var/chroot/home/content/57/3881957/html/inc/inc_head.php';
//	include reference: f() defined below
print "<body onload=\"spatiallink_gmaps();\">";
// include headernobody
include '/var/chroot/home/content/57/3881957/html/inc/inc_headernobody.php'; 
// include leftbar
include '/var/chroot/home/content/57/3881957/html/inc/inc_leftbar.php';
/*	include content here for cross-browser compatibility: check for API updates:v=1 @ http://groups-beta.google.com/group/Google-Maps-API. use GBrowserIsCompatible() to check browser compatibility.

	http://maps.google.com/maps?saddr=berkeley&daddr=1600+Amphitheatre+Parkway,+Mountain+View,+CA+94043%C2%A0(Google)&hl=en
	http://maps.google.com/maps?q=-33.86947,+151.20182+(Googlers+work+here+%3A-))&t=k&z=3
	http://maps.google.com/maps?q=1032+Lexington+Ave,+new+york,+ny+(Payard,%20mmm...chocolate)&hl=en
	http://maps.google.com/maps?q=1658+market+street,+san+francisco,+ca+(Zuni+Cafe...yummy+oysters)&hl=en

	WAP http://www.sreid.org/GMapViewer/
	OVERLAY http://gmaps.tommangan.us/tphoto.html */
?>
		<table width="520" border="0" align="left" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
<td class="medium">
<br />
<!--google local search-->
<form method="get" action="http://local.google.com/local">
What <input type="text" name="q" size="15" maxlength="255" value="" />
Where <input type="text" name="near" size="15" maxlength="255" value="" />
<input type="submit" name="btng" value="G Local Search" />
</form>
<!--google local search-->
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
<style type="text/css">
v\:* {
	behavior:url(#default#VML);
}
</style>
<script src="http://maps.google.com/maps?file=api&v=1&key=ABQIAAAAvs1RDRQjb2SY-mrRQA-afxQJuovN_ljd0lMt23zlUEkZPVyuixQshGX2Lhae7rPGJawJpUFmuS3u6Q" type="text/javascript">
</script>
<div id="map" style="width:520px;height:520px;">
</div>
<script type="text/javascript">
//<![CDATA[

	function spatiallink_gmaps() {
		//			
		//	create map
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
		//	center map at latitude / longitude of search and set zoomlevel: getBoundsLatLng() returns the lat / lng bounds of the map viewport. getSpanLatLng() returns the width / height of the map viewport in latitude / longitude ticks. while getZoomLevel() returns the integer zoom level of the map. and GBounds(minX, minY, maxX, maxY) creates a new bounds with the given coordinates.			
		map.centerAndZoom(new GPoint(-90.08, 29.96), 7);
    }

//]]>
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
// include rightbar
include '/var/chroot/home/content/57/3881957/html/inc/inc_rightbar.php'; 
//	include XHTML break all: may be NA if NOT sending header()
//	include '/var/chroot/home/content/57/3881957/html/inc/inc_xhtmlbreakall.php';
// include footer
include '/var/chroot/home/content/57/3881957/html/inc/inc_footer.php';
// done
?>