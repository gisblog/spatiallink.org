<?php
$dictionary['emails_contacts'] = array ( 'table' => 'emails_contacts'
                                  , 'fields' => array (
       array('name' =>'id', 'type' =>'char', 'len'=>'36', 'default'=>'')
      , array('name' =>'email_id', 'type' =>'char', 'len'=>'36', )
      , array('name' =>'contact_id', 'type' =>'char', 'len'=>'36', )
      , array('name' =>'deleted', 'type' =>'bool', 'len'=>'1', 'default'=>'0', 'required'=>true)
                                                      )                                  , 'indices' => array (
       array('name' =>'emails_contactspk', 'type' =>'primary', 'fields'=>array('id'))
      , array('name' =>'idx_con_email_email', 'type' =>'index', 'fields'=>array('email_id'))
      , array('name' =>'idx_con_email_con', 'type' =>'index', 'fields'=>array('contact_id'))
                                                      )
     ,'related_tables'=>array('emails'=>array('id'=>'email_id', 'type'=>'many'), 'contacts'=>array('id'=>'contact_id', 'type'=>'many'))
     ,'role_field'=>''
     ,'owner_module'=>'contacts'
                                  )
?>
