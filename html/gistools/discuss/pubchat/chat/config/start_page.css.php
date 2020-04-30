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
		$FontFace = "font-family: \"${FontName};";
		$SpecialFont = "1";
	}
	elseif ($Charset == "iso-8859-1")
	{
		$FontFace = "font-family: courier new;";
	};
};

if (!isset($medium) || $medium == "") $medium = 10;
$large = round(1.4 * $medium);
$small = round(0.8 * $medium);
?>

.ChatBody
{
	<?php if (isset($FontFace)) echo($FontFace); ?>
	background-color: #dddddd;
	color: #ffffff;
	font-size: <?php echo($medium); ?>pt;
	font-weight: 400;
	margin: 5px;
	text-indent: 0;
}

.ChatTable
{
	<?php if (isset($FontFace)) echo($FontFace); ?>
	background-color: #edecec;
	color: #000000;
	font-size: <?php echo($medium); ?>pt;
	font-weight: 400;
}

.ChatTabTitle
{
	<?php if (isset($FontFace)) echo($FontFace); ?>
	background-color: #dddddd;
	color: #ffffff;
	font-size: <?php echo($medium); ?>pt;
	font-weight: 800;
}

TR.ChatCell, TD.ChatCell, TH.ChatCell
{
	<?php if (isset($FontFace)) echo($FontFace); ?>
	color: #000000;
	font-size: <?php echo($medium); ?>pt;
	font-weight: 400;
}

TH.ChatCell
{
	<?php if (isset($FontFace)) echo($FontFace); ?>
	font-weight: 800;
}

<?php
if (isset($SpecialFont))
{
	?>
	A.ChatFonts
	{
		<?php if (isset($FontFace)) echo($FontFace); ?>
		text-decoration: underline;
		color: #ff0000;
		font-weight: 600;
	}

	A.ChatFonts:hover, A.ChatFonts:active
	{
		<?php if (isset($FontFace)) echo($FontFace); ?>
		color: #ff0000;
		text-decoration: none;
	}
	<?php
};
?>

A.ChatLink
{
	<?php if (isset($FontFace)) echo($FontFace); ?>
	text-decoration: underline;
	color: #ffffff;
	font-weight: 600;
}

A.ChatLink:hover, A.ChatLink:active
{
	<?php if (isset($FontFace)) echo($FontFace); ?>
	color: #ff9900;
	text-decoration: none;
}

A.ChatReg
{
	<?php if (isset($FontFace)) echo($FontFace); ?>
	text-decoration: underline;
	color: #0000c0;
	font-weight: 800;
}

A.ChatReg:hover,A.ChatReg:active
{
	<?php if (isset($FontFace)) echo($FontFace); ?>
	color: #0000c0;
	text-decoration: none;
}

INPUT.ChatBox, SELECT.ChatBox, TEXTAREA.ChatBox
{
	<?php if (isset($FontFace)) echo($FontFace); ?>
	background: #edecec;
}

.ChatTitle
{
	<?php if (isset($FontFace)) echo($FontFace); ?>
	color: #edecec;
	font-size: <?php echo($large); ?>pt;
	font-weight: 800;
}

.ChatError
{
	<?php if (isset($FontFace)) echo($FontFace); ?>
	font-size: <?php echo($medium); ?>pt;
	font-weight: 800;
	color: #ff0000;
}

.ChatCopy
{
	font-family: courier new;
	unicode-bidi: embed;
	color: #edecec;
	font-size: 8pt;
}

A.ChatCopy, A.ChatCopy:active
{
	font-family: courier new;
	color: #ffffff;
}

.ChatP1
{
	<?php if (isset($FontFace)) echo($FontFace); ?>
	font-size: <?php echo($medium); ?>pt;
	color: #ffffff;
}

.ChatP2
{
	<?php if (isset($FontFace)) echo($FontFace); ?>
	font-size: <?php echo($medium); ?>pt;
	color: #000000;
}

.ChatFlags
{
	<?php if (isset($FontFace)) echo($FontFace); ?>
	color: #000000;
	font-size: 10pt;
	font-weight: 400;
}