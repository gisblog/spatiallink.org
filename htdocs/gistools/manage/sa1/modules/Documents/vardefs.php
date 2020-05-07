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
$dictionary['Document'] = array('table' => 'documents'
                               ,'fields' => array (
  'id' => 
  array (
    'name' => 'id',
    'vname' => 'LBL_DOCUMENT_ID',
    'type' => 'char',
    'len' => '36',
    'required'=>true,
    'reportable'=>false,
  ),

  'document_name' => 
  array (
    'name' => 'document_name',
    'vname' => 'LBL_NAME',
    'type' => 'varchar',
    'len' => '255',
    'required'=>true
  ),
'active_date' => 
  array (
    'name' => 'active_date',
    'vname' => 'LBL_DOC_ACTIVE_DATE',
    'type' => 'date',
  ),

'exp_date' => 
  array (
    'name' => 'exp_date',
    'vname' => 'LBL_DOC_EXP_DATE',
    'type' => 'date',
  ),
  
  'description' => 
  array (
    'name' => 'description',
    'vname' => 'LBL_DESCRIPTION',
    'type' => 'text',
  ),

  'category_id' => 
  array (
    'name' => 'category_id',
    'vname' => 'LBL_SF_CATEGORY',
    'type' => 'enum',
    'len' => '25',
    'options' => 'document_category_dom', 
    'reportable'=>false,   
  ),

  'subcategory_id' => 
  array (
    'name' => 'subcategory_id',
    'vname' => 'LBL_SF_SUBCATEGORY',
    'type' => 'enum',
    'len' => '25',
    'options' => 'document_subcategory_dom', 
    'reportable'=>false,   
  ),

  'status_id' => 
  array (
    'name' => 'status_id',
    'vname' => 'LBL_DOC_STATUS',
    'type' => 'enum',
    'len' => '25',
    'options' => 'document_status_dom',
    'reportable'=>false,    
  ),

  'date_entered' => 
  array (
    'name' => 'date_entered',
    'vname' => 'LBL_DATE_ENTERED',
    'type' => 'datetime',
  ),

  'date_modified' => 
  array (
    'name' => 'date_modified',
    'vname' => 'LBL_DATE_MODIFIED',
    'type' => 'datetime',
  ),

  'deleted' => 
  array (
    'name' => 'deleted',
    'vname' => 'LBL_DELETED',
    'type' => 'bool',
    'default'=> 0,
    'reportable'=>false,
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
  










      
  'document_revision_id'=>
  array (
    'name' => 'document_revision_id',
    'vname' => 'LBL_LATEST_REVISION',
    'type' => 'varchar',
    'len' => '36',
    'reportable'=>false,
  ),  

),

 'indices' => array (
       array('name' =>'documentspk', 'type' =>'primary', 'fields'=>array('id'))
       )
);
$dictionary['DocumentRevision'] = array('table' => 'document_revisions'
                               ,'fields' => array (
  'id' => 
  array (
    'name' => 'id',
    'vname' => 'LBL_REVISION_NAME',
    'type' => 'char',
    'len' => '36',
    'required'=>true,
    'reportable'=>false,
  ),
  
 'change_log' => 
  array (
    'name' => 'change_log',
    'vname' => 'LBL_CHANGE_LOG',
    'type' => 'char',
    'len' => '255',
  ),
  
  'document_id' => 
  array (
    'name' => 'document_id',
    'vname' => 'LBL_DOCUMENT',
    'type' => 'char',
    'len' => '36',
    'required'=>true,
    'reportable'=>false,
  ),
  
   'date_entered' => 
  array (
    'name' => 'date_entered',
    'vname' => 'LBL_DATE_ENTERED',
    'type' => 'datetime',
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
  'filename' => 
  array (
    'name' => 'filename',
    'vname' => 'LBL_FILENAME',
    'type' => 'varchar',
    'len' => '255',
  ),
  'file_ext' => 
  array (
    'name' => 'file_ext',
    'vname' => 'LBL_FILE_EXTENSION',
    'type' => 'varchar',
    'len' => '25',
  ),
  'file_mime_type' => 
  array (
    'name' => 'file_mime_type',
    'vname' => 'LBL_MIME',
    'type' => 'varchar',
    'len' => '100',
  ),
  
  'revision'=>
  array (
    'name' => 'revision',
    'vname' => 'LBL_REVISION',
    'type' => 'varchar',
    'len' => '25',
  ),  
  
  'deleted' => 
  array (
    'name' => 'deleted',
    'vname' => 'LBL_DELETED',
    'type' => 'bool',
    'default' => 0,
    'reportable'=>false,
  ),
), 'indices' => array (
       array('name' =>'documentrevisionspk', 'type' =>'primary', 'fields'=>array('id'))
       )
);
?>
