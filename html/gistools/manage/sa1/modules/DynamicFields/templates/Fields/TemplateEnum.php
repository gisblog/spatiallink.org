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
require_once('modules/DynamicFields/templates/Fields/TemplateText.php');
require_once('include/utils/array_utils.php');
class TemplateEnum extends TemplateText{
	function get_html_edit(){
		$this->prepare();
		$xtpl_var = strtoupper( $this->name);
		return "<select name=\"". $this->name . "\">{OPTIONS_".$xtpl_var. "}</select>";
	}
	
	
	function get_xtpl_edit(){
		$name = $this->name;
		$value = '';
		if(isset($this->bean->$name)){
			$value = $this->bean->$name;
		}
		global $app_list_strings;
		$returnXTPL = array();
		$returnXTPL[strtoupper($this->name)] = $value;
		$returnXTPL[strtoupper('options_'.$this->name)] = get_select_options_with_id($app_list_strings[$this->ext1], $value);
		
		return $returnXTPL;	
		
		
	}
	

	
	
	function get_xtpl_search(){
		$searchFor = '';
		if(!empty($_REQUEST[$this->name])){
			$searchFor = $_REQUEST[$this->name];
		}
		global $app_list_strings;
		$returnXTPL = array();
		$returnXTPL[strtoupper($this->name)] = $searchFor;
		$returnXTPL[strtoupper('options_'.$this->name)] = get_select_options_with_id(add_blank_option($app_list_strings[$this->ext1]), $searchFor);
		return $returnXTPL;	

	}
	
	function get_db_type(){
		return " varchar($this->max_size)";	
	}
	


	function get_field_def(){
		return array('required'=>$this->is_required(),'source'=>'custom_fields',"name"=>$this->name,'options'=>$this->ext1, "vname"=>$this->label,"len"=>$this->max_size,'rname'=>$this->name, 'table'=>$this->bean->table_name . '_cstm',  'custom_type'=>'enum', 'type'=>'relate');	
	}
	
	
	function get_xtpl_detail(){
		$name = $this->name;
		if(isset($this->bean->$name)){
			global $app_list_strings;
			if(isset($app_list_strings[$this->ext1])){
				if(isset($app_list_strings[$this->ext1][$this->bean->$name])){
					return $app_list_strings[$this->ext1][$this->bean->$name];
				}
			}
		}
		return '';
	}
	
	
	
}


?>
