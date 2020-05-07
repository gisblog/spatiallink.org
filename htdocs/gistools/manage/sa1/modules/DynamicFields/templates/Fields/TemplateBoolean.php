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
require_once('modules/DynamicFields/templates/Fields/TemplateField.php');
class TemplateBoolean extends TemplateField{
	var $default_value = '0';
	function get_html_edit(){
		$this->prepare();
		return "<input type='hidden' name='". $this->name. "' value='0'><input type='checkbox' name='". $this->name. "' id='".$this->name."'  value='1' {". strtoupper($this->name). "_CHECKED}>";
	}

	function get_html_detail(){
		return "<input type='checkbox' name='". $this->name. "' id='".$this->name."'  value='1' disabled {". strtoupper($this->name). "_CHECKED}>";	
	}
	
	function get_html_list(){
		if(isset($this->bean)){
			$name = $this->bean->object_name . '.'. $this->name;
		}else{
			$name = $this->name;	
		}
		
			return "<input type='checkbox' name='". $name. "' id='". $name. "'   value='1' disabled {". strtoupper($name). "_CHECKED}>";		
	}
	
	function get_xtpl_edit(){
		$name = $this->name;
		if(isset($this->bean->$name)){

			$returnXTPL = array();
			if($this->bean->$name == '1'){
				$returnXTPL[$this->name . '_checked'] = 'checked';
			}
			return $returnXTPL;	
		}
		return '';
	}
	

	
	
	function get_xtpl_search(){
		
		if(!empty($_REQUEST[$this->name])){
			$returnXTPL = array();
			if($_REQUEST[$this->name] == '1'){
				$returnXTPL[$this->name . '_checked'] = 'checked';
			}
			return $returnXTPL;	
		}
		return '';
	}
	

	function get_db_type(){
		return " BOOL ";	
	}	

	function get_field_def(){
		return array('required'=>$this->is_required(),"name"=>$this->name, "vname"=>$this->label,"len"=>$this->max_size,'rname'=>$this->name, 'table'=>$this->bean->table_name . '_cstm',  'custom_type'=>'bool', 'type'=>'relate', 'source'=>'custom_fields');	
	}
	
	
	function get_xtpl_detail(){
		return $this->get_xtpl_edit();
	}
	function get_xtpl_list(){
		return $this->get_xtpl_edit();	
	}
	
	
	
}


?>
