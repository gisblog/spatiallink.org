<?php
$dictionary['roles_users'] = array ( 

	'table' => 'roles_users',

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
			'name' => 'user_id',
			'type' => 'char',
			'len' => '36',
			'default' => '',
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
			'name' => 'roles_userspk',
			'type' => 'primary',
			'fields' => array ( 'id' )
		),
		array (
			'name' => 'idx_role_id',
			'type' => 'index',
			'fields' => array ('role_id')
		),
		array (
			'name' => 'idx_user_id',
			'type' => 'index',
			'fields' => array ('user_id')
		),
	),
)
                                  
?>
