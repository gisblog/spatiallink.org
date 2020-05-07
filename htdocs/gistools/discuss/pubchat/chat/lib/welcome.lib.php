<?php
// Define the welcome message to be used when the 'C_WELCOME' switch is set
// to 1 inside the 'chat/config/config.lib.php' file

switch ($L)
{
   case 'argentinian_spanish':   // Para usuarios en Español para Argentina
		define('WELCOME_MSG', "Bienvenidos a nuestro chat. El objetivo es conocernos, intercambiar ideas y <I>especialmente, pasar un momento agradable</I>.");
		break;
	case 'danish':	// For danish users
		define('WELCOME_MSG', "Velkommen til chatten. Oprethold venligst en sober tone mens du chatter: <i>forsøg at være venlig, rar og imødekommende</i>.");
		break;
	case 'english':	// For english users
		define('WELCOME_MSG', "Chat");
		break;
	case 'french':	// For french users
		define('WELCOME_MSG', "Bienvenu(e) sur notre chat. N\'oubliez pas les <I>règles de politesse élémentaire</I> au cours de vos discussions.");
		break;
	case 'japanese':	// For japan users
		define('WELCOME_MSG', "¥Á¥ã¥Ã¥È¤Ø¤è¤¦¤³¤½¡ª Â¾¤Î¥æ¡¼¥¶¡¼¤ËÌÂÏÇ¤ò¤«¤±¤º¤Ë¡¢³Ú¤·¤ó¤Ç¤¤¤Ã¤Æ¤¯¤À¤µ¤¤¤Í¡£");
		break;
	case 'spanish':	// Para usuarios en Español
		define('WELCOME_MSG', "Bienvenidos a nuestro chat. El objetivo es conocernos, intercambiar ideas y <I>especialmente, pasar un momento agradable</I>.");
	default:		// When there is no translation for the language of the user
		define('WELCOME_MSG', "Welcome to our chat. Please obey net etiquette while chatting: <I>try to be pleasant and polite</I>.");
};

?>