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
/*********************************************************************************
 * $Id: ImportStep4.php,v 1.47 2005/04/30 04:24:09 majed Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
ini_set("max_execution_time", "600");

require_once('data/Tracker.php');
require_once('modules/Import/ImportMap.php');
require_once('modules/Import/UsersLastImport.php');
require_once('modules/Import/parse_utils.php');
require_once('include/ListView/ListView.php');
require_once('modules/Import/config.php');
require_once('include/utils.php');

global $mod_strings, $app_list_strings, $app_strings, $current_user, $import_bean_map;
global $import_file_name;
global $theme;

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

function implode_assoc($inner_delim, $outer_delim, $array) 
{
	$output = array();

	foreach( $array as $key => $item )
	{
               $output[] = $key . $inner_delim . $item;
	}

       return implode($outer_delim, $output);
}

$log->info("Upload Step 2");


$delimiter = ',';
//$delimiter = "\t";
// file handle
$count = 0;
$error = "";
$col_pos_to_field = array();
$header_to_field = array();
$field_to_pos = array();
$focus = 0;
$id_exists_count = 0;
$broken_ids = 0;

$has_header = 0;

if ( isset( $_REQUEST['has_header']) && $_REQUEST['has_header'] == 'on')
{
	$has_header = 1;
}

if (isset($import_bean_map[$_REQUEST['module']]))
{
	$currentModule = $_REQUEST['module'];
	$bean = $import_bean_map[$_REQUEST['module']];
	require_once("modules/Import/$bean.php");
	$focus = new $bean();
}
else
{
 echo "Imports aren't set up for this module type\n";
 exit;
}

global $current_language;
$mod_strings = return_module_language($current_language,$currentModule);

// loop through all request variables
foreach ($_REQUEST as $name=>$value)
{
	// only look for var names that start with "colnum"
	if ( strncasecmp( $name, "colnum", 6) != 0 )
	{
		continue;
	}
	if ($value == "-1")
	{
		continue;
	}

	// this value is a user defined field name
	$user_field = $value;

	// pull out the column position for this field name
	$pos = substr($name,6);

	// make sure we haven't seen this field defined yet
	if ( isset( $field_to_pos[$user_field]) )
	{
		show_error_import($mod_strings['LBL_ERROR_MULTIPLE']);
	        exit;

	}


	// match up the "official" field to the user 
	// defined one, and map to columm position: 
	//$translated_column_fields = $mod_list_strings[$list_string_key];

	
	$module_custom_fields_def = $focus->custom_fields->avail_fields;

foreach($module_custom_fields_def  as $name=>$field_def)
{
	if($name != 'id_c')
		$focus->importable_fields[$field_def['name']] = 1;
}

	if ( isset( $focus->importable_fields[$user_field] ) )
	{
		// now mark that we've seen this field
		$field_to_pos[$user_field] = $pos;

		$col_pos_to_field[$pos] = $user_field;
	}
}


// Now parse the file and look for errors
$max_lines = -1;

$ret_value = 0;

if ($_REQUEST['source'] == 'act')
{
        $ret_value = parse_import_act($_REQUEST['tmp_file'],$delimiter,$max_lines,$has_header);
}
else if ($_REQUEST['source'] == 'other_tab')
{
        $ret_value = parse_import_split($_REQUEST['tmp_file'],"\t",$max_lines,$has_header);
}
else
{
	$ret_value = parse_import($_REQUEST['tmp_file'],$delimiter,$max_lines,$has_header);
}

if (file_exists($_REQUEST['tmp_file']))
{
	unlink($_REQUEST['tmp_file']);
}

$rows = $ret_value['rows'];

$ret_field_count = $ret_value['field_count'];

$saved_ids = array();

$firstrow = 0;

if (! isset($rows))
{
	$error = $mod_strings['LBL_FILE_ALREADY_BEEN_OR'];
	$rows = array();
}

if ($has_header == 1)
{
	$firstrow = array_shift($rows);
}


$seedUsersLastImport =& new UsersLastImport();
$seedUsersLastImport->mark_deleted_by_user_id($current_user->id);

$skip_required_count = 0;


$not_imported_str = '';
$fp = fopen ("cache/not_imported_{$bean}.txt",'w');
$firstline = implode("\t",$firstrow);
$first_line_str = "$firstline\n";
fwrite($fp,$first_line_str);

// go thru each row, process and save()
foreach ($rows as $row)
{
	//$count = count($row);
	//$not_imported_str = 'id_exists,'.implode(",",$row)."\n";
	$focus =& new $bean();
	$focus->save_from_post = false;

	$do_save = 1;

	for($field_count = 0; $field_count < $ret_field_count; $field_count++)
	{

		if ( isset( $col_pos_to_field[$field_count]) )
		{
			if (! isset( $row[$field_count]) )
			{
				continue;
			}

			// TODO: add check for user input
			// addslashes, striptags, etc..
			$field = $col_pos_to_field[$field_count];
			$focus->$field = $row[$field_count];

		}

	}

	
	// if the id was specified	
	if ( isset( $focus->id ) )
	{
		$focus->id = convert_id($focus->id);

		// check if it already exists
		$check_bean =& new $bean();

		$query = "select * from {$check_bean->table_name} WHERE id='{$focus->id}'";

                $log->info($query);

                $result = mysqli_query($varconnect, $query)
                       or sugar_die("Error selecting sugarbean: ".mysqli_error($varconnect));

		$dbrow = $check_bean->db->fetchByAssoc($result);

		if (isset($dbrow['id']) && $dbrow['id'] != -1)
		{
			// if it exists but was deleted, just remove it
			if ( isset($dbrow['deleted']) && $dbrow['deleted'] == 1)
			{
				$query2 = "delete from {$check_bean->table_name} WHERE id='{$focus->id}'";

                		$log->info($query2);

                		$result2 = mysqli_query($varconnect, $query2)
                       			or sugar_die("Error deleting existing sugarbean: ".mysqli_error($varconnect));
			
			}
			else
			{
				$id_exists_count++;
				$do_save = 0;
				$badline = implode("\t",$row);
				$not_imported_str = "$badline\n";
				fwrite($fp,$not_imported_str);
				continue;
			}
		}

		// check if the id is too long
		else if ( strlen($focus->id) > 36)
		{
			$broken_ids++;
			$do_save = 0;
			$badline = implode("\t",$row);
			$not_imported_str = "$badline\n";
			fwrite($fp,$not_imported_str);
			continue;
			
		}

		if ($do_save != 0)
		{
			// set the flag to force an insert
			$focus->new_with_id = true;
		}
	}

	// now do any special processing
	$focus->process_special_fields();
	$no_required = 0;
	foreach ($focus->required_fields as $field=>$notused) 
	{ 
		if (! isset($focus->$field) || $focus->$field == '') 
		{ 
			$do_save = 0; 
			$skip_required_count++; 
			$badline = implode("\t",$row);
			$not_imported_str = "$badline\n";
			fwrite($fp,$not_imported_str);
			$no_required = 1;
			break; 
		} 
	}	

	if ( $no_required == 1)
	{
		continue;
	}


	if ($do_save)
	{
		if ( ! isset($focus->assigned_user_id) || $focus->assigned_user_id=='')
		{
			$focus->assigned_user_id = $current_user->id;
		}	
		if ( ! isset($focus->modified_user_id) || $focus->modified_user_id=='')
		{
			$focus->modified_user_id = $current_user->id;
		}	







		$focus->save();	
		$last_import =& new UsersLastImport();
		$last_import->assigned_user_id = $current_user->id;
		$last_import->bean_type = $_REQUEST['module'];
		$last_import->bean_id = $focus->id;
		$last_import->save();
		array_push($saved_ids,$focus->id);
		$count++;
	}
	

}

// SAVE MAPPING IF REQUESTED
if ( isset($_REQUEST['save_map']) && $_REQUEST['save_map'] == 'on'
	&& isset($_REQUEST['save_map_as']) && $_REQUEST['save_map_as'] != '')
{
	$serialized_mapping = '';

	if( $has_header)
	{


		foreach($col_pos_to_field as $pos=>$field_name)
		{
	
			if ( isset($firstrow[$pos]) &&  isset( $field_name))
			{
				$header_to_field[ $firstrow[$pos] ] = $field_name;
			}
		}

		$serialized_mapping = implode_assoc("=","&",$header_to_field);
	}
	else
	{
		$serialized_mapping = implode_assoc("=","&",$col_pos_to_field);
	}

	$mapping_file_name = $_REQUEST['save_map_as'];


	$mapping_file =& new ImportMap();

	$query_arr = array('assigned_user_id'=>$current_user->id,'name'=>$mapping_file_name);

	
	$mapping_file->retrieve_by_string_fields($query_arr, false);

	$result = $mapping_file->save_map( $current_user->id,
					$mapping_file_name,
					$_REQUEST['module'],
					$_REQUEST['source'],
					$has_header,
					$serialized_mapping );
}


$mod_strings = return_module_language($current_language,"Import");
$currentModule = "Import";
fclose($fp);

if ($error != "")
{
	show_error_import( $mod_strings['LBL_ERROR']." ". $error);
	exit;
}
else 
{
	$message= urlencode($mod_strings['LBL_SUCCESS']."<BR><b>$count</b>  ".$mod_strings['LBL_SUCCESSFULLY']."<br><b>".($broken_ids+$id_exists_count) ."</b> ". $mod_strings['LBL_IDS_EXISTED_OR_LONGER']. "<br><b>$skip_required_count</b> " .  $mod_strings['LBL_RECORDS_SKIPPED'] );
	if ( empty($_REQUEST['return_action']))
	{
		$_REQUEST['return_action'] = 'index';
	}

	header("Location: index.php?module={$_REQUEST['module']}&action=Import&step=last&return_module={$_REQUEST['return_module']}&return_action={$_REQUEST['return_action']}&message=$message");
exit;
}


?>
