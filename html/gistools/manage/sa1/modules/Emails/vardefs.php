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
$dictionary['Email'] = array('table' => 'emails'
                               ,'fields' => array (
  'id' => 
  array (
    'name' => 'id',
    'vname' => 'LBL_ID',
    'type' => 'id',
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
  'assigned_user_id' => 
  array (
    'name' => 'assigned_user_id',
    'rname' => 'user_name',
    'id_name' => 'assigned_user_id',
    'vname' => 'LBL_ASSIGNED_TO',
    'type' => 'assigned_user_name',
    'table' => 'users',
    'isnull' => 'false',
    'reportable'=>true,
    'dbType' => 'id',
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
    'reportable'=>true,
    'dbType' => 'id',
  ),
  'created_by' => 
  array (
    'name' => 'created_by',
    'vname' => 'LBL_CREATED_BY',
    'type' => 'id',
    'len'  => '36',
  ),










  'name' => 
  array (
    'name' => 'name',
    'vname' => 'LBL_SUBJECT',
    'type' => 'varchar',
    'required' => true,
    'len' => '255',
  ),
  'date_start' => 
  array (
    'name' => 'date_start',
    'vname' => 'LBL_DATE',
    'type' => 'date',
    'len' => '255',
    'rel_field' => 'time_start',
    'required' => true,
    'massupdate'=>false,
  ),
  'time_start' => 
  array (
    'name' => 'time_start',
    'vname' => 'LBL_TIME',
    'type' => 'time',
    'len' => '255',
    'rel_field' => 'date_start',
  ),
  'parent_type' => 
  array (
    'name' => 'parent_type',
    'type' => 'varchar',
    'reportable'=>false,
    'len' => '25',
  ),
  'parent_id' => 
  array (
    'name' => 'parent_id',
    'type' => 'id',
    'len' => '36',
    'reportable'=>false,
  ), 
  'description' => 
  array (
    'name' => 'description',
    'vname' => 'LBL_DESCRIPTION',
    'type' => 'text',
  ),
  'from_addr' => 
  array (
    'name' => 'from_addr',
    'vname' => 'LBL_FROM',
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
  'to_addrs' => 
  array (
    'name' => 'to_addrs',
    'vname' => 'LBL_TO',
    'type' => 'text',
  ),
  'cc_addrs' => 
  array (
    'name' => 'cc_addrs',
    'vname' => 'LBL_CC',
    'type' => 'text',
  ),
  'bcc_addrs' => 
  array (
    'name' => 'bcc_addrs',
    'vname' => 'LBL_BCC',
    'type' => 'text',
  ),
  'to_addrs_ids' => 
  array (
    'name' => 'to_addrs_ids',
    'type' => 'text',
    'reportable'=>false,
  ),
  'to_addrs_names' => 
  array (
    'name' => 'to_addrs_names',
    'type' => 'text',
    'reportable'=>false,
  ),
  'to_addrs_emails' => 
  array (
    'name' => 'to_addrs_emails',
    'type' => 'text',
    'reportable'=>false,
  ),
  'cc_addrs_ids' => 
  array (
    'name' => 'cc_addrs_ids',
    'type' => 'text',
    'reportable'=>false,
  ),
  'cc_addrs_names' => 
  array (
    'name' => 'cc_addrs_names',
    'type' => 'text',
    'reportable'=>false,
  ),
  'cc_addrs_emails' => 
  array (
    'name' => 'cc_addrs_emails',
    'type' => 'text',
    'reportable'=>false,
  ),
  'bcc_addrs_ids' => 
  array (
    'name' => 'bcc_addrs_ids',
    'type' => 'text',
    'reportable'=>false,
  ),
  'bcc_addrs_names' => 
  array (
    'name' => 'bcc_addrs_names',
    'type' => 'text',
    'reportable'=>false,
  ),
  'bcc_addrs_emails' => 
  array (
    'name' => 'bcc_addrs_emails',
    'type' => 'text',
    'reportable'=>false,
  ),
  'type' => 
  array (
    'name' => 'type',
    'vname' => 'LBL_LIST_TYPE',
    'type' => 'enum',
    'options' => 'dom_email_types',
    'len' => '25',
    'massupdate'=>false,
  ),
  'status' => 
  array (
    'name' => 'status',
    'vname' => 'LBL_STATUS',
    'type' => 'varchar',
    'len' => '25',
  ),
  'deleted' => 
  array (
    'name' => 'deleted',
    'vname' => 'LBL_DELETED',
    'type' => 'bool',
    'required' => true,
    'reportable'=>false,
  ),
  'first_name'=>
  		array(
			'name'=>'first_name',
			'rname'=>'first_name',
			'id_name'=>'contact_id',
			'vname'=>'LBL_CONTACT_FIRST_NAME',
			'type'=>'relate',
			'table'=>'contacts',
			'isnull'=>'true',
			'module'=>'Contacts',
			'source'=>'non-db',
			'massupdate'=>false,
			),
  'last_name'=> 
  		array(
			'name'=>'last_name',
			'rname'=>'last_name',
			'id_name'=>'contact_id',
			'vname'=>'LBL_CONTACT_LAST_NAME',
			'type'=>'relate',
			'table'=>'contacts',
			'isnull'=>'true',
			'module'=>'Contacts',
			'source'=>'non-db',
			'massupdate'=>false,
			),
)
                                                      , 'indices' => array (
       array('name' =>'emailspk', 'type' =>'primary', 'fields'=>array('id')),
       array('name' =>'idx_email_name', 'type'=>'index', 'fields'=>array('name'))
                                                      )

                            );
?>
