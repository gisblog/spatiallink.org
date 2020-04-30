<?php
/**
 * The popup window for displaying the details of a custom field
 *
 * PHP version 4
 *
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
 */

// $Id: EditView.php,v 1.4 2005/04/20 02:08:30 majed Exp $

global $theme;
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('XTemplate/xtpl.php');
require_once('include/utils.php');
require_once('modules/EditCustomFields/EditCustomFields.php');

global $app_strings;
global $mod_strings;
global $currentModule;


$image_path = 'themes/'.$theme.'/images/';

///////////////////////////////////////
// Populate the template
///////////////////////////////////////
$style = 'embeded';
if(isset($_REQUEST['style'])){
	$style = $_REQUEST['style'];	
}
$xtpl = new XTemplate ('modules/EditCustomFields/EditView.html');
$xtpl->assign('MOD', $mod_strings);
$xtpl->assign('APP', $app_strings);

$focus =& new FieldsMetaData();
$data_type_array = array('varchar' => 'Text', 'text' => 'Text Area','int' => 'Integer', 'float' => 'Decimal',
	 'bool' => 'Checkbox', 'date' => 'Date', 'enum' => 'Dropdown');
$enum_keys = array();
foreach($app_list_strings as $key => $value)
{
  	if(is_array($value)){
  		$enum_keys[$key] = $key;	
  	}
  		
}
if(!empty($_REQUEST['file_type'])){
	$xtpl->assign('FILE_TYPE', $_REQUEST['file_type']);
}
if(!empty($_REQUEST['field_count'])){
	$xtpl->assign('FIELD_COUNT', $_REQUEST['field_count']);
}

$return_module = 'EditCustomFields';
$return_action = 'index';
if(isset($_REQUEST['module_name'])){
	$return_module = $_REQUEST['module_name'];	
}
if(isset($_REQUEST['module_action'])){
	$return_action = $_REQUEST['module_action'];	
}
$xtpl->assign('RETURN_MODULE', $return_module);
$xtpl->assign('RETURN_ACTION', $return_action);
$xtpl->assign('STYLE', $style);
if(empty($_REQUEST['record']))
{
	$xtpl->assign('form', 'insert');
	$header = get_form_header($mod_strings['POPUP_INSERT_HEADER_TITLE'], '', false);
	$xtpl->assign('header', $header);
	$xtpl->assign('custom_module', $_REQUEST['module_name']);
	
   $data_type_options_html = get_select_options_with_id($data_type_array,
		'');
	$xtpl->assign('data_type_options', $data_type_options_html);
	$xtpl ->assign('ENUM_OPTIONS', get_select_options_with_id($enum_keys, ''));
}
else
{
	$xtpl->assign('form', 'edit');
	$header = get_form_header($mod_strings['POPUP_EDIT_HEADER_TITLE'], '', false);
	$xtpl->assign('header', $header);
	// populate the fields if a custom_field_id is given -> editing
	$record_id = $_REQUEST['record'];

	$focus->retrieve($record_id);
	if(!empty($_REQUEST['duplicate'])){
		$record_id = '';
		$focus->id = '';	
	}
	$xtpl->assign('NOEDIT', 'disabled');
	$xtpl->assign('custom_field_id', $focus->id);
	$xtpl->assign('name', $focus->name);
	$xtpl->assign('label', $focus->label);
	$xtpl->assign('custom_module', $focus->custom_module);
	
   $data_type_options_html = get_select_options_with_id($data_type_array,
		$focus->data_type);
	
	$xtpl->assign('data_type_options', $data_type_options_html);
	$xtpl->assign('max_size', $focus->max_size);
	$xtpl->assign('required_option', $focus->required_option);
	if($focus->required_option == 'required'){
		$xtpl->assign('REQUIRED_CHECKED', 'checked');
	}
	$xtpl->assign('default_value', $focus->default_value);
	
	$xtpl ->assign('ENUM_OPTIONS', get_select_options_with_id($enum_keys, $focus->ext1));
	$xtpl->assign('ext1', $focus->ext1);
	$xtpl->assign('ext2', $focus->ext2);
	$xtpl->assign('ext3', $focus->ext3);
}

$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign('module', $currentModule);
$action = basename(__FILE__, '.php');
$xtpl->assign('action', $action);

///////////////////////////////////////
// Start the output
///////////////////////////////////////
if($style == 'popup'){
	insert_popup_header($theme);
	$xtpl->parse("popup");
	$xtpl->out("popup");
	$xtpl->parse("body.topsave");
	$xtpl->parse("body.cancel");
}else{
	$xtpl->parse("embeded");
	$xtpl->out("embeded");
	if(!empty($record_id)){

		$xtpl->parse("body.cancel");
		$xtpl->parse("body.topsave");
	}else{
		$xtpl->parse("body.botsave");	
	}
}
$xtpl->parse("body");
$xtpl->out("body");

// Reset the sections that are already in the page so that they do not print again later.
$xtpl->reset("main");

echo get_form_footer();
if($style == 'popup'){
	insert_popup_footer();
}
require_once('include/javascript/javascript.php');
$javascript =& new javascript();
$javascript->setFormName('popup_form');
$javascript->setSugarBean($focus);
$javascript->addAllFields('');
echo $javascript->getScript();
?>
