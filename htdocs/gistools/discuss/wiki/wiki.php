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
*/
print '<!DOCTYPE html>';
print '<html>';
// include head
include '/opt/bitnami/apache2/htdocs/inc/inc_head.php'; 
// include header
include '/opt/bitnami/apache2/htdocs/inc/inc_header.php'; 
// include leftbar
include '/opt/bitnami/apache2/htdocs/inc/inc_leftbar.php'; 
// include content
include '/opt/bitnami/apache2/htdocs/inc_con/inc_con_wiki.php'; 
// include rightbar
include '/opt/bitnami/apache2/htdocs/inc/inc_rightbar.php'; 
// include footer
include '/opt/bitnami/apache2/htdocs/inc/inc_footer.php';
// done
?>