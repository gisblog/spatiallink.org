<?php
// connect, select, use:

$varconnect = mysqli_connect($varhost, $varuser, $varpass) or die("<html><head></head><body><script language='javascript'<alert('Could NOT connect to dataserver! Please try again later.'),history.go(-1)</script></body></html>".mysqli_error($varconnect).".");

mysqli_select_db($varconnect, $vardb) or die("<html><head></head><body><script language='javascript'<alert('Could NOT select database! Please try again later.'),history.go(-1)</script></body></html>".mysqli_error($varconnect).".");

$varuse = "USE 'spatiallink'";
?>
