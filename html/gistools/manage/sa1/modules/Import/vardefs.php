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
$dictionary['ImportMap'] = array ( 'table' => 'import_maps'
                                  , 'fields' => array (
  'id' => 
  array (
    'name' => 'id',
    'vname' => 'LBL_ID',
    'type' => 'id',
    'required'=>true,
    'reportable'=>false,
  ),
  'name' => 
  array (
    'name' => 'name',
    'vname' => 'LBL_NAME',
    'type' => 'varchar',
    'len' => '36',
    'required'=>true,
  ),
  'source' => 
  array (
    'name' => 'source',
    'vname' => 'LBL_SOURCE',
    'type' => 'varchar',
    'len' => '36',
    'required'=>true,
  ),
  'module' => 
  array (
    'name' => 'module',
    'vname' => 'LBL_MODULE',
    'type' => 'varchar',
    'len' => '36',
    'required'=>true,
  ),
  'content' => 
  array (
    'name' => 'content',
    'vname' => 'LBL_CONTENT',
    'type' => 'blob',
  ),
  'has_header' => 
  array (
    'name' => 'has_header',
    'vname' => 'LBL_HAS_HEADER',
    'type' => 'bool',
    'default' => '1',
    'required'=>true,
  ),
  'deleted' => 
  array (
    'name' => 'deleted',
    'vname' => 'LBL_DELETED',
    'type' => 'bool',
    'required'=>true,
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
    'dbType' => 'id',
    'reportable'=>false,
  ),
  'is_published' => 
  array (
    'name' => 'is_published',
    'vname' => 'LBL_IS_PUBLISHED',
    'type' => 'varchar',
    'len' => '3',
    'required'=>true,
    'default'=>'no',
  ),
)                                  
                                    , 'indices' => array (
       array('name' =>'import_mapspk', 'type' =>'primary', 'fields'=>array('id')),
       array('name' =>'idx_cont_owner_id_module_and_name', 'type' =>'index', 'fields'=>array('assigned_user_id','module','name','deleted'))
                                                      )
                                  );
                                  
$dictionary['UsersLastImport'] = array ( 'table' => 'users_last_import'
                                  , 'fields' => array (
  'id' => 
  array (
    'name' => 'id',
    'vname' => 'LBL_ID',
    'type' => 'id',
    'required'=>true,
    'reportable'=>false,
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
    'reportable'=>false,
  ),
  'bean_type' => 
  array (
    'name' => 'bean_type',
    'vname' => 'LBL_BEAN_TYPE',
    'type' => 'char',
    'len' => '36',
  ),
  'bean_id' => 
  array (
    'name' => 'bean_id',
    'vname' => 'LBL_BEAN_ID',
    'type' => 'id',
    'reportable'=>false,
  ),
  'deleted' => 
  array (
    'name' => 'deleted',
    'vname' => 'LBL_DELETED',
    'type' => 'bool',
    'reportable'=>false,
    'required'=>true,
  ),
)
                                  , 'indices' => array (
       array('name' =>'users_last_importpk', 'type' =>'primary', 'fields'=>array('id')),
        array('name' =>'idx_user_id', 'type' =>'index', 'fields'=>array('assigned_user_id'))
                                                      )
);
                                  
$dictionary['SugarFile'] = array ( 'table' => 'files'
                                  , 'fields' => array (
 'id' => 
  array (
    'name' => 'id',
    'vname' => 'LBL_ID',
    'type' => 'id',
    'required'=>true,
    'reportable'=>false,
  ),
  'name' => 
  array (
    'name' => 'name',
    'vname' => 'LBL_NAME',
    'type' => 'varchar',
    'len' => '36',
  ),
  'content' => 
  array (
    'name' => 'content',
    'vname' => 'LBL_CONTENT',
    'type' => 'blob',
  ),
  'deleted' => 
  array (
    'name' => 'deleted',
    'vname' => 'LBL_DELETED',
    'type' => 'bool',
    'reportable'=>false,
    'required'=>true,
  ),
   array(
		'name' =>'date_entered', 
		'type' =>'datetime',
		'len'=>'', 
		'required'=>true),
   
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
    'reportable'=>false,
  ),
)
                                  , 'indices' => array (
       array('name' =>'filespk', 'type' =>'primary', 'fields'=>array('id'))
                                                      )
)
                                  
?>
