<?php
$passkey = '';
$tilecachedir = '/cache';

// Discourage wireless carriers from messing with our data
header('Content-Type: application/octet-stream');

initialize();

// Check passkey
//if (getstr('p') != $passkey) {
//	fail('Not authorized.');
//}

// Call the requested action
switch (getstr('a')) {
	case 'maptile':
		get_map_tile();
		break;
	case 'search':
		search();
		break;
	default:
		fail('Invalid action requested.');
		break;
}
exit;

// Gets a request variable, failing if it is undefined or contains illegal characters.
function getvar($var, $regex) {
	if (!isset($_REQUEST[$var])) {
		fail('Required request variable is missing.');
	}
	else {
		$val = $_REQUEST[$var];
		if (!preg_match($regex, $val)) {
			fail('Illegal character(s) in request.');
		}
		else {
			return $val;
		}
	}
}

function getint($var) {
	return intval(getvar($var, '/^-?[0-9]+$/'));
}

function getstr($var) {
	return getvar($var, '/^[a-zA-Z0-9\-\+\.\,\:\;\ \"]*$/');
}

function initialize() {
	set_error_handler(error_handler);
	error_reporting(E_ALL);
	ignore_user_abort(true);
}

function error_handler($errno, $errstr, $errfile, $errline) {
	die("ERROR:Error #${errno}: ${errstr} @${errfile}:${errline}");
}

// Terminates with an error.
function fail($msg) {
	trigger_error($msg, E_USER_ERROR);
	die('ERROR:trigger_error did not terminate!?!'); // should never happen
}

// Get map tile action (a=maptile)
function get_map_tile() {
	global $tilecachedir;
	if (!is_dir($tilecachedir) || !is_readable($tilecachedir) || !is_writable($tilecachedir)) {
		fail('tilecachedir is not valid. This is a server-side configuration problem.');
	}

	$x = getint('tx'); // tile X, in google's tile coordinate system
	$y = getint('ty'); // tile Y, in google's tile coordinate system
	$z = getint('tz'); // zoom level

	$fn = "${tilecachedir}/tile_${x}_${y}_${z}.png";
	if (!file_exists($fn)) {
		// No existing map tile in our cache. Create it.
		$url = "http://mt.google.com/mt?v=.3&x=${x}&y=${y}&zoom=${z}";
		$img = imagecreatefromgif($url);
		if ($img == '') {
			fail('Could not decode map tile GIF.');
		}
		else {
			imagepng($img, $fn);
		}
	}
	if (!file_exists($fn) || filesize($fn) < 1) {
		@unlink($fn);
		fail('Failed to create map tile PNG.');
	}
	// Got the file. Send it.
	echo 'MAPTILE:'; // differentiate from error, and prevent Bell from messing with our image
	readfile($fn);
}

// Search action (a=search)
function search() {
	$x = getint('x'); // viewer position X, in our coordinate system
	$y = getint('y'); // viewer position Y, in our coordinate system
	$z = getint('z'); // viewer zoom level
	$sw = getint('sw'); // viewer screen width
	$sh = getint('sh'); // viewer screen height
	$q = getstr('q'); // the search query to submit to google

	// Not sure if sll/sspn are right. Just guessing at their function, really.
	$lng1 = $lat1 = $lng2 = $lat2 = 0; // these will be set by convert
	$sw = $sw << $z;
	$sh = $sh << $z;
	convertXYtoLL($x - ($sw/2), $y - ($sh/2), $lng1, $lat1);
	convertXYtoLL($x + ($sw/2), $y + ($sh/2), $lng2, $lat2);
	$q = urlencode($q);
	$url = "http://maps.google.com/maps?q=${q}&z=${z}&sll=${lat1}%2C${lng1}&sspn=${lat2}%2C${lng2}&output=js";
	$page = file_get_contents($url);
	$pageMatches = array();
	if (!preg_match("/_load\('(.*)', window\.document\)/", $page, $pageMatches)) {
		fail('Could not find XML in search results.');
	}
	$xml = $pageMatches[1];
	//echo "Got XML: $xml\n\n\n\n\n";

	// Parse the XML.
	// PHP has XML parsers, but their DOM API has changed between PHP 4 and 5,
	// and I don't feel like messing with SAX. Regex should work well enough.
	$results = '';
	$xmlMatches = array();
	// Get the lat/lng, and all text up to the end of the first line of the address.
	preg_match_all('|<location.*?>.*?<point lat="(.*?)" lng="(.*?)"/>(.*?)</line>.*?</location>|', $xml, $xmlMatches, PREG_SET_ORDER);
	foreach ($xmlMatches as $xmlMatch) {
		$lat = $xmlMatch[1];
		$lng = $xmlMatch[2];
		$locationXml = $xmlMatch[3]; // NOTE: substring, not well-formed XML

		// Get pin x/y
		$x = $y = 0;
		convertLLtoXY($lng, $lat, $x, $y);

		// Extract text for pin
		// Tags are replaced with spaces, leaving only the text nodes.
		$location = preg_replace('/<.*?>/', ' ', $locationXml); // strip tags
		$location = preg_replace('/\s+/', ' ', $location); // collapse multiple spaces
		$location = html_entity_decode($location); // decode "&amp;" and such.
		$location = preg_replace('/\&\#x([A-Fa-f0-9]+);/mei','chr(0x\\1)', $location); // decode "&#39;" and such
		$location = preg_replace('/\&\#([A-Fa-f0-9]+);/mei','chr(\\1)', $location); // decode "&#x2C;" and such
		$location = preg_replace('/[^a-zA-Z0-9\ \.\,\+\-\(\)\:\;\/\?\_\!\@\#\$\%\^\&\*\=\'\"]/', '?', $location); // replace anything not known-safe
		$location = trim($location);
		if (strlen($location) > 200) {
			$location = substr($location, 0, 200).'...';
		}
		$results .= "${x} ${y} ${location}\n";
	}
	if ($results == '') {
		fail('No data found in results.');
	}

	echo "SEARCHRESULTS:";
	echo $results;
}

// Converts longitude/latitude to our coordinate system
function convertLLtoXY($lng, $lat, &$x, &$y) {
	// This formula was found in a comment here: http://jgwebber.blogspot.com/2005/02/mapping-google.html
	$x = intval(($lng + 98.35) * 131072 * 0.77162458338772);
	$y = intval((39.5 - $lat) * 131072);
}

// Converts from our coordinate system to longitude/latitude
function convertXYtoLL($x, $y, &$lng, &$lat) {
	$lng = ($x / (131072 * 0.77162458338772)) - 98.35;
	$lat = ($y / 131072) + 39.5;
}
?>
