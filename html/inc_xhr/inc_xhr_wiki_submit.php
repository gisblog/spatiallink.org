<?php
/*	require cleanjs */
require '/var/chroot/home/content/57/3881957/html/inc/inc_cleanjs.php';
/* done */
/* require $var AGAIN: NA */
//	check for db variables
//	check for db csu
//	check for environment variables
/* done */
/*	get js $: note that here $xhr_wikiname = $filename */
$xhr_wikiname = $_GET['param'];
$xhr_input_code = $_GET['karam'];
/*	done */
/*	file: set filepath $. note that here $code = $key / $key_dir.$filename. also, refer to file_get_contents(). */
$code = "/var/chroot/home/content/57/3881957/html/gistools/discuss/wiki/wikikey/".$xhr_wikiname;
/*	done */
/*	fread: 'r' open for reading only; place the file pointer at the beginning of the file. Windows offers a text-mode translation flag ('t') which will transparently translate \n to \r\n when working with the file. In contrast, you can also use 'b' to force binary mode. To use these flags, specify either 'b' or 't' as the last character of the mode parameter.

	plan for !fopen() */
if (file_exists($code)) {
	$filehandle = fopen($code, "r");
	$filecontent = fread($filehandle, filesize($code));
	$fread_code = $filecontent;
	fclose($filehandle);
	//	compare
	if ($fread_code == $xhr_input_code) {
		//	OK
		print "*";
	} else {
		//	NOT OK
		print "";
	}
} else {
	//	NOT OK: file does NOT exist
	print "";
}
/*	done */
/*	kill */
exit;
return;
die;
/*	done */
?>