<?PHP
require_once('include/database/PearDatabase.php');
$db = new PearDatabase();
$result = $db->query('SELECT * FROM fields_meta_data WHERE deleted = 0');
$fields = array();
$str = '';
while($row = $db->fetchByAssoc($result)){
	foreach($row as $name=>$value){
		$str.= "$name:::$value\n";	
	}
	$str .= "DONE\n";
}
ob_get_clean();

header("Content-Disposition: attachment; filename=CustomFieldStruct.sugar");
header("Content-Type: text/txt; charset={$app_strings['LBL_CHARSET']}");
header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
header( "Cache-Control: post-check=0, pre-check=0", false );
header("Content-Length: ".strlen($str));
echo $str;
?>
