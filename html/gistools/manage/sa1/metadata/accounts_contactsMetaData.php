<?php
$dictionary['accounts_contacts'] = array ( 'table' => 'accounts_contacts'
                                  , 'fields' => array (
       array('name' =>'id', 'type' =>'char', 'len'=>'36', 'default'=>'')
      , array('name' =>'contact_id', 'type' =>'char', 'len'=>'36')
      , array('name' =>'account_id', 'type' =>'char', 'len'=>'36')
      , array('name' =>'deleted', 'type' =>'bool', 'len'=>'1', 'required'=>true, 'default'=>'0')
                                                      )                                  , 'indices' => array (
       array('name' =>'accounts_contactspk', 'type' =>'primary', 'fields'=>array('id'))
      , array('name' =>'idx_acc_cont_acc', 'type' =>'index', 'fields'=>array('account_id'))
      , array('name' =>'idx_acc_cont_cont', 'type' =>'index', 'fields'=>array('contact_id'))
                                                      )
                                  )
?>
