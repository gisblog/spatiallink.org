<?php
/**
 * Table definition file for the project_task table
 *
 * PHP version 4
 *
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
 */

// $Id: vardefs.php,v 1.26 2005/04/28 06:19:47 robert Exp $
$dictionary['ProjectTask'] = array(
	'table' => 'project_task',
	'fields' => array(
		'id' => array(
			'name' => 'id',
			'vname' => 'LBL_ID',
			'required' => true,
			'type' => 'id',
			'reportable'=>false,
		),
		'date_entered' => array(
			'name' => 'date_entered',
			'vname' => 'LBL_DATE_ENTERED',
			'type' => 'datetime',
			'required' => true,
		),
		'date_modified' => array(
			'name' => 'date_modified',
			'vname' => 'LBL_DATE_MODIFIED',
			'type' => 'datetime',
			'required' => true,
		),
		'assigned_user_id' => array(
			'name' => 'assigned_user_id',
			'rname' => 'user_name',
			'id_name' => 'assigned_user_id',
			'type' => 'assigned_user_name',
			'vname' => 'LBL_ASSIGNED_USER_ID',
			'required' => false,
			'dbType' => 'id',
			'table' => 'users',
			'isnull' => false,
			'reportable'=>true,
		),
		'modified_user_id' => array(
			'name' => 'modified_user_id',
			'rname' => 'user_name',
			'id_name' => 'modified_user_id',
			'vname' => 'LBL_MODIFIED_USER_ID',
			'type' => 'assigned_user_name',
			'table' => 'users',
			'isnull' => 'false',
			'dbType' => 'id',
			'required' => true,
			'default' => '',
			'reportable'=>true,
		),
		'created_by' => array(
			'name' => 'created_by',
			'rname' => 'user_name',
			'id_name' => 'modified_user_id',
			'vname' => 'LBL_CREATED_BY',
			'type' => 'assigned_user_name',
			'table' => 'users',
			'isnull' => 'false',
			'dbType' => 'id',
			'reportable'=>true,
		),








		'name' => array(
			'name' => 'name',
			'vname' => 'LBL_NAME',
			'required' => true,
			'type' => 'varchar',
			'len' => 50,
		),
		'status' => array(
			'name' => 'status',
			'vname' => 'LBL_STATUS',
			'type' => 'enum',
			'required' => false,
			'options' => 'task_status_dom',
		),
		'date_due' => array(
			'name' => 'date_due',
			'vname' => 'LBL_DATE_DUE',
			'type' => 'date',
			'rel_field' => 'time_due',
		),
		'time_due' => array(
			'name' => 'time_due',
			'vname' => 'LBL_TIME_DUE',
			'type' => 'time',
			'rel_field' => 'date_due',
			'reportable'=>false,
		),
		'date_start' => array(
			'name' => 'date_start',
			'vname' => 'LBL_DATE_START',
			'type' => 'date',
			'validation'=>array('type' => 'isbefore', 'compareto'=>'date_due'),
			'rel_field' => 'time_start',
		),
		'time_start' => array(
			'name' => 'time_start',
			'vname' => 'LBL_TIME_START',
			'type' => 'time',
			'rel_field' => 'date_start',
			'reportable'=>false,
		),
		'parent_id' => array(
			'name' => 'parent_id',
			'vname' => 'LBL_PARENT_ID',
			'required' => true,
			'type' => 'id',
			'reportable'=>false,
		),
		'priority' => array(
			'name' => 'priority',
			'vname' => 'LBL_PRIORITY',
			'type' => 'enum',
			'options' => 'project_task_priority_options',
		),
		'description' => array(
			'name' => 'description',
			'vname' => 'LBL_DESCRIPTION',
			'required' => false,
			'type' => 'text',
		),
		'order_number' => array(
			'name' => 'order_number',
			'vname' => 'LBL_ORDER_NUMBER',
			'required' => false,
			'type' => 'int',
			'default' => '1',
		),
		'task_number' => array(
			'name' => 'task_number',
			'vname' => 'LBL_TASK_NUMBER',
			'required' => false,
			'type' => 'int',
		),
		'depends_on_id' => array(
			'name' => 'depends_on_id',
			'vname' => 'LBL_DEPENDS_ON_ID',
			'required' => false,
			'type' => 'id',
			'reportable'=>false,
		),
		'milestone_flag' => array(
			'name' => 'milestone_flag',
			'vname' => 'LBL_MILESTONE_FLAG',
			'type' =>'bool',
			'dbType'=>'enum',
			'options'=>'on|off',
			'required' => false,
		),
		'estimated_effort' => array(
			'name' => 'estimated_effort',
			'vname' => 'LBL_ESTIMATED_EFFORT',
			'required' => false,
			'type' => 'int',
		),
		'actual_effort' => array(
			'name' => 'actual_effort',
			'vname' => 'LBL_ACTUAL_EFFORT',
			'required' => false,
			'type' => 'int',
		),
		'utilization' => array(
			'name' => 'utilization',
			'vname' => 'LBL_UTILIZATION',
			'required' => false,
			'type' => 'int',
			'validation' => array('type' => 'range', 'min' => 0, 'max' => 100),
			'default' => 100,
		),
		'percent_complete' => array(
			'name' => 'percent_complete',
			'vname' => 'LBL_PERCENT_COMPLETE',
			'required' => false,
			'validation' => array('type' => 'range', 'min' => 0, 'max' => 100),
			'default' => 0,
			'type' => 'int',
		),
		'deleted' => array(
			'name' => 'deleted',
			'vname' => 'LBL_DELETED',
			'type' => 'bool',
			'required' => true,
			'default' => '0',
			'reportable'=>false,
		),
		'project_name'=>    array(
			'name'=>'project_name',                 
			'rname'=>'name',
			'id_name'=>'project_id',                 
			'vname'=>'LBL_PROJECT_NAME',
			'type'=>'relate',
			'table'=>'project',
			'isnull'=>'true',
			'module'=>'Project',
			'massupdate'=>false,
			'source'=>'non-db'
                ),

	),
	'indices' => array(
		array(
			'name' =>'project_task_primary_key_index',
			'type' =>'primary',
			'fields'=>array('id')
		),
	),
);

?>
