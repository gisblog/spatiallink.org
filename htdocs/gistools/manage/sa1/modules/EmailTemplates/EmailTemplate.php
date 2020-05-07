<?php
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
/*********************************************************************************
 * $Header: /var/cvsroot/sugarcrm/modules/EmailTemplates/EmailTemplate.php,v 1.14.2.1 2005/05/05 23:11:29 joey Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');

// EmailTemplate is used to store email email_template information.
class EmailTemplate extends SugarBean {
	var $field_name_map = array();
	// Stored fields
	var $id;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;
	var $name;
	var $published;
	var $description;




	var $required_fields =  array("name"=>1);

	var $table_name = "email_templates";

	var $object_name = "EmailTemplate";
	var $module_dir = "EmailTemplates";
	var $column_fields = Array("id"
		, "date_entered"
		, "date_modified"
		, "modified_user_id"
		, "created_by"
		, "description"
		, "subject"
		, "body"
		, "name"
		, "published"



		);




	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array();

	var $list_fields = Array('id', 'name', 'description','date_modified'



	);

	function EmailTemplate() {
		parent::SugarBean();
      



	}

	var $new_schema = true;

	

	function get_summary_text()
	{
		return "$this->name";
	}

	function create_list_query(&$order_by, &$where)
	{
		$custom_join = $this->custom_fields->getJOIN();
		$query = 'SELECT id, name, description, date_modified ';
		if($custom_join){
   				$query .= $custom_join['select'];
 			}
		$query .= ' FROM email_templates ';
		if($custom_join){
  				$query .= $custom_join['join'];
			}
		

		

		$where_auto = "deleted=0";

		if($where != "")
			$query .= "where $where AND ".$where_auto;
		else
			$query .= "where ".$where_auto;

		if($order_by != "")
			$query .= " ORDER BY $order_by";
		else
			$query .= " ORDER BY name";

		return $query;
	}




        function create_export_query(&$order_by, &$where)
        {
        	return $this->create_list_query($order_by, $where);
        }




	function fill_in_additional_list_fields()
	{
//		$this->fill_in_additional_detail_fields();
		$this->fill_in_additional_parent_fields();
	}

	function fill_in_additional_detail_fields()
	{

		$this->created_by_name = get_assigned_user_name($this->created_by);
		$this->modified_by_name = get_assigned_user_name($this->modified_user_id);

		$this->fill_in_additional_parent_fields();
	}

	function fill_in_additional_parent_fields()
	{

	}
	
	function get_list_view_data()
	{
		$fields = $this->get_list_view_array();
		global $app_list_strings, $focus, $action, $currentModule;
		$fields["DATE_MODIFIED"] = substr($fields["DATE_MODIFIED"], 0 , 10);

		return $fields;
	}


	function parse_template_bean($string,$bean_name, $focus)
	{
		global $beanFiles,$beanList;
		$repl_arr = array();
		
		foreach($focus->field_defs as $field_def)
		{
			
			if(isset($focus->$field_def['name']))
			{
           		if( $field_def['type'] == 'relate' || $field_def['type'] == 'assigned_user_name')
           		{
             		continue;
           		}

				if ($field_def['type'] == 'enum')
				{
					$translated = translate($field_def['options'],$bean_name,$focus->$field_def['name']);
					if ( isset($translated) && ! is_array($translated))
					{
						$repl_arr[strtolower($beanList[$bean_name])."_".$field_def['name']] = $translated;
					}
				}
				else
				{
					$repl_arr[strtolower($beanList[$bean_name])."_".$field_def['name']] = $focus->$field_def['name'];
				}
			}
			else
			{

				if($field_def['name'] == 'full_name')
				{
					$repl_arr[strtolower($beanList[$bean_name]).'_full_name'] = $focus->get_summary_text();
				}
				else
				{	
					$repl_arr[strtolower($beanList[$bean_name])."_".$field_def['name']] = '';
				}
			}
		}
		
		krsort($repl_arr);
		reset($repl_arr);

		foreach ($repl_arr as $name=>$value)
		{
			$string = str_replace("\$$name",$value,$string);	
		}	
		return $string; 
	}

	function parse_template($string,&$bean_arr)
	{
		global $beanFiles,$beanList;

		foreach ($bean_arr as $bean_name=>$bean_id)
		{
			require_once($beanFiles[$beanList[$bean_name]]);

			$focus = new $beanList[$bean_name];

			if ( $focus->retrieve($bean_id) == -1)
			{
				sugar_die("bean not found by id: ".$bean_id);
			}
		if(isset($this) && $this->module_dir == 'EmailTemplates'){
			$string = $this->parse_template_bean($string, $bean_name, $focus);
		}else{
			$string = EmailTemplate::parse_template_bean($string, $bean_name, $focus);	
		}
		}
		return $string;

		
		
	}

}
?>
