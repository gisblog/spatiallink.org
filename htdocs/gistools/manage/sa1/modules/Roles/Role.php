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
 * $Id: Role.php,v 1.8 2005/04/28 20:27:48 joey Exp $
 * Description:
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');
require_once('include/utils.php');


class Role extends SugarBean {

	var $field_name_map;
	
	var $id;
	var $deleted;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $created_by;
	var $name;
	var $description;
	var $modules;
	
	var $table_name = 'roles';
	var $rel_module_table = 'roles_modules';
	var $object_name = 'Role';
	var $module_dir = 'Roles';
	var $new_schema = true;
	
	var $column_fields = array (
		'id', 'date_entered', 'date_modified',
		'modified_user_id', 'created_by', 'name',
		'description',
	);
	
	var $list_fields = array (
		'id','name','description',
	);

	var $required_fields = array (
		'name'=>1,
	);
	
	function Role()
	{
		parent::SugarBean();
	}
	
	function get_summary_text()
	{
		return $this->name;
	}
	
	function create_list_query($order_by, $where)
	{
		$query = "SELECT ";
		$query .= "roles.* FROM roles ";
		
		$where_auto = " roles.deleted=0";
		
		if($where != "")
			$query .= " WHERE $where AND " . $where_auto;
		else
			$query .= " WHERE " . $where_auto;
		
		if($order_by != "")
			$query .= " ORDER BY $order_by ";
		else
			$query .= " ORDER BY roles.name ";

		return $query;		
	}

	function create_export_query($order_by, $where)
	{
		return $this->create_list_query($order_by, $where);
	}	
	
	function query_modules($allow = 1)
	{
		$query = "SELECT module_id FROM roles_modules WHERE ";
		$query .= "role_id = '$this->id' AND allow = '$allow' AND deleted=0";
		$result = $this->db->query($query);
		
		$return_array = array();
		
		while($row = $this->db->fetchByAssoc($result))
		{
			array_push($return_array, $row['module_id']);
		}
		
		return $return_array;
	}
	function set_module_relationship($role_id, &$mod_ids, $allow)
	{
		foreach($mod_ids as $mod_id)
		{
			if($mod_id != '')
				$this->set_relationship('roles_modules', array( 'module_id'=>$mod_id, 'role_id'=>$role_id, 'allow'=>$allow ));
		}
	}
	
	function clear_module_relationship($role_id)
	{
		$query = "DELETE FROM roles_modules WHERE role_id='$role_id'";
		$this->db->query($query);
	}

	function set_user_relationship($role_id, &$user_ids)
	{
		foreach($user_ids as $user_id)
		{
			if($user_id != '')
				$this->set_relationship('roles_users', array( 'user_id'=>$user_id, 'role_id'=>$role_id ));
		}
	}

	function clear_user_relationship($role_id, $user_id)
	{
		$query = "DELETE FROM roles_users WHERE role_id='$role_id' AND user_id='$user_id'";
		$this->db->query($query);
	}

	function query_user_allowed_modules($user_id)
	{
		$userArray = array();
		global $app_list_strings;
		
		require_once('modules/Roles/Role.php');
	
		$sql = "SELECT role_id FROM roles_users WHERE user_id='$user_id'";
		
		$result = $this->db->query($sql);
		
		while($row = $this->db->fetchByAssoc($result))
		{
			$role_id = $row["role_id"];
			$sql = "SELECT module_id FROM roles_modules WHERE role_id='$role_id' AND allow='1'";
			$res = $this->db->query($sql);
			
			while($col = $this->db->fetchByAssoc($res))
			{
				$key = $col['module_id'];
				if(!(array_key_exists($key, $userArray)))
				{
					$userArray[$key] = $app_list_strings['moduleList'][$key];
				}
			}
		}
	
		return $userArray;
	}
	
	function query_user_disallowed_modules($user_id, &$allowed)
	{
		global $moduleList;
		
		$returnArray = array();
		
		foreach($moduleList as $key=>$val)
		{
			if(array_key_exists($val, $allowed))
				continue;
			$returnArray[$val] = $val;
		}
		
		return $returnArray;

	}

	function get_users()
	{
		// First, get the list of IDs.

		require_once('modules/Users/User.php');
		
		$query = "SELECT user_id as id FROM roles_users WHERE role_id='$this->id' AND deleted=0";
		
		return $this->build_related_list($query, new User());
	}

	function check_user_role_count($user_id)
	{
		$query =  "SELECT count(*) AS num FROM roles_users WHERE ";
		$query .= "user_id='$user_id' AND deleted=0";
		$result = $this->db->query($query);
		
		$row = $this->db->fetchByAssoc($result);
		
		return $row['num'];
	}		
		
}

?>
