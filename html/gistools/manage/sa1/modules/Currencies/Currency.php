<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version
 * 1.1.3 ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an "AS IS" basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied.  See the License
 * for the specific language governing rights and limitations under the
 * License.
 *
 * All copies of the Covered Code must include on each user interface screen:
 *    (i) the "Powered by SugarCRM" logo and
 *    (ii) the SugarCRM copyright notice
 * in the same form as they appear in the distribution.  See full license for
 * requirements.
 *
 * The Original Code is: SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) 2004 SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Id: Currency.php,v 1.19 2005/04/29 08:51:28 majed Exp $
 ********************************************************************************/
include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');

// Contact is used to store customer information.
class Currency extends SugarBean 
{
	// Stored fields
	var $id;
	var $iso4217;
	var $name;
	var $status; 
	var $conversion_rate;
	var $deleted;
	var $date_entered;
	var $date_modified;
	var $symbol;
	var $hide = '';
	var $unhide = '';
	var $field_name_map;

	var $table_name = "currencies";
	var $object_name = "Currency";
	var $module_dir = "Currencies";
	var $new_schema = true;

	var $column_fields = Array("id"
		,"name"
		,"conversion_rate"
		,"iso4217"
		,"symbol"
		,'status'
                ,"deleted"
                ,"date_entered"
                ,"date_modified"
		);
	var $list_fields ;
	

	var $required_fields = array('name'=>1, 'symbol'=>2, 'conversion_rate'=>3, 'iso4217'=>4 , 'status'=>5);
	

	function Currency() 
	{
		$this->log = LoggerManager::getLogger('currency');
		parent::SugarBean();
		$this->list_fields =  array_merge($this->column_fields, array('hide', 'unhide'));
		foreach ($this->field_defs as $field)
		{
			$this->field_name_map[$field['name']] = $field;
		}



	}
	
	
	function convertToDollar($amount){
		return $amount / $this->conversion_rate;	
		
	}
	
	function convertFromDollar($amount){	
		return $amount * $this->conversion_rate;
	}
	
	function getDefaultCurrencyName(){
		return translate('LBL_US_DOLLAR', 'Currencies');	
	}
	
	 function retrieveIDBySymbol($symbol, $encode = true){
	 	$query = "select id from currencies where symbol='$symbol' and deleted=0;";
	 	$result = $this->db->query($query);
	 	if($result){
	 	$row = $this->db->fetchByAssoc($result);
	 	if($row){
	 		return $row['id'];	
	 	}
	 	}
	 	return '';	
	 }
	 
	 
	 function list_view_parse_additional_sections(&$list_form)
	{
		global $isMerge;
		
		if(isset($isMerge) && $isMerge && $this->id != '-99'){
		$list_form->assign('PREROW', '<input name="mergecur[]" type="checkbox" value="'.$this->id.'">');
		}
		return $list_form;
	}
     function retrieve($id, $encode = true){
     	if($id == '-99'){
     		$this->name = 	$this->getDefaultCurrencyName();
     		$this->symbol = '$';
     		$this->id = '-99';
     		$this->conversion_rate = 1;
     		$this->iso4217 = 'USD';
     		$this->deleted = 0;
     		$this->status = 'Active';
     		$this->hide = '<!--';
     		$this->unhide = '-->';
     	}else{
     		parent::retrieve($id, $encode);	
     	}
     	if(!isset($this->name) || $this->deleted == 1){
     		$this->name = 	$this->getDefaultCurrencyName();
     		$this->symbol = '$';
     		$this->conversion_rate = 1;
     		$this->iso4217 = 'USD';
     		$this->id = '-99';
     		$this->deleted = 0;
     		$this->status = 'Active';
     		$this->hide = '<!--';
     		$this->unhide = '-->';
     	}
     	
     }
        

}


?>
