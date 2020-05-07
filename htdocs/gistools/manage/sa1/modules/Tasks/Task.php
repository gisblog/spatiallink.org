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
 * $Id: Task.php,v 1.100.2.5 2005/05/17 19:12:19 andrew Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');

// Task is used to store customer information.
class Task extends SugarBean {
        var $field_name_map;

	// Stored fields
	var $id;
	var $date_entered;
	var $date_modified;
	var $assigned_user_id;
	var $modified_user_id;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;



	var $description;
	var $name;
	var $status;
	var $date_due_flag;
	var $date_due;
	var $time_due;
	var $date_start_flag;
	var $date_start;
	var $time_start;
	var $priority;
	var $parent_type;
	var $parent_id;
	var $contact_id;

	var $parent_name;
	var $contact_name;
	var $contact_phone;
	var $contact_email;
	var $assigned_user_name;




	var $required_fields = array('name'=>1);
	var $default_task_name_values = array('Assemble catalogs', 'Make travel arrangements', 'Send a letter', 'Send contract', 'Send fax', 'Send a follow-up letter', 'Send literature', 'Send proposal', 'Send quote');

	var $table_name = "tasks";

	var $object_name = "Task";
	var $module_dir = 'Tasks';
	var $column_fields = Array("id"
		, "date_entered"
		, "date_modified"
		, "assigned_user_id"
		, "modified_user_id"
		, "created_by"



		, "description"
		, "name"
		, "status"
		, "date_due"
		, "time_due"
		, "date_start_flag"
		, "date_start"
		, "time_start"
		, "priority"
		, "date_due_flag"
		, "parent_type"
		, "parent_id"
		, "contact_id"
		);



	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('assigned_user_name', 'assigned_user_id', 'contact_name', 'contact_phone', 'contact_email', 'parent_name');

	// This is the list of fields that are in the lists.
	var $list_fields = Array('id', 'status', 'name', 'parent_type', 'parent_name', 'parent_id', 'date_due', 'contact_id', 'contact_name', 'assigned_user_name', 'assigned_user_id','first_name','last_name','time_due', 'priority'




		);

	function Task() {
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
                $query = "SELECT ";
            
                $query .= "
			tasks.id,
			tasks.assigned_user_id,
			tasks.status,
			tasks.name,
			tasks.parent_type,
			tasks.parent_id,
			tasks.contact_id,
			tasks.date_due,
			tasks.time_due,
			tasks.priority,
			contacts.first_name,
            contacts.last_name,
            users.user_name as assigned_user_name";



            if($custom_join){
   				$query .= $custom_join['select'];
 			}
            $query .= " FROM tasks ";





$query .= 					"LEFT JOIN contacts
                            ON tasks.contact_id=contacts.id";



                            $query .= " LEFT JOIN users
                            ON tasks.assigned_user_id=users.id ";
	   if($custom_join){
   				$query .= $custom_join['join'];
 			}

                    $where_auto = " tasks.deleted=0
                                    AND ( contacts.deleted IS NULL OR contacts.deleted=0) ";
				//GROUP BY tasks.id";

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
                $contact_required = ereg("contacts", $where);
				$custom_join = $this->custom_fields->getJOIN();
                if($contact_required)
                {
                        $query = "SELECT tasks.*, contacts.first_name, contacts.last_name";



                        if($custom_join){
   							$query .= $custom_join['select'];
 						}
                        $query .= " FROM contacts, tasks ";
                        $where_auto = "tasks.contact_id = contacts.id AND tasks.deleted=0 AND contacts.deleted=0";
                }
                else
                {
                        $query = 'SELECT tasks.*';



                        if($custom_join){
   							$query .= $custom_join['select'];
 						}
                        $query .= ' FROM tasks ';
                        $where_auto = "tasks.deleted=0";
                }









				if($custom_join){
   				$query .= $custom_join['join'];
 			}

                if($where != "")
                        $query .= "where $where AND ".$where_auto;
                else
                        $query .= "where ".$where_auto;

                if($order_by != "")
                        $query .= " ORDER BY $order_by";
                else
                        $query .= " ORDER BY tasks.name";
                return $query;

        }



	function fill_in_additional_list_fields()
	{
//		$this->fill_in_additional_detail_fields();
		$this->fill_in_additional_parent_fields();
	}

	function fill_in_additional_detail_fields()
	{
		// Fill in the assigned_user_name
		$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);




		global $app_strings;

		if (isset($this->contact_id)) {
			require_once("modules/Contacts/Contact.php");
			$contact = new Contact();
			$query = "SELECT first_name, last_name, phone_work, email1 from $contact->table_name where id = '$this->contact_id'";

			$result =$this->db->query($query,true,$app_strings['ERR_CREATING_FIELDS']);


			// Get the id and the name.

			$row = $this->db->fetchByAssoc($result);


			if($row != null)
			{
				$this->contact_name = return_name($row, 'first_name', 'last_name');
				if ($row['phone_work'] != '') $this->contact_phone = $row['phone_work'];
				if ($row['email1'] != '') $this->contact_email = $row['email1'];
			}
		}

		$this->created_by_name = get_assigned_user_name($this->created_by);
		$this->modified_by_name = get_assigned_user_name($this->modified_user_id);

		$this->fill_in_additional_parent_fields();
	}

	function fill_in_additional_parent_fields()
	{
		global $app_strings;

		if ($this->parent_type == "Opportunities") {
			require_once("modules/Opportunities/Opportunity.php");
			$parent = new Opportunity();
			$query = "SELECT name from $parent->table_name where id = '$this->parent_id'";

			$result =$this->db->query($query,true, $app_strings['ERR_CREATING_FIELDS']);

			// Get the id and the name.

			$row = $this->db->fetchByAssoc($result);


			if($row != null)
			{
				if ($row['name'] != '') $this->parent_name = stripslashes($row['name']);
			}
		}
		elseif ($this->parent_type == "Cases") {
			require_once("modules/Cases/Case.php");
			$parent = new aCase();
			$query = "SELECT name from $parent->table_name where id = '$this->parent_id'";

			$result =$this->db->query($query,true,$app_strings['ERR_CREATING_FIELDS']);


			// Get the id and the name.

			$row = $this->db->fetchByAssoc($result);


			if($row != null)
			{
				if ($row['name'] != '') $this->parent_name = stripslashes($row['name']);
			}
		}
		elseif ($this->parent_type == "Bugs") {
			require_once("modules/Bugs/Bug.php");
			$parent = new Bug();
			$query = "SELECT name from $parent->table_name where id = '$this->parent_id'";

			$result =$this->db->query($query,true,$app_strings['ERR_CREATING_FIELDS']);


			// Get the id and the name.

			$row = $this->db->fetchByAssoc($result);


			if($row != null)
			{
				if ($row['name'] != '') $this->parent_name = stripslashes($row['name']);
			}
		}
		elseif ($this->parent_type == "Project") {
			require_once("modules/Project/Project.php");
			$parent = new Project();
			$query = "SELECT name from $parent->table_name where id = '$this->parent_id'";

			$result =$this->db->query($query,true,$app_strings['ERR_CREATING_FIELDS']);


			// Get the id and the name.

			$row = $this->db->fetchByAssoc($result);


			if($row != null)
			{
				if ($row['name'] != '') $this->parent_name = stripslashes($row['name']);
			}
		}
		elseif ($this->parent_type == "ProjectTask") {
			require_once("modules/ProjectTask/ProjectTask.php");
			$parent = new ProjectTask();
			$query = "SELECT name from $parent->table_name where id = '$this->parent_id'";

			$result =$this->db->query($query,true,$app_strings['ERR_CREATING_FIELDS']);


			// Get the id and the name.

			$row = $this->db->fetchByAssoc($result);


			if($row != null)
			{
				if ($row['name'] != '') $this->parent_name = stripslashes($row['name']);
			}
		}
		elseif ($this->parent_type == "Accounts") {
			require_once("modules/Accounts/Account.php");
			$parent = new Account();
			$query = "SELECT name from $parent->table_name where id = '$this->parent_id'";

			$result =$this->db->query($query,true, $app_strings['ERR_CREATING_FIELDS']);


			// Get the id and the name.

			$row = $this->db->fetchByAssoc($result);


			if($row != null)
			{
				if ($row['name'] != '') $this->parent_name = stripslashes($row['name']);
			}
		}
		elseif ($this->parent_type == "Issues") {
        	require_once("modules/Issues/Issue.php");
            $parent = new Issue();

            $query = "SELECT name from $parent->table_name where id = '$this->parent_id'";
			$result = $this->db->query($query, TRUE, "Error filling in additional detail fields: ");
			$row = $this->db->fetchByAssoc($result);

			if (!is_null($row)) {
 				$this->parent_name = '';
				if (!empty($row['name'])) $this->parent_name .= stripslashes($row['name']);
			}
		}
		elseif ($this->parent_type == "Leads") {
			require_once("modules/Leads/Lead.php");
			$parent = new Lead();
			$query = "SELECT first_name, last_name from $parent->table_name where id = '$this->parent_id'";

			$result =$this->db->query($query,true, $app_strings['ERR_CREATING_FIELDS']);


			// Get the id and the name.

			$row = $this->db->fetchByAssoc($result);


			if($row != null)
			{
				$this->parent_name = '';
				if ($row['first_name'] != '') $this->parent_name .= stripslashes($row['first_name']). ' ';
				if ($row['last_name'] != '') $this->parent_name .= stripslashes($row['last_name']);
			}
		}









































	}





	function get_list_view_data(){
		global $action, $currentModule, $focus, $current_module_strings, $app_list_strings, $image_path;
		$timedate = new TimeDate();
		$today = $timedate->handle_offset(date("Y-m-d H:i:s", time()), $timedate->dbDayFormat, true);
		$task_fields =$this->get_list_view_array();
		$date_due = $timedate->to_db_date($task_fields['DATE_DUE'],false);
		if (!empty($this->priority)) 
			$task_fields['PRIORITY'] = $app_list_strings['task_priority_dom'][$this->priority];
		if (isset($this->parent_type)) 
			$task_fields['PARENT_MODULE'] = $this->parent_type;
		if ($this->status != "Completed" && $this->status != "Deferred" ) {
			$task_fields['SET_COMPLETE'] = "<a href='index.php?return_module=$currentModule&return_action=$action&return_id=" . ((isset($focus->id)) ? $focus->id : "") . "&action=EditView&module=Tasks&record={$this->id}&status=Completed'>".get_image($image_path."close_inline","alt='Close' border='0'")."</a>";
		}
		if( $date_due	< $today){
			$task_fields['DATE_DUE']= "<font class='overdueTask'>".$task_fields['DATE_DUE'].' '.$task_fields['TIME_DUE']."</font>";
		}else if( $date_due	== $today ){
			$task_fields['DATE_DUE'] = "<font class='todaysTask'>".$task_fields['DATE_DUE'].' '.$task_fields['TIME_DUE']."</font>";
		}else{
			$task_fields['DATE_DUE'] = "<font class='futureTask'>".$task_fields['DATE_DUE'].' '.$task_fields['TIME_DUE']."</font>";
		}

		$task_fields['CONTACT_NAME']= return_name($task_fields,"FIRST_NAME","LAST_NAME");
		$task_fields['TITLE'] = '';
		if (!empty($task_fields['CONTACT_NAME'])) {
			$task_fields['TITLE'] .= $current_module_strings['LBL_LIST_CONTACT'].": ".$task_fields['CONTACT_NAME'];
		}
		if (!empty($this->parent_name)) {
			$task_fields['TITLE'] .= "\n".$app_list_strings['record_type_display'][$this->parent_type].": ".$this->parent_name;
		}
		if (isset($task_fields['STATUS'])) {
                        $task_fields['STATUS'] = translate('task_status_dom', '', $task_fields['STATUS']);
                }

		return $task_fields;
	}

	function parse_additional_headers(&$list_form, $xTemplateSection) {

	}

	function list_view_parse_additional_sections(&$list_form, $xTemplateSection) {
		return $list_form;
	}

	function set_notification_body($xtpl, $task)
	{
		global $app_list_strings;		
		
		$xtpl->assign("TASK_SUBJECT", $task->name);
		$xtpl->assign("TASK_PRIORITY", (isset($task->priority)? $app_list_strings['task_status_dom'][$task->status]:"") );
		$xtpl->assign("TASK_DUEDATE", $task->date_due . " " . $task->time_due);
		$xtpl->assign("TASK_STATUS", (isset($task->status)?$app_list_strings['task_status_dom'][$task->status]:""));
		$xtpl->assign("TASK_DESCRIPTION", $task->description);

		return $xtpl;
	}

}
?>
