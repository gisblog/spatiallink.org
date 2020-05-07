<?php
// This script is an example of display, in another page of your website, the list or number of users connected to the chat. Lines below must be at the top of your file and completed according to your settings.
	
// Relative path from this page to your chat directory
$ChatPath = "chat/";

// HTML link to launch the chat (used by constants below)
$ChatLaunch = "<a href=\"chatmonitor.php\" target=\"_self\"></a>";
	
$ShowPrivate = "0";     
// 1 to display users even if they are in a private room, 0 else
	
$DisplayUsers = "1";    
// 0 to display only the number of connected users, 1 to display a list of users
	
define("NB_USERS_IN","User(s) are ".$ChatLaunch." at this time.");
// used if $DisplayUsers = 0
	
define("USERS_LOGIN","User(s) ".$ChatLaunch." at this time:");
// used if $DisplayUsers = 1
	
define("NO_USER","Nobody is chatting ".$ChatLaunch." at this time.");
	
require("./${ChatPath}/lib/connected_users.lib.php");
?>
<?php
display_connected($ShowPrivate,$DisplayUsers,($DisplayUsers ? USERS_LOGIN : NB_USERS_IN),NO_USER);
?>