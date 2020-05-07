<?php
// register_globals cheat code
//HTTP_GET_VARS
while (list($key, $val) = @each($HTTP_GET_VARS)) {
       $GLOBALS[$key] = $val;
}
//HTTP_POST_VARS
while (list($key, $val) = @each($HTTP_POST_VARS)) {
       $GLOBALS[$key] = $val;
}
//_FILES
while (list($key, $val) = @each($_FILES)) {
       $GLOBALS[$key] = $val;
}
//$HTTP_SESSION_VARS
while (list($key, $val) = @each($HTTP_SESSION_VARS)) {
       $GLOBALS[$key] = $val;
}
//$HTTP_SERVER_VARS
while (list($key, $val) = @each($HTTP_SERVER_VARS)) {
       $GLOBALS[$key] = $val;
}

$auth = $_GET["auth"];
$loginForm = $_POST["loginForm"];
$passwordForm = $_POST["passwordForm"];
$file = $_GET["file"];
global $postnukeIntegration;
$postnukeIntegration = "true";
if ($file == "") {
include("modules/PhpCollab/general/login.php");
} else if (file_exists("modules/PhpCollab/".$file.".php")) {
include("modules/PhpCollab/".$file.".php");
} else {
include("modules/PhpCollab/general/login.php");
}
function phpcollab_user_main() {
return true;
}
?>