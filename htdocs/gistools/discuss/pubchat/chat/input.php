<?php
// Get the names and values for vars sent by index.lib.php
if (isset($HTTP_GET_VARS))
{
	while(list($name,$value) = each($HTTP_GET_VARS))
	{
		$$name = $value;
	};
};

// Get the names and values for post vars
if (isset($HTTP_POST_VARS))
{
	while(list($name,$value) = each($HTTP_POST_VARS))
	{
		$$name = $value;
	};
};

// Fix some security issues
if ((empty($From) || trim($From) == '')
	|| (empty($U) || trim($U) == '')
	|| (empty($R) || trim($R) == '')
	|| (empty($Ver) || empty($L) || empty($N))
	|| (!isset($T) || !isset($D) || !isset($O) || !isset($ST) || !isset($NT))
	|| !is_dir('./localization/'.$L))
{
	exit();
}
	
require("./config/config.lib.php");
require("./localization/".$L."/localized.chat.php");
require("./lib/release.lib.php");
require("./lib/database/".C_DB_TYPE.".lib.php");
require("./lib/clean.lib.php");

header("Content-Type: text/html; charset=${Charset}");

// avoid server configuration for magic quotes
set_magic_quotes_runtime(0);

$U = urldecode($U);
$R = urldecode($R);

// Translate to html special characters, and entities if message was sent with a latin 1 charset
$Latin1 = ($Charset == "iso-8859-1");
function special_char($str,$lang)
{
	return addslashes($lang ? htmlentities(stripslashes($str)) : htmlspecialchars(stripslashes($str)));
};

$DbLink = new DB;


// ** Updates user info in connected users tables and fix some security issues **
$DbLink->query("SELECT room, status, ip FROM ".C_USR_TBL." WHERE username = '$U' LIMIT 1");
if ($DbLink->num_rows() != 0)
{
	list($room, $status, $knownIp) = $DbLink->next_record();
	$DbLink->clean_results();
	$kicked = 0;
	// Security issue
	include("./lib/get_IP.lib.php");
	if ($knownIp != $IP)
	{
		$kicked = 5;
	}
	// Update users info
	if ($room != stripslashes($R))	// Same nick in another room
	{
		$DbLink->query("INSERT INTO ".C_MSG_TBL." VALUES ($T, '$R', 'SYS exit', '', ".time().", '', 'sprintf(L_EXIT_ROM, \"".special_char($U,$Latin1)."\")')");
		$kicked = 3;
	}
	elseif ($status == "k")			// Kicked by a moderator or the admin.
	{
		$DbLink->query("INSERT INTO ".C_MSG_TBL." VALUES ($T, '$R', 'SYS exit', '', ".time().", '', 'sprintf(L_KICKED, \"".special_char($U,$Latin1)."\")')");
		$kicked = 1;
	}
	elseif ($status == "d")			// The admin just deleted the room
	{
		$kicked = 2;
	}
	elseif ($status == "b")			// Banished by a moderator or the admin.
	{
		$DbLink->query("INSERT INTO ".C_MSG_TBL." VALUES ($T, '$R', 'SYS exit', '', ".time().", '', 'sprintf(L_BANISHED, \"".special_char($U,$Latin1)."\")')");
		$kicked = 4;
	};
	if ($kicked > 0)
	{
		// Kick the user from the current room
		$kickedUrl	= ($kicked < 5)
					? "$From?L=$L&U=".urlencode(stripslashes($U))."&E=".urlencode(stripslashes($R))."&KICKED=$kicked"
					: "$From?L=$L";
		?>
		<SCRIPT TYPE="text/javascript" LANGUAGE="JavaScript">
		<!--
		window.parent.window.location = '<?php echo($kickedUrl); ?>';
		// -->
		</SCRIPT>
		<?php
		$DbLink->close();
		exit;
	}
}
else
{
	$DbLink->clean_results();
	// Fix a security issue
	?>
	<SCRIPT TYPE="text/javascript" LANGUAGE="JavaScript">
	<!--
	window.parent.window.location = '<?php echo("$From?L=$L"); ?>';
	// -->
	</SCRIPT>
	<?php
	$DbLink->close();
	exit;
};


// ** Send formated messages to the message table **
function AddMessage($M, $T, $R, $U, $C, $Private)
{
	global $DbLink;
	global $Latin1;
	global $status;

	// Text formating tags
	if(C_HTML_TAGS_KEEP == "none")
	{
		if(C_HTML_TAGS_SHOW == 0)
		{
			// eliminates every HTML like tags
			$M = ereg_replace("<[^>]+>", "", $M);
		}
		else
		{
			// or keep it without effect
			$M = str_replace("<", "&lt;", $M);
			$M = str_replace(">", "&gt;", $M);
		}
	}
	else
	{
		// then C_HTML_TAGS_KEEP == "simple", we keep U, B and I tags
		$M = str_replace("<", "&lt;", $M);
		$M = str_replace(">", "&gt;", $M);

		if(function_exists("preg_match"))
		{
			while(preg_match("/&lt;([ubi]?)&gt;(.*?)&lt;(\/\\1)&gt;/i",$M))
			{
				$M = preg_replace("/&lt;([ubi]?)&gt;(.*?)&lt;(\/\\1)&gt;/i","<\\1>\\2<\\3>",$M);
			}
			if(C_HTML_TAGS_SHOW == 0)
			{
				$M = preg_replace("/&lt;\/?[ubi]?&gt;/i","",$M);
			}
		}
	}

	// URL
	$M = eregi_replace('([[:space:]]|^)(www)', '\\1http://\\2', $M); // no prefix (www.myurl.ext)
	$prefix = '(http|https|ftp|telnet|news|gopher|file|wais)://';
	$pureUrl = '([[:alnum:]/\n+-=%&:_.~?]+[#[:alnum:]+]*)';
	$M = eregi_replace($prefix . $pureUrl, '<a href="\\1://\\2" target="_blank">\\1://\\2</a>', $M);

	// e-mail addresses
	$M = eregi_replace('([0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-wyz][a-z](fo|g|l|m|mes|o|op|pa|ro|seum|t|u|v|z)?)', '<a href="mailto:\\1">\\1</a>', $M);

	// Smilies
	if (C_USE_SMILIES == 1)
	{
		include("./lib/smilies.lib.php");
		Check4Smilies($M,$SmiliesTbl);
		unset($SmiliesTbl);
	};

	// transform ISO-8859-1 special characters
	if ($Latin1)
	{
		global $MsgTo;
		ereg("(.*)(".$MsgTo."(&gt;)?)(.*)",$M,$Regs);
		if ($MsgTo != "" && ($Regs[1] == "" && $Regs[4] == "")) $Regs[4] = $M;
		if (!ereg("&[[:alnum:]]{1,10};",$Regs[1]) && !ereg("&[[:alnum:]]{1,10};",$Regs[4]))
		{
			for ($i = 1; $i <= 4; $i++)
			{
				if (($i != 1 && $i != 4) || $Regs[$i] == "") continue;
				$part = $Regs[$i];
				$part = htmlentities($part);
				$part = str_replace("&lt;", "<", $part);
				$part = str_replace("&gt;", ">", $part);
				$part = str_replace("&amp;lt;", "&lt;", $part);
				$part = str_replace("&amp;gt;", "&gt;", $part);
				$part = str_replace("&quot;","\"", $part);
				$part = ereg_replace("&amp;(#[[:digit:]]{2,5};)", "&\\1", $part);
				$Regs[$i] = $part;
			}
			$M = $Regs[1].$Regs[2].$Regs[4];
		}
	}

	if (isset($C) and $C != "")
	{
		// Red colors are reserved to the admin or a moderator for the current room
		if ((ereg('#(FF0000|fc403f|fc4b34|fa582a|f66421|f27119|ec7e11|ec117f|f21971|f62164|fa2a58|fc344b)', $C))
			&& !($status == "a" || $status == "m"))
			$C = "#000000";
		$M = "<FONT COLOR=\"".$C."\">".$M."</FONT>";
	};

	$DbLink->query("INSERT INTO ".C_MSG_TBL." VALUES ($T, '$R', '".addslashes($U)."', '$Latin1', ".time().", '$Private', '".addslashes($M)."')");
}


// ** Define the default color that will be used for messages **
if (isset($HTTP_COOKIE_VARS["CookieColor"])) $CookieColor = $HTTP_COOKIE_VARS["CookieColor"];
if(!isset($C))
{
	if(!isset($CookieColor))
	{
		// set default color to black
		$C = "#000000";
	}
	elseif (ereg('#(FF0000|fc403f|fc4b34|fa582a|f66421|f27119|ec7e11|ec117f|f21971|f62164|fa2a58|fc344b)', $CookieColor))
	{
		// Red colors are reserved to the admin or a moderator for the current room
		if (!(isset($status) && ($status == "a" || $status == "m")))
			$C = "#000000";
	}
	
	if (!isset($C))
	{
		$C = $CookieColor;
	}
};
setcookie("CookieColor", $C, time() + 60*60*24*365);        // cookie expires in one year


// ** Test for online commands and swear words **
$IsCommand = false;
$RefreshMessages = false;
$IsPopup = false;
$IsM = false;

if (isset($M) && trim($M) != "" && ereg("^\/", $M)) include("./lib/commands.lib.php");

if (isset($M) && ereg("^\/", $M) && !($IsCommand) && !isset($Error)) $Error = L_BAD_CMD;

if (isset($M) && trim($M) != "" && (!isset($M0) || ($M != $M0)) && !($IsCommand || isset($Error)))
{
	if (C_NO_SWEAR == 1)
	{
		include("./lib/swearing.lib.php");
		$M = checkwords($M, false);
	}
	AddMessage(stripslashes($M), $T, $R, $U, $C, "");
	$RefreshMessages = true;
}

$DbLink->close();

// For translations with an explicit charset (not the 'x-user-defined' one)
if (!isset($FontName)) $FontName = "";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML dir="<?php echo(($Charset == "windows-1256") ? "RTL" : "LTR"); ?>">

<HEAD>
<TITLE>Input frame</TITLE>
<LINK REL="stylesheet" HREF="config/style.css.php?<?php echo("Charset=${Charset}&medium=${FontSize}&FontName=${FontName}"); ?>" TYPE="text/css">
<SCRIPT TYPE="text/javascript" LANGUAGE="JavaScript1.2">
<!--
// Get the position for the help popup
if (window.parent.NS4) document.captureEvents(Event.MOUSEDOWN);
document.onmousedown = window.parent.displayLocation;
// -->
</SCRIPT>
</HEAD>

<BODY CLASS="frame" <?php if (!$IsPopup) echo("onLoad=\"if (window.focus) window.parent.get_focus();\""); ?>>
<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>

	<!-- Input form  -->

	<TD>
	<?php
	// Define the way posted values will be handled according to the javascript abilities
	// of the browser
	if ($Ver == "H")
	{
		$action = "handle_inputH.php";
		$target = "input_sent";
	}
	else
	{
		$action = "input.php";
		$target = "_self";
	};
	?>
	<FORM NAME="MsgForm" ACTION="<?php echo($action); ?>" METHOD="POST" AUTOCOMPLETE="OFF" TARGET="<?php echo($target); ?>" onSubmit="return window.parent.validateSubmission();">
		<INPUT TYPE="hidden" NAME="From" VALUE="<?php echo($From); ?>">
		<INPUT TYPE="hidden" NAME="Ver" VALUE="<?php echo($Ver); ?>">
		<INPUT TYPE="hidden" NAME="L" VALUE="<?php echo($L); ?>">
		<INPUT TYPE="hidden" NAME="U" VALUE="<?php echo(htmlspecialchars(stripslashes(urlencode($U)))); ?>">
		<INPUT TYPE="hidden" NAME="R" VALUE="<?php echo(htmlspecialchars(stripslashes(urlencode($R)))); ?>">
		<INPUT TYPE="hidden" NAME="T" VALUE="<?php echo($T); ?>">
		<INPUT TYPE="hidden" NAME="D" VALUE="<?php echo($D); ?>">
		<INPUT TYPE="hidden" NAME="N" VALUE="<?php echo($N); ?>">
		<INPUT TYPE="hidden" NAME="O" VALUE="<?php echo($O); ?>">
		<INPUT TYPE="hidden" NAME="ST" VALUE="<?php echo($ST); ?>">
		<INPUT TYPE="hidden" NAME="NT" VALUE="<?php echo($NT); ?>">
		<INPUT TYPE="hidden" NAME="PWD_Hash" VALUE="<?php echo(isset($PWD_Hash) ? $PWD_Hash : ''); ?>">

		<!-- Ignored users list -->
		<INPUT TYPE="hidden" NAME="Ign" VALUE="<?php echo(isset($Ign) ? htmlspecialchars(stripslashes($Ign)) : ""); ?>">

		<!-- Last sent message or command (will be used for the '/!' command) -->
		<INPUT TYPE="hidden" NAME="M0" VALUE="<?php echo(isset($M) ? htmlspecialchars(stripslashes($M)) : ""); ?>">

		<A HREF="help_popup.php?<?php echo("L=$L&Ver=$Ver"); ?>" onClick="window.parent.help_popup(); return false" TARGET="_blank" onmouseover="document.images['helpImg'].src = window.parent.imgHelpOn.src" onmouseout="document.images['helpImg'].src = window.parent.imgHelpOff.src"><IMG NAME="helpImg" SRC="images/helpOff.gif" WIDTH=15 HEIGHT=15 BORDER=0 ALT="<?php echo(L_HLP); ?>" onClick="document.forms['MsgForm'].elements['M'].focus();"></A>&nbsp;

		<?php
		// Get the value to put in the message box : preceding M0 field value for /! command,
		// preceding entry if it was an erroneous command, else nothing; 
		$ValM = $IsM ? $M0 : "";
		if (isset($Error) && !($IsCommand)) $ValM = $M;
		?>
		<INPUT TYPE="text" NAME="M" SIZE="50" MAXLENGTH="299" VALUE="<?php echo(htmlspecialchars(stripslashes($ValM))); ?>">

		<!-- Addressee that will be filled when the user click on a nick at the users frame -->
		<INPUT TYPE="hidden" NAME="MsgTo" VALUE="">

		<?php
		if ($Ver == "L")
		{
			// Drop down list of colors for non-enabled JavaScript1.1+ browsers
			echo("<SELECT NAME=\"C\">\n");
			while(list($ColorName, $ColorCode) = each($TextColors))
			{
				// Red color is reserved to the admin or a moderator for the current room
				if ($ColorCode == "#FF0000" && !(isset($status) && ($status == "a" || $status == "m"))) continue;
				echo("<OPTION VALUE=\"".$ColorCode."\"");
				if($C == $ColorCode || $ColorCode == "#000000") echo(" SELECTED");
				echo(">".$ColorName."</OPTION>");
			}
			echo("\n</SELECT>&nbsp;\n");
		}
		else
		{
			?>
			<INPUT TYPE="hidden" NAME="C" VALUE="<?php echo($C); ?>">
			<?php
		}
		?>
		<INPUT TYPE="hidden" NAME="sent" VALUE="0">
		<INPUT TYPE="submit" NAME="sendForm" VALUE="<?php echo(L_OK); ?>">
	</TD>

	<?php
	if ($Ver != "L")
	{
		// Define the colors picker for JavaScript1.1+ enabled browsers 
		unset($TextColors);
		$TextColors = array('#000000', '#ffffff');
		for($x = 0; $x < 360; $x += 6)
		{
			$r = ceil(126 * (cos(deg2rad($x)) + 1));
			$g = ceil(126 * (cos(deg2rad($x + 240)) + 1));
			$b = ceil(126 * (cos(deg2rad($x + 120)) + 1));
			if(!($r > 128 && $g < 128 && $b < 128 && !(isset($status) && ($status == "a" || $status == "m"))))
			{
				$TextColors[] = '#'.substr('0'.dechex($r), -2).substr('0'.dechex($g), -2).substr('0'.dechex($b), -2);
			}
		}
		?>
		<TD>&nbsp;&nbsp;</TD>
		<TD>
		<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>
		<TR>
			<?php
			while(list($key, $ColorCode) = each($TextColors))
			{
				$i = $key + 1;
				if ($ColorCode == $C)
				{
					$wichImage = "selColor.gif";
					$wichSelected = $i;
				}
				else
				{
					$wichImage = "unselColor.gif";
				}
				echo("\n\t\t\t");
				echo('<td bgcolor="' . $ColorCode . '"><a href="#" onclick="window.parent.ChangeColor(\'' . $ColorCode . '\',\'C' . $i .'\'); return false;"><img src="images/' . $wichImage . '" alt="' . $ColorCode . '" name="C' . $i . '" border="0" width="2" height="20" /></a></td>');
			};
			unset($TextColors);
			echo("\n");
			?>
		</TR>
		</TABLE>
		</TD>
		<TD></FORM></TD>
		<?php
	}
	?>
</TR>
</TABLE>

<?php
// ** Ensure a color is selected in the colors picker, else select the default one (black) **
if ($Ver != "L")
{
	?>
	<SCRIPT TYPE="text/javascript" LANGUAGE="JavaScript">
	<!--
	<?php
	if (isset($wichSelected))
	{
		?>
		window.parent.SelColor = "<?php echo("C${wichSelected}"); ?>";
		<?php
	}
	else
	{
		?>
		window.parent.ChangeColor("#000000","C1");
		<?php
	}
	?>
	// -->
	</SCRIPT>
	<?php
};

// ** Refresh the messages frame if necessary **
if($RefreshMessages)
{
	$Tmp = isset($Ign) ? "&Ign=".urlencode(stripslashes($Ign)) : "";
	$First = isset($First) ? $First : 0;
	?>
	<SCRIPT TYPE="text/javascript" LANGUAGE="JavaScript">
	<!--
	<?php
	if ($Ver == "H")
	{
		if ($First) echo("window.parent.frames['messages'].window.document.close();\n\twindow.parent.connect = 0;\n");
		?>
		if (window.parent.connect == 0)
		{
			window.parent.refresh_query = "<?php echo("From=".urlencode($From)."&L=$L&U=".urlencode(stripslashes($U))."&R=".urlencode(stripslashes($R))."&T=$T&D=$D&N=$N&ST=$ST&NT=$NT".$Tmp."&First=$First"); ?>";
			window.parent.force_refresh();
		};
		<?php
	}
	else
	{
		?>
		window.parent.frames['messages'].window.location = 'messagesL.php?<?php echo("From=".urlencode($From)."&L=$L&U=".urlencode(stripslashes($U))."&R=".urlencode(stripslashes($R))."&T=$T&D=$D&N=$N&O=$O&ST=$ST&NT=$NT".$Tmp); ?>';
		<?php
	};
	?>
	// -->
	</SCRIPT>
	<?php
};

// ** Display a JavaScript alert box with the error message if necessary **
if(isset($Error))
{
	?>
	<SCRIPT TYPE="text/javascript" LANGUAGE="JavaScript">
	<!--
	document.forms['MsgForm'].elements['M'].select();
	alert("<?php echo(str_replace("\\\\n","\\n",addslashes($Error))); ?>");
	// -->
	</SCRIPT>
	<?php
}

// ** Put JavaScript instructions that commands may have set
if (isset($jsTbl))
{
	for (reset($jsTbl); $jsInst=current($jsTbl); next($jsTbl))
	{
		echo("$jsInst\n");
	};
	unset($jsTbl);
}
?>
</BODY>

</HTML>