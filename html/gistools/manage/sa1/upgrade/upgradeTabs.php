<?php
if( !is_file( "config.php" ) ){
    // running directly, not from index.php
    chdir( ".." );
}
require_once("config.php");
require_once("include/database/PearDatabase.php");
require_once("include/modules.php");
require_once("include/utils.php");
require_once("modules/Users/User.php");
require_once("modules/MySettings/TabController.php");

if( preg_match( "/^3\.0/", $sugar_config['sugar_version'] ) ){
    print( "Already running 3.0.  Aborting." );
    exit();
}

function log_info($str) {
	global $info;

	$info[] = $str;
}

$current_user =& new User();
$current_user->retrieve(1);
$info = array();

$db = new PearDatabase();

$query = "SELECT id FROM users WHERE deleted ='0'";

$result = $db->query($query);

while($row = $db->fetchByAssoc($result))
{
	$user =& new User();
	$user->retrieve($row['id']);

	$controller =& new TabController();
		
	$tabs = $controller->get_old_tabs($user);
	
	echo "<br>Setting tab preferences for " . $user->user_name;
	echo "<br>";
/*	echo "display_tabs : <br>";
	print_r($tabs[0]);
	echo "<br>";
	echo "remove_tabs : <br>";
	print_r($tabs[1]);
*/
	
	$user->setPreference('display_tabs', $tabs[0]);
	$user->setPreference('remove_tabs', $tabs[1]);
	$user->setPreference('hide_tabs', array());
	$user->setPreference('tabs', array());
		  
}

/*
for ($i = 0; $i < count($info); $i++) {
	echo "<b>Info</b>: {$info[$i]}<br>";
}
*/

?>
