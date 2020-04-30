<?php
$dictionary['tracker'] = array ( 'table' => 'tracker'
                                  , 'fields' => array (
       array('name' =>'id', 'type' =>'int', 'len'=>'11', 'required'=>true, 'auto_increment'=>true)
      , array('name' =>'user_id', 'type' =>'id', 'len'=>'36', )
      , array('name' =>'module_name', 'type' =>'char', 'len'=>'25', )
      , array('name' =>'item_id', 'type' =>'id', 'len'=>'36', )
      , array('name' =>'item_summary', 'type' =>'char', 'len'=>'255', )
                                                      )                                  , 'indices' => array (
       array('name' =>'trackerpk', 'type' =>'primary', 'fields'=>array('id'))
                                                      )
                                  )
?>
