<?php
// File: english.lang.php
// Original file by Nicolas Hoizey <nhoizey@phpheaven.net>

// extra header for charset
$Charset = "iso-8859-1";

// medium font size in pt.
$FontSize = 10;

// welcome page
define("L_TUTORIAL", "Tutorial");

define("L_WEL_1", "Messages are deleted after");
define("L_WEL_2", "hours and usernames after");
define("L_WEL_3", "minutes...");
// spatiallink:
define("L_CUR_1", "Users :");
define("L_CUR_2", "");
define("L_CUR_3", "Users currently in Chat");
define("L_CUR_4", "users in private rooms");

define("L_SET_1", "");
define("L_SET_2", "Username");
define("L_SET_3", "the number of messages to display");
define("L_SET_4", "the timeout between each update");
define("L_SET_5", "Select a chat room...");
define("L_SET_6", "default rooms");
define("L_SET_7", "Make your choice...");
define("L_SET_8", "public rooms created by users");
define("L_SET_9", "create your own");
define("L_SET_10", "public");
define("L_SET_11", "private");
define("L_SET_12", "room");
define("L_SET_13", "");
define("L_SET_14", "Enter Chat");

define("L_SRC", "is freely available on");

define("L_SECS", "seconds");
define("L_MIN", "minute");
define("L_MINS", "minutes");

// registration stuff:
define("L_REG_1", "Password");
define("L_REG_1r", "[If You Are Registered]");
define("L_REG_2", "Account Management");
define("L_REG_3", "Register");
define("L_REG_4", "Edit Profile");
define("L_REG_5", "Delete User");
define("L_REG_6", "User registration");
define("L_REG_7", "Password");
define("L_REG_8", "Email");
define("L_REG_9", "You have successfully registered.");
define("L_REG_10", "Back");
define("L_REG_11", "Editing");
define("L_REG_12", "Modifying User Information");
define("L_REG_13", "Deleting User");
define("L_REG_14", "Login");
define("L_REG_15", "Log In");
define("L_REG_16", "Change");
define("L_REG_17", "Your profile was successfully updated");
define("L_REG_18", "You have been kicked out of the room by the Moderator.");
define("L_REG_19", "Do you really want to be removed?");
define("L_REG_20", "Yes");
define("L_REG_21", "You have been successfully removed");
define("L_REG_22", "No");
define("L_REG_25", "Close");
define("L_REG_30", "firstname");
define("L_REG_31", "lastname");
define("L_REG_32", "WEB");
define("L_REG_33", "Show Email");
define("L_REG_34", "Editing User Profile");
define("L_REG_35", "Administration");
define("L_REG_36", "Spoken Languages");
define("L_REG_37", "Fields with a <span class=\"error\">*</span> must be completed.");
define("L_REG_39", "The room you were in has been removed by the Administrator.");
define("L_REG_45", "Gender");
define("L_REG_46", "Male");
define("L_REG_47", "Female");

// e-mail validation stuff
define("L_EMAIL_VAL_1", "Your settings to enter the chat");
define("L_EMAIL_VAL_2", "Welcome to our chat server.");
define("L_EMAIL_VAL_Err", "Internal error, please contact the Administrator: <a href=\"mailto:%s\">%s</a>.");
define("L_EMAIL_VAL_Done", "Your password has been sent to the <BR>email address you provided");

// admin stuff
define("L_ADM_1", "%s is no longer the Moderator for this room.");
define("L_ADM_2", "You are no longer a registered user.");

// error messages
define("L_ERR_USR_1", "This username is already in use");
define("L_ERR_USR_2", "You must chose a username");
define("L_ERR_USR_3", "This username is registered. Please type your correct password or select another username.");
define("L_ERR_USR_4", "You typed an incorrect password");
define("L_ERR_USR_5", "You must type your username");
define("L_ERR_USR_6", "You must type your password");
define("L_ERR_USR_7", "You must type your email");
define("L_ERR_USR_8", "You must type a correct email address");
define("L_ERR_USR_9", "This username is already in use");
define("L_ERR_USR_10", "Wrong username or password");
define("L_ERR_USR_11", "You must be the Administrator");
define("L_ERR_USR_12", "You are the Administrator, so you cannot be removed.");
define("L_ERR_USR_13", "To create your own room you must be registered.");
define("L_ERR_USR_14", "You must be registered before chatting.");
define("L_ERR_USR_15", "You must type your full name.");
define("L_ERR_USR_16", "Username cannot contain spaces, commas or backslashes (\\).");
define("L_ERR_USR_17", "This room doesn't exist, and you are not allowed to create one.");
define("L_ERR_USR_18", "Banished word found in your username.");
define("L_ERR_USR_19", "You cannot be in more than one room at the same time.");
define("L_ERR_USR_20", "You have been banished from this room or from the chat.");
define("L_ERR_ROM_1", "Room's name cannot contain commas or backslashes (\\).");
define("L_ERR_ROM_2", "Banished word found in the room's name you want to create.");
define("L_ERR_ROM_3", "This room already exists as a public one.");
define("L_ERR_ROM_4", "Invalid room name.");

// users frame or popup
define("L_EXIT", "Exit");
define("L_DETACH", "Detach");
define("L_EXPCOL_ALL", "Expand/Collapse All");
define("L_CONN_STATE", "Connection state");
define("L_CHAT", "Chat");
define("L_USER", "user");
define("L_USERS", "users");
define("L_NO_USER", "No user");
define("L_ROOM", "room");
define("L_ROOMS", "rooms");
define("L_EXPCOL", "Expand/Collapse room");
define("L_BEEP", "Beep/no beep at user entrance");
define("L_PROFILE", "Display profile");
define("L_NO_PROFILE", "No profile");

// input frame
define("L_HLP", "Help");
define("L_BAD_CMD", "This is not a valid command!");
define("L_ADMIN", "%s is already the Administrator!");
define("L_IS_MODERATOR", "%s is already the Moderator!");
define("L_NO_MODERATOR", "Only the Moderator of this room can use this command.");
define("L_MODERATOR", "%s is now the Moderator for this room.");
define("L_NONEXIST_USER", "User %s isn't in the current room.");
define("L_NONREG_USER", "User %s isn't registered.");
define("L_NONREG_USER_IP", "His IP is: %s.");
define("L_NO_KICKED", "User %s is the Moderator or the Administrator and can't be kicked away.");
define("L_KICKED", "User %s has successfully been kicked away.");
define("L_NO_BANISHED", "User %s is the Moderator or the Administrator and can't be banished.");
define("L_BANISHED", "User %s has successfully been banished.");
define("L_SVR_TIME", "Server Time: ");
define("L_NO_SAVE", "No message to save!");
define("L_NO_ADMIN", "Only the Administrator can use this command.");
define("L_ANNOUNCE", "ANNOUNCE");
define("L_INVITE", "%s requests that you join her/him into the <a href=\"#\" onClick=\"window.parent.runCmd('%s','%s')\">%s</a> room.");
define("L_INVITE_REG", " You have to be registered to enter this room.");
define("L_INVITE_DONE", "Your invitation has been sent to %s.");
define("L_OK", "Send");

// help popup
define("L_HELP_TIT_1", "Chat Smilies");
define("L_HELP_TIT_2", "Text Formating For Messages");
define("L_HELP_FMT_1", "You can put bold, italic or underlined text in messages by encasing the applicable sections of your text with either the &lt;B&gt; &lt;/B&gt;, &lt;I&gt; &lt;/I&gt; or &lt;U&gt; &lt;/U&gt; tags.<BR>For example, &lt;B&gt;this text&lt;/B&gt; will produce <B>this text</B>.");
define("L_HELP_FMT_2", "To create a hyperlink for email or URL in your message, simply type the corresponding address without any tag. The hyperlink will be created automatically.");
define("L_HELP_TIT_3", "Commands");
define("L_HELP_USR", "user");
define("L_HELP_MSG", "message");
define("L_HELP_ROOM", "room");
define("L_HELP_CMD_0", "{} represents a required setting, [] an optional one");
define("L_HELP_CMD_1a", "Set number of messages to show. Minimum and default are 5.");
define("L_HELP_CMD_1b", "Reload the messages frame and display the n latest messages, minimum and default are 5.");
define("L_HELP_CMD_2a", "Modify messages list refresh delay (in seconds).<BR>If n is not specified or less than 3, toggles between no refresh and 10s refresh.");
define("L_HELP_CMD_2b", "Modify messages and users lists refresh delay (in seconds).<BR>If n is not specified or less than 3, toggles between no refresh and 10s refresh.");
define("L_HELP_CMD_3", "Inverts messages order.");
define("L_HELP_CMD_4", "Join another room, creating it if it doesn't exist and if you're allowed to.<BR>n equals 0 for private and 1 for public, default to 1 if not specified.");
define("L_HELP_CMD_5", "Leave the chat after displaying an optional message.");
define("L_HELP_CMD_6", "Avoid diplaying messages from a user if name is specified.<BR>Set ignoring off for a user when name and - both are specified, for all users when - is but not name.<BR>With no option, this command pops up a window that shows all ignored names.");
define("L_HELP_CMD_7", "Recall the previous text typed (command or message).");
define("L_HELP_CMD_8", "Show/Hide time before messages.");
define("L_HELP_CMD_9", "Kick away user from the chat. This command can only be used by the Moderator.");
define("L_HELP_CMD_10", "Send a private message to the specified user.");
define("L_HELP_CMD_11", "Show information about specified user.");
define("L_HELP_CMD_12", "Popup window for editing user profile.");
define("L_HELP_CMD_13", "Toggles notifications of user entrance/exit for the current room.");
define("L_HELP_CMD_14", "Allow the Administrator or the Moderator(s) of the current room to promote another registered user to the Moderator for the same room.");
define("L_HELP_CMD_15", "Clear the messages frame and show only the last 5 messages.");
define("L_HELP_CMD_16", "Save the last n messages (notifications ones excluded) to an HTML file. If n is not specified, all available messages will be taken into account.");
define("L_HELP_CMD_17", "Allow the Administrator to send an announcement to all users in all chat rooms.");
define("L_HELP_CMD_18", "Invite a user chatting in another room to join the room you are in.");
define("L_HELP_CMD_19", "Allow the Moderator of a room or the Administrator to \"banish\" a user from the room for a time defined by the Administrator.<BR>The later can banish a user chatting in an other room than the one he is into and use the '<B>&nbsp;*&nbsp;</B>' setting to banish \"for ever\" a user from the chat as the whole.");
define("L_HELP_CMD_20", "Describe what you're doing without referring to yourself.");

// messages frame
define("L_NO_MSG", "There is currently no message...");
define("L_TODAY_DWN", "The messages that have been sent today start below");
define("L_TODAY_UP", "The messages that have been sent today start above");

// message colors
$TextColors = array(	"Black" => "#000000",
				"Red" => "#FF0000",
				"Green" => "#009900",
				"Blue" => "#0000FF",
				"Purple" => "#9900FF",
				"Dark red" => "#990000",
				"Dark green" => "#006600",
				"Dark blue" => "#000099",
				"Maroon" => "#996633",
				"Aqua blue" => "#006699",
				"Carrot" => "#FF6600");

// ignored popup
define("L_IGNOR_TIT", "Ignored");
define("L_IGNOR_NON", "No ignored user");

// whois popup
define("L_WHOIS_ADMIN", "Administrator");
define("L_WHOIS_MODER", "Moderator");
define("L_WHOIS_USER", "User");

// Notification messages of user entrance/exit
define("L_ENTER_ROM", "%s enters this room");
define("L_EXIT_ROM", "%s exits from this room");
?>