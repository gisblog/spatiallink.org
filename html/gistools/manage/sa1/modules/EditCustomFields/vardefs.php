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
// $Id: vardefs.php,v 1.4 2005/04/27 00:33:40 ron Exp $
$dictionary['FieldsMetaData'] = array (
	'table' => 'fields_meta_data',
	'fields' => array (
		'id'=>array('name' =>'id', 'type' =>'varchar', 'len'=>'255', 'default'=>'','reportable'=>false, 'required'=>true),
		'name'=>array('name' =>'name', 'vname'=>'COLUMN_TITLE_NAME', 'type' =>'varchar', 'len'=>'255', 'default'=>'' ,'required'=>true),
		'label'=>array('name' =>'label' ,'type' =>'varchar','vname'=>'COLUMN_TITLE_LABEL',  'len'=>'255', 'default'=>'','required'=>true),
		'custom_module'=>array('name' =>'custom_module',  'type' =>'varchar', 'len'=>'255', 'default'=>''),
		'data_type'=>array('name' =>'data_type', 'vname'=>'COLUMN_TITLE_DATA_TYPE',  'type' =>'varchar', 'len'=>'255', 'default'=>'', 'required'=>true),
		'max_size'=>array('name' =>'max_size','vname'=>'COLUMN_TITLE_MAX_SIZE', 'type' =>'int', 'len'=>'11', 'default'=>'', 'validation' => array('type' => 'range', 'min' => 1, 'max' => 255),),
		'required_option'=>array('name' =>'required_option', 'type' =>'varchar', 'len'=>'255', 'default'=>''),
		'default_value'=>array('name' =>'default_value', 'type' =>'varchar', 'len'=>'255', 'default'=>''),
		'deleted'=>array('name' =>'deleted', 'type' =>'bool', 'len'=>'4','reportable'=>false, 'default'=>'0'),
		'ext1'=>array('name' =>'ext1', 'type' =>'varchar', 'len'=>'255', 'default'=>'0'),
		'ext2'=>array('name' =>'ext2', 'type' =>'varchar', 'len'=>'255', 'default'=>'0'),
		'ext3'=>array('name' =>'ext3', 'type' =>'varchar', 'len'=>'255', 'default'=>'0'),
	),
	'indices' => array (
		array('name' =>'fields_meta_datapk', 'type' =>'primary', 'fields' => array('id')),
		array('name' =>'idx_meta_id_del', 'type' =>'index', 'fields'=>array('id','deleted'))
	),
);
?>
