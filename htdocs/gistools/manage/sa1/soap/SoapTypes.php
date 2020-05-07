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
$server->wsdl->addComplexType(
    'note_attachment',
    'complexType',
    'struct',
    'all',
    '',
    array(
        "id" => array('name'=>"id",'type'=>'xsd:string'),
		"filename" => array('name'=>"filename",'type'=>'xsd:string'),
		"file" => array('name'=>"file",'type'=>'xsd:string'),
    )
);

$server->wsdl->addComplexType(
    'return_note_attachment',
    'complexType',
    'struct',
    'all',
    '',
    array(
        "note_attachment"=>array('name'=>'note_attachment', 'type'=>'tns:note_attachment'),
		"error"=> array('name'=>'error', 'type'=>'tns:error_value'),
    )
);

$server->wsdl->addComplexType(
   	 'user_auth',
   	 'complexType',
   	 'struct',
   	 'all',
  	  '',
	array(
		'user_name'=>array('name'=>'user_name', 'type'=>'xsd:string'),
		'password' => array('name'=>'password', 'type'=>'xsd:string'), 
		'version'=>array('name'=>'version', 'type'=>'xsd:string'),
	)
	
);

$server->wsdl->addComplexType(
    'field',
	'complexType',
   	 'struct',
   	 'all',
  	  '',
		array(
			'name'=>array('name'=>'name', 'type'=>'xsd:string'),
			'type'=>array('name'=>'type', 'type'=>'xsd:string'),
			'label'=>array('name'=>'label', 'type'=>'xsd:string'),
			'required'=>array('name'=>'required', 'type'=>'xsd:int'),
			'options'=>array('name'=>'options', 'type'=>'tns:name_value_list'),
			
		)
);


$server->wsdl->addComplexType(
    'field_list',
	'complexType',
   	 'array',
   	 '',
  	  'SOAP-ENC:Array',
	array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:field[]')
    ),
	'tns:field'
);




$server->wsdl->addComplexType(
    'name_value',
	'complexType',
   	 'struct',
   	 'all',
  	  '',
		array(
        	'name'=>array('name'=>'name', 'type'=>'xsd:string'),
			'value'=>array('name'=>'value', 'type'=>'xsd:string'),
		)
);
$server->wsdl->addComplexType(
    'name_value_list',
	'complexType',
   	 'array',
   	 '',
  	  'SOAP-ENC:Array',
	array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType', 'wsdl:arrayType'=>'tns:name_value[]')
    ),
	'tns:name_value'
);


//these are just a list of fields we want to get
$server->wsdl->addComplexType(
    'select_fields',
	'complexType',
   	 'array',
   	 '',
  	  'SOAP-ENC:Array',
	array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType', 'wsdl:arrayType'=>'xsd:string[]')
    ),
	'xsd:string'
);



//these are just a list of fields we want to get
$server->wsdl->addComplexType(
    'module_fields',
	'complexType',
   	 'struct',
   	 'all',
  	  '',
		array(
        	'module_name'=>array('name'=>'module_name', 'type'=>'xsd:string'),
			'module_fields'=>array('name'=>'module_fields', 'type'=>'tns:field_list'),
			'error' => array('name' =>'error', 'type'=>'tns:error_value'),
		)
);

$server->wsdl->addComplexType(
    'error_value',
	'complexType',
   	 'struct',
   	 'all',
  	  '',
		array(
        	'number'=>array('name'=>'number', 'type'=>'xsd:string'),
			'name'=>array('name'=>'name', 'type'=>'xsd:string'),
			'description'=>array('name'=>'description', 'type'=>'xsd:string'),
		)
);



$server->wsdl->addComplexType(
    'entry_value',
	'complexType',
   	 'struct',
   	 'all',
  	  '',
		array(
        	'id'=>array('name'=>'id', 'type'=>'xsd:string'),
			'module_name'=>array('name'=>'module_name', 'type'=>'xsd:string'),
			'name_value_list'=>array('name'=>'name_value_list', 'type'=>'tns:name_value_list'),
		)
);

$server->wsdl->addComplexType(
    'entry_list',
	'complexType',
   	 'array',
   	 '',
  	  'SOAP-ENC:Array',
	array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType', 'wsdl:arrayType'=>'tns:entry_value[]')
    ),
	'tns:entry_value'
);






$server->wsdl->addComplexType(
   	 'get_entry_list_result',
   	 'complexType',
   	 'struct',
   	 'all',
  	  '',
	array(
		'result_count' => array('name'=>'result_count', 'type'=>'xsd:int'),
		'next_offset' => array('name'=>'next_offset', 'type'=>'xsd:int'),
		'field_list'=>array('name'=>'field_list', 'type'=>'tns:field_list'),
		'entry_list' => array('name' =>'entry_list', 'type'=>'tns:entry_list'),
		'error' => array('name' =>'error', 'type'=>'tns:error_value'),
	)
);

$server->wsdl->addComplexType(
   	 'get_entry_result',
   	 'complexType',
   	 'struct',
   	 'all',
  	  '',
	array(
		'field_list'=>array('name'=>'field_list', 'type'=>'tns:field_list'),
		'entry_list' => array('name' =>'entry_list', 'type'=>'tns:entry_list'),
		'error' => array('name' =>'error', 'type'=>'tns:error_value'),
	)
);

$server->wsdl->addComplexType(
   	 'set_entry_result',
   	 'complexType',
   	 'struct',
   	 'all',
  	  '',
	array(
		'id' => array('name'=>'id', 'type'=>'xsd:string'),
		'error' => array('name' =>'error', 'type'=>'tns:error_value'),
	)
);



?>
