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
 * $Id: phprint.php,v 1.23 2005/02/09 07:08:54 andrew Exp $
 * Description: Main file and starting point for the application.  Calls the
 * theme header and footer files defined for the user as well as the module as
 * defined by the input parameters.
 ********************************************************************************/

$query_string = "";

foreach ($_GET as $key => $val) {
	if ($key != "print") {
		if (is_array($val)) {
			foreach ($val as $k => $v) {
				$query_string .= "{$key}[{$k}]=" . urlencode($v) . "&";
			}
		}
		else {
			$query_string .= "{$key}=" . urlencode($val) . "&";
		}
	}
}

$url = "{$_SERVER['PHP_SELF']}?{$query_string}";

?>
<html>
<head>
<style type="text/css" media="all">
BODY { font-family: Arial, Helvetica, sans-serif; }
</style>
</head>
<body>
<a href="<?php echo $url; ?>"><< <?php echo $app_strings['LBL_BACK']; ?></a><br><br>
<?php
echo $page_arr[1];
?>
<br><br><a href="<?php echo $url; ?>"><< <?php echo $app_strings['LBL_BACK']; ?></a>
</body>
</html>
