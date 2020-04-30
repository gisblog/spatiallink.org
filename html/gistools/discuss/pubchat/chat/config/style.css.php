<?php
// Get the names and values for vars sent by the script that called this one
if (isset($HTTP_GET_VARS))
{
	while(list($name,$value) = each($HTTP_GET_VARS))
	{
		$$name = $value;
	};
};

if (isset($Charset))
{
	if (isset($FontName) && $FontName != "")
	{
		?>
		* {font-family: <?php echo($FontName); ?>;}
		<?php
	}
	elseif ($Charset == "iso-8859-1")
	{
		?>
		* {font-family: courier new;}
		<?php
	};
};

if (!isset($medium) || $medium == "") $medium = 10;
$large = round(1.4 * $medium);
$small = round(0.8 * $medium);
?>

BODY
{
	background-color: #666699;
	color: #FFFFFF;
	font-size: <?php echo($medium); ?>pt;
	font-weight: 400;
	margin: 5px;
	text-indent: 0;
}

BODY.frame
{
	background-color: #666699;
	color: #CCCCFF;
	font-size: <?php echo($medium); ?>pt;
	font-weight: 400;
	margin: 5px;
	text-indent: 0;
}

BODY.mainframe
{
	background-color: #CCCCFF;
	color: #000000;
	font-size: <?php echo($medium); ?>pt;
	font-weight: 400;
	margin: 5px;
}

TABLE, TR, TD, TH
{
	color: #000000;
	font-size: <?php echo($medium); ?>pt;
	font-weight: 400;
}

TD.whois, .whois
{
	color: #CCCCFF;
	font-size: <?php echo($medium); ?>pt;
	font-weight: 600;
}

TH, B
{
	font-weight: 800;
}

A
{
	text-decoration: underline;
	color: #FFFFFF;
	font-weight: 600;
}

A:hover, A:active
{
	color: #FF9900;
	text-decoration: none;
}

A.user, A.user:active
{
	text-decoration: none;
	color: #CCCCFF;
	font-weight: 400;
}

INPUT, SELECT, TEXTAREA
{
	background: #EEEEFF;
}

A.sender, A.sender:active
{
	text-decoration: none;
	color: #000000;
	font-weight: 600;
}

.msg
{
	margin-top: 0px;
	margin-bottom: 2px;
	margin-left: <?php echo($Charset == "windows-1256" ? "5" : "55"); ?>px;
	margin-right: <?php echo($Charset == "windows-1256" ? "55" : "5"); ?>px;
	text-indent: -50px;
}

.title
{
	color: #CCCCFF;
	font-size: <?php echo($large); ?>pt;
	font-weight: 800;
}

.table
{
	background-color: #CCCCFF;
	color: #000000;
	font-size: <?php echo($medium); ?>pt;
	font-weight: 400;
}

.tabtitle
{
	background-color: #666699;
	color: #FFFFFF;
	font-size: <?php echo($medium); ?>pt;
	font-weight: 800;
}

.error
{
	font-weight: 800;
	color: #FF0000;
}

.small
{
	color: #FFFFFF;
	font-size: <?php echo($small); ?>pt;
}

.time
{
	unicode-bidi: embed;
	color: #000000;
	font-size: 8pt;
}

.notify
{
	color: #666699;
	font-size: <?php echo($medium); ?>pt;
	font-weight: 600;
}