<?php
//connect,select,use:
$varconnect=mysql_connect($varhost,$varuser,$varpass) or die("<html><head></head><body><script language='javascript'<alert('Could NOT connect to dataserver! Please try again later.'),history.go(-1)</script></body></html>".mysql_error().".");
mysql_select_db("$vardb") or die("<html><head></head><body><script language='javascript'<alert('Could NOT select database! Please try again later.'),history.go(-1)</script></body></html>".mysql_error().".");
$varuse = "USE 'spatiallink'";
?>