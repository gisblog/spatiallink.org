<?php
$dictionary['opportunities_contacts'] = array ( 'table' => 'opportunities_contacts'
                                  , 'fields' => array (
       array('name' =>'id', 'type' =>'char', 'len'=>'36', 'default'=>'')
      , array('name' =>'contact_id', 'type' =>'char', 'len'=>'36', )
      , array('name' =>'opportunity_id', 'type' =>'char', 'len'=>'36',)
      , array('name' =>'contact_role', 'type' =>'char', 'len'=>'50', )
      , array('name' =>'deleted', 'type' =>'bool', 'len'=>'1', 'default'=>'0', 'required'=>true)
                                                      )                                  , 'indices' => array (
       array('name' =>'opportunities_contactspk', 'type' =>'primary', 'fields'=>array('id'))
      , array('name' =>'idx_con_opp_con', 'type' =>'index', 'fields'=>array('contact_id'))
      , array('name' =>'idx_con_opp_opp', 'type' =>'index', 'fields'=>array('opportunity_id'))
                                                      )
                                  )
?>
