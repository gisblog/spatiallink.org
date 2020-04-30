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

class RowSlotParser extends SlotParser{
	
	
function RowSlotParser(){
		global $image_path;
		$this->font_slot = "<img src='$image_path" . "slot.gif' alt='Slot' border='0'>";
}


function get_edit_view(){
		global $image_path;
		
		$view = $this->contents;
		$counter = 0;
		foreach($this->slots as $slot){

			$explode = explode($slot[0], $view, 2);
			if($this->slot_count($slot[0]) > 0 && $this->row_count($slot[2]. '</tr>') == 0){
				$view =  $explode[0] . $slot[1]. "<td class='tabDetailViewDF' nowrap><a href='#' onclick='add_row_to_view($counter)' >".get_image($image_path."plus_inline","border='0' alt='Add Row'")."</a>&nbsp;<a href='#' onclick='delete_row_from_view($counter)' >".get_image($image_path."minus_inline","border='0' alt='Remove row'")."</a></td>". $slot[2]. $slot[3]. $explode[1];
			}else{
				$view = $explode[0].$slot[0] . 	$explode[1];
			}
			$counter++;
		}
		
		return $view;
		
	}
	
	function get_edit_row_script(){
		return  <<<EOQ
		<form name='add_row_form'>
			<input type="hidden" name="action" value="index">
			<input type="hidden" name="module" value="DynamicLayout">
			<input type='hidden' id='add_row_MSI' name='add_row_MSI' value='-1'>
			<input type='hidden' id='edit_row_MSI' name='edit_row_MSI' value='1'>
			<input type='hidden' id='delete_row_MSI' name='delete_row_MSI' value='-1'>
		</form>
		<script>
		function delete_row_from_view(i){
				document.getElementById('delete_row_MSI').value = i;
				document.add_row_form.submit();
			}
		function add_row_to_view(i){
				document.getElementById('add_row_MSI').value = i;
				document.add_row_form.submit();
		}
		</script>
		
EOQ;
	
	}
	
	
	
	
	
	function add_row($file){
		
		if(substr_count($file, 'EditView') > 0 || substr_count($file, 'DetailView') > 0 || substr_count($file, 'SearchForm') > 0){
			
			$this->parse_file($file,'rows');	
			$fp = fopen($file, 'w');
			fwrite($fp, $this->handle_add_row());
			fclose($fp);
			return $file;
		}
		
	}
	
	function handle_add_row(){
		$logger = new SlotLogger();
		$logger->open($this->file);
		require_once('modules/DynamicLayout/LayoutTemplate.php');
		$layoutTemplate = new LayoutTemplate();
		
		$view = $this->contents;
		$counter = 0;
		$return_view = '';
		$add_row = -1;
		$delete_row = -1;
		if(!empty($_REQUEST['add_row_MSI'])){
			$add_row = $_REQUEST['add_row_MSI'];
			$logger->add_row($add_row);
		}
		if(!empty($_REQUEST['delete_row_MSI'])){
			$delete_row = $_REQUEST['delete_row_MSI'];
			$logger->del_row($delete_row);
		}
		$logger->close();
		for($i = 0; $i < sizeof($this->slots); $i++){

				
				$slot = $this->slots[$i];
				$explode = explode($slot[0], $view, 2);
				
				if($delete_row != $i){
					$return_view .=  $explode[0] . $slot[0];
				if($add_row == $i){
					if(substr_count($this->file, 'EditView') > 0 || substr_count($this->file, 'SearchForm') > 0){
						$return_view .= 	$layoutTemplate->get_edit_source_row();
					}
					if(substr_count($this->file, 'DetailView') > 0){
						$return_view .= 	$layoutTemplate->get_detail_source_row();
					}
				}
			}else{
				$return_view .=  $explode[0];
			}
				$view = $explode[1];
			
			$counter++;
			
		}
		if(empty($return_view)){
			return $this->contents;
		}	
		return $return_view. $view;
	}	
	
}
?>
