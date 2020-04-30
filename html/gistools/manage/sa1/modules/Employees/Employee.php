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
 * $Id: Employee.php,v 1.7 2005/04/30 04:24:09 majed Exp $
 * Description: TODO:  To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');

// Employee is used to store customer information.
class Employee extends SugarBean {
	// Stored fields
	var $id;
	var $is_admin;
	var $first_name;
	var $last_name;
	var $user_name;
	var $title;
	var $description;
	var $department;
	var $reports_to_id;
	var $reports_to_name;
	var $phone_home;
	var $phone_mobile;
	var $phone_work;
	var $phone_other;
	var $phone_fax;
	var $email1;
	var $email2;
	var $address_street;
	var $address_city;
	var $address_state;
	var $address_postalcode;
	var $address_country;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;
	var $employee_status;
	var $messenger_id;
	var $messenger_type;
	var $error_string;
	
	var $module_dir = "Employees";





	var $table_name = "users";

	var $object_name = "User";
	var $user_preferences;
	var $column_fields = Array("id"
		,"first_name"
		,"last_name"
		,"user_name"
		,"title"
		,"description"
		,"department"
		,"reports_to_id"
		,"phone_home"
		,"phone_mobile"
		,"phone_work"
		,"phone_other"
		,"phone_fax"
		,"email1"
		,"email2"
		,"address_street"
		,"address_city"
		,"address_state"
		,"address_postalcode"
		,"address_country"
		,"date_entered"
		,"date_modified"
		,"modified_user_id"
		, "created_by"
		,"employee_status"
		,"messenger_id"
		,"messenger_type"



		);

	var $encodeFields = Array("first_name", "last_name", "description");

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('reports_to_name');

	// This is the list of fields that are in the lists.
	var $list_fields = Array('id', 'first_name', 'last_name', 'user_name', 'title', 'department', 'email1', 'phone_work', 'reports_to_name', 'reports_to_id', 'employee_status');

	var $default_order_by = "last_name";

	var $new_schema = true;

	function Employee() {
		parent::SugarBean();
		//$this->setupCustomFields('Employees');



	}

	function save($check_notify = false){
		parent::save($check_notify);
		
		if(!empty($this->user_preferences)){
			$this->savePreferecesToDB();
		}

	
	}

	
	
	function get_summary_text()
	{
		return "$this->first_name $this->last_name";
	}


	function fill_in_additional_list_fields() {
		$this->fill_in_additional_detail_fields();
	}

	function fill_in_additional_detail_fields()
	{
		$query = "SELECT u1.first_name, u1.last_name from users as u1, users as u2 where u1.id = u2.reports_to_id AND u2.id = '$this->id' and u1.deleted=0";
		$result =$this->db->query($query, true, "Error filling in additional detail fields") ;

		$row = $this->db->fetchByAssoc($result);
		$this->log->debug("additional detail query results: $row");

		if($row != null)
		{
			$this->reports_to_name = stripslashes($row['first_name'].' '.$row['last_name']);
		}
		else
		{
			$this->reports_to_name = '';
		}
	}

	function retrieve_employee_id($employee_name)
	{
		$query = "SELECT id from users where user_name='$user_name' AND deleted=0";
		$result  =& $this->db->query($query, false,"Error retrieving employee ID: ");
		$row = $this->db->fetchByAssoc($result);
		return $row['id'];
	}

	/**
	 * @return -- returns a list of all employees in the system.
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	 */
	function verify_data()
	{
		//none of the checks from the users module are valid here since the user_name and
		//is_admin_on fields are not editable.
		return TRUE;
	}

	function get_list_view_data(){
		global $image_path;

		$user_fields = $this->get_list_view_array();
		return $user_fields;
	}

	function list_view_parse_additional_sections(&$list_form, $xTemplateSection){
		return $list_form;
	}

	function create_list_query($order_by, $where)
	{
		global $current_user;
		$custom_join = $this->custom_fields->getJOIN();
		$query = "SELECT
				users.*";
		if($custom_join){
			$query .= $custom_join['select'];
		}
		$query .= " FROM users ";

		if($custom_join){
			$query .= $custom_join['join'];
		}

		if(is_admin($current_user)) 
		{
			$where_auto = " users.deleted = 0";
		}
		else 
		{
			$where_auto = " users.deleted = 0 and users.employee_status<>'Terminated'";
		}

		if($where != "")
			$query .= " WHERE $where AND " . $where_auto;
		else
			$query .= " WHERE " . $where_auto;

		if($order_by != "")
			$query .= " ORDER BY $order_by";
		else
			$query .= " ORDER BY users.user_name";

		return $query;
	}















	function create_export_query($order_by, $where)
	{
		$query = "SELECT
				users.*";
		$query .= " FROM users ";

		$where_auto = " users.deleted = 0";

		if($where != "")
			$query .= " WHERE $where AND " . $where_auto;
		else
			$query .= " WHERE " . $where_auto;

		if($order_by != "")
			$query .= " ORDER BY $order_by";
		else
			$query .= " ORDER BY users.user_name";

		return $query;
	}

}

?>
