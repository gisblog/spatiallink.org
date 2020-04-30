<?php
/**
 * Data access layer for the project_relation table
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

// $Id: ProjectRelation.php,v 1.2 2005/04/18 22:19:42 majed Exp $


require_once('data/SugarBean.php');
require_once('include/database/PearDatabase.php');
require_once('include/utils.php');
include_once('config.php');
require_once('include/logging.php');

class ProjectRelation extends SugarBean {
	// database table columns
	var $id;
	var $project_id;
	var $relation_id;
	var $relation_type;
	var $deleted;
	var $new_schema = true;
	var $table_name = 'project_relation';
	var $object_name = 'ProjectRelation';
	var $module_dir = 'ProjectRelation';

	var $column_fields = array(
		'id',
		'project_id',
		'relation_id',
		'relation_type',
		'deleted',
	);

	var $list_fields = array(
		'id',
		'project_id',
		'relation_id',
		'relation_type',
		'deleted',
	);

	//var $field_name_map;
	var $field_defs = array();

	//////////////////////////////////////////////////////////////////
	// METHODS
	//////////////////////////////////////////////////////////////////

	function ProjectRelation()
	{
		parent::SugarBean();
	}
	
}
?>
