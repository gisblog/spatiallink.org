<?php
/**
 * Data access layer for the fields_meta_data table
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

// $Id: FieldsMetaData.php,v 1.12.2.1 2005/05/17 22:29:54 majed Exp $


require_once('data/SugarBean.php');
require_once('include/database/PearDatabase.php');
require_once('include/utils.php');
include_once('config.php');
require_once('include/logging.php');
require_once('modules/Contacts/Contact.php');
require_once('modules/Users/User.php');

class FieldsMetaData extends SugarBean {
	// database table columns
	var $id;
	var $name;
	var $label;
  	var $custom_module;
  	var $data_type;
  	var $max_size;
  	var $required_option;
  	var $default_value;
  	var $deleted;
  	var $ext1;
  	var $ext2;
  	var $ext3;

	var $required_fields =  array("name"=>1, "date_start"=>2, "time_start"=>3,);

	var $table_name = 'fields_meta_data';
	var $object_name = 'FieldsMetaData';
	var $module_dir = 'EditCustomFields';
	var $column_fields = array(
		'id',
		'name',
		'label',
		'custom_module',
		'data_type',
		'max_size',
		'required_option',
		'default_value',
		'deleted',
		'ext1',
		'ext2',
		'ext3',
	);

	var $list_fields = array(
		'id',
		'name',
		'label',
		'data_type',
		'max_size',
		'required_option',
		'default_value',
	);

	var $field_name_map;
	var $new_schema = true;

	//////////////////////////////////////////////////////////////////
	// METHODS
	//////////////////////////////////////////////////////////////////

	function FieldsMetaData()
	{
		parent::SugarBean();
	}
	
	function mark_deleted($id)
	{
		$query = "DELETE FROM $this->table_name WHERE  id='$id'";
		$this->db->query($query, true,"Error deleting record: ");
		$this->mark_relationships_deleted($id);

	}


	function get_summary_text()
	{
		return $this->name;
	}
}
?>
