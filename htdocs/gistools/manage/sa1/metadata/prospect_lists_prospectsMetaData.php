<?php
$dictionary['prospect_lists_prospects'] = array ( 

	'table' => 'prospect_lists_prospects',

	'fields' => array (
		array (
			'name' => 'id',
			'type' => 'char',
			'len' => '36',
			'default' => '',
		),
		array (
			'name' => 'prospect_list_id',
			'type' => 'char',
			'len' => '36',
			'default' => '',
		),
		array (
			'name' => 'prospect_id',
			'type' => 'char',
			'len' => '36',
			'default' => '',
		),
		array (
			'name' => 'contact_id',
			'type' => 'char',
			'len' => '36',
			'default' => '',
		),
		array (
			'name' => 'lead_id',
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
			'name' => 'prospect_lists_prospectspk',
			'type' => 'primary',
			'fields' => array ( 'id' )
		),
		array (
			'name' => 'idx_pro_id',
			'type' => 'index',
			'fields' => array ('prospect_list_id')
		),
		array (
			'name' => 'idx_pros_id',
			'type' => 'index',
			'fields' => array ('prospect_id')
		),
		array (
			'name' => 'idx_cont_id',
			'type' => 'index',
			'fields' => array ('contact_id')
		),
		array (
			'name' => 'idx_lead_id',
			'type' => 'index',
			'fields' => array ('lead_id')
		),
	
	),
)

                                                      
?>
