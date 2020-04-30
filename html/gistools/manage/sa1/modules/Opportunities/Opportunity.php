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
 * $Id: Opportunity.php,v 1.131.2.3 2005/05/17 22:29:54 majed Exp $
 * Description:
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');
require_once('modules/Contacts/Contact.php');
require_once('modules/Tasks/Task.php');
require_once('modules/Notes/Note.php');
require_once('modules/Calls/Call.php');
require_once('modules/Leads/Lead.php');
require_once('modules/Emails/Email.php');
require_once('include/utils.php');

// Opportunity is used to store customer information.
class Opportunity extends SugarBean {
	var $field_name_map;
	// Stored fields
	var $id;
	var $lead_source;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $assigned_user_id;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;
	var $description;
	var $name;
	var $opportunity_type;
	var $amount;
	var $amount_usdollar;
	var $currency_id;
	var $date_closed;
	var $next_step;
	var $sales_stage;
	var $probability;






	// These are related
	var $account_name;
	var $account_id;
	var $contact_id;
	var $task_id;
	var $note_id;
	var $meeting_id;
	var $call_id;
	var $email_id;
	var $assigned_user_name;

	var $table_name = "opportunities";
	var $rel_account_table = "accounts_opportunities";
	var $rel_contact_table = "opportunities_contacts";
	var $module_dir = "Opportunities";



	var $track_on_save=true;
	
	var $object_name = "Opportunity";

	var $column_fields = Array("id"
		, "name"
		, "opportunity_type"
		, "lead_source"
		, "amount"
		, "amount_backup"
		, "currency_id"
		, "amount_usdollar"
		, "date_entered"
		, "date_modified"
		, "modified_user_id"
		, "assigned_user_id"
		, "created_by"



		, "date_closed"
		, "next_step"
		, "sales_stage"
		, "probability"
		, "description"
		);


	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('assigned_user_name', 'assigned_user_id', 'account_name', 'account_id', 'contact_id', 'task_id', 'note_id', 'meeting_id', 'call_id', 'email_id'



	);

	// This is the list of fields that are in the lists.
	var $list_fields = Array('id', 'name', 'account_id', 'sales_stage', 'account_name', 'date_closed', 'amount', 'assigned_user_name', 'assigned_user_id','sales_stage','probability','lead_source','opportunity_type'




	, "amount_usdollar"
	);

	var $required_fields = Array('name'=>1, 'date_closed'=>2, 'amount'=>3, 'sales_stage'=>4, 'account_name'=>5);

	function Opportunity() {
		parent::SugarBean();
		global $sugar_config;
		if(!$sugar_config['require_accounts']){
			unset($this->required_fields['account_name']);	
		}



	}

	var $new_schema = true;

	

	function get_summary_text()
	{
		return "$this->name";
	}

	function create_list_query($order_by, $where)
	{
		
$custom_join = $this->custom_fields->getJOIN();
                $query = "SELECT ";
            
                $query .= "
                            accounts.id as account_id,
                            accounts.name as account_name,
                            users.user_name as assigned_user_name ";



                            if($custom_join){
   								$query .= $custom_join['select'];
 							}
                            $query .= " ,opportunities.*
                            FROM opportunities ";






$query .= 			"LEFT JOIN users
                            ON opportunities.assigned_user_id=users.id ";



                            $query .= "LEFT JOIN $this->rel_account_table
                            ON opportunities.id=$this->rel_account_table.opportunity_id
                            LEFT JOIN accounts
                            ON $this->rel_account_table.account_id=accounts.id ";
			    if($custom_join){
  					$query .= $custom_join['join'];
				}
			    
		$where_auto = "
		($this->rel_account_table.deleted is null OR $this->rel_account_table.deleted=0)
		AND (accounts.deleted is null OR accounts.deleted=0)  
		AND opportunities.deleted=0";

		if($where != "")
			$query .= "where ($where) AND ".$where_auto;
		else
			$query .= "where ".$where_auto;

		if($order_by != "")
			$query .= " ORDER BY $order_by";
		else
			$query .= " ORDER BY opportunities.name";

		return $query;
	}


        function create_export_query($order_by, $where)
        {
								$custom_join = $this->custom_fields->getJOIN();
                                $query = "SELECT
                                opportunities.*,
                                accounts.name as account_name,
                                users.user_name as assigned_user_name ";



								if($custom_join){
   									$query .= $custom_join['select'];
 								}
	                            $query .= "FROM opportunities ";




		$query .= 				"LEFT JOIN users
                                ON opportunities.assigned_user_id=users.id";



                                $query .= " LEFT JOIN $this->rel_account_table
                                ON opportunities.id=$this->rel_account_table.opportunity_id
                                LEFT JOIN accounts
                                ON $this->rel_account_table.account_id=accounts.id ";
								if($custom_join){
  									$query .= $custom_join['join'];
								}
		$where_auto = "
			($this->rel_account_table.deleted is null OR $this->rel_account_table.deleted=0)
			AND (accounts.deleted is null OR accounts.deleted=0)  
			AND opportunities.deleted=0";

        if($where != "")
                $query .= "where $where AND ".$where_auto;
        else
                $query .= "where ".$where_auto;

        if($order_by != "")
                $query .= " ORDER BY $order_by";
        else
                $query .= " ORDER BY opportunities.name";
        return $query;
    }



	function save_relationship_changes($is_update)
    {
    	$this->clear_opportunity_account_relationship($this->id);

		if($this->account_id != "")
    	{
    		$this->set_opportunity_account_relationship($this->id, $this->account_id);
    	}
    	if($this->contact_id != "")
    	{
    		$this->set_opportunity_contact_relationship($this->id, $this->contact_id);
    	}
    	if($this->task_id != "")
    	{
    		$this->set_opportunity_task_relationship($this->id, $this->task_id);
    	}
    	if($this->note_id != "")
    	{
    		$this->set_opportunity_note_relationship($this->id, $this->note_id);
    	}
    	if($this->meeting_id != "")
    	{
    		$this->set_opportunity_meeting_relationship($this->id, $this->meeting_id);
    	}
    	if($this->call_id != "")
    	{
    		$this->set_opportunity_call_relationship($this->id, $this->call_id);
    	}
    	if($this->email_id != "")
    	{
    		$this->set_opportunity_email_relationship($this->id, $this->email_id);
    	}






    }























	function get_projects()
	{
		global $beanFiles;
		require_once($beanFiles['Project']);
		// the query that will retrieve a list of id's
		$query = "SELECT project.id FROM project "
			. "LEFT JOIN project_relation ON project_relation.project_id=project.id "
			. "LEFT JOIN {$this->table_name} ON {$this->table_name}.id=project_relation.relation_id "
			. "WHERE project_relation.relation_type='Opportunities' "
			. "AND project.deleted=0 AND project_relation.deleted=0 AND {$this->table_name}.deleted=0 "
			. "AND {$this->table_name}.id='{$this->id}';";
		return $this->build_related_list($query, new Project());
	}















	function set_opportunity_account_relationship($opportunity_id, $account_id)
	{
		$this->set_relationship($this->rel_account_table, array('account_id'=> $account_id ,'opportunity_id'=> $opportunity_id));
	}

	function clear_opportunity_account_relationship($opportunity_id)
	{
		$query = "delete from $this->rel_account_table where opportunity_id='$opportunity_id' and deleted=0";
		$this->db->query($query, true, "Error clearing account to opportunity relationship: ");
	}

	function set_opportunity_contact_relationship($opportunity_id, $contact_id)
	{
		global $app_list_strings;
		$default = $app_list_strings['opportunity_relationship_type_default_key'];
		$this->set_relationship($this->rel_contact_table, array('opportunity_id'=> $opportunity_id , 'contact_id'=> $contact_id , 'contact_role'=>$default));
	}

	function clear_opportunity_contact_relationship($opportunity_id)
	{
		$query = "delete from $this->rel_contact_table where opportunity_id='$opportunity_id' and deleted=0";
		$this->db->query($query, true, "Error marking record deleted: ");

	}

	function set_opportunity_task_relationship($opportunity_id, $task_id)
	{
		$query = "UPDATE tasks set parent_id='$opportunity_id', parent_type='Opportunities' where id='$task_id'";
		$this->db->query($query, true, "Error setting opportunity to task relationship: ");

	}

	function clear_opportunity_task_relationship($opportunity_id)
	{
		$query = "UPDATE tasks set parent_id='', parent_type='' where parent_id='$opportunity_id'";
		$this->db->query($query, true, "Error clearing opportunity to task relationship: ");

	}

	function set_opportunity_note_relationship($opportunity_id, $note_id)
	{
		$query = "UPDATE notes set parent_id='$opportunity_id', parent_type='Opportunities' where id='$note_id'";
		$this->db->query($query, true, "Error setting opportunity to note relationship: ");
	}

	function clear_opportunity_note_relationship($opportunity_id)
	{
		$query = "UPDATE notes set parent_id='', parent_type='' where parent_id='$opportunity_id'";
		$this->db->query($query, true, "Error clearing opportunity to note relationship: ");
	}

	function set_opportunity_meeting_relationship($opportunity_id, $meeting_id)
	{
		$query = "UPDATE meetings set parent_id='$opportunity_id', parent_type='Opportunities' where id='$meeting_id'";
		$this->db->query($query, true,"Error setting opportunity to meeting relationship: ");
	}

	function clear_opportunity_meeting_relationship($opportunity_id)
	{
		$query = "UPDATE meetings set parent_id='', parent_type='' where parent_id='$opportunity_id'";
		$this->db->query($query, true,"Error clearing opportunity to meeting relationship: ");
	}

	function set_opportunity_call_relationship($opportunity_id, $call_id)
	{
		$query = "UPDATE calls set parent_id='$opportunity_id', parent_type='Opportunities' where id='$call_id'";
		$this->db->query($query, true,"Error setting opportunity to call relationship: ");
	}

	function clear_opportunity_call_relationship($opportunity_id)
	{
		$query = "UPDATE calls set parent_id='', parent_type='' where parent_id='$opportunity_id'";
		$this->db->query($query, true,"Error clearing opportunity to call relationship: ");
	}

	function set_opportunity_email_relationship($opportunity_id, $email_id)
	{
		$query = "UPDATE emails set parent_id='$opportunity_id', parent_type='Opportunities' where id='$email_id'";
		$this->db->query($query, true,"Error setting opportunity to email relationship: ");
	}

	function clear_opportunity_email_relationship($opportunity_id)
	{
		$query = "UPDATE emails set parent_id='', parent_type='' where parent_id='$opportunity_id'";
		$this->db->query($query, true,"Error clearing opportunity to email relationship: ");
	}

	function mark_relationships_deleted($id)
	{
		$this->clear_opportunity_contact_relationship($id);
		$this->clear_opportunity_account_relationship($id);
		$this->clear_opportunity_task_relationship($id);
		$this->clear_opportunity_note_relationship($id);
		$this->clear_opportunity_meeting_relationship($id);
		$this->clear_opportunity_call_relationship($id);



	}

	function fill_in_additional_list_fields()
	{
	}

	function fill_in_additional_detail_fields()
	{
		require_once('modules/Currencies/Currency.php');
		$currency = new Currency();
		$currency->retrieve($this->currency_id);
		if($currency->id != $this->currency_id || $currency->deleted == 1){
				$this->amount = $this->amount_usdollar;
				$this->currency_id = $currency->id;
		}

		// Fill in the assigned_user_name
		$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);




		$query = "SELECT acc.id, acc.name from accounts  acc, $this->rel_account_table  a_o where acc.id = a_o.account_id and a_o.opportunity_id = '$this->id' and a_o.deleted=0 and acc.deleted=0";
		$result =& $this->db->query($query, true,"Error filling in additional detail fields: ");

		// Get the id and the name.
		$row = $this->db->fetchByAssoc($result);

		if($row != null)
		{
			$this->account_name = stripslashes($row['name']);
			$this->account_id 	= stripslashes($row['id']);
		}
		else
		{
			$this->account_name = '';
			$this->account_id = '';
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
		$query = "SELECT c.id, c.first_name, c.last_name, c.title, c.email1, c.phone_work, o_c.contact_role as opportunity_role, o_c.id as opportunity_rel_id ".
				 "from $this->rel_contact_table o_c, contacts c ".
				 "where o_c.opportunity_id = '$this->id' and o_c.deleted=0 and c.id = o_c.contact_id AND c.deleted=0 order by c.last_name";

	    $temp = Array('id', 'first_name', 'last_name', 'title', 'email1', 'phone_work', 'opportunity_role', 'opportunity_rel_id');
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

	function get_leads()
	{
		// First, get the list of IDs.
		$query = "SELECT id  from leads where opportunity_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new Lead());
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

	function update_currency_id($fromid, $toid){
		$idequals = '';
		require_once('modules/Currencies/Currency.php');
		$currency = new Currency();
		$currency->retrieve($toid);
		foreach($fromid as $f){
			if(!empty($idequals)){
				$idequals .=' or ';
			}
			$idequals .= "currency_id='$f'";
		}

		if(!empty($idequals)){
			$query = "select amount, id from opportunities where (". $idequals. ") and deleted=0 and opportunities.sales_stage <> 'Closed Won' AND opportunities.sales_stage <> 'Closed Lost';";
			$result =& $this->db->query($query);
			while($row = $this->db->fetchByAssoc($result)){

				$query = "update opportunities set currency_id='".$currency->id."', amount_usdollar='".$currency->convertToDollar($row['amount'])."' where id='".$row['id']."';";
				$this->db->query($query);

			}

	}
	}

	function get_list_view_data(){
		global $current_language, $current_user, $mod_strings, $app_list_strings;
		$app_strings = return_application_language($current_language);
		$symbol = $app_strings['LBL_CURRENCY_SYMBOL'];
		require_once('modules/Currencies/Currency.php');
		$currency = new Currency();
		if($current_user->getPreference('currency') ){
			$currency->retrieve($current_user->getPreference('currency'));
			$symbol = $currency->symbol;
		}else{
			$currency->retrieve('-99');
			$symbol = $currency->symbol;
		}

		$temp_array = $this->get_list_view_array();

		$temp_array['SALES_STAGE'] = translate('sales_stage_dom', '', $temp_array['SALES_STAGE']);
		$temp_array['AMOUNT'] =   $symbol.'&nbsp;'. round($currency->convertFromDollar($this->amount_usdollar),2);
	        $temp_array["ENCODED_NAME"]=$this->name;
//		$temp_array["ENCODED_NAME"]=htmlspecialchars($this->name, ENT_QUOTES);
		return $temp_array;
	}
	/**
		builds a generic search based on the query string using or
		do not include any $this-> because this is called on without having the class instantiated
	*/
	function build_generic_where_clause ($the_query_string) {
	$where_clauses = Array();
	$the_query_string = addslashes($the_query_string);
	array_push($where_clauses, "opportunities.name like '$the_query_string%'");
	array_push($where_clauses, "accounts.name like '$the_query_string%'");

	$the_where = "";
	foreach($where_clauses as $clause)
	{
		if($the_where != "") $the_where .= " or ";
		$the_where .= $clause;
	}


	return $the_where;
}

	function save($check_notify = FALSE) {
		require_once('modules/Currencies/Currency.php');
			//US DOLLAR
			if(isset($this->amount) && !number_empty($this->amount)){

				$currency = new Currency();
				$currency->retrieve($this->currency_id);
				$this->amount_usdollar = $currency->convertToDollar($this->amount);

			}
		return parent::save($check_notify);

	}

	function parse_additional_headers(&$list_form, $xTemplateSection) {

	}

	function list_view_parse_additional_sections(&$list_form, $xTemplateSection) {
		return $list_form;
	}

	function set_notification_body($xtpl, $oppty)
	{
		global $app_list_strings;
		
		$xtpl->assign("OPPORTUNITY_NAME", $oppty->name);
		$xtpl->assign("OPPORTUNITY_AMOUNT", $oppty->amount);
		$xtpl->assign("OPPORTUNITY_CLOSEDATE", $oppty->date_closed);
		$xtpl->assign("OPPORTUNITY_STAGE", (isset($oppty->sales_stage)?$app_list_strings['sales_stage_dom'][$oppty->sales_stage]:""));
		$xtpl->assign("OPPORTUNITY_DESCRIPTION", $oppty->description);

		return $xtpl;
	}

}









?>
