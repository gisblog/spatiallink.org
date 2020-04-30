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
$dictionary['CustomFields'] = array('table' => 'custom_fields'
                               ,'fields' => array (
  'id' => 
  array (
    'name' => 'id',
    'vname' => 'LBL_NAME',
    'type' => 'char',
    'len' => '36',
  ),
  'name' => 
  array (
    'name' => 'bena_id',
    'vname' => 'LBL_LIST_NAME',
    'type' => 'varchar',
    'len' => '36',
    'required' => true
  ),
  'set_num' => 
  array (
    'name' => 'set_num',
    'vname' => 'LBL_LIST_SYMBOL',
    'type' => 'int',
    'required' => true
  ),
  'field0' => 
  array (
    'name' => 'field0',
    'vname' => 'LBL_LIST_SYMBOL',
    'type' => 'varchar',
    'len' => '255'
  ),
  'field1' => 
  array (
    'name' => 'field1',
    'vname' => 'LBL_LIST_SYMBOL',
    'type' => 'varchar',
    'len' => '255'
  ),
  'field2' => 
  array (
    'name' => 'field2',
    'vname' => 'LBL_LIST_SYMBOL',
    'type' => 'varchar',
    'len' => '255'
  ),
  'field3' => 
  array (
    'name' => 'field3',
    'vname' => 'LBL_LIST_SYMBOL',
    'type' => 'varchar',
    'len' => '255'
  ),
  'field4' => 
  array (
    'name' => 'field4',
    'vname' => 'LBL_LIST_SYMBOL',
    'type' => 'varchar',
    'len' => '255'
  ),
  'field5' => 
  array (
    'name' => 'field5',
    'vname' => 'LBL_LIST_SYMBOL',
    'type' => 'varchar',
    'len' => '255'
  ),
  'field6' => 
  array (
    'name' => 'field6',
    'vname' => 'LBL_LIST_SYMBOL',
    'type' => 'varchar',
    'len' => '255'
  ),
  'field7' => 
  array (
    'name' => 'field7',
    'vname' => 'LBL_LIST_SYMBOL',
    'type' => 'varchar',
    'len' => '255'
  ),
  'field8' => 
  array (
    'name' => 'field8',
    'vname' => 'LBL_LIST_SYMBOL',
    'type' => 'varchar',
    'len' => '255'
  ),
  'field9' => 
  array (
    'name' => 'field9',
    'vname' => 'LBL_LIST_SYMBOL',
    'type' => 'varchar',
    'len' => '255'
  ),
  'deleted' => 
  array (
    'name' => 'deleted',
    'vname' => 'LBL_DATE_MODIFIED',
    'type' => 'bool',
    'required' => true
  ),
  'created_by' => 
  array (
    'name' => 'created_by',
    'vname' => 'LBL_CREATED_BY',
    'type' => 'char',
    'len'  => '36',
    'required' => true
  ),
  'deleted' => 
  array (
    'name' => 'deleted',
    'vname' => 'LBL_CREATED_BY',
    'type' => 'bool',
    'required' => true
  ),
)
                                                      , 'indices' => array (
       array('name' =>'custom_fieldspk', 'type' =>'primary', 'fields'=>array('id'))
                                                      )
                            );
		



?>
