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

// $Id: EditView.php,v 1.3 2005/01/19 00:40:13 andrew Exp $

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_user;

echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ", true);
echo "\n</p>\n";
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$new_dropdown_name = isset($_POST['new_dropdown_name']) ?
                     $_POST['new_dropdown_name'] : '';

if('' != $new_dropdown_name)
{
   if(create_dropdown_type_all_lang($new_dropdown_name))
   {
      echo "<p>Creation of new dropdown type: $new_dropdown_name is successful.</p>";
   }
   else
   {
      echo "<p>Failed to create new dropdown type: $new_dropdown_name.</p>";
   }
}


$xtpl=new XTemplate ('modules/Dropdown/EditView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

$xtpl->parse("main");
$xtpl->out("main");

?>
