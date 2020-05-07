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
require("./lib/release.lib.php");
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
header("Content-Type: text/html; charset=".$Charset);

// Text direction
$textDirection = ($Charset == "windows-1256") ? "RTL" : "LTR";

// For translations with an explicit charset (not the 'x-user-defined' one)
if (!isset($FontName)) $FontName = "";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML dir="<?php echo($textDirection); ?>">

<HEAD>
<TITLE><?php echo(APP_NAME); ?></TITLE>
<LINK REL="stylesheet" HREF="config/style.css.php?<?php echo("Charset=${Charset}&medium=${FontSize}&FontName=${FontName}"); ?>" TYPE="text/css">
<SCRIPT TYPE="text/javascript" LANGUAGE="javascript1.2">
<!--
// Add styles for positioned layers;
if (window.parent.ver4)
{
	with (document) {
		write("<STYLE TYPE=\"text/css\">");
		if (parent.NS4) {
			write(".parent {position:absolute; visibility:visible}");
			write(".child {position:absolute; visibility:visible}");
			write(".regular {position:absolute; visibility:visible}")
		} else {
			write(".child {display:none}")
		};
		write("<\/STYLE>");
	}
}

// Get the Y scrolling position of the users frame;
function GetY()
{
	window.parent.Y = (window.parent.NS4 ? window.pageYOffset : document.body.scrollTop);
};

// Initialize the collapsible outline;
onload = window.parent.initIt;
// -->
</SCRIPT>
</HEAD>

<BODY CLASS="frame" onUnload="GetY();">
<P>
<?php
// Special formats depending on users status
function special_char($str,$lang,$type)
{
	$tag_open = (($type == 'a' || $type == 'm') ? "<I>":"");
	$tag_close = ($tag_open != "" ? "</I>":"");
	return $tag_open.($lang ? htmlentities($str) : htmlspecialchars($str)).$tag_close;
}

// Used inside javascript links 
function special_char2($str,$lang)
{
	return ($lang ? htmlentities(addslashes($str)) : htmlspecialchars(addslashes($str)));
}

$DbLink = new DB;

//** Build users list for the current room **
if (C_DB_TYPE == 'mysql')
{
	$currentRoomQuery	= 'SELECT usr.username, usr.latin1, usr.status, reg.gender '
						. 'FROM ' . C_USR_TBL . ' usr LEFT JOIN ' . C_REG_TBL . ' reg ON usr.username = reg.username '
						. 'WHERE usr.room = \'' . $R . '\' '
						. 'ORDER BY username';
}
else if (C_DB_TYPE == 'pgsql')
{
	$currentRoomQuery	= 'SELECT usr.username, usr.latin1, usr.status, reg.gender '
						. 'FROM ' . C_USR_TBL . ' usr, ' . C_REG_TBL . ' reg '
						. 'WHERE usr.room = \'' . $R . '\' AND usr.username = reg.username '
						. 'UNION '
						. 'SELECT usr.username, usr.latin1, usr.status, NULL AS gender '
						. 'FROM ' . C_USR_TBL . ' usr '
						. 'WHERE usr.username NOT IN (SELECT reg.username FROM ' . C_REG_TBL . ' reg) AND usr.room = \'' . $R . '\' '
						. 'ORDER BY username';
}
else
{
	$currentRoomQuery	= 'SELECT usr.username, usr.latin1, usr.status, NULL AS gender '
						. 'FROM ' . C_USR_TBL . ' usr '
						. 'WHERE usr.room = \'' . $R . '\' '
						. 'ORDER BY username';
}

$DbLink->query($currentRoomQuery);
echo("<B>".htmlspecialchars(stripslashes($R))."</B><SPAN CLASS=\"small\"><BDO dir=\"${textDirection}\"></BDO>&nbsp;(".$DbLink->num_rows().")</SPAN><BR>\n");
echo("<DIV STYLE=\"margin-bottom: 10px\">\n");
while(list($User, $Latin1, $status, $gender) = $DbLink->next_record())
{
	// Put an icon when there is a profile for the user
	if($gender == 0)
		$gender = 'undefined';
	elseif($gender == 1)
		$gender = 'boy';
	elseif($gender == 2)
		$gender = 'girl';
	else
		$gender = 'none';
	if ($status != "u" && $status != "k" && $status != "d" && $status != "b")
	{
		$Cmd2Send = ($User == stripslashes($U) ? "'PROFILE',''" : "'WHOIS','".special_char2($User,$Latin1)."'");
		echo('<a href="#" onClick="window.parent.runCmd('.$Cmd2Send.'); return false;" class="user"><img src="images/gender_'.$gender.'.gif" width="14" height="14" border="0" alt="'.L_PROFILE.'"></a>&nbsp;');
	}
	else
	{
		echo('<img src="images/gender_none.gif" width="14" height="14" border="0" alt="' . L_NO_PROFILE . '">&nbsp;');
	}
	if($User != stripslashes($U))
	{
		echo("<A HREF=\"javascript:window.parent.userClick('".special_char2($User,$Latin1)."',false);\" CLASS=\"user\">".special_char($User,$Latin1,$status)."</A><BR>\n");
	}
	else
	{
		echo(special_char($User,$Latin1,$status)."<BR>\n");
	}
}
echo("</DIV>\n");
$DbLink->clean_results();

//** Build users list for other rooms **
$AddPwd2Link = (isset($PWD_Hash) && $PWD_Hash != "") ? "&PWD_Hash=$PWD_Hash" : "";
$DbLink->query("SELECT DISTINCT room FROM ".C_MSG_TBL." WHERE room != '$R' AND type = 1 ORDER BY room");
if($DbLink->num_rows() > 0)
{
	$i = 0;
	$ChildNb = Array();
	$OthersUsers = new DB;
	while(list($Other) = $DbLink->next_record())
	{
		if (C_DB_TYPE == 'mysql')
		{
			$otherRoomsQuery	= 'SELECT usr.username, usr.latin1, usr.status, reg.gender '
								. 'FROM ' . C_USR_TBL . ' usr LEFT JOIN ' . C_REG_TBL . ' reg ON usr.username = reg.username '
								. 'WHERE usr.room = \'' . addslashes($Other) . '\' '
								. 'ORDER BY username';
		}
		else if (C_DB_TYPE == 'pgsql')
		{
			$otherRoomsQuery	= 'SELECT usr.username, usr.latin1, usr.status, reg.gender '
								. 'FROM ' . C_USR_TBL . ' usr, ' . C_REG_TBL . ' reg '
								. 'WHERE usr.room = \'' . addslashes($Other) . '\' AND usr.username = reg.username '
								. 'UNION '
								. 'SELECT usr.username, usr.latin1, usr.status, NULL AS gender '
								. 'FROM ' . C_USR_TBL . ' usr '
								. 'WHERE usr.username NOT IN (SELECT reg.username FROM ' . C_REG_TBL . ' reg) AND usr.room = \'' . addslashes($Other) . '\' '
								. 'ORDER BY username';
		}
		else
		{
			$otherRoomsQuery	= 'SELECT usr.username, usr.latin1, usr.status, NULL AS gender '
								. 'FROM ' . C_USR_TBL . ' usr '
								. 'WHERE usr.room = \'' . addslashes($Other) . '\' '
								. 'ORDER BY username';
		}

		$OthersUsers->query($otherRoomsQuery);
		if($OthersUsers->num_rows() > 0)
		{
			$i++;
			$id = md5($Other);
			if ($i == 1) $FirstOtherRoom = "Parent".$id;
			echo("<DIV ID=\"Parent${id}\" CLASS=\"parent\" STYLE=\"margin-top: 10px; CURSOR: hand\">");
			echo("<A HREF=\"#\" onClick=\"window.parent.expandIt('${id}'); return false\">");
			echo("<IMG NAME=\"imEx\" SRC=\"images/closed.gif\" WIDTH=9 HEIGHT=9 BORDER=0 ALT=\"".L_EXPCOL."\"></A>");
			echo("&nbsp;<A HREF=\"$From?Ver=H&L=$L&U=".urlencode(stripslashes($U))."$AddPwd2Link&R1=".urlencode($Other)."&T=1&D=$D&N=$N&E=".urlencode(stripslashes($R))."&EN=$T\" TARGET=\"_parent\">".htmlspecialchars($Other)."</A><SPAN CLASS=\"small\"><BDO dir=\"${textDirection}\"></BDO>&nbsp;(".$OthersUsers->num_rows().")</SPAN>");
			echo("</DIV>\n");
			echo("<DIV ID=\"Child${id}\" CLASS=\"child\" STYLE=\"margin-left: 12px\">\n");
			$j = 0;
			while(list($OtherUser, $Latin1, $status, $gender) = $OthersUsers->next_record())
			{
				$j++;
				// Put an icon when there is a profile for the user
				if($gender == 0)
					$gender = 'undefined';
				elseif($gender == 1)
					$gender = 'boy';
				elseif($gender == 2)
					$gender = 'girl';
				else
					$gender = 'none';
				if ($status != "u" && $status != "k" && $status != "d" && $status != "b")
				{
					echo('<a href="#" onClick="window.parent.runCmd(\'WHOIS\',\''.special_char2($OtherUser,$Latin1).'\'); return false;" class="user"><img src="images/gender_'.$gender.'.gif" width="14" height="14" border="0" alt="'.L_PROFILE.'"></a>&nbsp;');
				}
				else
				{
					echo('<img src="images/gender_none.gif" width="14" height="14" border="0" alt="' . L_NO_PROFILE . '">&nbsp;');
				}
				echo("<A HREF=\"javascript:window.parent.userClick('".special_char2($OtherUser,$Latin1)."',false);\" CLASS=\"user\">".special_char($OtherUser,$Latin1,$status)."</A><BR>\n");
			}
			echo("</DIV>\n");
			$ChildNb[$id] = $j;
		}
		$OthersUsers->clean_results();
	}
}
$DbLink->clean_results();
$DbLink->close();

// Display all rest default rooms
for($k = 0; $k < count($DefaultChatRooms); $k++)
{
	$tmpRoom = stripslashes($DefaultChatRooms[$k]);
	$id = md5($tmpRoom);

	// Display this room name when it hadn't been displayed yet
	if (strcasecmp($tmpRoom, stripslashes($R)) != 0 && (!isset($ChildNb) || !isset($ChildNb[$id])))
	{
		if (!isset($FirstOtherRoom))
			$FirstOtherRoom = "Parent".$id;

        echo("<DIV ID=\"Parent${id}\" CLASS=\"parent\" STYLE=\"margin-top: 10px; CURSOR: hand\">");
        echo("<A HREF=\"$From?Ver=H&L=$L&U=".urlencode(stripslashes($U))."$AddPwd2Link&R0=".urlencode($tmpRoom)."&T=1&D=$D&N=$N&E=".urlencode(stripslashes($R))."&EN=$T\" TARGET=\"_parent\">".htmlspecialchars($tmpRoom)."</A><SPAN CLASS=\"small\"><BDO dir=\"${textDirection}\"></BDO>&nbsp;(0)</SPAN>");
        echo("</DIV>\n");
    };
}
?>
</P>

<SCRIPT TYPE="text/javascript" LANGUAGE="JavaScript1.2">
<!--
window.parent.rooms_number = <?php echo(isset($i) ? "$i" : "0"); ?>;
window.parent.usersFrame = window;
window.parent.exitFrame = window.parent.frames['exit'].window;

<?php
if (isset($ChildNb) && count($ChildNb) > 0)
{
	?>
	// Set the table containing number of users per 'others' rooms
	window.parent.ChildNb = new Array;
	<?php
	while(list($key, $nb) = each($ChildNb))
	{
		echo("window.parent.ChildNb['$key'] = '$nb';\n");
	};
	unset($ChildNb);
};
?>

// Get the index of the first expandable/collapsible room under NN4+
if (window.parent.NS4)
{
	<?php
	if (isset($FirstOtherRoom))
	{
		?>
		firstEl = "<?php echo($FirstOtherRoom); ?>";
		firstInd = window.parent.getIndex(firstEl);
		window.parent.arrange();
		<?php
	}
	else
	{
		?>
		firstInd = null;
		<?php
	}
	?>
};

// Scrolls to the position where the frame was before reloading
if (window.parent.Y != null)
{
	scrollTo(0, window.parent.Y);
	if (window.parent.IE4) scrollBy(0, window.parent.Y-document.body.scrollTop);
};
//-->
</SCRIPT>
</BODY>

</HTML>
<?php

?>