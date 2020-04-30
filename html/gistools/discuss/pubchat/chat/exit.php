<?php
// Get the names and values for vars sent by index.lib.php
if (isset($HTTP_GET_VARS))
{
	while(list($name,$value) = each($HTTP_GET_VARS))
	{
		$$name = $value;
	};
};

// Fix a security hole
if (isset($L) && !is_dir('./localization/'.$L)) exit();
	
require("./config/config.lib.php");
require("./localization/".$L."/localized.chat.php");

header("Content-Type: text/html; charset=${Charset}");

$Ver1 = ($Ver == "H" ? $Ver : "L");

// For translations with an explicit charset (not the 'x-user-defined' one)
if (!isset($FontName)) $FontName = "";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML dir="<?php echo(($Charset == "windows-1256") ? "RTL" : "LTR"); ?>">

<HEAD>
<TITLE>Exit frame</TITLE>
<LINK REL="stylesheet" HREF="config/style.css.php?<?php echo("Charset=${Charset}&medium=${FontSize}&FontName=${FontName}"); ?>" TYPE="text/css">
<SCRIPT TYPE="text/javascript" LANGUAGE="javascript">
<!--
// Open the users popup
function users_popup()
{
	window.focus();
	users_popupWin = window.open("users_popup<?php echo($Ver1); ?>.php?<?php echo("From=$From&L=$L"); ?>","users_popup_<?php echo(md5(uniqid(""))); ?>","width=180,height=300,resizable=yes,scrollbars=yes");
	users_popupWin.focus();
}

// Close some popups when the user exits the chat
function close_popups()
{
	with (window.parent)
	{
		if (is_help_popup && !is_help_popup.closed) is_help_popup.close();
		if (is_ignored_popup && !is_ignored_popup.closed)
		{
			is_ignored_popup.window.document.forms['IgnForm'].elements['Exit'].value = '1';
			is_ignored_popup.close()
		};
		if (frames['loader'] && !frames['loader'].closed && leaveChat)
		{
			leaveChat = true;
			frames['loader'].close();
		};
	};
}
// -->
</SCRIPT>
</HEAD>

<BODY CLASS="frame" onUnload="close_popups()">
<CENTER>
<A HREF="<?php echo("$From?Ver=$Ver&L=$L&U=".urlencode(stripslashes($U))."&E=".urlencode(stripslashes($R))."&EN=$T"); ?>" TARGET="_parent"><?php echo(L_EXIT); ?></A>
<BR>
<?php
if ($FontSize < 11) echo("<BR>");
if ($Ver == "H")
{
	?>
	<!-- Display the big + clickable icon used to expand/collapse all rooms -->
	<A HREF="#" onClick="window.parent.expandAll(); return false">
	<IMG NAME="imEx_big" SRC="images/closed_big.gif" WIDTH=13 HEIGHT=13 ALIGN="MIDDLE" BORDER=0 ALT="<?php echo(L_EXPCOL_ALL); ?>"></A>
	&nbsp;
	<?php
}
?>
<A HREF="users_popup<?php echo($Ver1); ?>.php?<?php echo("From=$From&L=$L"); ?>" onClick="users_popup(); return false" TARGET="_blank"><IMG SRC="images/popup.gif" WIDTH=13 HEIGHT=13 ALIGN="MIDDLE" BORDER=0 ALT="<?php echo(L_DETACH); ?>"></A>
<?php
if ($Ver == "H")
{
	?>
	<!-- Display the connection state icon -->
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<A HREF="#" onClick="window.parent.reConnect(); if (window.parent.frames['input'] && window.parent.frames['input'].window.document.forms['MsgForm'].elements['M']) window.parent.frames['input'].window.document.forms['MsgForm'].elements['M'].focus(); return false">
	<IMG NAME="ConState" SRC="images/connectOff.gif" WIDTH=13 HEIGHT=13 ALIGN="MIDDLE" BORDER=0 ALT="<?php echo(L_CONN_STATE); ?>"></A>
	<?php
}
?>
</CENTER>
</BODY>

</HTML>