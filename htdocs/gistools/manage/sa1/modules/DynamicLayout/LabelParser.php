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


class LabelParser {

	var $file;
	var $slots = array();
	var $rows;
	var $contents;
	var $regex;
	
	var $form;
	var $remove_fields = '';
	var $cols = array();
	var $font_slot = "<font color='red'>-SLOT-</font>";
	
	function LabelParser(){
		global $image_path;
		$this->font_slot = "<img src='$image_path". "slot.gif' alt='Slot' border='0'>";
	}
	
	function parse_slots($str){
		preg_match_all("'\{[\ ]*MOD\.([a-zA-Z0-9\_]*)[\ ]*\}'si", $str, $this->slots,PREG_SET_ORDER);
	}
	
	function slot_count($str){
		$result = array();
		 preg_match_all("'MOD\.[a-zA-Z0-9\_]*'si", $str, $result);
		  
	}
	
	function parse_file($filename, $field='slots' ){
		$this->file = $filename;
		 
		$handle = fopen($filename, "r");
		$this->contents = fread($handle, filesize($filename));
		$this->parse_slots($this->contents);
		fclose($handle);
	}
	
	
	
	function get_edit_view(){
		global $image_path;
		$view = $this->contents;
		$counter = 0;
		$module_name = $_SESSION['dyn_layout_module'];
		foreach($this->slots as $slot){
			$explode = explode($slot[0], $view, 2);
			$view =  $explode[0] . "<a href='#' onclick='window.frames[\"labeleditor\"].document.location=\"index.php?module=LabelEditor&action=EditView&style=popup&sugar_body_only=1&refresh_parent=1&module_name=$module_name&record=". $slot[1] ."\"'>".get_image($image_path.'edit_inline','border="0" align="absmiddle"')."</a>&nbsp;<a href='#' onclick='window.frames[\"labeleditor\"].document.location=\"index.php?module=LabelEditor&action=EditView&style=popup&sugar_body_only=1&refresh_parent=1&module_name=$module_name&record=". $slot[1] ."\"'>". $slot[0] . "</a>" . $explode[1];
			$counter++;
		}
		return $view;
	}
	

}
?>
