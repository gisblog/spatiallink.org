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
* $Id: SugarBean.php,v 1.150.2.1 2005/05/12 23:24:33 majed Exp $
* Description:  Defines the base class for all data entities used throughout the
* application.  The base class including its methods and variables is designed to
* be overloaded with module-specific methods and variables particular to the
* module's base entity class.
* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
* All Rights Reserved.
* Contributor(s): ______________________________________..
********************************************************************************/

include_once('config.php');
include_once('sugar_version.php');
require_once('include/logging.php');
require_once('data/Tracker.php');
require_once('include/utils.php');
require_once('modules/DynamicFields/DynamicField.php');
require_once('modules/CustomFields/CustomFields.php');
require_once('include/TimeDate.php');
include_once('include/database/DBManagerFactory.php');

class SugarBean
{
	/**
	* This method implements a generic insert and update logic for any SugarBean
	* This method only works for subclasses that implement the same variable names.
	* This method uses the presence of an id field that is not null to signify and update.
	* The id field should not be set otherwise.
	* todo - Add support for field type validation and encoding of parameters.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/

	var $db;
	var $log;
	var $new_schema = false;
	var $new_with_id = false;



	var $new_assigned_user_name;
	var $processed_dates_times = array();
	var $process_save_dates =true;
	var $save_from_post = true;
	var $duplicates_found = false;
    var $dbManager;
    var $deleted = 0;

	var $table_name = '';
	var $object_name = '';
	var $module_dir = '';
	var $field_name_map;
	var $field_defs;
	var $custom_fields;
	var $column_fields = array();
	var $list_fields = array();
	var $additional_column_fields = array();
	var $current_notify_user;
    
	function SugarBean(){
		$this->db = new PearDatabase();
        $this->dbManager = DBManagerFactory::getInstance();
		$this->log = LOGGER::getLogger($this->object_name);
		
		global $dictionary, $beanList;








		if(isset($this->module_dir) && isset($this->object_name) && !isset($dictionary[$this->object_name])){
			if(file_exists('modules/'. $this->module_dir . '/vardefs.php')){
				include_once('modules/'. $this->module_dir . '/vardefs.php');
			}





		}
		
		if(isset($dictionary[$this->object_name])){
				$this->field_name_map =& $dictionary[$this->object_name]['fields'];
				$this->field_defs =&	$dictionary[$this->object_name]['fields'];
			
		}
		//setup custom fields
		if(!isset($this->custom_fields))
		 $this->setupCustomFields($this->module_dir);
        
        // we do not need to initialize it in abstract class
//		$this->object_name = 'sugarbean - basic';	
	}

    function getObjectName(){
        if ($this->object_name) return $this->object_name;
        
        // must be over ridden
        //$this->log->fatal("SugarBean: getObjectName must be overridden");
        
        // This is a quick way out. The generated metadata files have the table name
        // as the key. The correct way to do this is to override this function
        // in bean and return the object name. That requires changing all the beans
        // as well as put the object name in the generator.
        return $this->table_name;
    }
    
    function getTableName(){
        global $dictionary;
		if(isset($this->table_name)){
			return $this->table_name;	
		}
        return $dictionary[$this->getObjectName()]['table'];
    }

    function getFieldDefinitions(){
       return $this->field_defs;
    }
    
    function getIndices(){
        global $dictionary;
        if(isset($dictionary[$this->getObjectName()]['indices'])){
        	return $dictionary[$this->getObjectName()]['indices'];
        }
        return array();
    }
    
    function getFieldDefinition($name){
      
        return $this->field_defs[$name];
    }
    
    function getPrimaryFieldDefinition(){
    	$def = $this->getFieldDefinition("id");
    	if (!$def) $def = $this->getFieldDefinition(0);
    	return $def;
    }
    
    function getFieldValue($name){
        if (!isset($this->$name)) { 
           return FALSE;
        }
        return $this->$name;
    }
    
    function create_tables()
    {
        global $dictionary;
        
        $key = $this->getObjectName();
        if (!array_key_exists($key, $dictionary)){
           $this->log->fatal("Metadata for table ".$this->table_name. " does not exist");
           display_notice("meta data absent for table ".$this->table_name." keyed to $key ");              
        }
        else {
            $this->dbManager->createTable($this);
          
        }
    }
    
    
    function drop_tables()
    {
        global $dictionary;
        $key = $this->getObjectName();
        if (!array_key_exists($key, $dictionary)){
           $this->log->fatal("Metadata for table ".$this->table_name. " does not exist");
           echo "meta data absent for table ".$this->table_name."<br>\n";              
        } else {
        	if ($this->db->tableExists($this->table_name))
	        	$this->dbManager->dropTable($this);  
        }  
    }
    
	
	function setupCustomFields($module_name){
			$this->custom_fields =& new DynamicField($module_name);
			$this->custom_fields->setup($this);
	}

	function save($check_notify = FALSE)
	{
		
		$timedate = new TimeDate();
		global $current_user;
		global $current_module;
		$isUpdate = true;
		if(empty($this->id))
		{
			$isUpdate = false;
		}

		if ( $this->new_with_id == true )
		{
			$isUpdate = false;
		}

	
		$this->date_modified = date("Y-m-d H:i:s", time());
		
		
		if (isset($current_user)) $this->modified_user_id = $current_user->id;

		if ($this->deleted != 1) $this->deleted = 0;

		if($isUpdate)
		{
			$query = "Update ";
		}
		else
		{
			if (!isset($this->date_entered))
			{
				if($this->process_save_dates)
					$this->date_entered = $timedate->to_display_date_time(date("Y-m-d H:i:s", time()), true);
				else
					$this->date_entered = date("Y-m-d H:i:s", time());
            }
            //if (!isset($this->created_by))
            //{ 
                // created by should always be this user    
				$this->created_by = (isset($current_user)) ? $current_user->id : "";
			//}
			if($this->new_schema &&
			$this->new_with_id == false)
			{
				$this->id = create_guid();

			}
            			
			$query = "INSERT into ";




		}

        // use the db independent query generator
        
        $this->check_date_relationships_save();
		
		if ($check_notify) {
			require_once("modules/Administration/Administration.php");
			$admin = new Administration();
			$admin->retrieveSettings();
			if ($admin->settings['notify_on']) {
				$this->log->info("Notifications: user assignment has changed, checking if user receives notifications");
				$notify_list = $this->get_notification_recipients();
				foreach ($notify_list as $notify_user)
				{
					$this->send_assignment_notifications($notify_user, $admin);
				}
			}
			else {
				$this->log->info("Notifications: not sending e-mail, notify_on is set to OFF");
			}
		}
	
        if ($this->db->dbType == "oci8"){








        } else if ($this->db->dbType == 'mysql') {
    		// write out the SQL statement.
	       	$query .= $this->table_name." set ";

    		$firstPass = 0;
	       	if(isset($this->custom_fields)) $this->custom_fields->save($isUpdate);
    		foreach($this->column_fields as $field)
    		{
    			// Do not write out the id field on the update statement.
    			// We are not allowed to change ids.
    			if($isUpdate && ('id' == $field)) continue;
    			//custom fields handle there save seperatley
    			if(isset($this->field_name_map) && !empty($this->field_name_map[$field]['custom_type']))
    				continue;
    
    			// Only assign variables that have been set.
    			if(isset($this->$field)){
    				// Try comparing this element with the head element.
    				if(0 == $firstPass)
    				$firstPass = 1;
    				else
    				$query .= ", ";
    				$query .= $field."='".PearDatabase::quote(from_html($this->$field))."'";
    			}
    		}
    
    		if($isUpdate)
    		{
    			$query = $query." WHERE ID = '$this->id'";
    			$this->log->info("Update $this->object_name: ".$query);
    		} else  {
    			$this->log->info("Insert: ".$query);
    		}
    		
            $this->log->info("Save: $query");
    		$this->db->query($query, true);
        }
        
		// let subclasses save related field changes
		$this->save_relationship_changes($isUpdate);

		//if track_on_save is set ot true create the track record.
		if (isset($this->track_on_save) && $this->track_on_save == true && isset($this->module_dir)) {
			$this->track_view($current_user->id, $this->module_dir);	
		}
		return $this->id;
	}

	/**
	* This function determines which users received a notification.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
	function get_notification_recipients()
	{
		$notify_user = new User();
		$notify_user->retrieve($this->assigned_user_id);
		$this->new_assigned_user_name = $notify_user->first_name.' '.$notify_user->last_name;

		$this->log->info("Notifications: recipient is $this->new_assigned_user_name");

		$user_list = array($notify_user);
		return $user_list;
	}

	/**
	* This function handles sending out email notifications when items are first assigned to users.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
	function send_assignment_notifications($notify_user, $admin)
	{
		global $sugar_config, $app_list_strings, $current_user;
		if ($notify_user->receive_notifications) {
			if (empty($notify_user->email1) && empty($notify_user->email2)) {
				$this->log->warn("Notifications: no e-mail address set for user {$notify_user->user_name}, cancelling send");
			}
			else {
				$notify_mail = $this->create_notification_email($notify_user);
				if ($admin->settings['mail_sendtype'] == "SMTP") {
					$notify_mail->Mailer = "smtp";
					$notify_mail->Host = $admin->settings['mail_smtpserver'];
					$notify_mail->Port = $admin->settings['mail_smtpport'];
					if ($admin->settings['mail_smtpauth_req']) {
						$notify_mail->SMTPAuth = TRUE;
						$notify_mail->Username = $admin->settings['mail_smtpuser'];
						$notify_mail->Password = $admin->settings['mail_smtppass'];
					}
				}

				$notify_mail->From = $admin->settings['notify_fromaddress'];
				$notify_mail->FromName = (empty($admin->settings['notify_fromname'])) ? "" : $admin->settings['notify_fromname'];

				if(!$notify_mail->Send()) {
					$this->log->warn("Notifications: error sending e-mail (method: {$notify_mail->Mailer}), (error: {$notify_mail->ErrorInfo})");
				}
				else {
					$this->log->info("Notifications: e-mail successfully sent");
				}
			}
		}
	}

	/**
	* This function handles sending out email notifications when items are first assigned to users.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
	function create_notification_email($notify_user)
	{
		global $sugar_version, $sugar_config, $app_list_strings, $current_user;

		$notify_address = (empty($notify_user->email1)) ? from_html($notify_user->email2) : from_html($notify_user->email1);
		$notify_name = (empty($notify_user->first_name)) ? from_html($notify_user->user_name) : from_html($notify_user->first_name . " " . $notify_user->last_name);
		$this->log->debug("Notifications: user has e-mail defined");

		require_once("include/phpmailer/class.phpmailer.php");
		$notify_mail = new PHPMailer;

		$notify_mail->AddAddress($notify_address, $notify_name);

		if (empty($_SESSION['authenticated_user_language'])) {
			$current_language = $sugar_config['default_language'];
		}
		else {
			$current_language = $_SESSION['authenticated_user_language'];
		}

		require_once("XTemplate/xtpl.php");
		$xtpl = new XTemplate("include/language/{$current_language}.notify_template.html");

		$template_name = $this->object_name;

		$this->current_notify_user = $notify_user;

		if (in_array('set_notification_body', get_class_methods($this)))
		{
			$xtpl = $this->set_notification_body($xtpl, $this);
		}
		else
		{
			$xtpl->assign("OBJECT", $this->object_name);
			$template_name = "Default";
		}

		$xtpl->assign("ASSIGNED_USER", $this->new_assigned_user_name);
		$xtpl->assign("ASSIGNER", $current_user->user_name);
		$xtpl->assign("URL", "{$sugar_config['site_url']}/index.php?module={$app_list_strings['record_type_module'][$this->object_name]}&action=DetailView&record={$this->id}");
		$xtpl->assign("SUGAR", "Sugar Suite v{$sugar_version}");
		$xtpl->parse($template_name);
		$xtpl->parse($template_name . "_Subject");

		$notify_mail->Body = from_html(trim($xtpl->text($template_name)));
		$notify_mail->Subject = from_html($xtpl->text($template_name . "_Subject"));

		return $notify_mail;
	}

	/**
	* This function is a good location to save changes that have been made to a relationship.
	* This should be overriden in subclasses that have something to save.
	* param $is_update true if this save is an update.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
	function save_relationship_changes($is_update)
	{

	}

	/**
	* This function retrieves a record of the appropriate type from the DB.
	* It fills in all of the fields from the DB into the object it was called on.
	* param $id - If ID is specified, it overrides the current value of $this->id.  If not specified the current value of $this->id will be used.
	* returns this - The object that it was called apon or null if exactly 1 record was not found.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/

	function check_date_relationships_load(){
		$timedate = new TimeDate();
		if(empty($this->field_defs)){
			return;
		}
		foreach($this->field_defs as $field){
			$field = $field['name'];
			if(!key_exists($field, $this->processed_dates_times)){
				$this->processed_dates_times[$field] = '1';
			if($field == 'date_modified' || $field == 'date_entered' && !empty($this->$field)){

				$this->$field = $timedate->to_display_date_time($this->$field);

			}else if(!empty($this->$field)){
				$type = $this->field_name_map[$field]['type'];
				if($type == 'relate'  && isset($this->field_name_map[$field]['custom_type'])){
					$type = $this->field_name_map[$field]['custom_type'];	
				}
				if($type == 'date'){
					$this->$field = from_db_convert($this->$field, 'date');
					if($this->$field == '0000-00-00'){
						$this->$field = '';
					}else
					if(!empty($this->field_name_map[$field]['rel_field'])){
						$rel_field = $this->field_name_map[$field]['rel_field'];
						if(!empty($this->$rel_field)){
							$mergetime = $timedate->merge_date_time($this->$field,$this->$rel_field);
							$this->$field = $timedate->to_display_date($mergetime);
							$this->$rel_field = $timedate->to_display_time($mergetime);

						}
					}else{
						$this->$field = $timedate->to_display_date($this->$field, false);
					}
				}else if($type == 'datetime'){
							$this->$field = $timedate->to_display_date_time($this->$field);
				}else if($type == 'time'){
						//$this->$field = from_db_convert($this->$field, 'time');
						if(empty($this->field_name_map[$field]['rel_field'])){
							$this->$field = $timedate->to_display_time($this->$field,true, false);
						}
				}
				
			}}
		}
	}

	function check_date_relationships_save(){
		 $timedate = new TimeDate();
		if($this->process_save_dates){
		
		if(empty($this->field_defs)){
			return;
		}

		foreach($this->field_defs as $field){
      if ( ! isset($this->$field['name']) || $field == 'date_modified' )
			{
				continue;
			}
			$field = $field['name'];
			if($field == 'date_modified'){
				
			//do nothing we need to do this so we can keep seconds on the time
			}else if($field == 'date_entered'){

				$this->$field = $timedate->to_db($this->$field);

			}else if(!empty($this->$field)){
				$type = $this->field_name_map[$field]['type'];
				if($type == 'relate'  && isset($this->field_name_map[$field]['custom_type'])){
					$type = $this->field_name_map[$field]['custom_type'];	
				}
				if($type == 'date'){
					if(!empty($this->field_name_map[$field]['rel_field'])){
						$rel_field = $this->field_name_map[$field]['rel_field'];
						if(empty($this->$rel_field)){
							$this->$rel_field = $timedate->to_display_time('12:00:00', true, false);
						}
						$mergetime = $timedate->merge_date_time($this->$field,$this->$rel_field);
						$this->$field = $timedate->to_db_date($mergetime);
						$this->$rel_field = $timedate->to_db_time($mergetime);
						
					}else{
						
						$this->$field = $timedate->to_db_date($this->$field, false);
						
						
					}
				}else if($type == 'datetime'){
							$this->$field = $timedate->to_db($this->$field);
				}else if($type == 'time'){
						if(empty($this->field_name_map[$field]['rel_field'])){
							$this->$field = $timedate->to_db_time($this->$field, false);
						}
				}
			}
		}
		}/*else{
			$this->date_modified = $timedate->to_db($this->date_modified);
		}*/
		
	}

	function retrieve($id = -1, $encode=true) {
		if ($id == -1) {
			$id = $this->id;
		}
		if(isset($this->custom_fields))
			$custom_join = $this->custom_fields->getJOIN();
		else $custom_join = false;
		
		if($custom_join){
			$query = "SELECT $this->table_name.*". $custom_join['select']. " FROM $this->table_name ";
		}else{
			$query = "SELECT $this->table_name.* FROM $this->table_name ";	
		}
	
		








		
		if($custom_join){
			$query .= ' ' . $custom_join['join'];	
		}
		$query .= " WHERE $this->table_name.id = '$id' ";
		$this->log->debug("Retrieve $this->object_name: ".$query);
		$result =& $this->db->requireSingleResult($query, true, "Retrieving record by id $this->table_name:$id found ");

		if(empty($result))
		{
			return null;
		}
	
		$row = $this->db->fetchByAssoc($result, -1, $encode);
		
		if (sizeof($row) == 0) return false;
		
		$this->populateFromRow($row);
		$this->processed_dates_times = array();
		$this->check_date_relationships_load();
		
		$this->fill_in_additional_detail_fields();

		return $this;
	}
	
	function populateFromRow($row){
		foreach($this->column_fields as $field)
		{




			$rfield = $field; // fetch returns it in lowercase only
			if(isset($row[$rfield]))
			{
				$this->$field = $row[$rfield];
			}else{
				$this->$field = '';
			}
		}
	}

	/**
	* Add any required joins to the list count query.  The joins are required if there
	* is a field in the $where clause that needs to be joined.
	*/
	function add_list_count_joins(&$query, $where)
	{
		$custom_join = $this->custom_fields->getJOIN();
		if($custom_join){
  				$query .= $custom_join['join'];
		}

	}

	/**
	* Create a query to return the number of items in a list.  This is used to
	* populate the upper limit on list views
	*/
	function create_list_count_query($where)
	{
		$orderby = '';
		$query = $this->create_list_query($orderby, $where);
		$query = explode('FROM', $query);
		if(sizeof($query) == 1){
			$query = explode('from', $query[0]);	
		}
		$query = explode( 'ORDER BY',$query[1]);

		return 'SELECT count(*) FROM ' . $query[0];
		
		/*
		$query = "
			SELECT count(*)
			  FROM $this->table_name ";





		// add joins that are needed
		$this->add_list_count_joins($query, $where);

		if($where != "")
			$query .= "where ($where) AND ".$this->table_name.".deleted=0 ";
		else
			$query .= "where ".$this->table_name.".deleted=0 ";

		return $query;
		*/
	}

	/**
	* This function returns a paged list of the current object type.  It is intended to allow for
	* hopping back and forth through pages of data.  It only retrieves what is on the current page.
	* This method must be called on a new instance.  It trashes the values of all the fields in the current one.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	* All Rights Reserved..
	* Contributor(s): ______________________________________..
	*/
	function get_list($order_by = "", $where = "", $row_offset = 0, $limit=-1, $max=-1) {
		$this->log->debug("get_list:  order_by = '$order_by' and where = '$where' and limit = '$limit'");
	
		$query = $this->create_list_query($order_by, $where);
		return $this->process_list_query($query, $row_offset, $limit, $max, $where);
	}

	/**
	* This function returns a full (ie non-paged) list of the current object type.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	* All Rights Reserved..
	* Contributor(s): ______________________________________..
	*/
	function get_full_list($order_by = "", $where = "", $check_dates=false) {
		$this->log->debug("get_full_list:  order_by = '$order_by' and where = '$where'");
		
		$query = $this->create_list_query($order_by, $where);
		return $this->process_full_list_query($query, $check_dates);
	}

	function create_list_query($order_by, $where)
	{	
		$custom_join = false;
		if(isset($this->custom_fields))
			$custom_join = $this->custom_fields->getJOIN();
			$query = "SELECT ";

		if($custom_join){
			$query .= " $this->table_name.*". $custom_join['select']. " FROM $this->table_name " . $custom_join['join'];
		}else{
			$query .= " $this->table_name.* FROM $this->table_name ";	
		}
		




		if($where != "")
		$query .= "where ($where) AND ".$this->table_name.".deleted=0 ";
		else
		$query .= "where ".$this->table_name.".deleted=0 ";
		
		if(!empty($order_by))
		$query .= " ORDER BY $order_by";


		return $query;
	}

	function process_list_query($query, $row_offset, $limit= -1, $max_per_page = -1, $where = '')
	{
		global $sugar_config;
		$this->log->debug("process_list_query: ".$query);
		if($max_per_page == -1){
			$max_per_page 	= $sugar_config['list_max_entries_per_page'];
		}

		// Check to see if we have a count query available.
		$count_query = $this->create_list_count_query($where);

		if(!empty($count_query) && (empty($limit) || $limit == -1))
		{
			// We have a count query.  Run it and get the results.
			$result =& $this->db->query($count_query, true, "Error running count query for $this->object_name List: ");
			$assoc = $this->db->fetchByAssoc($result);
			if(!empty($assoc['count(*)']))
			{
				$rows_found = $assoc['count(*)'];
				$limit = $sugar_config['list_max_entries_per_page'];
			}
		}

		if(empty($row_offset))
		{
			$row_offset = 0;
		}

		if(!empty($limit) && $limit != -1){
			$result =& $this->db->limitQuery($query, $row_offset, $limit,true,"Error retrieving $this->object_name list: ");
		}else{
			$result =& $this->db->query($query,true,"Error retrieving $this->object_name list: ");
		}

		$list = Array();
		if(empty($rows_found))
		{
  			$rows_found =  $this->db->getRowCount($result);
		}

		$this->log->debug("Found $rows_found ".$this->object_name."s");

		$previous_offset = $row_offset - $max_per_page;
		$next_offset = $row_offset + $max_per_page;

		if($rows_found != 0 or $this->db->dbType != 'mysql')
		{
			

			for($index = $row_offset , $row = $this->db->fetchByAssoc($result, $index); 
			    $row && (($index < $row_offset + $max_per_page || $max_per_page == -99) or ($this->db->dbType != 'mysql'));
			    $index++, $row = $this->db->fetchByAssoc($result, $index)){

				
				foreach($this->list_fields as $field)
				{
					if (isset($row[$field])) {
						$this->$field = $row[$field];


						$this->log->debug("$this->object_name({$row['id']}): ".$field." = ".$this->$field);
					}else if (isset($row[$this->table_name .'.'.$field])) {
						$this->$field = $row[$this->table_name .'.'.$field];


						$this->log->debug("$this->object_name({$row[$this->table_name .'.'.'id']}): ".$field." = ".$this->$field);

					}
					else
					{
						$this->$field = "";
					}
				}

				$this->fill_in_additional_list_fields();

				$list[] = $this;
			}
		}

		$response = Array();
		$response['list'] = $list;
		$response['row_count'] = $rows_found;
		$response['next_offset'] = $next_offset;
		$response['previous_offset'] = $previous_offset;

		return $response;
	}

	function process_full_list_query($query, $check_date=false)
	{
		
		$this->log->debug("process_full_list_query: query is ".$query);
		$result =& $this->db->query($query, false);
		$this->log->debug("process_full_list_query: result is ".$result);

		if($this->db->getRowCount($result) > 0){

			// We have some data.
			while ($row = $this->db->fetchByAssoc($result)) {
				foreach($this->list_fields as $field)
				{
					if (isset($row[$field])) {
						$this->$field = $row[$field];

						$this->log->debug("process_full_list: $this->object_name({$row['id']}): ".$field." = ".$this->$field);
					}else {
						$this->$field = '';
					}
				}
				if($check_date){
					$this->processed_dates_times = array();
					$this->check_date_relationships_load();
				}
					$this->fill_in_additional_list_fields();
				
				$list[] = $this;
			}
		}

		if (isset($list)) return $list;
		else return null;
	}

	/**
	* Track the viewing of a detail record.  This leverages get_summary_text() which is object specific
	* params $user_id - The user that is viewing the record.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	* All Rights Reserved..
	* Contributor(s): ______________________________________..
	*/
	function track_view($user_id, $current_module)
	{
		$this->log->debug("About to call tracker (user_id, module_name, item_id)($user_id, $current_module, $this->id)");

		$tracker = new Tracker();
		$tracker->track_view($user_id, $current_module, $this->id, $this->get_summary_text());
	}

	/**
	* return the summary text that should show up in the recent history list for this object.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	* All Rights Reserved..
	* Contributor(s): ______________________________________..
	*/
	function get_summary_text()
	{
		return "Base Implementation.  Should be overridden.";
	}

	/**
	* This is designed to be overridden and add specific fields to each record.  This allows the generic query to fill in
	* the major fields, and then targetted queries to get related fields and add them to the record.  The contact's account for instance.
	* This method is only used for populating extra fields in lists
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	* All Rights Reserved..
	* Contributor(s): ______________________________________..
	*/
	function fill_in_additional_list_fields()
	{
	}

	/**
	* This is designed to be overridden and add specific fields to each record.  This allows the generic query to fill in
	* the major fields, and then targetted queries to get related fields and add them to the record.  The contact's account for instance.
	* This method is only used for populating extra fields in the detail form
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	* All Rights Reserved..
	* Contributor(s): ______________________________________..
	*/
	function fill_in_additional_detail_fields()
	{
	}

	/**
	* This is a helper class that is used to quickly created indexes when createing tables
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	* All Rights Reserved..
	* Contributor(s): ______________________________________..
	*/
	function create_index($query)
	{
		$this->log->info($query);

		$result =& $this->db->query($query, true, "Error creating index:");
	}

	/** This function should be overridden in each module.  It marks an item as deleted.
	* If it is not overridden, then marking this type of item is not allowed
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	* All Rights Reserved..
	* Contributor(s): ______________________________________..
	*/
	function mark_deleted($id)
	{
		$query = "UPDATE $this->table_name set deleted=1 where id='$id'";
		$this->db->query($query, true,"Error marking record deleted: ");

		$this->mark_relationships_deleted($id);

		// Take the item off of the recently viewed lists.
		$tracker = new Tracker();
		$tracker->delete_item_history($id);

	}

	/** This function deletes relationships to this object.  It should be overridden to handle the relationships of the specific object.
	* This function is called when the item itself is being deleted.  For instance, it is called on Contact when the contact is being deleted.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	* All Rights Reserved..
	* Contributor(s): ______________________________________..
	*/
	function mark_relationships_deleted($id)
	{

	}

	/**
	* This function is used to execute the query and create an array template objects from the resulting ids from the query.
	* It is currently used for building sub-panel arrays.
	* param $query - the query that should be executed to build the list
	* param $template - The object that should be used to copy the records.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	* All Rights Reserved..
	* Contributor(s): ______________________________________..
	*/
	function build_related_list($query, &$template)
	{

		$this->log->debug("Finding linked records $this->object_name: ".$query);

		$result =& $this->db->query($query, true);

		$list = Array();

		while($row = $this->db->fetchByAssoc($result))
		{
			$record = $template->retrieve($row['id']);

			if($record != null)
			{
				// this copies the object into the array
				$list[] = $template;
			}
		}

		return $list;
	}
	
	
		/**
	* This function is used to execute the query and create an array template objects from the resulting ids from the query.
	* It is currently used for building sub-panel arrays. It supports an additional where clause that is executed as a filter on the results
	* 
	* param $query - the query that should be executed to build the list
	* param $template - The object that should be used to copy the records.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	* All Rights Reserved..
	* Contributor(s): ______________________________________..
	*/
	function build_related_list_where($query, &$template, $where='', $in='', $order_by)
	{
		// No need to do an additional query

		$this->log->debug("Finding linked records $this->object_name: ".$query);
		if(empty($in) && !empty($query)){
			
			$idList = $this->build_related_in($query);
			$in = $idList['in'];
		}
		$query = "SELECT id FROM $this->table_name WHERE deleted=0 AND id IN $in";
		if(!empty($where)){
			$query .= "AND $where";	
		}
		if(!empty($order_by)){
			$query .= "ORDER BY $order_by";		
		}
		
		$result =& $this->db->query($query, true);
		
		$list = Array();	
		while($row = $this->db->fetchByAssoc($result))
		{
			
			$record = $template->retrieve($row['id']);

			if($record != null)
			{
				// this copies the object into the array
				$list[] = $template;
			}
		}

		return $list;
	}
	
	
	
	
	
	
	function build_related_in($query)
	{
		$idList = array();
		$result =& $this->db->query($query, true);
		$ids = '';
		while($row = $this->db->fetchByAssoc($result))
		{
			$idList[] = $row['id'];
			if(empty($ids)){
				$ids = "('" . $row['id'] . "'";
			}else{
				$ids .= ",'" . $row['id'] . "'";	
			}
		}
		if(empty($ids)){
			$ids = '(';	
		}
		$ids .= ')';
		return array('list'=>$idList, 'in'=>$ids);
	}

	/**
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	* All Rights Reserved..
	* Contributor(s): ______________________________________..
	*/
	function build_related_list2($query, &$template, &$field_list)
	{

		$this->log->debug("Finding linked values $this->object_name: ".$query);

		$result =& $this->db->query($query, true);

		$list = Array();

		while($row = $this->db->fetchByAssoc($result))
		{
			// Create a blank copy
			$copy = $template;

			foreach($field_list as $field)
			{
				// Copy the relevant fields
				$copy->$field = $row[$field];

			}


			// this copies the object into the array
			$list[] = $copy;
		}

		return $list;
	}

	// only used by Meeting and Call SugarBeans
	//
	function build_related_list_by_user_id($user_id,$where)
	{
		$bean_id_name = strtolower($this->object_name).'_id';

		$query = "SELECT {$this->table_name}.* from {$this->table_name}, {$this->rel_users_table} WHERE ";

		if (! empty($where))
		{
			$query .= $where. ' AND ';
		}

		$query .= " {$this->rel_users_table}.{$bean_id_name}={$this->table_name}.id AND {$this->rel_users_table}.user_id='{$user_id}' AND {$this->table_name}.deleted=0";

		$result =& $this->db->query($query, true);

		$list = Array();

		while($row = $this->db->fetchByAssoc($result))
		{
			foreach($this->column_fields as $field)
			{
				if(isset($row[$field]))
				{
					$this->$field = $row[$field];
				}
				else
				{
					$this->$field = '';
				}
			}
			$this->processed_dates_times = array();
			$this->check_date_relationships_load();
			$list[] = $this;
		}

		return $list;

	}


	/* This is to allow subclasses to fill in row specific columns of a list view form */
	function list_view_parse_additional_sections(&$list_form)
	{
	}

	/* This function assigns all of the values into the template for the list view */
	function get_list_view_array(){
		$return_array = Array();


		foreach($this->list_fields as $field)
		{



				if(isset($this->$field))
					$return_array[strtoupper($field)] = $this->$field;
				if(isset($this->custom_fields)){
					if($this->custom_fields->getType($field) == 'bool'){
						if($this->$field == '1'){
							$return_array[strtoupper($field . '_CHECKED')] = 'checked';
					}
					}
				}
					
		}

			return $return_array;
	}
	function get_list_view_data()
	{

		return $this->get_list_view_array();
	}

	function get_where(&$fields_array)
	{
		$where_clause = "WHERE ";
		$first = 1;
		foreach ($fields_array as $name=>$value)
		{
			if ($first)
			{
				$first = 0;
			}
			else
			{
				$where_clause .= " AND ";
			}

			$where_clause .= "$name = '".PearDatabase::quote($value)."'";
		}

		$where_clause .= " AND deleted=0";
		return $where_clause;
	}


	function retrieve_by_string_fields($fields_array, $encode=true)
	{
		$where_clause = $this->get_where($fields_array);
		if(isset($this->custom_fields))
			$custom_join = $this->custom_fields->getJOIN();
		else $custom_join = false;
		if($custom_join){
			$query = "SELECT $this->table_name.*". $custom_join['select']. " FROM $this->table_name " . $custom_join['join'];
		}else{
			$query = "SELECT $this->table_name.* FROM $this->table_name ";	
		}
		$query .= " $where_clause";
		$this->log->debug("Retrieve $this->object_name: ".$query);
		$result =& $this->db->requireSingleResult($query, true, "Retrieving record $where_clause:");
		
		if( empty($result))
		{
			return null;
		}
		if($this->db->getRowCount($result) > 1){
			$this->duplicates_found = true;
		}

		$row = $this->db->fetchByAssoc($result, -1, $encode);

		foreach($this->column_fields as $field)
		{
			if(isset($row[$field]))
			{
				$this->$field = $row[$field];
			}
		}
		$this->fill_in_additional_detail_fields();
		return $this;
	}

	// this method is called during an import before inserting a bean
	// define an associative array called $special_fields
	// the keys are user defined, and don't directly map to the bean's fields
	// the value is the method name within that bean that will do extra
	// processing for that field. example: 'full_name'=>'get_names_from_full_name'

	function process_special_fields()
	{
		foreach ($this->special_functions as $func_name)
		{
			if ( method_exists($this,$func_name) )
			{
				$this->$func_name();
			}
		}
	}
	/**
	builds a generic search based on the query string using or
	do not include any $this-> because this is called on without having the class instantiated
	*/
	function build_generic_where_clause($value){
		$where_clause = "WHERE ";
		$first = 1;
		foreach ($fields_array as $name=>$value)
		{
			if ($first)
			{
				$first = 0;
			}
			else
			{
				$where_clause .= " or";
			}

			$where_clause .= "$name = '".PearDatabase::quote($value)."'";
		}

		$where_clause .= " AND deleted=0";
		return $where_clause;
	}




















	function parse_additional_headers(&$list_form, $xTemplateSection) {
		return $list_form;

	}

	function assign_display_fields($currentModule){
		$timedate = new TimeDate();
		foreach($this->column_fields as $field){
			if(isset($this->field_name_map[$field]) && empty($this->$field)){
				if($this->field_name_map[$field]['type'] != 'date' && $this->field_name_map[$field]['type'] != 'enum')
				$this->$field = $field;
				if($this->field_name_map[$field]['type'] == 'date'){
					$this->$field = $timedate->to_display_date('1980-07-09');
				}
				if($this->field_name_map[$field]['type'] == 'enum'){
					$dom = $this->field_name_map[$field]['options'];
					global $current_language, $app_list_strings;
					$mod_strings = return_module_language($current_language, $currentModule);

					if(isset($mod_strings[$dom])){
						$options = $mod_strings[$dom];
						foreach($options as $key=>$value){
							if(!empty($key) && empty($this->$field )){
								$this->$field = $key;
							}
						}
					}
					if(isset($app_list_strings[$dom])){
						$options = $app_list_strings[$dom];
						foreach($options as $key=>$value){
							if(!empty($key) && empty($this->$field )){
								$this->$field = $key;
							}
						}
					}


				}
			}

		}









	}


	// called as a special_function from an Import when saving
	function add_created_modified_dates()
	{
		if ( isset ($this->date_entered_only))
		{
			$mysql_date_str = getSQLDate($this->date_entered_only);
			if ( ! empty($mysql_date_str))
			{
				if ( isset ($this->time_entered_only))
				{
					$this->date_entered = $mysql_date_str . " " . $this->time_entered_only;
				}
				else
				{
					$this->date_entered = $mysql_date_str . " 00:00:00";
				}

			}
		}


		if ( isset ($this->date_modified_only))
		{
			$mysql_date_str = getSQLDate($this->date_modified_only);
			if ( ! empty($mysql_date_str))
			{
				if ( isset ($this->time_modified_only))
				{
					$this->date_modified = $mysql_date_str . " " . $this->time_modified_only;
				}
				else
				{
					$this->date_modified = $mysql_date_str . " 00:00:00";
				}

			}
		}

		if ( ! isset ( $this->date_modified) && isset ( $this->date_entered))
		{
			$this->date_modified = $this->date_entered;
		}
		else if ( ! isset ( $this->date_entered) && isset ( $this->date_modified))
		{
			$this->date_entered = $this->date_modified;
		}

	}
	
	/*
	 * 	RELATIONSHIP HANDLING
	 */
	 
	function set_relationship($table, $relate_values, $check_duplicates = true,$do_update=false,$data_values=null){
		$where = '';
		if($check_duplicates){
			$query = "SELECT * FROM $table ";
			$where = "WHERE deleted = '0'  ";
			foreach($relate_values as $name=>$value){
				$where .= " AND $name = '$value' ";
			}
			$query .= $where;
			$result =& $this->db->query($query, false, "Looking For Duplicate Relationship:" . $query);
		}

		if(!$check_duplicates || $this->db->getRowCount($result) < 1){
			unset($relate_values['id']);
			if ( isset($data_values))
			{
				$relate_values = array_merge($relate_values,$data_values);
      }
      $query = "INSERT INTO $table (id, ". implode(',', array_keys($relate_values)) . ") VALUES ('" . create_guid() . "', " . "'" . implode("', '", $relate_values) . "')" ;

			$this->db->query($query, false, "Creating Relationship:" . $query);
		}
		else if ($do_update)
		{
			$conds = array();
			foreach($data_values as $key=>$value)
			{
				array_push($conds,$key."='".PearDatabase::quote(from_html($value))."'");
			}
			$query = "UPDATE $table SET ". implode(',', $conds)." " .$where;
			$this->db->query($query, false, "Updating Relationship:" . $query);
		}
	}

	 
	 function retrieve_relationships($table, $values, $select_id){
	 	$query = "SELECT $select_id FROM $table WHERE deleted = 0  ";
	 	foreach($values as $name=>$value){
	 		$query .= " AND $name = '$value' ";	
	 	}	
	 	$result =& $this->db->query($query, false, "Retrieving Relationship:" . $query);
	 	$ids = array();
	 	while($row =& $this->fetchByAssoc($result )){
	 			$ids[] = $row;
	 	}
	 	return $ids;
	 }
	 
	 function clear_relationship($table, $where){
	 	$query = "UPDATE $table SET deleted = 1 WHERE deleted = 0  ";
	 	foreach($values as $name=>$value){
	 		$query .= " AND $name = '$value' ";	
	 	}	
	 	$this->db->query($query, false, "Clearing Relationship:" . $query);	
	 }


}

?>
