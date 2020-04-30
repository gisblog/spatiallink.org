<?php
include("admin_header.php");

function phpcollabConfig() {
	global $xoopsConfig, $xoopsModule, $xoops_newsConfig, $strings;
	xoops_cp_header();
	OpenTable();
	echo "todo";
	CloseTable();
}

function phpcollabConfigS() {
	global $xoopsConfig, $HTTP_POST_VARS;
}


switch($op){
	case "phpcollabConfig":
		phpcollabConfig();
		break;
	case "phpcollabConfigS":
		if (xoopsfwrite()) {
			phpcollabConfigS();
		}
		break;
	case "default":
	default:
		xoops_cp_header();
		OpenTable();
		echo " - <b><a href='index.php?op=phpcollabConfig'>Edit settings</a></b><br /><br />\n";
		CloseTable();
		break;
}
	xoops_cp_footer();
?>