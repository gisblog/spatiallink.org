<?php
/**
 * Forms
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

// $Id: Forms.php,v 1.6 2005/04/20 03:57:27 andrew Exp $

global $theme;
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('XTemplate/xtpl.php');
require_once('include/utils.php');

$log = LoggerManager::getLogger('ProjectTask-Forms');

$image_path = 'themes/'.$theme.'/images/';

function get_new_record_form()
{
	global $app_strings;
	global $mod_strings;
	global $currentModule;
	global $current_user;

	$the_form = get_left_form_header('New Project Task');
	$form = new XTemplate ('modules/ProjectTask/Forms.html');

	$module_select = empty($_REQUEST['module_select']) ? ''
		: $_REQUEST['module_select'];
	$form->assign('mod', $mod_strings);
	$form->assign('app', $app_strings);
	$form->assign('module', $currentModule);
	$options = get_select_options_with_id(get_user_array(), $current_user->id);
	$form->assign('ASSIGNED_USER_OPTIONS', $options);

	$form->parse('main');
	$the_form .= $form->text('main');

   require_once('modules/ProjectTask/ProjectTask.php');
   $focus =& new ProjectTask();

   require_once('include/javascript/javascript.php');
   $javascript = new javascript();
   $javascript->setFormName('quick_save');
   $javascript->setSugarBean($focus);
   $javascript->addRequiredFields('');
   $jscript = $javascript->getScript();

   $the_form .= $jscript . get_left_form_footer();
	return $the_form;
}

/**
 * Create javascript to validate the data entered into a record.
 */
function get_validate_record_js () {
	return '';
}

?>
