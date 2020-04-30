<?php
$dictionary['emails_cases'] = array ( 'table' => 'emails_cases'
                                  , 'fields' => array (
       array('name' =>'id', 'type' =>'char', 'len'=>'36', 'default'=>'')
      , array('name' =>'email_id', 'type' =>'char', 'len'=>'36',)
      , array('name' =>'case_id', 'type' =>'char', 'len'=>'36', )
      , array('name' =>'deleted', 'type' =>'bool', 'len'=>'1', 'default'=>'0', 'required'=>true)
                                                      )                                  , 'indices' => array (
       array('name' =>'emails_casespk', 'type' =>'primary', 'fields'=>array('id'))
      , array('name' =>'idx_case_email_email', 'type' =>'index', 'fields'=>array('email_id'))
      , array('name' =>'idx_case_email_case', 'type' =>'index', 'fields'=>array('case_id'))
                                                      )
                                  )
?>
