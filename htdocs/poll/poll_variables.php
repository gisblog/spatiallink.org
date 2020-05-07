<?php
//	set poll_variables:
$ip = getenv('REMOTE_ADDR');
$RESULT_FILE_NAME = "/opt/bitnami/apache2/htdocs/poll/poll_data.txt";
$que = "/opt/bitnami/apache2/htdocs/poll/poll_question.txt"; 
$ans = "/opt/bitnami/apache2/htdocs/poll/poll_answer.txt"; 
$fn = fopen ($que, "r"); 
$puff = fread ($fn, filesize($que)); 
fclose ($fn);
$QUESTION = "$puff";
$lis= 0;
$plsr = file($ans);

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
