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
 * $Id: Save.php,v 1.27 2005/03/17 01:28:44 majed Exp $
 * Description:  Saves an Account record and then redirects the browser to the
 * defined return URL.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('modules/Tasks/Task.php');
require_once('include/logging.php');

$local_log =& LoggerManager::getLogger('index');

$focus =& new Task();

require_once('include/TimeDate.php');
$timedate =& new TimeDate();
if(!empty($_POST[$prefix.'meridiem'])){
	$_POST[$prefix.'time_due'] = $timedate->merge_time_meridiem($_POST[$prefix.'time_due'],$timedate->get_time_format(true), $_POST[$prefix.'meridiem']);	
	$_POST[$prefix.'time_start'] = $timedate->merge_time_meridiem($_POST[$prefix.'time_start'],$timedate->get_time_format(true), $_POST[$prefix.'meridiem']);	
}
require_once('include/formbase.php');
$focus = populateFromPost('', $focus);

if (!isset($_POST['date_due_flag'])) $focus->date_due_flag = 'off';
if (!isset($_POST['date_start_flag'])) $focus->date_start_flag = 'off';

$focus->save($GLOBALS['check_notify']);
$return_id = $focus->id;

handleRedirect($return_id,'Tasks');



?>
