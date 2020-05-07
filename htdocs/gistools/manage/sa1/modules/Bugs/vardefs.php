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
$dictionary['Bug'] = array('table' => 'bugs'
                               ,'fields' => array (
  'id' => 
  array (
    'name' => 'id',
    'vname' => 'LBL_NAME',
    'type' => 'id',
    'required'=>true,
    'reportable'=>false,
  ),
  'number' => 
  array (
    'name' => 'number',
    'vname' => 'LBL_NUMBER',
    'type' => 'int',
    'len' => 11,
    'required'=>true,
    'auto_increment'=>true,
  ),

    'date_entered' => 
  array (
    'name' => 'date_entered',
    'vname' => 'LBL_DATE_ENTERED',
    'type' => 'datetime',
    'required'=>true,
  ),
  'date_modified' => 
  array (
    'name' => 'date_modified',
    'vname' => 'LBL_DATE_MODIFIED',
    'type' => 'datetime',
    'required'=>true,
  ),
    'modified_user_id' => 
  array (
    'name' => 'modified_user_id',
    'rname' => 'user_name',
    'id_name' => 'modified_user_id',
    'vname' => 'LBL_MODIFIED',
    'type' => 'assigned_user_name',
    'table' => 'modified_user_id_users',
    'isnull' => 'false',
    'dbType' => 'char',
    'len' => 36,
    'required'=>true,
    'reportable'=>true,
    'default'=>'',
  ),
   'assigned_user_id' => 
  array (
    'name' => 'assigned_user_id',
    'rname' => 'user_name',
    'id_name' => 'assigned_user_id',
    'vname' => 'LBL_ASSIGNED_TO',
    'type' => 'assigned_user_name',
    'table' => 'users',
    'isnull' => 'false',
    'dbType' => 'char',
    'reportable'=>true,
    'len' => 36,
  ),

























 'deleted' => 
  array (
    'name' => 'deleted',
    'vname' => 'LBL_CREATED_BY',
    'type' => 'bool',
    'required' => true,
    'reportable'=>false,
  ),
 'name' => 
  array (
    'name' => 'name',
    'vname' => 'LBL_LIST_SUBJECT',
    'type' => 'varchar',
    'len' => 255,
  ),

    'status' => 
  array (
    'name' => 'status',
    'vname' => 'LBL_STATUS',
    'type' => 'enum',
    'options' => 'bug_status_dom',
    'len'=>25,
    
  )
  ,'priority' => 
  array (
    'name' => 'priority',
    'vname' => 'LBL_PRIORITY',
    'type' => 'enum',
    'options' => 'bug_priority_dom',
    'len'=>25,
  ),
    'description' => 
  array (
    'name' => 'description',
    'vname' => 'LBL_DESCRIPTION',
    'type' => 'text',
  ),
    'created_by' => 
  array (
    'name' => 'created_by',
    'rname' => 'user_name',
    'id_name' => 'created_by',
    'vname' => 'LBL_CREATED',
    'type' => 'assigned_user_name',
    'table' => 'created_by_users',
    'isnull' => 'false',
    'dbType' => 'char',
    'len' => 36,
  ),

  'resolution' => 
  array (
    'name' => 'resolution',
    'vname' => 'LBL_RESOLUTION',
    'type' => 'enum',
    'options' => 'bug_resolution_dom',
    'len'=>255,
  ),
  'release'=>
  	array(
  	'name'=>'release',
  	'type' => 'varchar',
  	 'vname' => 'LBL_FOUND_IN_RELEASE',
  	'len'=>255,
  	'reportable'=>false
  	),
   'release_name'=>
  array (
    'name' => 'release_name',
    'rname' => 'name',
    'id_name' => 'release',
    'vname' => 'LBL_FOUND_IN_RELEASE',
    'type' => 'relate',
    'table' => 'releases',
    'isnull' => 'false',
    'massupdate' => false,
    'module' => 'Releases',
    'dbType' => 'char',
    'len' => 36,
    'source'=>'non-db',
    'reportable'=>false,
  ),
  'type' => 
  array (
    'name' => 'type',
    'vname' => 'LBL_TYPE',
    'type' => 'enum',
    'options' => 'bug_type_dom',
    'len'=>255
  ),
    'fixed_in_release'=>
  	array(
  	'name'=>'fixed_in_release',
  	'type' => 'varchar',
  	 'vname' => 'LBL_FIXED_IN_RELEASE',
  	'len'=>255,
  	'reportable'=>false
  	),
   'fixed_in_release_name'=>
  array (
    'name' => 'fixed_in_release_name',
    'rname' => 'name',
    'id_name' => 'fixed_in_release',
    'vname' => 'LBL_FIXED_IN_RELEASE',
    'type' => 'relate',
    'table' => 'releases',
    'isnull' => 'false',
    'massupdate' => false,
    'module' => 'Releases',
    'dbType' => 'char',
    'len' => 36,
    'source'=>'non-db'
  ),
   'work_log' => 
  array (
    'name' => 'work_log',
    'vname' => 'LBL_WORK_LOG',
    'type' => 'text',
  ),
    'source' => 
  array (
    'name' => 'source',
    'vname' => 'LBL_SOURCE',
    'type' => 'enum',
    'options'=>'source_dom',
    'len' => 255
  ),
    'product_category' => 
  array (
    'name' => 'product_category',
    'vname' => 'LBL_PRODUCT_CATEGORY',
    'type' => 'enum',
    'options'=>'product_category_dom',
    'len' => 255
  ),
  	

 

)
                                                      , 'indices' => array (
       array('name' =>'bugspk', 'type' =>'primary', 'fields'=>array('id')),
        array('name' =>'number', 'type' =>'index', 'fields'=>array('number')),
         array('name' =>'idx_bug_name', 'type' =>'index', 'fields'=>array('name'))
                                                      )

                            );
?>
