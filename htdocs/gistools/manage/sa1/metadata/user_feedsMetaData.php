<?php
$dictionary['users_feeds'] = array ( 'table' => 'users_feeds'
                                  , 'fields' => array (
    
       array('name' =>'user_id', 'type' =>'char', 'len'=>'36', 'default'=>'', 'required' => true)
      , array('name' =>'feed_id', 'type' =>'char', 'len'=>'36', 'default'=>'', 'required' => true)
      , array('name' =>'rank', 'type' =>'int', 'default'=>'', 'required' => true)
      , array('name' =>'deleted', 'type' =>'bool', 'len'=>'', 'default'=>'0', 'required' => true)
                                                      ) 
                                 , 'indices' => array (
  
       array('name' =>'idx_user_id', 'type' =>'index', 'fields'=>array('user_id', 'feed_id'))                                  
                                                      )
                                  )
?>
