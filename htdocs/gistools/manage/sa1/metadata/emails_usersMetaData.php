<?php
$dictionary['emails_users'] = array ( 'table' => 'emails_users'
                                  , 'fields' => array (
       array('name' =>'id', 'type' =>'char', 'len'=>'36', 'default'=>'')
      , array('name' =>'email_id', 'type' =>'char', 'len'=>'36', )
      , array('name' =>'user_id', 'type' =>'char', 'len'=>'36',)
      , array('name' =>'deleted', 'type' =>'bool', 'len'=>'1', 'default'=>'0', 'required'=>true)
                                                      )                                  , 'indices' => array (
       array('name' =>'emails_userspk', 'type' =>'primary', 'fields'=>array('id'))
      , array('name' =>'idx_usr_email_email', 'type' =>'index', 'fields'=>array('email_id'))
      , array('name' =>'idx_usr_email_usr', 'type' =>'index', 'fields'=>array('user_id'))
                                                      )
                                  )
?>
