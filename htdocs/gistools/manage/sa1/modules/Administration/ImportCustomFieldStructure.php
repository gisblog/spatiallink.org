
<?PHP
	if(empty($_FILES)){
echo $mod_strings['LBL_IMPORT_CUSTOM_FIELDS_DESC'];
echo <<<EOQ
<br>
<br>
<form enctype="multipart/form-data" action="index.php" method="POST">
   	<input type='hidden' name='module' value='Administration'>
   	<input type='hidden' name='action' value='ImportCustomFieldStructure'>
   {$mod_strings['LBL_IMPORT_CUSTOM_FIELDS_STRUCT']}: <input name="sugfile" type="file" />
    <input type="submit" value="Import Structure" class='button'/>
</form>
EOQ;

	
	}else{
	require_once('modules/EditCustomFields/FieldsMetaData.php');
	$fmd =& new FieldsMetaData();
	$fmd->db->query("Truncate $fmd->table_name");
	echo 'Dropping - Custom Fields Meta Data Information<br>';
	$lines = file($_FILES['sugfile']['tmp_name']);
	$cur = array();
	foreach($lines as $line){

		if(trim($line) == 'DONE'){
			echo 'Adding Custom Field Meta Data Information - '.$cur['custom_module'] . ' ' . $cur['name'] . '<br>';
			$fmd->db->query("INSERT INTO $fmd->table_name (" . implode(array_keys($cur), ',') . ") VALUES ('" . implode(array_values($cur), "','") . "')");
			$cur = array();
		}else{

			$ln = explode(':::', $line ,2); 
			if(sizeof($ln) == 2){
				$cur[trim($ln[0])] = trim($ln[1]);
			}
		}	
		
	}
	$result = $fmd->db->query("SELECT * FROM $fmd->table_name");
	echo 'Total Custom Fields :' . $fmd->db->getAffectedRowCount($result) . '<br>';
	include('modules/Administration/UpgradeFields.php');
	}
?>
