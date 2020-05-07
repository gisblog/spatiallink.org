<?php
// This is an example of what may be done to include phpMyChat into an
// existing web page, regardless of its name.
// You can also include such a file in a frameset.

// Lines below must be at the top of your file because 'index.lib.php'
// sets headers and cookies.
$ChatPath = "";		// relative path to chat dir, empty value if this
					// file is in the same dir than the chat;
require("./${ChatPath}lib/index.lib.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML dir="<?php echo(($Charset == "windows-1256") ? "RTL" : "LTR"); ?>">

<HEAD>
<?php
// You can put html head statements right after the "<HEAD>" tag.

// Both values are boolean. See explanations in 'index.lib.php' file.
send_headers(1,1);
?>
</HEAD>

<BODY CLASS="ChatBody">
<?php
// If nothing other than phpMyChat is loaded in this page, or if you want
// to have the same background color as phpMyChat for the whole page,
// you have to modify the BODY tag to '<BODY CLASS="ChatBody">'
// You can put html statements right after the "<BODY>" tag or add
// php code here.

$Is_Error = (isset($Error));

if (isset($HTTP_COOKIE_VARS))
{
	if (isset($HTTP_COOKIE_VARS["CookieUsername"])) $CookieUsername = $HTTP_COOKIE_VARS["CookieUsername"];
	if (isset($HTTP_COOKIE_VARS["CookieRoom"])) $CookieRoom = $HTTP_COOKIE_VARS["CookieRoom"];
	if (isset($HTTP_COOKIE_VARS["CookieRoomType"])) $CookieRoomType = $HTTP_COOKIE_VARS["CookieRoomType"];
};
$Username = (isset($CookieUsername) ? $CookieUsername : "");
$Room_name = (isset($CookieRoom) ? $CookieRoom : "");
$Room_type = (isset($CookieRoomType) ? $CookieRoomType : "");

layout($Is_Error,$Username,$Room_name,$Room_type);

// You can add php code here, or add html statements before the "</BODY>" tag.
?>
</BODY>

</HTML>
<?php
// The following line is required
$DbLink->close();
?>