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
 * $Id: Case.php,v 1.89.2.1 2005/05/09 20:53:19 ajay Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');
require_once('modules/Contacts/Contact.php');
require_once('modules/Tasks/Task.php');
require_once('modules/Notes/Note.php');
require_once('modules/Calls/Call.php');
require_once('modules/Emails/Email.php');
require_once('modules/Bugs/Bug.php');
require_once('include/utils.php');

// Case is used to store customer information.
class aCase extends SugarBean {
        var $field_name_map = array();
	// Stored fields
	var $id;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $assigned_user_id;



	var $number;
	var $resolution;
	var $description;
	var $name;
	var $status;
	var $priority;
	

	var $created_by;
	var $created_by_name;
	var $modified_by_name;

	// These are related
	var $bug_id;
	var $account_name;
	var $account_id;
	var $contact_id;
	var $task_id;
	var $note_id;
	var $meeting_id;
	var $call_id;
	var $email_id;
	var $assigned_user_name;




	var $table_name = "cases";
	var $rel_account_table = "accounts_cases";
	var $rel_contact_table = "contacts_cases";
	var $module_dir = 'Cases';

	var $object_name = "Case";

	var $column_fields = Array("id"
		, "name"
		, "number"
		, "account_name"
		, "account_id"
		, "date_entered"
		, "date_modified"
		, "modified_user_id"
		, "assigned_user_id"
		, "created_by"



		, "status"
		, "priority"
		, "description"
		, "resolution"
		);
	var $required_fields = array('name'=>1, 'account_name'=>2);



	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('bug_id', 'assigned_user_name', 'assigned_user_id', 'contact_id', 'task_id', 'note_id', 'meeting_id', 'call_id', 'email_id');

	// This is the list of fields that are in the lists.
	var $list_fields = Array('id', 'priority', 'status', 'name', 'account_name', 'number', 'account_id', 'assigned_user_name', 'assigned_user_id'




		);

	function aCase() {
		parent::SugarBean();
		global $sugar_config;
		if(!$sugar_config['require_accounts']){
			unset($this->required_fields['account_name']);
		}

		

		$this->log = LoggerManager::getLogger('case');
		
		 $this->setupCustomFields('Cases');
		foreach ($this->field_defs as $field)
                {
                        $this->field_name_map[$field['name']] = $field;
                }





	}

	var $new_schema = true;

	

	

	function get_summary_text()
	{
		return "$this->name";
	}

	function create_list_count_query($where){
		$order_by = '';
		$query = $this->create_list_query($order_by, $where);
		$query = explode('FROM', $query);
		return 'SELECT count(*) FROM ' . $query[1];
	}
	function create_list_query($order_by, $where)
	{
		// Fill in the assigned_user_name
//		$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);

				$custom_join = $this->custom_fields->getJOIN();
                $query = "SELECT ";
            

		$query .= "
                                cases.id,
                                cases.assigned_user_id,
                                cases.status,
                                cases.priority,
                                cases.name,
                                cases.number,
                                cases.account_id,
                                accounts.name as account_name,
                                users.user_name as assigned_user_name";



                            	if($custom_join){
   									$query .= $custom_join['select'];
 								}
                                $query .= " FROM cases ";





		$query .= "				LEFT JOIN users
                                ON cases.assigned_user_id=users.id";



                                $query .= " LEFT JOIN accounts
                                ON cases.account_id=accounts.id ";

								if($custom_join){
  									$query .= $custom_join['join'];
								}


                $where_auto = " (accounts.deleted IS NULL OR accounts.deleted=0) AND cases.deleted=0 ";



		if($where != "")
			$query .= "where $where AND ".$where_auto;
		else
			$query .= "where ".$where_auto;

		if($order_by != "")
			$query .= " ORDER BY cases.$order_by";
		else
			$query .= " ORDER BY cases.name";
		
		return $query;
	}

        function create_export_query($order_by, $where)
        {
				$custom_join = $this->custom_fields->getJOIN();
                $query = "SELECT
                                cases.*,
                                accounts.name as account_name,
                                users.user_name as assigned_user_name";
             					if($custom_join){
   									$query .= $custom_join['select'];
 								}
                                $query .= " FROM cases ";




		$query .= "				LEFT JOIN users
                                ON cases.assigned_user_id=users.id";
                                $query .= " LEFT JOIN accounts
                                ON cases.account_id=accounts.id";
                                
                 			if($custom_join){
  								$query .= $custom_join['join'];
							}	
                $where_auto = " accounts.deleted=0
                                AND cases.deleted=0
                ";

                if($where != "")
                        $query .= " where $where AND ".$where_auto;
                else
                        $query .= " where ".$where_auto;

                if($order_by != "")
                        $query .= " ORDER BY $order_by";
                else
                        $query .= " ORDER BY cases.name";
//sugar_die($query);
                return $query;
        }





	function save_relationship_changes($is_update)
    {
    	$this->clear_case_account_relationship($this->id);

		if($this->account_id != "")
    	{
    		$this->set_case_account_relationship($this->id, $this->account_id);
    	}
    	if ($this->bug_id != "") {
    		$this->set_case_bug_relationship($this->id, $this->bug_id);
    	}
    	if($this->contact_id != "")
    	{
    		$this->set_case_contact_relationship($this->id, $this->contact_id);
    	}
    	if($this->task_id != "")
    	{
    		$this->set_case_task_relationship($this->id, $this->task_id);
    	}
    	if($this->note_id != "")
    	{
    		$this->set_case_note_relationship($this->id, $this->note_id);
    	}
    	if($this->meeting_id != "")
    	{
    		$this->set_case_meeting_relationship($this->id, $this->meeting_id);
    	}
    	if($this->call_id != "")
    	{
    		$this->set_case_call_relationship($this->id, $this->call_id);
    	}
    	if($this->email_id != "")
    	{
    		$this->set_case_email_relationship($this->id, $this->email_id);
    	}
    }

	function set_case_account_relationship($case_id, $account_id)
	{
		$query = "update cases set account_id='$account_id' where id='$case_id'";
		$this->db->query($query,true,"Error setting account to case relationship: ");
	}
	function set_account_relationship($module_id, $account_id){
		$this->set_case_account_relationship($module_id, $account_id);	
	}

	function clear_case_account_relationship($case_id)
	{
		$query = "UPDATE cases set account_id='' where id='$case_id' and deleted=0";
		$this->db->query($query,true,"Error clearing account to case relationship: ");
	}

	function set_case_bug_relationship($case_id, $bug_id) {
		$this->set_relationship('cases_bugs', array('case_id'=>$case_id , 'bug_id'=>$bug_id));
	}

	function set_case_contact_relationship($case_id, $contact_id)
	{
		global $app_list_strings;
		$default = $app_list_strings['case_relationship_type_default_key'];
		$this->set_relationship('contacts_cases', array('case_id'=> $case_id, 'contact_id'=>$contact_id , 'contact_role'=>$default));
	}
	function set_contact_relationship($self_id, $contact_id){
		$this->set_case_contact_relationship($self_id, $contact_id);	
	}

	function clear_case_contact_relationship($case_id)
	{
		$query = "delete from contacts_cases where case_id='$case_id' AND deleted=0";
		$this->db->query($query,true,"Error clearing case to contact relationship: ");
	}

	function set_case_task_relationship($case_id, $task_id)
	{
		$query = "UPDATE tasks set parent_id='$case_id', parent_type='Cases' where id='$task_id' AND deleted=0";
		$this->db->query($query,true,"Error setting case to task relationship: ");
	}

	function clear_case_task_relationship($case_id)
	{
		$query = "UPDATE tasks set parent_id='', parent_type='' where parent_id='$case_id' AND deleted=0";
		$this->db->query($query,true,"Error clearing case to task relationship: ");
	}

	function set_case_note_relationship($case_id, $note_id)
	{
		$query = "UPDATE notes set parent_id='$case_id', parent_type='Cases' where id='$note_id' AND deleted=0";
		$this->db->query($query,true,"Error setting case to note relationship: ");
	}
	function set_note_relationship($case_id, $note_id){
			$this->set_case_note_relationship($case_id, $note_id);
	}
	function clear_case_note_relationship($case_id)
	{
		$query = "UPDATE notes set parent_id='', parent_type='' where parent_id='$case_id' AND deleted=0";
		$this->db->query($query,true,"Error clearing case to note relationship: ");
	}
	function clear_note_relationship($case_id){
			$this->clear_case_note_relationship($case_id);
	}

	function set_case_meeting_relationship($case_id, $meeting_id)
	{
		$query = "UPDATE meetings set parent_id='$case_id', parent_type='Cases' where id='$meeting_id' AND deleted=0";
		$this->db->query($query,true,"Error setting case to meeting relationship: ");
	}

	function clear_case_meeting_relationship($case_id)
	{
		$query = "UPDATE meetings set parent_id='', parent_type='' where parent_id='$case_id' AND deleted=0";
		$this->db->query($query,true,"Error clearing case to meeting relationship: ");
	}

	function set_case_call_relationship($case_id, $call_id)
	{
		$query = "UPDATE calls set parent_id='$case_id', parent_type='Cases' where id='$call_id' AND deleted=0";
		$this->db->query($query,true,"Error setting case to call relationship: ");
	}

	function clear_case_call_relationship($case_id)
	{
		$query = "UPDATE calls set parent_id='', parent_type='' where parent_id='$case_id' AND deleted=0";
		$this->db->query($query,true,"Error clearing case to call relationship: ");
	}

	function set_case_email_relationship($email_id, $call_id)
	{
		$query = "UPDATE emails set parent_id='$case_id', parent_type='Cases' where id='$email_id' AND deleted=0";
		$this->db->query($query,true,"Error setting email to case relationship: ");
	}

	function clear_case_email_relationship($case_id)
	{
		$query = "UPDATE emails set parent_id='', parent_type='' where parent_id='$case_id' AND deleted=0";
		$this->db->query($query,true,"Error clearing email to case relationship: ");
	}

	function mark_relationships_deleted($id)
	{
		$this->clear_case_contact_relationship($id);
		$this->clear_case_task_relationship($id);
		$this->clear_case_note_relationship($id);
		$this->clear_case_meeting_relationship($id);
		$this->clear_case_call_relationship($id);
		$this->clear_case_email_relationship($id);
	}

	function fill_in_additional_list_fields()
	{
		// Fill in the assigned_user_name
		//$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);



	}

	function fill_in_additional_detail_fields()
	{
		// Fill in the assigned_user_name
		$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);




		$query = "SELECT acc.id, acc.name from accounts  acc, cases  where acc.id = cases.account_id and cases.id = '$this->id' and cases.deleted=0 and acc.deleted=0";
		$result = $this->db->query($query,true," Error filling in additional detail fields: ");

		// Get the id and the name.
		$row = $this->db->fetchByAssoc($result);

		if($row != null)
		{
			$this->account_name = stripslashes($row['name']);
			$this->account_id 	= $row['id'];
		}

		$this->created_by_name = get_assigned_user_name($this->created_by);
		$this->modified_by_name = get_assigned_user_name($this->modified_user_id);
	}


	/** Returns a list of the associated contacts
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_contacts()
	{
		// First, get the list of IDs.
		$query = "SELECT c.id, c.first_name, c.last_name, c.title, c.email1, c.phone_work, o_c.contact_role as case_role, o_c.id as case_rel_id ".
				 "from contacts_cases o_c, contacts c ".
				 "where o_c.case_id = '$this->id' and o_c.deleted=0 and c.id = o_c.contact_id AND c.deleted=0";

	    $temp = Array('id', 'first_name', 'last_name', 'title', 'email1', 'phone_work', 'case_role', 'case_rel_id');
		return $this->build_related_list2($query, new Contact(), $temp);
	}

	/** Returns a list of the associated tasks
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_tasks()
	{
		// First, get the list of IDs.
		$query = "SELECT id from tasks where parent_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new Task());
	}

	function get_bugs()
	{
		// First, get the list of IDs.
		$query = "SELECT bug_id as id from cases_bugs where case_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new Bug());
	}

	/** Returns a list of the associated notes
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_notes()
	{
		// First, get the list of IDs.
		$query = "SELECT id from notes where parent_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new Note());
	}

	/** Returns a list of the associated meetings
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_meetings()
	{
		// First, get the list of IDs.
		$query = "SELECT id from meetings where parent_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new Meeting());
	}

	/** Returns a list of the associated calls
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_calls()
	{
		// First, get the list of IDs.
		$query = "SELECT id from calls where parent_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new Call());
	}

		/** Returns a list of the associated emails
		 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
		 * All Rights Reserved..
		 * Contributor(s): ______________________________________..
	*/
	function get_emails()
	{
		// First, get the list of IDs.
		$query = "SELECT id from emails where parent_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new Email());
	}

	function get_list_view_data(){
		global $current_language, $image_path;
		$app_list_strings = return_app_list_strings_language($current_language);
		$temp_array =& $this->get_list_view_array();
		$temp_array['NAME'] = (($this->name == "") ? "<em>blank</em>" : $this->name);
		$temp_array['PRIORITY'] = empty($this->priority)? "" : $app_list_strings['case_priority_dom'][$this->priority];
		$temp_array['STATUS'] = empty($this->status)? "" : $app_list_strings['case_status_dom'][$this->status];
		$temp_array['ENCODED_NAME'] = $this->name;
		$temp_array['NUMBER'] = $this->number;
		$temp_array['SET_COMPLETE'] =  "<a href='index.php?return_module=Home&return_action=index&action=EditView&module=Cases&record=$this->id&status=Closed'>".get_image($image_path."close_inline","alt='Close' border='0'")."</a>";
		return $temp_array;
	}

	/**
		builds a generic search based on the query string using or
		do not include any $this-> because this is called on without having the class instantiated
	*/
	function build_generic_where_clause ($the_query_string) {
	$where_clauses = Array();
	$the_query_string = addslashes($the_query_string);
	array_push($where_clauses, "cases.name like '$the_query_string%'");
	array_push($where_clauses, "accounts.name like '$the_query_string%'");

	if (is_numeric($the_query_string)) array_push($where_clauses, "cases.number like '$the_query_string%'");

	$the_where = "";
	foreach($where_clauses as $clause)
	{
		if($the_where != "") $the_where .= " or ";
		$the_where .= $clause;
	}

	return $the_where;
	}

	function parse_additional_headers(&$list_form, $xTemplateSection) {

	}

	function list_view_parse_additional_sections(&$list_form, $xTemplateSection) {
		return $list_form;
	}

	function set_notification_body($xtpl, $case)
	{
		global $app_list_strings;		
		
		$xtpl->assign("CASE_SUBJECT", $case->name);
		$xtpl->assign("CASE_PRIORITY", (isset($case->priority) ? $app_list_strings['case_priority_dom'][$case->priority]:""));
		$xtpl->assign("CASE_STATUS", (isset($case->status) ? $app_list_strings['case_status_dom'][$case->status]:""));
		$xtpl->assign("CASE_DESCRIPTION", $case->description);

		return $xtpl;
	}

}
?>
