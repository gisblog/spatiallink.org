<?php
$dictionary['meetings_contacts'] = array ( 'table' => 'meetings_contacts'
                                  , 'fields' => array (
       array('name' =>'id', 'type' =>'char', 'len'=>'36', 'default'=>'')
      , array('name' =>'meeting_id', 'type' =>'char', 'len'=>'36', )
      , array('name' =>'contact_id', 'type' =>'char', 'len'=>'36', )
      , array('name' =>'required', 'type' =>'char', 'len'=>'1', 'default'=>'1', )
      , array('name' =>'accept_status', 'type' =>'char', 'len'=>'25', 'default'=>'none')
      , array('name' =>'deleted', 'type' =>'bool', 'len'=>'1', 'default'=>'0', 'required'=>true)
                                                      )                                  , 'indices' => array (
       array('name' =>'meetings_contactspk', 'type' =>'primary', 'fields'=>array('id'))
      , array('name' =>'idx_con_mtg_mtg', 'type' =>'index', 'fields'=>array('meeting_id'))
      , array('name' =>'idx_con_mtg_con', 'type' =>'index', 'fields'=>array('contact_id'))
                                                      )
                                  )
?>
