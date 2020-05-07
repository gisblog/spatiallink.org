<?php
$dictionary['emails_accounts'] = array ( 'table' => 'emails_accounts'
                                  , 'fields' => array (
       array('name' =>'id', 'type' =>'char', 'len'=>'36', 'default'=>'')
      , array('name' =>'email_id', 'type' =>'char', 'len'=>'36', )
      , array('name' =>'account_id', 'type' =>'char', 'len'=>'36', )
      , array('name' =>'deleted', 'type' =>'bool', 'len'=>'1', 'default'=>'0', 'required'=>true)
                                                      )                                  , 'indices' => array (
       array('name' =>'emails_accountspk', 'type' =>'primary', 'fields'=>array('id'))
      , array('name' =>'idx_acc_email_email', 'type' =>'index', 'fields'=>array('email_id'))
      , array('name' =>'idx_acc_email_acc', 'type' =>'index', 'fields'=>array('account_id'))
                                                      )
                                  )
?>
