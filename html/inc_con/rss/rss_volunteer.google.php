<?php
/* 1 */
	// set error reporting
	error_reporting(E_ERROR);
	// done
	// fetch RSS/RDF: validate at-
	// http://feeds.archive.org/validator/; http://validator.w3.org/
	$rss = fetch_rss('http://news.google.com/news?topic=n&q=fema&output=rss');
	if ($rss) {
		// split the array to show first 2
		$items = array_slice($rss->items, 0, 2);
		// cycle through each item and print
		foreach ($items as $item )
		{
			//	$description: TITLE OR OVERLIB. refer to rss_blog.sci_tech.php
			print '&#186;&nbsp;<a href="'.htmlentities($item['link']).'" onmouseover="return overlib(\''.fct_cleanstring($item['description']).'\');" onmouseout="return nd();">'.htmlentities($item['title']).'</a><br />';
			// done
		}
	} else {
			//	skip error: print '<b>Error:</b><br />'.magpie_error().'<br />';
	}
	// done
	// restore original error reporting value
	@ini_restore('error_reporting');
	// done
?>