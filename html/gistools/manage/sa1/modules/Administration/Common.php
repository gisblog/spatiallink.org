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

// $Id: Common.php,v 1.21 2005/04/18 08:02:42 majed Exp $

require_once('include/utils.php');
require_once('include/utils/array_utils.php');

/**
 * @return bool
 * @desc Creates the include language directory under the custom directory.
 */
function create_include_lang_dir()
{
   $continue = true;

   if(!is_dir('custom/include'))
   {
      $continue = mkdir('custom/include');
   }

   if($continue)
   {
      if(!is_dir("custom/include/language"))
      {
         $continue = mkdir("custom/include/language");
      }
   }

   return $continue;
}

/**
 * @return bool
 * @param module string
 * @desc Creates the module's language directory under the custom directory.
 */
function create_module_lang_dir($module)
{
   $continue = true;

   if(!is_dir('custom/modules'))
   {
      $continue = mkdir('custom/modules');
   }

   if($continue)
   {
      if(!is_dir("custom/modules/$module"))
      {
         $continue = mkdir("custom/modules/$module");
      }
   }

   if($continue)
   {
      if(!is_dir("custom/modules/$module/language"))
      {
         $continue = mkdir("custom/modules/$module/language");
      }
   }

   return $continue;
}

/**
 * @return string&
 * @param the_array array, language string, module string
 * @desc Returns the contents of the customized language pack.
 */
function &create_field_lang_pak_contents($old_contents, $key, $value, $language, $module)
{
	if(!empty($old_contents))
	{ 
		
		$old_contents = preg_replace("'[^\[\n\r]+\[\'{$key}\'\][^\;]+;[\ \r\n]*'i", '', $old_contents);
		$contents = str_replace("\n?>","\n\$mod_strings['{$key}'] = '$value';\n?>", $old_contents);
		
	
	}
	else
	{
   	$contents = "<?php\n"
			. '// Creation date: ' . date('Y-m-d H:i:s') . "\n"
			. "// Module: $module\n"
			. "// Language: $language\n\n"
			. "\$mod_strings['$key'] = '$value';"
			. "\n?>";
	}

   return $contents;
}

/**
 * @return string&
 * @param the_array array, language string
 * @desc Returns the contents of the customized language pack.
 */
function &create_dropdown_lang_pak_contents(&$the_array, $language)
{
   $contents = "<?php\n" .
               '// ' . date('Y-m-d H:i:s') . "\n" .
               "// Language: $language\n\n" .
               '$app_list_strings = ' .
               var_export($the_array, true) .
               ";\n?>";

   return $contents;
}

/**
 * @return bool
 * @param module string, key string, value string
 * @desc Wrapper function that will create a field label for every language.
 */
function create_field_label_all_lang($module, $key, $value, $overwrite = false)
{
   $languages = get_languages();
   $return_value = false;

   foreach($languages as $lang_key => $lang_value)
   {
      $return_value = create_field_label($module, $lang_key, $key, $value, $overwrite);
      if(!$return_value)
      {
         break;
      }
   }

   return $return_value;
}

/**
 * @return bool
 * @param module string, language string, key string, value string
 * @desc Returns true if new field label can be created, false otherwise.
 *       Probable reason for returning false: new_field_key already exists.
 */
function create_field_label($module, $language, $key, $value, $overwrite=false)
{
   global $log;
   $return_value = false;
   $mod_strings = return_module_language($language, $module);

   if(isset($mod_strings[$key]) && !$overwrite)
   {
      $log->info("Tried to create a key that already exists: $key");
   }
   else
   {
      $mod_strings = array_merge($mod_strings, array($key => $value));
      $dirname = "custom/modules/$module/language";
      $dir_exists = is_dir($dirname);

      if(!$dir_exists)
      {
      	
         $dir_exists = create_module_lang_dir($module);
      }

      if($dir_exists)
      {
         $filename = "$dirname/$language.lang.php";
         	if(is_file($filename)){
        	 	$handle = fopen($filename, 'rb');
				$old_contents = fread($handle, filesize($filename));
				fclose($handle);
         	}else{
         		$old_contents = '';	
         	}
			$handle = fopen($filename, 'wb');
         

         if($handle)
         {
            $contents = &create_field_lang_pak_contents($old_contents, $key,
					$value, $language, $module);

            if(fwrite($handle, $contents))
            {
               $return_value = true;
               $log->info("Successful write to: $filename");
            }

            fclose($handle);
         }
         else
         {
            $log->info("Unable to write edited language pak to file: $filename");
         }
      }
      else
      {
          $log->info("Unable to create dir: $dirname");
      }
   }

   return $return_value;
}

/**
 * @return bool
 * @param dropdown_name string
 * @desc Wrapper function that creates a dropdown type for all languages.
 */
function create_dropdown_type_all_lang($dropdown_name)
{
   $languages = get_languages();
   $return_value = false;

   foreach($languages as $lang_key => $lang_value)
   {
      $return_value = create_dropdown_type($dropdown_name, $lang_key);
      if(!$return_value)
      {
         break;
      }
   }

   return $return_value;
}

/**
 * @return bool
 * @param app_list_strings array
 * @desc Saves the app_list_strings to file in the 'custom' dir.
 */
function save_custom_app_list_strings_contents(&$contents, $language)
{
	global $log;
	$return_value = false;
   $dirname = 'custom/include/language';
   $dir_exists = is_dir($dirname);

   if(!$dir_exists)
   {
      $dir_exists = create_include_lang_dir($dirname);
   }

   if($dir_exists)
   {
      $filename = "$dirname/$language.lang.php";
      $handle = @fopen($filename, 'wt');

      if($handle)
      {
         if(fwrite($handle, $contents))
         {
            $return_value = true;
            $log->info("Successful write to: $filename");
         }

         fclose($handle);
      }
      else
      {
         $log->info("Unable to write edited language pak to file: $filename");
      }
   }
   else
   {
      $log->info("Unable to create dir: $dirname");
   }

	return $return_value;
}

/**
 * @return bool
 * @param app_list_strings array
 * @desc Saves the app_list_strings to file in the 'custom' dir.
 */
function save_custom_app_list_strings(&$app_list_strings, $language)
{
	global $log;
	$return_value = false;
   $dirname = 'custom/include/language';
   $dir_exists = is_dir($dirname);

   if(!$dir_exists)
   {
      $dir_exists = create_include_lang_dir($dirname);
   }

   if($dir_exists)
   {
      $filename = "$dirname/$language.lang.php";
      $handle = @fopen($filename, 'wt');

      if($handle)
      {
         $contents = &create_dropdown_lang_pak_contents($app_list_strings,
         					$language);

         if(fwrite($handle, $contents))
         {
            $return_value = true;
            $log->info("Successful write to: $filename");
         }

         fclose($handle);
      }
      else
      {
         $log->info("Unable to write edited language pak to file: $filename");
      }
   }
   else
   {
      $log->info("Unable to create dir: $dirname");
   }

	return $return_value;
}

function return_custom_app_list_strings_file_contents($language)
{
	$contents = '';
	
	$filename = "custom/include/language/$language.lang.php";
	$handle = @fopen($filename, 'rt');

	if($handle)
	{
		$contents = fread($handle, filesize($filename));
		fclose($handle);
	}

	return $contents;
}

/**
 * @return bool
 * @param dropdown_name string, language string
 * @desc Creates a new dropdown type.
 */
function create_dropdown_type($dropdown_name, $language)
{
   global $log;
   $return_value = false;
   $app_list_strings = return_app_list_strings_language($language);

   if(isset($app_list_strings[$dropdown_name]))
   {
      $log->info("Tried to create a dropdown list key that already exists: $dropdown_name");
   }
   else
   {
		// get the contents of the custom app list strings file
		$contents = return_custom_app_list_strings_file_contents($language);

		// add the new dropdown_name to it
		if($contents == '')
		{
			$new_contents = "<?php\n\$app_list_strings['$dropdown_name'] = array(''=>'');\n?>";
		}
		else
		{
			$new_contents = str_replace('?>', "\$app_list_strings['$dropdown_name'] = array(''=>'');\n?>", $contents);
		}

		// save the new contents to file
		$return_value = save_custom_app_list_strings_contents($new_contents, $language);
   }

   return $return_value;
}

/**
 * @return string&
 * @param identifier string, pairs array, first_entry string, selected_key string
 * @desc Generates the HTML for a dropdown list.
 */
function &create_dropdown_html($identifier, &$pairs, $first_entry='', $selected_key='')
{
   $html = "<select name=\"$identifier\">\n";

   if('' != $first_entry)
   {
      $html .= "<option name=\"\">$first_entry</option>\n";
   }

   foreach($pairs as $key => $value)
   {
      $html .= $selected_key == $key ?
               "<option name=\"$key\" selected=\"selected\">$value</option>\n" :
               "<option name=\"$key\">$value</option>\n";
   }

   $html .= "</select>\n";

   return $html;
}

function dropdown_item_delete($dropdown_type, $language, $index)
{
	$app_list_strings_to_edit = return_app_list_strings_language($language);
   $dropdown_array = &$app_list_strings_to_edit[$dropdown_type];
	helper_dropdown_item_delete($dropdown_array, $index);

	$contents = return_custom_app_list_strings_file_contents($language);
	$new_contents = replace_or_add_dropdown_type($dropdown_type, $dropdown_array,
		$contents);

   save_custom_app_list_strings_contents($new_contents, $language);
}

function helper_dropdown_item_delete(&$dropdown_array, $index)
{
   // perform the delete from the array
	$sliced_off_array = array_splice($dropdown_array, $index);
   array_shift($sliced_off_array);
	$dropdown_array = array_merge($dropdown_array, $sliced_off_array);
}

function dropdown_item_move_up($dropdown_type, $language, $index)
{
	$app_list_strings_to_edit = return_app_list_strings_language($language);
	$dropdown_array = &$app_list_strings_to_edit[$dropdown_type];

	if($index > 0 && $index < count($dropdown_array))
	{
		$key = '';
		$value = '';
		$i = 0;

		reset($dropdown_array);
		while(list($k, $v) = each($dropdown_array))
		{
			if($i == $index)
			{
				$key = $k;
				$value = $v;
				break;
			}

			$i++;
		}

		helper_dropdown_item_delete($dropdown_array, $index);
		helper_dropdown_item_insert($dropdown_array, $index - 1, $key, $value);

		// get the contents of the custom app list strings file
		$contents = return_custom_app_list_strings_file_contents($language);
		$new_contents = replace_or_add_dropdown_type($dropdown_type,
			$dropdown_array, $contents);

		save_custom_app_list_strings_contents($new_contents, $language);
	}
}

function dropdown_item_move_down($dropdown_type, $language, $index)
{
	$app_list_strings_to_edit = return_app_list_strings_language($language);
	$dropdown_array = &$app_list_strings_to_edit[$dropdown_type];

	if($index >= 0 && $index < count($dropdown_array) - 1)
	{
		$key = '';
		$value = '';
		$i = 0;

		reset($dropdown_array);
		while(list($k, $v) = each($dropdown_array))
		{
			if($i == $index)
			{
				$key = $k;
				$value = $v;
				break;
			}

			$i++;
		}

		helper_dropdown_item_delete($dropdown_array, $index);
		helper_dropdown_item_insert($dropdown_array, $index + 1, $key, $value);

		// get the contents of the custom app list strings file
		$contents = return_custom_app_list_strings_file_contents($language);
		$new_contents = replace_or_add_dropdown_type($dropdown_type,
			$dropdown_array, $contents);

		save_custom_app_list_strings_contents($new_contents, $language);
	}
}

function dropdown_item_insert($dropdown_type, $language, $index, $key, $value)
{
	$app_list_strings_to_edit = return_app_list_strings_language($language);
	$dropdown_array = &$app_list_strings_to_edit[$dropdown_type];
	helper_dropdown_item_insert($dropdown_array, $index, $key, $value);

	// get the contents of the custom app list strings file
	$contents = return_custom_app_list_strings_file_contents($language);
	$new_contents = replace_or_add_dropdown_type($dropdown_type,
		$dropdown_array, $contents);

   save_custom_app_list_strings_contents($new_contents, $language);
}

function helper_dropdown_item_insert(&$dropdown_array, $index, $key, $value)
{
	$pair = array($key => $value);
	if($index <= 0)
	{
		$dropdown_array = array_merge($pair, $dropdown_array);
	}
	if($index >= count($dropdown_array))
	{
		$dropdown_array = array_merge($dropdown_array, $pair);
	}
	else
	{
		$sliced_off_array = array_splice($dropdown_array, $index);
		$dropdown_array = array_merge($dropdown_array, $pair);
		$dropdown_array = array_merge($dropdown_array, $sliced_off_array);
	}
}

function dropdown_item_edit($dropdown_type, $language, $key, $value)
{
	$app_list_strings_to_edit = return_app_list_strings_language($language);
	$dropdown_array = &$app_list_strings_to_edit[$dropdown_type];

	$dropdown_array[$key] = $value;

	$contents = return_custom_app_list_strings_file_contents($language);

	// get the contents of the custom app list strings file
	$new_contents = replace_or_add_dropdown_type($dropdown_type,
		$dropdown_array, $contents);

   save_custom_app_list_strings_contents($new_contents, $language);
}

function replace_or_add_dropdown_type($dropdown_type, &$dropdown_array,
   &$file_contents)
{
	$new_contents = "<?php\n?>";
	$new_entry = override_value_to_string('app_list_strings',
		$dropdown_type, $dropdown_array);

	if(empty($file_contents))
	{
		// empty file, must create the php tags
   	$new_contents = "<?php\n$new_entry\n?>";
	}
	else
	{
		// existing file, try to replace
		$new_contents = replace_dropdown_type($dropdown_type,
			$dropdown_array, $file_contents);

		if($new_contents == $file_contents)
		{
			// replace failed, append to end of file
			$new_contents = str_replace('?>', "$new_entry\n?>", $file_contents);
		}
	}

	return $new_contents;
}

function replace_dropdown_type($dropdown_type, &$dropdown_array,
	&$file_contents)
{
	$new_contents = $file_contents;

	if(!empty($dropdown_type) &&
		is_array($dropdown_array) &&
		!empty($file_contents))
	{
		$pattern = '/\$app_list_strings\[\''. $dropdown_type .
			'\'\][\ ]*=[\ ]*array[\ ]*\([^\)]*\)[\ ]*;/';
		$replacement = override_value_to_string('app_list_strings',
			$dropdown_type, $dropdown_array);
		$new_contents = preg_replace($pattern, $replacement, $file_contents, 1);
	}

	return $new_contents;
}

?>
