<?php
#Application name: PhpCollab
#Status page: 0
#Path by root: ../browsecvs/header.php

//TY
if ($use_compression==1) ob_start("ob_gzhandler");

$themepath = "modules/themes/".$themes_list->get_dir($userdata['theme'])."/";
require_once($themepath."index.php");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title><?php echo $sitename; ?></title>
	<link href="<?php echo $themepath; ?>style.css" rel="stylesheet" type="text/css">
</head>
<?php
$theme->header();
?>
