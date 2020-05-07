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
 * $Id: Release.php,v 1.16 2005/04/18 22:19:42 majed Exp $
 * Description:
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');
require_once('include/utils.php');

class Release extends SugarBean {
	// Stored fields
	var $id;
	var $deleted;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;
	var $name;
	var $status;

	var $table_name = "releases";

	var $object_name = "Release";
	var $module_dir = 'Releases';
	var $new_schema = true;

	var $column_fields = Array("id"
		,"name"
		,"list_order"
		,"date_entered"
		,"date_modified"
		,"status"
		,"modified_user_id"
		, "created_by"
		);

	
	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array();

	// This is the list of fields that are in the lists.
	var $list_fields = Array('id', 'name', 'list_order', 'status');
	// This is the list of fields that are required
	var $required_fields =  array("name"=>1,'list_order'=>1,'status'=>1);

	function Release() {
		parent::SugarBean();



	}

	function get_summary_text()
	{
		return "$this->name";
	}

	function get_releases($add_blank=false,$status='Active')
	{
		$query = "SELECT id, name FROM $this->table_name where deleted=0 ";
		if ($status=='Active') {
			$query .= " and status='Active' ";
		}
		elseif ($status=='Hidden') {
			$query .= " and status='Hidden' ";
		}
		elseif ($status=='All') {
		}
		$query .= " order by list_order asc";
		$result =& $this->db->query($query, false);
		$this->log->debug("get_releases: result is ".$result);

		$list = array();
		if ($add_blank) {
			$list['']='';
		}
		if($this->db->getRowCount($result) > 0){
			// We have some data.
			while ($row = $this->db->fetchByAssoc($result)) {
				$list[$row['id']] = $row['name'];
				$this->log->debug("row id is:".$row['id']);
				$this->log->debug("row name is:".$row['name']);
			}
		}
		return $list;
	}

	function create_list_query(&$order_by, &$where)
	{
		$custom_join = $this->custom_fields->getJOIN();
                $query = "SELECT ";
               
                $query .= " id, status, name, list_order ";
                if($custom_join){
   				$query .= $custom_join['select'];
 			}
                $query .= " FROM ".$this->table_name." ";
                if($custom_join){
  				$query .= $custom_join['join'];
			}
		$where_auto = "deleted=0";

		if($where != "")
			$query .= "where ($where) AND ".$where_auto;
		else
			$query .= "where ".$where_auto;

		if(!empty($order_by))
			$query .= " ORDER BY $order_by";

		return $query;
	}

	function fill_in_additional_list_fields()
	{
		$this->fill_in_additional_detail_fields();
	}

	function fill_in_additional_detail_fields() {

	}

	function get_list_view_data(){
		$temp_array = $this->get_list_view_array();
        $temp_array["ENCODED_NAME"]=$this->name;
//	$temp_array["ENCODED_NAME"]=htmlspecialchars($this->name, ENT_QUOTES);
    	return $temp_array;

	}
	/**
		builds a generic search based on the query string using or
		do not include any $this-> because this is called on without having the class instantiated
	*/
	function build_generic_where_clause ($the_query_string) {
	$where_clauses = Array();
	$the_query_string = addslashes($the_query_string);
	array_push($where_clauses, "name like '$the_query_string%'");

	$the_where = "";
	foreach($where_clauses as $clause)
	{
		if($the_where != "") $the_where .= " or ";
		$the_where .= $clause;
	}


	return $the_where;
}


}

?>
