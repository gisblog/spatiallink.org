<?php
//	monitor whether searchkeyword[s] return data or not: optionally, may send automail to self
$filehandle0 = fopen("/opt/bitnami/apache2/htdocs/txt/volunteer/searchmonitor.txt", "a");
$filecontent0 = " || $vardate::$varip::$country::$searchkeywords\r\n";
fwrite($filehandle0, $filecontent0);
fclose($filehandle0);
?>