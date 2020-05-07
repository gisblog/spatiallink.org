<?php
$dictionary['accounts_opportunities'] = array ( 'table' => 'accounts_opportunities'
                                  , 'fields' => array (
       array('name' =>'id', 'type' =>'char', 'len'=>'36', 'default'=>'', 'required'=>true)
      , array('name' =>'opportunity_id', 'type' =>'char', 'len'=>'36', )
      , array('name' =>'account_id', 'type' =>'char', 'len'=>'36', )
      , array('name' =>'deleted', 'type' =>'bool', 'len'=>'1', 'default'=>'0', 'required'=>true)
                                                      )                                  , 'indices' => array (
       array('name' =>'accounts_opportunitiespk', 'type' =>'primary', 'fields'=>array('id'))
      , array('name' =>'idx_acc_opp_acc', 'type' =>'index', 'fields'=>array('account_id'))
      , array('name' =>'idx_acc_opp_opp', 'type' =>'index', 'fields'=>array('opportunity_id'))
      , array('name' =>'idx_a_o_opp_acc_del', 'type' =>'index', 'fields'=>array('opportunity_id','account_id','deleted'))
                                                      )
                                  )
?>
