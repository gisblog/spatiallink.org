<?php
$dictionary['accounts_bugs'] = array ( 'table' => 'accounts_bugs'
                                  , 'fields' => array (
       array('name' =>'id', 'type' =>'char', 'len'=>'36', 'required'=>true, 'default'=>'')
      , array('name' =>'account_id', 'type' =>'char', 'len'=>'36')
      , array('name' =>'bug_id', 'type' =>'char', 'len'=>'36')
      , array('name' =>'deleted', 'type' =>'bool', 'len'=>'1', 'required'=>true, 'default'=>'0')
                                                      )                                  , 'indices' => array (
       array('name' =>'accounts_bugspk', 'type' =>'primary', 'fields'=>array('id'))
      , array('name' =>'idx_acc_bug_acc', 'type' =>'index', 'fields'=>array('account_id'))
      , array('name' =>'idx_acc_bug_bug', 'type' =>'index', 'fields'=>array('bug_id'))
                                                      )
                                  )
?>
