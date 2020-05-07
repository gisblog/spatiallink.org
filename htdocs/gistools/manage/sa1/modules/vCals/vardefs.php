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

		// Create the indexes
$dictionary['vcal'] = array('table' => 'vcals'
                               ,'fields' => array (
  'id' => 
  array (
    'name' => 'id',
    'vname' => 'LBL_NAME',
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
    'user_id' => 
  array (
    'name' => 'user_id',
    'type' => 'id',
	'required'=>true,
	'reportable'=>false,
  ),
    'type' => 
  array (
    'name' => 'type',
    'type' => 'varchar',
    'len' =>25,
  ),
  'source' => 
  array (
    'name' => 'source',
    'type' => 'varchar',
    'len' =>25,
  ),
  'content' => 
  array (
    'name' => 'content',
    'type' => 'text',
  ),
  

)
                                                      , 'indices' => array (
       array('name' =>'vcalspk', 'type' =>'primary', 'fields'=>array('id')),
        array('name' =>'idx_vcal', 'type' =>'index', 'fields'=>array('type', 'user_id'))
                                                      )

                            );
?>
