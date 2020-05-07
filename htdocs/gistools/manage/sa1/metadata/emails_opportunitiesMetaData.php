<?php
$dictionary['emails_opportunities'] = array ( 'table' => 'emails_opportunities'
                                  , 'fields' => array (
       array('name' =>'id', 'type' =>'char', 'len'=>'36', 'default'=>'')
      , array('name' =>'email_id', 'type' =>'char', 'len'=>'36', )
      , array('name' =>'opportunity_id', 'type' =>'char', 'len'=>'36', )
      , array('name' =>'deleted', 'type' =>'bool', 'len'=>'1', 'default'=>'0', 'required'=>true)
                                                      )                                  , 'indices' => array (
       array('name' =>'emails_opportunitiespk', 'type' =>'primary', 'fields'=>array('id'))
      , array('name' =>'idx_opp_email_email', 'type' =>'index', 'fields'=>array('email_id'))
      , array('name' =>'idx_opp_email_opp', 'type' =>'index', 'fields'=>array('opportunity_id'))
                                                      )
                                  )
?>
