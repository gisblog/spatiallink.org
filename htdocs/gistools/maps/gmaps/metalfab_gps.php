<!--**gps**-->
<center>
<script src="http://maps.google.com/maps?file=api&v=1&key=ABQIAAAAvs1RDRQjb2SY-mrRQA-afxQ2VAGNDc2emVRAq5RC4ikZxaze9BRQy8loFwp0ybSQoZ5cpyZxNGnyiQ" type="text/javascript"></script>
<script type="text/javascript">
//<![CDATA[
	function gps(sn,xx,yy) {
		if (!sn) {
			var xx = -81.10;
			var yy = 37.60;
			var zz = 8;
		} else {
			//	var xx;
			//	var yy;
			var zz = 2;
		}
		var map = new GMap(document.getElementById("map"));
		map.addControl(new GLargeMapControl());
		map.addControl(new GMapTypeControl());
		map.centerAndZoom(new GPoint(xx, yy), zz);
		function createMarker(point, pointinfo) {
			var marker = new GMarker(point);
			var htmlinfo = "<div id=\"mapbox\" style=\"width:250px;height:200px;font-size:12px;\" align=\"left\">" + pointinfo + "</div>";
			GEvent.addListener(marker, "click", function() {
				marker.openInfoWindowHtml(htmlinfo);
			});
			return marker;
		}
		var request = GXmlHttp.create();
		request.open("GET", "/metalfab/gps/<?php print $filename_distinct; ?>.xml", true);
		request.onreadystatechange = function() {
			if (request.readyState == 4) {
				var xmlDoc = request.responseXML;
				var markers = xmlDoc.documentElement.getElementsByTagName("marker");
				for (var i = 0; i < markers.length; i++) {
					var point = new GPoint(parseFloat(markers[i].getAttribute("lng")), parseFloat(markers[i].getAttribute("lat")));
					var marker = createMarker(point, "<b>SN</b> " + parseFloat(markers[i].getAttribute("sn")) + "<br /><b>Company</b> " + markers[i].getAttribute("company") + "<br /><img src=/metalfab/images/" + markers[i].getAttribute("photo") + " /><br /><b>Longitude</b> " + parseFloat(markers[i].getAttribute("lng")) + "<br /><b>Latitude</b> " + parseFloat(markers[i].getAttribute("lat")));
					map.addOverlay(marker);
				}			
			}
		}	
		request.send(null);
	}
//]]>
</script>
<div id="map" style="width: 640px; height: 400px"></div>
</center>
<!--**done**-->