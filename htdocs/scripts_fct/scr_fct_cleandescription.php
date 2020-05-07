<?php
function fct_cleandescription($description) {
	/*	refer to scr_fct_cleanstring */
	//	replace search description with replacement description: refer to http://www.cookwood.com/html/extras/entities.html and http://www.sitepoint.com/blogs/2005/04/19/character-encodings-and-input/.
	$description_one = str_replace("\n", "", str_replace("–", "&#8211;", str_replace("—", "&#8212;", str_replace("(", "&#9001;", str_replace(")", "&#9002;", str_replace("à", "&#224;", str_replace("á", "&#225;", str_replace("â", "&#226;", 
	str_replace("è", "&#232;", str_replace("é", "&#233;", str_replace("ê", "&#234;", str_replace("ì", "&#236;", 
	str_replace("í", "&#237;", str_replace("ò", "&#242;", str_replace("ó", "&#243;", str_replace("‘", "&#8216;", str_replace("’", "&#8217;", str_replace("´", "&#180;", str_replace("'", "&#39;", str_replace("‚", "&#8218;", 	str_replace("“", "&#8220;", str_replace("”", "&#8221;", str_replace("\"", "&#34;", str_replace("„", "&#8222;", preg_replace('/[^\x09\x0A\x0D\x20-\x7F\xC0-\xFF]/', '', $description)."..."))))))))))))))))))))))));
	//
	//	refer to user notes on http://us2.php.net/str_replace:
	//	description replace-->encode description in which all non-alphanumeric characters except -_. are replaced with '%'+'2 hex digits' and spaces encoded as '+'-->trim white spaces from beginning and end. note that ereg_replace() replaces regular expression.
	$description_two = str_replace("%0D", " ", urlencode($description_one));
	//
	//	return-->strip HTML and PHP tags-->decode any %# encoding: note that strip_tags() has a max tag-length of 1024 and does NOT work with linebreaks, hence using at the end.
	//	note: do NOT htmlentities($item['description']) since clickable_link-->html_link
	return strip_tags(trim(urldecode($description_two)));
}
?>