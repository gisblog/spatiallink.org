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
 * $Id: Campaign.php,v 1.20.2.1 2005/05/05 20:54:12 joey Exp $
 * Description:
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');
require_once('include/utils.php');
require_once('modules/ProspectLists/ProspectList.php');

class Campaign extends SugarBean {
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




	var $name;
	var $start_date;
	var $end_date;
	var $status;
	var $budget;
	var $expected_cost;
	var $actual_cost;
	var $expected_revenue;
	var $campaign_type;
	var $objective;
	var $content;
	var $tracker_key;
	var $tracker_text;
	var $tracker_count;
	var $refer_url;
	
	// These are related
	var $assigned_user_name;

	// module name definitions and table relations
	var $table_name = "campaigns";
	var $rel_prospect_list_table = "prospect_list_campaigns";
	var $object_name = "Campaign";
	var $module_dir = 'Campaigns';

	var $column_fields = array(
				"id", "date_entered",
				"date_modified", "modified_user_id",
				"assigned_user_id", "created_by",



				"name", "start_date",
				"end_date", "status",
				"budget", "expected_cost",
				"actual_cost", "expected_revenue",
				"campaign_type", "objective",
				"content", "tracker_key","refer_url","tracker_text",
				"tracker_count",
	);
	


	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = array(	
				'assigned_user_name', 'assigned_user_id', 
	);

	// This is the list of fields that are in the lists.
	var $list_fields = array(
				'id', 'name', 'status',
				'campaign_type','assigned_user_id','assigned_user_name','end_date',




				'refer_url',
	);

	var $required_fields = array(
				'name'=>1,  
				'status'=>2, 'campaign_type'=>3
	);

	function Campaign() {
		global $sugar_config;
		parent::SugarBean();
		



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
		$query .= "users.user_name as assigned_user_name, ";
		$query .= "campaigns.*";

		if($custom_join){
			$query .= $custom_join['select'];
		}	    



		$query .= " FROM campaigns ";





		$query .= "LEFT JOIN users
					ON campaigns.assigned_user_id=users.id ";




		if($custom_join){
			$query .= $custom_join['join'];
		}
		
		$where_auto = " campaigns.deleted=0";

		if($where != "")
			$query .= "where $where AND ".$where_auto;
		else
			$query .= "where ".$where_auto;

		if($order_by != "")
			$query .= " ORDER BY $order_by";
		else
			$query .= " ORDER BY campaigns.name";

		return $query;
	}


        function create_export_query($order_by, $where)
        {

                                $query = "SELECT
                                campaigns.*,
                                users.user_name as assigned_user_name ";



	                            $query .= "FROM campaigns ";




		$query .= 				"LEFT JOIN users
                                ON campaigns.assigned_user_id=users.id";




		$where_auto = " campaigns.deleted=0";

        if($where != "")
                $query .= " where $where AND ".$where_auto;
        else
                $query .= " where ".$where_auto;

        if($order_by != "")
                $query .= " ORDER BY $order_by";
        else
                $query .= " ORDER BY campaigns.name";
        return $query;
    }



	function save_relationship_changes($is_update)
    {
		if($this->lead_id != "")
    	{
    		$this->set_campaign_lead_relationship($this->id, $this->lead_id);
    	}
    	if($this->prospect_list_id != "")
    	{
    		$this->set_campaign_prospect_list_relationship($this->id, $this->prospect_list_id);
    	}
    }




	function set_campaign_lead_relationship($campaign_id, $lead_id)
	{
		$query = "INSERT INTO $this->rel_lead_table set id='". create_guid() . "', campaign_id='$campaign_id', lead_id='$lead_id'";
		$this->db->query($query, true, "Error setting campaign to lead relationship: ");
	}
	
	function set_campaign_prospect_list_relationship($campaign_id, $prospect_list_id)
	{
		$this->set_relationship('prospect_list_campaigns', array('campaign_id'=>$campaign_id , 'prospect_list_id'=>$prospect_list_id, ));
	}
	
	function clear_campaign_lead_relationship($campaign_id, $lead_id='')
	{
		if(!empty($lead_id))
		{
			$lead_clause = " and lead_id = '$lead_id' ";
		}
		else
		{
			$lead_clause = '';
		}
		
		$query = "DELETE FROM $this->rel_lead_table WHERE campaign_id='$campaign_id' AND deleted = '0' " . $lead_clause;
		$this->db->query($query, true, "Error clearing campaign to lead relationship: ");
	}
	
	function clear_campaign_prospect_list_relationship($campaign_id, $prospect_list_id='')
	{
		if(!empty($prospect_list_id))
			$prospect_clause = " and prospect_list_id = '$prospect_list_id' ";
		else
			$prospect_clause = '';
			
		$query = "DELETE FROM $this->rel_prospect_list_table WHERE campaign_id='$campaign_id' AND deleted = '0' " . $prospect_clause;
	 	$this->db->query($query, true, "Error clearing campaign to prospect_list relationship: ");
	}
	
		

	function mark_relationships_deleted($id)
	{
		$this->clear_campaign_lead_relationship($id);
		$this->clear_campaign_prospect_list_relationship($id);
	}

	function fill_in_additional_list_fields()
	{
	}

	function fill_in_additional_detail_fields()
	{
		$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);



		$this->created_by_name = get_assigned_user_name($this->created_by);
		$this->modified_by_name = get_assigned_user_name($this->modified_user_id);
	}

	
	function update_currency_id($fromid, $toid){
	}


	function get_list_view_data(){

		$temp_array = $this->get_list_view_array();
		
		return $temp_array;
	}
	/**
		builds a generic search based on the query string using or
		do not include any $this-> because this is called on without having the class instantiated
	*/
	function build_generic_where_clause ($the_query_string) 
	{
		$where_clauses = Array();
		$the_query_string = addslashes($the_query_string);
		array_push($where_clauses, "campaigns.name like '$the_query_string%'");

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
			if(isset($this->amount) && !empty($this->amount)){

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

	function set_notification_body($xtpl, $camp)
	{
		$xtpl->assign("CAMPAIGN_NAME", $camp->name);
		$xtpl->assign("CAMPAIGN_AMOUNT", $camp->amount);
		$xtpl->assign("CAMPAIGN_CLOSEDATE", $camp->date_closed);
		$xtpl->assign("CAMPAIGN_STAGE", $camp->sales_stage);
		$xtpl->assign("CAMPAIGN_DESCRIPTION", $camp->description);

		return $xtpl;
	}

	function get_prospect_lists()
	{
		// First, get the list of IDs.

		$query = "SELECT prospect_list_id as id FROM prospect_list_campaigns WHERE campaign_id='$this->id' AND deleted=0";
		
		return $this->build_related_list($query, new ProspectList());
	}

	function get_email_campaigns()
	{
		require_once('modules/EmailMarketing/EmailMarketing.php');
		
		$query = "SELECT id FROM email_marketing WHERE campaign_id='$this->id' AND deleted=0";
		return $this->build_related_list($query, new EmailMarketing());
	}
	
}









?>
