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

global $mod_strings, $app_strings;
$importButton ='';
$importForm = '';
if ($_REQUEST['module'] == 'Contacts' ||
	$_REQUEST['module'] == 'Opportunities' ||
	$_REQUEST['module'] == 'Accounts' ||
	$_REQUEST['module'] == 'Leads')
{
$importForm= <<<EOQ
<form name="Import" method="get" action="index.php">
<input type="hidden" name="module" value="{$_REQUEST['module']}">
<input type="hidden" name="action" value="Import">
<input type="hidden" name="step" value="1">
<input type="hidden" name="return_module" value="{$_REQUEST['module']}">
<input type="hidden" name="return_action" value="index">
</form>
EOQ;
$importButton = <<<EOQ
<input title="{$app_strings['LBL_IMPORT']} {$mod_strings['LBL_MODULE_NAME']}" accessKey="" class="button" type="button" name="button" value="  {$app_strings['LBL_IMPORT']} {$mod_strings['LBL_MODULE_NAME']}  " onclick='document.forms["Import"].submit();'  >
EOQ;

}

