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

// wml:
print "<!DOCTYPE wml PUBLIC \"-//WAPFORUM//DTD WML 1.1//EN\" \"http://www.wapforum.org/DTD/wml_1.1.xml\">";
print "<wml>";

// xhtml:
print "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"DTD/xhtml1-transitional.dtd\"	>";
print "<html	xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\">";
*/
print '<!DOCTYPE html>';
print '<html>';

// include headerwap
include '/opt/bitnami/apache2/htdocs/inc/inc_headerwap.php'; 
?>

<body>

<?php
// no headerwapbar

// include content
include '/opt/bitnami/apache2/htdocs/inc_con/inc_con_wappubrss.php'; 

// include footerwap
include '/opt/bitnami/apache2/htdocs/inc/inc_footerwap.php';
?>