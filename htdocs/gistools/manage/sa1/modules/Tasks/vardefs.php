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
$dictionary['Task'] = array('table' => 'tasks'
                               ,'fields' => array (
   'id' => 
  array (
    'name' => 'id',
    'vname' => 'LBL_ID',
    'type' => 'id',
    'required'=>true,
    'reportable'=>false,
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
  'assigned_user_id' => 
  array (
    'name' => 'assigned_user_id',
    'rname' => 'user_name',
    'id_name' => 'assigned_user_id',
    'vname' => 'LBL_ASSIGNED_TO',
    'type' => 'assigned_user_name',
    'table' => 'users',
    'isnull' => 'false',
    'dbType' => 'id',
    'reportable'=>true,
  ),
  'modified_user_id' => 
  array (
    'name' => 'modified_user_id',
    'rname' => 'user_name',
    'id_name' => 'modified_user_id',
    'vname' => 'LBL_MODIFIED',
    'type' => 'assigned_user_name',
    'table' => 'users',
    'isnull' => 'false',
    'dbType' => 'id',
    'reportable'=>true,
  ),
  'created_by' => 
  array (
    'name' => 'created_by',
    'rname' => 'user_name',
    'id_name' => 'modified_user_id',
    'vname' => 'LBL_CREATED',
    'type' => 'assigned_user_name',
    'table' => 'users',
    'isnull' => 'false',
    'dbType' => 'id'
  ),










  'name' => 
  array (
    'name' => 'name',
    'vname' => 'LBL_SUBJECT',
    'type' => 'varchar',
    'len' => '50',
  ),
  'status' => 
  array (
    'name' => 'status',
    'vname' => 'LBL_STATUS',
    'type' => 'enum',
    'options' => 'task_status_dom',
    'len'=>25,
  ),
  'date_due_flag' => 
  array (
    'name' => 'date_due_flag',
    'vname' => 'LBL_DATE_DUE_FLAG',
    'type' =>'bool',
    'dbType'=>'enum',
    'options'=>'on|off',
    'default'=>'on',
    'len'=>'5'
  ), 
  'date_due' => 
  array (
    'name' => 'date_due',
    'vname' => 'LBL_DUE_DATE',
    'type' => 'date',
    'rel_field' => 'time_due',
  ),
  'time_due' => 
  array (
    'name' => 'time_due',
    'vname' => 'LBL_DUE_TIME',
    'type' => 'time',
    'rel_field' => 'date_due',
  ),
  'date_start_flag' => 
  array (
    'name' => 'date_start_flag',
    'vname' => 'LBL_DATE_START_FLAG',
    'type' =>'bool',
    'dbType'=>'enum',
    'options'=>'on|off',
    'default'=>'on',
    'len'=>'5'
  ),
  'date_start' => 
  array (
    'name' => 'date_start',
    'vname' => 'LBL_START_DATE',
    'type' => 'date',
    'rel_field' => 'time_start',
  ),
  'time_start' => 
  array (
    'name' => 'time_start',
    'vname' => 'LBL_START_TIME',
    'type' => 'time',
    'rel_field' => 'date_start',
  ),
  'parent_type' => 
  array (
    'name' => 'parent_type',
    'type' => 'varchar',
    'len' => '25',
    'reportable'=>false,
  ),
  'parent_id' => 
  array (
    'name' => 'parent_id',
    'type' => 'id',
    'reportable'=>false,
  ),  
  'contact_id' => 
  array (
    'name' => 'contact_id',
    'type' => 'id',
    'reportable'=>false,
  ), 
  'priority' => 
  array (
    'name' => 'priority',
    'vname' => 'LBL_PRIORITY',
    'type' => 'enum',
    'options' => 'task_priority_dom',
    'len'=>25,
  ),
  'description' => 
  array (
    'name' => 'description',
    'vname' => 'LBL_DESCRIPTION',
    'type' => 'text',
  ),
  'deleted' => 
  array (
    'name' => 'deleted',
    'vname' => 'LBL_DELETED',
    'type' => 'bool',
    'reportable'=>false,
    'required'=>true,
  ),
)
                                                      , 'indices' => array (
       array('name' =>'taskspk', 'type' =>'primary', 'fields'=>array('id')),
       array('name' =>'idx_tsk_name', 'type'=>'index', 'fields'=>array('name')),
       array('name' =>'idx_task_con_del', 'type'=>'index', 'fields'=>array('contact_id','deleted')),
       array('name' =>'idx_task_par_del', 'type'=>'index', 'fields'=>array('parent_id','parent_type','deleted')),
                                                      )
                            );
?>
