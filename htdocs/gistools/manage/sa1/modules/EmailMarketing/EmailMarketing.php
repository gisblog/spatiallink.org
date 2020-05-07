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
 * $Id: EmailMarketing.php,v 1.5.2.1 2005/05/06 17:52:16 joey Exp $
 * Description:
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');
require_once('include/utils.php');


class EmailMarketing extends SugarBean {

	var $field_name_map;
	
	var $id;
	var $deleted;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $created_by;
	var $name;
	var $from_addr;
	var $from_name;
	var $date_start;
	var $time_start;
	var $template_id;
	var $campaign_id;
	
	var $table_name = 'email_marketing';
	var $object_name = 'EmailMarketing';
	var $module_dir = 'EmailMarketing';
	
	var $new_schema = true;
	
	var $disable_row_level_security = true;
	
	var $column_fields = array (
		'id', 'date_entered', 'date_modified',
		'modified_user_id', 'created_by', 'name',
		'from_addr', 'from_name', 'date_start','time_start', 'template_id', 'campaign_id'	
	);
	
	var $list_fields = array (
		'id','name','date_start','time_start', 'template_id'
	);

	var $required_fields = array (
		'name'=>1, 'from_name'=>1,
		'from_addr'=>1, 'date_start'=>1, 'time_start'=>1,
		'template_id'=>1,
	);
	
	function EmailMarketing()
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
		$query .= "email_marketing.* FROM email_marketing ";
		
		$where_auto = " email_marketing.deleted=0";
		
		if($where != "")
			$query .= " WHERE $where AND " . $where_auto;
		else
			$query .= " WHERE " . $where_auto;
		
		if($order_by != "")
			$query .= " ORDER BY $order_by ";
		else
			$query .= " ORDER BY email_marketing.name ";

		return $query;		
	}

	function create_export_query($order_by, $where)
	{
		return $this->create_list_query($order_by, $where);
	}	
	
	function get_list_view_data(){

		$temp_array = $this->get_list_view_array();
		
		$id = $temp_array['ID'];
		$template_id = $temp_array['TEMPLATE_ID'];
		
		$query = "SELECT name FROM email_templates WHERE id = '$template_id'";
		$res = $this->db->query($query);
		$row = $this->db->fetchByAssoc($res);
		$temp_array['TEMPLATE_NAME'] = $row['name'];
		
		$query = "SELECT count(*) AS num FROM emailman WHERE marketing_id = '$id'";
		$res = $this->db->query($query);
		$row = $this->db->fetchByAssoc($res);
		$toprocess = $row['num'];

		$query = "SELECT count(*) AS num FROM emailman_sent WHERE marketing_id = '$id'";
		$res = $this->db->query($query);
		$row = $this->db->fetchByAssoc($res);
		$processed = $row['num'];
		
		$status = '';
		if($toprocess > 0)
		{
			$status = "In Queue";
			
			if($processed > 0)
			{
				$status = "In Progress";
			}
		}
		if($processed > 0)
		{
			$status = "Sent";
			
			if($toprocess > 0)
			{
				$status = "In Progress";
			}
		}

		
		$temp_array['STATUS'] = $status;	
		return $temp_array;
	}
		
}

?>
