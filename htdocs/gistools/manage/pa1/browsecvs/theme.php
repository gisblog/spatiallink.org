<?php
#Application name: PhpCollab
#Status page: 0
#Path by root: ../browsecvs/theme.php

//TY

class thememodule
{
    var $themepath;
    var $bgcolor1;
    var $bgcolor2;
    var $bgcolor3;
    var $textcolor1;
    var $textcolor2;

    function thememodule($dir)
    {
	$this->themepath = $dir;
	$this->bgcolor1 = "#FFFFFF";
	$this->bgcolor2 = "#E6E6E6";//"#F9DDC9";
	$this->bgcolor3 = "#CCCCCC";//"#F9C9A5";
	$this->textcolor1 = "#000000";
	$this->textcolor2 = "#000000";
    }

    function opentable($width="100%", $cellpadding=0,$cellspacing=0, $bg2="", $bg1="") {
	if ($bg1=="") $bg1 = $this->bgcolor1;
	if ($bg2=="") $bg2 = $this->bgcolor2;
	$wspan = ' width="'.$width.'"';
	if ($width=="") $wspan = "";
	$retval = "";
//	$retval .= '<table'.$wspan.' border="0" cellspacing="1" cellpadding="0" bgcolor="'.$bg2.'" align="center"><tr><td>'."\n";
	$retval .= '<table'.$wspan.' border="0" cellspacing="'.$cellspacing.'" cellpadding="'.$cellpadding.'" bgcolor="'.$bg1.'"><tr><td>'."\n";
	return $retval;
    }

    function closetable() {
	$retval = "";
	$retval .= "</td></tr></table>";
//	$retval .= "</td></tr></table>\n";
	return $retval;
    }
    
    function header() {
	?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
	<html>
	<head>
		<title>BrowseCVS</title>
		<link href="style.css" rel="stylesheet" type="text/css">
	</head>
<body>
	<?php
    }

    function footer() {
	echo "</body>\n</html>\n";
    }
}

$theme = new thememodule($themepath);

?>
