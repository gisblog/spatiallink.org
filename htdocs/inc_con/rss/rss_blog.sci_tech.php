<?php
/* refer to rss_blog.spatial.php */
// require_once(rss): multiple feeds
// require_once '/opt/bitnami/apache2/htdocs/inc_con/rss/magpierss061/rss_fetch.inc';
// require_once fct():
// require_once '/opt/bitnami/apache2/htdocs/scripts_fct/scr_fct_cleandescription.php';
// require_once '/opt/bitnami/apache2/htdocs/scripts_fct/scr_fct_cleanstring.php';
// require_once '/opt/bitnami/apache2/htdocs/scripts_fct/scr_fct_feed.php';
// include line: depending on header(), tabs may create parsing error
// include '/opt/bitnami/apache2/htdocs/inc/inc_line.php';
//
//
print fct_feed('http://blog.thehill.com/category/technology/feed/', '2', 'link', 'description', 'title', 'The Hill');
print fct_feed('http://blogs.sun.com/jonathan/feed/entries/atom', '2', 'link', 'content', 'title', 'Jonathan Schwartz'); # <content type='html'>
// print fct_feed('http://feeds.feedburner.com/typepad/sethsmainblog', '2', 'link', 'summary', 'title', 'Seth Godin');
print fct_feed('http://radar.oreilly.com/feed', '1', 'link', 'summary', 'title', 'O\'Reilly Radar'); # http://news.zdnet.com/2509-1_22-0-5.xml
print fct_feed('http://www.oreillynet.com/feeds/author/?x-au=27}&x-mimetype=application%2Frdf%2Bxml', '1', 'link', 'description', 'title', 'Tim O\'Reilly');
print fct_feed('http://gigaom.com/feed/', '2', 'link', 'description', 'title', 'GigaOM');
print fct_feed('http://www.markshuttleworth.com/feed/', '2', 'link', 'description', 'title', 'Mark Shuttleworth');
print fct_feed('http://www.johnniemanzari.com/index.xml', '2', 'link', 'description', 'title', 'Johnnie Manzari');
print fct_feed('http://www.ibm.com/developerworks/blogs/dw_blog_rss.jspa?blog=384', '2', 'link', 'description', 'title', 'Bob Sutor');
print fct_feed('http://www.creativesynthesis.net/blog/feed/', '2', 'link', 'description', 'title', 'Creative Synthesis');
print fct_feed('http://scienceblogs.com/pharyngula/index.xml', '2', 'link', 'description', 'title', 'Pharyngula');
print fct_feed('http://www.newscientist.com/blog/technology/atom.xml', '2', 'link', 'content', 'title', 'New Scientist');
// print fct_feed('http://forevergeek.com/feed/', '2', 'link', 'description', 'title', 'Forever Geek'); # access denied
print fct_feed('http://www.technologyreview.com/rss/BlogRSS.aspx', '1', 'link', 'description', 'title', 'Technology Review');
print fct_feed('http://www.technologyreview.com/blog/rss.asp', '1', 'link', 'description', 'title', 'Technology Review');
print fct_feed('http://news.com.com/2063-10795_3-7334.xml', '1', 'link', 'description', 'title', 'c|net'); # http://blogs.zdnet.com/wp-rss2.php
print fct_feed('http://news.com.com/2063-10798_3-7335.xml', '1', 'link', 'description', 'title', 'c|net');
print fct_feed('http://blogs.pcworld.com/techlog/index.rss', '2', 'link', 'description', 'title', 'PC World');
print fct_feed('http://techdirt.com/techdirt_rss.xml', '2', 'link', 'description', 'title', 'Techdirt');
print fct_feed('http://blogs.techrepublic.com.com/wp-rss2.php', '2', 'link', 'description', 'title', 'TechRepublic');
print fct_feed('http://pogue.blogs.nytimes.com/rss2.xml', '1', 'link', 'description', 'title', 'Pogue');
print fct_feed('http://bits.blogs.nytimes.com/rss2.xml', '1', 'link', 'description', 'title', 'Bits');
print fct_feed('http://blog.washingtonpost.com/posttech/index.xml', '1', 'link', 'description', 'title', 'Bits');
print fct_feed('http://blogs.guardian.co.uk/technology/index.xml', '2', 'link', 'description', 'title', 'Guardian Unlimited');
print fct_feed('http://googleresearch.blogspot.com/atom.xml', '2', 'link', 'description', 'title', 'Google Research');
print fct_feed('http://labs.live.com/photosynth/blogs/SyndicationService.asmx/GetRss', '2', 'link', 'description', 'title', 'Microsoft Labs');
print fct_feed('http://blogs.intel.com/it/index.xml', '2', 'link', 'description', 'title', 'IT@Intel');
print fct_feed('http://feeds.popularmechanics.com/pm/blogs/science_news', '1', 'link', 'description', 'title', 'PopularMechanics');
print fct_feed('http://feeds.popularmechanics.com/pm/blogs/technology_news', '1', 'link', 'description', 'title', 'PopularMechanics');
// http://googleblog.blogspot.com/atom.xml
// http://blogs.msdn.com/conblog/
// yahoo...
?>