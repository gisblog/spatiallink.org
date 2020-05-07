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
$dictionary['Contact'] = array('table' => 'contacts'
                               ,'fields' => array (
  'id' => 
  array (
    'name' => 'id',
    'vname' => 'LBL_ID',
    'type' => 'id',
    'required'=>true,
    'reportable'=>false,
  ),
   'deleted' => 
  array (
    'name' => 'deleted',
    'vname' => 'LBL_DELETED',
    'type' => 'bool',
    'required' => true,
    'reportable'=>false,
    'default' => '0',
  ),
   'date_entered' => 
  array (
    'name' => 'date_entered',
    'vname' => 'LBL_DATE_ENTERED',
    'type' => 'datetime',
    'required' => true,
  ),
  'date_modified' => 
  array (
    'name' => 'date_modified',
    'vname' => 'LBL_DATE_MODIFIED',
    'type' => 'datetime',
    'required' => true,
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
    'reportable'=>true,
    'dbType' => 'id',
    'required' => true,
    'default' => '',
  ),
   'assigned_user_id' => 
  array (
    'name' => 'assigned_user_id',
    'rname' => 'user_name',
    'id_name' => 'assigned_user_id',
    'vname' => 'LBL_ASSIGNED_TO',
    'type' => 'assigned_user_name',
    'table' => 'users',
    'reportable'=>true,
    'isnull' => 'false',
    'dbType' => 'id'
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

























   'salutation' => 
  array (
    'name' => 'salutation',
    'vname' => 'LBL_SALUTATION',
    'type' => 'enum',
    'options' => 'salutation_dom',
    'massupdate' => false,
    'len'=>'5',
  ),
  'full_name' => 
  array (
    'name' => 'full_name',
    'rname' => 'full_name',
    'vname' => 'LBL_NAME',
    'type' => 'varchar',
    'source' => 'non-db',
    'len' => '510',
  ),
  'first_name' => 
  array (
    'name' => 'first_name',
    'vname' => 'LBL_FIRST_NAME',
    'type' => 'varchar',
    'len' => '100',
  ),
  'last_name' => 
  array (
    'name' => 'last_name',
    'vname' => 'LBL_LAST_NAME',
    'type' => 'varchar',
    'len' => '100',
  ),
  
  'lead_source' => 
  array (
    'name' => 'lead_source',
    'vname' => 'LBL_LEAD_SOURCE',
    'type' => 'enum',
    'options' => 'lead_source_dom',
    'len'=>'100',
  ),
  
  'title' => 
  array (
    'name' => 'title',
    'vname' => 'LBL_TITLE',
    'type' => 'varchar',
    'len' => '25',
  ),
  'department' => 
  array (
    'name' => 'department',
    'vname' => 'LBL_DEPARTMENT',
    'type' => 'varchar',
    'len' => '100',
  ),
    'account_name' => 
  array (
    'name' => 'account_name',
    'rname' => 'name',
    'id_name' => 'account_id',
    'vname' => 'LBL_ACCOUNT_NAME',
    'type' => 'relate',
    'table' => 'accounts',
    'isnull' => 'true',
    'module' => 'Accounts',
    'dbType' => 'char',
    'len' => '255',
   	'source'=>'non-db',
  ),
    	 'reports_to_id'=>
  array(
  	'name'=>'reports_to_id',
  	'vname'=>'LBL_REPORTS_TO_ID',
  	'type'=>'id',
  	'required'=>false,
  	'reportable'=>false,
  	),
  	    'report_to_name' => 
  array (
    'name' => 'report_to_name',
    'rname' => 'last_name',
    'id_name' => 'report_to_id',
    'vname' => 'LBL_REPORTS_TO',
    'type' => 'relate',
    'table' => 'contacts',
    'isnull' => 'true',
    'module' => 'Contacts',
    'dbType' => 'char',
    'len' => 'id',
  	'reportable'=>false,
   	'source'=>'non-db',
  ),
  'birthdate' => 
  array (
    'name' => 'birthdate',
    'vname' => 'LBL_BIRTHDATE',
    'massupdate' => false,
    'type' => 'date',
  ),
  'do_not_call' => 
  array (
    'name' => 'do_not_call',
    'vname' => 'LBL_DO_NOT_CALL',
    'type'=>'bool',
    'dbType' => 'char',
    'len' => '3',
    'default' =>'0',
  ),
  'phone_home' => 
  array (
    'name' => 'phone_home',
    'vname' => 'LBL_HOME_PHONE',
    'type' => 'phone',
    'dbType' => 'char',
    'len' => '25',
  ),
  'phone_mobile' => 
  array (
    'name' => 'phone_mobile',
    'vname' => 'LBL_MOBILE_PHONE',
    'type' => 'phone',
    'dbType' => 'char',
    'len' => '25',
  ),
  'phone_work' => 
  array (
    'name' => 'phone_work',
    'vname' => 'LBL_OFFICE_PHONE',
    'type' => 'phone',
    'dbType' => 'char',
    'len' => '25',
  ),
  'phone_other' => 
  array (
    'name' => 'phone_other',
    'vname' => 'LBL_OTHER_PHONE',
    'type' => 'phone',
    'dbType' => 'char',
    'len' => '25',
  ),
  'phone_fax' => 
  array (
    'name' => 'phone_fax',
    'vname' => 'LBL_FAX_PHONE',
    'type' => 'phone',
    'dbType' => 'char',
    'len' => '25',
  ),
  'email1' => 
  array (
    'name' => 'email1',
    'vname' => 'LBL_EMAIL_ADDRESS',
    'type' => 'email',
    'dbType' => 'char',
    'len' => '100',
  ),
  'email2' => 
  array (
    'name' => 'email2',
    'vname' => 'LBL_OTHER_EMAIL_ADDRESS',
    'type' => 'email',
    'dbType' => 'char',
    'len' => '100',
  ),
  'assistant' => 
  array (
    'name' => 'assistant',
    'vname' => 'LBL_ASSISTANT',
    'type' => 'varchar',
    'len' => '75',
  ),
  'assistant_phone' => 
  array (
    'name' => 'assistant_phone',
    'vname' => 'LBL_ASSISTANT_PHONE',
    'type' => 'phone',
    'dbType' => 'char',
    'len' => '25',
  ),
  'email_opt_out' => 
  array (
    'name' => 'email_opt_out',
    'vname' => 'LBL_EMAIL_OPT_OUT',
    'type' => 'bool',
     'dbType' => 'char',
    'len' => '3',
    'default' =>'0',
  ),
  'primary_address_street' => 
  array (
    'name' => 'primary_address_street',
    'vname' => 'LBL_PRIMARY_ADDRESS_STREET',
    'type' => 'varchar',
    'len' => '150',
  ),
  'primary_address_city' => 
  array (
    'name' => 'primary_address_city',
    'vname' => 'LBL_PRIMARY_ADDRESS_CITY',
    'type' => 'varchar',
    'len' => '100',
  ),
  'primary_address_state' => 
  array (
    'name' => 'primary_address_state',
    'vname' => 'LBL_PRIMARY_ADDRESS_STATE',
    'type' => 'varchar',
    'len' => '100',
  ),
  'primary_address_postalcode' => 
  array (
    'name' => 'primary_address_postalcode',
    'vname' => 'LBL_PRIMARY_ADDRESS_POSTALCODE',
    'type' => 'varchar',
    'len' => '20',
  ),
  'primary_address_country' => 
  array (
    'name' => 'primary_address_country',
    'vname' => 'LBL_PRIMARY_ADDRESS_COUNTRY',
    'type' => 'varchar',
    'len' => '100',
  ),
  'alt_address_street' => 
  array (
    'name' => 'alt_address_street',
    'vname' => 'LBL_ALT_ADDRESS_STREET',
    'type' => 'varchar',
    'len' => '150',
  ),
  'alt_address_city' => 
  array (
    'name' => 'alt_address_city',
    'vname' => 'LBL_ALT_ADDRESS_CITY',
    'type' => 'varchar',
    'len' => '100',
  ),
  'alt_address_state' => 
  array (
    'name' => 'alt_address_state',
    'vname' => 'LBL_ALT_ADDRESS_STATE',
    'type' => 'varchar',
    'len' => '100',
  ),
  'alt_address_postalcode' => 
  array (
    'name' => 'alt_address_postalcode',
    'vname' => 'LBL_ALT_ADDRESS_POSTALCODE',
    'type' => 'varchar',
    'len' => '20',
  ),
  'alt_address_country' => 
  array (
    'name' => 'alt_address_country',
    'vname' => 'LBL_ALT_ADDRESS_COUNTRY',
    'type' => 'varchar',
    'len' => '100',
  ),
  
'description' => 
  array (
    'name' => 'description',
    'vname' => 'LBL_DESCRIPTION',
    'type' => 'text',
  ),
  'portal_name' => 
  array (
    'name' => 'portal_name',
    'vname' => 'LBL_PORTAL_NAME',
    'type' => 'varchar',
    'len' => '255',
  ),
    'portal_active' => 
  array (
    'name' => 'portal_active',
    'vname' => 'LBL_PORTAL_ACTIVE',
    'type' => 'bool',
    'required' => true,
    'default' => '0',
  ),
  'portal_app' => 
  array (
    'name' => 'portal_app',
    'vname' => 'LBL_PORTAL_APP',
    'type' => 'varchar',
    'len' => '255',
  ),
  'invalid_email' => 
  array (
    'name' => 'invalid_email',
    'vname' => 'LBL_INVALID_EMAIL',
    'type' => 'bool',
  ),



)
                                                      , 'indices' => array (
       array('name' =>'contactspk', 'type' =>'primary', 'fields'=>array('id')),
       array('name' =>'idx_cont_last_first', 'type' =>'index', 'fields'=>array('last_name', 'first_name', 'deleted')),
       array('name' =>'idx_contacts_del_last', 'type' =>'index', 'fields'=>array( 'deleted', 'last_name')),
       array('name' =>'idx_cont_del_reports', 'type' =>'index', 'fields'=>array('deleted', 'reports_to_id', 'last_name')),



       )
);
?>
