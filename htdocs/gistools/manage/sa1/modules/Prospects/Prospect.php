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
 * $Id: Prospect.php,v 1.10 2005/04/29 08:51:28 majed Exp $
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

class Prospect extends SugarBean {
    var $field_name_map;
	// Stored fields
	var $id;
	var $name = '';
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
	var $tracker_key;
	var $invalid_email;

	// These are for related fields
	var $assigned_user_name;




	var $module_dir = 'Prospects';
	var $table_name = "prospects";

	var $object_name = "Prospect";

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
		,"title"
		,"department"
		,"birthdate"
		,"do_not_call"
		,"phone_home"
		,"phone_mobile"
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
		,"tracker_key"
		,'invalid_email'
		);



	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('assigned_user_name');

	// This is the list of fields that are in the lists.
	var $list_fields = Array('id', 'first_name', 'last_name', 'account_name', 'account_id', 'title', 'email1','email2', 'phone_work', 'assigned_user_name', 'assigned_user_id','email_and_name1','email_and_name2'




,'invalid_email'
		);

	// This is the list of fields that are required
	var $required_fields =  array("last_name"=>1);

	function Prospect() {
		global $current_user;
		parent::SugarBean();










	}
	

	function get_summary_text()
	{
		return "$this->first_name $this->last_name";
	}

	function create_list_query(&$order_by, &$where)
	{
		$custom_join = $this->custom_fields->getJOIN();
		$query = "SELECT ";
		
		$query .= "
                users.user_name as assigned_user_name ";




		if($custom_join){
   				$query .= $custom_join['select'];
 		}
        $query .= " ,prospects.assigned_user_id,
				prospects.id,
                prospects.first_name,
                prospects.last_name,
                prospects.phone_work,
                prospects.title,
                prospects.email1,
                prospects.email2
                FROM prospects ";





		$query .=		"LEFT JOIN users
	                    ON prospects.assigned_user_id=users.id ";



		if($custom_join){
  				$query .= $custom_join['join'];
		}

		$where_auto = " prospects.deleted=0 ";

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
                                prospects.*,
                                users.user_name as assigned_user_name ";



						if($custom_join){
   							$query .= $custom_join['select'];
 						}
						 $query .= " FROM prospects ";




                         $query .= "LEFT JOIN users
	                                ON prospects.assigned_user_id=users.id ";



						if($custom_join){
  							$query .= $custom_join['join'];
						}

		$where_auto = " prospects.deleted=0 ";

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

    }

	function mark_relationships_deleted($id)
	{
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

		array_push($where_clauses, "prospects.last_name like '$the_query_string%'");
		array_push($where_clauses, "prospects.first_name like '$the_query_string%'");
		array_push($where_clauses, "prospects.assistant like '$the_query_string%'");
		array_push($where_clauses, "prospects.email1 like '$the_query_string%'");
		array_push($where_clauses, "prospects.email2 like '$the_query_string%'");

		if (is_numeric($the_query_string))
		{
			array_push($where_clauses, "prospects.phone_home like '%$the_query_string%'");
			array_push($where_clauses, "prospects.phone_mobile like '%$the_query_string%'");
			array_push($where_clauses, "prospects.phone_work like '%$the_query_string%'");
			array_push($where_clauses, "prospects.phone_other like '%$the_query_string%'");
			array_push($where_clauses, "prospects.phone_fax like '%$the_query_string%'");
			array_push($where_clauses, "prospects.assistant_phone like '%$the_query_string%'");
		}

		$the_where = "";
		foreach($where_clauses as $clause)
		{
			if($the_where != "") $the_where .= " or ";
			$the_where .= $clause;
		}


		return $the_where;
	}

	function set_notification_body($xtpl, $prospect)
	{
		$xtpl->assign("PROSPECT_NAME", trim($prospect->first_name . " " . $prospect->last_name));
		$xtpl->assign("PROSPECT_DESCRIPTION", $prospect->description);

		return $xtpl;
	}

	function get_prospect_id_by_email($email)
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
