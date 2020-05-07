<?php
if( !is_file( "config.php" ) ){
    // running directly, not from index.php
    chdir( ".." );
}
require_once("config.php");
require_once("include/database/PearDatabase.php");
require_once("cache/custom_fields/custom_fields_def.php");
require_once("include/modules.php");
require_once("include/utils.php");

if( preg_match( "/^3\.0/", $sugar_config['sugar_version'] ) && !(isset($force_run) && ($force_run == true) ) ){
    print( "Already running 3.0.  Aborting." );
    exit();
}

// store errors in an array for later processing
function log_error($label, $str) {
	global $errors;

	if ($label == "insert_meta_data" || $label == "create_custom_table") {
		die("A fatal error occured during your custom field upgrade:<br><i>{$str}</i>");
	}

	$errors[] = $str;
}

function queue_query($label, $mod, $fieldnum, $query, $select) {
	global $queries, $query_count;

	$queries[$query_count]['label'] = $label;
	$queries[$query_count]['mod'] = $mod;
	$queries[$query_count]['fieldnum'] = $fieldnum;
	$queries[$query_count]['query'] = $query;
	$queries[$query_count]['select'] = $select;

	$query_count++;
}

$db =& new PearDatabase();
$errors = array();
$queries = array();
$query_count = 0;
$custom_table_fields = array("field0", "field1", "field2", "field3", "field4", "field5", "field6", "field7", "field8", "field9");
$custom_table_fields = array_flip($custom_table_fields);

$beans = array_flip($beanList);
if (count($beans) != count($beanList)) {
	echo "Exception: unable to flip module list";
	exit();
}

if (empty($custom_fields_def)) {
	echo "No custom fields!";
	return;
}

if (is_file("cache/dynamic_fields/modules.php") && !unlink("cache/dynamic_fields/modules.php")) {
	echo "Please remove the <b>cache/dynamic_fields/modules.php</b> file from your Sugar installation before continuing";
	exit();
}
// for each module...
foreach ($custom_fields_def as $mod => $fields) {
	echo "<b>{$mod}</b>: " . count($fields) . " field(s)<br>";

	switch ($mod) {
		case "Case":
			$mod = "aCase";
			break;
		case "ProductTypes":
			$mod = "ProductType";
			break;
	}

	require_once($beanFiles[$mod]);
	$dummy = new $mod();

	$mod_table = $dummy->table_name;
	$custom_table = "{$mod_table}_cstm";
	unset($dummy);

	$table_fields = array();

	// for each field in the given module...
	for ($fieldnum = 0; $fieldnum < count($fields); $fieldnum++) {

		$set_num = floor($fieldnum / 10);

		$meta_data['custom_module'] = $beans[$mod];
		$meta_data['name'] = $fields[$fieldnum]['name'];
		$meta_data['id'] = $meta_data['custom_module'] . $meta_data['name'];
		$meta_data['label'] = str_replace("MOD.", "", $fields[$fieldnum]['label']);  // we need to remove the MOD. prefix on labels for correct lookup.
		$meta_data['data_type'] = ($fields[$fieldnum]['type'] == "char") ? "varchar" : $fields[$fieldnum]['type'];
		$meta_data['required_option'] = "optional";
		$meta_data['default_value'] = "";
		$meta_data['deleted'] = 0;
		$meta_data['ext1'] = ($fields[$fieldnum]['type'] == "enum") ? $fields[$fieldnum]['options'] : "";
		$meta_data = array_map("mysqli_escape_string", $meta_data);
		$meta_query = "INSERT INTO fields_meta_data (id, name, label, custom_module, data_type, max_size, required_option, default_value, deleted, ext1, ext2, ext3) VALUES ('{$meta_data['id']}', '{$meta_data['name']}', '{$meta_data['label']}', '{$meta_data['custom_module']}', '{$meta_data['data_type']}', '255', '{$meta_data['required_option']}', '{$meta_data['default_value']}', '{$meta_data['deleted']}', '{$meta_data['ext1']}', '', '')";
		$select = "SELECT id FROM fields_meta_data WHERE id = '{$meta_data['id']}'";
		queue_query("insert_meta_data", $mod, $fieldnum, $meta_query,$select);

		$table_fields[] = $fields[$fieldnum]['name'];
	}

	$table_fields_dedup = array_unique($table_fields);

	// create custom table
	$table_query = "CREATE TABLE IF NOT EXISTS {$custom_table} ( `id_c` VARCHAR(36) NOT NULL,";
	$added_fields = array();
	for ($i = 0; $i < count($table_fields); $i++) {
		if (isset($table_fields_dedup[$i])) {
			if(!isset($added_fields[$table_fields_dedup[$i]])){
				$table_query .= " `{$table_fields_dedup[$i]}` VARCHAR(255),";
				$added_fields[$table_fields_dedup[$i]] = '1';
			}
		}
	}
	$table_query .= " PRIMARY KEY ( `id_c` ) )";
	queue_query("create_custom_table", $mod, -1, $table_query, false);

	$all_records_res = $db->query("SELECT id FROM {$mod_table}");
	while ($record = $db->fetchByAssoc($all_records_res)) {
		$record_query = "INSERT INTO {$custom_table} (id_c) VALUES ('{$record['id']}')";
		$select_query = "SELECT id_c FROM {$custom_table} WHERE id_c = '{$record['id']}'";
		queue_query("insert_blank_record", $mod, -1, $record_query, $select_query);
	}

	$empty = 0;

	$custom_records_res = $db->query("SELECT custom_fields.* FROM custom_fields JOIN {$mod_table} ON ({$mod_table}.id = custom_fields.bean_id)");
	while ($custom_record = $db->fetchByAssoc($custom_records_res)) {
		$custom_data = array();
		$custom_update_query = "UPDATE {$custom_table} SET ";
		for ($i = 0; $i < 10 && (10 * $custom_record['set_num'] + $i) < count($fields); $i++) {
			if (!empty($custom_record['field' . $i])) {
				$custom_data[$fields[($custom_record['set_num'] * 10) + $i]['name']] = $custom_record['field' . $i];
			}
		}

		if (empty($custom_data)) {
			$empty++;
			continue;
		}

		$custom_data = array_map("mysqli_escape_string", $custom_data);

		$add_comma = FALSE;
		foreach ($custom_data as $k => $v) {
			if ($add_comma) {
				$custom_update_query .= ", ";
			}
			else {
				$add_comma = TRUE;
			}

			$custom_update_query .= "{$k} = '{$v}'";
		}

		$custom_update_query .= " WHERE id_c = '{$custom_record['bean_id']}'";
		queue_query("update_blank_record", $mod, -1, $custom_update_query, false);
	}
}

echo "<br>";

$fp = fopen("queries.log", "wb");
for ($i = 0; $i < count($queries); $i++) {
	$rowcount = 0;

	if(!$queries[$i]['select']){
		$row = false;
	}else{
		$result = $db->query($queries[$i]['select']);
		$rowcount = $db->getRowCount($result);

	}
	if(!$rowcount){
		$db->query($queries[$i]['query']) or log_error($queries[$i]['label'], "MySQL error: {$queries[$i]['label']} -- {$queries[$i]['mod']} -- {$queries[$i]['fieldnum']} -- {$queries[$i]['query']} -- " . mysqli_error($varconnect));
		fwrite($fp, $queries[$i]['query'] . "\n");
	}else{
		echo 'Record Already Exists <br>';
	}
}
fclose($fp);

for ($i = 0; $i < count($errors); $i++) {
	echo "<b>Error</b>: {$errors[$i]}<br>";
}

?>

