<?php
// db close varconnect: mysqli_close() is NOT necessary, as non-persistent open links are automatically closed at the end of script execution. 
mysqli_close($varconnect);
// done
?>