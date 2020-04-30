<?php
#Application name: PhpCollab
#Status page: 2
#Path by root: ../includes/files_types.php

function file_info_type($extension) {
global $type;
switch ($extension){

case "doc":$type = "doc.gif";break;
case "mdb":$type = "mdb.gif";break;
case "ppt":$type = "ppt.gif";break;
case "xls":$type = "xls.gif";break;

case "pdf":$type = "pdf.gif";break;
case "ai":$type = "ai.gif";break;
case "eps":$type = "ai.gif";break;
case "ttf":$type = "ttf.gif";break;

case "gif":$type = "gif.gif";break;
case "jpg":$type = "jpg.gif";break;
case "png":$type = "png.gif";break;
case "psd":$type = "psd.gif";break;

case "txt":$type = "txt.gif";break;

case "js":$type = "js.gif";break;

case "htm":$type = "htm.gif";break;
case "html":$type = "htm.gif";break;

case "php":$type = "php.gif";break;
case "php3":$type = "php.gif";break;

case "zip":$type = "zip.gif";break;
case "rar":$type = "rar.gif";break;

default:$type = "fic.gif";
}
return $type;
}
?>