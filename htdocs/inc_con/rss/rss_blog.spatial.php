<?php
// require_once(rss): multiple feeds
require_once '/opt/bitnami/apache2/htdocs/inc_con/rss/magpierss061/rss_fetch.inc';
//	require_once fct(): order imp
require_once '/opt/bitnami/apache2/htdocs/scripts_fct/scr_fct_cleandescription.php';
require_once '/opt/bitnami/apache2/htdocs/scripts_fct/scr_fct_cleanstring.php';
require_once '/opt/bitnami/apache2/htdocs/scripts_fct/scr_fct_mon2num.php';
require_once '/opt/bitnami/apache2/htdocs/scripts_fct/scr_fct_feed.php';
// include line: depending on header(), tabs may create parsing error
// include '/opt/bitnami/apache2/htdocs/inc/inc_line.php';
//
// no:
// http://www.spatiallyadjusted.com/
// http://www.mcwetboy.net/maproom/
// http://www.andrewtheken.com/
// http://www.sharpgis.net/
// http://lptf.blogspot.com/
// http://fantomplanet.wordpress.com/
// http://lordkingsquirrel.com/
//
//
// print fct_feed_condition_1('http://www.spatiallink.org/gistools/discuss/weblogs/blogs/xmlsrv/rss2.php?blog=5', '2', 'link', 'description', 'title', '&#960;', 'category', 'spatial');
print fct_feed('http://www.spatiallink.org/gistools/discuss/weblogs/blogs/feed', '2', 'link', 'description', 'title', '&#960;');
print fct_feed('http://mappinghacks.com/index.cgi/index.rss', '2', 'link', 'description', 'title', 'Mapping Hacks');
print fct_feed('http://trevor.typepad.com/blog/index.rdf', '2', 'link', 'description', 'title', 'Trevor Smith');
// print fct_feed('http://www.csthota.com/blog/rss.aspx?cat=Technology', '2', 'link', 'description', 'title', 'Chandu Thota');
print fct_feed('http://www.perrygeo.net/wordpress/?feed=rss2', '2', 'link', 'description', 'title', 'Matt Perry');
// http://www.starhill.us/
print fct_feed('http://www.edparsons.com/?feed=rss2', '2', 'link', 'description', 'title', 'Ed Parsons');
print fct_feed('http://geothought.blogspot.com/rss.xml', '2', 'link', 'content', 'title', 'Peter Batty'); # <content type='html'>
print fct_feed('http://geotips.blogspot.com/rss.xml', '2', 'link', 'content', 'title', 'Paul Ramsey'); # <content type='html'>
print fct_feed('http://thepcnews.blogspot.com/rss.xml', '2', 'link', 'summary', 'title', 'Tobin Bradley'); # <content type='html'>; atom:summary;
print fct_feed('http://gregsadetsky.com/?feed=rss2', '1', 'link', 'description', 'title', 'Greg Sadetsky'); # poly9
print fct_feed('http://denislaprise.com/feed/atom/', '1', 'link', 'content', 'title', 'Denis Laprise'); # poly9; summary;
print fct_feed('http://www.remotesensingtools.com/feed/', '2', 'link', 'description', 'title', 'Daniel Denk');
print fct_feed('http://cfis.savagexi.com/xml/rss20/feed.xml', '2', 'link', 'description', 'title', 'Charlie Savage');
print fct_feed('http://cholmes.wordpress.com/feed/', '2', 'link', 'description', 'title', 'Chris Holmes');
// print fct_feed('http://zcologia.com/news/rdf10_xml', '2', 'link', 'summary', 'title', 'Sean Gillies');
print fct_feed('http://www.spatialdatalogic.com/cs/blogs/brian_flood/rss.aspx', '2', 'link', 'description', 'title', 'Brian Flood');
print fct_feed('http://geosql.blogspot.com/rss.xml', '2', 'link', 'description', 'title', 'Jeremy'); # <content type='html'>
print fct_feed('http://think.random-stuff.org/rss', '2', 'link', 'description', 'title', 'Allan Doyle');
print fct_feed('http://hobu.biz/index_html/RSS', '2', 'link', 'description', 'title', 'Howard Butler');
print fct_feed('http://feeds.feedburner.com/Spanring', '2', 'link', 'description', 'title', 'Chris Spanring');
print fct_feed('http://www.jasonbirch.com/nodes/feed', '2', 'link', 'description', 'title', 'Jason Birch');
print fct_feed('http://crschmidt.net/blog/feed/', '2', 'link', 'description', 'title', 'Chris Schmidt');
print fct_feed('http://www.jdoneill.com/blog/feed/', '2', 'link', 'description', 'title', 'Daniel O&#180;Neill');
print fct_feed('http://www.gisarch.com/rss.xml', '2', 'link', 'description', 'title', 'Matt'); #? <content type='html'>
print fct_feed('http://www.dankarran.com/blog/index.rdf', '2', 'link', 'description', 'title', 'Dan Karran');
print fct_feed('http://www.eicher-gis.com/Blog/rss.xml', '2', 'link', 'description', 'title', 'Cory Eicher');
print fct_feed('http://mapwrecker.wordpress.com/feed/', '2', 'link', 'description', 'title', 'Bill Thorp');
print fct_feed('http://terrapagestech.blogspot.com/feeds/posts/default', '2', 'link', 'content', 'title', 'Cameron Shorter');
print fct_feed('http://mapzlibrarian.blogspot.com/feeds/posts/default', '2', 'link', 'summary', 'title', 'mapz');
print fct_feed('http://surveying-mapping-gis.blogspot.com/feeds/posts/default?alt=rss', '2', 'link', 'description', 'title', 'David Smith');
print fct_feed('http://weblogs.java.net/blog/jive/index.rdf', '2', 'link', 'description', 'title', 'Jody Garnett');
print fct_feed('http://www.rajsingh.org/blog/?feed=rss2', '2', 'link', 'description', 'title', 'Raj Singh');
print fct_feed('http://www.osgeo.org/tyler/feed', '2', 'link', 'description', 'title', 'Tyler Mitchell');
//
//
print fct_feed('http://ccablog.blogspot.com/feeds/posts/default', '2', 'link', 'summary', 'title', 'CCA'); # Canadian Cartographic Association
print fct_feed('http://www.opengeodata.org/?feed=rss2', '2', 'link', 'description', 'title', 'Open Geo Data');
print fct_feed('http://freegeotools.blogspot.com/rss.xml', '2', 'link', 'content', 'title', 'Free Geo Tools'); # <content type='html'>
print fct_feed('http://batchgeocode.blogspot.com/rss.xml', '2', 'link', 'content', 'title', 'Geocoding Etc'); # <content type='html'>
print fct_feed('http://maps.huge.info/blog/index.xml', '2', 'link', 'content', 'title', 'maps.huge.info');
print fct_feed('http://digitalurban.blogspot.com/feeds/posts/default', '2', 'link', 'description', 'title', 'Digital Urban');
print fct_feed('http://www.gisblog.net/feed/', '2', 'link', 'description', 'title', 'gisblog.net');
print fct_feed('http://www.webmapper.net/rss.xml', '2', 'link', 'description', 'title', 'webmapper');
print fct_feed('http://mapperz.blogspot.com/rss.xml', '2', 'link', 'description', 'title', 'Mapperz'); # <content type='html'>
print fct_feed('http://www.cadmaps.com/blogfeed20.xml', '2', 'link', 'description', 'title', 'Micro Map & CAD');
print fct_feed('http://kellylab.berkeley.edu/blog/?feed=rss2', '2', 'link', 'description', 'title', 'Kelly Lab');
print fct_feed('http://www.nsgic.org/blog/atom.xml', '2', 'link', 'content', 'title', 'NSGIC'); # <content type='html'>
print fct_feed('http://www.roktech.net/devblog/rss.cfm', '2', 'link', 'description', 'title', 'ROK Technologies');
print fct_feed('http://spatiallaw.blogspot.com/feeds/posts/default', '2', 'link', 'content', 'title', 'Spatial Law'); # <content type='html'>
//
//
print fct_feed('http://planetpostgresql.org/rss20.xml', '2', 'link', 'description', 'title', 'PostgreSQL');
print fct_feed('http://www.planetmysql.org/rss20.xml', '2', 'link', 'description', 'title', 'MySQL');
// print fct_feed('http://planet.xmlhack.com/index.rdf', '2', 'link', 'description', 'title', 'XML');
print fct_feed('http://www.oreillynet.com/pub/feed/20', '1', 'link', 'summary', 'title', 'XML.com'); #  entry -> link summary title
print fct_feed('http://www.topxml.com/rss/rss.asp', '1', 'link', 'description', 'title', 'TopXML');
print fct_feed('http://blog.360.yahoo.com/rss-FpNKzAEjd6M5b.AhogwN7Q--?cq=1', '1', 'link', 'description', 'title', 'Rasmus Lerdorf');
print fct_feed('http://toys.lerdorf.com/feeds/index.rss1', '1', 'link', 'description', 'title', 'Rasmus\' Toys');
print fct_feed('http://planet.python.org/rss10.xml', '2', 'link', 'description', 'title', 'Python');
print fct_feed('http://www.planet-php.org/rss/', '2', 'link', 'description', 'title', 'PHP');
//
//
print fct_feed('http://mapserver.gis.umn.edu/search_rss?SearchableText=&Title=&Subject_usage%3Aignore_empty=&Description=&created%3Alist%3Adate=2007%2F04%2F13&created_usage=range%3Amin&pt_toggle=%23&portal_type%3Alist=ATPhoto&portal_type%3Alist=ATPhotoAlbum&portal_type%3Alist=Document&portal_type%3Alist=Event&portal_type%3Alist=Favorite&portal_type%3Alist=File&portal_type%3Alist=Folder&portal_type%3Alist=Gallery&portal_type%3Alist=GalleryFolder&portal_type%3Alist=HeaderSetFolder&portal_type%3Alist=HelpCenter&portal_type%3Alist=HelpCenterDefinition&portal_type%3Alist=HelpCenterErrorReference&portal_type%3Alist=HelpCenterErrorReferenceFolder&portal_type%3Alist=HelpCenterFAQ&portal_type%3Alist=HelpCenterFAQFolder&portal_type%3Alist=HelpCenterGlossary&portal_type%3Alist=HelpCenterHowTo&portal_type%3Alist=HelpCenterHowToFolder&portal_type%3Alist=HelpCenterInstructionalVideo&portal_type%3Alist=HelpCenterInstructionalVideoFolder&portal_type%3Alist=HelpCenterLink&portal_type%3Alist=HelpCenterLinkFolder&portal_type%3Alist=HelpCenterReferenceManual&portal_type%3Alist=HelpCenterReferenceManualFolder&portal_type%3Alist=HelpCenterReferenceManualPage&portal_type%3Alist=HelpCenterReferenceManualSection&portal_type%3Alist=HelpCenterTutorial&portal_type%3Alist=HelpCenterTutorialFolder&portal_type%3Alist=HelpCenterTutorialPage&portal_type%3Alist=Image&portal_type%3Alist=Large+Plone+Folder&portal_type%3Alist=Link&portal_type%3Alist=News+Item&portal_type%3Alist=Questionnaire&portal_type%3Alist=RuleFolder&portal_type%3Alist=Topic&Creator=&rs_toggle=%23&review_state%3Alist=obsolete&review_state%3Alist=published&review_state%3Alist=pending&review_state%3Alist=private&review_state%3Alist=published&review_state%3Alist=visible&review_state%3Alist=published&review_state%3Alist=in-progress&review_state%3Alist=private&review_state%3Alist=hidden&review_state%3Alist=visible&review_state%3Alist=pending&submit=Search', '2', 'link', 'description', 'title', 'MapServer');
print fct_feed('http://blog.geoserver.org/', '2', 'link', 'description', 'title', 'GeoServer');
print fct_feed('http://blog.qgis.org/', '2', 'link', 'description', 'title', 'Quantum GIS');
print fct_feed('http://openjump.blogspot.com/rss.xml', '2', 'link', 'description', 'title', 'OpenJUMP'); # <content type='html'>
print fct_feed('http://geocarta.blogspot.com/rss.xml', '2', 'link', 'content', 'title', 'GeoCarta'); # <content type='html'>
print fct_feed('http://www.georss.org/blog/feed/', '2', 'link', 'summary', 'title', 'GeoRSS');
print fct_feed('http://geopdf.blogspot.com/rss.xml', '2', 'link', 'description', 'title', 'GeoPDF'); # <content type='html'>
print fct_feed('http://blog.fortiusone.com/feed/', '2', 'link', 'description', 'title', 'FortiusOne');
print fct_feed('http://feeds.esri.com/GeographyMatters', '1', 'link', 'description', 'title', 'Geography Matters');
print fct_feed('http://feeds.esri.com/MappingCenter', '1', 'link', 'description', 'title', 'Mapping Center');
print fct_feed('http://blogs.esri.com/Dev/blogs/arcgisserver/rss.aspx', '1', 'link', 'description', 'title', 'ArcGIS Server');
//
//
print fct_feed('http://googlemapsapi.blogspot.com/rss.xml', '2', 'link', 'content', 'title', 'Google Maps API'); # <content type='html'>
print fct_feed('http://feeds.ogleearth.com/ogleearth', '2', 'link', 'description', 'title', 'Ogle Earth');
print fct_feed('http://virtualearth.spaces.live.com/feed.rss', '1', 'link', 'description', 'title', 'Virtual Earth');
print fct_feed('http://virtualearth4gov.spaces.live.com/feed.rss', '1', 'link', 'description', 'title', 'virtualearth4gov');
print fct_feed('http://ylocalblog.com/blog/feed/', '2', 'link', 'description', 'title', 'Yahoo! Local Maps');
print fct_feed('http://bullsworld.blogspot.com/rss.xml', '2', 'link', 'summary', 'title', 'Bull\'s rambles'); # <content type='html'>
//
//
print fct_feed('http://www.urbancartography.com/rss.xml', '2', 'link', 'content', 'title', 'urbancartography'); # <content type='html'>
print fct_feed('http://blog.urbanmapping.com/xml/atom/feed.xml', '2', 'link', 'content', 'title', 'Urban Mapping'); # <content type='html'>
print fct_feed('http://veryspatial.com/?feed=rss2', '2', 'link', 'description', 'title', 'VerySpatial');
print fct_feed_encode('http://www.allpointsblog.com/feeds/index.rss2', '2', 'link', 'content', 'encoded', 'title', 'All Points'); # <content:encoded> -> $item['content']['encoded']
// print fct_feed('http://slashgeo.org/index.rss', '2', 'link', 'description', 'title', 'Slashgeo');
?>