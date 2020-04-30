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
 * $Id: ContactBugRelationship.php,v 1.3 2005/02/09 07:08:54 andrew Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');

// Contact is used to store customer information.
class ContactBugRelationship extends SugarBean {
	// Stored fields
	var $id;
	var $contact_id;
	var $contact_role;
	var $bug_id;

	// Related fields
	var $contact_name;
	var $bug_name;

	var $table_name = "contacts_bugs";
	var $object_name = "ContactBugRelationship";
	var $column_fields = Array("id"
		,"contact_id"
		,"bug_id"
		,"contact_role"
		);

	var $new_schema = true;

	var $additional_column_fields = Array();

	function ContactBugRelationship() {
		$this->log = LoggerManager::getLogger('ContactBugRelationship');
		$this->db = new PearDatabase();



	}

	function fill_in_additional_detail_fields()
	{
		if(isset($this->contact_id) && $this->contact_id != "")
		{
			$query = "SELECT first_name, last_name from contacts where id='$this->contact_id' AND deleted=0";
			$result = $this->db->query($query,true," Error filling in additional detail fields: ");
			// Get the id and the name.
			$row = $this->db->fetchByAssoc($result);

			if($row != null)
			{
				$this->contact_name = return_name($row, 'first_name', 'last_name');
			}
		}

		if(isset($this->bug_id) && $this->bug_id != "")
		{
			$query = "SELECT name from bugs where id='$this->bug_id' AND deleted=0";
			$result =$this->db->query($query,true," Error filling in additional detail fields: ");
			// Get the id and the name.
			 $row = $this->db->fetchByAssoc($result);

			if($row != null)
			{
				$this->bug_name = $row['name'];
			}
		}

	}

	function create_list_query(&$order_by, &$where)
	{
		$query = "SELECT id, first_name, last_name, phone_work, title, email1 FROM contacts ";
		$where_auto = "deleted=0";

		if($where != "")
			$query .= "where $where AND ".$where_auto;
		else
			$query .= "where ".$where_auto;

		$query .= " ORDER BY last_name, first_name";

		return $query;
	}
}



?>
