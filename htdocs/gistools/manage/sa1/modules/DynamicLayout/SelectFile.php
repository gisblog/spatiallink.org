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

if(!is_admin($current_user)){

	sugar_die('Only admins may edit layouts');
}

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

$base_path = 'modules';
$blocked_modules = array('iFrames', 'Dropdown', 'Feeds');
	$can_display = array('EditView.html' => 1, 'DetailView.html'=> 1, 'ListView.html'=> 1);
$display_files = array();
function get_display_files($path){
	global $display_files, $can_display, $blocked_modules;

	$d = dir($path);
	while($entry = $d->read()){	
		if($entry != '..' && $entry != '.'){
			if(is_dir($path. '/'. $entry)){
				get_display_files($path. '/'. $entry);	
			}else{
				if(key_exists($entry, $can_display)){
					$can_add = true;
					foreach($blocked_modules as $mod){
						if(substr_count($path, $mod) > 0){
							$can_add = false;	
						}	
					}
					if($can_add){
					$display_files[create_guid()] = $path. '/'. $entry;
					}
				}	
			}
		}
	}

}

if(!isset($_SESSION['dyn_layout_files'])){
	get_display_files($base_path);
	asort($display_files);
	reset($display_files);
	$_SESSION['dyn_layout_files'] = $display_files;
}else{
	$display_files = $_SESSION['dyn_layout_files'];
}
echo <<<EOQ
<form method='post' action='index.php'>
<input type='hidden' name='action' value='index'>
<input type='hidden' name='module' value='DynamicLayout'>
<select name='select_file_id'>
EOQ;
echo get_select_options_with_id($display_files,'');
echo '</select>';
echo '<input type="submit" class="button" name="Submit" value="Select File"></form>';
$edit_in_place = '';
if(!empty($_SESSION['editinplace'])){
	$edit_in_place = 'checked';	
}
echo <<<EOQ
<form name='edit' method='post' action='index.php'>
<input type='hidden' name='action' value='index'>
<input type='hidden' name='module' value='DynamicLayout'>
<input type='hidden' name='in_place' value='true'>
Edit In Place:<input type="checkbox" name="edit_in_place" class="checkbox" onChange='document.edit.submit();' value="Edit In Place" $edit_in_place>
</form>
EOQ;

// How To Use Layout Text Block Instructions

$slot_image = "<img src='$image_path". "slot.gif' alt='Slot' border='0'>";

echo "<br>";
echo get_form_header($mod_strings['DESC_USING_LAYOUT_TITLE'],'',false);
$using_layout_shortcuts = $mod_strings['DESC_USING_LAYOUT_SHORTCUTS'];
$using_layout_toolbar = $mod_strings['DESC_USING_LAYOUT_TOOLBAR'];
$using_layout_select_file = $mod_strings['DESC_USING_LAYOUT_SELECT_FILE'];
$using_layout_edit_fields = $mod_strings['DESC_USING_LAYOUT_EDIT_FIELDS'];
$using_layout_edit_rows = $mod_strings['DESC_USING_LAYOUT_EDIT_ROWS'];
$using_layout_add_field = $mod_strings['DESC_USING_LAYOUT_ADD_FIELD'];
$using_layout_remove_item = $mod_strings['DESC_USING_LAYOUT_REMOVE_ITEM'];
$using_layout_display_html = $mod_strings['DESC_USING_LAYOUT_DISPLAY_HTML'];
$using_layout_blk1 = $mod_strings['DESC_USING_LAYOUT_BLK1'];
$using_layout_blk2 = $mod_strings['DESC_USING_LAYOUT_BLK2'];
$using_layout_blk3 = $mod_strings['DESC_USING_LAYOUT_BLK3'];
$using_layout_blk4 = $mod_strings['DESC_USING_LAYOUT_BLK4'];
$using_layout_blk5 = $mod_strings['DESC_USING_LAYOUT_BLK5'];
$using_layout_blk6 = $mod_strings['DESC_USING_LAYOUT_BLK6'];
$using_layout_blk7 = $mod_strings['DESC_USING_LAYOUT_BLK7'];
$using_layout_blk8 = $mod_strings['DESC_USING_LAYOUT_BLK8'];
$using_layout_blk9 = $mod_strings['DESC_USING_LAYOUT_BLK9'];
$using_layout_blk10 = $mod_strings['DESC_USING_LAYOUT_BLK10'];
echo "<br>";
echo $using_layout_blk1, "<br><br>";
echo "<b>",$using_layout_shortcuts,"</b><br>";
echo "<u>", $using_layout_select_file, "</u>", $using_layout_blk2, "<br>";
echo "<u>", $using_layout_edit_fields, "</u>",$using_layout_blk3, $slot_image, $using_layout_blk4, "<br>";
echo "<u>", $using_layout_edit_rows, "</u>",$using_layout_blk5, "<br><br>";
echo "<b>",$using_layout_toolbar,"</b><br>";
echo $using_layout_blk6, "<br>";
echo "<u>", $using_layout_add_field, "</u>",$using_layout_blk7, "<br>";
echo "<u>", $using_layout_remove_item, "</u>",$using_layout_blk8, "<br>";
echo "<u>", $using_layout_display_html, "</u>",$using_layout_blk9, "<br><br>";
echo $using_layout_blk10;

?>
