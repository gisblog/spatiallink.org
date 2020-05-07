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
 * $Id: Account.php,v 1.136 2005/04/30 01:28:22 clint Exp $
 * Description:  Defines the Account SugarBean Account entity with the necessary
 * methods and variables.
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');
require_once('modules/Contacts/Contact.php');
require_once('modules/Opportunities/Opportunity.php');
require_once('modules/Cases/Case.php');
require_once('modules/Calls/Call.php');
require_once('modules/Notes/Note.php');
require_once('modules/Emails/Email.php');
require_once('modules/Bugs/Bug.php');

// Account is used to store account information.
class Account extends SugarBean {
	var $field_name_map = array();
	// Stored fields
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $assigned_user_id;
	var $annual_revenue;
	var $billing_address_street;
	var $billing_address_city;
	var $billing_address_state;
	var $billing_address_country;
	var $billing_address_postalcode;
	var $description;
	var $email1;
	var $email2;
	var $employees;
	var $id;
	var $industry;
	var $name;
	var $ownership;
	var $parent_id;
	var $phone_alternate;
	var $phone_fax;
	var $phone_office;
	var $rating;
	var $shipping_address_street;
	var $shipping_address_city;
	var $shipping_address_state;
	var $shipping_address_country;
	var $shipping_address_postalcode;
	var $sic_code;
	var $ticker_symbol;
	var $account_type;
	var $website;
	var $custom_fields;

	var $created_by;
	var $created_by_name;
	var $modified_by_name;

	// These are for related fields
	var $opportunity_id;
	var $case_id;
	var $contact_id;
	var $task_id;
	var $note_id;
	var $meeting_id;
	var $call_id;
	var $email_id;
	var $member_id;
	var $parent_name;
	var $assigned_user_name;
	var $account_id = '';
	var $account_name = '';
	var $bug_id ='';
	var $module_dir = 'Accounts';
	







	var $table_name = "accounts";





	var $object_name = "Account";

	var $new_schema = true;

	var $column_fields = Array(
		"annual_revenue"
		,"billing_address_street"
		,"billing_address_city"
		,"billing_address_state"
		,"billing_address_postalcode"
		,"billing_address_country"
		,"date_entered"
		,"date_modified"
		,"modified_user_id"
		,"assigned_user_id"
		,"description"
		,"email1"
		,"email2"
		,"employees"
		,"id"
		,"industry"
		,"name"
		,"ownership"
		,"parent_id"
		,"phone_alternate"
		,"phone_fax"
		,"phone_office"
		,"rating"
		,"shipping_address_street"
		,"shipping_address_city"
		,"shipping_address_state"
		,"shipping_address_postalcode"
		,"shipping_address_country"
		,"sic_code"
		,"ticker_symbol"
		,"account_type"
		,"website"
		, "created_by"



		);


	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('assigned_user_name', 'assigned_user_id', 'opportunity_id', 'bug_id', 'case_id', 'contact_id', 'task_id', 'note_id', 'meeting_id', 'call_id', 'email_id', 'parent_name', 'member_id'



	);

	// This is the list of fields that are in the lists.
	var $list_fields = Array('id', 'name', 'website', 'phone_office', 'assigned_user_name', 'assigned_user_id'
	, 'billing_address_street'
	, 'billing_address_city'
	, 'billing_address_state'
	, 'billing_address_postalcode'
	, 'billing_address_country'
	, 'shipping_address_street'
	, 'shipping_address_city'
	, 'shipping_address_state'
	, 'shipping_address_postalcode'
	, 'shipping_address_country'




		);

	// This is the list of fields that are required.
	var $required_fields =  array("name"=>1);

	function Account() {
        parent::SugarBean();
        
		$this->log =LoggerManager::getLogger('account');

        $this->setupCustomFields('Accounts');
		foreach ($this->field_defs as $field)
		{
			$this->field_name_map[$field['name']] = $field;
		}




	}


	


	

	function get_summary_text()
	{
		return $this->name;
	}

	/** Returns a list of the associated accounts who are member orgs
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_member_accounts()
	{
		// First, get the list of IDs.
		$query = "SELECT a1.id from accounts a1, accounts a2 where a2.id=a1.parent_id AND a2.id='$this->id' AND a1.deleted=0";

		return $this->build_related_list($query, new Account());
	}

	function get_projects()
	{
		global $beanFiles;
		require_once($beanFiles['Project']);
		// the query that will retrieve a list of id's
		$query = "SELECT project.id FROM project "
			. "LEFT JOIN project_relation ON project_relation.project_id=project.id "
			. "LEFT JOIN {$this->table_name} ON {$this->table_name}.id=project_relation.relation_id "
			. "WHERE project_relation.relation_type='Accounts' "
			. "AND project.deleted=0 AND project_relation.deleted=0 AND {$this->table_name}.deleted=0 "
			. "AND {$this->table_name}.id='{$this->id}';";
		return $this->build_related_list($query, new Project());
	}

	function get_bugs()
	{
		// First, get the list of IDs.
		$query = "SELECT bug_id as id from accounts_bugs where account_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new Bug());
	}

	/** Returns a list of the associated contacts
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_contacts()
	{
		// First, get the list of IDs.
		$query = "SELECT contact_id as id from accounts_contacts where account_id='$this->id' AND (deleted is NULL OR deleted=0)";

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
		$query = "SELECT opportunity_id as id from accounts_opportunities where account_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new Opportunity());
	}

	function get_leads()
	{
		// First, get the list of IDs.
		$query = "SELECT id  from leads where account_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new Lead());
	}

	/** Returns a list of the associated cases
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_cases()
	{
		// First, get the list of IDs.
		$query = "SELECT id from cases where account_id='$this->id' AND deleted=0";

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

		return $this->build_related_list($query, new note());
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





















































	function save_relationship_changes($is_update)
    {
    	if(!empty($this->member_id))
    	{
    		$this->set_account_member_account_relationship($this->id, $this->member_id);
    	}
    	if(!empty($this->opportunity_id))
    	{
    		$this->clear_account_opportunity_relationship('',$this->opportunity_id);
    		$this->set_account_opportunity_relationship($this->id, $this->opportunity_id);
    	}
    	if(!empty($this->case_id))
    	{
    		$this->set_account_case_relationship($this->id, $this->case_id);
    	}
		if(!empty($this->contact_id))
    	{
    		$this->set_account_contact_relationship($this->id, $this->contact_id);
    	}
    	if(!empty($this->task_id))
    	{
    		$this->set_account_task_relationship($this->id, $this->task_id);
    	}
    	if(!empty($this->bug_id))
    	{
    		$this->set_account_bug_relationship($this->id, $this->bug_id);
    	}
    	if(!empty($this->note_id))
    	{
    		$this->set_account_note_relationship($this->id, $this->note_id);
    	}
    	if(!empty($this->meeting_id))
    	{
    		$this->set_account_meeting_relationship($this->id, $this->meeting_id);
    	}
    	if(!empty($this->call_id))
    	{
    		$this->set_account_call_relationship($this->id, $this->call_id);
    	}
    	if(!empty($this->email_id))
    	{
    		$this->set_account_email_relationship($this->id, $this->email_id);
    	}







    }

	function set_account_opportunity_relationship($account_id, $opportunity_id)
	{
		$this->set_relationship('accounts_opportunities', array('opportunity_id'=>$opportunity_id, 'account_id'=>$account_id));

	}

	function clear_account_opportunity_relationship($account_id='', $opportunity_id='')
	{
		if (empty($opportunity_id)) $opp_where = '';
		else $opp_where = " and opportunity_id = '$opportunity_id' ";
		if (empty($account_id)) $acc_where = '';
		else $acc_where = " and account_id = '$account_id' ";

		$query = "delete from accounts_opportunities where deleted=0 ".$opp_where.$acc_where;
		$this->db->query($query,true,"Error clearing account to opportunity relationship: ");
	}

	function set_account_bug_relationship($account_id, $bug_id)
	{
		$this->set_relationship('accounts_bugs', array('bug_id'=>$bug_id, 'account_id'=>$account_id));
	}

	function clear_account_bug_relationship($account_id)
	{
		$query = "delete from accounts_bugs where account_id='$account_id' and deleted=0";
		$this->db->query($query,true,"Error clearing account to bug relationship: ");
	}

	function set_account_case_relationship($account_id, $case_id)
	{
		$query = "update cases set account_id='$account_id' where id='$case_id'";
		$this->db->query($query,true,"Error setting account to case relationship: ");
	}

	function clear_account_case_relationship($account_id='', $case_id='')
	{
		if (empty($case_id)) $where = '';
		else $where = " and id = '$case_id'";
		$query = "UPDATE cases SET account_name = '', account_id = '' WHERE account_id = '$account_id' AND deleted = 0 " . $where;
		$this->db->query($query,true,"Error clearing account to case relationship: ");
	}

	function set_account_contact_relationship($account_id, $contact_id)
	{
		$this->set_relationship('accounts_contacts', array('account_id'=>$account_id, 'contact_id'=> $contact_id));

		
	}
	function set_contact_relationship($self_id, $contact_id){
		$this->set_account_contact_relationship($self_id, $contact_id);
	}

	function clear_account_contact_relationship($account_id)
	{
		$query = "delete from accounts_contacts where account_id='$account_id' and deleted=0";
		$this->db->query($query,true,"Error clearing account to contact relationship: ");
	}

	function clear_contact_relationship($contact_id)
	{
		$query = "delete from accounts_contacts where contact_id='$contact_id' and deleted=0";
		$this->db->query($query,true,"Error clearing a contact relationship: ");
	}

	function set_account_task_relationship($account_id, $task_id)
	{
		$query = "UPDATE tasks set parent_id='$account_id', parent_type='Accounts' where id='$task_id'";
		$this->db->query($query,true,"Error setting account to task relationship: ");
	}

	function clear_account_task_relationship($account_id)
	{
		$query = "UPDATE tasks set parent_id='', parent_type='' where parent_id='$account_id' and deleted=0";
		$this->db->query($query,true,"Error clearing account to task relationship: ");
	}

	function set_account_note_relationship($account_id, $note_id)
	{
		$query = "UPDATE notes set parent_id='$account_id', parent_type='Accounts' where id='$note_id'";
		$this->db->query($query,true,"Error setting account to note relationship: ");
	}

	function clear_account_note_relationship($account_id)
	{
		$query = "UPDATE notes set parent_id='', parent_type='' where parent_id='$account_id' and deleted=0";
		$this->db->query($query,true,"Error clearing account to note relationship: ");
	}

	function set_account_meeting_relationship($account_id, $meeting_id)
	{
		$query = "UPDATE meetings set parent_id='$account_id', parent_type='Accounts' where id='$meeting_id'";
		$this->db->query($query,true,"Error setting account to meeting relationship: ");
	}

	function clear_account_meeting_relationship($account_id)
	{
		$query = "UPDATE meetings set parent_id='', parent_type='' where parent_id='$account_id' and deleted=0";
		$this->db->query($query,true,"Error clearing account to meeting relationship: ");
	}

	function set_account_call_relationship($account_id, $call_id)
	{
		$query = "UPDATE calls set parent_id='$account_id', parent_type='Accounts' where id='$call_id'";
		$this->db->query($query,true,"Error setting account to call relationship: ");
	}

	function clear_account_call_relationship($account_id)
	{
		$query = "UPDATE calls set parent_id='', parent_type='' where parent_id='$account_id' and deleted=0";
		$this->db->query($query,true,"Error clearing account to call relationship: ");
	}

	function set_account_email_relationship($account_id, $email_id)
	{
		$query = "UPDATE emails set parent_id='$account_id', parent_type='Accounts' where id='$email_id'";
		$this->db->query($query,true,"Error setting account to email relationship: ");
	}

	function clear_account_email_relationship($account_id)
	{
		$query = "UPDATE emails set parent_id='', parent_type='' where parent_id='$account_id' and deleted=0";
		$this->db->query($query,true,"Error clearing account to email relationship: ");
	}

	function set_account_member_account_relationship($account_id, $member_id)
	{
		$query = "update accounts set parent_id='$account_id' where id='$member_id' and deleted=0";
		$this->db->query($query,true,"Error setting account to member account relationship: ");
	}

	function clear_account_account_relationship($account_id)
	{
		$query = "UPDATE accounts set parent_id='' where parent_id='$account_id' and deleted=0";
		$this->db->query($query,true,"Error clearing account to account relationship: ");
	}

	function clear_account_member_account_relationship($account_id)
	{
		$query = "UPDATE accounts set parent_id='' where id='$account_id' and deleted=0";
		$this->db->query($query,true,"Error clearing account to member account relationship: ");
	}

	function mark_relationships_deleted($id)
	{
		$this->clear_account_bug_relationship($id);
		$this->clear_account_account_relationship($id);
		$this->clear_account_contact_relationship($id);
		$this->clear_account_opportunity_relationship($id);
		$this->clear_account_case_relationship($id);
		$this->clear_account_task_relationship($id);
		$this->clear_account_note_relationship($id);
		$this->clear_account_meeting_relationship($id);
		$this->clear_account_call_relationship($id);
		$this->clear_account_email_relationship($id);



	}

	// This method is used to provide backward compatibility with old data that was prefixed with http://
	// We now automatically prefix http://
	function remove_redundant_http()
	{
		if(eregi("http://", $this->website))
		{
			$this->website = substr($this->website, 7);
		}
	}

	function fill_in_additional_list_fields()
	{
	// Fill in the assigned_user_name
	//	$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);




		$this->remove_redundant_http();
	}

	function fill_in_additional_detail_fields()
	{
		// Fill in the assigned_user_name
			$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);




		$query = "SELECT a1.name from accounts a1, accounts a2 where a1.id = a2.parent_id and a2.id = '$this->id' and a1.deleted=0";
		$result = $this->db->query($query,true," Error filling in additional detail fields: ");

		// Get the id and the name.
		$row = $this->db->fetchByAssoc($result);

		if($row != null)
		{
			$this->parent_name = $row['name'];
		}
		else
		{
			$this->parent_name = '';
		}

		$this->created_by_name = get_assigned_user_name($this->created_by);
		$this->modified_by_name = get_assigned_user_name($this->modified_user_id);

		$this->remove_redundant_http();
	}
	function get_list_view_data(){
		$temp_array = $this->get_list_view_array();
		$temp_array["ENCODED_NAME"]=$this->name;
//		$temp_array["ENCODED_NAME"]=htmlspecialchars($this->name, ENT_QUOTES);
		if(!empty($this->billing_address_state))
		{
			$temp_array["CITY"] = $this->billing_address_city . ', '. $this->billing_address_state;
		}
		else
		{
			$temp_array["CITY"] = $this->billing_address_city;
		}
		$temp_array["BILLING_ADDRESS_STREET"]  = preg_replace("/[\r]/",'',$this->billing_address_street);
		$temp_array["SHIPPING_ADDRESS_STREET"] = preg_replace("/[\r]/",'',$this->shipping_address_street);
		$temp_array["BILLING_ADDRESS_STREET"]  = preg_replace("/[\n]/",'\n',$temp_array["BILLING_ADDRESS_STREET"] );
		$temp_array["SHIPPING_ADDRESS_STREET"] = preg_replace("/[\n]/",'\n',$temp_array["SHIPPING_ADDRESS_STREET"] );
		return $temp_array;
	}
	/**
		builds a generic search based on the query string using or
		do not include any $this-> because this is called on without having the class instantiated
	*/
	function build_generic_where_clause ($the_query_string) {

	$where_clauses = Array();
	$the_query_string = addslashes($the_query_string);
	array_push($where_clauses, "accounts.name like '$the_query_string%'");
	if (is_numeric($the_query_string)) {
		array_push($where_clauses, "accounts.phone_alternate like '%$the_query_string%'");
		array_push($where_clauses, "accounts.phone_fax like '%$the_query_string%'");
		array_push($where_clauses, "accounts.phone_office like '%$the_query_string%'");
	}

	$the_where = "";
	foreach($where_clauses as $clause)
	{
		if(!empty($the_where)) $the_where .= " or ";
		$the_where .= $clause;
	}


	return $the_where;
}

	function create_export_query(&$order_by, &$where)
        {
        	$custom_join = $this->custom_fields->getJOIN();
			$query = "SELECT
					accounts.*,
                    users.user_name as assigned_user_name ";



                     if($custom_join){
						$query .=  $custom_join['select'];
					}
                    $query .= "FROM accounts ";




			if($custom_join){
					$query .=  $custom_join['join'];
				}
            $query .= " LEFT JOIN users
                    	ON accounts.assigned_user_id=users.id ";




            $where_auto = " users.status='ACTIVE'
            AND accounts.deleted=0 ";

            if($where != "")
                    $query .= "where ($where) AND ".$where_auto;
            else
                    $query .= "where ".$where_auto;

            if(!empty($order_by))
                    $query .= " ORDER BY $order_by";

            return $query;
        }

        function create_list_query(&$order_by, &$where)
        {

			$custom_join = $this->custom_fields->getJOIN();

                $query = "SELECT ";

                $query .= "
                    users.user_name assigned_user_name,
                    accounts.*";
                 if($custom_join){
					$query .=  $custom_join['select'];
				}



             $query .= " FROM  accounts ";
			




			 $query .= "LEFT JOIN users
                    	ON accounts.assigned_user_id=users.id ";
             if($custom_join){
					$query .=  $custom_join['join'];
				}




            $where_auto = " accounts.deleted=0 ";

            if($where != "")
                    $query .= "where ($where) AND ".$where_auto;
            else
                    $query .= "where ".$where_auto;

            if(!empty($order_by))
                    $query .= " ORDER BY $order_by";
            return $query;
        }

	function parse_additional_headers(&$list_form, $xTemplateSection) {

	}

	function list_view_parse_additional_sections(&$list_form, $xTemplateSection) {
		return $list_form;
	}


	function set_notification_body($xtpl, $account)
	{
		$xtpl->assign("ACCOUNT_NAME", $account->name);
		$xtpl->assign("ACCOUNT_TYPE", $account->account_type);
		$xtpl->assign("ACCOUNT_DESCRIPTION", $account->description);

		return $xtpl;
	}


}



?>
