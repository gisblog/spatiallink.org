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

require_once('modules/DynamicLayout/DynamicLayoutUtils.php');
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";


error_reporting(0);



//check for edit in place setting 
if(!empty($_REQUEST['in_place'])){
	if(!empty($_REQUEST['edit_in_place'])){
			$_SESSION['editinplace'] = true;
						
	}else{
		unset($_SESSION['editinplace']);	
	}	
	header('Location: index.php?action=index&module=Home');
}

//MAKE SURE A FILE IS SELECTED
require_once('modules/DynamicLayout/HTMLPHPMapping.php');
if(!empty($_SESSION['dyn_layout_file'])){
	$file = $_SESSION['dyn_layout_file'];
	$the_module = get_module($file);
	$fileType = '';

		if(substr_count($file, 'EditView') > 0 || isset($html_php_mapping_edit[$file])){
			$fileType = 'edit';
		}else if(substr_count($file, 'DetailView') > 0 || isset($html_php_mapping_detail[$file])){
			$fileType = 'detail';
		}else if(substr_count($file, 'ListView') > 0 || isset($html_php_mapping_subpanel[$file])){
			$fileType = 'list';
		}else if(substr_count($file, 'SearchForm') > 0 || isset($html_php_mapping_searchform[$file])){
			$fileType = 'search';
		}else  if(isset($html_php_mapping_other[$file])){
			$fileType = 'other';
		}

	//HANDLE ANY DELETED FIELDS
	require_once('modules/DynamicLayout/DeleteFields.php');
	$deleteFields = new DeleteFields();
	$deleteFields->get_trash_file($file);




	//create the slot parser 
	
	
	require_once('modules/DynamicLayout/SlotParser.php');
	$sp = new SlotParser();
	
	
	//if the last request was a save lets do that
	if(!empty($_REQUEST['save_layout_MSI'])){
		$file = $sp->save_layout($file);
	}
	
	//if we should be editing rows files lets do that
	if(!empty($_REQUEST['edit_row_MSI'])){
		require_once('modules/DynamicLayout/RowSlotParser.php');
		$rp = new RowSlotParser();
		if(!empty($_REQUEST['add_row_MSI'])){
			$rp->add_row($file);
			header('Location: index.php?action=index&module=DynamicLayout&edit_row_MSI=1');
		}
		
		$rp->parse_file($file,'rows');
		echo $rp->get_edit_row_script();
		$view =$rp->get_edit_view();
		$prev_mod = $mod_strings;
		$mod_strings = return_module_language($current_language, $the_module);
		$xtpl = write_to_cache($file, $view);
		include_once($xtpl);
		$mod_strings = $prev_mod;
		
	//else if we should be editing columns (listview) lets get that done
	}else if(!empty($_REQUEST['edit_col_MSI'])){
		require_once('modules/DynamicLayout/ColSlotParser.php');
		$cp = new ColSlotParser();
		if(!empty($_REQUEST['add_col_MSI'])){
			$cp->add_col($file);
			header('Location: index.php?action=index&module=DynamicLayout&edit_col_MSI=1');
		}
		$cp->parse_file($file,'cols');
		echo $cp->get_edit_col_script();
		$view =  $cp->get_edit_view();
		$prev_mod = $mod_strings;
		if(!empty($_REQUEST['mod_lang'])){
			$mod_strings = return_module_language($current_language, $_REQUEST['mod_lang']);
		}else{
			$mod_strings = return_module_language($current_language, $the_module);	
		}
		$xtpl = write_to_cache($file, $view);
		include_once($xtpl);
		
		$mod_strings = $prev_mod;

	}else if(!empty($_REQUEST['edit_label_MSI'])){
		require_once('modules/DynamicLayout/LabelParser.php');
		$cp =& new LabelParser();
		$cp->parse_file($file,'cols');
		$view =  $cp->get_edit_view();
		$prev_mod = $mod_strings;
		if(!empty($_REQUEST['mod_lang'])){
			$mod_strings = return_module_language($current_language, $_REQUEST['mod_lang']);
		}else{
			$mod_strings = return_module_language($current_language, $the_module);	
		}
		$xtpl = write_to_cache($file, $view);
		include_once($xtpl);
		
		$mod_strings = $prev_mod;
	}else{
		//otherwise we must be editing field layout \
		require_once('modules/DynamicLayout/AddField.php');
		$addfield = new AddField();
		
		if($fileType != 'other'){
		require_once('modules/DynamicFields/DynamicField.php');
		if(!empty($_REQUEST['mod_class'])){
			$temp_mod = $_REQUEST['mod_class'];
			$class_name = $beanList[$temp_mod];
			$class_file = $class_name;
			require_once("modules/$temp_mod/$class_file.php");
			$customFields = new DynamicField($temp_mod);
		}else{
			$class_name = $beanList[$the_module];
			if($class_name == 'aCase'){
				$class_file = 'Case';	
			}else{
				$class_file = $class_name;
			}
			require_once("modules/$the_module/$class_file.php");
			$customFields = new DynamicField($the_module);	
		}
		
		$mod =& new $class_name();
		
		
		
		
		$customFields->setup($mod);
		$result = $customFields->getAllBeanFieldsView($fileType, 'html');
		foreach($result as $f_name=>$f_field){
			$addfield->add_field($f_name, $f_field['html'], $f_field['label'], '', 'sugar_fields_MSI');	
		}

		
		
		
		}

		$deleteFields->load_deleted_fields();

		foreach($deleteFields->deleted_fields as $dl){

			$addfield->add_deleted_field($dl);
		}
		
	
		$sp->parse_file($file);
		echo $sp->get_javascript_swap();
		//$view = str_replace('<!-- END: main -->',  $addfield->get_script()."\n<!-- END: main -->", $sp->get_edit_view() );
		
			echo $addfield->get_script();
		
		$view = $sp->get_edit_view();
		$slotCount = sizeof($sp->slots);
		
		echo "<script> setModuleName('$the_module'); setFileType('$fileType'); setSlotCount($slotCount); </script>";

		echo $sp->get_form();
		$prev_mod = $mod_strings;

		$mod_strings = return_module_language($current_language, $the_module);
		
		$xtpl = write_to_cache($file, $view);
		$old_buffer = ob_get_contents();
		ob_flush();
		ob_end_clean();
		ob_start();
		include_once($xtpl);
		$include_xtpl = ob_get_contents();
		ob_end_clean();
		ob_start();
		
		if($fileType == 'list'){
			global $list_view_row_count;
		 	if($list_view_row_count == 0){
		 		include_once(get_real_file_from_custom($xtpl));
		 		echo '<br><font color="red">' . translate('NO_RECORDS_LISTVIEW', 'DynamicLayout') . '</font>'	;
			 }else{
			 	echo $include_xtpl;		
			 }
		}else{
			echo $include_xtpl;	
		}
		$mod_strings = $prev_mod;
		echo $sp->get_remove_field_script();
	}
}else{
	include_once('modules/DynamicLayout/SelectFile.php');
}

?>
