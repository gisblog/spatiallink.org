<?PHP 
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version
 * 1.1.3 ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an "AS IS" basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied.  See the License
 * for the specific language governing rights and limitations under the
 * License.
 *
 * All copies of the Covered Code must include on each user interface screen:
 *    (i) the "Powered by SugarCRM" logo and
 *    (ii) the SugarCRM copyright notice
 * in the same form as they appear in the distribution.  See full license for
 * requirements.
 *
 * The Original Code is: SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) 2004 SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
 require_once('include/database/PearDatabase.php');

 global $db;
 if(!isset($db)){
 	$db =& new PearDatabase();
 }
 $result =& $db->query( 'SELECT * FROM fields_meta_data WHERE deleted = 0 ORDER BY custom_module');
 $modules = array();
 /*
  * get the real field_meta_data
  */
 while($row = $db->fetchByAssoc($result)){
 	$the_modules = $row['custom_module'];
 	if(!isset($modules[$the_modules])){
 		$modules[$the_modules] = array();	
 	}
 	$modules[$the_modules][$row['name']] = $row['name'];
 }
 	
 $simulate = false;
 if(!isset($_REQUEST['run'])){
 	$simulate = true;
 	echo "SIMULATION MODE - NO CHANGES WILL BE MADE EXCEPT CLEARING CACHE";
 
 }
 
 	echo '<br>Clearing deleted custom field structure<br>';
 	if(!$simulate)$db->query( 'DELETE FROM fields_meta_data WHERE deleted = 1');
 		
 

 foreach($modules as $the_module=>$fields){
 	$class_name = $beanList[$the_module];
 	echo "<br><br>Scanning $the_module <br>";
		if($class_name == 'aCase'){
			$class_file = 'Case';	
		}else{
			$class_file = $class_name;
		}
		require_once("modules/$the_module/$class_file.php");
		$mod =& new $class_name();
		if(!$simulate){
			if($mod->custom_fields->createCustomTable()){
				echo "Creating Custom Table for $the_module<br>";	
			}
		}
		$result =& $db->query("DESCRIBE $mod->table_name" . "_cstm");
		if($result){
		while($row = $db->fetchByAssoc($result)){
			
			
			$col = $row['Field'];
			$type = $row['Type'];
			$the_field = $mod->custom_fields->getField($col);
				
				if(!isset($fields[$col]) && $col != 'id_c'){
					if(!$simulate)$db->query("ALTER TABLE $mod->table_name" . "_cstm DROP COLUMN $col");
					unset($fields[$col]);
					echo "Dropping Column $col from $mod->table_name" . "_cstm for module $the_module<br>";
				}	else{
					if($col != 'id_c'){
					if(trim($the_field->get_db_type()) != trim($type) && !empty($the_field->name)){
						
					echo "Fixing Column Type for $col changing $type to ". $the_field->get_db_type() . "<br>";
					if(!$simulate)$db->query($the_field->get_db_modify_alter_table($mod->table_name . '_cstm'));		
					}
					}
					
					unset($fields[$col]);
				}
			
		}
		}else{
			echo "Need to create custom table for $the_module<br>";	
		}
		
			
			echo sizeof($fields) . " field(s) missing from $mod->table_name" . "_cstm<br>";
			foreach($fields as $field){
				echo "Adding Column $field to $mod->table_name" . "_cstm<br>";
				if(!$simulate)$mod->custom_fields->add_existing_custom_field($field);
			}
			
		
			
		
		
		
	
	}
	
	if(file_exists('cache/dynamic_fields/modules.php')){
		echo '<br>Clearing Cache<br>';
		 unlink('cache/dynamic_fields/modules.php');
	}
	echo '<br>Done<br>';
	if($simulate){
		echo '<a href="index.php?module=Administration&action=UpgradeFields&run=true">Execute non-simulation mode</a>';	
	}
		
		
 
 
 	
 
 
 
 ?>
