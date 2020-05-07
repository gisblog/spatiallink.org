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
 * $Id: UnifiedSearch.php,v 1.22.2.1 2005/05/09 22:21:34 ajay Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('include/logging.php');
require_once('modules/Contacts/Contact.php');
require_once('modules/Accounts/Account.php');
require_once('modules/Opportunities/Opportunity.php');
require_once('modules/Cases/Case.php');
require_once('modules/Leads/Lead.php');
require_once('modules/Project/Project.php');
global $mod_strings, $modListHeader;

//main
global $app_strings;
echo get_module_title("Search", $mod_strings['LBL_SEARCH_RESULTS'], true); 
echo "\n<BR>\n";
if(isset($_REQUEST['query_string']) && preg_match("/[\w]/", $_REQUEST['query_string']))
{

	if(array_key_exists('Contacts', $modListHeader))
	{
		//get contacts
		$where = Contact::build_generic_where_clause($_REQUEST['query_string']);
		echo "<table><td><tr>\n";
		include ("modules/Contacts/ListView.php");
	}
	
	if(array_key_exists('Accounts', $modListHeader))
	{
		//get accounts
		$where = Account::build_generic_where_clause($_REQUEST['query_string']);
		echo "<table><td><tr>\n";
		include ("modules/Accounts/ListView.php");
	}
	
	if(array_key_exists('Leads', $modListHeader))
	{
		//get leads
		$where = Lead::build_generic_where_clause($_REQUEST['query_string']);
		echo "<table><td><tr>\n";
		include ("modules/Leads/ListView.php");
	}
	
	if(array_key_exists('Opportunities', $modListHeader))
	{
		//get opportunities
		$where = Opportunity::build_generic_where_clause($_REQUEST['query_string']);
		echo "<table><td><tr>\n";
		include ("modules/Opportunities/ListView.php");
	}
	
	if(array_key_exists('Quotes', $modListHeader))
	{
		require_once('modules/Quotes/Quote.php');
		//get leads
		$where = Quote::build_generic_where_clause($_REQUEST['query_string']);
		echo "<table><td><tr>\n";
		include ("modules/Quotes/ListView.php");
	}
	
	if(array_key_exists('Cases', $modListHeader))
	{
		//get cases
		$where = aCase::build_generic_where_clause($_REQUEST['query_string']);
		echo "<table><td><tr>\n";
		include ("modules/Cases/ListView.php");
	}
	
	if(array_key_exists('Project', $modListHeader))
	{
		//get projects
		$where = Project::build_generic_where_clause($_REQUEST['query_string']);
		echo "<table><td><tr>\n";
		include ("modules/Project/ListView.php");
	}
}
else {
	echo "<br><br><em>".$mod_strings['ERR_ONE_CHAR']."</em>";
	//echo "</td></tr></table>\n";
}


?>
<script>
<!--
    document.UnifiedSearch.query_string.focus();
    document.UnifiedSearch.query_string.select();
//-->
</script>
