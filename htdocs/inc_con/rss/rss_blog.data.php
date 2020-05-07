<?php
/* refer to rss_blog.spatial.php */
// require_once(rss): multiple feeds
// require_once '/opt/bitnami/apache2/htdocs/inc_con/rss/magpierss061/rss_fetch.inc';
//	require_once fct():
// require_once '/opt/bitnami/apache2/htdocs/scripts_fct/scr_fct_cleandescription.php';
// require_once '/opt/bitnami/apache2/htdocs/scripts_fct/scr_fct_cleanstring.php';
// require_once '/opt/bitnami/apache2/htdocs/scripts_fct/scr_fct_feed.php';
//	include line: depending on header(), tabs may create parsing error
// include '/opt/bitnami/apache2/htdocs/inc/inc_line.php';
//
//
print fct_feed('http://www.geotorrent.org/rss/rss.xml', '2', 'link', 'description', 'title', 'geoTorrent');
?>