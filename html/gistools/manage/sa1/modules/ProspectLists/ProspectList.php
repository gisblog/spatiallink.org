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
 * $Id: ProspectList.php,v 1.13 2005/04/30 04:24:09 majed Exp $
 * Description:
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');
require_once('include/utils.php');

class ProspectList extends SugarBean {
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
	var $description;
	
	// These are related
	var $assigned_user_name;
	var $prospect_id;
	var $contact_id;
	var $lead_id;

	// module name definitions and table relations
	var $table_name = "prospect_lists";
	var $module_dir = 'ProspectLists';
	var $rel_prospects_table = "prospect_lists_prospects";
	var $object_name = "ProspectList";

	var $column_fields = array(
				"id", "date_entered",
				"date_modified", "modified_user_id",
				"assigned_user_id", "created_by",



				"name", "description",
	);
	

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = array(	
				'assigned_user_name', 'assigned_user_id', 
	);

	// This is the list of fields that are in the lists.
	var $list_fields = array(
				'id', 'name', 'description',
				'assigned_user_id','assigned_user_name',



	);

	var $required_fields = array(
				'name'=>1,
	);

	function ProspectList() {
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
		$query .= "prospect_lists.*";

		if($custom_join){
			$query .= $custom_join['select'];
		}	    



		$query .= " FROM prospect_lists ";





		$query .= "LEFT JOIN users
					ON prospect_lists.assigned_user_id=users.id ";




		if($custom_join){
			$query .= $custom_join['join'];
		}
		
		$where_auto = " prospect_lists.deleted=0";

		if($where != "")
			$query .= "where $where AND ".$where_auto;
		else
			$query .= "where ".$where_auto;

		if($order_by != "")
			$query .= " ORDER BY $order_by";
		else
			$query .= " ORDER BY prospect_lists.name";

		return $query;
	}


        function create_export_query($order_by, $where)
        {

                                $query = "SELECT
                                prospect_lists.*,
                                users.user_name as assigned_user_name ";



	                            $query .= "FROM prospect_lists ";




		$query .= 				"LEFT JOIN users
                                ON prospect_lists.assigned_user_id=users.id";




		$where_auto = " prospect_lists.deleted=0";

        if($where != "")
                $query .= "where $where AND ".$where_auto;
        else
                $query .= "where ".$where_auto;

        if($order_by != "")
                $query .= " ORDER BY $order_by";
        else
                $query .= " ORDER BY prospect_lists.name";
        return $query;
    }



	function save_relationship_changes($is_update)
    {
		if($this->lead_id != "")
	   		$this->set_prospect_relationship($this->id, $this->lead_id, "lead");
    	if($this->contact_id != "")
    		$this->set_prospect_relationship($this->id, $this->contact_id, "contact");
    	if($this->prospect_id != "")
    		$this->set_prospect_relationship($this->id, $this->contact_id, "prospect");
    }

	function set_prospect_relationship($prospect_list_id, &$link_ids, $link_name)
	{
		$link_field = sprintf("%s_id", $link_name);
		
		foreach($link_ids as $link_id)
		{
			$this->set_relationship('prospect_lists_prospects', array( $link_field=>$link_id, 'prospect_list_id'=>$prospect_list_id ));
		}
	}

	function set_prospect_relationship_single($prospect_list_id, $link_id, $link_name)
	{
		$link_field = sprintf("%s_id", $link_name);
		
		$this->set_relationship('prospect_lists_prospects', array( $link_field=>$link_id, 'prospect_list_id'=>$prospect_list_id ));
	}


	function clear_prospect_relationship($prospect_list_id, $link_id, $link_name)
	{
		$link_field = sprintf("%s_id", $link_name);
		$where_clause = " AND $link_field = '$link_id' ";
		
		$query = sprintf("DELETE FROM prospect_lists_prospects WHERE prospect_list_id='%s' AND deleted = '0' %s", $prospect_list_id, $where_clause);
	
		$this->db->query($query, true, "Error clearing prospect/prospect_list relationship: ");
	}
	

	function mark_relationships_deleted($id)
	{
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


	function get_entry_count()
	{
		$query = "SELECT count(*) AS num FROM prospect_lists_prospects WHERE prospect_list_id='$this->id' AND deleted = '0'";
		$result = $this->db->query($query, true, "Grabbing prospect_list entry count");
		
		$row = $this->db->fetchByAssoc($result);

		if($row)
			return $row['num'];
		else
			return 0;
	}
		
		
	function get_list_view_data(){

		$temp_array = $this->get_list_view_array();

		$temp_array["ENTRY_COUNT"] = $this->get_entry_count();		
		
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
		array_push($where_clauses, "prospect_lists.name like '$the_query_string%'");

		$the_where = "";
		foreach($where_clauses as $clause)
		{
			if($the_where != "") $the_where .= " or ";
			$the_where .= $clause;
		}


		return $the_where;
	}

	function save($check_notify = FALSE) {

		return parent::save($check_notify);

	}

	function parse_additional_headers(&$list_form, $xTemplateSection) {

	}

	function list_view_parse_additional_sections(&$list_form, $xTemplateSection) {
		return $list_form;
	}

	function get_prospects()
	{
		// First, get the list of IDs.

		require_once('modules/Prospects/Prospect.php');
		
		$query = "SELECT prospect_id as id FROM prospect_lists_prospects WHERE prospect_list_id='$this->id' AND deleted=0";
		
		return $this->build_related_list($query, new Prospect());
	}

	function get_contacts()
	{
		// First, get the list of IDs.

		require_once('modules/Contacts/Contact.php');
		
		$query = "SELECT contact_id as id FROM prospect_lists_prospects WHERE prospect_list_id='$this->id' AND deleted=0";
		
		return $this->build_related_list($query, new Contact());
	}

	function get_leads()
	{
		// First, get the list of IDs.

		require_once('modules/Leads/Lead.php');
		
		$query = "SELECT lead_id as id FROM prospect_lists_prospects WHERE prospect_list_id='$this->id' AND deleted=0";
		
		return $this->build_related_list($query, new Lead());
	}



}





?>
