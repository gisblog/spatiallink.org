<?php
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
// done
?>

<?php
// include header
include '/opt/bitnami/apache2/htdocs/inc/inc_head.php'; 
// done
?>

<body>

<?php
// include headerbar
include '/opt/bitnami/apache2/htdocs/inc/inc_header.php'; 
// done
?>

<?php
// include scripts
include '/opt/bitnami/apache2/htdocs/scripts/scr_awk_query.js'; 
// done
?>

<?php
// include content
include '/opt/bitnami/apache2/htdocs/inc_con/inc_con_awk.user.result.php'; 
// done
?>

<?php
// include footer
include '/opt/bitnami/apache2/htdocs/inc/inc_footer.php';
// done
?>