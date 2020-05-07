<?php
/*
function fct_feeddate {
	// isset(), unset(), empty(), is_null(), defined():
	if(isset($item['pubdate'])) {
		// <pubDate>Sat, 05 May 2007 15:12:48 +0000</pubDate>:
		$pieces = explode(' ', trim($item['pubdate'])); # htmlentities($item['pubdate'])
		return $pieces[3].fct_mon2num($pieces[2]).$pieces[1];
	} elseif(isset($item['dc']['date'])) {
		// <dc:date>2007-03-14T11:00:00-07:00</dc:date>:
		return substr($item['dc']['date'], 0, 4).substr($item['dc']['date'], 5, 2).substr($item['dc']['date'], 8, 2); # str_split(): php 5
	} elseif(isset($item['published'])) {
		// <published>2007-03-14T11:00:00-07:00</published>:
		return substr($item['published'], 0, 4).substr($item['published'], 5, 2).substr($item['published'], 8, 2);
	} elseif(isset($item['issued'])) {
		// <issued>2007-03-14T11:00:00-07:00</issued>:
		return substr($item['issued'], 0, 4).substr($item['issued'], 5, 2).substr($item['issued'], 8, 2);
	} else {
		// '':
		return '';
	}
}
*/
/*
	url parsing:
	refer to htmlentities(), urlencode(), htmlspecialchars() OR preg_replace();
	refer to http://www.zend.com/tips/tips.php?id=251&single=1;
	validate feed at http://feeds.archive.org/validator/; http://validator.w3.org;
	//
	ex 1.
	$str = "A 'quote' is <b>bold</b>";
	Outputs: A 'quote' is &lt;b&gt;bold&lt;/b&gt;
	echo htmlentities($str);
	//
	ex 2.
	<a href=<?php echo htmlspecialchars($url); ?>>example</a>
	//
	ex 3.
	$title = preg_replace("/&/","&amp;",$title); // &amp;
	print "<b>".$title."</b><br/>\n";
*/
//
//
// 1:
function fct_feed($url, $number, $link, $description, $title, $owner) {
	// set error reporting:
	error_reporting(E_ERROR); # skip
	// fetch:
	$feed = fetch_rss($url);
	if ($feed) {
		// split array to show 1st $number:
		$items = array_slice($feed->items, 0, $number);
		// cycle through each item:
		foreach ($items as $item ) {
			/*
			// check and impose condition: n/a
			//
			// print:
			// do not return. to include cell table, <span> can NOT enclose <table> for xhtml. it can enclose <span>.
			// print '&#186;&nbsp;<a href="'.htmlentities($item['link']).'">'.htmlentities($item['title']).' - &#960;</a><div class="summary">'.fct_cleandescription($item['content']['encoded']).'</div>';
			//	description_edit: remove '<img src' from the start and go to <p>. note that this is case-sensitive and does not filter-out '<img src' tags that follow
			// $description = strstr($item['description'], '<p>');
			//
			// url:
			// get domain name from url-
			//	<?php
			//	get host name from URL
			//	preg_match("/^(http:\/\/)?([^\/]+)/i", "http://www.php.net/index.html", $matches);
			//	$host = $matches[2];
			//	get last two segments of host name
			//	preg_match("/[^\.\/]+\.[^\.\/]+$/", $host, $matches);
			//	echo "domain name is: {$matches[0]}\n";
			//	?>
			//	domain name is php.net. thus, preg_match to create actual url, like so [also, refer to pattern_url.php]-
			// $match_with = $item['link'];
			// regex- /pattern/ == /http://dx.doi.org/="STRING"......+="PLUS"......[0-9]="A NUMBER BETWEEN 0-9"......+="PLUS"......\.="DOT"......[0-9]="A NUMBER BETWEEN 0-9......+="PLUS"......\/="SLASH"......(.*)="A WILD CARD CHARACTER, OR $MATCH['1']"
			// $match = preg_match("/http:\/\/dx\.doi\.org\/+[0-9]+\.[0-9]+\/(.*)/", $match_with, $result);
			// assuming regex was successful [note that htmlentities($result['0']) will print the entire $match_with]-
			// $webpage = htmlentities($result['1']);
			//	create actual url-
			// $actualurl = "http://www.nature.com/nature/journal/v".$item['prism']['volume']."/n".$item['prism']['number']."/full/".$webpage.".html";
			// print '&#186;&nbsp;<a href="'.htmlentities($item['link']).'" onmouseover="return overlib(\''.fct_cleanstring($item['description']).'\');" onmouseout="return nd();">'.htmlentities($item['title']).' - nature.com</a><br />';
			//
			// title or overlib:
			//	title- [a] tag- The title attribute may be set for both A and LINK to add information about the nature of a link. This information may be spoken by a user agent, rendered as a tool tip, cause a change in cursor image, etc. Other attributes include CLASS, ID, TARGET, HREF.
			//	http://www.w3.org/TR/REC-html40/struct/links.html#h-12.1.4
			//	http://www.w3.org/TR/2002/WD-xhtml2-20020805/mod-hypertext.html
			//	http://www.w3.org/TR/2002/WD-xhtml2-20020805/mod-link.html
			//	http://www.w3schools.com/xhtml/xhtml_standardattributes.asp
			//	http://www.vladdy.net/webdesign/Tooltips.html
			//	clean: fetch fct_cleanstring()
			//	$strip_one = str_replace("\"", "", $item['description']);
			//	$strip_two = str_replace("'", "", $strip_one);
			//	print '&#186;&nbsp;<a title="'.fct_cleanstring($item['description']).'" href="'.htmlentities($item['link']).'">'.htmlentities($item['title']).'</a><br />';
			//	or- note that title gets displayed over overlib. also, do not htmlentities($item['description']) since clickable_link-->html_link.
			//	overlib- you can create sticky popup_text from $description and control its display. note that strip_tags() has a max tag-length of 1024 and it does NOT work with linebreaks. this can cause 'unterminated string constant' error in js while using OVERLIB. the error can also be caused by other variables being passed to js.
			*/
			// date: isset(), unset(), empty(), is_null(), defined()
			if(isset($item['pubdate'])) {
				// <pubDate>Sat, 05 May 2007 15:12:48 +0000</pubDate>:
				$pieces = explode(' ', trim($item['pubdate'])); # htmlentities($item['pubdate'])
				$date = $pieces[3].fct_mon2num($pieces[2]).$pieces[1];
			} elseif(isset($item['dc']['date'])) {
				// <dc:date>2007-03-14T11:00:00-07:00</dc:date>:
				$date = substr($item['dc']['date'], 0, 4).substr($item['dc']['date'], 5, 2).substr($item['dc']['date'], 8, 2); # str_split(): php 5
			} elseif(isset($item['published'])) {
				// <published>2007-03-14T11:00:00-07:00</published>:
				$date = substr($item['published'], 0, 4).substr($item['published'], 5, 2).substr($item['published'], 8, 2);
			} elseif(isset($item['issued'])) {
				// <issued>2007-03-14T11:00:00-07:00</issued>:
				$date = substr($item['issued'], 0, 4).substr($item['issued'], 5, 2).substr($item['issued'], 8, 2);
			} else {
				// '':
				$date = '';
			}
			print '<tr><td>'.$date.'</td><td><a href="'.htmlentities($item[$link]).'" onmouseover="return overlib(\''.htmlentities(fct_cleanstring($item[$description])).'\');" onmouseout="return nd();">'.htmlentities($item[$title]).'</a></td><td>'.$owner.'</td></tr>';
			//	include line:
			// include '/opt/bitnami/apache2/htdocs/inc/inc_line.php';
		}
	} else {
		//	skip error: print '<b>Error:</b><br />'.magpie_error().'<br />';
	}
	// restore original error reporting value:
	@ini_restore('error_reporting');
}
//
//
// 2:
function fct_feed_encode($url, $number, $link, $description, $encode, $title, $owner) {
	// set error reporting:
	error_reporting(E_ERROR); # skip
	// fetch:
	$feed = fetch_rss($url);
	if ($feed) {
		// split array to show 1st $number:
		$items = array_slice($feed->items, 0, $number);
		// cycle through each item:
		foreach ($items as $item ) {
			/*
			// check and impose condition: n/a
			//
			// print:
			// do not return. to include cell table, <span> can NOT enclose <table> for xhtml. it can enclose <span>.
			// print '&#186;&nbsp;<a href="'.htmlentities($item['link']).'">'.htmlentities($item['title']).' - &#960;</a><div class="summary">'.fct_cleandescription($item['content']['encoded']).'</div>';
			//	description_edit: remove '<img src' from the start and go to <p>. note that this is case-sensitive and does not filter-out '<img src' tags that follow
			// $description = strstr($item['description'], '<p>');
			*/
			// date:
			if(isset($item['pubdate'])) {
				// <pubDate>Sat, 05 May 2007 15:12:48 +0000</pubDate>:
				$pieces = explode(' ', trim($item['pubdate'])); # htmlentities($item['pubdate'])
				$date = $pieces[3].fct_mon2num($pieces[2]).$pieces[1];
			} elseif(isset($item['dc']['date'])) {
				// <dc:date>2007-03-14T11:00:00-07:00</dc:date>:
				$date = substr($item['dc']['date'], 0, 4).substr($item['dc']['date'], 5, 2).substr($item['dc']['date'], 8, 2); # str_split(): php 5
			} elseif(isset($item['published'])) {
				// <published>2007-03-14T11:00:00-07:00</published>:
				$date = substr($item['published'], 0, 4).substr($item['published'], 5, 2).substr($item['published'], 8, 2);
			} elseif(isset($item['issued'])) {
				// <issued>2007-03-14T11:00:00-07:00</issued>:
				$date = substr($item['issued'], 0, 4).substr($item['issued'], 5, 2).substr($item['issued'], 8, 2);
			} else {
				// '':
				$date = '';
			}
			print '<tr><td>'.$date.'</td><td><a href="'.htmlentities($item[$link]).'" onmouseover="return overlib(\''.htmlentities(fct_cleanstring($item[$description][$encode])).'\');" onmouseout="return nd();">'.htmlentities($item[$title]).'</a></td><td>'.$owner.'</td></tr>';
			//	include line:
			// include '/opt/bitnami/apache2/htdocs/inc/inc_line.php';
		}
	} else {
		//	skip error: print '<b>Error:</b><br />'.magpie_error().'<br />';
	}
	// restore original error reporting value:
	@ini_restore('error_reporting');
}
//
//
// 3
function fct_feed_condition_1($url, $number, $link, $description, $title, $owner, $condition, $value_1) {
	// set error reporting:
	error_reporting(E_ERROR); # skip
	// fetch:
	$feed = fetch_rss($url);
	if ($feed) {
		// split array to show 1st $number:
		$items = array_slice($feed->items, 0, $number);
		// cycle through each item:
		foreach ($items as $item ) {
			// check condition and value:
			if (isset($condition) && isset($value_1)) {
				/*
				// impose condition:
				// if $item['condition'] has 1+ values, then $item['condition'] concatenates those values [value_1...2...]->use stristr()
				// if (stristr($item['category'], "ArcGIS") || stristr($item['category'], "ArcSDE") || stristr($item['category'], "ArcObjects") || stristr($item['category'], "Geodesy"))
				*/
				//
				// date:
				if(isset($item['pubdate'])) {
					// <pubDate>Sat, 05 May 2007 15:12:48 +0000</pubDate>:
					$pieces = explode(' ', trim($item['pubdate'])); # htmlentities($item['pubdate'])
					$date = $pieces[3].fct_mon2num($pieces[2]).$pieces[1];
				} elseif(isset($item['dc']['date'])) {
					// <dc:date>2007-03-14T11:00:00-07:00</dc:date>:
					$date = substr($item['dc']['date'], 0, 4).substr($item['dc']['date'], 5, 2).substr($item['dc']['date'], 8, 2); # str_split(): php 5
				} elseif(isset($item['published'])) {
					// <published>2007-03-14T11:00:00-07:00</published>:
					$date = substr($item['published'], 0, 4).substr($item['published'], 5, 2).substr($item['published'], 8, 2);
				} elseif(isset($item['issued'])) {
					// <issued>2007-03-14T11:00:00-07:00</issued>:
					$date = substr($item['issued'], 0, 4).substr($item['issued'], 5, 2).substr($item['issued'], 8, 2);
				} else {
					// '':
					$date = '';
				}
				print '<tr><td>'.$date.'</td><td><a href="'.htmlentities($item[$link]).'" onmouseover="return overlib(\''.htmlentities(fct_cleanstring($item[$description])).'\');" onmouseout="return nd();">'.htmlentities($item[$title]).'</a></td><td>'.$owner.'</td></tr>';
				//	include line:
				// include '/opt/bitnami/apache2/htdocs/inc/inc_line.php';
			}
		}
	} else {
		//	skip error: print '<b>Error:</b><br />'.magpie_error().'<br />';
	}
	// restore original error reporting value:
	@ini_restore('error_reporting');
}
//
//
// 4:
function fct_feed_condition_2($url, $number, $link, $description, $title, $owner, $condition, $value_1, $value_2) {
	// set error reporting:
	error_reporting(E_ERROR); # skip
	// fetch:
	$feed = fetch_rss($url);
	if ($feed) {
		// split array to show 1st $number:
		$items = array_slice($feed->items, 0, $number);
		// cycle through each item:
		foreach ($items as $item ) {
			// check condition and values:
			if (isset($condition) && isset($value_1) && isset($value_2)) {
				// impose conditions:
				if (stristr($item[$condition], $value_1) || stristr($item[$condition], $value_2)) {
					// date:
					if(isset($item['pubdate'])) {
						// <pubDate>Sat, 05 May 2007 15:12:48 +0000</pubDate>:
						$pieces = explode(' ', trim($item['pubdate'])); # htmlentities($item['pubdate'])
						$date = $pieces[3].fct_mon2num($pieces[2]).$pieces[1];
					} elseif(isset($item['dc']['date'])) {
						// <dc:date>2007-03-14T11:00:00-07:00</dc:date>:
						$date = substr($item['dc']['date'], 0, 4).substr($item['dc']['date'], 5, 2).substr($item['dc']['date'], 8, 2); # str_split(): php 5
					} elseif(isset($item['published'])) {
						// <published>2007-03-14T11:00:00-07:00</published>:
						$date = substr($item['published'], 0, 4).substr($item['published'], 5, 2).substr($item['published'], 8, 2);
					} elseif(isset($item['issued'])) {
						// <issued>2007-03-14T11:00:00-07:00</issued>:
						$date = substr($item['issued'], 0, 4).substr($item['issued'], 5, 2).substr($item['issued'], 8, 2);
					} else {
						// '':
						$date = '';
					}
					print '<tr><td>'.$date.'</td><td><a href="'.htmlentities($item[$link]).'" onmouseover="return overlib(\''.htmlentities(fct_cleanstring($item[$description])).'\');" onmouseout="return nd();">'.htmlentities($item[$title]).'</a></td><td>'.$owner.'</td></tr>';
					//	include line:
					// include '/opt/bitnami/apache2/htdocs/inc/inc_line.php';
				}
			}
		}
	} else {
		//	skip error: print '<b>Error:</b><br />'.magpie_error().'<br />';
	}
	// restore original error reporting value:
	@ini_restore('error_reporting');
}
?>