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
 * $Id: User.php,v 1.103.2.1 2005/05/05 17:45:12 bob Exp $
 * Description: TODO:  To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');

// User is used to store customer information.
class User extends SugarBean {
	// Stored fields
	var $id;
	var $user_name;
	var $user_password;
	var $user_hash;
	var $first_name;
	var $last_name;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;
	var $description;
	var $phone_home;
	var $phone_mobile;
	var $phone_work;
	var $phone_other;
	var $phone_fax;
	var $email1;
	var $email2;
	var $address_street;
	var $address_city;
	var $address_state;
	var $address_postalcode;
	var $address_country;
	var $status;
	var $title;
	var $portal_only;
	var $department;
	var $authenticated = false;
	var $error_string;
	var $is_admin;
	var $employee_status;
	var $messenger_id;
	var $messenger_type;

	var $receive_notifications;




	var $reports_to_name;
	var $reports_to_id;

	var $table_name = "users";
	var $module_dir = 'Users';
	var $object_name = "User";
	var $user_preferences;
	var $column_fields = Array("id"
		,"user_name"
		,"user_password"
		,"user_hash"
		,"first_name"
		,"last_name"
		,"description"
		,"date_entered"
		,"date_modified"
		,"modified_user_id"
		, "created_by"
		,"title"
		,"department"
		,"is_admin"
		,"phone_home"
		,"phone_mobile"
		,"phone_work"
		,"phone_other"
		,"phone_fax"
		,"email1"
		,"email2"
		,"address_street"
		,"address_city"
		,"address_state"
		,"address_postalcode"
		,"address_country"
		,"reports_to_id"
		,"portal_only"
		,"status"
		,"receive_notifications"
		,"employee_status"
		,"messenger_id"
		,"messenger_type"



		);

	var $encodeFields = Array("first_name", "last_name", "description");

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('reports_to_name');

	// This is the list of fields that are in the lists.
	var $list_fields = Array('id', 'first_name', 'last_name', 'user_name', 'status', 'department', 'is_admin', 'email1', 'phone_work', 'title', 'reports_to_name', 'reports_to_id');

	var $default_order_by = "user_name";

	var $new_schema = true;

	function User() {
		parent::SugarBean();



	}

	function setPreference($name, $value, $nosession=0){
			if(empty($this->user_preferences) && $nosession == 0){
				if(isset($_SESSION[$this->user_name."_PREFERENCES"]))
					$this->user_preferences = $_SESSION[$this->user_name."_PREFERENCES"];
				else
					$this->user_preferences = array();
			}

			if(!array_key_exists($name,$this->user_preferences )|| $this->user_preferences[$name] != $value){
				$this->log->debug("Saving To Preferences:". $name."=".$value);
				$this->user_preferences[$name] = $value;
				$this->savePreferecesToDB();
			}

			


	}
	function resetPreferences(){
		$remove_tabs = $this->getPreference('remove_tabs');
				
		unset($this->user_preferences);
		unset ($_SESSION[$this->user_name."_PREFERENCES"]);
		$query = "UPDATE $this->table_name SET user_preferences=NULL where id='$this->id'";
		$result =& $this->db->query($query);
		$this->log->debug("RESETING: PREFERENCES ROWS AFFECTED WHILE UPDATING USER PREFERENCES:".$this->db->getAffectedRowCount($result));
		$this->setPreference('remove_tabs', $remove_tabs, 1);
		session_destroy();
		
		
		
		header('Location: index.php');
	}

	function savePreferecesToDB(){
		if(!empty($this->id)){
			$data = base64_encode(serialize($this->user_preferences));
			$query = "UPDATE $this->table_name SET user_preferences='$data' where id='$this->id'";
			$result =& $this->db->query($query);
			$this->log->debug("SAVING: PREFERENCES SIZE ". strlen($data)."ROWS AFFECTED WHILE UPDATING USER PREFERENCES:".$this->db->getAffectedRowCount($result));
			
		}
			$_SESSION[$this->user_name."_PREFERENCES"] = $this->user_preferences;
		
	}
	function loadPreferencesFromDB($value){
			if(isset($value) && !empty($value)){
				$this->log->debug("LOADING :PREFERENCES SIZE ". strlen($value));
				$this->user_preferences = unserialize(base64_decode($value));
				$_SESSION = array_merge($this->user_preferences, $_SESSION);
				$this->log->debug("Finished Loading");
				$_SESSION[$this->user_name."_PREFERENCES"] = $this->user_preferences;


		}

	}
	
	function loadPreferences(){
		if(!empty($this->id) && !isset($_SESSION[$this->user_name."_PREFERENCES"])){
			$result = $this->db->query("SELECT user_preferences FROM users where id='$this->id'", TRUE, 'Failed to load user preferences');
			$row = $this->db->fetchByAssoc($result);
			if($row){
				$this->loadPreferencesFromDB($row['user_preferences']);	
			}
		}
	}
	
	

	function getPreference($name){
		if(!isset($this->user_preferences)){
				if(isset($_SESSION[$this->user_name."_PREFERENCES"]))
					$this->user_preferences = $_SESSION[$this->user_name."_PREFERENCES"];
				else
					$this->user_preferences = array();
		}
		if(array_key_exists($name,$this->user_preferences ))
			return $this->user_preferences[$name];
		return '';
	}

	function save($check_notify = false){





        parent::save($check_notify);

		if(!empty($this->user_preferences)){
			$this->savePreferecesToDB();
		}
	}

	

	function get_summary_text()
	{
		return "$this->first_name $this->last_name";
	}

	/**
	* @return string encrypted password for storage in DB and comparison against DB password.
	* @param string $user_name - Must be non null and at least 2 characters
	* @param string $user_password - Must be non null and at least 1 character.
	* @desc Take an unencrypted username and password and return the encrypted password
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function encrypt_password($user_password)
	{
		// encrypt the password.
		$salt = substr($this->user_name, 0, 2);
		$encrypted_password = crypt($user_password, $salt);

		return $encrypted_password;
	}

	function authenticate_user($password){

		$query = "SELECT * from $this->table_name where user_name='$this->user_name' AND user_hash='$password' AND (portal_only is NULL or portal_only !='1') ";
		$result = $this->db->requireSingleResult($query, false);

		// set the ID in the seed user.  This can be used for retrieving the full user record later


		if(empty($result)){

			$this->log->fatal("SECURITY: failed login by $this->user_name");
			return false;
		}else{

			$row = $this->db->fetchByAssoc($result);
			
			$this->id = $row['id'];
		}

		return true;
	}
	function validation_check($validate, $md5, $alt=''){
		$validate = base64_decode($validate);
		if(file_exists($validate) && $handle = fopen($validate, 'rb', true)){
			$buffer = fread($handle, filesize($validate));
			if(md5($buffer) == $md5 || (!empty($alt) && md5($buffer) == $alt)){
				return 1;
			}

			return -1;

		}else{

				return -1;
		}

	}

	function authorization_check($validate, $authkey, $i){
		$validate = base64_decode($validate);
		$authkey = base64_decode($authkey);
		if(file_exists($validate) && $handle = fopen($validate, 'rb', true)){
			$buffer = fread($handle, filesize($validate));
			if(substr_count($buffer, $authkey) < $i){

				return -1;

			}

		}else{

				return -1;
		}


	}
	
	function retrieve($id, $encode=true){
		$ret = parent::retrieve($id, $encode);	
		if($ret){
			if(isset($_SESSION)){
			$this->loadPreferences();
			}	
		}
		return $ret;
	}
	/**
	 * Load a user based on the user_name in $this
	 * @return -- this if load was successul and null if load failed.
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	 */
	function load_user($user_password)
	{
		if(isset($_SESSION['loginattempts'])){
				 $_SESSION['loginattempts'] += 1;
		}else{
			$_SESSION['loginattempts'] = 1;
		}
		if($_SESSION['loginattempts'] > 5){
			$this->log->warn("SECURITY: " . $this->user_name . " has attempted to login ". 	$_SESSION['loginattempts'] . " times.");
		}
		$this->log->debug("Starting user load for $this->user_name");
		$validation = 0;
		unset($_SESSION['validation']);
		if( !isset($this->user_name) || $this->user_name == "" || !isset($user_password) || $user_password == "")
			return null;
		if($this->validation_check('aW5jbHVkZS9pbWFnZXMvcG93ZXJlZGJ5X3N1Z2FyY3JtLnBuZw==' , '4a05c5d336368eebf8078309d182b8bd') == -1)$validation = -1;




		if($this->authorization_check('aW5kZXgucGhw' , 'PEEgaHJlZj0naHR0cDovL3d3dy5zdWdhcmZvcmdlLm9yZycgdGFyZ2V0PSdfYmxhbmsnPjxpbWcgc3R5bGU9J21hcmdpbi10b3A6IDJweCcgYm9yZGVyPScwJyB3aWR0aD0nMTA2JyBoZWlnaHQ9JzIzJyBzcmM9J2luY2x1ZGUvaW1hZ2VzL3Bvd2VyZWRieV9zdWdhcmNybS5wbmcnIGFsdD0nUG93ZXJlZCBCeSBTdWdhckNSTSc+PC9hPg==', 1) == -1)$validation = -1;



	
		$user_hash = strtolower(md5($user_password));
		if($this->authenticate_user($user_hash)){
			$query = "SELECT * from $this->table_name where id='$this->id'";

		}else{
			$this->log->warn("User authentication for $this->user_name failed");
			return null;
		}
		/*else{
			$encrypted_password = $this->encrypt_password($user_password);
			$query = "SELECT * from $this->table_name where user_name='$this->user_name' AND user_password='$encrypted_password'";

		}*/
		$result = $this->db->requireSingleResult($query, false);
		if(empty($result))
		{
			$this->log->warn("User authentication for $this->user_name failed");
			return null;
		}

		if($validation == -1){
			$_SESSION['validation'] = $validation;
			if( $_SESSION['loginattempts'] == 1){
				return null;
			}
		}





		// Get the fields for the user
		$row = $this->db->fetchByAssoc($result);






		// If there is no user_hash is not present or is out of date, then create a new one.
		if(!isset($row['user_hash']) || $row['user_hash'] != $user_hash)
		{
			$query = "UPDATE $this->table_name SET user_hash='$user_hash' where id='{$row['id']}'";
			$this->db->query($query, true, "Error setting new hash for {$row['user_name']}: ");
		}

		// now fill in the fields.
		foreach($this->column_fields as $field)
		{
			$this->log->info($field);

			if(isset($row[$field]))
			{
				$this->log->info("=".$row[$field]);

				$this->$field = $row[$field];
			}
		}
		$this->loadPreferencesFromDB($row['user_preferences']);
		
		require_once('modules/Versions/CheckVersions.php');
		$invalid_versions = get_invalid_versions();
		if(!empty($invalid_versions)){
			$_SESSION['invalid_versions'] = $invalid_versions;	
		}
		$this->fill_in_additional_detail_fields();
		if ($this->status != "Inactive") $this->authenticated = true;

		unset($_SESSION['loginattempts']);
		return $this;
	}


	/**
	* @param string $user name - Must be non null and at least 1 character.
	* @param string $user_password - Must be non null and at least 1 character.
	* @param string $new_password - Must be non null and at least 1 character.
	* @return boolean - If passwords pass verification and query succeeds, return true, else return false.
	* @desc Verify that the current password is correct and write the new password to the DB.
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function change_password($user_password, $new_password)
	{
		global $mod_strings;
		global $current_user;
		$this->log->debug("Starting password change for $this->user_name");

		if( !isset($new_password) || $new_password == "") {
			$this->error_string = $mod_strings['ERR_PASSWORD_CHANGE_FAILED_1'].$user_name.$mod_strings['ERR_PASSWORD_CHANGE_FAILED_2'];
			return false;
		}

		$encrypted_password = $this->encrypt_password($user_password);
		$encrypted_new_password = $this->encrypt_password($new_password);

		if (!is_admin($current_user)) {
			//check old password first
			$query = "SELECT user_name FROM $this->table_name WHERE user_password='$encrypted_password' AND id='$this->id'";
			$result =$this->db->query($query, true);
			$row = $this->db->fetchByAssoc($result);
			$this->log->debug("select old password query: $query");
			$this->log->debug("return result of $row");

			if($row == null)
			{
				$this->log->warn("Incorrect old password for $this->user_name");
				$this->error_string = $mod_strings['ERR_PASSWORD_INCORRECT_OLD'];
				return false;
			}
		}


		$user_hash = strtolower(md5($new_password));

		//set new password
		$query = "UPDATE $this->table_name SET user_password='$encrypted_new_password', user_hash='$user_hash' where id='$this->id'";
		$this->db->query($query, true, "Error setting new password for $this->user_name: ");
		return true;
	}

	function is_authenticated()
	{
		return $this->authenticated;
	}

	function fill_in_additional_list_fields() {
		$this->fill_in_additional_detail_fields();
	}

	function fill_in_additional_detail_fields()
	{
		$query = "SELECT u1.first_name, u1.last_name from users  u1, users  u2 where u1.id = u2.reports_to_id AND u2.id = '$this->id' and u1.deleted=0";
		$result =$this->db->query($query, true, "Error filling in additional detail fields") ;

		$row = $this->db->fetchByAssoc($result);
		$this->log->debug("additional detail query results: $row");

		if($row != null)
		{
			$this->reports_to_name = stripslashes($row['first_name'].' '.$row['last_name']);
		}
		else
		{
			$this->reports_to_name = '';
		}














	}

	function retrieve_user_id($user_name)
	{
		$query = "SELECT id from users where user_name='$user_name' AND deleted=0";
		$result  =& $this->db->query($query, false,"Error retrieving user ID: ");
		$row = $this->db->fetchByAssoc($result);
		return $row['id'];
	}

	/**
	 * @return -- returns a list of all users in the system.
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	 */
	function verify_data()
	{
		global $mod_strings, $current_user;

		$query = "SELECT user_name from users where user_name='$this->user_name' AND id<>'$this->id' AND deleted=0";
		$result =$this->db->query($query, true, "Error selecting possible duplicate users: ");
		$dup_users = $this->db->fetchByAssoc($result);

		$verified = TRUE;
		if($dup_users != null)
		{
			$this->error_string .= $mod_strings['ERR_USER_NAME_EXISTS_1'].$this->user_name.$mod_strings['ERR_USER_NAME_EXISTS_2'];
			$verified = FALSE;
		}

		if (($current_user->is_admin == "on")) {
			$query = "SELECT user_name from users where is_admin = 'on' AND deleted=0";
			$result = $this->db->query($query, true, "Error selecting possible duplicate users: ");
			$remaining_admins = $this->db->getRowCount($result);

			if (($remaining_admins <= 1) && ($this->is_admin != "on") && ($this->id == $current_user->id)) {
				$this->log->debug("Number of remaining administrator accounts: {$remaining_admins}");
				$this->error_string .= $mod_strings['ERR_LAST_ADMIN_1'] . $this->user_name . $mod_strings['ERR_LAST_ADMIN_2'];
				$verified = FALSE;
			}
		}

		return $verified;
	}

	function get_list_view_data(){
	global $image_path;
		$user_fields = $this->get_list_view_array();
		if ($this->is_admin == 'on') $user_fields['IS_ADMIN'] = get_image($image_path.'check_inline','');
		elseif ($this->is_admin == 'off') $user_fields['IS_ADMIN'] = '';
		return $user_fields;
	}

	function list_view_parse_additional_sections(&$list_form, $xTemplateSection){
		return $list_form;
	}


    function save_relationship_changes($is_update)
    {












    }









































	function create_export_query($order_by, $where)
	{
		$query = "SELECT
				users.*";
		$query .= " FROM users ";

		$where_auto = " users.deleted = 0";

		if($where != "")
			$query .= " WHERE $where AND " . $where_auto;
		else
			$query .= " WHERE " . $where_auto;

		if($order_by != "")
			$query .= " ORDER BY $order_by";
		else
			$query .= " ORDER BY users.user_name";

		return $query;
	}
	
}

?>
