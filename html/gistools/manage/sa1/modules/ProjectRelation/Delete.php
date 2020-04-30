<?php
/**
 * Delete functionality for a ProjectRelation
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

// $Id: Delete.php,v 1.4 2005/04/21 23:14:36 andrew Exp $

require_once('modules/ProjectRelation/ProjectRelation.php');
require_once('include/logging.php');

$sugarbean =& new ProjectRelation();
$log = LoggerManager::getLogger('ProjectRelation_delete');

// perform the delete if given a record to delete

if(empty($_REQUEST['relation_id']))
{
	$log->info("delete called without a relation id specified");
}
else
{
	$relation_id = $_REQUEST['relation_id'];
	$log->info("deleting relation_id: $relation_id");
	$query = 'UPDATE project_relation'
		. ' SET deleted=1'
		. " WHERE relation_id='$relation_id';";
	$result = $sugarbean->db->query($query, true , 'Error deleting relation: ');
}

// handle the return location variables

$return_module = empty($_REQUEST['return_module']) ? 'Project'
	: $_REQUEST['return_module'];

$return_action = empty($_REQUEST['return_action']) ? 'index'
	: $_REQUEST['return_action'];

$return_id = empty($_REQUEST['return_id']) ? ''
	: $_REQUEST['return_id'];

$return_location = "index.php?module=$return_module&action=$return_action";

// append the return_id if given
if(!empty($return_id))
{
	$return_location .= "&record=$return_id";
}

// now that the delete has been performed, return to given location

header("Location: $return_location");
?>
