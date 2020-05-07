<?php
/*
// intro xhtml: goes before anything else
if (stristr($_SERVER['HTTP_ACCEPT'], "application/xhtml+xml")) 
  {
    header("content-Type: application/xhtml+xml; charset=utf-8");
  }
  else
  {
    header("content-type: text/html; charset=utf-8");
  }
print "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
print "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"DTD/xhtml1-transitional.dtd\"	>";
print "<html	xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\">";
*/
print '<!DOCTYPE html>';
print '<html>';

// include head
include '/var/chroot/home/content/57/3881957/html/inc/inc_head.php';
// include header
include '/var/chroot/home/content/57/3881957/html/inc/inc_header.php';
// do NOT include leftbar
// include '/var/chroot/home/content/57/3881957/html/inc/inc_leftbar.php';

// check for browser compatibility
if (strstr($_SERVER["HTTP_USER_AGENT"], "MSIE")) {
?>
	<!--include java content-->
			<table border="0" align="left" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
		<tr>
	<td width="760" align="left" valign="top" bgcolor="#DDDDDD">
		<!--java-->	
		<APPLET archive="jars/support.jar,jars/geotools.jar,jars/collections.jar" CODE="GraphApplet.class" WIDTH="760" HEIGHT="418" ALIGN="BOTTOM">
			<!--custom-->
			<PARAM NAME="shapefile" VALUE="maps/statepop">
			<PARAM NAME="groups" VALUE="3">
			
			<PARAM NAME="group1col" VALUE="Kerry">
			<PARAM NAME="group1name" VALUE="Democrat">
			<PARAM NAME="group1color" value="#0000FF">
			
			<PARAM NAME="group2col" VALUE="Bush">
			<PARAM NAME="group2name" VALUE="Republican">
			<PARAM NAME="group2color" value="#FF0000">
			
			<PARAM NAME="group3col" VALUE="Nader">
			<PARAM NAME="group3name" VALUE="Others">
			<PARAM NAME="group3color" value="#FFFF00">
			
			<PARAM NAME="tooltip" VALUE="STATE_NAME">
			<!--custom-->
		</APPLET>
		<!--java-->
	</td>
		</tr>
			</table>
	<!--include java content-->
<?php
} else {
	print "Unauthorized Access or Incompatible Browser";
}
// check for browser compatibility

// do NOT include rightbar
// include '/var/chroot/home/content/57/3881957/html/inc/inc_rightbar.php'; 
// include footer
include '/var/chroot/home/content/57/3881957/html/inc/inc_footer.php';
?>