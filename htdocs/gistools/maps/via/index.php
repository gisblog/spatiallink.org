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
print "<body onload=\"OnPageLoad()\">";
// include headernobody
include '/var/chroot/home/content/57/3881957/html/inc/inc_headernobody.php'; 
// include leftbar
include '/var/chroot/home/content/57/3881957/html/inc/inc_leftbar.php';
//	include content here for cross-browser compatibility: check for API updates @ http://www.viavirtualearth.com/
?>
		<table width="520" border="0" align="left" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
<td class="medium">
<br />
Courtesy:: Virtual Earth [Content does not express or imply sponsorship or endorsement by Microsoft]
<br />
<br />
<div id="viamapcentermessage">
</div>
</td>
	</tr>
	<tr>
<td class="medium">
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
<script src="http://virtualearth.msn.com/js/MapControl.js" type="text/javascript">
</script>
<div id="map" style="width:520px;height:520px;">
</div>
<script type="text/javascript">
//<![CDATA[

	function OnPageLoad() {
		//	VE_MapControl(latitude, longitude, zoomLevel, mapStyle, position, x, y, width, height);
		//	SetMapStyle(mapStyle); ..."a" for aerial, "r" for road or "h" for hybrid	
		var map = new VE_MapControl(29.96, -90.08, 100, 'r', "relative", 1, 1, 520, 520);
	
		//	GetMouseX(); ...get current X of the mouse
		//	GetMouseY(); ...get current Y of the mouse
		
		//	onStartContinuousPan ...event is fired when map starts continuous pan
		//	onEndContinuousPan ...event is fired when map finishes continuous pan
		map.onEndContinuousPan = function() {
			var latLngStr = '(' + map.GetCenterLatitude() + ', ' + map.GetCenterLongitude() + ')';
			document.getElementById("viamapcentermessage").innerHTML = latLngStr;
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