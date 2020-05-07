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
 * $Id: Lead.php,v 1.55.2.2 2005/05/13 17:45:43 ajay Exp $
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
require_once('modules/Meetings/Meeting.php');
require_once('modules/Calls/Call.php');
require_once('modules/Emails/Email.php');

// Lead is used to store profile information for people who may become customers.
class Lead extends SugarBean {
	var $field_name_map;
	// Stored fields
	var $id;
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
	var $reports_to_id;
	var $do_not_call;
	var $phone_home;
	var $phone_mobile;
	var $phone_work;
	var $phone_other;
	var $phone_fax;
	var $refered_by;
	var $email1;
	var $email2;
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
	var $name ;
	var $portal_name;
	var $portal_app;
	var $contact_id;
	var $contact_name;
	var $account_id;
	var $opportunity_id;
	var $opportunity_name;
	var $opportunity_amount;
	//used for vcard export only
	var $birthdate;
	var $invalid_email;
	var $status;
	var $status_description;

	var $lead_source;
	var $lead_source_description;
	// These are for related fields
	var $account_name;
	var $account_site;
	var $account_description;
	var $case_role;
	var $case_rel_id;
	var $case_id;
	var $task_id;
	var $note_id;
	var $meeting_id;
	var $call_id;
	var $email_id;
	var $assigned_user_name;






	var $table_name = "leads";

	var $object_name = "Lead";
	var $object_names = "Leads";
	var $module_dir = "Leads";


	var $new_schema = true;

	var $column_fields = Array("id"
		,"refered_by"
		,"date_entered"
		,"date_modified"
		,"modified_user_id"
		,"assigned_user_id"
		, "created_by"



		,"salutation"
		,"first_name"
		,"last_name"
		,"lead_source"
		,"lead_source_description"
		,"title"
		,"department"
		,"reports_to_id"
		,"do_not_call"
		,"phone_home"
		,"phone_mobile"
		,"phone_work"
		,"phone_other"
		,"phone_fax"
		,"email1"
		,"email2"
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
		,"status"
		,"status_description"
		,"account_name"
		,"account_description"
		,"account_id"
		,"opportunity_id"
		,"contact_id"
		,"opportunity_amount"
		,"opportunity_name"
		,"portal_name"
		,"portal_app"
		,'invalid_email'

		);



	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('assigned_user_name', 'task_id', 'note_id', 'meeting_id', 'call_id', 'email_id');

	// This is the list of fields that are in the lists.
	var $list_fields = Array('id', 'first_name', 'last_name', 'account_name', 'title', 'email1', 'phone_work', 'assigned_user_name', 'assigned_user_id', 'lead_source', 'lead_source_description', 'refered_by', 'opportunity_name', 'opportunity_amount', 'date_entered', 'status'




		,'invalid_email'
		);

	// This is the list of fields that are required
	var $required_fields =  array("last_name"=>1);

	function Lead() {
		parent::SugarBean();



	}

	

	function get_summary_text()
	{
		return "$this->first_name $this->last_name";
	}








	/** Returns a list of the associated tasks
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_tasks()
	{
		// First, get the list of IDs.
		$query = "SELECT id from tasks where parent_type='$this->object_names' and parent_id='$this->id' AND deleted=0";

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
		$query = "SELECT id from notes where parent_type='$this->object_names' and parent_id='$this->id' AND deleted=0";

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
		$query = "SELECT  id from meetings where parent_type='$this->object_names' and parent_id='$this->id'AND deleted=0";

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
		$query = "SELECT  id from calls where parent_type='$this->object_names' and parent_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new Call());
	}

	function get_account()
	{
		if(isset($this->account_id) && !empty($this->account_id)){
			$query = "SELECT name FROM accounts WHERE id='{$this->account_id}';";
			$result = $this->db->requireSingleResult($query);
			if(!empty($result)){
				$row = $this->db->fetchByAssoc($result);
				$this->account_name = $row['name'];
			}

	}}
	function get_opportunity()
	{
		if(isset($this->opportunity_id) && !empty($this->opportunity_id)){
			$query = "SELECT name FROM opportunities WHERE id='{$this->opportunity_id}';";
			$result = $this->db->requireSingleResult($query);
			if(!empty($result)){
				$row = $this->db->fetchByAssoc($result);
				$this->opportunity_name = $row['name'];
			}

	}}
	function get_contact()
	{
		if(isset($this->contact_id) && !empty($this->contact_id)){
			$query = "SELECT first_name, last_name FROM contacts WHERE id='{$this->contact_id}';";

			$result = $this->db->requireSingleResult($query);
			if(!empty($result)){
				$row= $this->db->fetchByAssoc($result);
				$this->contact_name = $row['first_name']. ' '. $row['last_name'];
			}

	}}

	/** Returns a list of the associated emails
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_emails()
	{
		// First, get the list of IDs.
		$query = "SELECT  id from emails where parent_type='$this->object_names' and parent_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new Email());
	}

	function create_list_query(&$order_by, &$where)
	{
		$custom_join = $this->custom_fields->getJOIN();
                $query = "SELECT ";
              

			$query .= "
                                leads.account_name,
								leads.account_id,
                                leads.status,
                                users.user_name as assigned_user_name,
                                leads.id,
                                leads.first_name,
                                leads.last_name,
                                leads.phone_work,
								leads.lead_source,
                                leads.title,
                                leads.email1,
								leads.date_entered";



			if($custom_join){
   				$query .= $custom_join['select'];
 			}
            $query .= " FROM leads ";





			$query .= "			LEFT JOIN users
                                ON leads.assigned_user_id=users.id ";



			if($custom_join){
  				$query .= $custom_join['join'];
			}

			$where_auto = " leads.deleted=0 ";

		if($where != "")
			$query .= "where ($where) AND ".$where_auto;
		else
			$query .= "where ".$where_auto; //."and (leads.converted='0')";

		if(!empty($order_by))
			$query .= " ORDER BY $order_by";

		return $query;
	}



        function create_export_query(&$order_by, &$where)
        {
                         $custom_join = $this->custom_fields->getJOIN();
                         $query = "SELECT
                                leads.*,

                                users.user_name as assigned_user_name";



                         if($custom_join){
   							$query .= $custom_join['select'];
 						}
                         $query .= " FROM leads ";




			$query .= "			LEFT JOIN users
                                ON leads.assigned_user_id=users.id ";



            	if($custom_join){
  					$query .= $custom_join['join'];
				}

                        $where_auto = " leads.deleted=0 ";

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

    	if($this->task_id != "")
    	{
    		$this->set_task_lead_relationship($this->id, $this->task_id);
    	}
    	if($this->note_id != "")
    	{
    		$this->set_note_lead_relationship($this->id, $this->note_id);
    	}
    	if($this->meeting_id != "")
    	{
    		$this->set_meeting_lead_relationship($this->id, $this->meeting_id);
    	}
    	if($this->call_id != "")
    	{
    		$this->set_call_lead_relationship($this->id, $this->call_id);
    	}
    	if($this->email_id != "")
    	{
    		$this->set_email_lead_relationship($this->id, $this->email_id);
    	}
    }

    function converted_lead($leadid, $contactid, $accountid, $opportunityid){
    	$query = "UPDATE leads set status='Converted', converted='1', contact_id=$contactid, account_id=$accountid, opportunity_id=$opportunityid where  id=$leadid and deleted=0";
		$this->db->query($query,true,"Error converting lead: ");
    }

	function set_task_lead_relationship($lead_id, $task_id)
	{
		$query = "UPDATE tasks set parent_type='Leads', parent_id='$lead_id' where id='$task_id'";
		$this->db->query($query,true,"Error setting lead to task relationship: ");
	}

	function clear_task_lead_relationship($lead_id)
	{
		$query = "UPDATE tasks set parent_type=NULL, parent_id=NULL where  parent_type='Leads' and parent_id='$lead_id' and deleted=0";
		$this->db->query($query,true,"Error clearing task to lead relationship: ");
	}

	function set_note_lead_relationship($lead_id, $note_id)
	{
		$query = "UPDATE notes set parent_type='Leads', parent_id='$lead_id' where id='$note_id'";
		$this->db->query($query,true,"Error setting lead to note relationship: ");
	}

	function clear_note_lead_relationship($lead_id)
	{
		$query = "UPDATE notes set parent_type=NULL, parent_id=NULL where parent_type='Leads' and parent_id='$lead_id' and deleted=0";
		$this->db->query($query,true,"Error clearing note to lead relationship: ");
	}

	function set_meeting_lead_relationship($lead_id, $meeting_id)
	{
		$query = "insert into meetings set id='".create_guid()."', meeting_id='$meeting_id', parent_type='Leads', parent_id='$lead_id'";
		$this->db->query($query,true,"Error setting meeting to lead relationship: "."<BR>$query");
	}

	function clear_meeting_lead_relationship($lead_id)
	{
		$query = "UPDATE meetings set parent_type=NULL, parent_id=NULL where parent_type='Leads' and parent_id='$lead_id' and deleted=0";
		$this->db->query($query,true,"Error clearing meeting to lead relationship: ");
	}

	function set_call_lead_relationship($lead_id, $call_id)
	{
		$query = "insert into calls set id='".create_guid()."', call_id='$call_id', parent_type='Leads', parent_id='$lead_id'";
		$this->db->query($query,true,"Error setting meeting to lead relationship: "."<BR>$query");
	}

	function clear_call_lead_relationship($lead_id)
	{
		$query = "UPDATE calls set parent_type=NULL, parent_id=NULL where parent_type='Leads' and parent_id='$lead_id' and deleted=0";
		$this->db->query($query,true,"Error clearing call to lead relationship: ");
	}

	function set_email_lead_relationship($lead_id, $email_id)
	{
		$query = "insert into emails_leads set id='".create_guid()."', email_id='$email_id', parent_type='Leads', parent_id='$lead_id'";
		$this->db->query($query,true,"Error setting email to lead relationship: "."<BR>$query");
	}

	function clear_email_lead_relationship($lead_id)
	{
		$query = "UPDATE emails set parent_type=NULL, parent_id=NULL where parent_type='Leads' and parent_id='$lead_id' and deleted=0";
		$this->db->query($query,true,"Error clearing email to lead relationship: ");
	}



	function mark_relationships_deleted($id)
	{
		$this->clear_task_lead_relationship($id);
		$this->clear_note_lead_relationship($id);
		$this->clear_call_lead_relationship($id);
		$this->clear_meeting_lead_relationship($id);
		$this->clear_email_lead_relationship($id);
	}

	function fill_in_additional_list_fields()
	{
		//$this->fill_in_additional_detail_fields();
		$this->get_account();

	}

	function fill_in_additional_detail_fields()
	{
		//Fill in the assigned_user_name
		//if(!empty($this->status))
		//$this->status = translate('lead_status_dom', '', $this->status);
		$this->name = $this->first_name . ' ' . $this->last_name;
		$this->get_contact();
		$this->get_opportunity();
		$this->get_account();
		$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);




		$this->created_by_name = get_assigned_user_name($this->created_by);
		$this->modified_by_name = get_assigned_user_name($this->modified_user_id);
	}
	function get_list_view_data(){
		global $app_list_strings;
		$temp_array = $this->get_list_view_array();
		if (!empty($temp_array['STATUS'])) {
			$temp_array['STATUS'] = $app_list_strings['lead_status_dom'][$temp_array['STATUS']];
		}
        $temp_array["ENCODED_NAME"]=$this->first_name.' '.$this->last_name;   
    	return $temp_array;

	}

	/**
		builds a generic search based on the query string using or
		do not include any $this-> because this is called on without having the class instantiated
	*/
	function build_generic_where_clause ($the_query_string) {
	$where_clauses = Array();
	$the_query_string = addslashes($the_query_string);

	array_push($where_clauses, "leads.last_name like '$the_query_string%'");
	array_push($where_clauses, "leads.account_name like '$the_query_string%'");
	array_push($where_clauses, "leads.first_name like '$the_query_string%'");
	array_push($where_clauses, "leads.email1 like '$the_query_string%'");
	array_push($where_clauses, "leads.email2 like '$the_query_string%'");
	if (is_numeric($the_query_string)) {
		array_push($where_clauses, "leads.phone_home like '%$the_query_string%'");
		array_push($where_clauses, "leads.phone_mobile like '%$the_query_string%'");
		array_push($where_clauses, "leads.phone_work like '%$the_query_string%'");
		array_push($where_clauses, "leads.phone_other like '%$the_query_string%'");
		array_push($where_clauses, "leads.phone_fax like '%$the_query_string%'");

	}

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

	function set_notification_body($xtpl, $lead)
	{
		global $app_list_strings;		
		
		$xtpl->assign("LEAD_NAME", trim($lead->first_name . " " . $lead->last_name));
		$xtpl->assign("LEAD_SOURCE", (isset($lead->lead_source) ? $app_list_strings['lead_source_dom'][$lead->lead_source] : ""));
		$xtpl->assign("LEAD_STATUS", (isset($lead->status)? $app_list_strings['lead_status_dom'][$lead->status]:""));
		$xtpl->assign("LEAD_DESCRIPTION", $lead->description);

		return $xtpl;
	}

}



?>
