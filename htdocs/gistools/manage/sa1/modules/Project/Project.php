<?php
/**
 * Data access layer for the project table
 *
 * PHP version 4
 *
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
 */

// $Id: Project.php,v 1.36.2.1 2005/05/12 23:39:49 clint Exp $


require_once('data/SugarBean.php');
require_once('include/database/PearDatabase.php');
require_once('include/utils.php');
include_once('config.php');
require_once('include/logging.php');
require_once('modules/Calls/Call.php');
require_once('modules/Notes/Note.php');
require_once('modules/Emails/Email.php');

/**
 *
 */
class Project extends SugarBean {
	// database table columns
	var $id;
	var $date_entered;
	var $date_modified;
	var $assigned_user_id;
	var $modified_user_id;
	var $created_by;



	var $name;
	var $description;
	var $deleted;

	// related information
	var $assigned_user_name;
	var $modified_by_name;
	var $created_by_name;



	var $relation_id;
	var $relation_name;
	var $relation_type;

	var $account_id;

	// calculated information
	var $total_estimated_effort;
	var $total_actual_effort;

	var $required_fields = array('name'=>1, );

	var $object_name = 'Project';
	var $module_dir = 'Project';
	var $new_schema = true;
	var $table_name = 'project';

	var $column_fields = array(
		'id',
		'date_entered',
		'date_modified',
		'assigned_user_id',
		'modified_user_id',
		'created_by',



		'name',
		'description',
		'deleted',
	);

	var $list_fields = array(
		'id',
		'assigned_user_id',
		'assigned_user_name',




		'name',
		'relation_id',
		'relation_name',
		'relation_type',
		'total_estimated_effort',
		'total_actual_effort',
	);

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('account_id',);


	//////////////////////////////////////////////////////////////////
	// METHODS
	//////////////////////////////////////////////////////////////////

	/**
	 *
	 */
	function Project()
	{
		parent::SugarBean();












	}

	/**
	 * overriding the base class function to do a join with users table
	 */
	function create_list_query($order_by, $where)
	{
		$custom_join = $this->custom_fields->getJOIN();

		$query = "SELECT users.user_name assigned_user_name, project.*";

		if($custom_join){ $query .=  $custom_join['select']; }




		$query .= " FROM project ";




          $query .= "LEFT JOIN users ON project.assigned_user_id=users.id ";
          //$query .= "LEFT JOIN project_relation ON project.id=project_relation.project_id ";



		if($custom_join){ $query .=  $custom_join['join']; }


		$where_auto = " project.deleted=0 ";

            if($where != '')
                    $query .= "WHERE ($where) AND ".$where_auto;
            else
                    $query .= "WHERE ".$where_auto;

            if(!empty($order_by))
                    $query .= " ORDER BY $order_by";
//die($query);
		return $query;
	}

	/**
	 *
	 */
	function fill_in_additional_detail_fields()
	{
		$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);



		$this->total_estimated_effort = $this->_get_total_estimated_effort($this->id);
		$this->total_actual_effort = $this->_get_total_actual_effort($this->id);
	}

	/**
	 *
	 */
	function fill_in_additional_list_fields()
	{
		$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);



		$this->total_estimated_effort = $this->_get_total_estimated_effort($this->id);
		$this->total_actual_effort = $this->_get_total_actual_effort($this->id);
		$this->_fill_in_additional_parent_fields();
	}

	/**
	 *
	 */
	function _get_total_estimated_effort($project_id)
	{
		$return_value = '';

		$query = 'SELECT SUM(estimated_effort) total_estimated_effort'
			. ' FROM project_task'
			. " WHERE parent_id='{$project_id}' AND deleted=0;";
		$result = $this->db->query($query,true," Error filling in additional detail fields: ");
		$row = $this->db->fetchByAssoc($result);
		if($row != null)
		{
			$return_value = $row['total_estimated_effort'];
		}

		return $return_value;
	}

	/**
	 *
	 */
	function _get_total_actual_effort($project_id)
	{
		$return_value = '';

		$query = 'SELECT SUM(actual_effort) total_actual_effort'
			. ' FROM project_task'
			. " WHERE parent_id='{$project_id}' AND deleted=0;";
		$result = $this->db->query($query,true," Error filling in additional detail fields: ");
		$row = $this->db->fetchByAssoc($result);
		if($row != null)
		{
			$return_value = $row['total_actual_effort'];
		}

		return $return_value;
	}

	/**
	 *
	 */
	function _fill_in_additional_parent_fields()
	{
		global $app_strings;

		switch($this->relation_type)
		{

			case 'Accounts':
				require_once("modules/Accounts/Account.php");
				$parent = new Account();
				break;

			case 'Contacts':
				require_once("modules/Contacts/Contact.php");
				$parent = new Contact();
				break;

			case 'Opportunities':
				require_once("modules/Opportunities/Opportunity.php");
				$parent = new Opportunity();
				break;






		}

		if(!empty($parent))
		{
			$parent->retrieve($this->parent_id);
			$this->parent_name = $parent->name;
		}
	}

	/**
	 *
	 */
	function get_project_tasks()
	{
		global $beanFiles;
		require_once($beanFiles['ProjectTask']);

		// the query that will retrieve a list of id's
		$query = "SELECT id FROM project_task WHERE parent_id='{$this->id}' AND deleted=0";
		return $this->build_related_list($query, new ProjectTask());
	}

	/**
	 *
	 */
	function get_contact_relations()
	{
		global $beanFiles;
		require_once($beanFiles['Contact']);

		// the query that will retrieve a list of id's
		$query = 'SELECT relation_id id'
			. ' FROM project_relation'
			. " WHERE project_id='{$this->id}' AND relation_type='Contacts' AND deleted=0;";
		return $this->build_related_list($query, new Contact());
	}

	/**
	 *
	 */
	function get_account_relations()
	{
		global $beanFiles;
		require_once($beanFiles['Account']);

		// the query that will retrieve a list of id's
		$query = 'SELECT relation_id id'
			. ' FROM project_relation'
			. " WHERE project_id='{$this->id}' AND relation_type='Accounts' AND deleted=0;";
		return $this->build_related_list($query, new Account());
	}

	/**
	 *
	 */
	function get_quote_relations()
	{
		global $beanFiles;
		require_once($beanFiles['Quote']);

		// the query that will retrieve a list of id's
		$query = 'SELECT relation_id id'
			. ' FROM project_relation'
			. " WHERE project_id='{$this->id}' AND relation_type='Quotes' AND deleted=0;";
		return $this->build_related_list($query, new Quote());
	}

	/**
	 *
	 */
	function get_opportunity_relations()
	{
		global $beanFiles;
		require_once($beanFiles['Opportunity']);

		// the query that will retrieve a list of id's
		$query = 'SELECT relation_id id'
			. ' FROM project_relation'
			. " WHERE project_id='{$this->id}' AND relation_type='Opportunities' AND deleted=0;";
		return $this->build_related_list($query, new Opportunity());
	}

	/**
	 *
	 */
	function save_relationship_changes($is_update)
	{
		$row_values = array('project_id' => $this->id,
			'relation_id' => $this->relation_id,
			'relation_type' => $this->relation_type);
		$this->set_relationship('project_relation', $row_values);
		if (!empty($this->account_id)) {
                $row_values = array('project_id' => $this->id,
				'relation_id' => $this->account_id,
				'relation_type' => 'Accounts');
			$this->set_relationship('project_relation', $row_values);
		}
	}

	/**
	 *
	 */
	function get_summary_text()
	{
		return $this->name;
	}

	/**
	 *
	 */
	function build_generic_where_clause ($the_query_string)
	{
		$where_clauses = array();
		$the_query_string = addslashes($the_query_string);
		array_push($where_clauses, "project.name LIKE '%$the_query_string%'");

		$the_where = '';
		foreach($where_clauses as $clause)
		{
			if($the_where != '') $the_where .= " OR ";
			$the_where .= $clause;
		}

		return $the_where;
	}

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

}
?>
