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

require_once('include/database/PearDatabase.php');
require_once('modules/Currencies/Currency.php');
$db = new PearDatabase();

$currency = new Currency();
$currencies = array();
function updateAmountBySymbol($id, $symbol, $amount){
	global $db, $currencies;
	if(isset($id) && !empty($id)){
			if(isset($currencies[$symbol])){
					$currencyID = $currencies[$symbol];
					$currency = new Currency();
					$currency->retrieve($currencyID);
					$dollars = $currency->convertToDollar($amount);
			$query = "update opportunities set amount='$amount', currency_id='$currencyID', amount_usdollar='$dollars' where id='$id';";
			$db->query($query);
			}
}
}

function updateAmountByID($id, $curID, $amount){
	global $db, $currencies;
	if(isset($id) && !empty($id)){
			
			$currency = new Currency();
			$currency->retrieve($curID);
			$dollars = $currency->convertToDollar($amount);
			$query = "update opportunities set amount='$amount', currency_id='$curID', amount_usdollar='$dollars' where id='$id';";
			$db->query($query);
	}
}

function doVerify($closed = false){
	global $db, $currency, $currencies, $mod_strings;
	$query = "select id, amount,name from opportunities";
	if(!$closed){
		$query.= " where opportunities.sales_stage <> 'Closed Won' AND opportunities.sales_stage <> 'Closed Lost';";
	}
	$result = $db->query($query);
	$count = 0;
	echo get_form_header($mod_strings['UPDATE_VERIFY'].':','','').'<br>' ;
while($row = $db->fetchByAssoc($result)){
		$amount = '';
		$symbol ='';
		$regs = array();
		print '<table>';
		if($row['amount'] == NULL){
			echo $mod_strings['UPDATE_NULL_VALUE'] . ' ' .$row['name'] .'<br>';
		}else if(!is_numeric($row['amount'])){
			$count++;
			ereg("([^0-9^\.^\]*)([0-9\.\,]+)([^0-9^\.^\]*)", $row['amount'], $regs);	
			if(sizeof($regs) > 3){	
				$amount = $regs[2];
				$symbol .= trim($regs[1]).trim($regs[3]);
			
			}
			
			if(strpos($amount, ',') >  strpos($amount,'.')){
				$amount = str_replace('.', '', $amount);
				$amount = str_replace(',', '.', $amount);
			}else{
				$amount = str_replace(',', '', $amount);
			}
			print '<tr><td>'.$mod_strings['UPDATE_VERIFY_FAIL'] . '</td><td><a href="index.php?module=Opportunities&action=DetailView&record='.$row['id']. '" target="_blank">'.$row['name']. '</a></tr><tr><td></td><td>' . $mod_strings[ 'UPDATE_VERIFY_CURAMOUNT'] . '&nbsp;' . $row['amount']. '</td></tr><tr><td></td><td><b>'. $mod_strings['UPDATE_VERIFY_FIX']. '</b><br>'. $mod_strings['UPDATE_VERIFY_NEWAMOUNT']. '&nbsp;' . $amount . '<br>' . $mod_strings['UPDATE_VERIFY_NEWCURRENCY'] . $symbol . '</td></tr>';
			}
			
		
}		
		print '</table>';
		print $mod_strings['UPDATE_BUGFOUND_COUNT'].' ' .  $count . '<br>';
		print $mod_strings['UPDATE_DONE'].'<br>';	
}

function doFix($closed = false){
	global $db, $currency, $currencies, $mod_strings;
	$query = "select id, amount,name from opportunities";
	if(!$closed){
		$query.= " where opportunities.sales_stage <> 'Closed Won' AND opportunities.sales_stage <> 'Closed Lost';";
	}
	$result = $db->query($query);
	$count = 0;
	echo get_form_header($mod_strings['UPDATE_FIX'].':','','').'<br>' ;
while($row = $db->fetchByAssoc($result)){
		$amount = '';
		$symbol ='';
		$regs = array();
		if($row['amount'] == NULL){
			echo $mod_strings['UPDATE_NULL_VALUE'] . ' ' .$row['name'] .'<br>';
			updateAmountByID($row['id'], '-99', '0');
		}else if(!is_numeric($row['amount'])){
			$count++;
			ereg("([^0-9^\.^\]*)([0-9\.\,]+)([^0-9^\.^\]*)", $row['amount'], $regs);	
			if(sizeof($regs) > 3){	
				$amount = $regs[2];
				$symbol .= trim($regs[1]).trim($regs[3]);
			}
			
			if(strpos($amount, ',') >  strpos($amount,'.')){
				$amount = str_replace('.', '', $amount);
				$amount = str_replace(',', '.', $amount);
			}else{
				$amount = str_replace(',', '', $amount);
			}
			if(!isset($currencies[$symbol])){
			$curid = $currency->retrieveIDBySymbol($symbol);
			if(empty($symbol)){
				$curid = '-99';	
			}
				if(!empty($curid)){
						$currencies[$symbol] = $curid;
				}else{
					echo $mod_strings['UPDATE_CREATE_CURRENCY'] .' '. $symbol. '<br>';
					$temp = new Currency();
					$temp->conversion_rate = 1;
					$temp->iso4217 = substr($symbol, 0, 3);
					$temp->symbol = $symbol;
					$temp->name = $symbol;
					$temp->status = 'Active';
					$temp->save();
					$currencies[$symbol] = $temp->id;
						
				}
			}
			if(!empty($amount) && !empty($amount)){
				$query = "update opportunities set amount_backup='". $row['amount']."' where id='".$row['id']."' and opportunities.sales_stage <> 'Closed Won' AND opportunities.sales_stage <> 'Closed Lost';";
				$db->query($query);
			 	updateAmountBySymbol($row['id'], $symbol ,$amount);
			}
			
				
		}
		
		
}		
		print $mod_strings['UPDATE_BUG_COUNT'].' ' .  $count . '<br>';
		print $mod_strings['UPDATE_DONE'].'<br>';	
		doVerify();
}

function doUpdateDollarAmounts($closed = false){
	global $db, $currency, $currencies, $mod_strings;
	$query = "select name, id,currency_id, amount from opportunities";
	if(!$closed){
		$query.= " where opportunities.sales_stage <> 'Closed Won' AND opportunities.sales_stage <> 'Closed Lost';";
	}
	$result = $db->query($query);
	$count = 0;
	echo get_form_header($mod_strings['UPDATE_DOLLARAMOUNTS'].':', '', '').'<br>' ;
while($row = $db->fetchByAssoc($result)){
			if(is_numeric($row['amount'])){
			updateAmountByID($row['id'], $row['currency_id'] ,$row['amount']);
			$count++;
			}else{
				print $mod_strings['UPDATE_FAIL'] . 	$row['name'] . '<br>';
			}
			
			
			
				
		}
	print $mod_strings['UPDATE_COUNT'] .' ' . $count . '<br>';
	print $mod_strings['UPDATE_DONE'].'<br>';	
}

function doRestoreAmounts(){
	global $db, $currency, $currencies, $mod_strings;
	$query = 'select id, amount_backup from opportunities;';
	$result = $db->query($query);
	$count = 0;
	echo get_form_header($mod_strings['UPDATE_RESTORE'].':', '', '').'<br>' ;
while($row = $db->fetchByAssoc($result)){
			if($row['amount_backup'] != NULL && !empty($row['amount_backup'])) 	{	
			$amount_backup = $row['amount_backup'];

			$id = $row['id'];
			$query = "update opportunities set amount='$amount_backup' where id='$id' and opportunities.sales_stage <> 'Closed Won' AND opportunities.sales_stage <> 'Closed Lost';";
			$db->query($query);
			$count++;
			}
				
		}
	print $mod_strings['UPDATE_RESTORE_COUNT'] .' ' . $count . '<br>';
	print $mod_strings['UPDATE_DONE'].'<br>';	
}

if(isset($_POST['doAction']) && $_POST['doAction'] == 'verify'){ 
	doVerify(isset($_POST['include_closea']) && $_POST['include_closea'] == 'true');	
}
if(isset($_POST['doAction']) && $_POST['doAction'] == 'fix'){ 
	doFix(isset($_POST['include_closeb']) && $_POST['include_closeb'] == 'true');	
}
else if(isset($_POST['doAction']) && $_POST['doAction'] == 'dollar'){ 
	doUpdateDollarAmounts(isset($_POST['include_closec']) && $_POST['include_closec'] == 'true');
		
}
else if(isset($_POST['doAction']) && $_POST['doAction'] == 'restore'){ 
	doRestoreAmounts();
		
}else{
		
}


echo get_form_header($mod_strings['UPDATE'], '', '');
echo <<<EOQ
		<form action='index.php' method='post' name='update'>
<input type='hidden' name='action' value='UpgradeCurrency'>
<input type='hidden' name='module' value='Opportunities'>
<input type='hidden' name='doAction' value=''>
<table width="100%" border="0" cellspacing="$gridline" cellpadding="0" class="tabDetailView2">
<tr>
<td width='20%' class="tabDetailViewDL2"><input type='button' name='sanityCheck' class='button' value='{$mod_strings['UPDATE_VERIFY']}' onclick='document.update.doAction.value="verify"; document.update.submit();'><br>
<input type='checkbox' class='checkbox' name='include_closea' value='true' style='vertical-align: middle;'>{$mod_strings['UPDATE_INCLUDE_CLOSE']}</td>
<td class="tabDetailViewDF2">{$mod_strings['UPDATE_VERIFY_TXT']}</td>

</tr> 
<tr>
<td width='20%' class="tabDetailViewDL2"><input type='button' name='sanityCheck' class='button' value='{$mod_strings['UPDATE_FIX']}' onclick='document.update.doAction.value="fix"; document.update.submit();'><br>
<input type='checkbox' class='checkbox'  name='include_closeb' value='true' style='vertical-align: middle;'>&nbsp;{$mod_strings['UPDATE_INCLUDE_CLOSE']}</td>
<td class="tabDetailViewDF2">{$mod_strings['UPDATE_FIX_TXT']}</td></tr> 

<tr>
<td width='20%' class="tabDetailViewDL2"><input type='button' name='sanityCheck' class='button' value='{$mod_strings['UPDATE_MERGE']}' onclick='document.update.action.value="index";document.update.module.value="Currencies";document.update.doAction.value="merge"; document.update.submit();'>&nbsp;</td>
<td class="tabDetailViewDF2">{$mod_strings['UPDATE_MERGE_TXT']}<BR></td></tr>

<tr>
<td width='20%' class="tabDetailViewDL2"><input type='button' name='sanityCheck' class='button' value='{$mod_strings['UPDATE_DOLLARAMOUNTS']}' onclick='document.update.doAction.value="dollar"; document.update.submit();'><br>
<input type='checkbox' class='checkbox'  name='include_closec' value='true' style='vertical-align: middle;'>&nbsp;{$mod_strings['UPDATE_INCLUDE_CLOSE']}</td>
<td  class="tabDetailViewDF2">{$mod_strings['UPDATE_DOLLARAMOUNTS_TXT']}</td></tr>

<tr>
<td width='20%' class="tabDetailViewDL2"><input type='button' name='sanityCheck' class='button' value='{$mod_strings['UPDATE_RESTORE']}' onclick='document.update.doAction.value="restore"; document.update.submit();'>&nbsp;</td>

<td class="tabDetailViewDF2">{$mod_strings['UPDATE_RESTORE_TXT']}</td></tr>

</table>

</form>


EOQ;



?>

