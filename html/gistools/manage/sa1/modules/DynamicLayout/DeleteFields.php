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


class DeleteFields{
	var $deleted_fields = array(); 
	var $file = '';
	var $quote_cleanup=  array('"' =>'&qt;', "'" =>  '&sqt;', "<"=>"&lt;", ">"=>"&gt;");
	function load_deleted_fields($file=''){
		if(!empty($file)){
			$this->file = $file;	
		}
		if(file_exists($this->file)){
			$fp = fopen($this->file, 'r');
			clearstatcache();
			$text = fread($fp, filesize($this->file) );
			
			$text = str_replace(array("<?PHP\ndie();", "\n?>"), array('',''), $text);
			
			//$text = base64_decode($text);
			$this->deleted_fields = unserialize($text);
			foreach($this->deleted_fields as $del){
				$newDel = $this->quote_restore($del);
				unset($this->deleted_fields[$del]);
				$this->deleted_fields[$newDel] = $newDel;
		}
			fclose($fp);
			
		}
		
	}
	function quote_cleanup($value){
		return str_replace(array_keys($this->quote_cleanup), array_values($this->quote_cleanup), $value);
	}
	function quote_restore($value){
		return str_replace(array_values($this->quote_cleanup), array_keys($this->quote_cleanup), $value);
	}
	
	function delete_fields_for_request($file){
		$this->file = $file;
		$this->load_deleted_fields();
		$deleted = $_REQUEST['delete_fields'];
		foreach($deleted as $del){
				$this->deleted_fields[$del] = $del;
		}
		$this->save_deleted_fields();
	}
	
	function delete_field($field){
			$this->deleted_fields[$field] = $field;
	}
	
	function save_deleted_fields(){
		foreach($this->deleted_fields as $del){
			$newDel = $this->quote_cleanup($del);
			unset($this->deleted_fields[$del]);
			$this->deleted_fields[$newDel] = $newDel;
		}
		$text = serialize($this->deleted_fields);
		//$text = base64_encode($text);
		$fp = fopen($this->file, 'w');
		fwrite($fp, "<?PHP\ndie();".  $text . "\n?>");
		fclose($fp);
		
			
		
	}
	
	function get_trash_file($file){
		require_once('modules/DynamicLayout/DynamicLayoutUtils.php');
		$delete_file = str_replace('.html', '.trash.php', $file);
		$delete_file =  create_cache_directory($delete_file);
		$this->file = $delete_file;
		return $delete_file;
	
	}
		
}

?>
