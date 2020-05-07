<?php
$dictionary['files'] = array ( 'table' => 'files'
                                  , 'fields' => array (
       array('name' =>'id', 'type' =>'varchar', 'len'=>'36', 'default'=>'')
      , array('name' =>'name', 'type' =>'varchar', 'len'=>'36',)
      , array('name' =>'content', 'type' =>'blob')
      , array('name' =>'deleted', 'type' =>'bool', 'len'=>'1', 'default'=>'0', 'required'=>true)
      , array('name' =>'date_entered', 'type' =>'datetime', 'len'=>'', 'required'=>true)
      , array('name' =>'assigned_user_id', 'type' =>'varchar', 'len'=>'36', )
                                                      )                                  , 'indices' => array (
       array('name' =>'filespk', 'type' =>'primary', 'fields'=>array('id'))
      , array('name' =>'idx_cont_owner_id_and_name', 'type' =>'index', 'fields'=>array('assigned_user_id','name','deleted'))
                                                      )
                                  )
?>
