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
 * $Id: Contact.php,v 1.150 2005/04/30 01:28:22 clint Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');
require_once('include/utils.php');
require_once('modules/Opportunities/Opportunity.php');
require_once('modules/Cases/Case.php');
require_once('modules/Tasks/Task.php');
require_once('modules/Notes/Note.php');
require_once('modules/Leads/Lead.php');
require_once('modules/Meetings/Meeting.php');
require_once('modules/Calls/Call.php');
require_once('modules/Emails/Email.php');
require_once('modules/Bugs/Bug.php');
require_once('modules/Users/User.php');


// Contact is used to store customer information.
class Contact extends SugarBean {
    var $field_name_map;
	// Stored fields
	var $id;
	var $name = '';
	var $lead_source;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $assigned_user_id;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;



	var $description;
	var $salutation;
	var $first_name;
	var $last_name;
	var $title;
	var $department;
	var $birthdate;
	var $reports_to_id;
	var $do_not_call;
	var $phone_home;
	var $phone_mobile;
	var $phone_work;
	var $phone_other;
	var $phone_fax;
	var $email1;
	var $email_and_name1;
	var $email_and_name2;
	var $email2;
	var $assistant;
	var $assistant_phone;
	var $email_opt_out;
	var $primary_address_street;
	var $primary_address_city;
	var $primary_address_state;
	var $primary_address_postalcode;
	var $primary_address_country;
	var $alt_address_street;
	var $alt_address_city;
	var $alt_address_state;
	var $alt_address_postalcode;
	var $alt_address_country;
	var $portal_name;
	var $portal_app;
	var $portal_active;

	// These are for related fields
	var $bug_id;
	var $account_name;
	var $account_id;
	var $reports_to_name;
	var $opportunity_role;
	var $opportunity_rel_id;
	var $opportunity_id;
	var $case_role;
	var $case_rel_id;
	var $case_id;
	var $task_id;
	var $note_id;
	var $meeting_id;
	var $call_id;
	var $email_id;
	var $assigned_user_name;






	var $invalid_email;
	var $table_name = "contacts";
	var $rel_account_table = "accounts_contacts";
	//This is needed for upgrade.  This table definition moved to Opportunity module.
	var $rel_opportunity_table = "opportunities_contacts";




	var $object_name = "Contact";
	var $module_dir = 'Contacts';

	var $new_schema = true;

	var $column_fields = Array("id"
		,"date_entered"
		,"date_modified"
		,"modified_user_id"
		,"assigned_user_id"
		, "created_by"



		,"salutation"
		,"first_name"
		,"last_name"
		,"lead_source"
		,"title"
		,"department"
		,"birthdate"
		,"reports_to_id"
		,"do_not_call"
		,"phone_home"
		,"phone_mobile"
		, "portal_name"
		, "portal_app"
		,"portal_active"
		,"phone_work"
		,"phone_other"
		,"phone_fax"
		,"email1"
		,"email2"
		,"assistant"
		,"assistant_phone"
		,"email_opt_out"
		,"primary_address_street"
		,"primary_address_city"
		,"primary_address_state"
		,"primary_address_postalcode"
		,"primary_address_country"
		,"alt_address_street"
		,"alt_address_city"
		,"alt_address_state"
		,"alt_address_postalcode"
		,"alt_address_country"
		,"description"
		,'invalid_email'
		);


	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('bug_id', 'assigned_user_name', 'account_name', 'account_id', 'opportunity_id', 'case_id', 'task_id', 'note_id', 'meeting_id', 'call_id', 'email_id'



	);

	// This is the list of fields that are in the lists.
	var $list_fields = Array('id', 'first_name', 'last_name', 'account_name', 'account_id', 'title', 'email1','email2', 'phone_work', 'assigned_user_name', 'assigned_user_id', "case_role", 'case_rel_id', 'opportunity_role', 'opportunity_rel_id','email_and_name1','email_and_name2'






,'invalid_email'
		);

	// This is the list of fields that are required
	var $required_fields =  array("last_name"=>1);

	function Contact() {
		
		 parent::SugarBean();
		global $current_user;








	}

	



	

	function get_summary_text()
	{
		return "$this->first_name $this->last_name";
	}

	/** Returns a list of the associated contacts who are direct reports
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_direct_reports()
	{
		// First, get the list of IDs.
		$query = "SELECT c1.id from contacts  c1, contacts  c2 where c2.id=c1.reports_to_id AND c2.id='$this->id' AND c1.deleted=0 order by c1.last_name";

		return $this->build_related_list($query, new Contact());
	}

	/** Returns a list of the associated opportunities
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_opportunities()
	{
		// First, get the list of IDs.
		$query = "SELECT opportunity_id as id from opportunities_contacts where contact_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new Opportunity());
	}

	function get_projects()
	{
		global $beanFiles;
		require_once($beanFiles['Project']);
		// the query that will retrieve a list of id's
		$query = "SELECT project.id FROM project "
			. "LEFT JOIN project_relation ON project_relation.project_id=project.id "
			. "LEFT JOIN {$this->table_name} ON {$this->table_name}.id=project_relation.relation_id "
			. "WHERE project_relation.relation_type='Contacts' "
			. "AND project.deleted=0 AND project_relation.deleted=0 AND {$this->table_name}.deleted=0 "
			. "AND {$this->table_name}.id='{$this->id}';";
		return $this->build_related_list($query, new Project());
	}

	function get_bugs()
	{
		// First, get the list of IDs.
		$query = "SELECT bug_id as id from contacts_bugs where contact_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new Bug());
	}



	/** Returns a list of the associated opportunities
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_leads()
	{
		// First, get the list of IDs.
		$query = "SELECT id  from leads where contact_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new Lead());
	}

		/** Returns a list of the associated opportunities
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_accounts()
	{
		// First, get the list of IDs.
		$query = "SELECT account_id as id from accounts_contacts where contact_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new Account());
	}


































	/** Returns a list of the associated cases
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_cases()
	{
		// First, get the list of IDs.
		$query = "SELECT case_id as id from contacts_cases where contact_id='$this->id' AND deleted=0";

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
		$query = "SELECT id from tasks where contact_id='$this->id' AND deleted=0";

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
		$query = "SELECT id from notes where contact_id='$this->id' AND deleted=0";

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
		$query = "SELECT meeting_id as id from meetings_contacts where contact_id='$this->id' AND deleted=0";

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
		$query = "SELECT call_id as id from calls_contacts where contact_id='$this->id' AND deleted=0";

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
		$query = "SELECT email_id as id from emails_contacts where contact_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new Email());
	}

	function add_list_count_joins(&$query, $where)
	{
		// accounts.name
		if(eregi("accounts.name", $where))
		{
			// add a join to the accounts table.
			$query .= "
	            LEFT JOIN accounts_contacts
	            ON contacts.id=accounts_contacts.contact_id
	            LEFT JOIN accounts
	            ON accounts_contacts.account_id=accounts.id
			";
		}
		$custom_join = $this->custom_fields->getJOIN();
		if($custom_join){
  				$query .= $custom_join['join'];
		}
		

	}

	function create_list_query(&$order_by, &$where)
	{
		$custom_join = $this->custom_fields->getJOIN();
		$query = "SELECT ";
		$query .= "
                accounts.name as account_name,
                accounts.id as account_id,
                users.user_name as assigned_user_name ";




		if($custom_join){
   				$query .= $custom_join['select'];
 		}
        $query .= " ,contacts.assigned_user_id,
				contacts.id,
                contacts.first_name,
                contacts.last_name,
                contacts.phone_work,
                contacts.title,
                contacts.email1,
                contacts.email2
                FROM contacts ";





		$query .=		"LEFT JOIN users
	                    ON contacts.assigned_user_id=users.id
	                    LEFT JOIN accounts_contacts
	                    ON contacts.id=accounts_contacts.contact_id
	                    LEFT JOIN accounts
	                    ON accounts_contacts.account_id=accounts.id ";



		if($custom_join){
  				$query .= $custom_join['join'];
		}

		$where_auto = "( accounts_contacts.deleted IS NULL OR accounts_contacts.deleted=0 )
                    AND ( accounts.deleted IS NULL OR accounts.deleted=0 )
                    AND contacts.deleted=0 ";

		if($where != "")
			$query .= "where ($where) AND ".$where_auto;
		else
			$query .= "where ".$where_auto;

		if(!empty($order_by))
			$query .= " ORDER BY $order_by";

		return $query;
	}



        function create_export_query(&$order_by, &$where)
        {
        		$custom_join = $this->custom_fields->getJOIN();
                         $query = "SELECT
                                contacts.*,
                                accounts.name as account_name,
                                users.user_name as assigned_user_name ";



						if($custom_join){
   							$query .= $custom_join['select'];
 						}
						 $query .= " FROM contacts ";




                         $query .= "LEFT JOIN users
	                                ON contacts.assigned_user_id=users.id ";



	                     $query .= "LEFT JOIN accounts_contacts
	                                ON contacts.id=accounts_contacts.contact_id
	                                LEFT JOIN accounts
	                                ON accounts_contacts.account_id=accounts.id ";
						if($custom_join){
  							$query .= $custom_join['join'];
						}

		$where_auto = "( accounts_contacts.deleted IS NULL OR accounts_contacts.deleted=0 )
                    AND ( accounts.deleted IS NULL OR accounts.deleted=0 )
                    AND contacts.deleted=0 ";

                if($where != "")
                        $query .= "where ($where) AND ".$where_auto;
                else
                        $query .= "where ".$where_auto;

                if(!empty($order_by))
                        $query .= " ORDER BY $order_by";

                return $query;
        }



	function save_relationship_changes($is_update)
    {

    	$this->clear_account_contact_relationship($this->id);

    	if($this->account_id != "")
    	{
    		$this->set_account_contact_relationship($this->id, $this->account_id);
    	}
    	if($this->opportunity_id != "")
    	{
    		$this->set_opportunity_contact_relationship($this->id, $this->opportunity_id);
    	}
    	if($this->case_id != "")
    	{
    		$this->set_case_contact_relationship($this->id, $this->case_id);
    	}
		if($this->bug_id != "") {
			$this->set_contact_bug_relationship($this->id, $this->bug_id);
		}
    	if($this->task_id != "")
    	{
    		$this->set_task_contact_relationship($this->id, $this->task_id);
    	}
    	if($this->note_id != "")
    	{
    		$this->set_note_contact_relationship($this->id, $this->note_id);
    	}
    	if($this->meeting_id != "")
    	{
    		$this->set_meeting_contact_relationship($this->id, $this->meeting_id);
    	}
    	if($this->call_id != "")
    	{
    		$this->set_call_contact_relationship($this->id, $this->call_id);
    	}
    	if($this->email_id != "")
    	{
    		$this->set_email_contact_relationship($this->id, $this->email_id);
    	}






    }
















	function clear_account_contact_relationship($contact_id)
	{
		$query = "delete from accounts_contacts where contact_id='$contact_id' and deleted=0";
		$this->db->query($query,true,"Error clearing account to contact relationship: ");
	}

	function set_contact_bug_relationship($contact_id, $bug_id) {
		$this->set_relationship('contacts_bugs', array('contact_id'=>$contact_id, 'bug_id'=>$bug_id));
		
	}

	function set_account_contact_relationship($contact_id, $account_id)
	{
		$this->set_relationship('accounts_contacts', array('contact_id'=>$contact_id, 'account_id'=>$account_id));
	}

	function set_opportunity_contact_relationship($contact_id, $opportunity_id)
	{
		global $app_list_strings;
		$this->set_relationship('opportunities_contacts' , array('opportunity_id'=>$opportunity_id , 'contact_id'=>$contact_id, 'contact_role'=>$app_list_strings['opportunity_relationship_type_default_key']));
	}

	function clear_opportunity_contact_relationship($contact_id)
	{
		$query = "delete from opportunities_contacts where contact_id='$contact_id' and deleted=0";
		$this->db->query($query,true,"Error clearing opportunity to contact relationship: ");
	}

	function set_case_contact_relationship($contact_id, $case_id)
	{
		global $app_list_strings;
		$this->set_relationship('contacts_cases', array('case_id'=>$case_id, 'contact_id'=>$contact_id , 'contact_role'=> $app_list_strings['case_relationship_type_default_key']));
	}

	function clear_case_contact_relationship($contact_id)
	{
		$query = "delete from contacts_cases where contact_id='$contact_id' and deleted=0";
		$this->db->query($query,true,"Error clearing case to contact relationship: ");
	}

	function set_task_contact_relationship($contact_id, $task_id)
	{
		$query = "UPDATE tasks set contact_id='$contact_id' where id='$task_id'";
		$this->db->query($query,true,"Error setting contact to task relationship: ");
	}

	function clear_task_contact_relationship($contact_id)
	{
		$query = "delete from tasks where contact_id='$contact_id' and deleted=0";
		$this->db->query($query,true,"Error clearing task to contact relationship: ");
	}

	function set_note_contact_relationship($contact_id, $note_id)
	{
		$query = "UPDATE notes set contact_id='$contact_id' where id='$note_id'";
		$this->db->query($query,true,"Error setting contact to note relationship: ");
	}

	function clear_note_contact_relationship($contact_id)
	{
		$query = "delete from notes where contact_id='$contact_id' and deleted=0";
		$this->db->query($query,true,"Error clearing note to contact relationship: ");
	}

	function set_meeting_contact_relationship($contact_id, $meeting_id)
	{
		$query = "insert into meetings_contacts set id='".create_guid()."', meeting_id='$meeting_id', contact_id='$contact_id'";
		$this->db->query($query,true,"Error setting meeting to contact relationship: "."<BR>$query");
	}

	function clear_meeting_contact_relationship($contact_id)
	{
		$query = "delete from meetings_contacts where contact_id='$contact_id' and deleted=0";
		$this->db->query($query,true,"Error clearing meeting to contact relationship: ");
	}

	function set_call_contact_relationship($contact_id, $call_id)
	{
		$query = "insert into calls_contacts set id='".create_guid()."', call_id='$call_id', contact_id='$contact_id'";
		$this->db->query($query,true,"Error setting meeting to contact relationship: "."<BR>$query");
	}

	function clear_call_contact_relationship($contact_id)
	{
		$query = "delete from calls_contacts where contact_id='$contact_id' and deleted=0";
		$this->db->query($query,true,"Error clearing call to contact relationship: ");
	}

	function set_email_contact_relationship($contact_id, $email_id)
	{
		$query = "insert into emails_contacts set id='".create_guid()."', email_id='$email_id', contact_id='$contact_id'";
		$this->db->query($query,true,"Error setting email to contact relationship: "."<BR>$query");
	}

	function clear_email_contact_relationship($contact_id)
	{
		$query = "delete from emails_contacts where contact_id='$contact_id' and deleted=0";
		$this->db->query($query,true,"Error clearing email to contact relationship: ");
	}

	function clear_contact_all_direct_report_relationship($contact_id)
	{
		$query = "UPDATE contacts set reports_to_id='' where reports_to_id='$contact_id' and deleted=0";
		$this->db->query($query,true,"Error clearing contact to direct report relationship: ");
	}

	function clear_contact_direct_report_relationship($contact_id)
	{
		$query = "UPDATE contacts set reports_to_id='' where id='$contact_id' and deleted=0";
		$this->db->query($query,true,"Error clearing contact to direct report relationship: ");
	}

	function clear_contact_bug_relationship($contact_id)
	{
		$query = "delete from contacts_bugs where contact_id='$contact_id' and deleted=0";
		$this->db->query($query,true,"Error clearing contact to bug relationship: ");
	}

	function mark_relationships_deleted($id)
	{
		$this->clear_contact_all_direct_report_relationship($id);
		$this->clear_account_contact_relationship($id);
		$this->clear_opportunity_contact_relationship($id);
		$this->clear_case_contact_relationship($id);
		$this->clear_task_contact_relationship($id);
		$this->clear_note_contact_relationship($id);
		$this->clear_call_contact_relationship($id);
		$this->clear_meeting_contact_relationship($id);
		$this->clear_email_contact_relationship($id);
		$this->clear_contact_bug_relationship($id);



	}

	function fill_in_additional_list_fields()
	{
		$this->email_and_name1 = $this->first_name." ". $this->last_name." &lt;".$this->email1."&gt;";
		$this->email_and_name2 = $this->first_name." ". $this->last_name." &lt;".$this->email2."&gt;";
		//$this->fill_in_additional_detail_fields();
	}

	function fill_in_additional_detail_fields()
	{
		// Fill in the assigned_user_name
		$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);




		$query = "SELECT acc.id, acc.name from accounts  acc, accounts_contacts  a_c where acc.id = a_c.account_id and a_c.contact_id = '$this->id' and a_c.deleted=0";
		$result = $this->db->query($query,true," Error filling in additional detail fields: ");

		// Get the id and the name.
		$row = $this->db->fetchByAssoc($result);

		if($row != null)
		{
			$this->account_name = $row['name'];
			$this->account_id = $row['id'];
		}
		else
		{
			$this->account_name = '';
			$this->account_id = '';
		}
		$query = "SELECT c1.first_name, c1.last_name from contacts  c1, contacts  c2 where c1.id = c2.reports_to_id and c2.id = '$this->id' and c1.deleted=0";
		$result = $this->db->query($query,true," Error filling in additional detail fields: ");

		// Get the id and the name.
		$row = $this->db->fetchByAssoc($result);

		if($row != null)
		{
			$this->reports_to_name = $row['first_name'].' '.$row['last_name'];
		}
		else
		{
			$this->reports_to_name = '';
		}

		$this->created_by_name = get_assigned_user_name($this->created_by);
		$this->modified_by_name = get_assigned_user_name($this->modified_user_id);
	}
	function get_list_view_data(){
		$temp_array = $this->get_list_view_array();
        $temp_array["ENCODED_NAME"]=$this->first_name.' '.$this->last_name;
    	return $temp_array;

	}
	function parse_additional_headers(&$list_form, $xTemplateSection) {

	}

	function list_view_parse_additional_sections(&$list_form, $xTemplateSection) {
		return $list_form;
	}
	/**
		builds a generic search based on the query string using or
		do not include any $this-> because this is called on without having the class instantiated
	*/
	function build_generic_where_clause ($the_query_string)
	{
		$where_clauses = Array();
		$the_query_string = addslashes($the_query_string);

		array_push($where_clauses, "contacts.last_name like '$the_query_string%'");
		array_push($where_clauses, "contacts.first_name like '$the_query_string%'");
		array_push($where_clauses, "accounts.name like '$the_query_string%'");
		array_push($where_clauses, "contacts.assistant like '$the_query_string%'");
		array_push($where_clauses, "contacts.email1 like '$the_query_string%'");
		array_push($where_clauses, "contacts.email2 like '$the_query_string%'");

		if (is_numeric($the_query_string))
		{
			array_push($where_clauses, "contacts.phone_home like '%$the_query_string%'");
			array_push($where_clauses, "contacts.phone_mobile like '%$the_query_string%'");
			array_push($where_clauses, "contacts.phone_work like '%$the_query_string%'");
			array_push($where_clauses, "contacts.phone_other like '%$the_query_string%'");
			array_push($where_clauses, "contacts.phone_fax like '%$the_query_string%'");
			array_push($where_clauses, "contacts.assistant_phone like '%$the_query_string%'");
		}

		$the_where = "";
		foreach($where_clauses as $clause)
		{
			if($the_where != "") $the_where .= " or ";
			$the_where .= $clause;
		}


		return $the_where;
	}

	function set_notification_body($xtpl, $contact)
	{
		$xtpl->assign("CONTACT_NAME", trim($contact->first_name . " " . $contact->last_name));
		$xtpl->assign("CONTACT_DESCRIPTION", $contact->description);

		return $xtpl;
	}

	function get_contact_id_by_email($email)
	{
		$where_clause = "(email1='$email' OR email2='$email') AND deleted=0";

                $query = "SELECT * FROM $this->table_name WHERE $where_clause";
                $this->log->debug("Retrieve $this->object_name: ".$query);
                $result =& $this->db->requireSingleResult($query, true, "Retrieving record $where_clause:");
                if( empty($result))
                {
                        return null;
                }

                $row = $this->db->fetchByAssoc($result, -1, true);
		return $row['id'];

	}

}



?>
