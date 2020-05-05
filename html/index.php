<?php
/*
// intro XHTML: goes before anything else
if (stristr($_SERVER['HTTP_ACCEPT'], "application/xhtml+xml")) 
  {
    header("content-Type: application/xhtml+xml; charset=utf-8");
  }
  else
  {
    header("content-type: text/html; charset=utf-8");
  }
*/
print '<!DOCTYPE html>';
print '<html>';
// include head:
// // // include '/var/chroot/home/content/57/3881957/html/inc/inc_head.php';
// // // include( $_SERVER[ "DOCUMENT_ROOT" ] . '/webroot/inc/inc_head.php' );
// // // include( dirname( __FILE__ ) . '/webroot/inc/inc_head.php' );
include '/home/content/57/3881957/html/inc/inc_head.php';

// include header:
include '/home/content/57/3881957/html/inc/inc_header.php';
// d/not include leftbar: include '/var/chroot/home/content/57/3881957/html/inc/inc_leftbar.php'; 
// include content:
include '/home/content/57/3881957/html/inc_con/inc_con_index.php';
// d/not include rightbar: include '/var/chroot/home/content/57/3881957/html/inc/inc_rightbar.php';
// include footer:
include '/home/content/57/3881957/html/inc/inc_footer.php';
?>
