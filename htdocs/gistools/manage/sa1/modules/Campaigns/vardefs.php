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
$dictionary['Campaign'] = array (
	'table' => 'campaigns',
	
	'fields' => array (
	
		'id' => array (
			'name' => 'id',
			'vname' => 'LBL_ID',
			'type' => 'id',
			'required' => true,
			'reportable'=>false,
			),
		'tracker_key' => array (
			'name' => 'tracker_key',
			'vname' => 'LBL_TRACKER_KEY',
			'type' => 'int',
			'len' => '11',
			'auto_increment' => true,
			),
		'tracker_count' => array (
			'name' => 'tracker_count',
			'vname' => 'LBL_TRACKER_COUNT',
			'type' => 'int',
			'len' => '11',
			'default' => '0',
		),
		'name' => array (
			'name' => 'name',
			'vname' => 'LBL_CAMPAIGN_NAME',
			'type' => 'varchar',
			'len' => '50',
			),
		'refer_url' => array (
			'name' => 'refer_url',
			'vname' => 'LBL_REFER_URL',
			'type' => 'varchar',
			'len' => '255',
			'default' => 'http://',
		),
		'tracker_text' => array (
			'name' => 'tracker_text',
			'vname' => 'LBL_TRACKER_TEXT',
			'type' => 'varchar',
			'len' => '255',
		),
		'date_entered' => array (
			'name' => 'date_entered',
			'vname' => 'LBL_DATE_ENTERED',
			'type' => 'datetime',
			),
		'date_modified' => array (
			'name' => 'date_modified',
			'vname' => 'LBL_DATE_MODIFIED',
			'type' => 'datetime',
			),
		'modified_user_id' => array (
			'name' => 'modified_user_id',
			'rname' => 'user_name',
			'id_name' => 'modified_user_id',
			'vname' => 'LBL_MODIFIED',
			'type' => 'assigned_user_name',
			'table' => 'modified_user_id_users',
			'isnull' => 'false',
			'reportable'=>true,
			'dbType' => 'id'
			),
		'assigned_user_id' => array (
			'name' => 'assigned_user_id',
			'rname' => 'user_name',
			'id_name' => 'assigned_user_id',
			'vname' => 'LBL_ASSIGNED_TO',
			'type' => 'assigned_user_name',
			'table' => 'users',
			'isnull' => 'false',
			'reportable'=>true,
			'dbType' => 'id'
			),
		'created_by' => array (
			'name' => 'created_by',
			'rname' => 'user_name',
			'id_name' => 'created_by',
			'vname' => 'LBL_CREATED',
			'type' => 'assigned_user_name',
			'table' => 'created_by_users',
    		'isnull' => 'false',
    		'dbType' => 'id'
  			),























		'deleted' => array (
			'name' => 'deleted',
			'vname' => 'LBL_CREATED_BY',
			'type' => 'bool',
			'required' => true,
			'reportable'=>false,
		),
		'start_date' => array (
			'name' => 'start_date',
			'vname' => 'LBL_CAMPAIGN_START_DATE',
			'type' => 'date',
		),
		'end_date' => array (
			'name' => 'end_date',
			'vname' => 'LBL_CAMPAIGN_END_DATE',
			'type' => 'date',
		),
		'status' => array (
			'name' => 'status',
			'vname' => 'LBL_CAMPAIGN_STATUS',
			'type' => 'enum',
			'options' => 'campaign_status_dom',
			'len' => '25',
		),
		'budget' => array (
			'name' => 'budget',
			'vname' => 'LBL_CAMPAIGN_BUDGET',
			'type' => 'float',
			'dbtype' => 'double',
		),
		'expected_cost' => array (
			'name' => 'expected_cost',
			'vname' => 'LBL_CAMPAIGN_EXPECTED_COST',
			'type' => 'float',
			'dbtype' => 'double',
		),
		'actual_cost' => array (
			'name' => 'actual_cost',
			'vname' => 'LBL_CAMPAIGN_ACTUAL_COST',
			'type' => 'float',
			'dbtype' => 'double',
		),
		'expected_revenue' => array (
			'name' => 'expected_revenue',
			'vname' => 'LBL_CAMPAIGN_EXPECTED_REVENUE',
			'type' => 'float',
			'dbtype' => 'double',
		),
		'campaign_type' => array (
			'name' => 'campaign_type',
			'vname' => 'LBL_CAMPAIGN_TYPE',
			'type' => 'enum',
			'options' => 'campaign_status_dom',
			'len' => '25',
		),
		'objective' => array (
			'name' => 'objective',
			'vname' => 'LBL_CAMPAIGN_OBJECTIVE',
			'type' => 'text',
		),
		'content' => array (
			'name' => 'content',
			'vname' => 'LBL_CAMPAIGN_CONTENT',
			'type' => 'text',
		),
	
	),
	
	'indices' => array (
		array (
			'name' =>'campaignspk',
			'type' =>'primary',
			'fields'=>array('id')
		),
		array (
			'name' => 'auto_tracker_key' , 
			'type'=>'index' , 
			'fields'=>array('tracker_key')
		),
		array (
			'name' =>'idx_campaign_name',
			'type' =>'index',
			'fields'=>array('name')
		),	
	),
);
?>
