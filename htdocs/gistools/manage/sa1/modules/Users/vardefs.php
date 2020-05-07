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
$dictionary['User'] = array ( 'table' => 'users'
                                  , 'fields' => array (
  'id' => 
  array (
    'name' => 'id',
    'vname' => 'LBL_ID',
    'type' => 'id',
    'required'=>true,
  ),
  'user_name' => 
  array (
    'name' => 'user_name',
    'vname' => 'LBL_USER_NAME',
    'type' => 'varchar',
    'len' => '20',
  ),  
  'user_password' => 
  array (
    'name' => 'user_password',
    'vname' => 'LBL_USER_PASSWORD',
    'type' => 'varchar',
    'len' => '30',
  ),
  'user_hash' => 
  array (
    'name' => 'user_hash',
    'vname' => 'LBL_USER_HASH',
    'type' => 'varchar',
    'len' => '32',
  ),
  'first_name' => 
  array (
    'name' => 'first_name',
    'vname' => 'LBL_FIRST_NAME',
    'type' => 'varchar',
    'len' => '30',
  ),
  'last_name' => 
  array (
    'name' => 'last_name',
    'vname' => 'LBL_LAST_NAME',
    'type' => 'varchar',
    'len' => '30',
  ),
  'reports_to_id' => 
  array (
    'name' => 'reports_to_id',
    'vname' => 'LBL_ID',
    'type' => 'id',
  ),
  'is_admin' => 
  array (
    'name' => 'is_admin',
    'vname' => 'LBL_IS_ADMIN',
    'type' => 'char',
    'len' => '3',
    'default'=>'0',
  ),
  'receive_notifications' => 
  array (
    'name' => 'receive_notifications',
    'vname' => 'LBL_RECEIVE_NOTIFICATIONS',
    'type' => 'char',
    'len' => '1',
    'default'=>'1',
  ),
  'description' => 
  array (
    'name' => 'description',
    'vname' => 'LBL_DESCRIPTION',
    'type' => 'text',
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
    'vname' => 'LBL_ASSIGNED_TO',
    'type' => 'assigned_user_name',
    'table' => 'users',
    'isnull' => 'false',
    'dbType' => 'id',
    'required'=>true,
  ),
  'created_by' => 
  array (
    'name' => 'created_by',
    'rname' => 'user_name',
    'id_name' => 'modified_user_id',
    'vname' => 'LBL_ASSIGNED_TO',
    'type' => 'assigned_user_name',
    'table' => 'users',
    'isnull' => 'false',
    'dbType' => 'id'
  ),
  'title' => 
  array (
    'name' => 'title',
    'vname' => 'LBL_TITLE',
    'type' => 'varchar',
    'len' => '50',
  ),
  'department' => 
  array (
    'name' => 'department',
    'vname' => 'LBL_DEPARTMENT',
    'type' => 'varchar',
    'len' => '50',
  ), 
  'phone_home' => 
  array (
    'name' => 'phone_home',
    'vname' => 'LBL_PHONE_HOME',
    'type' => 'varchar',
    'len' => '50',
  ),
  'phone_mobile' => 
  array (
    'name' => 'phone_mobile',
    'vname' => 'LBL_PHONE_MOBILE',
    'type' => 'varchar',
    'len' => '50',
  ), 
  'phone_work' => 
  array (
    'name' => 'phone_work',
    'vname' => 'LBL_PHONE_WORK',
    'type' => 'varchar',
    'len' => '50',
  ), 
  'phone_other' => 
  array (
    'name' => 'phone_other',
    'vname' => 'LBL_PHONE_OTHER',
    'type' => 'varchar',
    'len' => '50',
  ),
  'phone_fax' => 
  array (
    'name' => 'phone_fax',
    'vname' => 'LBL_PHONE_FAX',
    'type' => 'varchar',
    'len' => '50',
  ),
  'email1' => 
  array (
    'name' => 'email1',
    'vname' => 'LBL_EMAIL1',
    'type' => 'varchar',
    'len' => '100',
  ), 
  'email2' => 
  array (
    'name' => 'email2',
    'vname' => 'LBL_EMAIL2',
    'type' => 'varchar',
    'len' => '100',
  ), 
  'status' =>
  array(
  	'name' =>'status',
  	'vname' => 'LBL_STATUS', 
  	'type' =>'varchar', 
  	'len'=>'25', 
  ),
  'address_street' => 
  array (
    'name' => 'address_street',
    'vname' => 'LBL_ADDRESS_STREET',
    'type' => 'varchar',
    'len' => '150',
  ),
  'address_city' => 
  array (
    'name' => 'address_city',
    'vname' => 'LBL_ADDRESS_CITY',
    'type' => 'varchar',
    'len' => '100',
  ),
  'address_state' => 
  array (
    'name' => 'address_state',
    'vname' => 'LBL_ADDRESS_STATE',
    'type' => 'varchar',
    'len' => '100',
  ),
  'address_country' => 
  array (
    'name' => 'address_country',
    'vname' => 'LBL_ADDRESS_COUNTRY',
    'type' => 'varchar',
    'len' => '25',
  ),
  'address_postalcode' => 
  array (
    'name' => 'address_postalcode',
    'vname' => 'LBL_ADDRESS_POSTALCODE',
    'type' => 'varchar',
    'len' => '9',
  ),
  'user_preferences' => 
  array (
    'name' => 'user_preferences',
    'vname' => 'LBL_USER_PREFERENCES',
    'type' => 'text',
  ),










  'deleted' => 
  array (
    'name' => 'deleted',
    'vname' => 'LBL_DELETED',
    'type' => 'bool',
    'required'=>true,
  ),
  'portal_only' => 
  array (
    'name' => 'portal_only',
    'vname' => 'LBL_DELETED',
    'type' => 'bool',
    'required'=>true,
  ),  
  'employee_status' =>
  array(
  	'name' =>'employee_status',
  	'vname' => 'LBL_EMPLOYEE_STATUS', 
  	'type' =>'varchar', 
  	'len'=>'25',
  ),
  'messenger_id' =>
  array(
  	'name' =>'messenger_id',
  	'vname' => 'LBL_MESSENGER_ID', 
  	'type' =>'varchar', 
  	'len'=>'25',
  ),
  'messenger_type' =>
  array(
  	'name' =>'messenger_type',
  	'vname' => 'LBL_MESSENGER_TYPE', 
  	'type' =>'varchar', 
  	'len'=>'25',
  ),
),     
                               'indices' => array (
       array('name' =>'userspk', 'type' =>'primary', 'fields'=>array('id')),
       array('name' =>'user_name', 'type' =>'index', 'fields'=>array('user_name')),
       array('name' =>'user_password', 'type' =>'index', 'fields'=>array('user_password'))
                                                      )
  )
?>
