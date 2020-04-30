<html>
<head>


<style type="text/css" media="screen">
<!--
.pin
{	
	width:50px;height:25px;
	font-weight:normal;font-size:8pt;
	color:#000000;background:#FFFFFF;
	padding:5px;
	z-index:5;
}
-->
</style>
<script src="http://virtualearth.msn.com/js/MapControl.js">
</script>
<script>
function OnPageLoad()
{ 	
//	VE_MapControl(latitude, longitude, zoomLevel, mapStyle, position, x, y, width, height);
//	SetMapStyle(mapStyle); ..."a" for aerial, "r" for road or "h" for hybrid	
var map = new VE_MapControl(29.96, -90.08, 100, 'r', "relative", 1, 1, 520, 520);

	//	GetMouseX(); ...get current X of the mouse
	//	GetMouseY(); ...get current Y of the mouse
	
	//	onStartContinuousPan ...event is fired when map starts continuous pan
	//	onEndContinuousPan ...event is fired when map finishes continuous pan
	map.onEndContinuousPan = function() {
		var latLngStr = '(' + map.GetCenterLatitude() + ', ' + map.GetCenterLongitude() + ')';
		document.getElementById("message").innerHTML = latLngStr;
	};

	//	AddPushpin(id, latitude, longitude, width, height, className, innerHtml, zIndex);
	//	RemovePushpin(id);
	//	className ...css classname
	map.AddPushpin('pin',  map.GetCenterLatitude(), map.GetCenterLongitude(), 50, 25, 'pin', 'Hurricane Katrina', 5);
	
	//	SetZoom(zoomLevel); ...zoom to specified level
	//	onStartZoom ...event is fired when the zoom starts
	//	onEndZoom ...event is fired when the zoom finishes
	map.SetZoom(10);
	
document.body.appendChild(map.element);
}
</script>


</head>
<body onLoad="OnPageLoad()">
<div id="message"></div>
</body>
</html>