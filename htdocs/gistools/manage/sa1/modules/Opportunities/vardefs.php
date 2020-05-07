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

$dictionary['Opportunity'] = array('table' => 'opportunities'
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
    'dbType' => 'id',
    'required' => true,
    'default' => '',
    'reportable'=>true,
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
  'created_by' => 
  array (
    'name' => 'created_by',
    'rname' => 'user_name',
    'id_name' => 'modified_user_id',
    'vname' => 'LBL_CREATED',
    'type' => 'assigned_user_name',
    'table' => 'users',
    'isnull' => 'false',
    'dbType' => 'id',
    'reportable'=>true,
  ),























  'deleted' => 
  array (
    'name' => 'deleted',
    'vname' => 'LBL_DELETED',
    'type' => 'bool',
    'required' => true,
    'default' => '0',
    'reportable'=>false,
  ), 
  'name' => 
  array (
    'name' => 'name',
    'vname' => 'LBL_OPPORTUNITY_NAME',
    'type' => 'varchar',
    'len' => '50',
  ),
  'opportunity_type' => 
  array (
    'name' => 'opportunity_type',
    'vname' => 'LBL_TYPE',
    'type' => 'enum',
    'options'=> 'opportunity_type_dom',
    'len' => '255',
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
  'lead_source' => 
  array (
    'name' => 'lead_source',
    'vname' => 'LBL_LEAD_SOURCE',
    'type' => 'enum',
    'options' => 'lead_source_dom',
    'len' => '50',
  ),
  'amount' => 
  array (
    'name' => 'amount',
    'vname' => 'Raw Amount',
    'type' => 'float',
    'dbtype' => 'double',
  ), 
  'amount_backup' => 
  array (
    'name' => 'amount_backup',
    'vname' => 'LBL_AMOUNT_BACKUP',
    'type' => 'varchar',
    'len' => '25',
  ),
  'amount_usdollar' => 
  array (
    'name' => 'amount_usdollar',
    'vname' => 'LBL_AMOUNT',
    'type' => 'float',
    'dbtype' => 'double',
  ),
  'currency_id' => 
  array (
    'name' => 'currency_id',
    'type' => 'id',
    'vname' => 'LBL_CURRENCY_ID',
    'reportable'=>false,
  ),
  'date_closed' => 
  array (
    'name' => 'date_closed',
    'vname' => 'LBL_DATE_CLOSED',
    'type' => 'date',
  ),
  'next_step' => 
  array (
    'name' => 'next_step',
    'vname' => 'LBL_NEXT_STEP',
    'type' => 'varchar',
    'len' => '25',
  ),
  'sales_stage' => 
  array (
    'name' => 'sales_stage',
    'vname' => 'LBL_SALES_STAGE',
    'type' => 'enum',
    'options' => 'sales_stage_dom',
    'len' => '25',
  ),
  'probability' => 
  array (
    'name' => 'probability',
    'vname' => 'LBL_PROBABILITY',
    'type' => 'float',
    'dbtype' => 'char',
    'len' => '3',
  ),
  'description' => 
  array (
    'name' => 'description',
    'vname' => 'LBL_DESCRIPTION',
    'type' => 'text',
  ),


),
		'indices' => array (
			array(
				'name' => 'opportunitiespk',
				'type' =>'primary',
				'fields'=>array('id'),
			),
			array(
				'name' => 'idx_opp_name',
				'type' => 'index',
				'fields' => array('name'),
			),







		),
);
?>
