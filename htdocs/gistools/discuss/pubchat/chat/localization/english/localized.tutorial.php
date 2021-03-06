<?php
// File : english.tutorial.php
// Original file by Sharif Islam <mislam@students.uiuc.edu> & Jessica Gibson <oram@uiuc.edu>
// & Dean Collins <joelford@pacbell.net>

// Get the names and values for vars sent by the script that called this one
if (isset($HTTP_GET_VARS))
{
	while(list($name,$value) = each($HTTP_GET_VARS))
	{
		$$name = $value;
	};
};
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>

<HEAD>
<TITLE>English Tutorial for <?php echo(APP_NAME." - ".APP_VERSION); ?></TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<STYLE>
A.topLink
{
	text-decoration: underline;
	color: #0000C0;
}

A.topLink:hover, A.topLink:active
{
	color: #FF9900;
	text-decoration: none;
	font-weight: 800;
}

.redText
{
	font-weight: 800;
	color: #FF0000;
}
</STYLE>
</HEAD>

<BODY BGCOLOR="#CCCCFF">
<!-- Remove this � in translation files -->
<?php
if(isset($NoTranslation)) echo($NoTranslation."\n");
?>
<!-- End of the � to be removed in translation files -->

<P><A NAME="top"></P>
<TABLE BORDER="5" CELLPADDING="5">
<TR>
	<TD><FONT SIZE="+2">Contents of this Tutorial</FONT></TD>
</TR>
</TABLE><BR>

<P CLASS="redText">
Warning: Netscape users must define their language as the default encoding or else each character in messages will be replace by a '?'.<BR>
This can be done as follows: View/CharacterSet/your language Auto-Detect, then View/CharacterSet/SetDefault.
</P>

<?php
if (C_MULTI_LANG == "1") 
{
	?>
	<A HREF="#language" CLASS="topLink">Choosing a language</A><BR>
	<?php
}
?>
<A HREF="#login" CLASS="topLink">Logging into the Chat</A><BR>
<A HREF="#register" CLASS="topLink">Registering</A><BR>
<A HREF="#modProfile" CLASS="topLink">Modifying<?php if (C_SHOW_DEL_PROF == "1") echo("/deleting"); ?> your profile</A><BR>
<?php
if (C_VERSION == "2") 
{
	?>
	<A HREF="#create_room" CLASS="topLink">Creating a room</A><BR>
	<?php
};
if ($Ver == "H") 
{
	?>
	<A HREF="#connection_state" CLASS="topLink">Understanding connection state</A><BR>
	<?php
};
?>
<A HREF="#sending" CLASS="topLink">Sending a Message</A><BR>
<A HREF="#users_list" CLASS="topLink">Understanding the users list</A><BR>
<A HREF="#exit" CLASS="topLink">Leaving the chat room</A><BR>
<A HREF="#users_popup" CLASS="topLink">Knowing who is chatting without being logged in</A><BR>
<P>
<A HREF="#customize" CLASS="topLink">Customizing the Chat View</A><BR>
<P>
<A HREF="#commands" CLASS="topLink">Features and Commands:</A><BR>
&nbsp&nbsp&nbsp&nbsp<A HREF="#help" CLASS="topLink">Help command</A><BR>
<?php
if (C_USE_SMILIES == "1")
{
	?>
	&nbsp&nbsp&nbsp&nbsp<A HREF="#smilies" CLASS="topLink">Graphical smilies</A><BR>
	<?php
};
if (C_HTML_TAGS_KEEP != "none")
{
	?>
	&nbsp&nbsp&nbsp&nbsp<A HREF="#text" CLASS="topLink">Text Formatting</A><BR>
	<?php
};
?>
&nbsp&nbsp&nbsp&nbsp<A HREF="#invite" CLASS="topLink">Invite an user to join your current chat room</A><BR>
&nbsp&nbsp&nbsp&nbsp<A HREF="#changeroom" CLASS="topLink">Changing from one chat room to another</A><BR>
&nbsp&nbsp&nbsp&nbsp<A HREF="#private" CLASS="topLink">Private Messages</A><BR>
&nbsp&nbsp&nbsp&nbsp<A HREF="#actions" CLASS="topLink">Actions</A><BR>
&nbsp&nbsp&nbsp&nbsp<A HREF="#ignore" CLASS="topLink">Ignoring other Users</A><BR>
&nbsp&nbsp&nbsp&nbsp<A HREF="#whois" CLASS="topLink">Getting Public Information about other Users</A><BR>
<?php
if (C_SAVE != "0")
{
	?>
	&nbsp&nbsp&nbsp&nbsp<A HREF="#save" CLASS="topLink">Save messages</A><BR>
	<?php
};
?>
<P>
<A HREF="#moderator" CLASS="topLink">Special commands for moderators and/or the administrator:</A><BR>
&nbsp&nbsp&nbsp&nbsp<A HREF="#announce" CLASS="topLink">Send an announcement</A><BR>
&nbsp&nbsp&nbsp&nbsp<A HREF="#kick" CLASS="topLink">Kicking an user</A><BR>
<?php
if (C_BANISH != "0")
{
	?>
	&nbsp&nbsp&nbsp&nbsp<A HREF="#banish" CLASS="topLink">Banish an user</A><BR>
	<?php
};
?>
&nbsp&nbsp&nbsp&nbsp<A HREF="#promote" CLASS="topLink">Promote an user to moderator</A><BR>
<P>
<HR>
<HR>


<?php
if (C_MULTI_LANG == "1") 
{
	?>
	<P>
	<FONT SIZE="+1"><A NAME="language"><B>Choosing a language:</B></A></FONT>
	<P>
	You may choose a language among those in which <?php echo(APP_NAME); ?> have been translated by clicking on one of the flags at the start page. In the example bellow, an user selects the French language:
	<P ALIGN="center">
	<IMG SRC="images/tutorials/flags.gif" HEIGHT="44" WIDTH="424" ALT="Flags for language selection">
	<BR><P ALIGN="right"><A HREF="#top">Back to the top</A></P>
	<HR>
	<?php
}
?>

<P>
<FONT SIZE="+1"><A NAME="login"><B>Login:</B></A></FONT>
<P>
If you have already registered, simply log in by entering your username and password. Then select which chat room you would like to enter and press the Chat button.<BR>
<?php
if (C_REQUIRE_REGISTER == "1")
{
	?>
	<P>
	Else you have to <A HREF="#register">register</A> first.
	<?php
}
else
{
	?>
	<P>
	Else you can <A HREF="#register">register</A> first or simply enter a room but your nick won't be reserved (an other user may use the same nick once you have logged out).
	<?php
}
?>
<BR><P ALIGN="right"><A HREF="#top">Back to the top</A></P>
<HR>

<P>
<FONT SIZE="+1"><A NAME="register"><B>To Register:</B></A></FONT> 
<P>
If you have not yet registered<?php if (C_REQUIRE_REGISTER == "0") echo("and would like to"); ?>, please choose the registration option. A small pop-up window will appear.
<P>	
<UL>
	<LI>First, create a username<?php if (!C_EMAIL_PASWD) echo(" and a password"); ?> for yourself by typing it into the appropriate boxes. The username you choose is the name that will be automatically displayed in the chat room. It cannot contain spaces, commas or backslashes (\).
<?php if (C_NO_SWEAR == "1") echo(" It can not contain \"swear words\"."); ?>
	<LI>Second, please enter your first name, last name, and your email address. In order to register to chat, all of this information must be provided. The gender information is optional. 
	<LI>If you have a homepage, you may enter the URL into the box.
	<LI>The language field may help other users in future discussions. They will know which language(s) you understand.
	<LI>Lastly, if you wish to allow your email address to be viewed by other participants, please check the box next to "show e-mail in public information". If
you do not want your e-mail address to be viewable, leave the box unchecked.
	<LI>Then, press the Register button and your account will be created. If you wish to stop at any time without registering, press the Close button.
</UL>
<P>
<A NAME="modProfile"></A>Of course, registered users would be able to modify<?php if (C_SHOW_DEL_PROF == "1") echo("/delete"); ?> their own profile by clicking on the appropriate <?php echo((C_SHOW_DEL_PROF == "0" ? "link" : "links")); ?>.<BR>
<BR><P ALIGN="right"><A HREF="#top">Back to the top</A></P>
<P>
<HR>

<?php
if (C_VERSION == "2") 
{
	?>
	<P>
	<FONT SIZE="+1"><A NAME="create_room"><B>To create a room:</B></A></FONT> 
	<P>
	Registered users can create rooms. Private ones can only be accessed by users who know their name and will never been displayed except for users that are in.<BR>
	<P>
	Room's name cannot contain comma or backslash (\).<?php if (C_NO_SWEAR == "1") echo(" It can no more contains \"swear words\"."); ?>
	<BR><P ALIGN="right"><A HREF="#top">Back to the top</A></P>
	<P>
	<HR>
	<?php
};
if ($Ver == "H")
{
	?>
	<P>
	<FONT SIZE="+1"><A NAME="connection_state"><B>Understanding connection state:</B></A></FONT> 
	<P>
	A sign representing your connection state is dipslayed at the top-right corner of the screen. It may take three forms :
	<P>	
	<UL>
		<LI><IMG SRC="images/connectOff.gif" WIDTH=13 HEIGHT=13 BORDER=0 ALT="No connection"> when no connection is required ;
		<LI><IMG SRC="images/connectOn.gif" WIDTH=13 HEIGHT=13 BORDER=0 ALT="Connecting"> when there is a connection in progress ;
		<LI><IMG SRC="images/connectError.gif" WIDTH=13 HEIGHT=13 BORDER=0 ALT="Connection failed"> when a connection failed.
	</UL>
	<P>
	In the third case, clicking on the red "button" will launch a new connection attempt.
	<BR><P ALIGN="right"><A HREF="#top">Back to the top</A></P>
	<P>
	<HR>
	<?php
};
?>

<P>
<FONT SIZE="+1"><A NAME="sending"><B>Sending messages:</B></A></FONT> 
<P>
To post a message to the chat room, type your text into the text box in the lower left corner and then press the Enter/Return key to send it. Messages from all users scroll in the chat box.<BR>
<?php if (C_NO_SWEAR == "1") echo("Note that \"swear words\" are skipped from messages."); ?>
<P>
You may change the color of the text of your messages by choosing a new color from the list of choices to the right of the text box.
<BR><P ALIGN="right"><A HREF="#top">Back to the top</A></P>
<P>
<HR>

<P>
<FONT SIZE="+1"><A NAME="users_list"><B>Understanding the users list (not for users popup window):</B></A></FONT> 
<P>
<OL>
	Two basic rules have been defined for the users list:<BR>
	<LI>a little icon that shows gender is displayed before the nick of a registered user (clicking on it will launch the <A HREF="#whois">whois popup</A> for this user), while unregistered users have nothing but blank spaces displayed before their nick;<BR>
	<LI>the nick of the administrator or of a moderator is italicized.
</OL>
<P><I>For example</I>, from the snapshot bellow you can conclude that:
<TABLE BORDER=0 CELLSPACING=10>
<TR>
	<TD>
		<IMG SRC="images/tutorials/usersList.gif" WIDTH=128 HEIGHT=145 BORDER=0 ALT="users list">
	</TD>
	<TD>
	<UL>
		<LI>Nicolas is the admin or one of the moderators of the phpMyChat room;<BR><BR>
		<LI>alien (whose gender is unknown), Jezek2 and Caridad are registered users with no extra "power" for the phpMyChat room;<BR><BR>
		<LI>lolo is a simple unregistered user.
	</UL>
	</TD>
</TR>
</TABLE>
<BR><P ALIGN="right"><A HREF="#top">Back to the top</A></P>
<P>
<HR>

<P>
<FONT SIZE="+1"><A NAME="exit"><B>Leaving the chat room:</B></A></FONT>
<P>
To exit the chat, simply click once on "Exit". Alternatively, you may also enter the one of the following commands into your text box:<BR>
/exit<BR>
/bye<BR>
/quit
These commands may be completed with a message to be sent before you leave the chat room.
<I>For example :</I> /quit CU soon!
<P>
will sent the message "CU soon!" in the main frame then log you out.

<BR><P ALIGN="right"><A HREF="#top">Back to the top</A></P>
<P>
<HR>

<P>
<FONT SIZE="+1"><A NAME="users_popup"><B>Knowing who is chatting without being logged in:</B></A></FONT> 
<P>
You may click on the link that shows the number of connected users at the start page, or, if you are chatting, click on the image <IMG SRC="images/popup.gif" WIDTH=13 HEIGHT=13 BORDER=0 ALT="Users popup"> at the top-right of the screen to open a independent window that will display the list of connected users, and the rooms they are in, in near real time.<BR>
The title of this window contains the usernames, if they are less than three, the numbers of users and opened rooms else.
<P>
Clicking on the <IMG SRC="images/sound.gif" WIDTH=13 HEIGHT=13 BORDER=0 ALT="Beeps"> icon at the top of this popup will enable/disable beeping sounds at user entrance.
<BR><P ALIGN="right"><A HREF="#top">Back to the top</A></P>
<P>
<HR>
<HR>


<P>
<FONT SIZE="+1"><A NAME="customize"><B>Customizing the Chat View:</B></A></FONT>
<P>
There are many different ways to customize the look of the Chat. To change settings, simply type the appropriate command into your text box and press the Enter/Return key.
<P>
<UL>
	<?php
	if ($Ver == "H")
	{
		?>
		<LI>The <B>Clear command</B> allows you to clean the main chat screen and display the last 5 messages sent on your screen.<BR>Type "/clear" without quotes.
		<P>
		<?php
	}
	else
	{
		?>
		<LI>The <B>Order command</B> allows you to toggle between having new messages appear at the top of the screen or at the bottom.<BR>Type "/order" without quotes.
		<P>
		<?php
	};
	?>
	<LI>The <B>Notify command</B> allows you to toggle on or off the option of seeing the notices when other users enter or exit the chat room. By default this option is <?php echo(C_NOTIFY ? "on" : "off"); ?> and the notices <?php echo(C_NOTIFY ? "will" : "won't"); ?> be seen.<BR>Type "/notify" without quotes.
	<P>
	<LI>The <B>Timestamp command</B> allows you to toggle on or off the option of seeing the time the message was posted before each message and the server time in the status bar. By default this option is <?php echo(C_SHOW_TIMESTAMP ? "on" : "off"); ?>.<BR>Type "/timestamp" without quotes.
	<P>
	<LI>The <B>Refresh command</B> allows you to adjust the rate at which the posted message are refreshed on your screen. The default rate is currently <?php echo(C_MSG_REFRESH); ?> seconds. To change the rate type "/refresh n" without quotes where n is the time in seconds of the new refresh rate.
	<P>
	<I>For example:</I> /refresh 5
	<P>
	will change the rate to 5 seconds. *Beware, if n is set to less than 3, the refresh is reset not to refresh at all (usefull when you want to read lots of old messages without being disturbed)!*
	<P>
	<?php
	if ($Ver == "L")
	{
		?>
		<LI>The <B>Show command</B> allows you to adjust the number of messages viewable on your screen. To change the default number, type "/show n" without quotes where n is the number of viewable messages.
		<P>
		<I>For example:</I> /show 50
		<P>
		will cause the 50 newest messages to be viewable on your screen. If all of the messages cannot be displayed within the message box, a scroll bar will appear on the right side of the message box.</UL>
		<?php
	}
	else
	{
		?>
		<LI>The <B>Show and Last commands</B> allow you to clean the screen and display the last <I>n</I> messages sent on your screen. Type "/show n" or "/last n" without quotes where n is the number of viewable messages.
		<P>
		<I>For example:</I> /show 50 or /last 50
		<P>
		will clean the message frame and cause the 50 newest messages to be viewable on your screen. If all of the messages cannot be displayed within the message box, a scroll bar will appear on the right side of the message box.</UL>
		<?php
	};
	?>
	<BR><P ALIGN="right"><A HREF="#top">Back to the top</A></P>
	<P>
</UL>
<HR>
<HR>


<P>
<FONT SIZE="+2"><A NAME="commands"><B><U>Features and Commands</U></B></A></FONT> 
<P>

<FONT SIZE="+1"><A NAME="help"><B>Help command:</B></A></FONT>
<P>
Once inside a chat room, you can lauch a help popup by clicking on the <IMG SRC="images/helpOff.gif" WIDTH=15 HEIGHT=15 BORDER=0 ALT="?"> image that sits just before the message box. You can also type the <B>"/help" or "/?" commands</B> in the message box.
<BR><P ALIGN="right"><A HREF="#top">Back to the top</A></P>
<P>
<P>
<HR>

<?php
if (C_USE_SMILIES == "1")
{
	include("./lib/smilies.lib.php");
	$Nb = count($SmiliesTbl);
	$ResultTbl = Array();
	DisplaySmilies($ResultTbl,$SmiliesTbl,$Nb,"tutorial");
	unset($SmiliesTbl);
	?>
	<FONT SIZE="+1"><A NAME="smilies"><B>Smilies:</B></A></FONT>
	<P>You may have graphical smilies inside your messages. See bellow the code you have to type into a message to obtain each one of these similes.
	<P ALIGN="center">
	<TABLE BORDER=0 CELLPADDING=3 CELLSPACING=5>
	<?php
	$i = "0";
	$Nb = count($ResultTbl);
	while($i < $Nb)
	{
		if ($i > 0) echo("\t");
		echo("<TR VALIGN=\"BOTTOM\">\n");
		echo("$ResultTbl[$i]");
		echo("\t</TR>\n\t<TR>\n");
		$i++;
		echo("$ResultTbl[$i]");
		echo("\t</TR>\n");
		$i++;
	};
	unset($ResultTbl);
	?>
	</TABLE>
	<P>
	<I>For example</I>, sending the text "Hi Jack :)" without quote will display the message Hi Jack <IMG SRC="images/smilies/smile1.gif" WIDTH=15 HEIGHT=15 ALT=":)"> in the main frame.
	<BR><P ALIGN="right"><A HREF="#top">Back to the top</A></P>
	<P>
	<HR>
	<?php
};

if (C_HTML_TAGS_KEEP != "none")
{
	?>
	<FONT SIZE="+1"><A NAME="text"><B>Text Formatting:</B></A></FONT>
	<P>
	Text can be bolded, italicized or underlined by encasing the applicable sections of your text with either the &LT;B&GT; &LT;/B&GT, &LT;I&GT; &LT;/I&GT; or &LT;U&GT; &LT;/U&GT HTML tags.
	<P>
	<I>For example</I>, &LT;B&GT;this text&LT;/B&GT; will produce <B>this text</B>.
	<P>
	To create a hyperlink for an e-mail address or URL, type the address (without any HTML tags). The hyperlink will be created automatically.
	<BR><P ALIGN="right"><A HREF="#top">Back to the top</A></P>
	<P>
	<P>
	<HR>
	<?php
};
?>

<P>
<FONT SIZE="+1"><A NAME="invite"><B>Invite someone to join your current chat room:</B></A></FONT>
<P>
You can use the <B>invite command</B> to invite an user to join the room you are chatting in.
<P>
<I>For example:</I> /invite Jack 
<P>
will send a private message to Jack suggesting him to join you in your current chat room. This message contains the name of the target room and this name appears as a link.
<P>
Note that you can put more than one username in the invite command (eg "/invite Jack,Helen,Alf"). They must be splitted by comma (,) without spaces.
<BR><P ALIGN="right"><A HREF="#top">Back to the top</A></P>
<P>
<HR>

<P>
<FONT SIZE="+1"><A NAME="changeroom"><B>Changing rooms:</B></A></FONT>
<P>
The list to the right of the screen provides a list of chat rooms and the users who are currently in that room. To leave the room you are in and move into one of those rooms, simply click once on the name of that room. Empty rooms do not appear on this list. You may move into an empty room by typing the <B>command "/join #roomname"</B> without quotes.
<P>
<I>For example:</I> /join #RedRoom 
<P>
will move you into the RedRoom.
<?php
if (C_VERSION == "2")
{
	echo(C_REQUIRE_REGISTER == "0" ? "<P>If you're a registered user, you" : "<BR><P>You");
	?>
	 may also create a new room with this same command. But then you have to specify its type: 0 stands for private, 1 for public (default value).
	<P>
	<I>For example:</I> /join 0 #MyRoom 
	<P>
	will create a new private room (assuming a public one has not already been created with that name) called MyRoom and move you into it.
	<P>
	Room's name cannot contain comma or backslash (\).<?php if (C_NO_SWEAR == "1") echo(" It can no more contains \"swear words\"."); ?>
	<?php
}
?>
<BR><P ALIGN="right"><A HREF="#top">Back to the top</A></P>
<P>
<HR>

<P>
<FONT SIZE="+1"><B>Modifying his own profile inside the chat:</B></FONT>
<P>
The <B>Profile command</B> creates a separate pop-up window in which you can edit your user profile and modify it except your nick and password (you have to use the link at the start page to do this).<BR>Type /profile
<BR><P ALIGN="right"><A HREF="#top">Back to the top</A></P>
<P>
<HR>

<P>
<FONT SIZE="+1"><B>Recalling the last message or command you have submitted:</B></FONT>
<P>
The <B>! command</B> recalls the last message or command you have submitted.<BR>Type /!
<BR><P ALIGN="right"><A HREF="#top">Back to the top</A></P>
<P>
<HR>

<P>
<FONT SIZE="+1"><B>Responding to a specific user:</B></FONT>
<P>
Clicking once on the name of another user from the list (to the right of the screen) will cause their "username>" to appear in your text box. This feature allows you to easily direct a public message to a user, perhaps in response to something he or she has posted above.
<BR><P ALIGN="right"><A HREF="#top">Back to the top</A></P>
<P>
<HR>

<P>
<FONT SIZE="+1"><A NAME="private"><B>Private messages:</B></A></FONT>
<P>
To send a private message to another user currently in your chat room, type the <B>command "/msg username messagetext" or "/to username messagetext"</B> without quotes.
<P>
<I>For example, where Jack is the username:</I> /msg Jack Hi there, how are you?
<P>
The message will appear to Jack and yourself, but no other users will see the message.
<P>
Note that clicking on the nick of a message sender in the main frame will automatically add this command to the input field for messages.
<BR><P ALIGN="right"><A HREF="#top">Back to the top</A></P>
<P>
<HR>

<P>
<FONT SIZE="+1"><A NAME="actions"><B>Actions:</B></A></FONT>
<P>
To describe what you're doing you can use the <B>command "/me action"</B> without quotes.
<P>
<I>For example:</I> If Jack sends the message "/me is smoking a cigarette" the message frame will shown "<B>* Jack</B> is smoking a cigarette".
<BR><P ALIGN="right"><A HREF="#top">Back to the top</A></P>
<P>
<HR>

<P>
<FONT SIZE="+1"><A NAME="ignore"><B>Ignoring other users:</B></A></FONT>
<P>
To ignore all of the posts by another user, type the <B>command "/ignore username"</B> without quotes.
<P>
<I>For example:</I> /ignore Jack
<P>
From that time onward, no messages by the user Jack will display on your screen.
<P>
To have a list of the users whose messages are ignored, just type the <B>command "/ignore"</B> without quotes. 
<P>
To resume display of message by an ignored user, type the <B>command "/ignore - username"</B> without quotes where "-" is a hyphen. <P> 
<P>
<I>For example:</I> /ignore - Jack 
<P>
Now all the messages by Jack posted during the current chat session will be displayed on your screen, including those messages posted by Jack before you typed this command.
If you don't specify an username after the hyphen, your "ignored list" will be cleaned.
<P>
Note that you can put more than one username in the ignore command (eg "/ignore Jack,Helen,Alf" or "/ignore - Jack,Alf"). They must be splitted by comma (,) without spaces.
<BR><P ALIGN="right"><A HREF="#top">Back to the top</A></P>
<P>
<HR>

<P>
<FONT SIZE="+1"><A NAME="whois"><B>Getting information about other Users:</B></A></FONT>
<P>
To see public information about another user, type the <B>command "/whois username"</B> without quotes.
<P>
<I>For Example:</I> /whois Jack
<P>
where 'Jack' is the username. This command will create a separate pop-up window that will display the publically available information about that user.
<BR><P ALIGN="right"><A HREF="#top">Back to the top</A></P>
<P>
<HR>

<?php
if (C_SAVE != "0")
{
	?>
	<P>
	<FONT SIZE="+1"><A NAME="save"><B>Save messages:</B></A></FONT>
	<P>
	To export messages (notification ones excluded) to a local HTML file, type the <B>command "/save n"</B> without quotes.
	<P>
	<I>For Example:</I> /save 5
	<P>
	where '5' is the number of messages to save. If n is not specified, all available messages sent to the current room will be taken into account.
	<BR><P ALIGN="right"><A HREF="#top">Back to the top</A></P>
	<P>
	<HR>
	<?php
};
?>
<HR>


<P>
<FONT SIZE="+2"><A NAME="moderator"><B><U>Commands for the adminstrator and/or moderators only</U></B></A></FONT> 

<P>
<FONT SIZE="+1"><A NAME="announce"><B>Send an announcement:</B></A></FONT>
<P>
The administrator may send an announcement to all users whatever the the room they are chatting into with the <B>announce command</B>.
<P>
<I>For example: /announce The chat system is going down for maintenance tonight at 8pm.</I>
<BR><P ALIGN="right"><A HREF="#top">Back to the top</A></P>
<P>
<HR>

<P>
<FONT SIZE="+1"><A NAME="kick"><B>Kicking an user:</B></FONT>
<P>
Moderators can kick an user and the administrator can kick an user or a moderator with the <B>kick command</B>. Except for the administrator, the user to be kicked must be in the current room.
<P>
<I>For example</I>, if Jack is the name of the user to kick away:</I> /kick Jack</I>
<BR><P ALIGN="right"><A HREF="#top">Back to the top</A></P>
<P>
<HR>

<?php
if (C_BANISH != "0")
{
	?>
	<P>
	<FONT SIZE="+1"><A NAME="banish"><B>Banish an user:</B></A></FONT>
	<P>
	Moderators can banish an user and the administrator can banish an user or a moderator with the <B>ban command</B>.<BR>
	The administrator can banish an user from another room than the one he is chatting into. He can also banish an user forever and from the chat as a whole with the '<B>&nbsp;*&nbsp;</B>' setting that must be inserted before the nick of the user to be banished.
	<P>
	<I>For example</I>, if Jack is the name of the user to banish : <I>/ban Jack</I> or <I>/ban * Jack</I>
	<BR><P ALIGN="right"><A HREF="#top">Back to the top</A></P>
	<P>
	<HR>
	<?php
};
?>

<P>
<FONT SIZE="+1"><A NAME="promote"><B>Promote an user to moderator:</B></A></FONT>
<P>
Moderators and the administrator can promote an other user to moderator with the <B>promote command</B>.
<P>
<I>For example</I>, if Jack is the name of the user to promote:<I> /promote Jack</I>
<P>
Only the administrator can access the opposite feature (reduce a moderator to simple user) at a dedicated page. There is no command to do it.
<BR><P ALIGN="right"><A HREF="#top">Back to the top</A></P>
<P>


</BODY>
</HTML>