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

require_once('modules/DynamicLayout/SlotLogger.php');
class SlotParser{

	var $file;
	var $slots;
	var $rows;
	var $contents;
	var $regex;
	
	var $form;
	var $remove_fields = '';
	var $cols = array();
	var $font_slot = "<font color='red'>-SLOT-</font>";
	
	function SlotParser(){
		global $image_path;
		$this->font_slot = "<img src='$image_path". "slot.gif' alt='Slot' border='0'>";
	}
	
	function parse_slots($str){
		preg_match_all("'<slot[^>]*>(.*?)</slot>'si", $str, $this->slots,PREG_SET_ORDER);
	}
	
	function slot_count($str){
		$result = array();
		 return preg_match_all("'<slot[^>]*>(.*?)</slot>'si", $str, $result);
		  
	}
	function row_count($str){
			$result = array();
			return preg_match_all("'(<tr[^>]*>)(.*?)(</tr[^>]*>)'si", $str, $result);
			
	}
	
	
	function parse_rows($str){
				
				preg_match_all("'(<tr[^>]*>)(.*?)(</tr[^>]*>)'si", $str, $this->slots,PREG_SET_ORDER);
				
	}
	function parse_cols($str){
				preg_match_all("'(<td[^>]*>)(.*?)(</td[^>]*>)'si", $str, $this->slots,PREG_SET_ORDER);
	}
	
	function parse_file($filename, $field='slots' ){
		$this->file = $filename;
		 
		$handle = fopen($filename, "r");
		$this->contents = fread($handle, filesize($filename));
		if($field=='rows')
			$this->parse_rows($this->contents);
		if($field=='cols')
			$this->parse_cols($this->contents);
		if($field=='slots')
			$this->parse_slots($this->contents);
		fclose($handle);
	}
	
	
	
	
	function remove_from_add_fields($field){
		require_once('modules/DynamicLayout/AddField.php');
		$this->remove_fields .= "\n\n". AddField::get_remove_from_add_field($field);
			
	}
	
	function get_remove_field_script(){
		return '<script>' .$this->remove_fields . '</script>';	
	}
	
	function get_edit_view(){
		$view = $this->contents;
		$counter = 0;
		$fields = get_register_values('dyn_layout_fields');
		foreach($this->slots as $slot){

			$explode = explode($slot[0], $view, 2);
			$view =  $explode[0] . "<div id='slot_$counter' style='display:inline;cursor:pointer;cursor:hand;'  onMouseDown=\"swap_div('slot_$counter');\"  onmouseover=\"swap_text(this.innerHTML);\" onmouseout=\"swap_text('&nbsp;')\"> {$this->font_slot}&nbsp;". $slot[1] . "</div>" . $explode[1];
			if($fields){
			foreach($fields as $field=>$field_code){
				
				if(trim($slot[1])== trim($field_code[1]) || preg_match("'name[\ ]*=[\ ]*([\'\"])". $field_code[0] . "\\1'si", $slot[1])){
					$this->remove_from_add_fields($field);	
				} 	
			}
			}
			
			$this->add_to_form($counter);
			$counter++;
		}
		return $view;
	}
	
	function save_layout($file){
		$this->parse_file($file);	
		$fp = fopen($file, 'w');
		fwrite($fp, $this->handle_save_swaps());
		fclose($fp);
		return $file;
	}
	
	function handle_save_swaps(){
		$logger = new SlotLogger();
		$logger->open($this->file);
		require_once('modules/DynamicLayout/DeleteFields.php');
		$df = new DeleteFields();
		$df->get_trash_file($this->file);
		$df->load_deleted_fields();
		$view = $this->contents;
		$counter = 0;
		$return_view = '';
		for($i = 0; $i < sizeof($this->slots); $i++){
			$slot = $this->slots[$i];
			
			$explode = explode($slot[0], $view, 2);
			$explode[0] .= '<slot>';
			$explode[1] = '</slot>' . $explode[1];
			if(!empty($_REQUEST['add_slot_'. $i] )){
				$logger->add_field($i, from_html($_REQUEST['add_slot_'. $i]) );
				$return_view .= $explode[0] . from_html($_REQUEST['add_slot_'. $i]);
			}else if($_REQUEST['form_slot_'. $i] == '-33' || $_REQUEST['form_slot_'. $i] == '-1'){
				//this is a delete row
				$return_view .=  $explode[0] . '&nbsp;';
				$logger->swap_fields($i, $_REQUEST['form_slot_'. $i]);
				$df->delete_field( $this->slots[$i][1]);
			}else {
				if($_REQUEST['form_slot_'. $i] < 0)
					$_REQUEST['form_slot_'. $i] = $i;
				$logger->swap_fields($i, $_REQUEST['form_slot_'. $i]);
				$return_view .=  $explode[0] . $this->slots[$_REQUEST['form_slot_'. $i]][1];
			}
			
			$view = $explode[1];
			$counter++;
			
		}
		$logger->close();
		$df->save_deleted_fields();
		if(empty($return_view))
			return $this->contents;	
		return $return_view. $view;
	}

	
	function add_to_form($counter){
	if(empty($this->form)){
		$this->form = '<form name="layout" id="layout" method="POST" action="index.php" onsubmit="move_deleted_fields_to_form(\'layout\')"> <input type="hidden" name="module" value="DynamicLayout"><input type="hidden" name="action" value="index">';			
		if(isset($_REQUEST['record'])){
			$this->form .= "<input type='hidden' name='record' value='". $_REQUEST['record'] . "'>";
		}
	}	
	$this->form .= "<input type='hidden' id='form_slot_$counter' name='form_slot_$counter' value='$counter'><input type='hidden' id='add_slot_$counter' name='add_slot_$counter' value=''>";
	}
	
	function get_form(){
		return $this->form . '<br><input type="submit" class="button" name="save_layout_MSI" value="Save Layout">&nbsp;<b>('.$this->file . ')</b></form>';	
	}
	
	function get_javascript_swap(){
		return <<<EOQ

				<div class='dataField' id='hotswapcontainter' style='display:none;position:absolute;z-index:10;background-color:#FFFFFF;border:1px solid #ff0000;padding:2px;' ><input type='hidden' id='form_hotswap' name='form_hotswap' value='-1'><input type='hidden' id='add_hotswap' name='add_hotswap' value=''><div id='hotswap' style='display:inline'>{$this->font_slot}&nbsp;</div></div>
				<div id='textcontainter' style='display:none;position:absolute;z-index:11;background-color:#FFFFC2;border:1px solid #222222;font-color:#000000;padding:2px' >&nbsp;</div>
				<script>registerMouseMove(); </script>
EOQ;
		
	}
}
?>
