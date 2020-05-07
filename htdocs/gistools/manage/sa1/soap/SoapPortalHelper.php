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
$portal_modules = array('Contacts', 'Accounts', 'Cases', 'Bugs', 'Notes');
/*
BUGS
*/
require_once('modules/Notes/Note.php');
require_once('modules/Contacts/Contact.php');
require_once('modules/Bugs/Bug.php');
function get_bugs_in_contacts($in, $orderBy = '', $where='')
	{
		// First, get the list of IDs.
		$query = "SELECT bug_id as id from contacts_bugs where contact_id IN $in AND deleted=0";
		if(!empty($orderBy)){
			$query .= ' ORDER BY ' . $orderBy;	
		}
		$sugar =& new Contact();



		set_module_in($sugar->build_related_in($query), 'Bugs'); 
	}

function get_bugs_in_accounts($in, $orderBy = '', $where='')
	{
		// First, get the list of IDs.
		$query = "SELECT bug_id as id from accounts_bugs where account_id IN $in AND deleted=0";
		if(!empty($orderBy)){
			$query .= ' ORDER BY ' . $orderBy;	
		}
		$sugar =& new Account();



		set_module_in($sugar->build_related_in($query), 'Bugs'); 
	}

/*
Cases
*/
require_once('modules/Cases/Case.php');
function get_cases_in_contacts($in, $orderBy = '')
	{
		// First, get the list of IDs.
		
		$query = "SELECT case_id as id from contacts_cases where contact_id IN $in AND deleted=0";
		if(!empty($orderBy)){
			$query .= ' ORDER BY ' . $orderBy;	
		}

		$sugar =& new Contact();



		set_module_in($sugar->build_related_in($query), 'Cases'); 
	}
	
function get_cases_in_accounts($in, $orderBy = '')
	{
		if(empty($_SESSION['viewable']['Accounts'])){
			return;	
		}
		// First, get the list of IDs.
		$query = "SELECT id  from cases where account_id IN $in AND deleted=0";
		if(!empty($orderBy)){
			$query .= ' ORDER BY ' . $orderBy;	
		}
	
		$sugar =& new Account();



		set_module_in($sugar->build_related_in($query), 'Cases'); 
	}


	
/*
NOTES
*/

require_once('modules/Accounts/Account.php');
function get_notes_in_contacts($in, $orderBy = '')
	{
		// First, get the list of IDs.
		$query = "SELECT id from notes where contact_id IN $in AND deleted=0 AND portal_flag=1";
		if(!empty($orderBy)){
			$query .= ' ORDER BY ' . $orderBy;	
		}
		
		$contact =& new Contact();	



		$note =& new Note();



		return $contact->build_related_list($query, $note);
	}
	
function get_notes_in_module($in, $module, $orderBy = '')
	{
		// First, get the list of IDs.
		$query = "SELECT id from notes where parent_id IN $in AND parent_type='$module' AND deleted=0 AND portal_flag = 1";
		if(!empty($orderBy)){
			$query .= ' ORDER BY ' . $orderBy;	
		}
		global $beanList, $beanFiles;

		if(!empty($beanList[$module])){
			$class_name = $beanList[$module];
			require_once($beanFiles[$class_name]);
			$sugar =& new $class_name();
		}else{
			return array();	
		}
		



		$note =& new Note();



		return $sugar->build_related_list($query, $note);
	}

function get_accounts_from_contact($contact_id, $orderBy = '')
	{
				// First, get the list of IDs.
		$query = "SELECT account_id as id from accounts_contacts where contact_id='$contact_id' AND deleted=0";
		if(!empty($orderBy)){
			$query .= ' ORDER BY ' . $orderBy;	
		}
		$sugar =& new Contact();



		set_module_in($sugar->build_related_in($query), 'Accounts'); 
	}

function get_contacts_from_account($account_id, $orderBy = '')
	{
		// First, get the list of IDs.
		$query = "SELECT contact_id as id from accounts_contacts where account_id='$account_id' AND deleted=0";
		if(!empty($orderBy)){
			$query .= ' ORDER BY ' . $orderBy;	
		}
		$sugar =& new Account();



		set_module_in($sugar->build_related_in($query), 'Contacts'); 
	}

function get_related_list($in, $template, $where, $order_by){
		$list = array();



		return $template->build_related_list_where('',$template, $where, $in, $order_by);
	
		
}

function build_relationship_tree($contact){
	global $sugar_config;
	$contact->retrieve($contact->id);




	get_accounts_from_contact($contact->id);
	
	set_module_in(array('list'=>array($contact->id), 'in'=> "('$contact->id')"), 'Contacts');
	
	$accounts = $_SESSION['viewable']['Accounts'];
	foreach($accounts as $id){
		if(!isset($sugar_config['portal_view']) || $sugar_config['portal_view'] != 'single_user'){
			get_contacts_from_account($id);
		}
	}
}

function get_contacts_in(){
	return $_SESSION['viewable']['contacts_in'];	
}

function get_accounts_in(){
	return $_SESSION['viewable']['accounts_in'];	
}

function get_module_in($module_name){
	$mod_in = '';
	if(!isset($_SESSION['viewable'][$module_name])){
		return '()';
	}
	if(isset($_SESSION['viewable'][strtolower($module_name).'_in'])){
		return $_SESSION['viewable'][strtolower($module_name).'_in'];	
	}
	foreach($_SESSION['viewable'][$module_name] as $id){
		if(empty($mod_in)){
			$mod_in = "('$id'";	
		}else{
			$mod_in = ",'$id'";	
		}	
	}
	if(empty($mod_in)){
		$mod_in = '(';	
	}
	$mod_in = ')';
	$_SESSION['viewable'][strtolower($module_name).'_in'] = $mod_in;
}

function set_module_in($arrayList, $module_name){

		if(!isset($_SESSION['viewable'][$module_name])){
			$_SESSION['viewable'][$module_name] = array();	
		}
		foreach($arrayList['list'] as $id){
			$_SESSION['viewable'][$module_name][$id] = $id;	
		}
		if($module_name == 'Accounts' && isset($id)){
			$_SESSION['account_id'] = $id;	
		}
		if(!empty($_SESSION['viewable'][strtolower($module_name).'_in'])){
			$_SESSION['viewable'][strtolower($module_name).'_in'] = str_replace(')','', $_SESSION['viewable'][strtolower($module_name).'_in']) . str_replace('(', ',', $arrayList['in']);	
		}else{
			$_SESSION['viewable'][strtolower($module_name).'_in'] = $arrayList['in'];
		}
}

$invalid_contact_fields = array('portal_name'=>1, 'portal_password'=>1, 'portal_active'=>1);
$valid_modules_for_contact = array('Contacts'=>1, 'Cases'=>1, 'Notes'=>1, 'Bugs'=>1, 'Accounts'=>1);




?>
