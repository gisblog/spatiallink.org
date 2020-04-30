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
$dictionary['Meeting'] = array('table' => 'meetings'
                               ,'fields' => array (
  'id' => 
  array (
    'name' => 'id',
    'vname' => 'LBL_NAME',
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
    'dbType' => 'id'
  ),
  'modified_user_id' => 
  array (
    'name' => 'modified_user_id',
    'rname' => 'user_name',
    'id_name' => 'modified_user_id',
    'vname' => 'LBL_MODIFIED_BY',
    'type' => 'assigned_user_name',
    'table' => 'users',
    'isnull' => 'false',
    'dbType' => 'id'
  ),
  'created_by' => 
  array (
    'name' => 'created_by',
    'rname' => 'user_name',
    'id_name' => 'modified_user_id',
    'vname' => 'LBL_CREATED_BY',
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
  'location' => 
  array (
    'name' => 'location',
    'vname' => 'LBL_LOCATION',
    'type' => 'varchar',
    'len' => '50',
  ),
  'duration_hours' => 
  array (
    'name' => 'duration_hours',
    'vname' => 'LBL_DURATION_HOURS',
    'type' => 'char',
    'len' => '2',
  ),
  'duration_minutes' => 
  array (
    'name' => 'duration_minutes',
    'vname' => 'LBL_DURATION_MINUTES',
    'type' => 'char',
    'len' => '2',
  ),
  'date_start' => 
  array (
    'name' => 'date_start',
    'vname' => 'LBL_DATE',
    'type' => 'date',
    'rel_field' => 'time_start',
  ),
  'time_start' => 
  array (
    'name' => 'time_start',
    'vname' => 'LBL_TIME',
    'type' => 'time',
    'rel_field' => 'date_start',
  ),
  'date_end' => 
  array (
    'name' => 'date_end',
    'vname' => 'LBL_DATE_END',
    'type' => 'date',
    'massupdate'=>false,
  ),
  'parent_type' => 
  array (
    'name' => 'parent_type',
    'vname'=>'LBL_LIST_RELATED_TO',
    'type' => 'varchar',
    'len' => '25',
    'reportable'=>false,
  ),
  'status' => 
  array (
    'name' => 'status',
    'vname' => 'LBL_STATUS',
    'type' => 'enum',
    'len' => '25',
    'options' => 'call_status_dom',
  ),
  'parent_id' => 
  array (
    'name' => 'parent_id',
    'vname'=>'LBL_LIST_RELATED_TO',
    'type' => 'id',
    'reportable'=>false,
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
    'required'=>true,
  ),
    'reminder_time' => 
  array (
    'name' => 'reminder_time',
    'vname' => 'LBL_REMINDER_TIME',
    'type' => 'int',
    'default'=>-1,
  ),















  'contact_name' => 
  array (
    'name' => 'contact_name',
    'rname' => 'last_name',
    'id_name' => 'contact_id',
    'massupdate' => false,
    'vname' => 'LBL_CONTACT_NAME',
    'type' => 'relate',
    'table' => 'contacts',
    'isnull' => 'true',
    'module' => 'Contacts',
    'dbType' => 'char',
    'source'=>'non-db',
    'len' => 36,
	)
)
                                                      , 'indices' => array (
       array('name' =>'meetingspk', 'type' =>'primary', 'fields'=>array('id')),
       array('name' =>'idx_mtg_name', 'type'=>'index', 'fields'=>array('name')),
       array('name' =>'idx_meet_par_del', 'type'=>'index', 'fields'=>array('parent_id','parent_type','deleted')),



                                                   )

                            );
?>
