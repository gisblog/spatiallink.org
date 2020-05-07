<?php
/**
 * Upgrade the mod_strings format
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

// $Id: language_upgrade.php,v 1.6 2005/04/29 00:32:34 majed Exp $

require_once('include/utils/array_utils.php');
require_once('modules/Versions/Version.php');
require_once('modules/Versions/ExpectedVersions.php');

$i = 0;
$keyArray = array();
$base_dir = ".";
$start_dir = ".";

function getDirList ($dirName)
{
	global $i;
	$d = dir($dirName);
	while($entry = $d->read())
	{
		if ($entry != "." && $entry != "..")
		{
			if (is_dir($dirName."/".$entry))
			{
				getDirList($dirName."/".$entry);
			}
			elseif(substr($entry, -9) == '.lang.php')
			{
				include($dirName."/".$entry);
				if(isset($mod_strings))
				{
					update_override($dirName . '/'.$entry, $mod_strings,
						'mod_strings');
				}
			}
		}
	}
	$d->close();
}

getDirList($base_dir. '/'.$start_dir);

function update_override($entry, $strings, $array_name)
{
	global $start_dir, $base_dir;
	$new_buffer = '';
	$override = 'custom'. substr($entry, 1);

	if(file_exists($override))
	{
		echo 'Updating Format:<br>';
		include($override);
		if(isset($$array_name))
		{
			$new_strings = & $$array_name;
			if(is_array($strings) && is_array($new_strings))
			{
				foreach($strings as $name=>$value)
				{
					if(isset($new_strings[$name]))
					{
						//only if they aren't the same do we write
						if($new_strings[$name] != $strings[$name])
						{
							echo "Updating: $name<br>";
							echo "Your Value:" . $new_strings[$name] . '<br>';
							echo "Original Value:" . $strings[$name] . '<br>';
							$keep =  override_value_to_string($array_name, $name,
								$new_strings[$name]) . "\n";
							$new_buffer .= $keep;
							echo $keep . '<br><br>';
						}
					}	
				}

				foreach($new_strings as $name=>$value)
				{
					if(!isset($strings[$name]))
					{
						//only if they aren't the same do we write
						echo "Adding Custom Array: $name<br>";
						$keep =  override_value_to_string($array_name, $name,
							$new_strings[$name]) . "\n";
						$new_buffer .= $keep;
						echo $keep . '<br><br>';
					}	
				}
			}	
		}
	}

	if(!empty($new_buffer))
	{
		echo 'Writing new custom file:<br>';
		$fp = fopen($override, 'w');
		fwrite($fp, "<?php\n". $new_buffer . "?>");	
	}
}

if(isset($expect_versions['Custom Labels']))
{
	echo $mod_strings['LBL_UPGRADE_VERSION'] . ': DB Version - '
		. $expect_versions['Custom Labels']['db_version']. '<br>';
	$version = new Version();
	$version->retrieve_by_string_fields(array('name'=>$expect_versions['Custom Labels']['name']));
	$version->name =  $expect_versions['Custom Labels']['name'];
	$version->db_version =  $expect_versions['Custom Labels']['db_version'];
	$version->file_version =  $expect_versions['Custom Labels']['file_version'];
	$version->save();

	if(isset($_SESSION['invalid_versions']['Custom Labels']))
	{
		unset($_SESSION['invalid_versions']['Custom Labels']);
	}
}

echo "\n--- DONE ---<br />\n";
?>
