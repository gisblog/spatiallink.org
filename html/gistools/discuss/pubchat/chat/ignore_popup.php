<?php
// Get the names and values for vars sent by input.php
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
require("./lib/release.lib.php");

header("Content-Type: text/html; charset=${Charset}");

// For translations with an explicit charset (not the 'x-user-defined' one)
if (!isset($FontName)) $FontName = "";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML dir="<?php echo(($Charset == "windows-1256") ? "RTL" : "LTR"); ?>">

<HEAD>
<TITLE><?php echo(L_IGNOR_TIT); ?></TITLE>
<LINK REL="stylesheet" HREF="config/style.css.php?<?php echo("Charset=${Charset}&medium=${FontSize}&FontName=${FontName}"); ?>" TYPE="text/css">
<SCRIPT TYPE="text/javascript" LANGUAGE="javascript1.1">
<!--
// Put the focus at the message box in the input frame
function put_focus()
{
	var clean = (document.forms['IgnForm'].Refresh.value != 1);
	with (window.opener)
	{
		if (clean) window.parent.is_ignored_popup = null;
		focus();
		if (document.forms['MsgForm']) document.forms['MsgForm'].elements['M'].focus();
	}
}
// -->
</SCRIPT>
</HEAD>

<BODY CLASS="frame" onUnload="if (window.opener && !window.opener.closed && document.forms['IgnForm'].elements['Exit'].value != 1) put_focus();">
<CENTER>
<?php
if(isset($Ign))
{
	$Ignore = explode (",", $Ign);
	for ($i = 0; $i < count($Ignore); $i++)
	{
		echo("-&nbsp;".urldecode($Ignore[$i])."<BR>");
	}
}
else
{
	echo(L_IGNOR_NON);
}
?>
</CENTER>

<?php
// The form bellow allows to check whether the popup unloads because ignored users list
// has been modified, because the user reduced/closed it or because the user exit the
// chat
?>
<FORM ACTION="nothing" METHOD="POST" AUTOCOMPLETE="OFF" NAME="IgnForm">
	<INPUT TYPE="hidden" NAME="Refresh" VALUE="0">
	<INPUT TYPE="hidden" NAME="Exit" VALUE="0">
</FORM>
</BODY>

</HTML>