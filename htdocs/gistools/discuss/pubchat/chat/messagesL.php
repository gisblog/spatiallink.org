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
require("./lib/database/".C_DB_TYPE.".lib.php");
require("./lib/clean.lib.php");

// Special cache instructions for IE5+
$CachePlus	= "";
if (ereg("MSIE [56789]", (isset($HTTP_USER_AGENT)) ? $HTTP_USER_AGENT : getenv("HTTP_USER_AGENT"))) $CachePlus = ", pre-check=0, post-check=0, max-age=0";
$now		= gmdate('D, d M Y H:i:s') . ' GMT';

header("Expires: $now");
header("Last-Modified: $now");
header("Cache-Control: no-cache, must-revalidate".$CachePlus);
header("Pragma: no-cache");

header("Content-Type: text/html; charset=${Charset}");

// Avoid server configuration for magic quotes
set_magic_quotes_runtime(0);


// Translate to html special characters, and entities if message was sent with a latin 1 charset
$Latin1 = ($Charset == "iso-8859-1");
function special_char($str,$lang,$slash_on)
{
	$str = ($lang ? htmlentities(stripslashes($str)) : htmlspecialchars(stripslashes($str)));
	return ($slash_on ? addslashes($str) : $str);
};


$DbLink = new DB;


// ** Updates user info in connected users tables **
$DbLink->query("SELECT room,status FROM ".C_USR_TBL." WHERE username = '$U' LIMIT 1");
if($DbLink->num_rows() != 0)
{
	// There is a row for the user in the users table
	list($room,$status) = $DbLink->next_record();
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
	};
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


// Text direction
$textDirection = ($Charset == "windows-1256") ? "RTL" : "LTR";

// For translations with an explicit charset (not the 'x-user-defined' one)
if (!isset($FontName)) $FontName = "";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML dir="<?php echo($textDirection); ?>">

<HEAD>
<TITLE>Messages frame</TITLE>
<?php
if ($D > 0)
	echo('<meta HTTP-EQUIV="Refresh" CONTENT="' . $D . '; URL=messagesL.php?' . ((isset($QUERY_STRING)) ? $QUERY_STRING : getenv('QUERY_STRING')) . '">');
?>
<LINK REL="stylesheet" HREF="config/style.css.php?<?php echo("Charset=${Charset}&medium=${FontSize}&FontName=${FontName}"); ?>" TYPE="text/css">
</HEAD>

<BODY CLASS="mainframe" <?php if($O == 1) echo("onLoad=\"this.scrollTo(0,65000);\""); ?>>
<?php

// ** Get messages **

// Define the SQL query (depends on values for ignored users list and on whether to display
// notification messages or not)

$CondForQuery = "";
$IgnoreList = "";
if (isset($Ign)) $IgnoreList = "'".str_replace(",","','",addslashes(urldecode($Ign)))."'";
if ($NT == "0") $IgnoreList .= ($IgnoreList != "" ? ",":"")."'SYS enter','SYS exit'";
if ($IgnoreList != "") $CondForQuery = "username NOT IN (${IgnoreList}) AND ";
$CondForQuery .= "(address = ' *' OR (address = '$U' AND (room = '$R' OR username = 'SYS inviteTo')) OR (room = '$R' AND (address = '' OR username = '$U')))";

$DbLink->query("SELECT m_time, username, latin1, address, message FROM ".C_MSG_TBL." WHERE ".$CondForQuery." ORDER BY m_time DESC LIMIT $N");

// Format and display new messages
if($DbLink->num_rows() > 0)
{
	$i = "1";
	$today = date('j', time() +  C_TMZ_OFFSET*60*60);
	$MessagesString = "";
	while(list($Time, $User, $Latin1, $Dest, $Message) = $DbLink->next_record())
	{
		// Skip the oldest message if the day seperator has been added
		if (isset($day_separator) && $i == $N) continue;

		$NewMsg = "<P CLASS=\"msg\">";
		if ($ST == 1) $NewMsg .= "<SPAN CLASS=\"time\">".date("H:i:s", $Time +  C_TMZ_OFFSET*60*60)."</SPAN> ";

		// "Standard" messages
		if (substr($User,0,4) != "SYS ")
		{
			$User = "<A HREF=\"#\" onClick=\"window.parent.userClick('".special_char($User,$Latin1,1)."',true); return false\" CLASS=\"sender\">".special_char($User,$Latin1,0)."</A>";
			if ($Dest != "") $Dest = "]<BDO dir=\"${textDirection}\"></BDO>>[".htmlspecialchars(stripslashes($Dest));
			$NewMsg .= "<B>[${User}${Dest}]<BDO dir=\"${textDirection}\"></BDO></B> $Message</P>";
		}
		// "System" messages
		else
		{
			if ($Dest == " *")
			{
				$Message = "[".L_ANNOUNCE."]<BDO dir=\"${textDirection}\"></BDO> ".$Message;
			}
			else
			{
				if ($Dest != "") $NewMsg .= "<B><BDO dir=\"${textDirection}\"></BDO>>[".htmlspecialchars(stripslashes($Dest))."]<BDO dir=\"${textDirection}\"></BDO></B> ";
				$Message = str_replace("$","\\$",$Message);	// avoid '$' chars in nick to be parsed bellow
				eval("\$Message = $Message;");
			};
			$NewMsg .= "<SPAN CLASS=\"notify\">".$Message."</SPAN></P>";
		};

		// Separator between messages sent before today and other ones
		if (!isset($day_separator) && date("j", $Time +  C_TMZ_OFFSET*60*60) != $today)
		{
			$day_separator = "<P CLASS=\"msg\"><SPAN CLASS=\"notify\">--------- ".($O == 0 ? L_TODAY_UP : L_TODAY_DWN)." ---------</SPAN></P>";
		};

		if($O == 0) {
			$MessagesString .= ((isset($day_separator) && $day_separator != "") ? $day_separator."\n" : "").$NewMsg."\n";
		} else {
			$MessagesString = $NewMsg.((isset($day_separator) && $day_separator != "") ? "\n".$day_separator : "")."\n".$MessagesString;
		};
		
		if (isset($day_separator)) $day_separator = "";		// Today separator already printed
		$i++;
	};
	echo($MessagesString);
}
else
{
	echo("<SPAN CLASS=\"notify\">".L_NO_MSG."</SPAN>");
};


$DbLink->clean_results();
$DbLink->close();
?>
</BODY>

</HTML>