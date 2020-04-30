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
		* {font-family: <?php echo($FontName); ?>}
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
	margin: 5px;
	background-color: #666699;
	color: #000000;
	font-size: <?php echo($medium); ?>pt;
	font-weight: 400;
	text-indent: 0;
}

TABLE, TR, TD, TH
{
	color: #000000;
	font-size: <?php echo($medium); ?>pt;
	font-weight: 400;
}

TH, B
{
	font-weight: 800;
}

.menu
{
	font-size: <?php echo($medium); ?>pt;
	font-weight: 600;
	background-color: #666699;
	color: #FFFFFF;
	border-bottom: 1pt solid #FFFFFF;
};

.menuTitle
{
	font-size: <?php echo($medium); ?>pt;
	font-weight: 600;
	background-color: #666699;
	color: #CCCCFF;
};

A
{
	text-decoration: underline;
	color: #FFFFFF;
	font-weight: 600;
};

A:hover, A:active
{
	color: #FF9900;
	text-decoration: none;
};

.thumbIndex
{
	font-size: <?php echo($small); ?>pt;
	font-weight: 600;
	background-color: #9999CC;
	color: #FFFFFF;
	border-top: 1pt solid #FFFFFF; 
	border-left: 1pt solid #FFFFFF; 
	border-right: 1pt solid #FFFFFF; 
};

.thumbIndex A
{
	text-decoration: none;
	color: #FFFFFF;
	font-weight: 600;
}

.thumbIndex A.selected
{
	text-decoration: none;
	color: #CCCCFF;
	font-weight: 600;
}

.thumbIndex A:hover, .thumbIndex A:active
{
	text-decoration: none;
	background: #666699;
	color: #FF9933;
}

INPUT, SELECT, TEXTAREA
{
	background: #EEEEFF;
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

.success
{
	font-weight: 800;
	color: #CCCCFF;
}

.small
{
	color: #FFFFFF;
	font-size: <?php echo($small); ?>pt;
}