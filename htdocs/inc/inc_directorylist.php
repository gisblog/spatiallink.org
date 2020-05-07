<?php
//	note: $varfilepath will give the filepath of this file
$fileurl = "<a href=\"";
$filedirectory = "";
//	will NOT show PHP: note the /
if ($filehandle = opendir(".".$filedirectory)) {
	while (false !== ($file = readdir($filehandle))) {
		if ($file != "." && $file != ".." && (stristr($file, ".php") === FALSE)) {
			print "&#186;&nbsp;".$fileurl.$filedirectory.$file."\">".$file."</a><br />";
		}
	}
	closedir($filehandle);
}
?>