<?php
$dictionary['prospect_list_campaigns'] = array ( 

	'table' => 'prospect_list_campaigns',

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
			'name' => 'campaign_id',
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
			'name' => 'prospect_list_campaignspk',
			'type' => 'primary',
			'fields' => array ( 'id' )
		),
		array (
			'name' => 'idx_pro_id',
			'type' => 'index',
			'fields' => array ('prospect_list_id')
		),
		array (
			'name' => 'idx_cam_id',
			'type' => 'index',
			'fields' => array ('campaign_id')
		),
	),
)
                                  
?>
