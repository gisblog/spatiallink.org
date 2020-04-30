<?php
$dictionary['contacts_bugs'] = array ( 'table' => 'contacts_bugs'
                                  , 'fields' => array (
       array('name' =>'id', 'type' =>'char', 'len'=>'36', 'default'=>'')
      , array('name' =>'contact_id', 'type' =>'char', 'len'=>'36', )
      , array('name' =>'bug_id', 'type' =>'char', 'len'=>'36', )
      , array('name' =>'contact_role', 'type' =>'char', 'len'=>'50', )
      , array('name' =>'deleted', 'type' =>'bool', 'len'=>'1', 'default'=>'0','required'=>true)
                                                      )                                  , 'indices' => array (
       array('name' =>'contacts_bugspk', 'type' =>'primary', 'fields'=>array('id'))
      , array('name' =>'idx_con_bug_con', 'type' =>'index', 'fields'=>array('contact_id'))
      , array('name' =>'idx_con_bug_bug', 'type' =>'index', 'fields'=>array('bug_id'))
                                                      )
                                  )
?>
