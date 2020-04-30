<?php
$dictionary['meetings_users'] = array ( 'table' => 'meetings_users'
                                  , 'fields' => array (

       array('name' =>'id', 'type' =>'char', 'len'=>'36', 'default'=>'')
      , array('name' =>'meeting_id', 'type' =>'char', 'len'=>'36',)
      , array('name' =>'user_id', 'type' =>'char', 'len'=>'36', )
      , array('name' =>'required', 'type' =>'char', 'len'=>'1', 'default'=>'1')
      , array('name' =>'accept_status', 'type' =>'char', 'len'=>'25', 'default'=>'none')
      , array('name' =>'deleted', 'type' =>'bool', 'len'=>'1', 'default'=>'0', 'required'=>true)
                                                      )                                  , 'indices' => array (
       array('name' =>'meetings_userspk', 'type' =>'primary', 'fields'=>array('id'))
      , array('name' =>'idx_usr_mtg_mtg', 'type' =>'index', 'fields'=>array('meeting_id'))
      , array('name' =>'idx_usr_mtg_usr', 'type' =>'index', 'fields'=>array('user_id'))
                                                      )
                                  )
?>
