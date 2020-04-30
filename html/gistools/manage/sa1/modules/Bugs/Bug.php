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
 * $Id: Bug.php,v 1.30.2.1 2005/05/06 18:49:19 majed Exp $
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
require_once('modules/Cases/Case.php');
require_once('modules/Accounts/Account.php');
require_once('include/utils.php');

// Bug is used to store customer information.
class Bug extends SugarBean {
        var $field_name_map = array();
	// Stored fields
	var $id;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $assigned_user_id;



	var $number;
	var $description;
	var $name;
	var $status;
	var $priority;

	// These are related
	var $resolution;
	var $release;
	var $release_name;
	var $fixed_in_release_name;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;
	var $account_id;
	var $contact_id;
	var $case_id;
	var $task_id;
	var $note_id;
	var $meeting_id;
	var $call_id;
	var $email_id;
	var $assigned_user_name;
	var $type;




	//BEGIN Additional fields being added to Bug Tracker
	
	var $fixed_in_release;
	var $work_log;
	var $source;
	var $product_category;
	//END Additional fields being added to Bug Tracker
	
	var $module_dir = 'Bugs';
	var $table_name = "bugs";
	var $rel_account_table = "accounts_bugs";
	var $rel_contact_table = "contacts_bugs";
	var $rel_case_table = "cases_bugs";

	var $object_name = "Bug";

	var $column_fields = Array("id"
		, "name"
		, "number"
		, "date_entered"
		, "date_modified"
		, "modified_user_id"
		, "assigned_user_id"



		, "status"
		, "release"
		, "created_by"
		, "resolution"
		, "priority"
		, "description"
		,'type'
		, "fixed_in_release"
		, "work_log"
		, "source"
		, "product_category"
		);
	var $required_fields = array('name'=>1);

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('assigned_user_name', 'assigned_user_id', 'case_id', 'account_id', 'contact_id', 'task_id', 'note_id', 'meeting_id', 'call_id', 'email_id');

	// This is the list of fields that are in the lists.
	var $list_fields = Array('id', 'priority', 'status', 'name', 'number', 'assigned_user_name', 'assigned_user_id', 'release', 'release_name', 'resolution', 'type'




		);

	function Bug() {
		parent::SugarBean();
		$this->log = LoggerManager::getLogger('bug');
		

		$this->setupCustomFields('Bugs');

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

	function create_list_query($order_by, $where)
	{
		// Fill in the assigned_user_name
//		$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);

		$custom_join = $this->custom_fields->getJOIN();
		
                $query = "SELECT ";
                
		$query .= "
                                bugs.id,
                                bugs.assigned_user_id,
                                bugs.status,
                                bugs.priority,
                                bugs.name,
								bugs.release,
                                bugs.number,
								bugs.resolution,
								bugs.type,

                                users.user_name as assigned_user_name";



                                 if($custom_join){
                               		 $query .= $custom_join['select'];
                                }
                                $query .= " FROM bugs ";
                               





		$query .= "				LEFT JOIN users
                                ON bugs.assigned_user_id=users.id";



                                $query .= "  ";
								if($custom_join){
                               		 $query .= $custom_join['join'];
                                }

                $where_auto = "bugs.deleted=0 ";

		if($where != "")
			$query .= "where $where AND ".$where_auto;
		else
			$query .= "where ".$where_auto;
		if(substr_count($order_by, '.') > 0){
			$query .= " ORDER BY $order_by";
		}
		else if($order_by != "")
			$query .= " ORDER BY bugs.$order_by";
		else
			$query .= " ORDER BY bugs.name";
		
		return $query;
	}

        function create_export_query($order_by, $where)
        {
				$custom_join = $this->custom_fields->getJOIN();
                $query = "SELECT
                                bugs.*,

                                users.user_name as assigned_user_name";
                                 if($custom_join){
									$query .=  $custom_join['select'];
								}
                                $query .= " FROM bugs ";




		$query .= "				LEFT JOIN users
                                ON bugs.assigned_user_id=users.id";
                                 if($custom_join){
									$query .=  $custom_join['join'];
								}
                                $query .= "";
                $where_auto = "  bugs.deleted=0
                ";

                if($where != "")
                        $query .= " where $where AND ".$where_auto;
                else
                        $query .= " where ".$where_auto;

                if($order_by != "")
                        $query .= " ORDER BY $order_by";
                else
                        $query .= " ORDER BY bugs.number";

                return $query;
        }





	function save_relationship_changes($is_update)
    {
    	//die("here");
		if($this->account_id != "")
    	{
    		$this->set_bug_account_relationship($this->id, $this->account_id);
    	}
		if($this->case_id != "")
    	{
    		$this->set_bug_case_relationship($this->id, $this->case_id);
    	}
    	if($this->contact_id != "")
    	{
    		$this->set_bug_contact_relationship($this->id, $this->contact_id);
    	}
    	if($this->task_id != "")
    	{
    		$this->set_bug_task_relationship($this->id, $this->task_id);
    	}
    	if($this->note_id != "")
    	{
    		$this->set_bug_note_relationship($this->id, $this->note_id);
    	}
    	if($this->meeting_id != "")
    	{
    		$this->set_bug_meeting_relationship($this->id, $this->meeting_id);
    	}
    	if($this->call_id != "")
    	{
    		$this->set_bug_call_relationship($this->id, $this->call_id);
    	}
    	if($this->email_id != "")
    	{
    		$this->set_bug_email_relationship($this->id, $this->email_id);
    	}
    }

	function set_bug_case_relationship($bug_id, $case_id)
	{
		$this->set_relationship('cases_bugs', array('bug_id'=>$bug_id , 'case_id'=>$case_id, ));
	}

	function clear_bug_case_relationship($case_id)
	{
		$query = "delete from cases_bugs where case_id='$case_id' AND deleted=0";
		$this->db->query($query,true,"Error clearing bug to case relationship: ");
	}

	function set_bug_account_relationship($bug_id, $account_id)
	{
		$this->set_relationship('accounts_bugs', array('bug_id'=>$bug_id , 'account_id'=>$account_id, ));
	}
	function set_account_relationship($module_id, $account_id){
		$this->set_bug_account_relationship($module_id, $account_id);	
	}

	function clear_bug_account_relationship($account_id)
	{
		$query = "delete from accounts_bugs where account_id='$account_id' AND deleted=0";
		$this->db->query($query,true,"Error clearing bug to account relationship: ");
	}

	function set_bug_contact_relationship($bug_id, $contact_id)
	{
		$this->set_relationship('contacts_bugs', array('bug_id'=>$bug_id , 'contact_id'=>$contact_id));
	}
	
	function set_contact_relationship($bug_id, $contact_id){
		$this->set_bug_contact_relationship($bug_id, $contact_id);	
	}

	function clear_bug_contact_relationship($contact_id)
	{
		$query = "delete from contacts_bugs where contact_id = '$contact_id'AND deleted=0";
		$this->db->query($query,true,"Error clearing bug to contact relationship: ");
	}

	function set_bug_task_relationship($bug_id, $task_id)
	{
		$query = "UPDATE tasks set parent_id='$bug_id', parent_type='Bugs' where id='$task_id' AND deleted=0";
		$this->db->query($query,true,"Error setting bug to task relationship: ");
	}

	function clear_bug_task_relationship($bug_id)
	{
		$query = "UPDATE tasks set parent_id='', parent_type='' where parent_id='$bug_id' AND deleted=0";
		$this->db->query($query,true,"Error clearing bug to task relationship: ");
	}

	function set_bug_note_relationship($bug_id, $note_id)
	{
		$query = "UPDATE notes set parent_id='$bug_id', parent_type='Bugs' where id='$note_id' AND deleted=0";
		$this->db->query($query,true,"Error setting bug to note relationship: ");
	}

	function clear_bug_note_relationship($bug_id)
	{
		$query = "UPDATE notes set parent_id='', parent_type='' where parent_id='$bug_id' AND deleted=0";
		$this->db->query($query,true,"Error clearing bug to note relationship: ");
	}

	function set_bug_meeting_relationship($bug_id, $meeting_id)
	{
		$query = "UPDATE meetings set parent_id='$bug_id', parent_type='Bugs' where id='$meeting_id' AND deleted=0";
		$this->db->query($query,true,"Error setting bug to meeting relationship: ");
	}

	function clear_bug_meeting_relationship($bug_id)
	{
		$query = "UPDATE meetings set parent_id='', parent_type='' where parent_id='$bug_id' AND deleted=0";
		$this->db->query($query,true,"Error clearing bug to meeting relationship: ");
	}

	function set_bug_call_relationship($bug_id, $call_id)
	{
		$query = "UPDATE calls set parent_id='$bug_id', parent_type='Bugs' where id='$call_id' AND deleted=0";
		$this->db->query($query,true,"Error setting bug to call relationship: ");
	}

	function clear_bug_call_relationship($bug_id)
	{
		$query = "UPDATE calls set parent_id='', parent_type='' where parent_id='$bug_id' AND deleted=0";
		$this->db->query($query,true,"Error clearing bug to call relationship: ");
	}

	function set_bug_email_relationship($bug_id, $email_id)
	{
		$query = "UPDATE emails set parent_id='$bug_id', parent_type='Bugs' where id='$email_id' AND deleted=0";
		$this->db->query($query,true,"Error setting email to bug relationship: ");
	}

	function clear_bug_email_relationship($bug_id)
	{
		$query = "UPDATE emails set parent_id='', parent_type='' where parent_id='$bug_id' AND deleted=0";
		$this->db->query($query,true,"Error clearing email to bug relationship: ");
	}

	function mark_relationships_deleted($id)
	{
		$this->clear_bug_case_relationship($id);
		$this->clear_bug_account_relationship($id);
		$this->clear_bug_contact_relationship($id);
		$this->clear_bug_task_relationship($id);
		$this->clear_bug_note_relationship($id);
		$this->clear_bug_meeting_relationship($id);
		$this->clear_bug_call_relationship($id);
		$this->clear_bug_email_relationship($id);
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




		$this->created_by_name = get_assigned_user_name($this->created_by);
		$this->modified_by_name = get_assigned_user_name($this->modified_user_id);
		$this->set_release();
		$this->set_fixed_in_release();
	}


	/** Returns a list of the associated contacts
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_contacts()
	{
		// First, get the list of IDs.
		$query = "SELECT contact_id as id from contacts_bugs where bug_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new Contact());
	}

	function get_accounts() {
		// First, get the list of IDs.
		$query = "SELECT account_id as id from {$this->rel_account_table} where bug_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new Account());
	}

	function get_cases() {
		// First, get the list of IDs.
		$query = "SELECT case_id as id from {$this->rel_case_table} where bug_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new aCase());
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

	function set_release() {
			$query = "SELECT r1.name from releases r1, $this->table_name i1 where r1.id = i1.release and i1.id = '$this->id' and i1.deleted=0 and r1.deleted=0";
			$result = $this->db->query($query,true," Error filling in additional detail fields: ");

			// Get the id and the name.
			$row = $this->db->fetchByAssoc($result);

			if($row != null)
			{
				$this->release_name = $row['name'];
			}
			else
			{
				$this->release_name = '';
			}
	}

	
	function set_fixed_in_release() {
			$query = "SELECT r1.name from releases r1, $this->table_name i1 where r1.id = i1.fixed_in_release and i1.id = '$this->id' and i1.deleted=0 and r1.deleted=0";
			$result = $this->db->query($query,true," Error filling in additional detail fields: ");

			// Get the id and the name.
			$row = $this->db->fetchByAssoc($result);

			
			
			if($row != null)
			{
				$this->fixed_in_release_name = $row['name'];
			}
			else
			{
				$this->fixed_in_release_name = '';
			}
			
			
	}
	
	
	function get_list_view_data(){
		global $current_language;
		$the_array = parent::get_list_view_data();
		$app_list_strings = return_app_list_strings_language($current_language);
		$mod_strings = return_module_language($current_language, 'Bugs');

		$this->set_release();
	
	$the_array['NAME'] = (($this->name == "") ? "<em>blank</em>" : $this->name);
	$the_array['PRIORITY'] = $app_list_strings['bug_priority_dom'][$this->priority];
	$the_array['STATUS'] =$app_list_strings['bug_status_dom'][$this->status];
	$the_array['RELEASE']= $this->release_name;
	$the_array['TYPE']=  $app_list_strings['bug_type_dom'][$this->type];
	$the_array['NUMBER'] = $this->number;
	$the_array['ENCODED_NAME']=$this->name;
					
			
	return  $the_array;
	}

	/**
		builds a generic search based on the query string using or
		do not include any $this-> because this is called on without having the class instantiated
	*/
	function build_generic_where_clause ($the_query_string) {
	$where_clauses = Array();
	$the_query_string = addslashes($the_query_string);
	array_push($where_clauses, "bugs.name like '$the_query_string%'");
	if (is_numeric($the_query_string)) array_push($where_clauses, "bugs.number like '$the_query_string%'");

	$the_where = "";
	foreach($where_clauses as $clause)
	{
		if($the_where != "") $the_where .= " or ";
		$the_where .= $clause;
	}

	return $the_where;
	}

	function parse_additional_headers(&$list_form, $xTemplateSection) {
		return $list_form;
	}

	function list_view_parse_additional_sections(&$list_form, $xTemplateSection) {
		return $list_form;
	}

	function set_notification_body($xtpl, $bug)
	{
		global $mod_strings, $app_list_strings;

		$bug->set_release();

		$xtpl->assign("BUG_SUBJECT", $bug->name);
		$xtpl->assign("BUG_TYPE", $app_list_strings['bug_type_dom'][$bug->type]);
		$xtpl->assign("BUG_PRIORITY", $app_list_strings['bug_priority_dom'][$bug->priority]);
		$xtpl->assign("BUG_STATUS", $app_list_strings['bug_status_dom'][$bug->status]);
		$xtpl->assign("BUG_RESOLUTION", $app_list_strings['bug_resolution_dom'][$bug->resolution]);
		$xtpl->assign("BUG_RELEASE", $bug->release_name);
		$xtpl->assign("BUG_DESCRIPTION", $bug->description);
		return $xtpl;
	}
}
?>
