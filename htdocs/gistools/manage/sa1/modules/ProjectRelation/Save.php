<?php
/**
 * Save functionality for ProjectRelation
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

// $Id: Save.php,v 1.1 2005/04/18 22:11:19 andrew Exp $

require_once('modules/ProjectRelation/ProjectRelation.php');

$sugarbean =& new ProjectRelation();

////
//// save the fields to the ProjectRelation object
////

foreach($sugarbean->column_fields as $field)
{
	if(isset($_REQUEST[$field]))
	{
		$sugarbean->$field = $_REQUEST[$field];
	}
}

$sugarbean->save();

if(isset($_REQUEST['form']))
{
	// we are doing the save from a popup window
	echo '<script>opener.window.location.reload();self.close();</script>';
	die();
}
else
{
	// need to refresh the page properly

	$return_module = empty($_REQUEST['return_module']) ? 'Project'
		: $_REQUEST['return_module'];

	$return_action = empty($_REQUEST['return_action']) ? 'index'
		: $_REQUEST['return_action'];

	$return_id = empty($_REQUEST['return_id']) ? ''
		: $_REQUEST['return_id'];

	header("Location: index.php?module=$return_module&action=$return_action&record=$return_id");

}
?>
