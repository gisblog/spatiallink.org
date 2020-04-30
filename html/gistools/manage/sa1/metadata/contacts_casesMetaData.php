<?php
$dictionary['contacts_cases'] = array ( 'table' => 'contacts_cases'
                                  , 'fields' => array (
       array('name' =>'id', 'type' =>'char', 'len'=>'36', 'default'=>'')
      , array('name' =>'contact_id', 'type' =>'char', 'len'=>'36', )
      , array('name' =>'case_id', 'type' =>'char', 'len'=>'36', )
      , array('name' =>'contact_role', 'type' =>'char', 'len'=>'50', )
      , array('name' =>'deleted', 'type' =>'bool', 'len'=>'1', 'default'=>'0','required'=>true)
                                                      )                                  , 'indices' => array (
       array('name' =>'contacts_casespk', 'type' =>'primary', 'fields'=>array('id'))
      , array('name' =>'idx_con_case_con', 'type' =>'index', 'fields'=>array('contact_id'))
      , array('name' =>'idx_con_case_case', 'type' =>'index', 'fields'=>array('case_id'))
                                                      )
                                  )
?>
