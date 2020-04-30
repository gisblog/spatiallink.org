<?PHP
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
global $app_strings;
global $app_list_strings;
global $theme;
require_once('XTemplate/xtpl.php');
$error_msg = '';
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');
global $current_language;
$mod_strings = return_module_language($current_language, 'Accounts');
echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$mod_strings['LBL_SAVE_ACCOUNT'], true);
echo "\n</p>\n";
$xtpl=new XTemplate ('modules/Accounts/ShowDuplicates.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);

$xtpl->assign("MODULE", $_REQUEST['module']);
if ($error_msg != '')
{
	$xtpl->assign("ERROR", $error_msg);
	$xtpl->parse("main.error");
}

require_once('modules/Accounts/Account.php');
$account = new Account();
require_once('modules/Accounts/AccountFormBase.php');
$accountForm = new AccountFormBase();
$GLOBALS['check_notify'] = FALSE;


$query = 'select id, name, website, billing_address_city  from accounts where deleted=0 ';
$duplicates = $_GET['duplicate']; 
$count = count($duplicates);
if ($count > 0)
{
	$query .= "and (";
	$first = true; 
	foreach ($duplicates as $duplicate_id) 
	{
		if (!$first) $query .= ' OR ';
		$first = false;
		$query .= "id='$duplicate_id' ";
	}
	$query .= ');';
}

$duplicateAccounts = array();
require_once('include/database/PearDatabase.php');
$db = new PearDatabase();
$result =& $db->query($query);
if($db->getRowCount($result) != 0){
	for($i = 0; $i < $db->getRowCount($result); $i++){
		$duplicateAccounts[$i] = $db->fetchByAssoc($result, $i);
	}
}			
$xtpl->assign('FORMBODY', $accountForm->buildTableForm($duplicateAccounts,  'Accounts'));

$input = '';
foreach ($account->column_fields as $field)
{	
	if (!empty($_GET['Accounts'.$field])) {
		$input .= "<input type='hidden' name='$field' value='${_GET['Accounts'.$field]}'>\n";
	}
}
foreach ($account->additional_column_fields as $field)
{	
	if (!empty($_GET['Accounts'.$field])) {
		$input .= "<input type='hidden' name='$field' value='${_GET['Accounts'.$field]}'>\n";
	}
}
$get = '';
if(!empty($_GET['return_module'])) $xtpl->assign('RETURN_MODULE', $_GET['return_module']);
else $get .= "Accounts";
$get .= "&return_action=";
if(!empty($_GET['return_action'])) $xtpl->assign('RETURN_ACTION', $_GET['return_action']);
else $get .= "DetailView";
if(!empty($_GET['return_id'])) $xtpl->assign('RETURN_ID', $_GET['return_id']);
$xtpl->assign('INPUT_FIELDS',$input);
$xtpl->parse('main');
$xtpl->out('main');

?>
