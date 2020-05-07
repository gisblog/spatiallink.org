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

class AddField{
	var $div='';
	var $script ='';
	var $html = array();
	var $counter = 0;
	var $slot = '-SLOT-';
	var $font_slot = "<font color='red'>-SLOT-</font>";
	function AddField(){
		
		
	
	}
	
	function get_remove_from_add_field($field){
		static $tables = array('sugar_fields_MSI','sugar_fields_MSI'); 
		$ret = '';
		foreach($tables as $table){
		$ret .= <<<EOQ
			remove_field_row_from_table('$field', '$table');
EOQ;
		}
		return $ret;
	}
	

	
	function quote_cleanup($value){
		$quote_cleanup=  array('"' =>'&qt;', "'" =>  '&sqt;',"\r\n"=>'', "\n"=>'', '</script>'=>'&lt;/script&gt;');
		return str_replace(array_keys($quote_cleanup), array_values($quote_cleanup), $value);
	}
	
	function add_deleted_field($html_code, $prefix=''){
			$count = get_register_value('dyn_layout', 'field_counter');
			if(!$count){
				$count = 0;	
			}
			$div_id = 'dyn_field_' .$count;
			set_register_value('dyn_layout_fields', $div_id, array($div_id, $html_code));
			$html_code = $this->quote_cleanup($html_code);
			$script = "\n \n". $prefix. "add_new_field_row_to_table('$div_id', '$html_code', 'sugar_trash_MSI');";
			$count += 1;
			set_register_value('dyn_layout', 'field_counter', $count);
			$this->script .= $script;
			return $script;
	}
	
	function add_field_no_label($name, $html_code, $prefix='', $table=''){
			if(empty($table)){
				$table = 'field_table_MSI';	
			}
			$count = get_register_value('dyn_layout', 'field_counter');
			if(!$count){
				$count = 0;	
			}
			$div_id = 'dyn_field_' .$count;
			set_register_value('dyn_layout_fields', $div_id, array($name, $html_code));
			$html_code = $this->quote_cleanup($html_code);
			$script = "\n \n". $prefix. "add_new_field_row_to_table('$div_id', '$html_code', '$table');";
			$count += 1;
			set_register_value('dyn_layout', 'field_counter', $count);
			$this->script .= $script;
			return $script;
			

	}
	
	function add_field($name, $html_code, $html_label, $prefix='', $table=''){
		
			if(empty($table)){
				$table = 'field_table_MSI';	
			}
				
			$count = get_register_value('dyn_layout', 'field_counter');
			if(empty($count)){
				$count = 0;	
			}
			$div_id = 'dyn_field_' .$count;
			$counter1 = $count + 1;
			$div_label_id = 'dyn_field_' . $counter1;
			set_register_value('dyn_layout_fields', $div_id, array($name, $html_code));
			set_register_value('dyn_layout_fields', $div_label_id, array($name.'_label', $html_label));
			$html_code = $this->quote_cleanup($html_code);
			$html_label = $this->quote_cleanup($html_label);
			$script = "\n \n". $prefix. "add_new_field_row_to_table('$div_id', '$html_code', '$table');";
			$script .= "\n". $prefix. "add_new_field_row_to_table('$div_label_id', '$html_label', '$table');";
			$count += 2;
			set_register_value('dyn_layout', 'field_counter', $count);
			$this->script .= $script;
			return $script;
			/*$this->html .= <<<EOQ
			
			<tr>
				<td nowrap><div id='slot_{$div_label_id}' style='display:inline;cursor:pointer;cursor:hand;border:1px solid #ff0000;'  onclick="swap_div('$div_label_id');"><input type='text' name='add_$div_label_id'  id='add_$div_label_id' value='$html_code'><input type='hidden' id='form_$div_label_id' name='form_$div_label_id' value='-66'><div id='$div_label_id' style='display:inline'>$html_label</div></div></td>
			</tr>
			<tr>	
				<td nowrap><div id='slot_{$div_id}' style='display:inline;cursor:pointer;cursor:hand;border:1px solid #ff0000;'  onclick="swap_div('$div_id');"><input type='text' name='add_$div_id' id='add_$div_id' value='$html_code'><input type='hidden' name='form_$div_id' id='form_$div_id' value='-66'><div id='$div_id' style='display:inline'>$html_code</div></div></td>
			</tr>
EOQ;
$this->counter += 2;
*/

	}
	
	function get_html(){
		global $image_path;
		global $image_path;
		$add_icon = get_image($image_path."plus_inline",'style="margin-left:4px;margin-right:4px;" alt="Maximize" border="0" align="absmiddle"');
		$min_icon = get_image($image_path."minus_inline",'style="margin-left:4px;margin-right:4px;" alt="Minimize" border="0" align="absmiddle"');
		return '<a href="#" onclick="delete_div()" class="leftColumnModuleS3Link"><table class="contentBox" cellpadding="0" cellspacing="0" border="0" width="100%" id="field_table_MSI"><tr><td>Staging Area (drag and drop items here)</td></tr></table></a>' 
				. '<div id="s_fields_MSIlink"><a href="#" onclick="toggleDisplay(\'s_fields_MSI\');">'. $add_icon . ' View Sugar Fields</a></div><div id="s_fields_MSI" style="display:none"><table class="contentBox" cellpadding="0" cellspacing="0" border="0" width="100%" id="sugar_fields_MSI"><tr><td><a href="#" onclick="toggleDisplay(\'s_fields_MSI\');">' .$min_icon .'</a>Sugar Fields (click items to add to staging area)</td></tr></table></div>'
				. '<br><div id="s_trash_MSIlink"><a href="#" onclick="toggleDisplay(\'s_trash_MSI\');">'. $add_icon . ' View Sugar Bin</a></div><div id="s_trash_MSI" style="display:none"><table class="contentBox" cellpadding="0" cellspacing="0" border="0" width="100%" id="sugar_trash_MSI"><tr><td><a href="#" onclick="toggleDisplay(\'s_trash_MSI\');">' .$min_icon .'</a>Sugar Bin (click items to add to staging area)</td></tr></table></div>';	
	}
	
	function get_script($prefix=''){
		$count = get_register_value('dyn_layout', 'field_counter');
		if(empty($count)){
			$count = 0;	
		}
		return '<script>' . $this->script . "\n$prefix". "field_count_MSI+=$count;" . '</script>';
	}
	
	
}
?>
