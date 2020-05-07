<?php
/* ---
This is the main library called by the start screen of phpMyChat. It contains:
   - the common work at the beginning;
   - all what have to be done once the user has submit the form (part I);
   - the 'send_headers' function that is used.... to send some HTTP headers and
		to define the <HEAD> part of the starting page;
   - the 'layout' function that creates the start page.

   The mains PHP variables used inside this script are:
   - $L -> the current language;
   - $Charset -> the name of the charset associated to the current language; 
   - $Ver -> the JavaScript abilities of the browser of the user ('H' when DHTML
		enabled, 'M' when JavaScript1.1, 'L' else).
   - $U -> the login nick of the user;
   - $PASSWORD -> the password of the user in clear mode;
   - $PWD_Hash -> the md5 hash of '$PASSWORD';
   - $N -> the number of messages to be shown each time the 'messages' frame is
		reloaded;
   - $D -> the timeout between each update of the 'messages' frame;
   - $T -> the type of the room the user wants to enter in;
   - $R0 -> the name of the default public room the user wants to enter in (not
		defined if he doesn't choose to enter one of the default public rooms);
   - $R1 -> the name of the 'other' public room the user wants to enter in (not
		defined if he doesn't choose to enter one of the 'other' public rooms);
   - $R2 -> the name of the room the user wants to create (not defined if he
		doesn't choose to create a room);
   - $E -> the name of the room the user just leaves. When $E is defined, the $EN
		boolean variable may also be defined and requires this script to insert
		an exit message to the 'messages' table;
   - $Reload -> when the user runs some specific actions inside the chat (he uses
		the '/join' command, clicks on a room name at the 'users' frame or resizes
		the window for the browser inside netscape 4+), this variable is defined
		to skip some tests that aren't necessary;
   - $perms -> permission level of the user for the room he wants to enter in.
   ----------------------------------------------------------- */

/*COMMON WORK*/

// Get the names and values for vars sent to index.lib.php
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

// Fix some security holes
if (!is_dir('./'.substr($ChatPath, 0, -1))) exit();

require("./${ChatPath}config/config.lib.php");
require("./${ChatPath}lib/release.lib.php");
require("./${ChatPath}localization/languages.lib.php");
require("./${ChatPath}localization/".$L."/localized.chat.php");
require("./${ChatPath}lib/database/".C_DB_TYPE.".lib.php");
require("./${ChatPath}lib/clean.lib.php");
include("./${ChatPath}lib/get_IP.lib.php");

// Special cache instructions for IE5+
$CachePlus	= "";
# # # if (preg_match("/MSIE [56789]", (isset($HTTP_USER_AGENT)) ? $HTTP_USER_AGENT : getenv("HTTP_USER_AGENT"), "/")) $CachePlus = ", pre-check=0, post-check=0, max-age=0";
$now		= gmdate('D, d M Y H:i:s') . ' GMT';

header("Expires: $now");
header("Last-Modified: $now");
header("Cache-Control: no-cache, must-revalidate".$CachePlus);
header("Pragma: no-cache");
header("Content-Type: text/html; charset=${Charset}");

// avoid server configuration for magic quotes
set_magic_quotes_runtime(0);

// Get the relative path to the script that called this one
if (!isset($PHP_SELF)) $PHP_SELF = $HTTP_SERVER_VARS["PHP_SELF"];
$Action = basename($PHP_SELF);
$From = urlencode(preg_replace("[^/]+/","../",$ChatPath).$Action);

// For translations with a real iso code
if (!isset($FontFace)) $FontFace = "";
// For others translations
$DisplayFontMsg = !(isset($U) && $U != "");

// Translate to html special characters, and entities if message was sent with a latin 1 charset
$Latin1 = ($Charset == "iso-8859-1");
function special_char($str,$lang)
{
	return addslashes($lang ? htmlentities(stripslashes($str)) : htmlspecialchars(stripslashes($str)));
};

// Ensure a room ($what) is include in a rooms list ($in)
function room_in($what, $in)
{
	$rooms = explode(",",$in);
	for (reset($rooms); $room_name=current($rooms); next($rooms))
	{
		if (strcasecmp($what, $room_name) == 0) return true;
	};
	return false;
};



/*********** PART I ***********/

// Define the message to display if user comes here because he has been kicked
if (isset($KICKED))
{
	switch ($KICKED)
	{
		case '1':
			$Error = L_REG_18;
			break;
		case '2':
			$Error = L_REG_39;
			break;
		case '3':
			$Error = L_ERR_USR_19;
			break;
		case '4':
			$Error = L_ERR_USR_20;
	};
};

$DbLink = new DB;

// Fix some security issues
if (isset($Reload))
{
	$isHacking = false;
	if (($Reload == 'JoinCmd')
	 	&& (empty($E) || empty($Ver) || empty($L) || empty($U) || (empty($R0) && empty($R1) && empty($R2)) || empty($D)))
	{
		$isHacking = true;
	}
	else if (($Reload == 'NNResize')
	 	&& (empty($Ver) || empty($L) || empty($U) || empty($R) || empty($T) || empty($D) || empty($N)))
	{
		$isHacking = true;
	}
	else
	{
		$DbLink->query("SELECT password FROM ".C_REG_TBL." WHERE username='$U' LIMIT 1");
		list($user_password) = $DbLink->next_record();
		$DbLink->clean_results();
		if (!empty($user_password) && (empty($PWD_Hash) || $PWD_Hash != $user_password))
			$isHacking = true;
		unset($user_password);
	}

	if ($isHacking)
	{
		unset($Reload);
		if (isset($U)) unset($U);
		if (isset($PWD_Hash)) unset($PWD_Hash);
		if (isset($T)) unset($T);
		if (isset($R)) unset($R);
		if (isset($R0)) unset($R0);
		if (isset($R1)) unset($R1);
		if (isset($R2)) unset($R2);
		if (isset($E)) unset($E);
		$Error = L_ERR_USR_10;
	}
}

// Removes user from users table and if necessary add a notication message for him
if(isset($E) && $E != "")
{
	// Fix a security issue
	$DbLink->query("SELECT COUNT(*) FROM " . C_USR_TBL . " WHERE username='$U' AND ip='$IP' AND room='$E'");
	list($isHacking) = $DbLink->next_record();
	$isHacking = 1 - $isHacking;
	$DbLink->clean_results();
	if ($isHacking)
	{
		// HACKERS Attack !!!
		unset($E);
		if (isset($U)) unset($U);
		$Error = L_ERR_USR_10;
	}
	else
	{	
		$DbLink->query("DELETE FROM ".C_USR_TBL." WHERE username='$U' AND room='$E'");
		if (isset($EN))
		{
			# # # $DbLink->query("INSERT INTO ".C_MSG_TBL." VALUES ($EN, '$E', 'SYS exit', '', ".time().", '', 'sprintf(L_EXIT_ROM, .special_char($U,$Latin1))')");
			$DbLink->query("INSERT INTO ".C_MSG_TBL." VALUES ($EN, '$E', 'SYS exit', '', ".time().", '', ".sprintf(L_EXIT_ROM, .special_char($U,$Latin1)).")");
		}
	}
}

// If no room is specified but the main form has been posted, define the room to enter
// in as the first among default ones
if ((isset($Form_Send) && $Form_Send) && (((C_VERSION == 0) || ((!isset($R0) || $R0 == "") && (!isset($R1) || $R1 == "") && (!isset($R2) || $R2 == ""))))) $R0 = $DefaultChatRooms[0];

// Optimize some of the tables when the user logs in
if(isset($U) && (isset($N) && $N != ""))
{
	$DbLink->optimize(C_MSG_TBL);
	$DbLink->optimize(C_USR_TBL);
}


//	Ensures the nick is a valid one except if the frameset is reloaded because of the
//	NN4+ resize bug or because the user runs a join command.
if(!isset($Reload) && isset($U) && (isset($N) && $N != ""))
{
	$relog = false;
	if (C_NO_SWEAR == 1) include("./${ChatPath}lib/swearing.lib.php");
	// Check for no nick entered in
	if ($U == "")
	{
		$Error = L_ERR_USR_2;
	}
	// Check for invalid characters or empty nick
	elseif (trim($U) == "" || preg_match("/[, ]/", stripslashes($U)))
	{
		$Error = L_ERR_USR_16;
	}
	// Check for swear words in the nick
	elseif (C_NO_SWEAR == 1 && checkwords($U, true))
	{
		$Error = L_ERR_USR_18;
	}
	else
	{
		$DbLink->query("SELECT room FROM ".C_USR_TBL." WHERE username='$U' LIMIT 1");
		$Nb = $DbLink->num_rows();
		// If the same nick is already in use and the user is not registered deny access
		if($Nb != 0 && $PASSWORD == "" && !isset($PWD_Hash))
		{
			$Error = L_ERR_USR_1;
			$DbLink->clean_results();
		}
		else
		{
			list($room) = $DbLink->next_record();
			$DbLink->clean_results();
			$DbLink->query("SELECT password,perms,rooms FROM ".C_REG_TBL." WHERE username='$U' LIMIT 1");
			$reguser = ($DbLink->num_rows() != 0);
			if ($reguser) list($user_password,$perms,$rooms) = $DbLink->next_record();
			$DbLink->clean_results();

			if (!(isset($E) && $E != ""))
			{
				// Check for password if the nick exist in registered users table
				if ($reguser)
				{
					if ($PASSWORD == "" && !isset($PWD_Hash))
					{
						$Error = L_ERR_USR_3;
					}
					else
					{
						if (md5(stripslashes($PASSWORD)) != $user_password && (!isset($PWD_Hash) || $PWD_Hash != $user_password)) $Error = L_ERR_USR_4;
					}
					if (!isset($Error)) $DbLink->query("UPDATE ".C_REG_TBL." SET reg_time=".time()." WHERE username='$U'");
				}
				// If users isn't a registered one and phpMyChat require registration deny access
				else if (C_REQUIRE_REGISTER)
				{
					$Error = L_ERR_USR_14;
				}
			}

			// The var bellow is set to 1 when a registered user is allowed to log using a nick
			// that already exist in the users table
			$relog = ($Nb != 0 && !isset($Error));

			$CookieUsername = urlencode(stripslashes($U));
			setcookie("CookieUsername", $CookieUsername, time() + 60*60*24*365);        // cookie expires in one year
		}
	}
}


// Get perms of the user if the script is called by a join command
if (isset($Reload) && $Reload == "JoinCmd")
{
	$DbLink->query("SELECT perms,rooms FROM ".C_REG_TBL." WHERE username='$U' LIMIT 1");
	$reguser = ($DbLink->num_rows() != 0);
	if ($reguser) list($perms,$rooms) = $DbLink->next_record();
	$DbLink->clean_results();
};


// Ensure the user is not banished from the room he wants to enter in
if(!isset($Error) && (isset($N) && $N != "") && !isset($Reload))
{
	if (C_BANISH != "0" && (!isset($perms) || $perms != "admin"))
	{
		include("./${ChatPath}lib/banish.lib.php");
		if ($IsBanished) $Error = L_ERR_USR_20;
	};
};


// Ensures the user can create a room and the room name is a valid one (bypassed test
//		when the frameset is reloaded because of the NN4+ resize bug).
if(!isset($Error) && (isset($R2) && $R2 != ""))
{
	// Skipped when the script is called by a join command.
	if (!isset($Reload))
	{
		// User is not registered -> Deny room creation
		if (!$reguser)
		{
			$Error = L_ERR_USR_13;
		}
		// Check for invalid characters or empty room name
		else if (trim($R2) == "" || preg_match("/[,]/", stripslashes($R2)))
		{
			$Error = L_ERR_ROM_1;
		}
		// Check for swear words in room name
		else if(C_NO_SWEAR == 1 && checkwords($R2, true))
		{
			$Error = L_ERR_ROM_2;
		}
		// Ensure there is no existing room with the same name but a different type...
		else
		{
			// ...among reserved name for private/public (default) rooms
			$ToCheck = ($T == "1" ? $DefaultPrivateRooms : $DefaultChatRooms);
			for ($i = 0; $i < count($ToCheck); $i++)
			{
				if (strcasecmp($R2,$ToCheck[$i]) == "0")
				{
					$Error = ($T == 0 ? L_ERR_ROM_3:L_ERR_ROM_4);
					break;
				};
			};
			unset($ToCheck);

			// ...among other rooms created by users
			if (!isset($Error))
			{
				$T1 = 1 - $T;
				$DbLink->query("SELECT count(*) FROM ".C_MSG_TBL." WHERE room = '$R2' AND type = '$T1' LIMIT 1");
				list($Nb) = $DbLink->next_record();
				$DbLink->clean_results();
				if($Nb != 0) $Error = ($T == 0 ? L_ERR_ROM_3:L_ERR_ROM_4);
			};
		};
	};

	// Define the user status
	if (!isset($Error))
	{
		$register_room = true;
		// If the name of the room to be created is a reserved one for private/public (default) rooms,
		// status will be 'user'. Skipped when the script is called by a join command.
		if (!isset($Reload))
		{
			$ToCheck = ($T == "1" ? $DefaultChatRooms : $DefaultPrivateRooms);
			for ($i = 0; $i < count($ToCheck); $i++)
			{
				if (strcasecmp($R2,$ToCheck[$i]) == "0") $register_room = false;
			};
			unset($ToCheck);
		};

		// If room name is the same than one of an existing room containing "true" messages
		// (not only notifications of users entrance/exit) or containing only "system"
		// message but an other user is already logged in, status will be 'user'
		if ($register_room)
		{
			$DbLink->query("SELECT Count(*) FROM ".C_MSG_TBL." WHERE room='$R2' AND username NOT LIKE 'SYS %' LIMIT 1");
			list($count) = $DbLink->next_record();
			$register_room = ($count == "0");
			$DbLink->clean_results();
		};

		if ($register_room)
		{
			$DbLink->query("SELECT count(*) FROM ".C_USR_TBL." WHERE room='$R2' AND username != '$U' LIMIT 1");
			list($anybody) = $DbLink->next_record();
			$register_room = ($anybody == 0);
			$DbLink->clean_results();
		};

		if ($register_room)
		{
			// If an other registered user is already moderator for the room to be created but
			// there is no "true" message in this room then set his status to user for this room
			$UpdLink = new DB;
			$DbLink->query("SELECT username,rooms FROM ".C_REG_TBL." WHERE perms = 'moderator' AND username != '$U'");
			while (list($mod_un,$mod_rooms) = $DbLink->next_record())
			{
				$changed = false;
				$roomTab = explode(",",$mod_rooms);
				for ($i = 0; $i < count($roomTab); $i++)
				{
					if (strcasecmp(stripslashes($R2), $roomTab[$i]) == 0)
					{
						$roomTab[$i] = "";
						$changed = true;
						break;
					};
				};
				if ($changed)
				{
					$mod_rooms = str_replace(",,",",",preg_replace("^,|,$","",implode(",",$roomTab)));
					$UpdLink->query("UPDATE ".C_REG_TBL." SET rooms='".addslashes($mod_rooms)."' WHERE username='".addslashes($mod_un)."'");
					$UpdLink->query("UPDATE ".C_USR_TBL." SET status='r' WHERE room='$R2' AND username='".addslashes($mod_un)."'");
				};
				unset($roomTab);
			};
			$DbLink->clean_results();

			// Update the current user status for the room to be created in registered users table
			$changed = false;
			if (!room_in(stripslashes($R2), $rooms))
			{
				if ($rooms != "") $rooms .= ",";
				$rooms .= stripslashes($R2);
				$changed = true;
			}
			if ($perms == "user" || $perms == "")
			{
				$perms = "moderator";
				$changed = true;
			}
			if (($changed)&&($perms != "admin"))
			{
				$DbLink->query("UPDATE ".C_REG_TBL." SET perms='$perms', rooms='".addslashes($rooms)."' WHERE username='$U'");
			}
		}
	}
}


// ** Enter the chat **
if(!isset($Error) && (isset($N) && $N != ""))
{
	if(isset($R2) && $R2 != "")
	{
		$R = $R2;
	}
	elseif (!isset($R))		// $R is set when the frameset is reloaded because of the NN4+ resize bug.
	{
		$T = 1;
		$R = (isset($R0) && $R0 != "")? $R0 : $R1;
	};

	$CookieRoom = urlencode(stripslashes($R));
	setcookie("CookieRoom", $CookieRoom, time() + 60*60*24*365);        // cookie expires in one year
	setcookie("CookieRoomType", $T, time() + 60*60*24*365);        // cookie expires in one year
	if (isset($HTTP_COOKIE_VARS))
	{
		if (isset($HTTP_COOKIE_VARS["CookieMsgOrder"])) $CookieMsgOrder = $HTTP_COOKIE_VARS["CookieMsgOrder"];
		if (isset($HTTP_COOKIE_VARS["CookieShowTimestamp"])) $CookieShowTimestamp = $HTTP_COOKIE_VARS["CookieShowTimestamp"];
		if (isset($HTTP_COOKIE_VARS["CookieNotify"])) $CookieNotify = $HTTP_COOKIE_VARS["CookieNotify"];
	};
	if (!isset($O)) $O = isset($CookieMsgOrder) ? $CookieMsgOrder : C_MSG_ORDER;
	if (!isset($ST)) $ST = isset($CookieShowTimestamp) ? $CookieShowTimestamp : C_SHOW_TIMESTAMP;
	if (!isset($NT)) $NT = isset($CookieNotify) ? $CookieNotify : C_NOTIFY;
	if (!isset($PWD_Hash)) $PWD_Hash = (isset($reguser) && $reguser ?  md5(stripslashes($PASSWORD)) : "");

	// Define the user status to be put in the users table if necessary. Skipped when the
	// frameset is reloaded because of the NN4+ resize bug.
	if (!isset($Reload) || $Reload != "NNResize")
	{
		if (!isset($perms)) $perms = ((isset($reguser) && $reguser) ? "" : "noreg");
		switch ($perms)
		{
			case 'admin':
				$status = "a";
				break;
			case 'moderator':
				$status = (room_in(stripslashes($R), $rooms) ? "m" : "r");
				break;
			case 'noreg':
				$status = "u";
				break;
			default:
				$status = "r";
		};
	};

	// Udpates the IP address and the last log. time of the user in the registered users table if necessary
	if (isset($reguser) && $reguser) $DbLink->query("UPDATE ".C_REG_TBL." SET reg_time='".time()."', ip='$IP' WHERE username='$U'");

	// In the case of a registered user that logs again...
	// ...in the same room update his logging time and update his IP address;
	// ...in an other room kick him from the other room, put a notification message of
	// 		exit for this room, update the users table and put a notification message of
	// 		entrance for the room he log in.
	$current_time = time();
	if (isset($relog) && $relog)
	{
		if (stripslashes($R) == $room)
		{
			$DbLink->query("UPDATE ".C_USR_TBL." SET u_time='$current_time', ip='$IP' WHERE username='$U'");
		}
		else
		{
			$DbLink->query("SELECT type FROM ".C_MSG_TBL." WHERE room='".addslashes($room)."' LIMIT 1");
			list($type) = $DbLink->next_record();
			$DbLink->clean_results();
			$DbLink->query("INSERT INTO ".C_MSG_TBL." VALUES ('$type', '".addslashes($room)."', 'SYS exit', '', '$current_time', '', 'sprintf(L_EXIT_ROM, .special_char($U,$Latin1))')");
			$DbLink->query("INSERT INTO ".C_MSG_TBL." VALUES ($T, '$R', 'SYS enter', '', '$current_time', '', 'sprintf(L_ENTER_ROM, .special_char($U,$Latin1))')");
			$DbLink->query("UPDATE ".C_USR_TBL." SET room='$R',u_time='$current_time', status='$status', ip='$IP' WHERE username='$U'");
			if (C_WELCOME)
			{
				// Delete old welcome messages sent to the current user
				$DbLink->query("DELETE FROM ".C_MSG_TBL." WHERE username = 'SYS welcome' AND address = '$U'");
				// Insert a new welcome message in the messages table
				include("./${ChatPath}lib/welcome.lib.php");
				$current_time_plus = $current_time + 1;	// ensures the welcome msg is the last one
				$DbLink->query("INSERT INTO ".C_MSG_TBL." VALUES ($T, '$R', 'SYS welcome', '', '$current_time_plus', '$U', 'sprintf(.WELCOME_MSG.)')");
			};
		};
	}
	// In the case of an user that logs again in the same room because of the resize bug of NN4+ 
	// update his logging time and his IP address
	elseif (isset($Reload) && $Reload == "NNResize")
	{
		$DbLink->query("UPDATE ".C_USR_TBL." SET room='$R',u_time='$current_time', ip='$IP' WHERE username='$U'");
	}
	// For all other case of users entering in, set user infos. in users table and put a
	// notification message of entrance in the messages table
	else
	{
		$DbLink->query("INSERT INTO ".C_USR_TBL." VALUES ('$R', '$U', '$Latin1', '$current_time', '$status','$IP')");
		$DbLink->query("INSERT INTO ".C_MSG_TBL." VALUES ($T, '$R', 'SYS enter', '', '$current_time', '', 'sprintf(L_ENTER_ROM, .special_char($U,$Latin1))')");
		if (C_WELCOME)
		{
			// Delete old welcome messages sent to the current user
			$DbLink->query("DELETE FROM ".C_MSG_TBL." WHERE username = 'SYS welcome' AND address = '$U'");
			// Insert a new welcome message in the messages table
			include("./${ChatPath}lib/welcome.lib.php");
			$current_time_plus = $current_time + 1;	// ensures the welcome msg is the last one
			$DbLink->query("INSERT INTO ".C_MSG_TBL." VALUES ($T, '$R', 'SYS welcome', '', '$current_time_plus', '$U', 'sprintf(.WELCOME_MSG.)')");
		};
	};

	// Delete invite messages sent to the user for the room he will enter in
	$DbLink->query("SELECT m_time FROM ".C_MSG_TBL." WHERE username='SYS inviteTo' AND address='$U' AND room='$R'");
	if($DbLink->num_rows() != 0)
	{
		$DelLink = new DB;
		while(list($sent_time) = $DbLink->next_record())
		{
			$DelLink->query("DELETE FROM ".C_MSG_TBL." WHERE m_time='$sent_time' AND (username='SYS inviteFrom' OR (username='SYS inviteTo' AND address='$U'))");
		};
	};
	?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
	<HTML>
	<HEAD>
	<TITLE><?php echo(APP_NAME); ?></TITLE>
	<LINK REL="SHORTCUT ICON" HREF="<?php echo($ChatPath); ?>favicon.ico">
	<SCRIPT TYPE="text/javascript" LANGUAGE="JavaScript">
	<!--
	// Display & remove the server time at the status bas
	function clock(gap)
	{
		cur_date = new Date();
		calc_date = new Date(cur_date - gap);
		calc_hours = calc_date.getHours();
		calc_minuts = calc_date.getMinutes();
		calc_seconds = calc_date.getSeconds();
		if (calc_hours < 10) calc_hours = "0" + calc_hours;
		if (calc_minuts < 10) calc_minuts = "0" + calc_minuts;
		if (calc_seconds < 10) calc_seconds = "0" + calc_seconds;
		calc_time = calc_hours + ":" + calc_minuts + ":" + calc_seconds;
		window.status = "<?php echo(L_SVR_TIME); ?>" + calc_time;

		clock_disp = setTimeout('clock(' + gap + ')', 1000);
	}

	function stop_clock()
	{
		clearTimeout(clock_disp);
		window.status = '';
	}

	function calc_gap(serv_date)
	{
		server_date = new Date(serv_date);
		local_date = new Date();
		return local_date - server_date;
	}

	<?php
	if ($ST == 1)
	{
		$CorrectedDate = mktime(date("H") + C_TMZ_OFFSET,date("i"),date("s"),date("m"),date("d"),date("Y"));
		?>
		gap = calc_gap("<?php echo(date("F d, Y H:i:s", $CorrectedDate)); ?>");
		clock(gap);
		<?php
	}
	?>

	// Automatically submit a command
	function runCmd(CmdName,infos)
	{
		if (window.frames['input'] && window.frames['input'].window.document.forms['MsgForm'])
		{
			var inputForm = window.frames['input'].window.document.forms['MsgForm'];
			if (infos != "") infos = " " + infos;
			inputForm.elements['M'].value = "/" + CmdName + infos;
			inputForm.elements['sent'].value = '1';
			if (document.all) inputForm.elements['sendForm'].disabled = true;
			inputForm.submit();
		};
	};

	// Misc vars
	var is_ignored_popup = null;
	var path2Chat = "<?php echo($ChatPath); ?>";

	<?php
	if ($Ver == "H")
	{
		?>
		// Forced reload of the loader frame, function called by the input frame
		var time4LastLoadedMsg = null;
		var time4LastCheckedUser = null;
		var refresh_query = "<?php echo("From=$From&L=$L&U=".urlencode(stripslashes($U))."&R=".urlencode(stripslashes($R))."&T=$T&D=$D&N=$N&ST=$ST&NT=$NT&First=1"); ?>";
		function force_refresh()
		{
			query = refresh_query + "&LastLoad=" + time4LastLoadedMsg + "&LastCheck=" + time4LastCheckedUser;
			window.frames['loader'].window.location.replace("loader.php?" + query);
		}
		<?php
	}
	else
	{
		?>
		ver4 = false;
		<?php
	};
	?>

	// Launch the help popup
	var is_help_popup = null;

	function help_popup()
	{
		if (is_help_popup && !is_help_popup.closed)
		{
			is_help_popup.focus();
		}
		else
		{
			var scrTop = mouseY-400;
			var scrLeft = mouseX-<?php echo($Charset == "windows-1256" ? "610" : "10"); ?>;
			var scrPos = "top=" + scrTop + ",screenY=" + scrTop + ",left=" + scrLeft + ",screenX=" + scrLeft + ",";
			is_help_popup = window.open("help_popup.php?<?php echo("L=$L&Ver=$Ver"); ?>","help_popup",scrPos + "width=600,height=350,scrollbars=yes,resizable=yes");
		};
	};
	// -->
	</SCRIPT>
	<SCRIPT TYPE="text/javascript" LANGUAGE="javascript1.1">
	<!--
	// Misc vars
	imgHelpOff = new Image(15,15); imgHelpOff.src = path2Chat + "images/helpOff.gif";
	imgHelpOn = new Image(15,15); imgHelpOn.src = path2Chat + "images/helpOn.gif";

	// Put the nick of the user who was clicked on in the messages or the users frames
	// to the message box in the input frame;
	function userClick(user,privMsg)
	{
		if (window.frames['input'] && window.frames['input'].window.document.forms['MsgForm'].elements['MsgTo'])
		{
			window.frames['input'].window.document.forms['MsgForm'].elements['MsgTo'].value = user;
			var msgbox = window.frames['input'].window.document.forms['MsgForm'].elements['M'];
			if (privMsg)
			{
				var oldStr = msgbox.value;
				if (oldStr == "" || oldStr.substring(0,1) != " ") oldStr = " " + oldStr;
				msgbox.value = "/TO " + user + oldStr;
			}
			else
			{
				msgbox.value += user;
				if (msgbox.value == user) msgbox.value += "> ";
			};
			msgbox.focus();
		};
	};

	// Color choice at the input frame; 
	isModerator = <?php echo((isset($status) && ($status == "a" || $status == "m")) ? 1 : 0); ?>;
	imgColor1 = new Image(4,20); imgColor1.src = path2Chat + "images/unselColor.gif";
	imgColor2 = new Image(4,20); imgColor2.src = path2Chat + "images/selColor.gif";
	var SelColor = null;

	function ChangeColor(ColorVal,ColorRank)
	{
		if (SelColor != ColorRank)
		{
			if (document.all)
			{
				obj1 = window.frames['input'].window.document.all[SelColor];
				obj2 = window.frames['input'].window.document.all[ColorRank];
			}
			else if (document.images)
			{
				obj1 = window.frames['input'].window.document.images[SelColor];
				obj2 = window.frames['input'].window.document.images[ColorRank];
			}
			else return;

			if (SelColor != null)
			{
				obj1.src = imgColor1.src;
			};
			SelColor = ColorRank;
			window.frames['input'].window.document.forms['MsgForm'].elements['C'].value = ColorVal;
			obj2.src = imgColor2.src;
		};
		window.frames['input'].window.document.forms['MsgForm'].elements['M'].focus();
	};

	// Set the focus to the message box at the input frame; 
	function get_focus()
	{
		window.frames['input'].window.focus();
		window.frames['input'].window.document.forms['MsgForm'].elements['M'].focus();
	};
	// -->
	</SCRIPT>
	<SCRIPT TYPE="text/javascript" LANGUAGE="JavaScript1.2">
	<!--
	// Get the position for the help popup
	var mouseX = 0;
	var mouseY = 0;

	function displayLocation(e)
	{
		if (ver4)
		{
			if (IE4) e = window.frames['input'].window.event;
			mouseX = e.screenX;
			mouseY = e.screenY;
		}
		return;
	}

	// Quick validation of the message or the command submited at the input frame
	function validateSubmission()
	{
		inputFrameForm = window.frames['input'].window.document.forms['MsgForm'];

		// Submission looks like a command?
		isCmd	= (inputFrameForm.elements['M'].value.substring(0,1) == '/');
		// RegExp to quick check for valid commands
		re = /^/(!$|announce .+|ban .+|clear$|help$|?$|ignore|invite .+|join .+|kick .+|me .+|msg .+|to .+|notify$|order$|profile$|promote .+|quit|exit|bye|refresh|save|show|last|timestamp$|whois .+)/i;

		// Ensure the message box isn't empty
		if (inputFrameForm.elements['M'].value == '')
		{
			inputFrameForm.elements['M'].focus();
			return false;
		}
		// It looks like a command but's not a valid one -> display error message
		else if (isCmd && !re.test(inputFrameForm.elements['M'].value))
		{
			inputFrameForm.elements['M'].select();
			alert("<?php echo(str_replace(",",L_BAD_CMD)); ?>");
			return false;
		}
		// It doesn't look like a command -> it's a message, then ensure a message
		// isn't currently being submitted...
		else if (!isCmd && inputFrameForm.elements['sent'].value == '1')
		{
			inputFrameForm.elements['M'].focus();
			return false;
		}
		// ... and that the same message hasn't been submitted the last time
 		else if (!isCmd && inputFrameForm.elements['M'].value == inputFrameForm.elements['M0'].value)
		{
			inputFrameForm.elements['M'].value = '';
			inputFrameForm.elements['M'].focus();
			return false;
		}
		// All the tests have been succesfully passed -> submit the from
		else
		{
			inputFrameForm.elements['sent'].value = '1';
			if (document.all) inputFrameForm.elements['sendForm'].disabled = true;
			return true;
		};
	}
	// -->
	</SCRIPT>
	<?php
	if ($Ver == "H")
	{
		?>
		<SCRIPT SRC="<?php echo($ChatPath); ?>lib/usersH.js" TYPE="text/javascript" LANGUAGE="JavaScript1.2"></SCRIPT>
		<SCRIPT SRC="<?php echo($ChatPath); ?>lib/connectStateH.js" TYPE="text/javascript" LANGUAGE="JavaScript1.2"></SCRIPT>
		<?php
	};
	$Ver1 = ($Ver == "H" ? $Ver : "L");
	$AddPwd2Url = ($PWD_Hash != "" ? "&PWD_Hash=$PWD_Hash" : "");
	include("./${ChatPath}lib/frameset_def.lib.php");
	?>
	</HTML>
	<?php
	$DbLink->close();
	exit;

} // end of entering the chat work



/*********** 'send_headers' FUNCTION ***********/

/* -------------------------------------------------------------
   The send_headers function add lines at the head part of your html file.

   $title var allows to show "phpMyChat" as the title for your window when sets to 1/true.
   $icon var allows to set phpMyChat icon as the icon for favorites when sets to 1/true.
   ----------------------------------------------------------- */

function send_headers($title, $icon)
{
	global $ChatPath, $From, $L;
	global $Charset, $FontName, $FontSize;

	if ($title)
		echo("t" . '<TITLE>' . APP_NAME . '</TITLE>' . "n");
	?>
	<!--The lines below are usefull for debugging purpose, please do not remove them!
		Release: phpMyChat 0.14.5 ｩ 2000-2001 
		The phpHeaven Team  (http://www.phpheaven.net/)-->
	<META NAME="description" CONTENT="phpMyChat">
	<META NAME="keywords" CONTENT="phpMyChat">
	<?php
	if ($icon) echo("<LINK REL="SHORTCUT ICON" HREF="${ChatPath}favicon.ico">n");

	// For translations with an explicit charset (not the 'x-user-defined' one)
	if (!isset($FontName)) $FontName = "";
	?>
	<LINK REL="stylesheet" HREF="<?php echo($ChatPath); ?>config/start_page.css.php?<?php echo("Charset=${Charset}&medium=${FontSize}&FontName=${FontName}"); ?>" TYPE="text/css">
	<link rel="stylesheet" type="text/css" href="/css/spatiallink.css" />
	<SCRIPT TYPE="text/javascript" LANGUAGE="javascript">
	<!--
	var NS4 = (document.layers) ? 1 : 0;
	var IE4 = ((document.all) && (parseInt(navigator.appVersion)>=4)) ? 1 : 0;
	var ver4 = (NS4 || IE4) ? "H" : "L";

	// Will update the "Ver" field in the form below according to the javascript abilities of
	// the browser the users surf with
	function defineVerField()
	{
		if (document.images && ver4 == 'L')
			document.forms['Params'].elements['Ver'].value = 'M';	// js1.1 enabled browser
		else document.forms['Params'].elements['Ver'].value = ver4;
	}

	// Open the tutorial popup
	function tutorial_popup()
	{
		window.focus();
		tutorial_popupWin = window.open("<?php echo($ChatPath); ?>tutorial_popup.php?<?php echo("L=$L&Ver="); ?>"+ver4,"tutorial_popup","resizable=yes,scrollbars=yes,toolbar=yes,menubar=yes,status=yes");
		tutorial_popupWin.focus();
	}

	// Open the users popup according to the DHTML capacities of the browser
	function users_popup()
	{
		window.focus();
		users_popupWin = window.open("<?php echo($ChatPath); ?>users_popup"+ver4+".php?<?php echo("From=$From&L=$L"); ?>","users_popup_<?php echo(md5(uniqid(""))); ?>","width=180,height=300,resizable=yes,scrollbars=yes");
		users_popupWin.focus();
	}

	// Open popups for registration stuff
	function reg_popup(name)
	{
		window.focus();
		url = "<?php echo($ChatPath); ?>" + name + ".php?L=<?php echo($L); ?>&Link=1";
		pop_width = (name != 'admin'? 350:510);
		pop_height = (name != 'deluser'? 470:190);
		param = "width=" + pop_width + ",height=" + pop_height + ",resizable=yes,scrollbars=yes";
		name += "_popup";
		window.open(url,name,param);
	}

	// The three functions bellow allows to ensure an unique choice among rooms
	function reset_R0()
	{
		<?php
		if (C_VERSION == 2)
		{
			?>
			document.forms['Params'].elements['R1'].options[0].selected = true;
			document.forms['Params'].elements['T'].options[0].selected = true;
			document.forms['Params'].elements['R2'].value = '';
			<?php
		}
		?>
	}

	function reset_R1()
	{
		document.forms['Params'].elements['R0'].options[0].selected = true;
		document.forms['Params'].elements['T'].options[0].selected = true;
		document.forms['Params'].elements['R2'].value = '';
	}

	function reset_R2()
	{
		document.forms['Params'].elements['R0'].options[0].selected = true;
		document.forms['Params'].elements['R1'].options[0].selected = true;
	}
	// -->
	</SCRIPT>
	<?php

} // end of send_headers function;



/*********** 'layout' FUNCTION ***********/

/* -------------------------------------------------------------
   The layout function draw the initial table/form. It will define three way to go
   into the chat (the $Ver et $Ver1 var) dependent of the browser capacities:
   - those that accept DHTML will use "H" (for highest) named scripts, the others
    	will run "L" (for lowest) named scripts;
   - those that support JavaScript1.1 stuff will use a color picker to choose
    	messages colors in the chat/input.php script ($Ver is set to "M" for medium
    	if the browser can not support DHTML, else "H"), others a drop down list.
   ----------------------------------------------------------- */

function layout($Err, $U, $R, $T)
{
	global $DbLink;
	global $ChatPath, $From, $Action, $L;
	global $Charset, $DisplayFontMsg, $FontName;
	global $AvailableLanguages;
	global $DefaultChatRooms;
	if ($Err) global $Error;
	?>

<TABLE ALIGN="center" CELLPADDING=5 CLASS="ChatBody"><TR><TD CLASS="ChatBody">

<CENTER>
<FORM ACTION="<?php echo("$Action"); ?>" METHOD="POST" AUTOCOMPLETE="OFF" NAME="Params" onSubmit="defineVerField();">
<SPAN CLASS="ChatTitle"><?php echo(APP_NAME." ".APP_VERSION); ?></SPAN>
<?php
// Msg for translations with no real iso code
if (isset($FontName) && $FontName != "" && file_exists($ChatPath."localization/${L}/${FontName}.zip"))
{
	if (!isset($Error) && $DisplayFontMsg) echo("<P CLASS="ChatError">This translation of ".APP_NAME." requires the <A HREF="${ChatPath}localization/${L}/${FontName}.zip" CLASS="ChatFonts">${FontName} font face</A></P>");
}
?>

<?php
/*	spatiallink:
<P>
<A HREF="<?php echo($ChatPath); ?>tutorial_popup.php?<?php echo("L=$L&Ver=L"); ?>" onClick="tutorial_popup(); return false" CLASS="ChatLink" TARGET="_blank"><?php echo(L_TUTORIAL); ?></A>
</P>
<P CLASS="ChatP1">
echo(L_WEL_1." ".C_MSG_DEL." ".L_WEL_2." ".C_USR_DEL." ".L_WEL_3);

echo("Usernames are deleted after ".C_USR_DEL." ".L_WEL_3);
</P> */
//	include content
include '/opt/bitnami/apache2/htdocs/inc_con/inc_con_indexgischat.php';
?>

<?php
$DefaultRoomFound = 0;
if($DbLink->query("SELECT DISTINCT u.username FROM ".C_USR_TBL." u, ".C_MSG_TBL." m WHERE u.room = m.room AND m.type = 1"))
{
	$Nb = $DbLink->num_rows();
	$link = " <A HREF="${ChatPath}users_popupL.php?From=$From&L=$L" CLASS="ChatLink" onClick="users_popup(); return false" TARGET="_blank">";
	echo("<P CLASS="ChatP1">".L_CUR_1.$link.$Nb." ".($Nb > 1 ? L_USERS : L_USER)."</A> ".L_CUR_2."</P>");
}
if(isset($Error))
{
	echo("<P CLASS="ChatError">$Error</P>");
}
if (!isset($Ver)) $Ver = "L";
?>

<INPUT TYPE="hidden" NAME="Ver" VALUE="<?php echo($Ver); ?>">
<INPUT TYPE="hidden" NAME="Form_Send" VALUE="1">
<INPUT TYPE="hidden" NAME="L" VALUE="<?php echo($L); ?>">
<INPUT TYPE="hidden" NAME="N" VALUE="<?php echo(C_MSG_NB); ?>">
<INPUT TYPE="hidden" NAME="D" VALUE="<?php echo(C_MSG_REFRESH); ?>">

<TABLE BORDER=0 CELLPADDING=3 CLASS="ChatTable">
<TR CLASS="ChatCell">
	<TD ALIGN="CENTER" CLASS="ChatCell">
		<TABLE BORDER=0 CLASS="ChatTable">
		<?php
		// Display flags if necessary
		if (C_MULTI_LANG == 1)
		{
		?>
		<TR CLASS="ChatCell">
			<TD COLSPAN=2 ALIGN="CENTER" CLASS="ChatCell">
				<SPAN CLASS="ChatFlags">
				<?php
				asort($AvailableLanguages);
				reset($AvailableLanguages);
				$i = 0;
				while(list($key, $name) = each($AvailableLanguages))
				{
					$i++;
					echo("<A HREF="$Action?L=${name}">");
					echo("<IMG SRC="${ChatPath}localization/${name}/flag.gif" BORDER=0 WIDTH=24 HEIGHT=16 ALT=.ucfirst(str_replace("_"," ",$name)).></A>&nbsp;");
					if ($i % 15 == 0) echo ("<BR>");
				};
				unset($AvailableLanguages);
				?>
				</SPAN>
			</TD>
		</TR>
		<?php
		};

		// Horizontal alignement for cells topic
		$CellAlign = ($Charset == "windows-1256" ? "LEFT" : "RIGHT");
		?>
		<TR CLASS="ChatCell">
			<TH COLSPAN=2 CLASS="ChatTabTitle"><?php echo(L_SET_1); ?></TH>
		</TR>
		<TR CLASS="ChatCell">
			<TD ALIGN="<?php echo($CellAlign); ?>" VALIGN="TOP" CLASS="ChatCell" NOWRAP><?php echo(L_SET_2); ?> :</TD>
			<TD VALIGN="TOP" CLASS="ChatCell">
				<INPUT TYPE="text" NAME="U" SIZE=11 MAXLENGTH=10 VALUE="<?php echo(htmlspecialchars(urldecode($U))); ?>" CLASS="ChatBox">
			</TD>
		</TR>
		<TR CLASS="ChatCell">
			<TD ALIGN="<?php echo($CellAlign); ?>" VALIGN="TOP" CLASS="ChatCell" NOWRAP><?php echo(L_REG_1); ?> :</TD>
			<TD VALIGN="TOP" CLASS="ChatCell" NOWRAP>
				<INPUT TYPE="password" NAME="PASSWORD" SIZE=11 MAXLENGTH=16 CLASS="ChatBox">
				<?php if (!C_REQUIRE_REGISTER) echo("&nbsp;<U>".L_REG_1r."</U>"); ?>
			</TD>
		</TR>

		<TR CLASS="ChatCell"><TD CLASS="ChatCell">&nbsp;</TD></TR>

		<TR CLASS="ChatCell">
			<TH COLSPAN=2 CLASS="ChatTabTitle"><?php echo(L_REG_2); ?></TH>
		</TR>
		<TR CLASS="ChatCell">
			<TD ALIGN="center" COLSPAN=2 CLASS="ChatCell">
				<BR>
				<A HREF="<?php echo($ChatPath); ?>register.php?L=<?php echo($L); ?>" CLASS="ChatReg" onClick="reg_popup('register'); return false" TARGET="_blank"><?php echo(L_REG_3); ?></A>
				| <A HREF="<?php echo($ChatPath); ?>edituser.php?L=<?php echo($L); ?>" CLASS="ChatReg" onClick="reg_popup('edituser'); return false" TARGET="_blank"><?php echo(L_REG_4); ?></A>
				<?php
				if (C_SHOW_DEL_PROF != 0)
				{
					?>
					| <A HREF="<?php echo($ChatPath); ?>deluser.php?L=<?php echo($L); ?>" CLASS="ChatReg" onClick="reg_popup('deluser'); return false" TARGET="_blank"><?php echo(L_REG_5); ?></A>
					<?php
				}
				if (C_SHOW_ADMIN != 0)
				{
					?>
					
<?php
/*	spatiallink:
<A HREF="[?php echo($ChatPath); ?]admin.php?L=[?php echo($L); ?]&Link=1" CLASS="ChatReg" onClick="reg_popup('admin'); return false" TARGET="_blank">[?php echo(L_REG_35); ?]</A> */
?>			
					<?php
				}
				?>
			</TD>
		</TR>
		<?php
		if (C_VERSION > 0)
		{
			?>
			<TR CLASS="ChatCell">
				<TD COLSPAN=2 CLASS="ChatCell">&nbsp;</TD>
			</TR>
			<TR CLASS="ChatCell">
				<TH COLSPAN=2 CLASS="ChatTabTitle"><?php echo(L_SET_5); ?></TH>
			</TR>
			</TABLE>
			<TABLE BORDER=0 CLASS="ChatTable">
			<TR CLASS="ChatCell">
				<TD ALIGN="<?php echo($CellAlign); ?>" VALIGN="TOP" CLASS="ChatCell" NOWRAP><?php echo(L_SET_6); ?> :</TD>
				<TD VALIGN="TOP" CLASS="ChatCell">
					<SELECT NAME="R0" CLASS="ChatBox" onChange="reset_R0();">
						<OPTION VALUE=""><?php echo(L_SET_7); ?></OPTION>
						<?php
						// Display default rooms in the drop down list
						$PrevRoom = "";
						if($R != "") $PrevRoom = urldecode($R);

						$DefaultRoomsString = "";
						for($i = 0; $i < count($DefaultChatRooms); $i++)
						{
							$tmpRoom = stripslashes($DefaultChatRooms[$i]);
							$DefaultRoomsString .= ($DefaultRoomsString == "" ? "" : ",").$tmpRoom;
							echo("<OPTION VALUE=.htmlspecialchars($tmpRoom).");
							if(strcasecmp($tmpRoom, $PrevRoom) == 0)
							{
								echo(" SELECTED");
								$DefaultRoomFound = 1;
							}
							echo(">".$tmpRoom."</OPTION>");
						}
						?>
					</SELECT>
				</TD>
			</TR>
			<?php
		}
		if (C_VERSION == 2)
		{
			?>
			<TR CLASS="ChatCell">
				<TD ALIGN="<?php echo($CellAlign); ?>" VALIGN="TOP" CLASS="ChatCell" NOWRAP><?php echo(L_SET_8); ?> :</TD>
				<TD VALIGN="TOP" CLASS="ChatCell">
					<SELECT NAME="R1" CLASS="ChatBox" onChange="reset_R1();">
						<OPTION VALUE=""><?php echo(L_SET_7); ?></OPTION>
						<?php
						// Display other public rooms in the drop down list
						$DbLink->query("SELECT DISTINCT room FROM ".C_MSG_TBL." WHERE type = 1 AND username NOT LIKE 'SYS %' ORDER BY room");
						while(list($Room) = $DbLink->next_record())
						{
							if (!room_in($Room, $DefaultRoomsString))
							{
								echo("<OPTION VALUE=.htmlspecialchars($Room).");
								if(strcasecmp($Room, $PrevRoom) == 0 && $DefaultRoomFound == 0)
								{
									echo(" SELECTED");
									$DefaultRoomFound = 1;
								}
								echo(">${Room}</OPTION>");
							}
						}
						$DbLink->clean_results();
						?>
					</SELECT>
				</TD>
			</TR>
			<TR CLASS="ChatCell">
				<TD ALIGN="<?php echo($CellAlign); ?>" VALIGN="TOP" CLASS="ChatCell" NOWRAP>
					<?php echo(L_SET_9." "); ?>
					<SELECT NAME="T" CLASS="ChatBox">
						<OPTION VALUE="1" <?php if($T == 1 && $DefaultRoomFound == 0) echo("SELECTED"); ?>><?php echo(L_SET_10); ?></OPTION>
						<OPTION VALUE="0" <?php if($T == 0 && $DefaultRoomFound == 0) echo("SELECTED"); ?>><?php echo(L_SET_11); ?></OPTION>
					</SELECT>
					<?php echo(" ".L_SET_12); ?> :
				</TD>
				<TD VALIGN="TOP" CLASS="ChatCell">
					<INPUT TYPE="text" NAME="R2" SIZE=11 MAXLENGTH=10 <?php if($DefaultRoomFound == 0 && $R != "") echo("VALUE=.htmlspecialchars(urldecode($R))."); ?> CLASS="ChatBox" onChange="reset_R2();">
				</TD>
			</TR>
			<?php
		}
		?>
		</TABLE>
		<P CLASS="ChatP2">
		<?php echo(L_SET_13." "); ?>
		<INPUT TYPE="submit" VALUE="<?php echo(L_SET_14); ?>" CLASS="ChatBox">
		</P>
	</TD>
</TR>
</TABLE>
<SPAN CLASS="ChatCopy" dir="LTR">
<a href="http://www.phpheaven.net/">&copy;</a>
</SPAN>
</FORM>
</CENTER>

</TD></TR></TABLE>

<?php
} // end of the layout function
?>
