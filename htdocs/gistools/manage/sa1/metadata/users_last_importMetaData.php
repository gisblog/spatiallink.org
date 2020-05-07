<?php
$dictionary['users_last_import'] = array ( 'table' => 'users_last_import'
                                  , 'fields' => array (
       array('name' =>'id', 'type' =>'char', 'len'=>'36', 'default'=>'')
      , array('name' =>'assigned_user_id', 'type' =>'char', 'len'=>'36', 'default'=>'')
      , array('name' =>'bean_type', 'type' =>'char', 'len'=>'36', 'default'=>'')
      , array('name' =>'bean_id', 'type' =>'char', 'len'=>'36', 'default'=>'')
      , array('name' =>'deleted', 'type' =>'bool', 'len'=>'1', 'default'=>'0', 'required'=>true,)
                                                      )                                  , 'indices' => array (
       array('name' =>'users_last_importpk', 'type' =>'primary', 'fields'=>array('id'))
      , array('name' =>'idx_user_id', 'type' =>'index', 'fields'=>array('assigned_user_id'))
                                                      )
                                  )
?>
