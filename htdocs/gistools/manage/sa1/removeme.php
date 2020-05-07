<?PHP

include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('modules/Users/User.php');
require_once('include/modules.php');
require_once('include/utils.php');

$id = $_REQUEST['remove'];
$module = $_REQUEST['from'];
$class = $beanList[$module];
require_once($beanFiles[$class]);
$mod =& new $class();
$db =& new PearDatabase();

$id = $db->quote($id);

if(ereg('^[0-9A-Za-z\-]*$', $id)){
	$query = "UPDATE $mod->table_name SET email_opt_out='on' WHERE id ='$id'";
	if($db->query($query)){
		echo "*";
	}
}
echo "You have opted out of receiving emails."	

?>
