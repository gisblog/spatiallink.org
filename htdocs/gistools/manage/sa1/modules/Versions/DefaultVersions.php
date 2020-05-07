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
 * $Header: /var/cvsroot/sugarcrm/modules/Versions/DefaultVersions.php,v 1.6 2005/04/29 08:54:55 majed Exp $
 * Description:
 ********************************************************************************/
$default_versions = array();

require_once('include/database/PearDatabase.php');
$new_db = new PearDatabase();










$db_version = '2.5.1';
$dirName ='custom/include/language'; 
if(is_dir($dirName))
{
	$d = dir($dirName);
	while($entry = $d->read()) {
			 if ($entry != "." && $entry != "..") {
				echo $dirName."/".$entry;
					  if (is_file($dirName."/".$entry) && substr($entry, -9)=='.lang.php') {
					$custom_lang_file = $dirName."/".$entry;
					  
	if(is_readable($custom_lang_file))
	{
		$pattern = '/\$app_list_strings[\ ]*=[\ ]*array/';
		$handle = @fopen($custom_lang_file, 'rt');
		$subject = fread($handle, filesize($custom_lang_file));
		fclose($handle);
		$matches = preg_match($pattern, $subject);
		if($matches > 0)
		{
			$db_version = '0';
		}
	}
	}}}
}
$default_versions[] = array('name'=>'Custom Labels', 'db_version' =>'3.0', 'file_version'=>'3.0');





?>
