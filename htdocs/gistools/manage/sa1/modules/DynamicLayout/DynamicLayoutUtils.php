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


function get_module($file){
		$the_module = '';
		$paths = explode('/' , $file);
	for($i = 0; $i < sizeof($paths) - 1; $i++){
		if($paths[$i] == 'modules'){
			$the_module = $paths[$i + 1];	
	}	
	return $the_module;
}
			
	}
	


function create_custom_directory($file){
			$paths = explode('/',$file);
			$dir = 'custom/layout';
			if(!file_exists($dir))
					mkdir($dir, 0755);
			for($i = 0; $i < sizeof($paths) - 1; $i++){
				$dir .= '/' . $paths[$i];
				if(!file_exists($dir))
					mkdir($dir, 0755);
			}
			return $dir . '/'. $paths[sizeof($paths) - 1];
}
function get_real_file_from_custom($file){
	return str_replace('cache/layout/', '', $file);
}
	
function write_to_cache($file, $view){
			if(!is_writable($file)){
				echo "<br><span style='color:red'>Warning: $file is not writeable. Please make sure it is writeable before continuing</span><br><br>";
			}
			$file_cache = create_cache_directory('layout/'.$file);
			$fp = fopen($file_cache, 'w');
			$view = replace_inputs($view);
			fwrite($fp, $view);
			return get_xtpl_file_and_cache($file);
}

function replace_inputs($str){
		$match = array("'(<input)([^>]*)'si"=>"\$1 disabled readonly $2","'(<select)([^>]*)'si"=>"\$1 disabled readonly $2", "'(href[\ ]*=[\ ]*)([\'])([^\']*)([\'])'si"=>"href=\$2#\$2 alt=\$2\$3\$2", "'(href[\ ]*=[\ ]*)([\"])([^\"]*)([\"])'si"=>"href=\$2#\$2 title=\$2\$3\$2"); 
		return preg_replace(array_keys($match),array_values($match), $str);
}

function populate_request_from_buffer($file){
	$results = array();
	$temp = fopen($file, 'r');
	$buffer = fread($temp, filesize($file));
	fclose($temp);
	preg_match_all("'name[\ ]*=[\ ]*[\']([^\']*)\''si", $buffer, $results);
	$res = $results[1];
	foreach($res as $r){
		$_REQUEST[$r]= $r;	
	}
	preg_match_all("'name[\ ]*=[\ ]*[\"]([^\"]*)\"'si", $buffer, $results);
	$res = $results[1];
	foreach($res as $r){
		$_REQUEST[$r]= $r;	
	}
	
	$_REQUEST['query'] = true;
	$_REQUEST['advanced'] = true;
		
}
function get_xtpl_file_and_cache($file){
		include('modules/DynamicLayout/HTMLPHPMapping.php');
		global $beanList;
		if(!empty($html_php_mapping_subpanel[$file])){
			$xtpl = $html_php_mapping_subpanel[$file];	
		}else if(!empty($html_php_mapping_edit[$file])){
			$xtpl = $html_php_mapping_edit[$file];	
		}else if(!empty($html_php_mapping_detail[$file])){
			$xtpl = $html_php_mapping_detail[$file];	
		}else if(!empty($html_php_mapping_other[$file])){
			$xtpl = $html_php_mapping_other[$file];	
		}else{
			$xtpl = $file;	
		}
		
		$xtpl = str_replace(array('.html', 'SearchForm'), array('.php', 'ListView'), $xtpl);
		$xtpl_fp = fopen($xtpl, 'r');
		$buffer = fread($xtpl_fp, filesize($xtpl));
		fclose($xtpl_fp);
		$cache_file = create_cache_directory('layout/'.$file);
		$xtpl_cache = create_cache_directory('layout/'.$xtpl);
		$module = get_module($file);
		
		$form_string = "require_once('modules/". $module . "/Forms.php');";
		if(substr_count($file,'DetailView') > 0){
			$buffer = str_replace('header(', 'if(false) header(', $buffer);	
		}
		if(substr_count($file,'DetailView') > 0 || substr_count($file,'EditView') > 0){
			if(empty($_REQUEST['record'])){
				$buffer = preg_replace('(\$xtpl[\ ]*=)', "\$focus->assign_display_fields('$module'); \$0", $buffer);
			}else{
				$buffer = preg_replace('(\$xtpl[\ ]*=)', "\$focus->retrieve('".$_REQUEST['record']."');\n\$focus->assign_display_fields('$module');\n \$0", $buffer);
		}
		}
		$_REQUEST['query'] = true;
		if(substr_count($file,'SearchForm') > 0){
			$temp_xtpl = new XTemplate($file);
			if($temp_xtpl->exists('advanced')){
				global $current_language;
				$mods = return_module_language($current_language, 'DynamicLayout');
				$class_name = $beanList[$module];
				if($class_name == 'aCase'){
					$class_file = 'Case';	
				}else{
					$class_file = $class_name;
				}
				
				require_once("modules/$module/$class_file.php");	
				$mod = new $class_name();
				
				populate_request_from_buffer($file);
				$mod->assign_display_fields($module);
				$buffer = str_replace(array('$search_form->parse("advanced");', '$search_form->out("advanced");', '$search_form->parse("main");', '$search_form->out("main");'), array('', '', '', ''), $buffer);
				$buffer = str_replace('echo get_form_footer();', '$search_form->parse("main");'. "\n". '$search_form->out("main");'. "\necho '<br><b>" . translate('LBL_ADVANCED', 'DynamicLayout') ."</b><br>';".  '$search_form->parse("advanced");'. "\n".  '$search_form->out("advanced");' . "\necho get_form_footer();\n \$sugar_config['list_max_entries_per_page'] = 1;", $buffer);
			}
		}
		
		if(!empty($html_php_mapping_subpanel[$file])){
			global $beanList;
			if(!empty($_REQUEST['mod_class'])){
				$bean = $beanList[$_REQUEST['mod_class']];
			}else{
			$bean = $beanList[$module];
			}
			$buffer = str_replace('replace_file_name', $file, $buffer);
		if(empty($_REQUEST['record'])){
			$buffer = str_replace('global $focus_list;',"global \$focus_list;\n\$focus_list = new $bean();\n\$focus_list->assign_display_fields('$module');", $buffer);
		}else{
						$buffer = str_replace('global $focus_list;',"global \$focus_list;\n\$focus_list = new $bean();\n\$focus_list->retrieve('". $_REQUEST['record'] ."');", $buffer);
		}
		}
		
		if(!empty($html_php_mapping_subpanel[$file])){
		foreach($html_php_mapping_subpanel as $key=>$val){
			if($val == $xtpl){
				$buffer = str_replace($key, $cache_file, $buffer);
			}
		}
		}else{
			$buffer = str_replace($file, $cache_file, $buffer);
		}
		$buffer =  "<?PHP\n\$sugar_config['list_max_entries_per_page'] = 1;\n ?>" . $buffer;
		$buffer = str_replace($form_string , '', $buffer); 
		$buffer = replace_inputs($buffer);
		$xtpl_fp_cache = fopen($xtpl_cache, 'w');
		fwrite($xtpl_fp_cache,$buffer);
		fclose($xtpl_fp_cache);
		return $xtpl_cache;
	}
	
?>
