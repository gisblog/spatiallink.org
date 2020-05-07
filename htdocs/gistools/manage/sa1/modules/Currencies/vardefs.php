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
$dictionary['Currency'] = array('table' => 'currencies'
                               ,'fields' => array (
  'id' => 
  array (
    'name' => 'id',
    'vname' => 'LBL_NAME',
    'type' => 'id',
    'required' => true,
    'reportable'=>false,
    ),
  'name' => 
  array (
    'name' => 'name',
    'vname' => 'LBL_LIST_NAME',
    'type' => 'char',
    'len' => '36',
     'required' => true,
  ),
  'symbol' => 
  array (
    'name' => 'symbol',
    'vname' => 'LBL_LIST_SYMBOL',
    'type' => 'char',
    'len' => '36',
     'required' => true,
  ),
  'iso4217' => 
  array (
    'name' => 'iso4217',
    'vname' => 'LBL_LIST_ISO4217',
    'type' => 'char',
    'len' => '3',
     'required' => true,
  ),
  'conversion_rate' => 
  array (
    'name' => 'conversion_rate',
    'vname' => 'LBL_LIST_RATE',
    'type' => 'float',
    'dbType' => 'double',
    'default' => '0',
     'required' => true,
  ),
  'status' => 
  array (
    'name' => 'status',
    'vname' => 'LBL_STATUS',
    'type' => 'enum',
    'dbType'=>'char',
    'options' => 'currency_status_dom',
    'len' => '25',
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
     'required' => true,
  ),
  'date_modified' => 
  array (
    'name' => 'date_modified',
    'vname' => 'LBL_DATE_MODIFIED',
    'type' => 'datetime',
     'required' => true,
  ),
  'created_by' => 
  array (
    'name' => 'created_by',
    'vname' => 'LBL_CREATED_BY',
    'type' => 'id',
    'len'  => '36',
    'required' => true
  ),
)
                                                      , 'indices' => array (
   array('name' =>'currenciespk', 'type' =>'primary', 'fields'=>array('id')),
   array('name' =>'idx_cont_name', 'type' =>'index', 'fields'=>array('name','deleted'))
                                                      )

                            );
?>
