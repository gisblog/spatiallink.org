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
 * $Id: Save.php,v 1.2 2005/04/27 00:26:52 nate Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('modules/Employees/Employee.php');
require_once('include/logging.php');
require_once('modules/MySettings/TabController.php');


$log =& LoggerManager::getLogger('employee');
$tabs_def = urldecode($_REQUEST['display_tabs_def']);
$DISPLAY_ARR = array();
parse_str($tabs_def,$DISPLAY_ARR); 


$focus =& new Employee();
$focus->retrieve($_POST['record']);

	foreach($focus->column_fields as $field)
	{
		if(isset($_POST[$field]))
		{
			$value = $_POST[$field];
			$focus->$field = $value;

		}
	}

	foreach($focus->additional_column_fields as $field)
	{
		if(isset($_POST[$field]))
		{
			$value = $_POST[$field];
			$focus->$field = $value;

		}
	}

	if (!$focus->verify_data()) 
	{
		header("Location: index.php?action=Error&module=Users&error_string=".urlencode($focus->error_string));
		exit;
	}
	else 
	{
		$focus->save();
		$return_id = $focus->id;








	}

	
if(isset($_POST['return_module']) && $_POST['return_module'] != "") $return_module = $_POST['return_module'];
else $return_module = "Users";
if(isset($_POST['return_action']) && $_POST['return_action'] != "") $return_action = $_POST['return_action'];
else $return_action = "DetailView";
if(isset($_POST['return_id']) && $_POST['return_id'] != "") $return_id = $_POST['return_id'];

$log->debug("Saved record with id of ".$return_id);

header("Location: index.php?action=$return_action&module=$return_module&record=$return_id");
?>