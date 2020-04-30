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
 
 class ListCurrency{
	var $focus = null;
	var $list = null;
	var $javascript = '<script>';
	function lookupCurrencies(){
		require_once('modules/Currencies/Currency.php');
		$this->focus = new Currency();
		$this->list = $this->focus->get_full_list('name');
		$this->focus->retrieve('-99');
	  	if(is_array($this->list)){
		$this->list = array_merge(Array($this->focus), $this->list);
	  	}else{
	  		$this->list = Array($this->focus);	
	  	} 
		
	}
	function handleAdd(){
			global $current_user;
			if($current_user->is_admin){
			if(isset($_POST['edit']) && $_POST['edit'] == 'true' && isset($_POST['name']) && !empty($_POST['name']) && isset($_POST['conversion_rate']) && !empty($_POST['conversion_rate']) && isset($_POST['symbol']) && !empty($_POST['symbol'])){
				require_once('modules/Currencies/Currency.php');
				$currency = new Currency();
				if(isset($_POST['record']) && !empty($_POST['record'])){
	
					$currency->retrieve($_POST['record']);
				}
				$currency->name = $_POST['name'];
				$currency->status = $_POST['status'];
				$currency->symbol = $_POST['symbol'];
				$currency->iso4217 = $_POST['iso4217'];
				$currency->conversion_rate = $_POST['conversion_rate'];
				$currency->save();
				$this->focus = $currency;
			}
			}
		
	}
		
	function handleUpdate(){
		global $current_user;
			if($current_user->is_admin){
				if(isset($_POST['id']) && !empty($_POST['id'])&&isset($_POST['name']) && !empty($_POST['name']) && isset($_POST['rate']) && !empty($_POST['rate']) && isset($_POST['symbol']) && !empty($_POST['symbol'])){
			$ids = $_POST['id'];
			$names= $_POST['name'];
			$symbols= $_POST['symbol'];
			$rates  = $_POST['rate'];
			$isos  = $_POST['iso'];
			$size = sizeof($ids);
			if($size != sizeof($names)|| $size != sizeof($isos) || $size != sizeof($symbols) || $size != sizeof($rates)){
				return;	
			}
			require_once('modules/Currencies/Currency.php');
				$temp = new Currency();
			for($i = 0; $i < $size; $i++){
				$temp->id = $ids[$i];
				$temp->name = $names[$i];
				$temp->symbol = $symbols[$i];
				$temp->iso4217 = $isos[$i];
				$temp->conversion_rate = $rates[$i];
				$temp->save();
			}
	}}
	}
	
	function getJavascript(){
		return $this->javascript . <<<EOQ
					function get_rate(id){
						return ConversionRates[id];
					}
					function ConvertToDollar(amount, rate){
						return amount / rate;
					}
					function ConvertFromDollar(amount, rate){
						return amount * rate;
					}
					function ConvertRate(id,fields){
							for(var i = 0; i < fields.length; i++){
								fields[i].value = toDecimal(ConvertFromDollar(ConvertToDollar(fields[i].value, lastRate), ConversionRates[id]));
							}
							lastRate = ConversionRates[id];
						}
				</script>
EOQ;
	}
	
	
	function getSelectOptions($id = ''){
		global $current_user;
		$this->javascript .="var ConversionRates = new Array(); \n";
		$options = '';
		$this->lookupCurrencies();
		$setLastRate = false;
		if(isset($this->list ) && !empty($this->list )){
		foreach ($this->list as $data){
			if($data->status == 'Active'){
			if($id == $data->id){
			$options .= '<option value="'. $data->id . '" selected>';
			$setLastRate = true;
			$this->javascript .= 'var lastRate = "' . $data->conversion_rate . '";';
			
			}else{
				$options .= '<option value="'. $data->id . '">'	;
			}
			$options .= $data->name . ' : ' . $data->symbol; 
				$this->javascript .=" ConversionRates['".$data->id."'] = '".$data->conversion_rate."';\n";
		}}
		if(!$setLastRate){
			$this->javascript .= 'var lastRate = "1";';
		}
		
	}
	return $options;
	}
	function getTable(){
		$this->lookupCurrencies();
		$usdollar = translate('LBL_US_DOLLAR');
		$currency = translate('LBL_CURRENCY');
		$currency_sym = translate('LBL_CURRENCY_SYMBOL');
		$conv_rate = translate('LBL_CONVERSION_RATE');
		$add = translate('LBL_ADD');
		$delete = translate('LBL_DELETE');
		$update = translate('LBL_UPDATE');
		
		$form = $html = "<br><table cellpadding='0' cellspacing='0' border='0'  class='tabForm'><tr><td><tableborder='0' cellspacing='0' cellpadding='0'>";
		$form .= <<<EOQ
					<form name='DeleteCurrency' action='index.php' method='post'><input type='hidden' name='action' value='{$_REQUEST['action']}'>
					<input type='hidden' name='module' value='{$_REQUEST['module']}'><input type='hidden' name='deleteCur' value=''></form>

					<tr><td><B>$currency</B></td><td><B>ISO 4217</B>&nbsp;</td><td><B>$currency_sym</B></td><td colspan='2'><B>$conv_rate</B></td></tr>
					<tr><td>$usdollar</td><td>USD</td><td>$</td><td colspan='2'>1</td></tr>
					<form name="UpdateCurrency" action="index.php" method="post"><input type='hidden' name='action' value='{$_REQUEST['action']}'>
					<input type='hidden' name='module' value='{$_REQUEST['module']}'>
EOQ;
		if(isset($this->list ) && !empty($this->list )){
		foreach ($this->list as $data){
			
			$form .= '<tr><td><input type="hidden" name="id[]" value="'.$data->id.'">'.$data->name. '<input type="hidden" name="name[]" value="'.$data->name.'"></td><td>'.$data->iso4217. '<input type="hidden" name="iso[]" value="'.$data->iso4217.'"></td><td>'.$data->symbol. '<input type="hidden" name="symbol[]" value="'.$data->symbol.'"></td><td>'.$data->conversion_rate.'&nbsp;</td><td><input type="text" name="rate[]" value="'.$data->conversion_rate.'"><td>&nbsp;<input type="button" name="delete" class="button" value="'.$delete.'" onclick="document.forms[\'DeleteCurrency\'].deleteCur.value=\''.$data->id.'\';document.forms[\'DeleteCurrency\'].submit();"> </td></tr>';
		}
		}
		$form .= <<<EOQ
					<tr><td></td><td></td><td></td><td></td><td></td><td>&nbsp;<input type='submit' name='Update' value='$update' class='button'></TD></form> </td></tr>
					<tr><td colspan='3'><br></td></tr>
					<form name="AddCurrency" action="index.php" method="post">
					<input type='hidden' name='action' value='{$_REQUEST['action']}'>
					<input type='hidden' name='module' value='{$_REQUEST['module']}'>
					<tr><td><input type = 'text' name='addname' value=''>&nbsp;</td><td><input type = 'text' name='addiso' size='3' maxlength='3' value=''>&nbsp;</td><td><input type = 'text' name='addsymbol' value=''></td><td colspan='2'>&nbsp;<input type ='text' name='addrate'></td><td>&nbsp;<input type='submit' name='Add' value='$add' class='button'></td></tr>
					</form></table></td></tr></table>
EOQ;
	return $form;
		
	}
				
		
}

//$lc = new ListCurrency();
//$lc->handleDelete();
//$lc->handleAdd();
//$lc->handleUpdate();
//echo '<select>'. $lc->getSelectOptions() . '</select>';
//echo $lc->getTable();

?>
