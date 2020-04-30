<?php
/* refer to rss_blog.spatial.php */
// require_once(rss): multiple feeds
// require_once '/var/chroot/home/content/57/3881957/html/inc_con/rss/magpierss061/rss_fetch.inc';
// require_once fct():
// require_once '/var/chroot/home/content/57/3881957/html/scripts_fct/scr_fct_cleandescription.php';
// require_once '/var/chroot/home/content/57/3881957/html/scripts_fct/scr_fct_cleanstring.php';
// require_once '/var/chroot/home/content/57/3881957/html/scripts_fct/scr_fct_feed.php';
// include line: depending on header(), tabs may create parsing error
// include '/var/chroot/home/content/57/3881957/html/inc/inc_line.php';
//
//
print fct_feed('http://news.google.com/news?ned=us&topic=t&output=rss', '2', 'link', 'description', 'title', ''); #
print fct_feed('http://rss.news.yahoo.com/rss/tech', '2', 'link', 'description', 'title', ''); #
print fct_feed('http://www.gcn.com/blogs/tech/index.xml', '2', 'link', 'description', 'title', ' GCN');
print fct_feed('http://www.fcw.com/rss/news/', '2', 'link', 'description', 'title', 'FCW');
print fct_feed('http://sciencenow.sciencemag.org/rss/current.xml', '2', 'link', 'description', 'title', 'Science/AAAS');
print fct_feed('http://www.nature.com/news/rss.rdf', '2', 'link', 'description', 'title', 'nature');
print fct_feed('http://digg.com/rss/indexgeneral_sciences.xml', '1', 'link', 'description', 'title', 'digg');
print fct_feed('http://digg.com/rss/indextech_news.xml', '1', 'link', 'description', 'title', 'digg');
print fct_feed('http://reddit.com/.rss', '2', 'link', 'description', 'title', 'reddit');
print fct_feed('http://rss.slashdot.org/slashdot/slashdot', '2', 'link', 'description', 'title', 'Slashdot');
print fct_feed('http://www.techcrunch.com/?feed=rss2', '2', 'link', 'description', 'title', 'TechCrunch');
print fct_feed('http://www.newsvine.com/_feeds/rss2/tag?id=technology', '2', 'link', 'description', 'title', 'Newsvine');
print fct_feed('http://www.technologyreview.com/rss/rss.aspx', '2', 'link', 'description', 'title', 'Technology Review');
print fct_feed('http://www.newsforge.com/index.rss', '2', 'link', 'description', 'title', 'NewsForge');
print fct_feed('http://news.com.com/2547-1_3-0-5.xml', '2', 'link', 'description', 'title', 'c|net'); # http://news.zdnet.com/2509-1_22-0-5.xml
print fct_feed('http://www.npr.org/rss/rss.php?id=1019', '2', 'link', 'description', 'title', 'npr');
print fct_feed('http://news.bbc.co.uk/rss/newsonline_world_edition/technology/rss.xml', '2', 'link', 'description', 'title', 'BBC');
print fct_feed('http://www.nytimes.com/services/xml/rss/nyt/Technology.xml', '2', 'link', 'description', 'title', 'New York Times');
print fct_feed('http://www.washingtonpost.com/wp-dyn/rss/technology/index.xml', '2', 'link', 'description', 'title', 'washingtonpost');
print fct_feed('http://www.csmonitor.com/rss/scitech.rss', '2', 'link', 'description', 'title', 'csmonitor');
print fct_feed('http://www.parc.xerox.com/rss/?rss_version=2.0', '2', 'link', 'description', 'title', 'PARC');
print fct_feed('http://research.sun.com/news/rss/', '2', 'link', 'description', 'title', 'Sun Research');
print fct_feed('http://research.microsoft.com/rss/news.xml', '1', 'link', 'description', 'title', 'Microsoft Research');
print fct_feed('http://labs.live.com/SyndicationService.asmx/GetRss', '1', 'link', 'description', 'title', 'Microsoft Labs');
print fct_feed('http://rss.intel.com/rss/intel-technology-and-research.xml', '2', 'link', 'description', 'title', 'Intel Research');
?>