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
 * $Id: iFrame.php,v 1.15.2.1 2005/05/06 17:33:23 julian Exp $
 ********************************************************************************/
include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');

// Contact is used to store customer information.
class iFrame extends SugarBean
{
	// Stored fields
	var $id;
	var $url;
	var $name;
	var $deleted;
	var $status = 1;
	var $placement='' ;
	var $date_entered;
	var $created_by;
	var $type;
	var $date_modified;
	var $table_name = "iframes";
	var $object_name = "iFrame";
	var $module_dir = 'iFrames';
	var $new_schema = true;

	var $column_fields = Array("id"
		,"url"
		,"name"
		,"placement"
		,'type'
		,"deleted"
		,"status"
        ,"date_entered"
        ,"date_modified"
        ,"created_by"
		);
	var $list_fields= array() ;


	var $required_fields = array('name'=>1, 'visible'=>2, 'url'=>3 , 'placement'=>4);


	function iFrame()
	{
		parent::SugarBean();
		$this->list_fields = $this->column_fields;



	}

	function get_xtemplate_data(){
		$return_array = array();
		global $current_user;
		foreach($this->column_fields as $field)
		{
			$return_array[strtoupper($field)] = $this->$field;
		}
				if(is_admin($current_user)){
					$select = translate('DROPDOWN_PLACEMENT', 'iFrames');
					$return_array['PLACEMENT_SELECT'] = get_select_options_with_id($select, $return_array['PLACEMENT'] );
				}else{
					$select = translate('DROPDOWN_PLACEMENT', 'iFrames');
					$shortcut = array('shortcut'=> $select['shortcut']);
					$return_array['PLACEMENT_SELECT'] = get_select_options_with_id($shortcut, '');
				}

				if(is_admin($current_user)){
					$select = translate('DROPDOWN_TYPE', 'iFrames');
					$return_array['TYPE_SELECT'] = get_select_options_with_id($select, $return_array['TYPE'] );
				}else{
					$select = translate('DROPDOWN_TYPE', 'iFrames');
					$personal = array('personal'=> $select['personal']);
					$return_array['TYPE_SELECT'] = get_select_options_with_id($personal, '');
				}
				if(!empty($select[$return_array['PLACEMENT']])){
					$return_array['PLACEMENT'] = $select[$return_array['PLACEMENT']];
				}

		return $return_array;
	}

		function get_list_view_data()
	{
		$ret_array = parent::get_list_view_array();
		if(!empty($ret_array['STATUS']) && $ret_array['STATUS'] > 0){
			 $ret_array['STATUS'] = '<input type="checkbox" class="checkbox" style="checkbox" checked disabled>';
		}else{
			$ret_array['STATUS'] = '<input type="checkbox" class="checkbox" style="checkbox" disabled>'	;
		}
		if(strlen($ret_array['URL']) > 63){
			$ret_array['URL'] = substr($ret_array['URL'], 0, 50) . '...' . substr($ret_array['URL'],-10);
		}
		$ret_array['CREATED_BY'] = get_assigned_user_name($this->created_by);
		$ret_array['PLACEMENT'] = translate('DROPDOWN_PLACEMENT', 'iFrames', $ret_array['PLACEMENT']);
				$ret_array['TYPE'] = translate('DROPDOWN_TYPE', 'iFrames', $ret_array['TYPE']);
		return $ret_array;

	}



	function lookup_frames($placement){
			global $current_user;
			if(isset($current_user)){
				$id = $current_user->id;
			}else{
				$id = -1;
			}
			$query = 'SELECT placement,name,id,url from '  .$this->table_name . " WHERE deleted=0 AND status=1 AND (placement='$placement' OR placement='all') AND (type='global' OR (type='personal' AND created_by='$id')) ORDER BY iframes.name";
			$res = $this->db->query($query);
			$frames = array();
			while($row = $this->db->fetchByAssoc($res)){
				$frames[$row['name']] = array($row['id'], $row['url'], $row['placement'],"iFrames",$row['name']);
			}
			return $frames;

	}

		function lookup_frame_by_record_id($record_id){
			global $current_user;
			if(isset($current_user)){
				$id = $current_user->id;
			}else{
				$id = -1;
			}
			$query = 'SELECT placement,name,id,url from '  .$this->table_name . " WHERE id = '$record_id' and  deleted=0 AND status=1 AND (placement='tab' OR placement='all') AND (type='global' OR (type='personal' AND created_by='$id'))";
			$res = $this->db->query($query);
			$frames = array();
			while($row = $this->db->fetchByAssoc($res)){
				$frames[$row['name']] = array($row['id'], $row['url'], $row['placement'],"iFrames",$row['name']);
			}
			return $frames;

	}

}


?>
