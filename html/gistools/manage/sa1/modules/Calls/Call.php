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
 * $Id: Call.php,v 1.108.2.1 2005/05/09 20:54:47 ajay Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');
require_once('modules/Contacts/Contact.php');
require_once('modules/Users/User.php');

// Call is used to store customer information.
class Call extends SugarBean {
	var $field_name_map;

	// Stored fields
	var $id;
	var $date_entered;
	var $date_modified;
	var $assigned_user_id;
	var $modified_user_id;



	var $description;
	var $name;
	var $status;
	var $date_start;
	var $time_start;
	var $duration_hours;
	var $duration_minutes;
	var $date_end;
	var $parent_type;
	var $parent_id;
	var $contact_id;
	var $user_id;
	var $direction;
	var $reminder_time;

  var $required;
  var $accept_status;

	var $created_by;
	var $created_by_name;
	var $modified_by_name;

	var $parent_name;
	var $contact_name;
	var $contact_phone;
	var $contact_email;
	var $account_id;
	var $opportunity_id;
	var $case_id;
	var $assigned_user_name;



	var $update_vcal = true;
  var $contacts_arr;
  var $users_arr;


	var $default_call_name_values = array('Assemble catalogs', 'Make travel arrangements', 'Send a letter', 'Send contract', 'Send fax', 'Send a follow-up letter', 'Send literature', 'Send proposal', 'Send quote');
	var $minutes_value_default = 15;
	var $minutes_values = array('0'=>'00','15'=>'15','30'=>'30','45'=>'45');
	var $required_fields =  array("name"=>1, "date_start"=>2, "time_start"=>3,);
	var $table_name = "calls";
	var $rel_users_table = "calls_users";
	var $rel_contacts_table = "calls_contacts";
	var $module_dir = 'Calls';
	var $object_name = "Call";

	var $column_fields = Array("id"
		, "date_entered"
		, "date_modified"
		, "assigned_user_id"
		, "modified_user_id"
		, "created_by"



		, "description"
		, "status"
		, "direction"
		, "name"
		, "date_start"
		, "time_start"
		, "duration_hours"
		, "duration_minutes"
		, "date_end"
		, "parent_type"
		, "parent_id"
		,'reminder_time'
		);


	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('assigned_user_name', 'assigned_user_id', 'contact_id', 'user_id', 'contact_name');

	// This is the list of fields that are in the lists.
	var $list_fields = Array('id', 'duration_hours', 'direction', 'status', 'name', 'parent_type', 'parent_name', 'parent_id', 'date_start', 'time_start', 'assigned_user_name', 'assigned_user_id', 'contact_name', 'contact_id','first_name','last_name','required','accept_status'




		);

	function Call() {
		parent::SugarBean();
		global $app_list_strings;
		$this->log = LoggerManager::getLogger('call');
		
	
       	$this->setupCustomFields('Calls');

		foreach ($this->field_defs as $field)
		{
			$this->field_name_map[$field['name']] = $field;

		}






	}

	var $new_schema = true;

	

	

        // save date_end by calculating user input
        // this is for calendar
        function save($check_notify = FALSE)
        {

                if ( isset($this->date_start)                         && isset($this->time_start)
                        && isset($this->duration_hours)
                        && isset($this->duration_minutes)
                )
                {
                        $date_time_start = DateTime::get_time_start($this->date_start,$this->time_start.":00");
                        $date_time_end = DateTime::get_time_end($date_time_start,$this->duration_hours,$this->duration_minutes);
                        $this->date_end = "{$date_time_end->year}-{$date_time_end->month}-{$date_time_end->day}";

                }

                parent::save($check_notify);
                global $current_user;
                require_once('modules/vCals/vCal.php');
                if ( $this->update_vcal )
                {
                  vCal::cache_sugar_vcal($current_user);
                }


	}

	/** Returns a list of the associated contacts
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_contacts()
	{
		// First, get the list of IDs.
		$query = "SELECT contact_id as id from calls_contacts where call_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new Contact());
	}

	/** Returns a list of the associated users
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_users()
	{
		// First, get the list of IDs.
		$query = "SELECT user_id as id from calls_users where call_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new User());
	}

	function save_relationship_changes($is_update)
    {
		if($this->account_id != "")
    	{
    		$this->set_calls_account_relationship($this->id, $this->account_id);
    	}
		if($this->opportunity_id != "")
    	{
    		$this->set_calls_opportunity_relationship($this->id, $this->opportunity_id);
    	}
		if($this->case_id != "")
    	{
    		$this->set_calls_case_relationship($this->id, $this->case_id);
    	}
		if($this->contact_id != "")
    	{
			$this->mark_call_contact_relationship_deleted($this->contact_id, $this->id);
    		$this->set_calls_contact_invitee_relationship($this->id, $this->contact_id);
    	}
		if($this->user_id != "")
    	{
			$this->mark_call_user_relationship_deleted($this->user_id, $this->id);
    		$this->set_calls_user_invitee_relationship($this->id, $this->user_id);
    	}
		if($this->assigned_user_id != "")
    	{
			$this->mark_call_user_relationship_deleted($this->assigned_user_id, $this->id);
    		$this->set_calls_user_invitee_relationship($this->id, $this->assigned_user_id);
    	}
    }

	function set_calls_account_relationship($call_id, $account_id)
	{
		$query = "update $this->table_name set parent_id='$account_id', parent_type='Accounts' where id='$call_id'";
		$this->db->query($query,true,"Error setting account to call relationship: "."<BR>$query");
	}

	function set_calls_opportunity_relationship($call_id, $opportunity_id)
	{
		$query = "update $this->table_name set parent_id='$opportunity_id', parent_type='Opportunities' where _id='$call_id'";
		$this->db->query($query,true,"Error setting opportunity to call relationship: "."<BR>$query");
	}

	function set_calls_case_relationship($call_id, $case_id)
	{
		$query = "update $this->table_name set parent_id='$case_id', parent_type='Cases' where _id='$call_id'";
		$this->db->query($query,true,"Error setting case to call relationship: "."<BR>$query");
	}

	function set_calls_contact_invitee_relationship($call_id, $contact_id)
	{
		$this->set_relationship($this->rel_contacts_table, array('contact_id'=> $contact_id , 'call_id'=>$call_id));
	}

	function set_calls_user_invitee_relationship($call_id, $user_id)
	{
		$this->set_relationship($this->rel_users_table, array('call_id'=> $call_id , 'user_id'=>$user_id));
	}

	function get_summary_text()
	{
		return "$this->name";
	}
	function create_list_count_query($where)
	{
		$orderby = '';
		$query = $this->create_list_query($orderby, $where);
		$query = explode('FROM', $query);
		if(sizeof($query) == 1){
			$query = explode('from', $query[0]);	
		}
		$query = explode( 'ORDER BY',$query[1]);
		
		return 'SELECT  COUNT(DISTINCT(calls.id)) FROM ' . $query[0] ;
	}

	function create_list_query(&$order_by, &$where)
	{
		$custom_join = $this->custom_fields->getJOIN();
                $query = "SELECT DISTINCT ";
		$query .= "
			calls.id,
			calls.name,
			calls.assigned_user_id,
			calls.status,
			calls.direction,
			calls.parent_type,
			calls.parent_id,
			calls.date_start,
			calls.time_start,
			calls.duration_hours,
			calls.duration_minutes,";
			if ( preg_match("/calls_users\.user_id/",$where))
			{
				$query .= "calls_users.required,
				calls_users.accept_status,";
			}

			$query .= "contacts.first_name,
			contacts.last_name,
			users.user_name as assigned_user_name";



			if($custom_join){
   				$query .= $custom_join['select'];
 			}
			$query .= " FROM calls ";






		$query .= "LEFT JOIN calls_users
			ON calls.id=calls_users.call_id
			LEFT JOIN calls_contacts
			ON calls.id=calls_contacts.call_id";



			$query .= " LEFT JOIN contacts
			ON calls_contacts.contact_id=contacts.id
			LEFT JOIN users
			ON calls.assigned_user_id=users.id ";
			if($custom_join){
  				$query .= $custom_join['join'];
			}
			
       		 $where_auto = " calls.deleted=0
			AND (contacts.deleted IS NULL OR contacts.deleted=0) ";

			//$where_auto .= " GROUP BY calls.id";

		if($where != "")
			$query .= "where $where AND ".$where_auto;
		else
			$query .= "where ".$where_auto;

		if($order_by != "")
			$query .= " ORDER BY $order_by";
		else
			$query .= " ORDER BY calls.name";
		return $query;
	}

	function create_export_query(&$order_by, &$where)
        {
            $contact_required = ereg("contacts", $where);
			$custom_join = $this->custom_fields->getJOIN();
            if($contact_required)
            {
                    $query = "SELECT calls.*, contacts.first_name, contacts.last_name";



                    if($custom_join){
   						$query .= $custom_join['select'];
 					}
                    $query .= " FROM contacts, calls, calls_contacts ";
                    $where_auto = "calls_contacts.contact_id = contacts.id AND calls_contacts.call_id = calls.id AND calls.deleted=0 AND contacts.deleted=0";
            }
            else
            {
                    $query = 'SELECT calls.*';



                   	if($custom_join){
   						$query .= $custom_join['select'];
 					}
                    $query .= ' FROM calls ';
                    $where_auto = "calls.deleted=0";
            }









			if($custom_join){
  				$query .= $custom_join['join'];
			}

			if($where != "")
                    $query .= "where $where AND ".$where_auto;
            else
                    $query .= "where ".$where_auto;

            if($order_by != "")
                    $query .= " ORDER BY $order_by";
            else
                    $query .= " ORDER BY calls.name";

            return $query;
        }



	function fill_in_additional_list_fields()
	{
		$this->fill_in_additional_detail_fields();
//		$this->fill_in_additional_parent_fields();
	}

	function fill_in_additional_detail_fields()
	{
		// Fill in the assigned_user_name
		$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);




		$query  = "SELECT c.first_name, c.last_name, c.phone_work, c.email1, c.id FROM contacts  c, calls_contacts  c_c ";
		$query .= "WHERE c_c.contact_id=c.id AND c_c.call_id='$this->id' AND c_c.deleted=0 AND c.deleted=0";
		$result = $this->db->query($query,true," Error filling in additional detail fields: ");

		// Get the id and the name.
		$row = Array();
		$row = $this->db->fetchByAssoc($result);

		$this->log->info("additional call fields $query");

		if($row != null)
		{
			$this->contact_name = return_name($row, 'first_name', 'last_name');
			$this->contact_phone = $row['phone_work'];
			$this->contact_id = $row['id'];
			$this->contact_email = $row['email1'];
			$this->log->debug("Call($this->id): contact_name = $this->contact_name");
			$this->log->debug("Call($this->id): contact_phone = $this->contact_phone");
			$this->log->debug("Call($this->id): contact_id = $this->contact_id");
			$this->log->debug("Call($this->id): contact_email1 = $this->contact_email");
		}
		else {
			$this->contact_name = '';
			$this->contact_phone = '';
			$this->contact_id = '';
			$this->contact_email = '';
			$this->log->debug("Call($this->id): contact_name = $this->contact_name");
			$this->log->debug("Call($this->id): contact_phone = $this->contact_phone");
			$this->log->debug("Call($this->id): contact_id = $this->contact_id");
			$this->log->debug("Call($this->id): contact_email1 = $this->contact_email");
		}
		$this->fill_in_additional_parent_fields();

		$this->created_by_name = get_assigned_user_name($this->created_by);
		$this->modified_by_name = get_assigned_user_name($this->modified_user_id);
	}

	function fill_in_additional_parent_fields()
	{
		global $app_strings, $beanFiles, $beanList;
		if ( ! isset($beanList[$this->parent_type]))
		{
			$this->parent_name = '';
			return;
		}
	    	$beanType = $beanList[$this->parent_type];
		require_once($beanFiles[$beanType]);
		$parent = new $beanType();
		if ($this->parent_type == "Leads") {
			$query = "SELECT first_name, last_name from $parent->table_name where id = '$this->parent_id'";
		}
		else {
			$query = "SELECT name from $parent->table_name where id = '$this->parent_id'";
		}
		$result = $this->db->query($query,true," Error filling in additional detail fields: ");

		// Get the id and the name.
		$row = $this->db->fetchByAssoc($result);


		if ($this->parent_type == "Leads" and $row != null)
		{
			$this->parent_name = '';
			if ($row['first_name'] != '') $this->parent_name .= stripslashes($row['first_name']). ' ';
			if ($row['last_name'] != '') $this->parent_name .= stripslashes($row['last_name']);
		}
		elseif ($this->parent_type == "Issues") {
        	require_once("modules/Issues/Issue.php");
            $parent = new Issue();

            $query = "SELECT name from $parent->table_name where id = '$this->parent_id'";
			$result = $this->db->query($query, TRUE, "Error filling in additional detail fields: ");
			$row = $this->db->fetchByAssoc($result);

			if (!is_null($row)) {
 				$this->parent_name = '';
				if (!empty($row['name'])) $this->parent_name .= stripslashes($row['name']);
			}
		}
		elseif($row != null)
		{
			$this->parent_name = stripslashes($row['name']);
		}
		else {
			$this->parent_name = '';
		}
	}

	function mark_relationships_deleted($id)
	{
		$query = "delete from $this->rel_users_table where call_id='$id'";
		$this->db->query($query,true,"Error marking record deleted: ");

		$query = "delete from $this->rel_contacts_table where call_id='$id'";
		$this->db->query($query,true,"Error marking record deleted: ");
	}

	function mark_call_contact_relationship_deleted($contact_id, $call_id)
	{
		$query = "delete from $this->rel_contacts_table where contact_id='$contact_id' and call_id='$call_id' and deleted=0";
		$this->db->query($query,true,"Error clearing call to contact relationship: ");
	}

	function mark_call_user_relationship_deleted($user_id, $call_id)
	{
		$query = "delete from $this->rel_users_table where user_id='$user_id' and call_id='$call_id' and deleted=0";
		$this->db->query($query,true,"Error clearing call to user relationship: ");
	}
	function get_list_view_data(){
		$call_fields = $this->get_list_view_array();
		global $app_list_strings, $focus, $action, $currentModule, $image_path;
		if (isset($focus->id)) $id = $focus->id;
		else $id = '';
		if (isset($this->parent_type) && $this->parent_type != null)
		{
			$call_fields['PARENT_MODULE'] = $this->parent_type;
		}
		if ($this->status == "Planned") {
			$call_fields['SET_COMPLETE'] = "<a href='index.php?return_module=$currentModule&return_action=$action&return_id=$id&action=EditView&status=Held&module=Calls&record=$this->id&status=Held'>".get_image($image_path."close_inline","alt='Close' border='0'")."</a>";
		}
		if(!empty($call_fields['DIRECTION'])){
			$call_fields['DIRECTION'] = translate('call_direction_dom', '', 	$call_fields['DIRECTION']);
		}
		$td =& new TimeDate();
		$today = date('Y-m-d H:i:s', time());
		$nextday = date('Y-m-d', time() + 3600*24);
		$mergeTime = $td->merge_date_time($call_fields['DATE_START'], $call_fields['TIME_START']);
		$date_db = $td->to_db($mergeTime);
		if( $date_db	< $today){
			$call_fields['DATE_START']= "<font class='overdueTask'>".$call_fields['DATE_START']."</font>";
		}else if($date_db < $nextday){
			$call_fields['DATE_START'] = "<font class='todaysTask'>".$call_fields['DATE_START']."</font>";
		}else{
			$call_fields['DATE_START'] = "<font class='futureTask'>".$call_fields['DATE_START']."</font>";
		}
		$call_fields['CONTACT_NAME'] = return_name($call_fields,"FIRST_NAME","LAST_NAME");
		return $call_fields;
	}

	function parse_additional_headers(&$list_form, $xTemplateSection) {

	}

	function list_view_parse_additional_sections(&$list_form, $xTemplateSection) {
		return $list_form;
	}

	function set_notification_body($xtpl, $call)
	{
    global $sugar_config;
    global $app_list_strings;
    
    $xtpl->assign("ACCEPT_URL", $sugar_config['site_url'].
      '/acceptDecline.php?module=Calls&user_id='.$call->current_notify_user->id.'&record='.$call->id);
    $xtpl->assign("CALL_TO", $call->current_notify_user->new_assigned_user_name);
		$xtpl->assign("CALL_SUBJECT", $call->name);
		$xtpl->assign("CALL_STARTDATE", $call->date_start . " " . $call->time_start);
		$xtpl->assign("CALL_HOURS", $call->duration_hours);
		$xtpl->assign("CALL_MINUTES", $call->duration_minutes);
		$xtpl->assign("CALL_STATUS", ((isset($call->status))?$app_list_strings['call_status_dom'][$call->status] : ""));
		$xtpl->assign("CALL_DESCRIPTION", $call->description);

		return $xtpl;
	}


  function get_call_users()
  {
    $template = new User();
    // First, get the list of IDs.
    $query = "SELECT calls_users.required, calls_users.accept_status, calls_users.user_id from calls_users where calls_users.call_id='$this->id' AND calls_users.deleted=0";
    $this->log->debug("Finding linked records $this->object_name: ".$query);


    $result =& $this->db->query($query, true);


    $list = Array();


    while($row = $this->db->fetchByAssoc($result))
    {
      $record = $template->retrieve($row['user_id']);
      $template->required = $row['required'];
      $template->accept_status = $row['accept_status'];


      if($record != null)
      {
        // this copies the object into the array
        $list[] = $template;
      }
    }
    return $list;


  }


  function get_invite_calls(&$user)
  {
    $template = $this;
    // First, get the list of IDs.
    $query = "SELECT calls_users.required, calls_users.accept_status, calls_users.call_id from calls_users where calls_users.user_id='$user->id' AND ( calls_users.accept_status IS NULL OR  calls_users.accept_status='none') AND calls_users.deleted=0";
    $this->log->debug("Finding linked records $this->object_name: ".$query);


    $result =& $this->db->query($query, true);


    $list = Array();


    while($row = $this->db->fetchByAssoc($result))
    {
      $record = $template->retrieve($row['call_id']);
      $template->required = $row['required'];
      $template->accept_status = $row['accept_status'];


      if($record != null)
      {
        // this copies the object into the array
        $list[] = $template;
      }
    }
    return $list;

  }




  function set_accept_status(&$user,$status)
  {
    $relate_values = array('user_id'=>$user->id,'call_id'=>$this->id);
    $data_values = array('accept_status'=>$status);
    $this->set_relationship($this->rel_users_table, $relate_values, true, true,$data_values);
    global $current_user;
    require_once('modules/vCals/vCal.php');
    if ( $this->update_vcal )
    {
      vCal::cache_sugar_vcal($user);
    }
  }



  function get_notification_recipients()
  {
    $list = array();
    if ( ! is_array($this->contacts_arr))
    {
      $this->contacts_arr =  array();
    }
    if ( ! is_array($this->users_arr))
    {
      $this->users_arr =  array();
    }
    foreach ($this->contacts_arr as $contact_id)
    {
     $notify_user = new Contact();
     $notify_user->retrieve($contact_id);
     $notify_user->new_assigned_user_name = $notify_user->first_name.' '.$notify_user->last_name;
     $this->log->info("Notifications: recipient is $notify_user->new_assigned_user_name");
     array_push($list,$notify_user);
    }

    foreach ($this->users_arr as $user_id)
    {
     $notify_user = new User();
     $notify_user->retrieve($user_id);
     $notify_user->new_assigned_user_name = $notify_user->first_name.' '.$notify_user->last_name;
     $this->log->info("Notifications: recipient is $notify_user->new_assigned_user_name");
     array_push($list,$notify_user);
    }
    return $list;
  }





}
?>
