<?php
if ((isset($AUTH_USERNAME) && $AUTH_USERNAME != "") && (isset($AUTH_PASSWORD) && $AUTH_PASSWORD != ""))
{
	// Ensure the password is a correct one
	$DbLink4Login = new DB;
	$DbLink4Login->query("SELECT password,perms FROM ".C_REG_TBL." WHERE username='$AUTH_USERNAME' LIMIT 1");
	if ($DbLink4Login->num_rows() != 0)
	{
		list($PWD_Hash, $perms) = $DbLink4Login->next_record();
		if ($PWD_Hash == md5($AUTH_PASSWORD) || $PWD_Hash == $AUTH_PASSWORD)
		{
			// Ensure the one who lauch the admin.php script is really admin 
			if (isset($MUST_BE_ADMIN) && $perms != "admin")
			{
				$Error = L_ERR_USR_11;
			}
			else
			{
				$do_not_login = true;
			}
		}
	}
	$DbLink4Login->clean_results();
	$DbLink4Login->close();
}

// If no login yet entered
if (!isset($do_not_login))
{

// Special cache instructions for IE5+
$CachePlus	= "";
if (ereg("MSIE [56789]", (isset($HTTP_USER_AGENT)) ? $HTTP_USER_AGENT : getenv("HTTP_USER_AGENT"))) $CachePlus = ", pre-check=0, post-check=0, max-age=0";
$now		= gmdate('D, d M Y H:i:s') . ' GMT';

header("Expires: $now");
header("Last-Modified: $now");
header("Cache-Control: no-cache, must-revalidate".$CachePlus);
header("Pragma: no-cache");
header("Content-Type: text/html; charset=${Charset}");

// avoid server configuration for magic quotes
set_magic_quotes_runtime(0);

if (isset($AUTH_PASSWORD))
{
	if (!isset($Error)) $Error = L_ERR_USR_10;
}

// If this script is lauch by a profile command, put focus to the password field
$Focus = (isset($LIMIT) && $LIMIT == 1 ) ? "AUTH_PASSWORD" : "AUTH_USERNAME";

// For translations with an explicit charset (not the 'x-user-defined' one)
if (!isset($FontName)) $FontName = "";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML dir="<?php echo(($Charset == "windows-1256") ? "RTL" : "LTR"); ?>">

<HEAD>
<TITLE><?php echo(APP_NAME); ?></TITLE>
<LINK REL="stylesheet" HREF="config/style.css.php?<?php echo("Charset=${Charset}&medium=${FontSize}&FontName=${FontName}"); ?>" TYPE="text/css">
<link rel="stylesheet" type="text/css" href="/css/spatiallink.css" />
<SCRIPT TYPE="text/javascript" LANGUAGE="JavaScript1.1">
<!--
function get_focus()
{
	window.focus();
	document.forms['LoginForm'].elements['<?php echo($Focus); ?>'].focus();
}
// -->
</SCRIPT>
</HEAD>

<BODY onLoad="if (window.focus) get_focus();">
<CENTER>
<BR>
<?php
// Get the name of the script that called the login library
if (!isset($PHP_SELF)) $PHP_SELF = $HTTP_SERVER_VARS["PHP_SELF"];
$From = basename($PHP_SELF);
?>
<FORM ACTION="<?php echo($From); ?>" METHOD="POST" AUTOCOMPLETE="OFF" NAME="LoginForm">
<P></P>
<?php
if(isset($Error))
{
	echo("<P><SPAN CLASS=\"error\">$Error</SPAN></P>");
}
?>
<INPUT TYPE="hidden" NAME="L" VALUE="<?php echo($L); ?>">
<INPUT TYPE="hidden" NAME="Link" VALUE="<?php if (isset($Link)) echo($Link); ?>">
<INPUT TYPE="hidden" NAME="LIMIT" VALUE="<?php if (isset($LIMIT)) echo($LIMIT); ?>">
<TABLE BORDER=0 CELLPADDING=3 CLASS="table">
<TR>
	<TD ALIGN="CENTER">
		<TABLE BORDER=0>
		<TR>
			<TH COLSPAN=2 CLASS="tabtitle"><?php echo(L_REG_14); ?></TH>
		</TR>
		<TR>
			<TD ALIGN="RIGHT" VALIGN="TOP" NOWRAP><?php echo(L_SET_2); ?> :</TD>
			<TD VALIGN="TOP">
				<INPUT TYPE="text" NAME="AUTH_USERNAME" SIZE=11 MAXLENGTH=10 VALUE="<?php if (isset($AUTH_USERNAME)) echo(htmlspecialchars(stripslashes($AUTH_USERNAME))); ?>">
			</TD>
		</TR>
		<TR>
			<TD ALIGN="RIGHT" VALIGN="TOP" NOWRAP><?php echo(L_REG_7); ?> :</TD>
			<TD VALIGN="TOP">
				<INPUT TYPE="password" NAME="AUTH_PASSWORD" SIZE=11 MAXLENGTH=16 VALUE="<?php if (isset($AUTH_PASSWORD)) echo(htmlspecialchars(stripslashes($AUTH_PASSWORD))); ?>">
			</TD>
		</TR>
		</TABLE>
		<P>
		<INPUT TYPE="submit" VALUE="<?php echo(L_REG_15); ?>">
	</TD>
</TR>
</TABLE>
</FORM>
</CENTER>
</BODY>

</HTML>
<?php
exit;

} // if(!isset($do_not_login))
?>