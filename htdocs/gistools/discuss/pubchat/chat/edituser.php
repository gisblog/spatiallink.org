<?php
// Get the names and values for vars sent to this script
if (isset($HTTP_GET_VARS))
{
	while(list($name,$value) = each($HTTP_GET_VARS))
	{
		$$name = $value;
	};
};

// Get the names and values for vars posted from the form bellow
if (isset($HTTP_POST_VARS))
{
	while(list($name,$value) = each($HTTP_POST_VARS))
	{
		$$name = $value;
	};
};

// Fix a security hole
if (isset($L) && !is_dir('./localization/'.$L)) exit();
	
require("./config/config.lib.php");
require("./localization/languages.lib.php");
require("./localization/".$L."/localized.chat.php");
require("./lib/release.lib.php");
require("./lib/database/".C_DB_TYPE.".lib.php");
require("./lib/login.lib.php");

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

$DbLink = new DB;

// Check for valid entries if the form have been sent
if (isset($FORM_SEND) && stripslashes($submit_type) == L_REG_16)
{
	if (C_NO_SWEAR == 1) include("./lib/swearing.lib.php");
	if (trim($U) == "")
	{
		$Error = L_ERR_USR_5;
	}
	else if (ereg("[\, ]", stripslashes($U)))
	{
		$Error = L_ERR_USR_16;
	}
	else if(C_NO_SWEAR == 1 && checkwords($U, true))
	{
		$Error = L_ERR_USR_18;
	}
	else if ($PASSWORD == "")
	{
		$Error = L_ERR_USR_6;
	}
	else if (trim($FIRSTNAME) == "" || trim($LASTNAME) == "")
	{
		$Error = L_ERR_USR_15;
	}
	else if (trim($EMAIL) == "")
	{
		$Error = L_ERR_USR_7;
	}
	else if (!eregi("^([0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-wyz][a-z](fo|g|l|m|mes|o|op|pa|ro|seum|t|u|v|z)?)$", $EMAIL))
	{
		$Error = L_ERR_USR_8;
	}
	else if ($U != $AUTH_USERNAME)
	{
		$DbLink->query("SELECT count(*) FROM ".C_REG_TBL." WHERE username='$U'");
		list($rows) = $DbLink->next_record();
		$DbLink->clean_results();
		if ($rows != 0) $Error = L_ERR_USR_9;
	}

	if (!isset($Error))
	{
		$Latin1 = ($Charset == "iso-8859-1");
		$PWD_Hash = md5(stripslashes($PASSWORD));
		if (!isset($GENDER)) $GENDER = "";
		$showemail = (isset($SHOWEMAIL) && $SHOWEMAIL)? 1:0;
		include("./lib/get_IP.lib.php");		// Set the $IP var
		$DbLink->query("UPDATE ".C_REG_TBL." SET username='$U', latin1='$Latin1', password='$PWD_Hash', firstname='$FIRSTNAME', lastname='$LASTNAME', country='$COUNTRY', website='$WEBSITE', email='$EMAIL', showemail=$showemail, reg_time=".time().", ip='$IP', gender='$GENDER' WHERE username='$AUTH_USERNAME'");
		if ($AUTH_USERNAME != $U) $AUTH_USERNAME = $U;
		if ($AUTH_PASSWORD != $PASSWORD) $AUTH_PASSWORD = $PASSWORD;
		$Message = L_REG_17;
	}
}
// Else initialize var that will be displayed in the form
else
{
	$U = $AUTH_USERNAME;
	$PASSWORD = $AUTH_PASSWORD;
	$DbLink->query("SELECT firstname,lastname,country,website,email,showemail,gender FROM ".C_REG_TBL." WHERE username='$U' LIMIT 1");
	if ($DbLink->num_rows() != 0)
	{
		list($FIRSTNAME, $LASTNAME, $COUNTRY, $WEBSITE, $EMAIL, $SHOWEMAIL, $GENDER) = $DbLink->next_record();
	}
	$DbLink->clean_results();
}

$DbLink->close();

// Modifications have been done ?
$done = (isset($Message) && $Message == L_REG_17);

// For translations with an explicit charset (not the 'x-user-defined' one)
if (!isset($FontName)) $FontName = "";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML dir="<?php echo(($Charset == "windows-1256") ? "RTL" : "LTR"); ?>">

<HEAD>
<TITLE><?php echo(APP_NAME); ?></TITLE>
<LINK REL="stylesheet" HREF="config/style.css.php?<?php echo("Charset=${Charset}&medium=${FontSize}&FontName=${FontName}"); ?>" TYPE="text/css">
<SCRIPT TYPE="text/javascript" LANGUAGE="javascript1.1">
<!--
// Put the focus to the message box if the window has been called with the profile command
function put_focus()
{
	if (window.opener.window.document.title == "Hidden Input frame")
		targetFrame = window.opener.window.parent.frames['input'].window;
	else
		targetFrame = window.opener.window;

	with (targetFrame)
	{
		focus();
		if (document.forms['MsgForm'] && document.forms['MsgForm'].elements['M'])
			document.forms['MsgForm'].elements['M'].focus();
	};
}
// -->
</SCRIPT>
</HEAD>

<BODY>
<CENTER>
<BR>
<FORM ACTION="edituser.php" METHOD="POST" AUTOCOMPLETE="OFF" NAME="EditUsrForm">
<INPUT type="hidden" name="FORM_SEND" value="1">
<INPUT type="hidden" name="AUTH_USERNAME" value="<?php echo(htmlspecialchars(stripslashes($AUTH_USERNAME))); ?>">
<INPUT type="hidden" name="AUTH_PASSWORD" value="<?php echo(htmlspecialchars(stripslashes($AUTH_PASSWORD))); ?>">
<P></P>
<?php
if(isset($Error))
{
	echo("<P><SPAN CLASS=\"error\">$Error</SPAN></P>");
}
?>
<INPUT TYPE="hidden" NAME="L" VALUE="<?php echo($L); ?>">
<TABLE BORDER=0 CELLPADDING=3 CLASS="table">
<TR>
	<TD ALIGN="CENTER">
		<TABLE BORDER=0>
		<TR>
			<TH COLSPAN=2 CLASS="tabtitle"><?php echo($done ? $Message : L_REG_34); ?></TH>
		</TR>
		<TR>
			<TH COLSPAN=2><?php if (!$done) echo(L_REG_37); ?></TH>
		</TR>
		<TR><TD>&nbsp;</TD></TR>
		<TR>
			<TD ALIGN="RIGHT" VALIGN="TOP" NOWRAP><?php echo(L_SET_2); ?> :</TD>
			<TD VALIGN="TOP">
				<!-- Nick can not be modified via the profile command -->
				<INPUT TYPE="text" NAME="U" SIZE=11 MAXLENGTH=10 VALUE="<?php echo(htmlspecialchars(stripslashes($U))); ?>"<?php if ($done) echo(" READONLY"); if (isset($LIMIT) && $LIMIT) echo(" DISABLED"); ?>>
				<?php
				if (isset($LIMIT) && $LIMIT)
				{
					?>
					<INPUT TYPE="hidden" NAME="U" VALUE="<?php echo(htmlspecialchars(stripslashes($U))); ?>">
					<?php
				};
				if (!$done)
				{
					?>
					<SPAN CLASS="error">*</SPAN>
					<?php
				};
				?>
			</TD>
		</TR>
		<TR>
			<TD ALIGN="RIGHT" VALIGN="TOP" NOWRAP><?php echo(L_REG_7); ?> :</TD>
			<TD VALIGN="TOP">
				<!-- Password can not be modified via the profile command -->
				<INPUT TYPE="password" NAME="PASSWORD" SIZE=11 MAXLENGTH=16 VALUE="<?php echo(htmlspecialchars(stripslashes($PASSWORD))); ?>"<?php if ($done) echo(" READONLY"); if (isset($LIMIT) && $LIMIT) echo(" DISABLED"); ?>>
				<?php
				if (isset($LIMIT) && $LIMIT)
				{
					?>
					<INPUT TYPE="hidden" NAME="PASSWORD" VALUE="<?php echo(htmlspecialchars(stripslashes($PASSWORD))); ?>">
					<?php
				};
				if (!$done)
				{
					?>
					<SPAN CLASS="error">*</SPAN>
					<?php
				};
				?>
			</TD>
		</TR>
		<TR>
			<TD ALIGN="RIGHT" VALIGN="TOP" NOWRAP><?php echo(L_REG_30); ?> :</TD>
			<TD VALIGN="TOP">
				<INPUT TYPE="text" NAME="FIRSTNAME" SIZE=11 MAXLENGTH=64 VALUE="<?php echo(htmlspecialchars(stripslashes($FIRSTNAME))); ?>"<?php if ($done) echo(" READONLY"); ?>>
				<?php if (!$done) { ?><SPAN CLASS="error">*</SPAN><?php }; ?>
			</TD>
		</TR>
		<TR>
			<TD ALIGN="RIGHT" VALIGN="TOP" NOWRAP><?php echo(L_REG_31); ?> :</TD>
			<TD VALIGN="TOP">
				<INPUT TYPE="text" NAME="LASTNAME" SIZE=11 MAXLENGTH=64 VALUE="<?php echo(htmlspecialchars(stripslashes($LASTNAME))); ?>"<?php if ($done) echo(" READONLY"); ?>>
				<?php if (!$done) { ?><SPAN CLASS="error">*</SPAN><?php }; ?>
			</TD>
		</TR>
		<TR>
			<TD ALIGN="RIGHT" VALIGN="TOP" NOWRAP><?php echo(L_REG_45); ?> :</TD>
			<TD VALIGN="TOP">
				<INPUT TYPE="radio" NAME="GENDER" VALUE="1" <?php if (isset($GENDER) && $GENDER == "1") echo("CHECKED"); if ($done) echo(" READONLY"); ?>>&nbsp;<?php echo(L_REG_46); ?><BR>
				<INPUT TYPE="radio" NAME="GENDER" VALUE="2" <?php if (isset($GENDER) && $GENDER == "2") echo("CHECKED"); if ($done) echo(" READONLY"); ?>>&nbsp;<?php echo(L_REG_47); ?>
			</TD>
		</TR>
		<TR>
			<TD ALIGN="RIGHT" VALIGN="TOP" NOWRAP><?php echo(L_REG_36); ?> :</TD>
			<TD VALIGN="TOP">
				<INPUT TYPE="text" NAME="COUNTRY" SIZE=11 MAXLENGTH=64 VALUE="<?php echo(htmlspecialchars(stripslashes($COUNTRY))); ?>"<?php if ($done) echo(" READONLY"); ?>>
			</TD>
		</TR>
		<TR>
			<TD ALIGN="RIGHT" VALIGN="TOP" NOWRAP><?php echo(L_REG_32); ?> :</TD>
			<TD VALIGN="TOP">
				<INPUT TYPE="text" NAME="WEBSITE" SIZE=11 MAXLENGTH=64 VALUE="<?php echo(htmlspecialchars(stripslashes($WEBSITE))); ?>"<?php if ($done) echo(" READONLY"); ?>>
			</TD>
		</TR>
		<TR>
			<TD ALIGN="RIGHT" VALIGN="TOP" NOWRAP><?php echo(L_REG_8); ?> :</TD>
			<TD VALIGN="TOP">
				<INPUT TYPE="text" NAME="EMAIL" SIZE=11 MAXLENGTH=64 VALUE="<?php echo(htmlspecialchars(stripslashes($EMAIL))); ?>"<?php if ($done) echo(" READONLY"); ?>>
				<?php if (!$done) { ?><SPAN CLASS="error">*</SPAN><?php }; ?>
			</TD>
		</TR>
		<TR>
			<TD COLSPAN=2 ALIGN="center">
				<INPUT type="checkbox" name="SHOWEMAIL" value="1" <?php if(isset($SHOWEMAIL) && $SHOWEMAIL) echo("checked"); ?><?php if ($done) echo(" READONLY"); ?>>
				&nbsp;<?php echo(L_REG_33); ?>
			</TD>
		</TR>
		</TABLE>
		<P>
		<?php
		if (!$done)
		{
			?>
			<INPUT TYPE="submit" NAME="submit_type" VALUE="<?php echo(L_REG_16); ?>">
			<?php
		}
		?>
		<INPUT TYPE="submit" NAME="submit_type" VALUE="<?php echo(L_REG_25); ?>" onClick="if (window.opener && !window.opener.closed) put_focus(); self.close(); return false;">
	</TD>
</TR>
</TABLE>
</FORM>
</CENTER>
</BODY>

</HTML>
<?php

?>