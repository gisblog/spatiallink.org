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
 * $Id: Meeting.php,v 1.119.2.1 2005/05/09 20:56:37 ajay Exp $
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
require_once('modules/Calendar/DateTime.php');

// Meeting is used to store customer information.
class Meeting extends SugarBean {
	// Stored fields
	var $id;
	var $date_entered;
	var $date_modified;
	var $assigned_user_id;
	var $modified_user_id;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;



	var $description;
	var $name;
	var $location;
	var $status;
	var $date_start;
	var $time_start;
	var $date_end;
	var $duration_hours;
	var $duration_minutes;
	var $parent_type;
	var $parent_id;
	var $field_name_map;
	var $contact_id;
	var $user_id;
	var $reminder_time;

	var $required;
	var $accept_status;

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
  // when assoc w/ a user/contact:


	var $default_meeting_name_values = array('Follow-up on proposal', 'Initial discussion', 'Review needs', 'Discuss pricing', 'Demo', 'Introduce all players', );
    var $minutes_values = array('0'=>'00','15'=>'15','30'=>'30','45'=>'45');

	var $table_name = "meetings";
	var $rel_users_table = "meetings_users";
	var $rel_contacts_table = "meetings_contacts";
	var $module_dir = "Meetings";
	var $object_name = "Meeting";
	var $required_fields =  array("name"=>1, "date_start"=>2, "time_start"=>3, "duration_hours"=>4);
	var $column_fields = Array("id"
		, "date_entered"
		, "date_modified"
		, "assigned_user_id"
		, "modified_user_id"
		, "created_by"



		, "description"
		, "name"
		, "status"
		, "location"
		, "date_start"
		, "time_start"
		, "date_end"
		, "duration_hours"
		, "duration_minutes"
		, "parent_type"
		, "parent_id"
		, 'reminder_time'
		);


	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('assigned_user_name', 'assigned_user_id', 'contact_id', 'user_id', 'contact_name');

	// This is the list of fields that are in the lists.
	var $list_fields = Array('id', 'location', 'duration_hours', 'name', 'status', 'parent_type', 'parent_name', 'parent_id', 'date_start', 'time_start', 'assigned_user_name', 'assigned_user_id', 'contact_name', 'contact_id','first_name','last_name','required','accept_status','duration_minutes'




		);

	// so you can run get_users() twice and run query only once
	var $cached_get_users = null;

	function Meeting() {
		$this->log = LoggerManager::getLogger('meeting');
		parent::SugarBean();
        $this->setupCustomFields('Meetings');
		foreach ($this->field_defs as $field)
		{
			$this->field_name_map[$field['name']] = $field;
		}



	}

	var $new_schema = true;

	

	/** Returns a list of the associated contacts
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_contacts()
	{
		// First, get the list of IDs.
		$query = "SELECT contact_id as id from meetings_contacts where meeting_id='$this->id' AND deleted=0";

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
		$query = "SELECT user_id as id from meetings_users where meeting_id='$this->id' AND deleted=0";
		return $this->build_related_list($query, new User());

		if ( $this->cached_get_users == null)
		{
		 	$this->cached_get_users = $this->build_related_list($query, new User());
		}

		return  $this->cached_get_users;
	}

	// save date_end by calculating user input
	// this is for calendar
	function save($check_notify = FALSE)
	{

		if ( isset($this->date_start)
			&& isset($this->time_start)
			&& isset($this->duration_hours)
			&& isset($this->duration_minutes)
		)
		{
			$date_time_start = DateTime::get_time_start($this->date_start,$this->time_start.":00");
			$date_time_end = DateTime::get_time_end($date_time_start,$this->duration_hours,$this->duration_minutes);
			$this->date_end = "{$date_time_end->year}-{$date_time_end->month}-{$date_time_end->day}";

		}

    if ( ! empty($_REQUEST['send_invites']) && $_REQUEST['send_invites'] == '1')
    {
     $check_notify = true;
    }
    else
    {
     $check_notify = false;
    }
		parent::save($check_notify);


		global $current_user;
		require_once('modules/vCals/vCal.php');
		if ( $this->update_vcal )
		{
			vCal::cache_sugar_vcal($current_user);
		}
	}

	// this is for calendar
	function mark_deleted($id)
	{
		parent::mark_deleted($id);
		global $current_user;
		require_once('modules/vCals/vCal.php');
		if ( $this->update_vcal )
		{
			vCal::cache_sugar_vcal($current_user);
		}
        }


	function save_relationship_changes($is_update)
    	{

	if($this->account_id != "")
    	{
    		$this->set_meetings_account_relationship($this->id, $this->account_id);
    	}
		if($this->opportunity_id != "")
    	{
    		$this->set_meetings_opportunity_relationship($this->id, $this->opportunity_id);
    	}
		if($this->case_id != "")
    	{
    		$this->set_meetings_case_relationship($this->id, $this->case_id);
    	}
		if($this->contact_id != "")
    	{
    		$this->mark_meeting_contact_relationship_deleted($this->contact_id, $this->id);
    		$this->set_meetings_contact_invitee_relationship($this->id, $this->contact_id);
    	}
		if($this->user_id != "")
    	{
    		$this->mark_meeting_user_relationship_deleted($this->user_id, $this->id);
    		$this->set_meetings_user_invitee_relationship($this->id, $this->user_id);
    	}
		if($this->assigned_user_id != "")
    	{
    		$this->mark_meeting_user_relationship_deleted($this->assigned_user_id, $this->id);
    		$this->set_meetings_user_invitee_relationship($this->id, $this->assigned_user_id);
    	}
    }

	function set_meetings_account_relationship($meeting_id, $account_id)
	{
		$query = "update $this->table_name set parent_id='$account_id', parent_type='Accounts' where id='$meeting_id'";
		$this->db->query($query,true,"Error setting account to meeting relationship: "."<BR>$query");
	}

	function set_meetings_opportunity_relationship($meeting_id, $opportunity_id)
	{
		$query = "update $this->table_name set parent_id='$opportunity_id', parent_type='Opportunities' where id='$meeting_id'";
		$this->db->query($query,true,"Error setting opportunity to meeting relationship: "."<BR>$query");
	}

	function set_meetings_case_relationship($meeting_id, $case_id)
	{
		$query = "update $this->table_name set parent_id='$case_id', parent_type='Cases' where id='$meeting_id'";
		$this->db->query($query,true,"Error setting case to meeting relationship: "."<BR>$query");
	}

	function set_meetings_contact_invitee_relationship($meeting_id, $contact_id)
	{
		$this->set_relationship($this->rel_contacts_table , array('meeting_id'=>$meeting_id ,'contact_id'=>$contact_id));
	}

	function set_meetings_user_invitee_relationship($meeting_id, $user_id)
	{
		$this->set_relationship($this->rel_users_table , array('meeting_id'=>$meeting_id ,'user_id'=>$user_id));
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
		
		return 'SELECT  COUNT(DISTINCT(meetings.id)) FROM ' . $query[0] ;
	}

	function create_list_query(&$order_by, &$where)
	{
		$custom_join = $this->custom_fields->getJOIN();

                $query = "SELECT  DISTINCT ";


		$query .= "
				meetings.id,
				meetings.assigned_user_id,
				meetings.status,
				meetings.name,
				meetings.parent_type,
				meetings.parent_id,
				meetings.date_start,
				meetings.time_start,
				meetings.duration_hours,
				meetings.duration_minutes,";
				if ( preg_match("/meetings_users\.user_id/",$where))
				{
					$query .= "meetings_users.required,
						meetings_users.accept_status,";
				}
			$query .= "contacts.first_name,
				contacts.last_name,
                users.user_name as assigned_user_name";



                if($custom_join){
   					$query .= $custom_join['select'];
 				}
				$query .= " FROM meetings ";





		$query .= "				LEFT JOIN meetings_users
                            	ON meetings_users.meeting_id=meetings.id
								LEFT JOIN meetings_contacts
                            	ON meetings_contacts.meeting_id= meetings.id";



                            	$query .= " LEFT JOIN contacts
                            	ON meetings_contacts.contact_id=contacts.id
                            	LEFT JOIN users
                            	ON meetings.assigned_user_id=users.id ";
                            	if($custom_join){
  									$query .= $custom_join['join'];
								}

	
		$where_auto = " meetings.deleted=0
                                    AND (contacts.deleted IS NULL OR contacts.deleted=0) ";

		
		

		if($where != "")
			$query .= "where $where AND ".$where_auto;
		else
			$query .= "where ".$where_auto;

		if($order_by != "")
			$query .= " ORDER BY $order_by";
		else
			$query .= " ORDER BY meetings.name";
		return $query;

	}

        function create_export_query(&$order_by, &$where)
        {
                $contact_required = ereg("contacts", $where);
				$custom_join = $this->custom_fields->getJOIN();
                if($contact_required)
                {
                        $query = "SELECT meetings.*, contacts.first_name, contacts.last_name";



                      	if($custom_join){
   							$query .= $custom_join['select'];
 						}
                        $query .= " FROM contacts, meetings, meetings_contacts ";
                        $where_auto = "meetings_contacts.contact_id = contacts.id AND meetings_contacts.meeting_id = meetings.id AND meetings.deleted=0 AND contacts.deleted=0";
                }
                else
                {
                        $query = 'SELECT meetings.*';



                        if($custom_join){
   							$query .= $custom_join['select'];
 						}
                        $query .= ' FROM meetings ';
                        $where_auto = "meetings.deleted=0";
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
                        $query .= " ORDER BY meetings.name";

                return $query;

        }



	function fill_in_additional_list_fields()
	{
		$this->fill_in_additional_detail_fields();
		//$this->fill_in_additional_parent_fields();
	}

	function fill_in_additional_detail_fields()
	{
		// Fill in the assigned_user_name
		$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);




		$query  = "SELECT contacts.first_name, contacts.last_name, contacts.phone_work, contacts.email1, contacts.id FROM contacts, meetings_contacts ";
		$query .= "WHERE meetings_contacts.contact_id=contacts.id AND meetings_contacts.meeting_id='$this->id' AND meetings_contacts.deleted=0 AND contacts.deleted=0";
		$result =$this->db->query($query,true," Error filling in additional detail fields: ");

		// Get the id and the name.
		$row = $this->db->fetchByAssoc($result);

		$this->log->info($row);

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

		$this->created_by_name = get_assigned_user_name($this->created_by);
		$this->modified_by_name = get_assigned_user_name($this->modified_user_id);

		$this->fill_in_additional_parent_fields();
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
		$query = "delete from $this->rel_users_table where meeting_id='$id'";
		$this->db->query($query,true,"Error marking record deleted: ");

		$query = "delete from $this->rel_contacts_table where meeting_id='$id'";
		$this->db->query($query,true,"Error marking record deleted: ");
	}

	function mark_meeting_contact_relationship_deleted($contact_id, $meeting_id)
	{
		$query = "delete from $this->rel_contacts_table where contact_id='$contact_id' and meeting_id='$meeting_id' and deleted=0";
		$this->db->query($query,true,"Error clearing meeting to contact relationship: ");
	}

	function mark_meeting_user_relationship_deleted($user_id, $meeting_id)
	{
		$query = "delete from $this->rel_users_table where user_id='$user_id' and meeting_id='$meeting_id' and deleted=0";
		$this->db->query($query,true,"Error clearing meeting to user relationship: ");
	}
	function get_list_view_data(){
		$meeting_fields = $this->get_list_view_array();
		global $app_list_strings, $focus, $action, $currentModule, $image_path;
		if (isset($this->parent_type))
			$meeting_fields['PARENT_MODULE'] = $this->parent_type;
		if ($this->status == "Planned") {
			$meeting_fields['SET_COMPLETE'] = "<a href='index.php?return_module=$currentModule&return_action=$action&return_id=$this->id&action=EditView&status=Held&module=Meetings&record=$this->id&status=Held'>".get_image($image_path."close_inline","alt='Close' border='0'")."</a>";
		}
		$td =& new TimeDate();
		$today = date('Y-m-d H:i:s', time());
		$nextday = date('Y-m-d', time() + 3600*24);
		$mergeTime = $td->merge_date_time($meeting_fields['DATE_START'], $meeting_fields['TIME_START']);
		$date_db = $td->to_db($mergeTime);
		if( $date_db	< $today	){
			$meeting_fields['DATE_START']= "<font class='overdueTask'>".$meeting_fields['DATE_START']."</font>";
		}else if( $date_db	< $nextday ){
			$meeting_fields['DATE_START'] = "<font class='todaysTask'>".$meeting_fields['DATE_START']."</font>";
		}else{
			$meeting_fields['DATE_START'] = "<font class='futureTask'>".$meeting_fields['DATE_START']."</font>";
		}
		$meeting_fields['CONTACT_NAME'] = return_name($meeting_fields,'FIRST_NAME','LAST_NAME');
		return $meeting_fields;
	}

	function parse_additional_headers(&$list_form, $xTemplateSection) {

	}

	function list_view_parse_additional_sections(&$list_form, $xTemplateSection) {
		return $list_form;
	}


  function set_notification_body($xtpl, &$meeting)
  {
    global $sugar_config;
	global $app_list_strings;		
    
    $xtpl->assign("ACCEPT_URL", $sugar_config['site_url'].
      '/acceptDecline.php?module=Meetings&user_id='.$meeting->current_notify_user->id.'&record='.$meeting->id);
    $xtpl->assign("MEETING_TO", $meeting->current_notify_user->new_assigned_user_name);
    $xtpl->assign("MEETING_SUBJECT", $meeting->name);
    $xtpl->assign("MEETING_STATUS",(isset($meeting->status)? $app_list_strings['meeting_status_dom'][$meeting->status]:"" ));
    $xtpl->assign("MEETING_STARTDATE", $meeting->date_start . " " . $meeting->time_start);
    $xtpl->assign("MEETING_HOURS", $meeting->duration_hours);
    $xtpl->assign("MEETING_MINUTES", $meeting->duration_minutes);
    $xtpl->assign("MEETING_DESCRIPTION", $meeting->description);

    return $xtpl;
  }

  function get_meeting_users()
  {
    $template = new User();
    // First, get the list of IDs.
    $query = "SELECT meetings_users.required, meetings_users.accept_status, meetings_users.user_id from meetings_users where meetings_users.meeting_id='$this->id' AND meetings_users.deleted=0";
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

  function get_invite_meetings(&$user)
  {
    $template = $this;
    // First, get the list of IDs.
    $query = "SELECT meetings_users.required, meetings_users.accept_status, meetings_users.meeting_id from meetings_users where meetings_users.user_id='$user->id' AND ( meetings_users.accept_status IS NULL OR  meetings_users.accept_status='none') AND meetings_users.deleted=0";
    $this->log->debug("Finding linked records $this->object_name: ".$query);


    $result =& $this->db->query($query, true);


    $list = Array();


    while($row = $this->db->fetchByAssoc($result))
    {
      $record = $template->retrieve($row['meeting_id']);
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
    $relate_values = array('user_id'=>$user->id,'meeting_id'=>$this->id);
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
