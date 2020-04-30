<?PHP
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
$dictionary['EmailMan'] = 
array (
	'table' => 'emailman',
	'fields'=> array(
	
	'date_entered'=>
				array(
					'name'=>'date_entered',
	        		'vname'=>'LBL_DATE_ENTERED',
					'type'=>'datetime',
					),
    'date_modified'=> 		 
     			array(
					'name'=>'date_modified',
        			'vname'=>'LBL_DATE_MODIFIED',
					'type'=>'datetime',
					),
      'user_id'=>  		 array(
					'name'=>'user_id',
        			'vname'=>'LBL_USER_ID',
					'type'=>'id','len'=>'36',
					'reportable'=>false, 
				),
       'template_id'=> 		 array(
					'name'=>'template_id',
        			'vname'=>'LBL_TEMPLATE_ID',
					'type'=>'id',
					'reportable'=>false,
					'len'=>'36' ),
       'from_email'=> 		 array(
					'name'=>'from_email',
        			'vname'=>'LBL_EMAIL',
					'type'=>'varchar',
					'len'=>'255' ),
      'from_name'=>  		 array(
					'name'=>'from_name',
        			'vname'=>'LBL_NAME',
					'type'=>'varchar',
					'len'=>'255' ),
      'id'=>			array(
					'name'=>'id',
        			'vname'=>'LBL_ID',
					'type'=>'id',
					'reportable'=>false,
					'required'=>true ),
       'module_id'=> 	    array(
					'name'=>'module_id',
        			'vname'=>'LBL_MODULE_ID',
					'type'=>'id',
					'reportable'=>false,
					 ),
		 'campaign_id'=> 	    array(
					'name'=>'campaign_id',
        			'vname'=>'LBL_CAMPAIGN_ID',
					'type'=>'id',
					'reportable'=>false,
					 ),
		'marketing_id'=> 	    array(
					'name'=>'marketing_id',
        			'vname'=>'LBL_MARKETING_ID',
					'type'=>'id',
					'reportable'=>false,
					 ),
       'list_id'=> 		array(
					'name'=>'list_id',
        			'vname'=>'LBL_LIST_ID',
					'type'=>'id',
					'reportable'=>false,
					'len'=>'36',
					),
       'module'=> 		array(
					'name'=>'module' ,
        			'vname'=>'LBL_SUBJECT',
					'type'=>'varchar',
					'len'=>'100' ),
        'send_date_time'=>		array(
					'name'=>'send_date_time' ,
        			'vname'=>'LBL_SEND_DATE_TIME',
					'type'=>'datetime',
					 ),
		   'modified_user_id'=> 		array(
					'name'=>'modified_user_id',
        			'vname'=>'LBL_MODIFIED_USER_ID',
					'type'=>'id',
					'reportable'=>false,
					'len'=>'36' 
					),
			 'invalid_email'=> 		array(
					'name'=>'invalid_email',
        			'vname'=>'LBL_INVALID_EMAIL',
					'type'=>'bool',
					),
			 'in_queue'=> 		array(
					'name'=>'in_queue',
        			'vname'=>'LBL_IN_QUEUE',
					'type'=>'bool',
					),
			 'in_queue_date'=> 		array(
					'name'=>'in_queue_date',
        			'vname'=>'LBL_IN_QUEUE_DATE',
					'type'=>'datetime',
					),
			 'send_attempts'=> 		array(
					'name'=>'send_attempts',
        			'vname'=>'LBL_SEND_ATTEMPTS',
					'type'=>'int',
					'default'=>'0',
					),
			'deleted'=> 		array(
					'name'=>'deleted',
        			'vname'=>'LBL_DELETED',
					'type'=>'bool',
					'reportable'=>false,
					),

        		)
      , 'indices' => array (
   array('name' =>'emailmanpk', 'type' =>'primary', 'fields'=>array('id')),
   array('name' =>'idx_eman_list', 'type' =>'index', 'fields'=>array('list_id','user_id','deleted')),
   
                                                      )
        		);
        		
        		
        		
$dictionary['EmailManSent'] = $dictionary['EmailMan'];
$dictionary['EmailManSent']['table'] = 'emailman_sent';
$dictionary['EmailManSent']['indices'] = array (
   array('name' =>'emailmanstpk', 'type' =>'primary', 'fields'=>array('id')),
   array('name' =>'idx_emanst_list', 'type' =>'index', 'fields'=>array('list_id','user_id','deleted')));
   
  

?>
