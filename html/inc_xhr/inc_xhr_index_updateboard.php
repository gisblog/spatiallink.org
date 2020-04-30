<?php
$xhr_boardcontent = $_GET['param'];
//
$xhr_boardhandle = fopen("/var/chroot/home/content/57/3881957/html/txt/txt_index_board.txt", "w+");
fwrite($xhr_boardhandle, $xhr_boardcontent);
fclose($xhr_boardhandle);	
//
print $xhr_boardcontent;
?>