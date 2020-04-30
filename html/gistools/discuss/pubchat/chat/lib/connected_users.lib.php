<?php
// Fix a security holes
if (!is_dir('./'.substr($ChatPath, 0, -1))) exit();

require("./${ChatPath}config/config.lib.php");
require("./${ChatPath}lib/database/".C_DB_TYPE.".lib.php");
require("./${ChatPath}lib/clean.lib.php");

function display_connected($Private,$Full,$String1,$String2)
{
	$List = "";

	if ($Private)
	{
		$query = "SELECT DISTINCT username, latin1 FROM ".C_USR_TBL;
	}
	else
	{
		$query = "SELECT DISTINCT u.username, u.latin1 FROM ".C_USR_TBL." u, ".C_MSG_TBL." m WHERE u.room = m.room AND m.type = 1 ORDER BY username";
	}

	$DbLink = new DB;
	$DbLink->query($query);
	$NbUsers = $DbLink->num_rows();

	if ($NbUsers > 0)
	{
		if ($Full)
		{
			echo($String1."<BR>");
			while(list($User,$Latin1) = $DbLink->next_record())
			{
				if ($Latin1) $User = htmlentities($User);
				$List .= ($List == "" ? $User : ", ".$User);
			}
			echo($List);
		}
		else
		{
			echo($NbUsers." ".$String1);
		}
	}
	else
	{
		echo($String2);
	}

	$DbLink->clean_results();
	$DbLink->Close();
}

?>