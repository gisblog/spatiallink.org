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
$dictionary['iFrame'] = array('table' => 'iframes'
                               ,'fields' => array (
  'id' => 
  array (
    'name' => 'id',
    'vname' => 'LBL_ID',
    'type' => 'id',
    'required'=>true,
  ),
  'name' => 
  array (
    'name' => 'name',
    'vname' => 'LBL_LIST_NAME',
    'type' => 'char',
    'len' => '255',
    'required'=>true,
  ),
  'url' => 
  array (
    'name' => 'url',
    'vname' => 'LBL_LIST_URL',
    'type' => 'char',
    'len' => '255',
    'required'=>true,
  ),
  'type' => 
  array (
    'name' => 'type',
    'vname' => 'LBL_LIST_TYPE',
    'type' => 'char',
    'len' => '255',
    'required'=>true,
  ),
  'placement' => 
  array (
    'name' => 'placement',
    'vname' => 'LBL_LIST_PLACEMENT',
    'type' => 'char',
    'len' => '255',
    'required'=>true,
  ),
  'status' => 
  array (
    'name' => 'status',
    'vname' => 'LBL_LIST_STATUS',
    'type' => 'bool',
    'required'=>true,
  ),
  'deleted' => 
  array (
    'name' => 'deleted',
    'vname' => 'LBL_DELETED',
    'type' => 'bool',
    'required'=>true,
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
  'created_by' => 
  array (
    'name' => 'created_by',
    'rname' => 'user_name',
    'id_name' => 'modified_user_id',
    'vname' => 'LBL_ASSIGNED_TO',
    'type' => 'assigned_user_name',
    'table' => 'users',
    'isnull' => 'false',
    'dbType' => 'id',
    'required'=>true,
  ),
)
                                                      , 'indices' => array (
       array('name' =>'iframespk', 'type' =>'primary', 'fields'=>array('id')),
       array('name' =>'idx_cont_name', 'type'=>'index', 'fields'=>array('name','deleted'))
                                                      )
                            );
?>
