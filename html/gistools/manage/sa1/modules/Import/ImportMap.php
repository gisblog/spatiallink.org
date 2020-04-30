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
class ImportMap extends SugarBean 
{
	// Stored fields
	var $id;
	var $name;
	var $module;
	var $source;
	var $content;
	var $has_header;
	var $deleted;
	var $date_entered;
	var $date_modified;
	var $assigned_user_id;
	var $is_published;

	var $table_name = "import_maps";
	var $object_name = "ImportMap";
	var $module_dir = 'Import';
	var $new_schema = true;

	var $column_fields = Array("id"
		,"name"
		,"module"
		,"source"
		,"content"
		,"has_header"
                ,"deleted"
                ,"date_entered"
                ,"date_modified"
                ,"assigned_user_id"
		,"is_published"
		);


	function ImportMap() 
	{
		parent::SugarBean();



	}
	
	
        function save_map( $owner_id, $name, $module, $source, $has_header,$content )
        {
		$query_arr = array(
			'assigned_user_id'=>$owner_id,'name'=>$name);

		$this->retrieve_by_string_fields($query_arr, false);

                $result = 1;
                $this->assigned_user_id = $owner_id;
                $this->name = $name;
                $this->source = $source;
                $this->module = $module;
                $this->content = $content;
                $this->has_header = $has_header;
                $this->deleted = 0;
                $this->save();
                return $result;
        }

        function mark_published($user_id,$flag)
        {
		$other_map = new ImportMap();

		if ($flag == 'yes')
		{
			// if you are trying to publish your map
			// but there's another published map
			// by the same name
			
			$query_arr = array('name'=>$this->name,
					'is_published'=>'yes');
		} 
		else 
		{
			// if you are trying to unpublish a map
			// but you own an unpublished map by the same name
			$query_arr = array('name'=>$this->name,
					'assigned_user_id'=>$user_id,
					'is_published'=>'no');
		}
		$other_map->retrieve_by_string_fields($query_arr, false);

		if ( isset($other_map->id) )
		{
			//.. don't do it!
			return -1;	
		}

                $query = "UPDATE $this->table_name set is_published='$flag', assigned_user_id='$user_id' where id='".$this->id."'";
                $this->db->query($query,true,"Error marking import map published: ");
		return 1;
        }


	function retrieve_all_by_string_fields($fields_array) 
	{ 
		$where_clause = $this->get_where($fields_array);
		$query = "SELECT * FROM $this->table_name $where_clause";
		$this->log->debug("Retrieve $this->object_name: ".$query);
		$result = & $this->db->query($query,true," Error: ");
		$obj_arr = array();

		while ($row = $this->db->fetchByAssoc($result,-1,FALSE) )
		{	
			$focus = new ImportMap();

			foreach($this->column_fields as $field) 
			{ 
				if(isset($row[$field])) 
				{ 
					$focus->$field = $row[$field];
				} 
			} 
			$focus->fill_in_additional_detail_fields(); 
			array_push($obj_arr,$focus);
		}
		return $obj_arr;
	}

}


?>
