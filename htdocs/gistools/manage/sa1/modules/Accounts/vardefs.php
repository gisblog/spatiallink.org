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
$dictionary['Account'] = array('table' => 'accounts'
                               ,'fields' => array (
  'id' => 
  array (
    'name' => 'id',
    'vname' => 'LBL_ID',
    'required'=>true,
    'reportable'=>false,
    'type' => 'id',
  ),
   'date_entered' => 
  array (
    'name' => 'date_entered',
    'vname' => 'LBL_DATE_ENTERED',
    'type' => 'datetime',
    'required'=>true
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
    'reportable'=>true,
    'isnull' => 'false',
    'dbType' => 'id',
    'required'=> true,
    'defualt' =>'',
    'len' => 36,
  ),
   'assigned_user_id' => 
  array (
    'name' => 'assigned_user_id',
    'rname' => 'user_name',
    'id_name' => 'assigned_user_id',
    'vname' => 'LBL_ASSIGNED_TO',
    'type' => 'assigned_user_name',
    'reportable'=>true,
    'table' => 'users',
    'isnull' => 'false',
    'dbType' => 'id',
    'len' => 36,
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
    'dbType' => 'id',
    'len' => 36,
  ),
   'name' => 
  array (
    'name' => 'name',
    'type' => 'varchar',
    'vname' => 'LBL_NAME',
    'len' => 150,
  ),
   'parent_id' => 
  array (
    'name' => 'parent_id',
    'vname' => 'LBL_NAME',
    'type' => 'id',
    'required'=>false,
    'reportable'=>false,
  ),
   'account_type' => 
  array (
    'name' => 'account_type',
    'vname' => 'LBL_TYPE',
    'type' => 'enum',
    'options' => 'account_type_dom',
    'len'=>25
  ),  
'industry' => 
  array (
    'name' => 'industry',
    'vname' => 'LBL_INDUSTRY',
    'type' => 'enum',
    'options' => 'industry_dom',
    'len'=>25
  ),
    'annual_revenue' => 
  array (
    'name' => 'annual_revenue',
    'vname' => 'LBL_ANNUAL_REVENUE',
    'type' => 'char',
    'len' => 25,
  ),
  'phone_fax' => 
  array (
    'name' => 'phone_fax',
    'vname' => 'LBL_PHONE_FAX',
    'type' => 'phone',
    'dbType' => 'char',
    'len' => 25
  ), 
  
  'billing_address_street' => 
  array (
    'name' => 'billing_address_street',
    'vname' => 'LBL_BILLING_ADDRESS_STREET',
    'type' => 'varchar',
    'len' => '150',
  ),
  'billing_address_city' => 
  array (
    'name' => 'billing_address_city',
    'vname' => 'LBL_BILLING_ADDRESS_CITY',
    'type' => 'varchar',
    'len' => '100',
  ),
  'billing_address_state' => 
  array (
    'name' => 'billing_address_state',
    'vname' => 'LBL_BILLING_ADDRESS_STATE',
    'type' => 'varchar',
    'len' => '100',
  ),
  'billing_address_postalcode' => 
  array (
    'name' => 'billing_address_postalcode',
    'vname' => 'LBL_BILLING_ADDRESS_POSTALCODE',
    'type' => 'varchar',
    'len' => '20',
  ),
  'billing_address_country' => 
  array (
    'name' => 'billing_address_country',
    'vname' => 'LBL_BILLING_ADDRESS_COUNTRY',
    'type' => 'varchar',
    'len' => '100',
  ),
   'description' => 
  array (
    'name' => 'description',
    'vname' => 'LBL_DESCRIPTION',
    'type' => 'text',
  ),
   'rating' => 
  array (
    'name' => 'rating',
    'vname' => 'LBL_RATING',
    'type' => 'varchar',
    'len' => 25,
  ),
    'phone_office' => 
  array (
    'name' => 'phone_office',
    'vname' => 'LBL_PHONE_OFFICE',
    'type' => 'phone',
    'dbType' => 'char',
    'len' => 25,
  ),
    'phone_alternate' => 
  array (
    'name' => 'phone_alternate',
    'vname' => 'LBL_PHONE_ALT',
    'type' => 'phone',
    'dbType' => 'char',
    'len' => 25,
  ),
  'email1' => 
  array (
    'name' => 'email1',
    'vname' => 'LBL_EMAIL',
    'type' => 'email',
    'dbType' => 'char',
    'len' => 100,
  ),
  'email2' => 
  array (
    'name' => 'email2',
    'vname' => 'LBL_OTHER_EMAIL_ADDRESS',
    'type' => 'email',
    'dbType' => 'char',
    'len' => 100,
  ),
   'website' => 
  array (
    'name' => 'website',
    'vname' => 'LBL_WEBSITE',
    'type' => 'varchar',
    'len' => 255,
  ),
   'ownership' => 
  array (
    'name' => 'ownership',
    'vname' => 'LBL_OWNERSHIP',
    'type' => 'varchar',
    'len' => 100,
  ),
   'employees' => 
  array (
    'name' => 'employees',
    'vname' => 'LBL_EMPLOYEES',
    'type' => 'num',
    'len' => 10,
    'dbType' => 'char',
  ),
  'sic_code' => 
  array (
    'name' => 'sic_code',
    'vname' => 'LBL_SIC_CODE',
    'type' => 'varchar',
    'len' => 10,
  ),
  'ticker_symbol' => 
  array (
    'name' => 'ticker_symbol',
    'vname' => 'LBL_TICKER_SYMBOL',
    'type' => 'varchar',
    'len' => 10,
  ),
  'shipping_address_street' => 
  array (
    'name' => 'shipping_address_street',
    'vname' => 'LBL_SHIPPING_ADDRESS_STREET',
    'type' => 'varchar',
    'len' => 150,
  ),
  'shipping_address_city' => 
  array (
    'name' => 'shipping_address_city',
    'vname' => 'LBL_SHIPPING_ADDRESS_CITY',
    'type' => 'varchar',
    'len' => 100,
  ),
  'shipping_address_state' => 
  array (
    'name' => 'shipping_address_state',
    'vname' => 'LBL_SHIPPING_ADDRESS_STATE',
    'type' => 'varchar',
    'len' => 100,
  ),
  'shipping_address_postalcode' => 
  array (
    'name' => 'shipping_address_postalcode',
    'vname' => 'LBL_SHIPPING_ADDRESS_POSTALCODE',
    'type' => 'varchar',
    'len' => 20,
  ),
  'shipping_address_country' => 
  array (
    'name' => 'shipping_address_country',
    'vname' => 'LBL_SHIPPING_ADDRESS_COUNTRY',
    'type' => 'varchar',
    'len' => 100,
  ),
    'deleted' => 
  array (
    'name' => 'deleted',
    'vname' => 'LBL_DELETED',
    'type' => 'bool',
    'required' => true,
    'reportable'=>false,
    'default' => '0'
  ),

























  'account_name' => 
  array (
    'name' => 'account_name',
    'rname' => 'name',
    'id_name' => 'account_id',
    'vname' => 'LBL_MEMBER_OF',
    'type' => 'relate',
    'table' => 'parent_accounts',
    'isnull' => 'true',
    'module' => 'Accounts',
    'massupdate' => false,
    'source'=>'non-db',
    'len' => 36,
  ),  
  
)
, 'indices' => array (
       array('name' =>'accountspk', 'type' =>'primary', 'fields'=>array('id')),
       array('name' =>'idx_accnt_id_del', 'type' =>'index', 'fields'=>array('id', 'deleted')),



                                                      )

                            );
?>
