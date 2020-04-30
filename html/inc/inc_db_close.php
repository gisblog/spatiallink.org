<?php
// db close varconnect: mysql_close() is NOT necessary, as non-persistent open links are automatically closed at the end of script execution. 
mysql_close($varconnect);
// done
?>