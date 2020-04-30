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

class TemplateField{
	/*
		The view is the context this field will be used in
		-edit
		-list
		-detail
		-search
	*/
	var $view = 'edit';
	var $name = '';
	var $label = '';
	var $id = '';
	var $size = '20';
	var $max_size = '255';
	var $required_option = 'optional';
	var $default_value = '';
	var $data_type = 'text';
	var $bean;
	
	/*
		HTML FUNCTIONS
	*/
	function get_html(){
		switch($this->view){
			case 'search':return $this->get_html_search();
			case 'edit': return $this->get_html_edit();
			case 'list': return $this->get_html_list();
			case 'detail': return $this->get_html_detail();
				
		}		
	}
	function set($values){
		foreach($values as $name=>$value){
			$this->$name = $value;	
		}	
	}
	
	function get_html_edit(){
		return 'not implemented';	
	}
	
	function get_html_list(){
		return $this->get_html_detail();	
	}
	
	function get_html_detail(){
		return 'not implemented';	
	}
	
	function get_html_search(){
		return $this->get_html_edit();	
	}
	function get_html_label(){
		
		$label =  "{MOD." . strtoupper($this->label) . "}";	
		if($this->view == 'list'){
			$label .= ':';
		}
		return $label;
			
	}
	
	/*
		XTPL FUNCTIONS
	*/
	
	function get_xtpl(&$bean){
		$this->bean =& $bean;
		switch($this->view){
			case 'search':return $this->get_xtpl_search();
			case 'edit': return $this->get_xtpl_edit();
			case 'list': return $this->get_xtpl_list();
			case 'detail': return $this->get_xtpl_detail();
				
		}		
	}
	
	function get_xtpl_edit(){
		return '/*not implemented*/';	
	}
	
	function get_xtpl_list(){
		return get_xtpl_detail();	
	}
	
	function get_xtpl_detail(){
		return '/*not implemented*/';	
	}
	
	function get_xtpl_search(){
		return get_xtpl_edit();	
	}
	

	
	
	
	/*
		DB FUNCTIONS
	*/
	
	function get_db_type(){
		return " varchar($this->max_size)";	
	}
	
	function get_db_default(){
		if(!empty($this->default_value)){
			return " DEFAULT $this->default_value";	
		}else{
			return '';	
		}
	}
	
	function get_db_required(){
		if(!empty($this->required_option) && $this->required_option == 'required'){
			return " NOT NULL";	
		}else{
			return '';	
		}
	}
	
	function get_db_add_alter_table($table){
			return"ALTER TABLE $table ADD $this->name " . $this->get_db_type() . $this->get_db_required() . $this->get_db_default();
	}
	
	
	/*
	 * BEAN FUNCTIONS
	 * 
	 */
	 
	function get_field_def(){
		return array("name"=>$this->name, "vname"=>$this->label,"type"=>'varchar',"len"=>$this->max_size, 'custom_type'=>'varchar');	
	}
	
	
	/*
		HELPER FUNCTIONS
	*/
	
	
	
	function prepare(){
		if(empty($this->id)){
			$this->id = $this->name;	
		}	
	}
	
}


?>
