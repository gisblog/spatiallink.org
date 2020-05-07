<?php
/* browser interoperability */
if (strstr($_SERVER["HTTP_USER_AGENT"], "MSIE")) {
	print "<span class=\"br_both\"></span>";
} else {
	print "<br clear=\"all\" />";
}
/* done */
?>