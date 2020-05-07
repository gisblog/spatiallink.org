<?php
/**
 * The popup window for displaying the details of a custom field
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

// $Id: Forms.php,v 1.7 2005/04/20 02:19:42 majed Exp $

global $theme;
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('XTemplate/xtpl.php');
require_once('include/utils.php');

$log = LoggerManager::getLogger('EditCustomFields-Forms');

$image_path = 'themes/'.$theme.'/images/';

function get_new_record_form()
{
	global $app_strings;
	global $mod_strings;
	global $currentModule;

	$the_form = '';
	if(!empty($_REQUEST['module_name']))
	{
		$the_form =  get_left_form_header($mod_strings['LBL_ADD_FIELD']."&nbsp;". $_REQUEST['module_name']);
		global $app_list_strings;
		$before_form = ob_get_contents();
		ob_end_clean();
		ob_start();
		include('modules/EditCustomFields/EditView.php');
		$the_form .= ob_get_contents();
		ob_end_clean();
		ob_start();
		echo $before_form;
		$the_form .=  get_left_form_footer();
	}
	return $the_form;
}
?>
