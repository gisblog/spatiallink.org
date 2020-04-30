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

require_once('modules/Roles/Role.php');
require_once('include/utils.php');
require_once('include/logging.php');

$local_log =& LoggerManager::getLogger('index');

$focus =& new Role();

$tabs_def = urldecode($_REQUEST['display_tabs_def']);
$tabs_hide = urldecode($_REQUEST['hide_tabs_def']);

$allow_modules = explode(':::', $tabs_def);
$disallow_modules = explode(':::', $tabs_hide);

$focus->retrieve($_POST['record']);
print_r($_POST);
unset($_POST['id']);


foreach($focus->column_fields as $field)
{
	if(isset($_POST[$field]))
	{
		$value = $_POST[$field];
		$focus->$field = $value;

	}
}


$check_notify = FALSE;

$focus->save($check_notify);
$return_id = $focus->id;

$focus->clear_module_relationship($return_id);
$focus->set_module_relationship($return_id, $allow_modules, 1);
$focus->set_module_relationship($return_id, $disallow_modules, 0);



if(isset($_POST['return_module']) && $_POST['return_module'] != "") $return_module = $_POST['return_module'];
else $return_module = "Roles";
if(isset($_POST['return_action']) && $_POST['return_action'] != "") $return_action = $_POST['return_action'];
else $return_action = "DetailView";
if(isset($_POST['return_id']) && $_POST['return_id'] != "") $return_id = $_POST['return_id'];

	$local_log->debug("Saved record with id of ".$return_id);
	header("Location: index.php?action=$return_action&module=$return_module&record=$return_id");


?>
