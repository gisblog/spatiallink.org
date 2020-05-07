<?php
// $xml = simplexml_load_file('gmaps_0.46551200_1139250251.xml');
include '/opt/bitnami/apache2/htdocs/inc_con/rss/magpierss061/rss_fetch.inc';
$rss = fetch_rss('http://www.spatiallink.org/gistools/volunteer/gmaps_0.46551200_1139250251.xml');
if ($rss) {
	$markers = array_slice($rss->markers, 0, 2);
	foreach ($markers as $marker ) {
		print $marker['marker'];
	}
} else {
}
?>