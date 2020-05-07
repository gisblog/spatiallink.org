<?php
/* $sn=1;
	open file handle or input ($string = "..."). note that any blank line in that file stops the scan */
$filehandle = fopen("http://www.spatiallink.org/txt/txt_gisvolunteer_link.txt", "r");
/* parse text: 
	scan file as parts. you may replace string in the syntax (FROM, TO, $), like so:
	$replacestring = ereg_replace(" ", "", $filehandle);
	OR
	if ($ip == "#.#.#.#.bras01.charter.com") {
		...
	} elseif (preg_match("*search*", $ip)) {
		...
	} elseif (preg_match("*bot*", $ip)) {
		...
	}
	OR
	if (ereg("10.1.0", $varip)) {
		...
	}
	OR
	include extra variables before linebreak, like so:
	while ($scan = fscanf($filehandle, "%s %s %s %s %s %s %s\n")) {
		...
	}
	%[^[]] basically matches anything that isn't nothing, so: */
	while ($scan = fscanf($filehandle, "%s %[^[]]\n")) {
		// while you scan, list variables to the parts (values)
		# # # list ($link, $title1, $title2, $title3, $title4, $title5, $title6) = $scan;
		list ($link, $title1) = $scan;
		// now do something with the values. also refer to: fnmatch() (UNIX ONLY), stristr()
		{
			/*	you may split $varip to link to main host, like so:
			print "<td>".$sn++."</td>";
			print "<td>[<a href=\"http://".$varip."\">".$varip."</a>] [<a href=\"http://ws.arin.net/cgi-bin/whois.pl?queryinput=".$varip."+\">ARIN</a>] [<a href=\"http://www.whois.sc/".$varip."\">WHOIS</a>]</td>";
			print "<td>".$stamp1."]</td>";
			print "<td>".$stamp2."</td>";
			print "<td>".$method."</td>";
			print "<td><a href=\"http://intern".$varurl."\">".$varurl."</a></td>";
			print "</tr>"; */
			# # # print "&#186;&nbsp;<a href=\"".$link."\">".$title1." ".$title2." ".$title3." ".$title4." ".$title5." ".$title6."</a><br />";
			print "&#186;&nbsp;<a href=\"".$link."\">".$title1."</a><br />";
		}
	}
fclose($filehandle);
print "<br />";
/* OR use simple/multiple array, like so:
	foreach (array(
	'http://www.globalmapaid.rdvp.org/',
	'http://www.worldvolunteerweb.org/development/ICT/'
	) as $link) {
		print "<a href=\"$link\">$link</a><br />";
	}
*/
?>
