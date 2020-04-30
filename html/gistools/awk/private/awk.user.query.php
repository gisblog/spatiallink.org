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
include '/var/chroot/home/content/57/3881957/html/inc/inc_head.php'; 
// done
?>

<body>

<?php
// include headerbar
include '/var/chroot/home/content/57/3881957/html/inc/inc_header.php'; 
// done
?>

<?php
// include scripts
include '/var/chroot/home/content/57/3881957/html/scripts/scr_awk_query.js'; 
// done
?>

<?php
// include content
include '/var/chroot/home/content/57/3881957/html/inc_con/inc_con_awk.user.query.php'; 
// done
?>

<?php
// include footer
include '/var/chroot/home/content/57/3881957/html/inc/inc_footer.php';
// done
?>