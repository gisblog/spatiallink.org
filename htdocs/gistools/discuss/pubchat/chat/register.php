<?php
// Credit for the generate and e-mail password stuff goes to
// Jose' Carlos Pereira <phpHeaven@abismo.org>

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

// Fix a security hole
if (isset($L) && !is_dir('./localization/'.$L)) exit();
	
require("./config/config.lib.php");
require("./localization/languages.lib.php");
require("./localization/".$L."/localized.chat.php");
require("./lib/release.lib.php");
require("./lib/database/".C_DB_TYPE.".lib.php");

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

// Check for valid entries
if (isset($FORM_SEND) && stripslashes($submit_type) == L_REG_3)
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
	else if (!C_EMAIL_PASWD && $PASSWORD == "")
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
	else if (C_EMAIL_PASWD && !checkdnsrr(substr(strstr($EMAIL,'@'),1),"ANY"))
	{
		$Error = L_ERR_USR_8;
	}
	else
	{
		$DbLink->query("SELECT count(*) FROM ".C_REG_TBL." WHERE username='$U'");
		list($rows) = $DbLink->next_record();
		$DbLink->clean_results();
		if ($rows != 0)
		{
			$Error = L_ERR_USR_9;
		}
		else
		{
			$Latin1 = ($Charset == "iso-8859-1");
			if (!isset($GENDER)) $GENDER = "";
			$showemail = (isset($SHOWEMAIL) && $SHOWEMAIL)? 1:0;
			include("./lib/get_IP.lib.php");		// Set the $IP var

			if (C_EMAIL_PASWD)		// Define the password
			{
				include("./lib/mail_validation.lib.php");
				$PASSWORD = gen_password(C_EMAIL_PASWD);
			};
			$PWD_Hash = md5(stripslashes($PASSWORD));
			// Send e-mail
			if (C_EMAIL_PASWD)
			{
				$send = send_email("[".APP_NAME."] ".L_EMAIL_VAL_1, L_SET_2, L_REG_7, L_EMAIL_VAL_2);
				if (!$send) $Error = sprintf(L_EMAIL_VAL_Err,$Sender_email,$Sender_email);
			};
			if (!isset($Error) || $Error == "")
			{			
				$DbLink->query("INSERT INTO ".C_REG_TBL." VALUES ('$U', '$Latin1', '$PWD_Hash', '$FIRSTNAME', '$LASTNAME', '$COUNTRY', '$WEBSITE', '$EMAIL', $showemail, 'user', '',".time().", '$IP', '$GENDER')");
				$Message = L_REG_9;
			};
		}
	}
}

$DbLink->close();

// Registration has been done ?
$done = (isset($Message) && $Message == L_REG_9);

// For translations with an explicit charset (not the 'x-user-defined' one)
if (!isset($FontName)) $FontName = "";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML dir="<?php echo(($Charset == "windows-1256") ? "RTL" : "LTR"); ?>">

<HEAD>
<TITLE><?php echo(APP_NAME); ?></TITLE>
<LINK REL="stylesheet" HREF="config/style.css.php?<?php echo("Charset=${Charset}&medium=${FontSize}&FontName=${FontName}"); ?>" TYPE="text/css">
<SCRIPT TYPE="text/javascript" LANGUAGE="javascript">
<!--
// Insert login and password in fields of the starter page form
function LoginToIndex()
{
<?php
if ($done)
{
	?>
	var indexform = window.opener.window.document.forms['Params'];
	var regform = document.forms['RegParams'];
	indexform.elements['U'].value = regform.elements['U'].value;
	<?php
	if (!C_EMAIL_PASWD)
	{
		?>
		indexform.elements['PASSWORD'].value = regform.elements['PASSWORD'].value;
		<?php
	};
};
?>
};

// Put focus to the username field of the form at the starter page
function get_focus()
{
	window.focus();
	document.forms['RegParams'].elements['U'].focus();
}
// -->
</SCRIPT>
</HEAD>

<BODY onLoad="if (window.focus) get_focus();">
<CENTER>
<BR>
<FORM ACTION="register.php" METHOD="POST" AUTOCOMPLETE="OFF" NAME="RegParams">
<P></P>
<?php
if(isset($Error))
{
	echo("<P><SPAN CLASS=\"error\">$Error</SPAN></P>");
}
?>
<INPUT TYPE="hidden" NAME="FORM_SEND" VALUE="1">
<INPUT TYPE="hidden" NAME="L" VALUE="<?php echo($L); ?>">
<TABLE BORDER=0 CELLPADDING=3 CLASS="table">
<TR>
	<TD ALIGN="CENTER">
		<TABLE BORDER=0>
		<TR>
			<TH COLSPAN=2 CLASS="tabtitle"><?php echo($done ? $Message : L_REG_6); ?></TH>
		</TR>
		<TR>
			<TH COLSPAN=2><?php if (!$done) echo(L_REG_37); elseif (C_EMAIL_PASWD) echo(L_EMAIL_VAL_Done); ?></TH>
		</TR>
		<TR><TD>&nbsp;</TD></TR>
		<TR>
			<TD ALIGN="RIGHT" VALIGN="TOP" NOWRAP><?php echo(L_SET_2); ?> :</TD>
			<TD VALIGN="TOP">
				<INPUT TYPE="text" NAME="U" SIZE=11 MAXLENGTH=10 VALUE="<?php if (isset($U)) echo(htmlspecialchars(stripslashes($U))); ?>"<?php if ($done) echo(" READONLY"); ?>>
				<?php if (!$done) { ?><SPAN CLASS=error>*</SPAN><?php }; ?>
			</TD>
		</TR>
		<?php
		if (!C_EMAIL_PASWD)
		{
			?>
			<TR>
				<TD ALIGN="RIGHT" VALIGN="TOP" NOWRAP><?php echo(L_REG_7); ?> :</TD>
				<TD VALIGN="TOP">
					<INPUT TYPE="password" NAME="PASSWORD" SIZE=11 MAXLENGTH=16 VALUE="<?php if (isset($PASSWORD)) echo(htmlspecialchars(stripslashes($PASSWORD))); ?>"<?php if ($done) echo(" READONLY"); ?>>
					<?php if (!$done) { ?><SPAN CLASS=error>*</SPAN><?php }; ?>
				</TD>
			</TR>
			<?php
		};
		?>
		<TR>
			<TD ALIGN="RIGHT" VALIGN="TOP" NOWRAP><?php echo(L_REG_30); ?> :</TD>
			<TD VALIGN="TOP">
				<INPUT TYPE="text" NAME="FIRSTNAME" SIZE=11 MAXLENGTH=64 VALUE="<?php if (isset($FIRSTNAME)) echo(htmlspecialchars(stripslashes($FIRSTNAME))); ?>"<?php if ($done) echo(" READONLY"); ?>>
				<?php if (!$done) { ?><SPAN CLASS=error>*</SPAN><?php }; ?>
			</TD>
		</TR>
		<TR>
			<TD ALIGN="RIGHT" VALIGN="TOP" NOWRAP><?php echo(L_REG_31); ?> :</TD>
			<TD VALIGN="TOP">
				<INPUT TYPE="text" NAME="LASTNAME" SIZE=11 MAXLENGTH=64 VALUE="<?php if (isset($LASTNAME)) echo(htmlspecialchars(stripslashes($LASTNAME))); ?>"<?php if ($done) echo(" READONLY"); ?>>
				<?php if (!$done) { ?><SPAN CLASS=error>*</SPAN><?php }; ?>
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
				<INPUT TYPE="text" NAME="COUNTRY" SIZE=11 MAXLENGTH=64 VALUE="<?php if (isset($COUNTRY)) echo(htmlspecialchars(stripslashes($COUNTRY))); ?>"<?php if ($done) echo(" READONLY"); ?>>
			</TD>
		</TR>
		<TR>
			<TD ALIGN="RIGHT" VALIGN="TOP" NOWRAP><?php echo(L_REG_32); ?> :</TD>
			<TD VALIGN="TOP">
				<INPUT TYPE="text" NAME="WEBSITE" SIZE=11 MAXLENGTH=64 VALUE="<?php if (isset($WEBSITE)) echo(htmlspecialchars(stripslashes($WEBSITE))); ?>"<?php if ($done) echo(" READONLY"); ?>>
			</TD>
		</TR>
		<TR>
			<TD ALIGN="RIGHT" VALIGN="TOP" NOWRAP><?php echo(L_REG_8); ?> :</TD>
			<TD VALIGN="TOP">
				<INPUT TYPE="text" NAME="EMAIL" SIZE=11 MAXLENGTH=64 VALUE="<?php if (isset($EMAIL)) echo(htmlspecialchars(stripslashes($EMAIL))); ?>"<?php if ($done) echo(" READONLY"); ?>>
				<?php if (!$done) { ?><SPAN CLASS="error">*</SPAN><?php }; ?>
			</TD>
		</TR>
		<TR>
			<TD COLSPAN=2 ALIGN="center">
				<INPUT type="checkbox" name="SHOWEMAIL" value="1" <?php if (isset($SHOWEMAIL) && $SHOWEMAIL) echo("CHECKED"); ?><?php if ($done) echo(" READONLY"); ?>>
				&nbsp;<?php echo(L_REG_33); ?>
			</TD>
		</TR>
		</TABLE>
		<P>
		<?php
		if (!$done)
		{
			?>
			<INPUT TYPE="submit" NAME="submit_type" VALUE="<?php echo(L_REG_3); ?>">
			<?php
		}
		?>
		<INPUT TYPE="submit" NAME="submit_type" VALUE="<?php echo(L_REG_25); ?>" onClick="LoginToIndex(); self.close(); return false;">
	</TD>
</TR>
</TABLE>
</FORM>
</CENTER>
</BODY>

</HTML>
<?php

?>