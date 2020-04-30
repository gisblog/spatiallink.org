<?php
$dictionary['calls_users'] = array ( 'table' => 'calls_users'
                                  , 'fields' => array (
       array('name' =>'id', 'type' =>'char', 'len'=>'36', 'default'=>'')
      , array('name' =>'call_id', 'type' =>'char', 'len'=>'36')
      , array('name' =>'user_id', 'type' =>'char', 'len'=>'36')
      , array('name' =>'required', 'type' =>'char', 'len'=>'1', 'default'=>'1')
      , array('name' =>'accept_status', 'type' =>'char', 'len'=>'25', 'default'=>'none')
      , array('name' =>'deleted', 'type' =>'bool', 'len'=>'1', 'default'=>'0', 'required'=>true)
                                                      )                                  , 'indices' => array (
       array('name' =>'calls_userspk', 'type' =>'primary', 'fields'=>array('id'))
      , array('name' =>'idx_usr_call_call', 'type' =>'index', 'fields'=>array('call_id'))
      , array('name' =>'idx_usr_call_usr', 'type' =>'index', 'fields'=>array('user_id'))
                                                      )
                                  )
?>
