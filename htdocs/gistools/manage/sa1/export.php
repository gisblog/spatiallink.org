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

if (substr(phpversion(), 0, 1) == "5") {
        ini_set("zend.ze1_compatibility_mode", "1");
}

include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('modules/Users/User.php');
require_once('include/modules.php');
require_once('include/utils.php');

// check for old config format.
if(empty($sugar_config) && isset($dbconfig['db_host_name']))
{
   make_sugar_config($sugar_config);
}

if (!empty($sugar_config['session_dir'])) {
	session_save_path($sugar_config['session_dir']);
}

session_start();

$user_unique_key = (isset($_SESSION['unique_key'])) ? $_SESSION['unique_key'] : "";
$server_unique_key = (isset($sugar_config['unique_key'])) ? $sugar_config['unique_key'] : "";

if ($user_unique_key != $server_unique_key) {
	session_destroy();
	header("Location: index.php?action=Login&module=Users");
	exit();
}

if(!isset($_SESSION['authenticated_user_id']))
{
	// TODO change this to a translated string.
	session_destroy();
	die("An active session is required to export content");
}

$current_user = new User();

$result = $current_user->retrieve($_SESSION['authenticated_user_id']);
if($result == null)
{
	session_destroy();
	die("An active session is required to export content");
}

$contact_fields = array(
"id"=>"Contact ID"
,"lead_source"=>"Lead Source"
,"date_entered"=>"Date Entered"
,"date_modified"=>"Date Modified"
,"first_name"=>"First Name"
,"last_name"=>"Last Name"
,"salutation"=>"Salutation"
,"birthdate"=>"Lead Source"
,"do_not_call"=>"Do Not Call"
,"email_opt_out"=>"Email Opt Out"
,"title"=>"Title"
,"department"=>"Department"
,"birthdate"=>"Birthdate"
,"do_not_call"=>"Do Not Call"
,"phone_home"=>"Phone (Home)"
,"phone_mobile"=>"Phone (Mobile)"
,"phone_work"=>"Phone (Work)"
,"phone_other"=>"Phone (Other)"
,"phone_fax"=>"Fax"
,"email1"=>"Email"
,"email2"=>"Email (Other)"
,"assistant"=>"Assistant"
,"assistant_phone"=>"Assistant Phone"
,"primary_address_street"=>"Primary Address Street"
,"primary_address_city"=>"Primary Address City"
,"primary_address_state"=>"Primary Address State"
,"primary_address_postalcode"=>"Primary Address Postalcode"
,"primary_address_country"=>"Primary Address Country"
,"alt_address_street"=>"Other Address Street"
,"alt_address_city"=>"Other Address City"
,"alt_address_state"=>"Other Address State"
,"alt_address_postalcode"=>"Other Address Postalcode"
,"alt_address_country"=>"Other Address Country"
,"description"=>"Description"
);


$account_fields = array(
"id"=>"Account ID",
"name"=>"Account Name",
"website"=>"Website",
"industry"=>"Industry",
"account_type"=>"Type",
"ticker_symbol"=>"Ticker Symbol",
"employees"=>"Employees",
"ownership"=>"Ownership",
"phone_office"=>"Phone",
"phone_fax"=>"Fax",
"phone_alternate"=>"Other Phone",
"email1"=>"Email",
"email2"=>"Other Email",
"rating"=>"Rating",
"sic_code"=>"SIC Code",
"annual_revenue"=>"Annual Revenue",
"billing_address_street"=>"Billing Address Street",
"billing_address_city"=>"Billing Address City",
"billing_address_state"=>"Billing Address State",
"billing_address_postalcode"=>"Billing Address Postalcode",
"billing_address_country"=>"Billing Address Country",
"shipping_address_street"=>"Shipping Address Street",
"shipping_address_city"=>"Shipping Address City",
"shipping_address_state"=>"Shipping Address State",
"shipping_address_postalcode"=>"Shipping Address Postalcode",
"shipping_address_country"=>"Shipping Address Country",
"description"=>"Description"
);




function export_all($type)
{
	global $beanList, $beanFiles;
	global $contact_fields;
	global $account_fields;
	$focus = 0;
	$content = '';

	$bean = $beanList[$type];
	require_once($beanFiles[$bean]);
	$focus = new $bean;

	$log = LoggerManager::getLogger('export_'.$type);
	$db = new PearDatabase();

	if ( isset($_REQUEST['all']) )
	{
		$where = '';
	}
	else
	{
		$where = $_SESSION['export_where'];
	}

	$order_by = "";

	$query = $focus->create_export_query($order_by,$where);

	$result = $db->query($query,true,"Error exporting $type: "."<BR>$query");

	$fields_array = $db->getFieldsArray($result);

	$header = implode("\",\"",array_values($fields_array));
	$header = "\"" .$header;
	$header .= "\"\r\n";
	$content .= $header;

	$column_list = implode(",",array_values($fields_array));

	while($val = $db->fetchByAssoc($result,-1,false))
	{
		$new_arr = array();

		foreach (array_values($val) as $value)
		{
			array_push($new_arr, preg_replace("/\"/","\"\"",$value));
		}

		$line = implode("\",\"",$new_arr);
		$line = "\"" .$line;
		$line .= "\"\r\n";

		$content .= $line;
	}
	return $content;

}

$content = export_all(clean_string($_REQUEST['module']));

header("Pragma: cache");
header("Content-Disposition: inline; filename={$_REQUEST['module']}.csv");
header("Content-Type: text/csv; charset=UTF-8");
header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
header( "Cache-Control: post-check=0, pre-check=0", false );
header("Content-Length: ".strlen($content));
print $content;
exit;
?>
