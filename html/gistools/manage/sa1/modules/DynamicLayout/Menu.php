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
if(!empty($_REQUEST['select_file_id']) && !empty($_SESSION['dyn_layout_files'])){
	$_SESSION['dyn_layout_file'] = $_SESSION['dyn_layout_files'][$_REQUEST['select_file_id']];
	$_SESSION['dyn_layout_module'] = get_module($_SESSION['dyn_layout_file']);
}
if(!empty($_REQUEST['from_module']) && !empty($_REQUEST['from_action'])){
	$_SESSION['dyn_layout_file'] = 'modules/' . $_REQUEST['from_module'] . '/'. $_REQUEST['from_action'] . '.html';
	$_SESSION['dyn_layout_module'] = get_module($_SESSION['dyn_layout_file']);
}
global $mod_strings;
$module_menu = Array (Array("index.php?action=SelectFile&module=DynamicLayout","Select File","Layout"));
if(!empty($_SESSION['dyn_layout_file']) && $_REQUEST['action'] != 'SelectFile'){
if(substr_count($_SESSION['dyn_layout_file'], 'SearchForm') > 0 || substr_count($_SESSION['dyn_layout_file'], 'EditView') > 0 || substr_count($_SESSION['dyn_layout_file'], 'DetailView') > 0){
  		array_push($module_menu, Array("index.php?action=index&module=DynamicLayout",$mod_strings['LBL_EDIT_LAYOUT'],"EditLayout"));
        array_push($module_menu,Array("index.php?action=index&module=DynamicLayout&edit_row_MSI=1", $mod_strings['LBL_EDIT_ROWS'],"EditLayout"));
        array_push($module_menu,Array("index.php?action=index&module=DynamicLayout&edit_label_MSI=1", $mod_strings['LBL_EDIT_LABELS'],"EditLayout"));
        
}else if(substr_count($_SESSION['dyn_layout_file'], 'ListView') > 0 ){
  		array_push($module_menu, Array("index.php?action=index&module=DynamicLayout",$mod_strings['LBL_EDIT_LAYOUT'],"EditLayout"));
        array_push($module_menu,Array("index.php?action=index&module=DynamicLayout&edit_col_MSI=1", $mod_strings['LBL_EDIT_COLUMNS'],"EditLayout"));
		array_push($module_menu,Array("index.php?action=index&module=DynamicLayout&edit_label_MSI=1", $mod_strings['LBL_EDIT_LABELS'],"EditLayout"));
}

}
?>
