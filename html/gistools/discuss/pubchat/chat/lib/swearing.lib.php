<?
// This library allows to check for "swear words". Users cannot login or create a room with such words,
// in messages they are replaced by the "@#$*!" string or the one you choose. 
// Credit for this lib goes to Gustavo Iwamoto <iwamoto@zaz.com.br> and Fabiano R. Prestes <zoso@post.com>

function checkwords ($String, $TestOnly)
{

	// You can add the words you don't want users to use in the $BadWords array bellow. As an eregi
	// function is called to find them in strings, you may use valid POSIX 1003.2 regular expressions
	// (see second line of the array for an example).
	// Note that search is not case sensitive, except for special characters such as accentued ones.

	$BadWords = array (
				"shit",
				"fuck([[:alpha:]]*)"
	);

	$ReplaceString = "@#$*!";	// String that will replace "swear words"


	// Don't modify lines bellow 

	$Found = false;
	for (reset($BadWords); $ToFind = current($BadWords); next($BadWords))
	{
		$Found = eregi(addslashes($ToFind), $String);
		if ($Found)
		{
			if ($TestOnly)
			{
				break;
			}
			else
			{
				$String = eregi_replace(addslashes($ToFind), $ReplaceString, $String);
			};
		};
	};

	unset($BadWords);
	return ($TestOnly ? $Found : $String);
}

?>