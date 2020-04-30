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
require_once('include/utils/file_utils.php');
class DynamicField {
	var $db;
	var $bean;
	var $avail_fields = array();
	var $module;
	var $modules = array();
	function DynamicField($module=''){
		static $db;
		if(!isset($db)){
			$db =& new PearDatabase();		
		}
		$this->db =& $db;
		$this->module = $module;
		if(empty($this->module) && !empty($_REQUEST['module'])){
			$this->module = $_REQUEST['module'];	
		}

	}
	var $table_name = 'fields_meta_data';
	
	function setup(&$bean){
		$this->bean =& $bean;
		
		if(isset( $this->bean->module_dir)){
			$this->module = $this->bean->module_dir;
		}
		
		$this->loadCustomModulesList();
		$this->getAvailableFields();
		$this->populateBean();
		
		
		
		
	}
	

	/*
		THIS CREATES CUSTOM DATA FIELDS FOR MODULES
	*/
	function createCustomTable(){
		$result = $this->db->query("SHOW TABLES LIKE '".$this->bean->table_name."_cstm'");

		if ($this->db->getRowCount($result) == 0){

			$query = 'CREATE TABLE '.$this->bean->table_name.'_cstm ( ';
			$query .='id_c char(36) NOT NULL';
			$query .=', PRIMARY KEY ( id_c ) )';

			$this->db->query($query);
			$this->add_existing_custom_fields();
			//$this->populate_existing_ids();
			return true;
		}
		return false;	
	
	}
	function add_existing_custom_fields(){
		$this->avail_fields = array();
		$this->getAvailableFields(true);
		foreach($this->avail_fields as $name=>$data){
			$field =& $this->getField($name);
			$query = $field->get_db_add_alter_table($this->bean->table_name . '_cstm');
			$this->db->query($query);
		}	
	}	
	
	
	function populate_existing_ids(){
		$result = $this->db->query("SELECT id FROM " . $this->bean->table_name);
		while($row = $this->db->fetchByAssoc($result)){
			$this->db->query("INSERT INTO ". $this->bean->table_name . "_cstm (id_c) VALUES ('". $row['id'] . "')");	
		}	
	}
	
	/*
	 * get the join for joining the custom table
	 * 
	 * */
	 
	 function getJOIN(){
	 		if(!array_key_exists($this->module, $this->modules)){
				return false;
			}
	 		$result = $this->db->query("SHOW TABLES LIKE '".$this->bean->table_name."_cstm'");

			if ($this->db->getRowCount($result) > 0){
	 			return array('select'=>" , ". $this->bean->table_name. "_cstm.*", 'join'=> " LEFT JOIN " .$this->bean->table_name. "_cstm ON " .$this->bean->table_name. ".id = ". $this->bean->table_name. "_cstm.id_c ");
			}
			return false;
	 }
	
	/*
	loads fields into the bean 
	*/
	
	function retrieve(){
		if(!array_key_exists($this->module, $this->modules)){
				return false;
		}
			
		$query = "SELECT * FROM ".$this->bean->table_name."_cstm WHERE id_c='".$this->bean->id."'";
		$result = $this->db->query($query);
		$row = $this->db->fetchByAssoc($result);
		
		if($row){
		foreach($row as $name=>$value){
			if(isset($this->avail_fields[$name])){
				$bean->$name = $value;
				
			}
				
		}
		}
		$this->populateBean();
				
	}
	
	
	
	/*
		Save Fields From The Bean
	*/
	function save($isUpdate){

		if(array_key_exists($this->module, $this->modules) && isset($this->bean->id)){
		
			if($isUpdate){
				$query = "UPDATE ". $this->bean->table_name. "_cstm SET ";
			}
			$queryInsert = "INSERT INTO ". $this->bean->table_name. "_cstm (id_c";
			$values = "('".$this->bean->id."'";	
			$first = true;
			foreach($this->avail_fields as $name=>$field){
			
					if(isset($this->bean->$name)){
						if($isUpdate){
							if($first){
								$query .= " $name='".$this->bean->$name."'";
								$first = false;
							}else{
								$query .= " ,$name='".$this->bean->$name."'";
							}
						}	
						
						$queryInsert .= " ,$name";
						$values .= " ,'". $this->bean->$name . "'";	
					}
						$this->clearBean($name);
				}
			
			if($isUpdate){
				$query.= " WHERE id_c='" . $this->bean->id ."'";
			}
				$queryInsert .= " ) VALUES $values )";	
			
			if(!$isUpdate){
				$this->db->query($queryInsert);	
			}else{
				
				$result =& $this->db->query($query);
				if($this->db->getAffectedRowCount($result) == 0){
					$this->db->query($queryInsert);
				}
			}
		}
			
			
}
	
	
	function getType($name){
		if(!isset($this->avail_fields[$name]) && isset($this->avail_fields[$this->getDBName($name)])){
			$name = $this->getDBName($name);	
		}
	
		if(isset($this->avail_fields[$name])){
			return 	$this->avail_fields[$name]['data_type'];
		}
		return '';
	}
	
	function getField($name, $type=''){

		if(!isset($this->avail_fields[$name]) && isset($this->avail_fields[$this->getDBName($name)])){
			$name = $this->getDBName($name);	
		}
		if(empty($type)){
			if(isset($this->avail_fields[$name])){
				$type = $this->avail_fields[$name]['data_type'];
			}
		}
			
		include('modules/DynamicFields/FieldCases.php');
		
		if(isset($this->avail_fields[$name])){
			$field->set($this->avail_fields[$name]);	
		}else{
			$field->set($this->getFieldSetFromFieldDef($name));	
		}
		if(isset($this->bean)){
			$field->bean = & $this->bean;
		}
		
		return $field;
	}
	
	function getFieldLabelHTML($name, $view){
		$field =& $this->getField($name);
		$field->view = $view;
		return $field->get_html_label();	
	}
	
	
	function getFieldHTML($name, $view){
		$field =& $this->getField($name);
		$field->view = $view;
		return $field->get_html();
	}
	
	function getFieldXTPL($name, $view){
		$field =& $this->getField($name);
		$field->view = $view;
		return $field->get_xtpl($this->bean);
	}
	
	function getAllFieldsHTML($view){
		return $this->getAllFieldsView($view, 'html');	
	}
	
	function getAllFieldsXTPL($view){
		return $this->getAllFieldsView($view, 'xtpl'); 	
	}
	
	function getDBName($name){
		return preg_replace("/[^\w]+/","_",$name) . '_c';	
	}
	//only custom fields
	function getAllFieldsView($view, $type){
		if(!array_key_exists($this->module, $this->modules)){
			return array();
		}
		$results = array();
		if(empty($this->avail_fields)){
			$this->getAvailableFields();	
		}

		foreach($this->avail_fields as $name=>$value){
			$field =& $this->getField($name);
			$field->view = $view;
			switch(strtolower($type)){
				case 'xtpl':
					$results[$name] = array('xtpl'=>$field->get_xtpl($this->bean));
					break;
				case 'html':
					$results[$name] = array('html'=> $field->get_html(), 'label'=> $field->get_html_label());
					break;
				
			}
				
		}	
		return $results;
	}
	//this includes non-custom fields
	function getAllBeanFieldsView($view, $type){
		static $bad_types = array();
		if(!isset($this->bean)){
			return array();
		}
		$this->avail_fields = array();
		$this->getAvailableFields();	
		$results = array();
		foreach($this->bean->field_defs as $name=>$value){
			$ftype = $value['type'];
			if(!isset($bad_types[$ftype]) ){
				
				if($ftype == 'text'){
					$ftype = 'textarea';
				}

				if(isset($this->avail_fields[$value['name']])){
					$ftype = $this->avail_fields[$value['name']]['data_type'];
				}
					
					
				$field =& $this->getField($value['name'], $ftype);
				$field->view = $view;
				switch(strtolower($type)){
					case 'xtpl':
						$results[$name] = array('xtpl'=>$field->get_xtpl($this->bean));
						break;
					case 'html':
						$results[$name] = array('html'=> $field->get_html(), 'label'=> $field->get_html_label());
						break;
					
				}
			}
				
		}	
		return $results;
	}
	
		function getFieldSetFromFieldDef($name){
		$set = array();
		if(isset($this->bean->field_defs[$name])){
			$set['name'] = $name;
			if(isset($this->bean->field_defs[$name]['vname'])){
				$set['label'] = $this->bean->field_defs[$name]['vname'];
			}else{
				$set['label'] = 'NO_LABEL';	
			}
			if(isset($this->bean->field_defs[$name]['len'])){
				$set['max_size'] = $this->bean->field_defs[$name]['len'];	
			}
			if(isset($this->bean->field_defs[$name]['required']) && $this->bean->field_defs[$name]['required']){
				$set['required_option'] = 'required';	
			}
			if(isset($this->bean->field_defs[$name]['default'])){
				$set['default_value'] = $this->bean->field_defs[$name]['default'];	
			}
			if(isset($this->bean->field_defs[$name]['options'])){
				$set['ext1'] = $this->bean->field_defs[$name]['options'];
			}
			
			
			
			
		}
		return $set;
	}
	
	function populateXTPL(&$xtpl, $view){
		if(!array_key_exists($this->module, $this->modules)){
				return false;
		}
		$results =& $this->getAllFieldsView($view, 'xtpl');
		foreach($results as $name=>$value){
			if(is_array($value['xtpl'])){
				foreach($value['xtpl'] as $xName=>$xValue){
					$xtpl->assign(strtoupper($xName), $xValue);	
				}	
			}else{
				$xtpl->assign(strtoupper($name), $value['xtpl']);	
			}
		}
			
	}
	
	function setWhereClauses(&$where_clauses){
		if(!array_key_exists($this->module, $this->modules)){
				return false;
		}
		foreach($this->avail_fields as $name=>$value){
			if(!empty($_REQUEST[$name])){
				array_push($where_clauses, $this->bean->table_name . "_cstm.$name LIKE '". PearDatabase::quote($_REQUEST[$name]). "%'");
			}
		}
		
	}
	function updateField($id, $values){
			if(empty($values)){
				return;
			}
			$query = "UPDATE fields_meta_data SET id='$id' ";
			foreach($values as $key=>$value){
				$query .= ",$key='$value' ";	
			}
			$query .= " WHERE id='$id'";
			$this->db->query($query);
			$this->cleanSaveToCache();
			$this->avail_fields = array();
			$this->getAvailableFields(true);
			$name = str_replace( $this->module, '', $id);
			$field =& $this->getField($name);
			if($field){
				$query = $field->get_db_modify_alter_table($this->bean->table_name . '_cstm');

				$this->db->query($query);	
			}
	}
	function dropField($name){
	
		$this->db->query("ALTER TABLE " . $this->bean->table_name . "_cstm DROP $name"); 	
	}
	
	
	function addField($name,$label='', $type='Text',$max_size='255',$required_option='optional', $default_value='', $ext1='', $ext2='', $ext3='' ){
		if(empty($label)){
				$label = $name;
		}
			
		$object_name = $this->module;
		$db_name = $this->getDBName($name);
		if(isset($this->avail_fields[$db_name])){
			return;
		}
		$this->createCustomTable();
		$label = $this->addLabel($label);
		$query = "INSERT INTO fields_meta_data (id, custom_module, name, label, data_type, max_size, required_option, default_value, ext1, ext2, ext3) VALUES ('$object_name$db_name', '$object_name', '$db_name', '$label', '$type', '$max_size', '$required_option', '$default_value', '$ext1', '$ext2', '$ext3')";
		$this->db->query($query);
		$this->cleanSaveToCache();
		if(!array_key_exists($this->module, $this->modules)){
			$this->createCustomTable();
			$this->saveCustomModulesList();
		}
	
		$this->avail_fields = array();
		$this->getAvailableFields(true);
		$field =& $this->getField($name);
		
		if($field){
			$query = $field->get_db_add_alter_table($this->bean->table_name . '_cstm');
			$this->db->query($query);	
		}
	}
	
	function add_existing_custom_field($name){
		$this->avail_fields = array();
		$this->getAvailableFields(true);
		$field =& $this->getField($name);
		
		if($field){
			$query = $field->get_db_add_alter_table($this->bean->table_name . '_cstm');
			$this->db->query($query);	
		}
	}
	
	function getAvailableFields($clean=false){
		if(!$clean){
			if(!array_key_exists($this->module, $this->modules)){
				$this->avail_fields = array();
				return $this->avail_fields;
			}
			if( $this->loadFromCache() &&  !empty($this->avail_fields)){
				return $this->avail_fields;
			}
		}
		$this->avail_fields = array();
		$query = "SELECT * FROM fields_meta_data WHERE custom_module='$this->module' AND deleted = 0";
		$result = $this->db->query($query);
		while($row = $this->db->fetchByAssoc($result)){
			$this->avail_fields[$row['name']] = $row;
		}
		
		$this->saveToCache();
		return $this->avail_fields;
			
	}
	
	function addLabel($label){
		global $current_language;
		$limit = 10;
		$count = 0;
		$field_key = $label;
		$curr_field_key = $this->getDBName($label);
		while( ! create_field_label($this->module, $current_language, $curr_field_key, $label) )
		{
			$curr_field_key = $field_key. "_$count";
			if ( $count == $limit)
			{
				sugar_die("cannot create field label");
			}
			$count++;
		}
		return $curr_field_key;	
	}
	

	
	function populateBean(){
		if(isset($this->bean->added_custom_field_defs) && $this->bean->added_custom_field_defs){
			return;	
		}
		foreach($this->avail_fields as $name=>$val){
			$field =& $this->getField($name);
			$this->bean->field_name_map[$name] = $field->get_field_def();
			$this->bean->column_fields[] = $name;
			$this->bean->list_fields[] = $name;
			if($this->bean->field_name_map[$name]['required']){
				$this->bean->required_fields[$name] = 1;
			}
			
		}
		
		$this->bean->added_custom_field_defs= true;
			
	}
	
	
	
	function clearBean($name){
			unset($this->bean->$name);
	}
	
	function cleanSaveToCache(){
		$this->deleteCache();
		$this->getAvailableFields();
		
	}
	
	
	
	function saveToCache(){
		$file = 'dynamic_fields/'. $this->module . '/fields.php';
		$file = create_cache_directory($file);
		$vardump = var_export($this->avail_fields, true);
		$fp = fopen($file, 'wb');
		fwrite($fp,"<?PHP\n\$avail_fields=".  $vardump . "\n?>");
		fclose($fp);
		
	}
	
	function deleteCache(){
		$file = 'cache/dynamic_fields/'. $this->module . '/fields.php';
		if(file_exists($file)){
			return unlink($file);
		}
		return true;
	}
	
	function loadFromCache(){
		$file = 'cache/dynamic_fields/'. $this->module . '/fields.php';
		if(file_exists($file)){
				include($file);
				$this->avail_fields =& $avail_fields;
				return true;
		}
		return false;
	}
	
	function loadCustomModulesList(){
		$file = 'cache/dynamic_fields/modules.php';
		$this->modules = array();
		if(file_exists($file)){
				include($file);
				$this->modules = $custom_modules;
				return true;
		}else{
			
			$this->saveCustomModulesList();
			 
			
		}
		
	}
	function saveCustomModulesList(){
		$query = 'SELECT DISTINCT custom_module FROM fields_meta_data';
		$result = $this->db->query($query);
		$modules = array();
		if($result){
			while($row = $this->db->fetchByAssoc($result)){
				$modules[$row['custom_module']] = $row['custom_module']; 	
			}
		}
		$this->modules = $modules;
		$file = 'dynamic_fields/modules.php';
		$file = create_cache_directory($file);
		$vardump = var_export($modules, true);
		$fp = fopen($file, 'wb');
		fwrite($fp,"<?PHP\n\$custom_modules=".  $vardump . "\n?>");
		fclose($fp);
		return $modules;
	}
	
	
	
		
	

}
	
	
	
