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

class ColSlotParser extends SlotParser{



	
	function ColSlotParser(){
		global $image_path;
		$this->font_slot = "<img src='$image_path" . "slot.gif' alt='Slot' border='0'>";
	}
	
	
	
	
	function get_edit_view(){
		global $image_path;

		$view = $this->contents;
		$counter = 0;
		$return_view = '';
		foreach($this->slots as $slot){
			
			$explode = explode($slot[0], $view, 2);
			$view = $explode[1];
			if($this->slot_count($slot[0]) > 0 ){
				$this->cols[] = $counter;
				if($counter < sizeof($this->slots) / 2){
					$return_view .=  $explode[0] . $slot[0]. "<td nowrap bgcolor='#333333' rowspan='2'><a href='#' onclick='add_col_to_view($counter)' >". get_image($image_path."plus_inline","border='0' alt='Add Column ->'")."</a><br><br><a href='#' onclick='delete_col_from_view($counter)' >".get_image($image_path."minus_inline","border='0' alt='<- Delete Column '")."</a></td>";
				}else{
					$return_view .=  $explode[0] . $slot[0]. "";
				}
			}else{
				$return_view .= $explode[0].$slot[0] ;
			}
			$counter++;
		}
		
		return $return_view. $view;
	}
	
	function add_col($file){

		if(substr_count($file, 'ListView') > 0 ){
			
			$this->parse_file($file,'cols');	
			$fp = fopen($file, 'w');
			fwrite($fp, $this->handle_add_col());
			fclose($fp);
			return $file;
		}
		
	}
	
	function handle_add_col(){
		$logger = new SlotLogger();
		$logger->open($this->file);
		$this->get_edit_view();
		require_once('modules/DynamicLayout/LayoutTemplate.php');
		$layoutTemplate = new LayoutTemplate();

		$view = $this->contents;
		$counter = 0;
		$return_view = '';
		$add_col = -1;
		$delete_col = -1;
		if(!empty($_REQUEST['add_col_MSI'])){
			$add_col = $_REQUEST['add_col_MSI'];
			$logger->add_col($add_col);
		}
		if(!empty($_REQUEST['delete_col_MSI'])){
			$delete_col = $_REQUEST['delete_col_MSI'];
			$logger->del_col($delete_col);
		}
		$logger->close();
		for($i = 0; $i < sizeof($this->slots); $i++){
				$slot = $this->slots[$i];
				$explode = explode($slot[0], $view, 2);
				$view = $explode[1];
			if($delete_col != $i){
			
				$return_view .=  $explode[0] . $slot[0];
				
				if($add_col == $i){
					
					if(substr_count($this->file, 'ListView') > 0){
						if($i < sizeof($this->slots) / 2){
							
							$return_view .= 	$layoutTemplate->get_list_view_header();
							$offset = $this->get_key_for_col($i);
							$add_col = $this->cols[$offset + floor(sizeof($this->cols)/2)];
							
						}else{
							
							$return_view .= 	$layoutTemplate->get_list_view_column();	
						}
					}
					
				}
				
			}else{
				if($i < sizeof($this->slots) / 2){
				$offset = $this->get_key_for_col($i);
				$delete_col = $this->cols[$offset + floor(sizeof($this->cols)/2)];	
				
				}
			}
			$counter++;
			
		}
		if(empty($return_view)){
			return $this->contents;
		}	
		return $return_view. $view;
	}
	
	function get_key_for_col($val){
			foreach($this->cols as $k=>$v){
				if($v == $val){
					return $k;	
				}	
			}
	}
	function get_edit_col_view(){
		global $image_path;
		
		$view = $this->contents;
		$counter = 0;
		foreach($this->slots as $slot){

			$explode = explode($slot[0], $view, 2);
			if(substr_count($slot[0], '<slot') > 0){
				$view =  $explode[0] . $slot[1]. "<td nowrap><a href='#' onclick='add_row_to_view($counter)' ><img src='$image_path/edit_inline' style='border:1px solid #ff0000'></a>&nbsp;<a href='#' onclick='delete_row_from_view($counter)' ><img src='$image_path/delete_inline' style='border:1px solid #ff0000'></a></td>". $slot[2]. $slot[3]. $explode[1];
			}else{
				$view = $explode[0].$slot[0] . 	$explode[1];
			}
			$counter++;
		}
		
		return $view;
		
	}
	
	function get_edit_col_script(){
		return <<<EOQ
		<form name='add_col_form'>
			<input type="hidden" name="action" value="index">
			<input type="hidden" name="module" value="DynamicLayout">
			<input type='hidden' id='add_col_MSI' name='add_col_MSI' value='-1'>
			<input type='hidden' id='edit_col_MSI' name='edit_col_MSI' value='1'>
			<input type='hidden' id='delete_col_MSI' name='delete_col_MSI' value='-1'>
		</form>
		<script>
			function delete_col_from_view(i){
				document.getElementById('delete_col_MSI').value = i;
				document.add_col_form.submit();
			}
			function add_col_to_view(i){
				document.getElementById('add_col_MSI').value = i;
				document.add_col_form.submit();
			}
		</script>	
EOQ;

	}
	
	
	
	
	
	
	

	
	
	
	
	
	
	
	

	

	
	
}
?>
	
