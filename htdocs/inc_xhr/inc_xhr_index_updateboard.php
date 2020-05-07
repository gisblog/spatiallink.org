<?php
$xhr_boardcontent = $_GET['param'];
//
$xhr_boardhandle = fopen("/opt/bitnami/apache2/htdocs/txt/txt_index_board.txt", "w+");
fwrite($xhr_boardhandle, $xhr_boardcontent);
fclose($xhr_boardhandle);	
//
print $xhr_boardcontent;
?>