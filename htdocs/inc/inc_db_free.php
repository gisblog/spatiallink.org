<?php
/* db free varresult: note that mysqli_free_result($varresult) should NOT be used if insert $varquery does NOT return a set, or returns a NULL set */
if (mysqli_num_rows($varresult) == 0) {
	// $varquery does not return data, or it was NOT a SELECT
} else {
	mysqli_free_result($varresult);
}
/* done */
?>