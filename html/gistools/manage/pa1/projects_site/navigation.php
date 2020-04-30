<?php
#Application name: PhpCollab
#Status page: 0
?>

<!-- Navigation Start -->
<form method="post" name="login" action="<? echo $pathMantis ?>login.php?url=<? echo "http://{$HTTP_HOST}{$REQUEST_URI}" ?>&id=<? echo $projectSession ?>&PHPSESSID=<? echo $PHPSESSID; ?>">
<input type="hidden" name="f_username" value="<?php echo $loginSession; ?>">
<input type="hidden" name="f_password" value="<?php echo $passwordSession; ?>">
<!-- Navigation End -->