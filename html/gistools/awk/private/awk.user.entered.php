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

// include header
include '/var/chroot/home/content/57/3881957/html/inc/inc_head.php'; 
// include headerbar
include '/var/chroot/home/content/57/3881957/html/inc/inc_header.php'; 
// include content
include '/var/chroot/home/content/57/3881957/html/inc_con/inc_con_awk.user.entered.php'; 
// include footer
include '/var/chroot/home/content/57/3881957/html/inc/inc_footer.php';
?>