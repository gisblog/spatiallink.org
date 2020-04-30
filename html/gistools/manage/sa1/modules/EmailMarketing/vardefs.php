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

$dictionary['EmailMarketing'] = array('table' => 'email_marketing'
                               ,'fields' => array (
  'id' => 
  array (
    'name' => 'id',
    'vname' => 'LBL_NAME',
    'type' => 'id',
    'required'=>true,
  ),
  		'deleted' => array (
			'name' => 'deleted',
			'vname' => 'LBL_CREATED_BY',
			'type' => 'bool',
			'required' => true,
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
    'vname' => 'LBL_NAME',
    'type' => 'varchar',
    'len' => '255',
  ),
  'from_addr' => 
  array (
    'name' => 'from_addr',
    'vname' => 'LBL_FROM_ADDR',
    'type' => 'varchar',
    'len' => '100',
  ),
  'from_name' => 
  array (
    'name' => 'from_name',
    'vname' => 'LBL_FROM_NAME',
    'type' => 'varchar',
    'len' => '100',
  ),
  'date_start' => 
  array (
    'name' => 'date_start',
    'vname' => 'LBL_DATE_START',
    'type' => 'date',
    'rel_field' => 'time_start',
  ),
  'time_start' => 
  array (
    'name' => 'time_start',
    'vname' => 'LBL_TIME_START',
    'type' => 'time',
    'rel_field' => 'date_start',
  ),  
  'template_id' => 
  array (
    'name' => 'template_id',
    'vname' => 'LBL_TEMPLATE',
    'type' => 'id',
    'required'=>true,
  ),
  'campaign_id' => 
  array (
    'name' => 'campaign_id',
    'vname' => 'LBL_CAMPAIGN_ID',
    'type' => 'id',
    'required'=>true,
  ),
  ),
  'indices' => array (
       array('name' =>'emmkpk', 'type' =>'primary', 'fields'=>array('id')),
       array('name' =>'idx_emmkt_name', 'type'=>'index', 'fields'=>array('name')),
       array('name' =>'idx_emmkit_del', 'type'=>'index', 'fields'=>array('deleted')),
  )
);


?>
