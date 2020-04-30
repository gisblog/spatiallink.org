<?php
/* db free varresult: note that mysql_free_result($varresult) should NOT be used if insert $varquery does NOT return a set, or returns a NULL set */
if (mysql_num_rows($varresult) == 0) {
	// $varquery does not return data, or it was NOT a SELECT
} else {
	mysql_free_result($varresult);
}
/* done */
?>