<?php
/**
 * Side-bar menu for Project
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

// $Id: Menu.php,v 1.11.2.1 2005/05/09 22:29:10 ajay Exp $
global $mod_strings;
global $current_user;
$module_menu = array();

// Each index of module_menu must be an array of:
// the link url, display text for the link, and the icon name.

$current_module = empty($_REQUEST['module']) ? 'Home' : $_REQUEST['module'];
$current_action = empty($_REQUEST['action']) ? 'index' : $_REQUEST['action'];

$module_menu[] = array("index.php?module=Project&action=EditView&return_module=Project&return_action=DetailView",
	$mod_strings['LNK_NEW_PROJECT'], 'CreateProject');
$module_menu[] = array('index.php?module=Project&action=index',
	$mod_strings['LNK_PROJECT_LIST'], 'Project');
$module_menu[] = array("index.php?module=ProjectTask&action=EditView&return_module=ProjectTask&return_action=DetailView",
	$mod_strings['LNK_NEW_PROJECT_TASK'], 'CreateProjectTask');
$module_menu[] = array('index.php?module=ProjectTask&action=index',
	$mod_strings['LNK_PROJECT_TASK_LIST'], 'ProjectTask');

?>
