<?php
//	set poll_variables:
$ip = getenv(REMOTE_ADDR);
$RESULT_FILE_NAME = "/var/chroot/home/content/57/3881957/html/poll/poll_data.txt";
$que = "/var/chroot/home/content/57/3881957/html/poll/poll_question.txt"; 
$ans = "/var/chroot/home/content/57/3881957/html/poll/poll_answer.txt"; 
$fn = fopen ($que, "r"); 
$puff = fread ($fn, filesize($que)); 
fclose ($fn);
$QUESTION = "$puff";
$lis= 0;
$plsr = file("/var/chroot/home/content/57/3881957/html/poll/poll_answer.txt");

for($x=0; $x<sizeof($plsr); $x++) {
	$temp = explode("|",$plsr[$x]);
	$list[$lis] = $temp[0];
	$lis++; 
}

$ANSWER = $list;
// extract() expects arr[]
is_array($_GET) ? extract($_GET) : ''; # php 5
is_array($_POST) ? extract($_POST) : ''; # php 5
$fname="ip_log.dat";
?>