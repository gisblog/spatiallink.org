<?php
// require_once(rss): multiple feeds
require_once '/opt/bitnami/apache2/htdocs/inc_con/rss/magpierss061/rss_fetch.inc';
//	require_once fct():
require_once '/opt/bitnami/apache2/htdocs/scripts_fct/scr_fct_cleandescription.php';
require_once '/opt/bitnami/apache2/htdocs/scripts_fct/scr_fct_cleanstring.php';
require_once '/opt/bitnami/apache2/htdocs/scripts_fct/scr_fct_mon2num.php';
require_once '/opt/bitnami/apache2/htdocs/scripts_fct/scr_fct_feed.php';
// include line: depending on header(), tabs may create parsing error
// include '/opt/bitnami/apache2/htdocs/inc/inc_line.php';
//
//
// no:
// http://www.gisuser.com/
//
//
// print fct_feed('http://www.spatiallink.org/gistools/discuss/pubforum/forum/rss/rss.php', '1', 'link', 'description', 'title', 'user contributed');
print fct_feed('http://news.google.com/news?q=geography+gis&output=rss', '2', 'link', 'description', 'title', '');
print fct_feed('http://www.blinkgeo.com/rss', '2', 'link', 'description', 'title', 'BlinkGeo'); # http://feeds.feedburner.com/BlinkGeo/PublishedNews
print fct_feed('http://www.usgs.gov/rss/newsroom.rss', '2', 'link', 'description', 'title', 'USGS');
print fct_feed('http://earthobservatory.nasa.gov/eo.rss', '1', 'link', 'description', 'title', 'NASA');
print fct_feed('http://www.nasa.gov/rss/breaking_news.rss', '1', 'link', 'description', 'title', 'NASA');
print fct_feed('http://portal.opengeospatial.org/rss/newsfeed.rss', '2', 'link', 'description', 'title', 'OGC');
print fct_feed('http://www.geoconnexion.com/includes/geo_news_rss.xml', '2', 'link', 'description', 'title', 'GeoConnexion');
print fct_feed('http://www10.giscafe.com/rss/news.xml?section=CorpNews', '2', 'link', 'description', 'title', 'GISCafe');
print fct_feed('http://www.geospatial-solutions.com/geospatialsolutions/geospatialsolutions.rss', '2', 'link', 'description', 'title', 'Geospatial Solutions');
print fct_feed('http://www.directionsmag.com/feed/rdf/top_stories.xml', '1', 'link', 'description', 'title', 'Directions Magazine');
print fct_feed('http://www.locationintelligence.net/articles/feed.rss', '1', 'link', 'summary', 'title', 'Location Intelligence');
print fct_feed('http://www.esri.com/news/rss/rss.xml', '2', 'link', 'summary', 'title', 'ESRI');
print fct_feed('http://www.planningnewsvote.com/rss.php', '2', 'link', 'description', 'title', 'PlanningNewsVote'); # http://feeds.feedburner.com/pnv/publishednews
?>