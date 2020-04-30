<?php
// Get the names and values for vars sent to this script
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
require("./lib/database/".C_DB_TYPE.".lib.php");
require("./lib/clean.lib.php");

header("Content-Type: text/html; charset=${Charset}");

// avoid server configuration for magic quotes
set_magic_quotes_runtime(0);

// Translate to html special characters, and entities if message was sent with a latin 1 charset
$Latin1 = ($Charset == "iso-8859-1");
function special_char($str,$lang,$slash_on)
{
	$str = ($lang ? htmlentities(stripslashes($str)) : htmlspecialchars(stripslashes($str)));
	return ($slash_on ? addslashes($str) : $str);
};

// Text direction
$textDirection = ($Charset == "windows-1256") ? "RTL" : "LTR";

$DbLink = new DB;


// ** Updates user info in connected users tables **;
$DbLink->query("SELECT status,room FROM ".C_USR_TBL." WHERE username = '$U' LIMIT 1");
if($DbLink->num_rows() != 0)
{
	// There is a row for the user in the users table
	list($status,$room) = $DbLink->next_record();
	$DbLink->clean_results();
	$kicked = 0;
	if ($room != stripslashes($R))	// Same nick in another room
	{
		$DbLink->query("INSERT INTO ".C_MSG_TBL." VALUES ($T, '$R', 'SYS exit', '', ".time().", '', 'sprintf(L_EXIT_ROM, \"".special_char($U,$Latin1,1)."\")')");
		$kicked = 3;
	}
	elseif ($status == "k")			// Kicked by a moderator or the admin.
	{
		$DbLink->query("INSERT INTO ".C_MSG_TBL." VALUES ($T, '$R', 'SYS exit', '', ".time().", '', 'sprintf(L_KICKED, \"".special_char($U,$Latin1,1)."\")')");
		$kicked = 1;
	}
	elseif ($status == "d")			// The admin just deleted the room
	{
		$kicked = 2;
	}
	elseif ($status == "b")			// Banished by a moderator or the admin.
	{
		$DbLink->query("INSERT INTO ".C_MSG_TBL." VALUES ($T, '$R', 'SYS exit', '', ".time().", '', 'sprintf(L_BANISHED, \"".special_char($U,$Latin1,1)."\")')");
		$kicked = 4;
	};
	if ($kicked > 0)
	{
		// Kick the user from the current room
		?>
		<SCRIPT TYPE="text/javascript" LANGUAGE="JavaScript">
		<!--
		window.parent.window.location = '<?php echo("$From?L=$L&U=".urlencode(stripslashes($U))."&E=$R&KICKED=${kicked}"); ?>';
		// -->
		</SCRIPT>
		<?php
		$DbLink->close();
		exit;
	}
	// Updates the time to ensure the user won't be cleaned from the users table
	$DbLink->query("UPDATE ".C_USR_TBL." SET u_time = ".time()." WHERE room = '$R' AND username = '$U'");
}
else
{
	// User hasn't been found in the users table -> add a row
	$DbLink->clean_results();
	$DbLink->query("SELECT perms,rooms FROM ".C_REG_TBL." WHERE username='$U' LIMIT 1");
	$reguser = ($DbLink->num_rows() != 0);
	if ($reguser) list($perms, $rooms) = $DbLink->next_record();
	$DbLink->clean_results();
	// Get user status
	$status = "u";
	if ($reguser)
	{
		switch ($perms)
		{
			case 'admin':
				$status = "a";
				break;
			case 'moderator':
				$roomsTab = explode(",",$rooms);
				for (reset($roomsTab); $room_name=current($roomsTab); next($roomsTab))
				{
					if (strcasecmp(stripslashes($R), $room_name) == 0) 
					{
						$status = "m";
						break;
					};
				};
			default:
				$status = "r";
		};
	};
	// Get IP address
	include("./lib/get_IP.lib.php");		// Set the $IP var
	$DbLink->query("INSERT INTO ".C_USR_TBL." VALUES ('$R', '$U', '$Latin1', ".time().", '$status', '$IP')");
};


// ** Check for updates in users list **
if ($First) $LastCheck = 0;
if ($CleanUsrTbl)
{
	$Users_Refresh = "1";
}
else
{
	$QueryRoom = " AND (type = 1".($T == "0" ? " OR (type = 0 AND room = '$R')" : "").") ";
	$DbLink->query("SELECT DISTINCT m_time FROM ".C_MSG_TBL." WHERE m_time > '$LastCheck' AND username IN ('SYS enter','SYS exit','SYS promote','SYS delreg')".$QueryRoom."ORDER BY m_time DESC LIMIT 1");
	$Users_Refresh = ($DbLink->num_rows() > 0);
	if ($Users_Refresh) list($LastCheck) = $DbLink->next_record();
	$DbLink->clean_results();
};

		 
// ** Check for updates in messages list and get new messages **
if ($First) $LastLoad = 0;

// Define the SQL query (depends on values for ignored users list and on whether to display
// notification messages or not
$CondForQuery		 = "";
$IgnoreList			 = "";
if (isset($Ign))
	$IgnoreList		 = "'".str_replace(",","','",addslashes(urldecode($Ign)))."'";
if ($NT == "0")
	$IgnoreList		.= ($IgnoreList != "" ? ",":"")."'SYS enter','SYS exit'";
if ($IgnoreList != "")
	$CondForQuery	 = "username NOT IN (${IgnoreList}) AND ";
$CondForQuery		.= "(address = ' *' OR (address = '$U' AND (room = '$R' OR username = 'SYS inviteTo')) OR (room = '$R' AND (address = '' OR username = '$U')))";
$LimitForQuery		 = ($First ? " LIMIT $N" : "");

$DbLink->query("SELECT m_time, username, latin1, address, message FROM ".C_MSG_TBL." WHERE m_time > '$LastLoad' AND ".$CondForQuery." ORDER BY m_time DESC".$LimitForQuery);

// Format and store new messages
$Messages = Array();
if($DbLink->num_rows() > 0)
{
	$i = "1";
	$today = date('j', time() +  C_TMZ_OFFSET*60*60);
	while(list($Time, $User, $Latin1, $Dest, $Message) = $DbLink->next_record())
	{
		// Skip the oldest message if the day seperator has been added
		if (isset($day_separator) && $First && $i == $N) continue;

		// Separator between messages sent before today and other ones
		if (!isset($day_separator) && date("j", $Time +  C_TMZ_OFFSET*60*60) != $today)
		{
			$Messages[] = "<P CLASS=\"msg\"><SPAN CLASS=\"notify\">--------- ".L_TODAY_DWN." ---------<\/SPAN><\/P>";
			$day_separator = "1";
		};

		$NewMsg = "<P CLASS=\"msg\">";
		if ($ST == 1) $NewMsg .= "<SPAN CLASS=\"time\">".date("H:i:s", $Time +  C_TMZ_OFFSET*60*60)."<\/SPAN> ";

		// "Standard" messages
		if (substr($User,0,4) != "SYS ")
		{
			$User = "<A HREF=\"#\" onClick=\"window.parent.userClick('".special_char($User,$Latin1,1)."',true); return false\" CLASS=\"sender\">".special_char($User,$Latin1,0)."<\/A>";
			if ($Dest != "") $Dest = "]<BDO dir=\"${textDirection}\"><\/BDO>>[".htmlspecialchars(stripslashes($Dest));
			$Message = str_replace("</FONT>","<\\/FONT>",$Message);	// slashes the closing HTML font tag
			$NewMsg .= "<B>[${User}${Dest}]<BDO dir=\"${textDirection}\"><\/BDO><\/B> $Message<\/P>";
		}
		// "System" messages
		else
		{
			if ($Dest == " *")
			{
				$Message = "[".L_ANNOUNCE."]<BDO dir=\"${textDirection}\"><\/BDO> ".$Message;
			}
			else
			{
				if ($Dest != "") $NewMsg .= "<B>>[".htmlspecialchars(stripslashes($Dest))."]<BDO dir=\"${textDirection}\"><\/BDO><\/B> ";
				$Message = str_replace("$","\\$",$Message);	// avoid '$' chars in nick to be parsed below
				eval("\$Message = $Message;");
			};
			$NewMsg .= "<SPAN CLASS=\"notify\">".$Message."<\/SPAN><\/P>";
		};
		$Messages[] = $NewMsg;
		if ($Time > $LastLoad) $LastLoad = $Time;
		$i++;
	};
}
else
{
	if ($First) $Messages[] = "<SPAN CLASS=\"notify\">".L_NO_MSG."<\/SPAN>";
};

$DbLink->clean_results();
$DbLink->close();


// ** Define the URL query part of the http refresh header **
if ($First)
{
	$Refresh = str_replace("First=1","First=0", (isset($QUERY_STRING)) ? $QUERY_STRING : getenv("QUERY_STRING"))."&LastLoad=${LastLoad}&LastCheck=${LastCheck}";
}
else
{
	$Refresh = ereg_replace("&LastLoad=([0-9]+)&LastCheck=([0-9]+)","&LastLoad=".$LastLoad."&LastCheck=".$LastCheck, (isset($QUERY_STRING)) ? $QUERY_STRING : getenv("QUERY_STRING"));
};


// Special cache instructions for IE5+
$CachePlus	= "";
if (ereg("MSIE [56789]", (isset($HTTP_USER_AGENT)) ? $HTTP_USER_AGENT : getenv("HTTP_USER_AGENT"))) $CachePlus = ", pre-check=0, post-check=0, max-age=0";
$now		= gmdate('D, d M Y H:i:s') . ' GMT';

header("Expires: $now");
header("Last-Modified: $now");
header("Cache-Control: no-cache, must-revalidate".$CachePlus);
header("Pragma: no-cache");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML dir="<?php echo($textDirection); ?>">
<HEAD>
<TITLE>Loader hidden frame</TITLE>
<?php
if ($D > 0)
	echo('<meta HTTP-EQUIV="Refresh" CONTENT="' . $D . '; URL=loader.php?' . $Refresh . '">' . "\n");
?>
<SCRIPT TYPE="text/javascript" LANGUAGE="JavaScript">
<!--
// Update the time values for last check and last loaded message in the main page
window.parent.time4LastLoadedMsg = "<?php echo($LastLoad); ?>";
window.parent.time4LastCheckedUser = "<?php echo($LastCheck); ?>";

<?php
// ** Refresh users frame if necessary **
if ($First || $Users_Refresh)
{
	?>
	window.parent.frames['users'].window.location.replace("usersH.php?<?php echo( (isset($QUERY_STRING)) ? $QUERY_STRING : getenv("QUERY_STRING")); ?>");
	<?php
};


// ** Refresh messages frame **

// Set the stylesheet for the messages frame at first load
if ($First)
{
	// For translations with an explicit charset (not the 'x-user-defined' one)
	if (!isset($FontName)) $FontName = "";
	?>
	with (window.parent.frames['messages'].window.document)
	{
		open("text/html", "replace");
		write("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">");
		write("<HTML dir=\"<?php echo($textDirection); ?>\">\n<HEAD>\n");
		write("<TITLE>Dynamic messages frame<\/TITLE>\n");
 		write("<LINK REL=\"stylesheet\" HREF=\"config/style.css.php?<?php echo("Charset=${Charset}&medium=${FontSize}&FontName=${FontName}"); ?>\" TYPE=\"text/css\">\n");
		write("<\/HEAD>\n\n");
		write("<BODY CLASS=\"mainframe\">\n");
	};
	<?php
};

// Send new message(s) if there is at least one, else an empty string to ensure the message
// frame isn't closed because of too long inactive delay
$message_nb = count($Messages);
for ($i = 0; $i < $message_nb; $i++)
{
	// doubles backslashes except the ones for closing HTML tags
	$ToSend = ereg_replace("([^<]+)[\]","\\1\\\\",$Messages[$message_nb-1-$i]);
	// slashes the quotes that should be displayed
	$ToSend = str_replace("\"","\\\"",$ToSend);
	?>
	window.parent.frames['messages'].window.document.write("<?php echo($ToSend); ?>\n");
	<?php
};
if ($message_nb < 1)
{
	?>
	window.parent.frames['messages'].window.document.write("");
	<?php
}
else
{
	?>
	// Scrolls to the bottom of the message frame
	with (window.parent.frames['messages'].window)
	{
		if (typeof(scrollBy) != 'undefined')
		{
			scrollBy(0, 65000);
			scrollBy(0, 65000);
		}
		else if (typeof(scroll) != 'undefined')
		{
			scroll(0, 65000);
			scroll(0, 65000);
		};
	}
	<?php
};
?>
// -->
</SCRIPT>
</HEAD>
<BODY onUnload="if (typeof(window.parent.frames['exit']) != 'undefined' && typeof(window.parent.leaveChat) != 'undefined' && !window.parent.leaveChat) window.parent.Connecting(window.parent.connect + 1);">
<SCRIPT TYPE="text/javascript" LANGUAGE="javascript1.1">
<!--
if (typeof(window.parent.frames['exit']) != 'undefined'
	&& typeof(window.parent.ConnectDone) != 'undefined')
	window.parent.ConnectDone();
// -->
</SCRIPT>
</BODY>
</HTML>