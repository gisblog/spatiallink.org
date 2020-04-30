<?php
$dictionary['roles_modules'] = array ( 

	'table' => 'roles_modules',

	'fields' => array (
		array (
			'name' => 'id',
			'type' => 'char',
			'len' => '36',
			'default' => '',
		),
		array (
			'name' => 'role_id',
			'type' => 'char',
			'len' => '36',
			'default' => '',
		),
		array (
			'name' => 'module_id',
			'type' => 'char',
			'len' => '36',
			'default' => '',
		),
		array (
			'name' => 'allow',
			'type' => 'bool',
			'len' => '3',
			'default' => '0',
		),
		array (
			'name' => 'deleted',
			'type' => 'bool',
			'len' => '1',
			'default' => '0'
		),
	),
	
	'indices' => array (
		array (
			'name' => 'roles_modulespk',
			'type' => 'primary',
			'fields' => array ( 'id' )
		),
		array (
			'name' => 'idx_role_id',
			'type' => 'index',
			'fields' => array ('role_id')
		),
		array (
			'name' => 'idx_module_id',
			'type' => 'index',
			'fields' => array ('module_id')
		),
	),
)
                                  
?>
