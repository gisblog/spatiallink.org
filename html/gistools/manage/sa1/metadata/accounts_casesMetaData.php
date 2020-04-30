<?php
$dictionary['accounts_cases'] = array ( 'table' => 'accounts_cases'
                                  , 'fields' => array (
       array('name' =>'id', 'type' =>'char', 'len'=>'36','required'=>true, 'default'=>'')
      , array('name' =>'account_id', 'type' =>'char', 'len'=>'36')
      , array('name' =>'case_id', 'type' =>'char', 'len'=>'36')
  	,array('name' =>'deleted', 'type' =>'bool', 'len'=>'1', 'required'=>true, 'default'=>'0')
                                                      )                                  , 'indices' => array (
       array('name' =>'accounts_casespk', 'type' =>'primary', 'fields'=>array('id'))
       , array('name' =>'idx_acc_case_acc', 'type' =>'index', 'fields'=>array('account_id'))
      , array('name' =>'idx_acc_acc_case', 'type' =>'index', 'fields'=>array('case_id'))
                                                      )
                                  )
?>
