<?php
/*	EX: img lib enabled-
	http://www.phpnoise.com/tutorials/1/2
	http://www.php-mysql-tutorial.com/user-authentication/image-verification.php */

/*	header: already sent */
//	header("content-type: image/gif"); 
/* session: refer to WIKI-->index.php
//	...

/*	set up img: 1st # is width, 2nd # is height */
//	$img = imagecreate(50, 25) OR use background. use $img_suffix to store and call img thus ensuring fresh display
$img_suffix = rand(1,4);
$img = imagecreatefromgif("/var/chroot/home/content/57/3881957/html/images/img_verification_back.".$img_suffix.".gif"); 

/*	create 2 variables to store img color */ 
$white = imagecolorallocate($img, 255, 255, 255); 
$black = imagecolorallocate($img, 0, 0, 0); 

/*	random string generator */
//	seed for random #
srand((double)microtime()*1000000); 
//	run string through MD5 function: use $img_suffix for random # thus ensuring fresh display
$string = md5(rand(0,9999) + ($img_suffix));
//	create new string
$verification_code = substr($string, 15, 5);

/*	fill img with black: NA */ 
//	imagefill($img, 0, 0, $black); 

/*	write string */
imagestring($img, 5, 3, 4, $verification_code, $black); 

/*	output to browser: storage folder */
$img_output = "/var/chroot/home/content/57/3881957/html/images/img_verification_code.".$img_suffix.".gif";
imagegif($img, $img_output);

/* $img destroy here */
imagedestroy($img);

/*	do NOT unlink here, like so:
if (file_exists($img_output)) {
	unlink($img_output);
} */
?>
<!--span starts in leftbar-->
	<center>
<img src="/images/img_verification_code.
<?php
print $img_suffix;
?>
.gif" alt="spatiallink_org" width="50" height="25" />
	</center>