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
$dictionary['EmailTemplate'] = array('table' => 'email_templates'
                               ,'fields' => array (
  'id' => 
  array (
    'name' => 'id',
    'vname' => 'LBL_NAME',
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
  'modified_user_id' => 
  array (
    'name' => 'modified_user_id',
    'rname' => 'user_name',
    'id_name' => 'modified_user_id',
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
    'vname' => 'LBL_CREATED_BY',
    'type' => 'char',
    'len'  => '36',
  ),
  'published' => 
  array (
    'name' => 'published',
    'vname' => 'LBL_PUBLISHED',
    'type' => 'char',
    'len' => '3',
  ),
  'name' => 
  array (
    'name' => 'name',
    'vname' => 'LBL_NAME',
    'type' => 'varchar',
    'len' => '255',
  ),
  'description' => 
  array (
    'name' => 'description',
    'vname' => 'LBL_DESCRIPTION',
    'type' => 'text',
  ),
  'subject' => 
  array (
    'name' => 'subject',
    'vname' => 'LBL_SUBJECT',
    'type' => 'varchar',
    'len' => '255',
  ),
  'body' => 
  array (
    'name' => 'body',
    'vname' => 'LBL_BODY',
    'type' => 'text',
  ),
  'deleted' => 
  array (
    'name' => 'deleted',
    'vname' => 'LBL_DELETED',
    'type' => 'bool',
    'required' => true,
    'reportable'=>false,
  ),









)
                                                      , 'indices' => array (
       array('name' =>'email_templatespk', 'type' =>'primary', 'fields'=>array('id')),
       array('name' =>'idx_email_template_name', 'type'=>'index', 'fields'=>array('name'))
                                                      )

                            );
?>
