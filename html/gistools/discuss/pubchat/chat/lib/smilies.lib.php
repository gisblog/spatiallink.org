<?
/* ------------------------------------------------------------------
	This library allows to transform text codes to graphical smilies.
	It is called by 'chat/input.php'.
   ------------------------------------------------------------------ */


// The table below define smilies code and associated gif names, width and height.
// You can add your own collection of smilies inside but be aware that codes may
// need to be slashed in some way because they are used as POSIX 1003.2 regular
// expressions (see the Check4Smilies function below). Moreover these codes are
// case sensitives.

$SmiliesTbl = Array(
	":\)"	=> array("smile1.gif", "15", "15"),
	":D"	=> array("smile2.gif", "15", "15"),
	":o"	=> array("smile3.gif", "15", "15"),
	":\("	=> array("smile4.gif", "15", "15"),
	";\)"	=> array("smile5.gif", "15", "15"),
	":p"	=> array("smile6.gif", "15", "15"),
	"8\)"	=> array("smile7.gif", "15", "15"),
	":\["	=> array("smile8.gif", "15", "15"),
	":kill:"	=> array("smile9.gif", "50", "15")
);

$MaxWidth = "50";		// Set the maximum width among similes
$MaxHeight = "15";		// Set the maximum height among similes


// ---- DO NOT MODIFY BELOW ----

// Slashes ' and " characters
function SpecialSlash(&$Str)
{
	return str_replace("\"","&quot;",str_replace("'","\\'",$Str));
}

// Replace smilies code by gif URLs in messages
function Check4Smilies(&$string,&$Table)
{
	$tmp_tbl = split("<a href|</a>", " ".$string." ");
	$i = "0";

	for (reset($tmp_tbl); $substring=current($tmp_tbl); next($tmp_tbl))
	{
		// $substring is one of the trailing spaces added above -> do nothing
		if($substring == " ")
		{
		}
		// $substring is not an HTTP link -> do the work for smilies
		elseif (($i % 2) == "0")
		{
			while(list($key, $prop) = each($Table))
			{
				$substring = ereg_replace($key, " <IMG SRC=\"images/smilies/$prop[0]\" WIDTH=$prop[1] HEIGHT=$prop[2] ALT=\"".str_replace("\"","&quot;", stripslashes($key))."\"> ", $substring);
			};
			$tmp_tbl[$i] = $substring;
		}
		// $substring is an HTTP link -> just restore HTML tags for links
		else
		{
			$tmp_tbl[$i] = "<a href".$substring."</a>";
		}
		$i++;
	};
	$string = trim(join("",$tmp_tbl));
	unset($tmp_tbl);
}

// Display smilies in the help popup and in the tutorials
function DisplaySmilies(&$ToDisplay,&$Table,&$TblSize,$Target)
{
	global $MaxWidth, $MaxHeight;

	$i = 0;
	$Str1 = "";
	$Str2 = "";
	$PerLines = floor(600/$MaxWidth);
	
	while(list($key, $prop) = each($Table))
	{
		$i++;
		if ($Target == "help") $Str1 .= "\t\t<TD ALIGN=\"CENTER\" WIDTH=$MaxWidth HEIGHT=$MaxHeight><A HREF=\"#\" onClick=\"smiley2Input('".SpecialSlash($key)."'); return false\"><IMG SRC=\"images/smilies/$prop[0]\" WIDTH=$prop[1] HEIGHT=$prop[2] BORDER=0 ALT=\"".str_replace("\"","&quot;", stripslashes($key))."\"></A></TD>\n";
		else $Str1 .= "\t\t<TD ALIGN=CENTER WIDTH=$MaxWidth HEIGHT=$MaxHeight><IMG SRC=\"images/smilies/$prop[0]\" WIDTH=$prop[1] HEIGHT=$prop[2] BORDER=0 ALT=\"".str_replace("\"","&quot;", stripslashes($key))."\"></TD>\n";
		$Str2 .= "\t\t<TD ALIGN=\"CENTER\" NOWRAP>".stripslashes($key)."</TD>\n";
		if (is_integer($i/$PerLines) || $i == $TblSize)
		{
			$ToDisplay[] = $Str1;
			$ToDisplay[] = $Str2;
			$Str1 = "";
			$Str2 = "";
		};
	};
};

?>