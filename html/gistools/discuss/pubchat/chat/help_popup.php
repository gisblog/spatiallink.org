<?php
if (isset($HTTP_GET_VARS["L"])) $L = $HTTP_GET_VARS["L"];

// Fix a security hole
if (isset($L) && !is_dir('./localization/'.$L)) exit();
	
if (isset($HTTP_GET_VARS["Ver"])) $Ver = $HTTP_GET_VARS["Ver"];
require("./config/config.lib.php");
require("./localization/".$L."/localized.chat.php");
require("./lib/release.lib.php");

header("Content-Type: text/html; charset=${Charset}");

// For translations with an explicit charset (not the 'x-user-defined' one)
if (!isset($FontName)) $FontName = "";

// Text direction and horizontal alignment for cells topic
$TextDir = ($Charset == "windows-1256" ? "RTL" : "LTR");
$CellAlign = ($Charset != "windows-1256" ? "LEFT" : "RIGHT");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML dir="<?php echo($TextDir); ?>">

<HEAD>
<TITLE><?php echo(L_HLP); ?></TITLE>
<LINK REL="stylesheet" HREF="config/style.css.php?<?php echo("Charset=${Charset}&medium=${FontSize}&FontName=${FontName}"); ?>" TYPE="text/css">
<SCRIPT TYPE="text/javascript" LANGUAGE="javascript1.1">
<!--
function targetWin()
{
	if (window.opener.window.document.title == "<?php echo(APP_NAME); ?>")
		return window.opener.frames['input'].window;
	else if (window.opener.window.document.title == "Hidden Input frame")
		return window.opener.window.parent.frames['input'].window;
	else
		return window.opener.window;
}

function smiley2Input(code)
{
	window.focus();
	if (window.opener && !window.opener.closed)
	{
		addTo = targetWin();
		if (addTo && !addTo.closed) addTo.document.forms['MsgForm'].elements['M'].value += code;
	};
}

function cmd2Input(code,addstring)
{
	window.focus();
	if (window.opener && !window.opener.closed)
	{
		addTo = targetWin();
		if (addTo && !addTo.closed)
		{
			oldStr = (addstring ? addTo.document.forms['MsgForm'].elements['M'].value : "");
			if (addstring && (oldStr == "" || oldStr.substring(0,1) != " "))
				oldStr = " " + oldStr;
			addTo.document.forms['MsgForm'].elements['M'].value = code + oldStr;
		};
	};
}
//-->
</SCRIPT>
</HEAD>

<BODY CLASS="mainframe" onLoad="if (window.focus) window.focus();">
<CENTER>

<?php
if (C_USE_SMILIES == "1")
{
	include("./lib/smilies.lib.php");
	$Nb = count($SmiliesTbl);
	$ResultTbl = Array();
	DisplaySmilies($ResultTbl,$SmiliesTbl,$Nb,"help");
	unset($SmiliesTbl);
	?>
	<!-- Smilies codes -->
	<TABLE BORDER=0 CELLPADDING=3 WIDTH=574 CLASS="table">
	<TR>
		<TH CLASS="tabtitle" COLSPAN=<?php echo($Nb); ?>><?php echo(L_HELP_TIT_1); ?></TH>
	</TR>
	<?php
	$i = "0";
	$Nb = count($ResultTbl);
	while($i < $Nb)
	{
		if ($i > 0) echo("\t");
		echo("<TR VALIGN=\"BOTTOM\">\n");
		echo("$ResultTbl[$i]");
		echo("\t</TR>\n\t<TR>\n");
		$i++;
		echo("$ResultTbl[$i]");
		echo("\t</TR>\n");
		$i++;
	};
	unset($ResultTbl);
	?>
	</TABLE>
	<BR>
	<?php
};

if (C_HTML_TAGS_KEEP != "none")
{
	?>
	<!-- Text formatting help -->
	<TABLE BORDER=0 CELLPADDING=3 WIDTH=574 CLASS="table">
		<TR><TD ALIGN="CENTER" CLASS="tabtitle"><?php echo(L_HELP_TIT_2); ?></TD></TR>
		<TR><TD ALIGN="<?php echo($CellAlign); ?>"><?php echo(L_HELP_FMT_1); ?></TD></TR>
		<TR><TD ALIGN="<?php echo($CellAlign); ?>"><?php echo(L_HELP_FMT_2); ?></TD></TR>
	</TABLE>
	<BR>
	<?php
}
?>

<!-- Commands help -->
<TABLE BORDER=0 CELLPADDING=3 WIDTH=574 CLASS="table">
	<TR><TH ALIGN="CENTER" CLASS="tabtitle" COLSPAN=2><?php echo(L_HELP_TIT_3); ?></TH></TR>
	<TR><TH ALIGN="CENTER" COLSPAN=2><?php echo(L_HELP_CMD_0); ?></TH></TR>
	<TR><TH ALIGN="<?php echo($CellAlign); ?>" COLSPAN=2><A HREF="#" onClick="cmd2Input('/!',false); return false" CLASS="sender">/!</A></TH></TR>
	<TR>
		<TD WIDTH=10>&nbsp;</TD>
		<TD><?php echo(L_HELP_CMD_7); ?></TD>
	</TR>
	<TR><TH ALIGN="<?php echo($CellAlign); ?>" COLSPAN=2><A HREF="#" onClick="cmd2Input('/ANNOUNCE',true); return false" CLASS="sender">/announce {<?php echo(L_HELP_MSG); ?>}<A></TH></TR>
	<TR>
		<TD WIDTH=10>&nbsp;</TD>
		<TD><?php echo(L_HELP_CMD_17); ?></TD>
	</TR>
	<?php
	if (C_BANISH != "0")
	{
		?>
		<TR><TH ALIGN="<?php echo($CellAlign); ?>" COLSPAN=2><A HREF="#" onClick="cmd2Input('/BAN',true); return false" CLASS="sender">/ban <BDO dir="<?php echo($TextDir); ?>">[*]</BDO> {<?php echo(L_HELP_USR); ?>}<A></TH></TR>
		<TR>
			<TD WIDTH=10>&nbsp;</TD>
			<TD><?php echo(L_HELP_CMD_19); ?></TD>
		</TR>
		<?php
	};
	// $Ver value is 'H' for dynamic rendering of the messages frame, 'L' or 'M' in other case
	if ($Ver == "H")
	{
		?>
		<TR><TH ALIGN="<?php echo($CellAlign); ?>" COLSPAN=2><A HREF="#" onClick="cmd2Input('/CLEAR',false); return false" CLASS="sender">/clear</A></TH></TR>
		<TR>
			<TD WIDTH=10>&nbsp;</TD>
			<TD><?php echo(L_HELP_CMD_15); ?></TD>
		</TR>
		<?php
	};
	?>
	<TR><TH ALIGN="<?php echo($CellAlign); ?>" COLSPAN=2><A HREF="#" onClick="cmd2Input('/IGNORE',true); return false" CLASS="sender">/ignore <BDO dir="<?php echo($TextDir); ?>">[-]</BDO> <?php echo("[".L_HELP_USR."[,".L_HELP_USR."...]]"); ?></A></TH></TR>
	<TR>
		<TD WIDTH=10>&nbsp;</TD>
		<TD><?php echo(L_HELP_CMD_6); ?></TD>
	</TR>
	<TR><TH ALIGN="<?php echo($CellAlign); ?>" COLSPAN=2><A HREF="#" onClick="cmd2Input('/INVITE',true); return false" CLASS="sender">/invite {<?php echo(L_HELP_USR); ?>}</A></TH></TR>
	<TR>
		<TD WIDTH=10>&nbsp;</TD>
		<TD><?php echo(L_HELP_CMD_18); ?></TD>
	</TR>
	<?php
	if (C_VERSION > 0)
	{
		?>
		<TR><TH ALIGN="<?php echo($CellAlign); ?>" COLSPAN=2><A HREF="#" onClick="cmd2Input('/JOIN',true); return false" CLASS="sender">/join <BDO dir="<?php echo($TextDir); ?>">[n]</BDO> {#<?php echo(L_HELP_ROOM); ?>}</A></TH></TR>
		<TR>
			<TD WIDTH=10>&nbsp;</TD>
			<TD><?php echo(L_HELP_CMD_4); ?></TD>
		</TR>
		<?php
	}
	?>
	<TR><TH ALIGN="<?php echo($CellAlign); ?>" COLSPAN=2><A HREF="#" onClick="cmd2Input('/KICK',true); return false" CLASS="sender">/kick {<?php echo(L_HELP_USR); ?>}</A></TH></TR>
	<TR>
		<TD WIDTH=10>&nbsp;</TD>
		<TD><?php echo(L_HELP_CMD_9); ?></TD>
	</TR>
	<TR><TH ALIGN="<?php echo($CellAlign); ?>" COLSPAN=2><A HREF="#" onClick="cmd2Input('/ME',true); return false" CLASS="sender">/me <?php echo("{".L_HELP_MSG."}"); ?></A><BR></TH></TR>
	<TR>
		<TD WIDTH=10>&nbsp;</TD>
		<TD><?php echo(L_HELP_CMD_20); ?></TD>
	</TR>
	<TR><TH ALIGN="<?php echo($CellAlign); ?>" COLSPAN=2>
		<A HREF="#" onClick="cmd2Input('/MSG',true); return false" CLASS="sender">/msg <?php echo("{".L_HELP_USR."} {".L_HELP_MSG."}"); ?></A><BR>
		<A HREF="#" onClick="cmd2Input('/TO',true); return false" CLASS="sender">/to <?php echo("{".L_HELP_USR."} {".L_HELP_MSG."}"); ?></A>
	</TH></TR>
	<TR>
		<TD WIDTH=10>&nbsp;</TD>
		<TD><?php echo(L_HELP_CMD_10); ?></TD>
	</TR>
	<TR><TH ALIGN="<?php echo($CellAlign); ?>" COLSPAN=2><A HREF="#" onClick="cmd2Input('/NOTIFY',false); return false" CLASS="sender">/notify</A></TH></TR>
	<TR>
		<TD WIDTH=10>&nbsp;</TD>
		<TD><?php echo(L_HELP_CMD_13); ?></TD>
	</TR>
	<?php
	if ($Ver != "H")
	{
		?>
		<TR><TH ALIGN="<?php echo($CellAlign); ?>" COLSPAN=2><A HREF="#" onClick="cmd2Input('/ORDER',false); return false" CLASS="sender">/order</A></TH></TR>
		<TR>
			<TD WIDTH=10>&nbsp;</TD>
			<TD><?php echo(L_HELP_CMD_3); ?></TD>
		</TR>
		<?php
	}
	?>
	<TR><TH ALIGN="<?php echo($CellAlign); ?>" COLSPAN=2><A HREF="#" onClick="cmd2Input('/PROFILE',false); return false" CLASS="sender">/profile</A></TH></TR>
	<TR>
		<TD WIDTH=10>&nbsp;</TD>
		<TD><?php echo(L_HELP_CMD_12); ?></TD>
	</TR>
	<TR><TH ALIGN="<?php echo($CellAlign); ?>" COLSPAN=2><A HREF="#" onClick="cmd2Input('/PROMOTE',true); return false" CLASS="sender">/promote {<?php echo(L_HELP_USR); ?>}</A></TH></TR>
	<TR>
		<TD WIDTH=10>&nbsp;</TD>
		<TD><?php echo(L_HELP_CMD_14); ?></TD>
	</TR>
	<TR><TH ALIGN="<?php echo($CellAlign); ?>" COLSPAN=2>
		<A HREF="#" onClick="cmd2Input('/QUIT',true); return false" CLASS="sender">/quit [<?php echo(L_HELP_MSG); ?>]</A><BR>
		<A HREF="#" onClick="cmd2Input('/EXIT',true); return false" CLASS="sender">/exit [<?php echo(L_HELP_MSG); ?>]</A><BR>
		<A HREF="#" onClick="cmd2Input('/BYE',true); return false" CLASS="sender">/bye [<?php echo(L_HELP_MSG); ?>]</A>
	</TH></TR>
	<TR>
		<TD WIDTH=10>&nbsp;</TD>
		<TD><?php echo(L_HELP_CMD_5); ?></TD>
	</TR>
	<TR><TH ALIGN="<?php echo($CellAlign); ?>" COLSPAN=2><A HREF="#" onClick="cmd2Input('/REFRESH',true); return false" CLASS="sender">/refresh <BDO dir="<?php echo($TextDir); ?>">[n]</BDO></A></TH></TR>
	<TR>
		<TD WIDTH=10>&nbsp;</TD>
		<TD><?php echo(($Ver == "H" ? L_HELP_CMD_2b : L_HELP_CMD_2a)); ?></TD>
	</TR>
	<?php
	if (C_SAVE != "0")
	{
		?>
		<TR><TH ALIGN="<?php echo($CellAlign); ?>" COLSPAN=2><A HREF="#" onClick="cmd2Input('/SAVE',true); return false" CLASS="sender">/save <BDO dir="<?php echo($TextDir); ?>">[n]</BDO></A></TH></TR>
		<TR>
			<TD WIDTH=10>&nbsp;</TD>
			<TD><?php echo(L_HELP_CMD_16); ?></TD>
		</TR>
		<?php
	}
	?>
	<TR><TH ALIGN="<?php echo($CellAlign); ?>" COLSPAN=2>
		<A HREF="#" onClick="cmd2Input('/SHOW',true); return false" CLASS="sender">/show <BDO dir="<?php echo($TextDir); ?>">[n]</BDO></A>
		<?php
		if ($Ver == "H")
		{
			?>
			<BR><A HREF="#" onClick="cmd2Input('/LAST',true); return false" CLASS="sender">/last <BDO dir="<?php echo($TextDir); ?>">[n]</BDO></A>
			<?php
		};
		?>
	</TH></TR>
	<TR>
		<TD WIDTH=10>&nbsp;</TD>
		<TD><?php echo(($Ver == "H") ? L_HELP_CMD_1b : L_HELP_CMD_1a); ?></TD>
	</TR>
	<TR><TH ALIGN="<?php echo($CellAlign); ?>" COLSPAN=2><A HREF="#" onClick="cmd2Input('/TIMESTAMP',false); return false" CLASS="sender">/timestamp</A></TH></TR>
	<TR>
		<TD WIDTH=10>&nbsp;</TD>
		<TD><?php echo(L_HELP_CMD_8); ?></TD>
	</TR>
	<TR><TH ALIGN="<?php echo($CellAlign); ?>" COLSPAN=2><A HREF="#" onClick="cmd2Input('/WHOIS',true); return false" CLASS="sender">/whois {<?php echo(L_HELP_USR); ?>}</A></TH></TR>
	<TR>
		<TD WIDTH=10>&nbsp;</TD>
		<TD><?php echo(L_HELP_CMD_11); ?></TD>
	</TR>
</TABLE>
</CENTER>
</BODY>

</HTML>