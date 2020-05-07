<?php
$dictionary['custom_fields'] = array ( 'table' => 'custom_fields'
                                  , 'fields' => array (
       array('name' =>'bean_id', 'type' =>'char', 'len'=>'36', 'default'=>'')
      , array('name' =>'set_num', 'type' =>'int', 'len'=>'11', 'default'=>'0')
      , array('name' =>'field0', 'type' =>'char', 'len'=>'255', 'default'=>'')
      , array('name' =>'field1', 'type' =>'char', 'len'=>'255', 'default'=>'')
      , array('name' =>'field2', 'type' =>'char', 'len'=>'255', 'default'=>'')
      , array('name' =>'field3', 'type' =>'char', 'len'=>'255', 'default'=>'')
      , array('name' =>'field4', 'type' =>'char', 'len'=>'255', 'default'=>'')
      , array('name' =>'field5', 'type' =>'char', 'len'=>'255', 'default'=>'')
      , array('name' =>'field6', 'type' =>'char', 'len'=>'255', 'default'=>'')
      , array('name' =>'field7', 'type' =>'char', 'len'=>'255', 'default'=>'')
      , array('name' =>'field8', 'type' =>'char', 'len'=>'255', 'default'=>'')
      , array('name' =>'field9', 'type' =>'char', 'len'=>'255', 'default'=>'')
      , array('name' =>'deleted', 'type' =>'bool', 'len'=>'1', 'default'=>'0')
                                                      )                                  , 'indices' => array (
       array('name' =>'idx_beanid_set_num', 'type' =>'index', 'fields'=>array('bean_id','set_num'))
                                                      )
                                  )
?>
