<?php
// set variables:
// $url = "http://www.spatiallink.org/inc_con/rss/rss_news.spatial.php";
$url = "http://www.spatiallink.org/gistools/discuss/news/contents.php"; # filename
$filepath = "/var/chroot/home/content/57/3881957/html/gistools/discuss/news/";
$filename= "index.html";
//
//
// open url, get url:
$urlhandle = fopen($url, "r");
$contents = file_get_contents($url);
//
//
// open file, write file:
$filehandle = fopen($filepath.$filename, "w+");
fwrite($filehandle, $contents);
//
//
// copy file to archives
$archivesyear = date("Y");
$archivesmonth = date("M");
$archivesdate = date("dS");
//
//
// mkdir(): recursive steps
$archivespath_01 = "/var/chroot/home/content/57/3881957/html/gistools/discuss/news/archives/".$archivesyear."/";
$archivespath_02 = "/var/chroot/home/content/57/3881957/html/gistools/discuss/news/archives/".$archivesyear."/".$archivesmonth."/";
$archivesname = $archivesdate.".html";
// mkdir()
if(is_dir($archivespath_02)) {
	// directory/file exists
} else {
	// directory/file does not exist: recursive steps for /gistools/discuss/news/c.php
	mkdir($archivespath_01);
	mkdir($archivespath_02);
}
// path is not created if it does not exist
copy($filepath.$filename, $archivespath_02.$archivesname); # overwrite
//
//
// close url, close file:
fclose($urlhandle);
fclose($filehandle);
?>