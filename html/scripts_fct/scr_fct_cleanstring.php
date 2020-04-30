<?php
function fct_cleanstring($string) {
	/*	js error: unterminated string literal
	
		simple function to replace multiple white spaces in $sample: $string = preg_replace('/\s\s+/', ' ', $sample);
	
		note that you can cause a line-break on this server with fwrite() using '\r\n'. however, str_replace("\r\n", "", $string) does NOT remove the line-break
		
		http://www.w3.org/TR/xhtml-modularization/dtd_module_defs.html#a_xhtml_character_entities */
	//
	//	find position of 1st '.' from start-->create substring+"..." like so: $string_one = substr($string, 0, strpos($string, '.'))."..."; 
	//	OR
	//	pick arbitary length and thus limit max characters-->create substring+"..." like so: $string_one = substr($string, 0, 512)."...";
	//	
	//	replace search string with replacement string [\n ( ) ' " ]-->create substring+"..."
	$string_one = str_replace("\n", "", str_replace("–", "", str_replace("\(", "", str_replace("\)", "", str_replace("’", "", str_replace("´", "", str_replace("'", "", str_replace("\"", "", substr($string, 0, 512)."..."))))))));
	//
	//	refer to user notes on http://us2.php.net/str_replace:
	//	string replace-->encode string in which all non-alphanumeric characters except -_. are replaced with '%'+'2 hex digits' and spaces encoded as '+'-->trim white spaces from beginning and end. note that ereg_replace() replaces regular expression.
	$string_two = str_replace("%0D", " ", urlencode($string_one));
	//
	//	return-->strip HTML and PHP tags-->decode any %# encoding: note that strip_tags() has a max tag-length of 1024 and does NOT work with linebreaks, hence using at the end.
	return strip_tags(trim(urldecode($string_two)));
}
?>
