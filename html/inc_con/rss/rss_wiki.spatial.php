<?php
// include rss: require_once if multiple feeds
require_once '/var/chroot/home/content/57/3881957/html/inc_con/rss/magpierss061/rss_fetch.inc';
//	include line: tabs may create parsing errors
include '/var/chroot/home/content/57/3881957/html/inc/inc_line.php';
//	include scripts_fct()
include '/var/chroot/home/content/57/3881957/html/scripts_fct/scr_fct_cleandescription.php';
// done

/* feed: 
	URL parsing with PHP-
	refer to htmlentities(), urlencode(), htmlspecialchars() OR preg_replace().
	refer to http://www.zend.com/tips/tips.php?id=251&single=1
	ex 1.
	$str = "A 'quote' is <b>bold</b>"; 
	Outputs: A 'quote' is &lt;b&gt;bold&lt;/b&gt; 
	echo htmlentities($str); 
	ex 2.
	<a href=<?php echo htmlspecialchars($url); ?>>example</a>
	ex 3.			
	$title=preg_replace("/&/","&amp;",$title); // &amp;
	print "<b>".$title."</b><br/>\n";
	validate feed at http://feeds.archive.org/validator/; http://validator.w3.org.
	
	others:
	http://www.oreillynet.com/feeds/author/?x-au=1898}&x-mimetype=application%2Frdf%2Bxml */

/* 01 */
	// set error reporting
	error_reporting(E_ERROR);
	// done
	// fetch RSS/RDF:
	$feed = fetch_rss('http://www.itpapers.com/xml/RSS-231.xml');
	if ($feed) {
			// split the array to show first 2
			$items = array_slice($feed->items, 0, 2);
			// cycle through each item and print
			//	note: to include cell table, <span> can NOT enclose <table> for xhtml. it can enclose <span>
			foreach ($items as $item) {
				// impose condition: NA
				print '&#186;&nbsp;<a href="'.htmlentities($item['link']).'">'.htmlentities($item['title']).' - IT Papers</a><div class="summary">'.fct_cleandescription($item['description']).'</div>';
				//	include line
				include '/var/chroot/home/content/57/3881957/html/inc/inc_line.php';
			}
		} else {
			//	skip error: print '<b>Error:</b><br />'.magpie_error().'<br />';
		}
	// done
	// restore original error reporting value
	@ini_restore('error_reporting');
	// done
?>