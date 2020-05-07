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
// $Id: Email.php,v 1.100.2.4 2005/05/17 19:17:03 andrew Exp $

include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');
require_once('modules/Contacts/Contact.php');
require_once('modules/Accounts/Account.php');
require_once('modules/Opportunities/Opportunity.php');
require_once('modules/Users/User.php');
require_once("include/phpmailer/class.phpmailer.php");
require_once("include/utils.php");

// Email is used to store customer information.
class Email extends SugarBean {
	var $field_name_map;

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
	var $duration_hours;
	var $duration_minutes;
	var $date_start;
	var $time_start;
	var $parent_type;
	var $parent_id;
	var $contact_id;
	var $user_id;

	var $parent_name;
	var $contact_name;
	var $contact_phone;
	var $contact_email;
	var $account_id;
	var $opportunity_id;
	var $case_id;
	var $assigned_user_name;




	var $from_addr;
	var $from_name;
	var $to_addrs;
    var $cc_addrs;
    var $bcc_addrs;
	var $to_addrs_arr;
    var $cc_addrs_arr;
    var $bcc_addrs_arr;
	var $to_addrs_ids;
	var $to_addrs_names;
	var $to_addrs_emails;
	var $cc_addrs_ids;
	var $cc_addrs_names;
	var $cc_addrs_emails;
	var $bcc_addrs_ids;
	var $bcc_addrs_names;
	var $bcc_addrs_emails;
    var $type = 'archived';
    var $status;

    var $attachments = array();
    var $attachment_image;



	var $default_email_subject_values = array('Follow-up on proposal', 'Initial discussion', 'Review needs', 'Discuss pricing', 'Demo', 'Introduce all players', );
	var $minutes_values = array('00', '15', '30', '45');

	var $table_name = "emails";
	var $rel_users_table = "emails_users";
	var $rel_contacts_table = "emails_contacts";
	var $rel_cases_table = "emails_cases";
	var $rel_accounts_table = "emails_accounts";
	var $rel_opportunities_table = "emails_opportunities";
	var $module_dir = 'Emails';
	var $object_name = "Email";

	var $column_fields = Array("id"
		, "date_entered"
		, "date_modified"
		, "assigned_user_id"
		, "modified_user_id"
		, "created_by"



		, "description"
		, "name"
		, "date_start"
		, "time_start"
		, "parent_type"
		, "parent_id"
		, "from_addr"
		, "from_name"
		, "to_addrs"
		, "cc_addrs"
		, "bcc_addrs"
		, "to_addrs_ids"
		, "to_addrs_names"
		, "to_addrs_emails"
		, "cc_addrs_ids"
		, "cc_addrs_names"
		, "cc_addrs_emails"
		, "bcc_addrs_ids"
		, "bcc_addrs_names"
		, "bcc_addrs_emails"
		, "type"
		, "status"
		);
	


	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('assigned_user_name', 'assigned_user_id', 'contact_id', 'user_id', 'contact_name','to_addrs_id');
	var $required_fields = array();
	// This is the list of fields that are in the lists.
	var $list_fields = Array('id', 'name', 'parent_type', 'parent_name', 'parent_id', 'date_start', 'time_start', 'assigned_user_name', 'assigned_user_id', 'contact_name', 'contact_id', 'first_name','last_name','to_addrs','from_addr','date_sent','type_name','type','status','link_action','date_entered','attachment_image'




		);

	function Email() {
		
		parent::SugarBean();





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
		$query = "SELECT contact_id as id from emails_contacts where email_id='$this->id' AND deleted=0";

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
		$query = "SELECT user_id as id from emails_users where email_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new User());
	}

	function save_relationship_changes($is_update)
    {
		if($this->account_id != "")
    	{
    		$this->set_emails_account_relationship($this->id, $this->account_id);
    	}
		if($this->opportunity_id != "")
    	{
    		$this->set_emails_opportunity_relationship($this->id, $this->opportunity_id);
    	}
		if($this->case_id != "")
    	{
    		$this->set_emails_case_relationship($this->id, $this->case_id);
    	}
		if($this->contact_id != "")
    	{
    		$this->set_emails_contact_invitee_relationship($this->id, $this->contact_id);
    	}
		if($this->user_id != "")
    	{
    		$this->set_emails_user_invitee_relationship($this->id, $this->user_id);
    	}
    }

	function set_emails_account_relationship($email_id, $account_id)
	{
		$this->set_relationship($this->rel_accounts_table, array('account_id' => $account_id , 'email_id' => $email_id));

	}

	function set_emails_opportunity_relationship($email_id, $opportunity_id)
	{
		$this->set_relationship($this->rel_opportunities_table, array('opportunity_id' => $opportunity_id , 'email_id' => $email_id));
	}

	function set_emails_case_relationship($email_id, $case_id)
	{
		$this->set_relationship($this->rel_cases_table, array('case_id' => $case_id , 'email_id' => $email_id));
	}

	function set_emails_contact_invitee_relationship($email_id, $contact_id)
	{
		$this->set_relationship($this->rel_contacts_table, array('contact_id' => $contact_id , 'email_id' => $email_id));
	}

	function set_emails_user_invitee_relationship($email_id, $user_id)
	{
		$this->set_relationship($this->rel_users_table, array('user_id' => $user_id , 'email_id' => $email_id));
	}

	function get_summary_text()
	{
		return "$this->name";
	}

	function create_list_query(&$order_by, &$where)
	{
                $custom_join = $this->custom_fields->getJOIN();
                $query = "SELECT ";

                $query .= "
					emails.id,
					emails.assigned_user_id,
					emails.date_entered,
					emails.name,
					emails.parent_type,
					emails.parent_id,
					emails.date_start,
					emails.time_start,
					emails.from_name,
					emails.from_addr,
					emails.to_addrs,
					emails.cc_addrs,
					emails.bcc_addrs,
					emails.type,
					emails.status,
                                	contacts.first_name,
                                	contacts.last_name,
                                	users.user_name as assigned_user_name";



                                	if($custom_join){
   										$query .= $custom_join['select'];
 									}
                                	$query .= " FROM emails ";





		$query .= "					LEFT JOIN emails_contacts
                                	ON emails.id=emails_contacts.email_id";



                                	$query .= " LEFT JOIN contacts
                                	ON emails_contacts.contact_id=contacts.id
                                	LEFT JOIN users
                                	ON emails.assigned_user_id=users.id ";
									if($custom_join){
  										$query .= $custom_join['join'];
									}

                        $where_auto = " emails.deleted=0
                                        AND (contacts.deleted IS NULL OR contacts.deleted=0)";
					//GROUP BY emails.id";

        if($where != "")
			$query .= "where $where AND ".$where_auto;
		else
			$query .= "where ".$where_auto;

		if($order_by != "")
			$query .= " ORDER BY $order_by";
		else
			$query .= " ORDER BY emails.name";

		return $query;
	}

	function create_export_query(&$order_by, &$where)
        {
                $contact_required = ereg("contacts", $where);
				$custom_join = $this->custom_fields->getJOIN();
                if($contact_required)
                {
                        $query = "SELECT emails.*, contacts.first_name, contacts.last_name";



                        if($custom_join){
   							$query .= $custom_join['select'];
 						}

                        $query .= " FROM contacts, emails, emails_contacts ";
                        $where_auto = "emails_contacts.contact_id = contacts.id AND emails_contacts.email_id = emails.id AND emails.deleted=0 AND contacts.deleted=0";
                }
                else
                {
                        $query = 'SELECT emails.*';



                         if($custom_join){
   							$query .= $custom_join['select'];
 						}
                        $query .= ' FROM emails ';
                        $where_auto = "emails.deleted=0";
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
                        $query .= " ORDER BY emails.name";

                return $query;
        }



	function fill_in_additional_list_fields()
	{
		$this->fill_in_additional_detail_fields();
		$this->fill_in_additional_parent_fields();

		//populate attachment_image, used to display attachment icon.

		$query =  "select 1 from notes where notes.parent_id = '$this->id' and notes.deleted = 0";
		$result =$this->db->query($query,true," Error filling in additional list fields: ");

		$row = $this->db->fetchByAssoc($result);

		global $theme;

		if ($row !=null) {
			$this->attachment_image = get_image('themes/'.$theme.'/images/attachment',"","","");
		} else {
				$this->attachment_image = get_image('include/images/blank.gif',"","","");
		}

	}

	function fill_in_additional_detail_fields()
	{
		global $app_list_strings,$mod_strings;
		// Fill in the assigned_user_name
		$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);




		$query  = "SELECT contacts.first_name, contacts.last_name, contacts.phone_work, contacts.email1, contacts.id FROM contacts, emails_contacts ";
		$query .= "WHERE emails_contacts.contact_id=contacts.id AND emails_contacts.email_id='$this->id' AND emails_contacts.deleted=0 AND contacts.deleted=0";
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
		$this->date_sent = $this->date_start . " ". $this->time_start;

		if ( $this->type == 'out' && $this->status == 'send_error')
		{
			$this->type_name = $mod_strings['LBL_NOT_SENT'];
		}
		else
		{
			$this->type_name = $app_list_strings['dom_email_types'][$this->type];
		}

		$this->link_action = 'DetailView';

		if ( ( $this->type == 'out' && $this->status == 'send_error') ||
			 $this->type == 'draft' )
		{
			$this->link_action = 'EditView';
		}

		if ( empty($this->name ) &&  empty($_REQUEST['record']))
		{
			$this->name = '(no subject)';
		}

		$this->fill_in_additional_parent_fields();
	}

	function fill_in_additional_parent_fields()
	{
		global  $app_strings;
		if ($this->parent_type == "Opportunities") {
			require_once("modules/Opportunities/Opportunity.php");
			$parent = new Opportunity();
			$query = "SELECT name from $parent->table_name where id = '$this->parent_id'";
			$result =$this->db->query($query,true," Error filling in additional detail fields: ");

			// Get the id and the name.
			$row = $this->db->fetchByAssoc($result);

			if($row != null)
			{
				$this->parent_name = stripslashes($row['name']);
			}
		}
		elseif ($this->parent_type == "Cases") {
			require_once("modules/Cases/Case.php");
			$parent = new aCase();
			$query = "SELECT name from $parent->table_name where id = '$this->parent_id'";
			$result =$this->db->query($query,true," Error filling in additional detail fields: ");

			// Get the id and the name.
			$row = $this->db->fetchByAssoc($result);

			if($row != null)
			{
				$this->parent_name = stripslashes($row['name']);
			}
		}
		elseif ($this->parent_type == "Bugs") {
			require_once("modules/Bugs/Bug.php");
			$parent = new Bug();
			$query = "SELECT name from $parent->table_name where id = '$this->parent_id'";
			$result =$this->db->query($query,true," Error filling in additional detail fields: ");

			// Get the id and the name.
			$row = $this->db->fetchByAssoc($result);

			if($row != null)
			{
				$this->parent_name = stripslashes($row['name']);
			}
		}
		elseif ($this->parent_type == "Project") {
			require_once("modules/Project/Project.php");
			$parent = new Project();
			$query = "SELECT name from $parent->table_name where id = '$this->parent_id'";
			$result =$this->db->query($query,true," Error filling in additional detail fields: ");

			// Get the id and the name.
			$row = $this->db->fetchByAssoc($result);

			if($row != null)
			{
				$this->parent_name = stripslashes($row['name']);
			}
		}
		elseif ($this->parent_type == "ProjectTask") {
			require_once("modules/ProjectTask/ProjectTask.php");
			$parent = new ProjectTask();
			$query = "SELECT name from $parent->table_name where id = '$this->parent_id'";
			$result =$this->db->query($query,true," Error filling in additional detail fields: ");

			// Get the id and the name.
			$row = $this->db->fetchByAssoc($result);

			if($row != null)
			{
				$this->parent_name = stripslashes($row['name']);
			}
		}
		elseif ($this->parent_type == "Accounts") {
			require_once("modules/Accounts/Account.php");
			$parent = new Account();
			$query = "SELECT name from $parent->table_name where id = '$this->parent_id'";
			$result =$this->db->query($query,true," Error filling in additional detail fields: ");

			// Get the id and the name.
			$row = $this->db->fetchByAssoc($result);

			if($row != null)
			{
				$this->parent_name = stripslashes($row['name']);
			}
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
		elseif ($this->parent_type == "Leads") {
			require_once("modules/Leads/Lead.php");
			$parent = new Lead();
			$query = "SELECT first_name, last_name from $parent->table_name where id = '$this->parent_id'";

			$result =$this->db->query($query,true, "ERROR CREATING ADDITIONAL FIELDS");


			// Get the id and the name.

			$row = $this->db->fetchByAssoc($result);


			if($row != null)
			{
				$this->parent_name = '';
				if ($row['first_name'] != '') $this->parent_name .= stripslashes($row['first_name']). ' ';
				if ($row['last_name'] != '') $this->parent_name .= stripslashes($row['last_name']);
			}
		}









































		else {
			$this->parent_name = '';
		}
	}

	function mark_relationships_deleted($id)
	{
		$query = "delete from $this->rel_users_table where email_id='$id'";
		$this->db->query($query,true,"Error marking record deleted: ");

		$query = "delete from $this->rel_contacts_table where email_id='$id'";
		$this->db->query($query,true,"Error marking record deleted: ");
		$query = "delete from $this->rel_cases_table where email_id='$id'";
		$this->db->query($query,true,"Error marking record deleted: ");
		$query = "delete from $this->rel_accounts_table where email_id='$id'";
		$this->db->query($query,true,"Error marking record deleted: ");
		$query = "delete from $this->rel_opportunities_table where email_id='$id'";
		$this->db->query($query,true,"Error marking record deleted: ");

	}

	function mark_email_contact_relationship_deleted($contact_id, $email_id)
	{
		$query = "delete from $this->rel_contacts_table where contact_id='$contact_id' and email_id='$email_id' and deleted=0";
		$this->db->query($query,true,"Error clearing email to contact relationship: ");
	}

	function mark_email_user_relationship_deleted($user_id, $email_id)
	{
		$query = "delete from $this->rel_users_table where user_id='$user_id' and email_id='$email_id' and deleted=0";
		$this->db->query($query,true,"Error clearing email to user relationship: ");
	}
	function mark_email_case_relationship_deleted($id, $email_id)
	{
		$query = "delete from $this->rel_cases_table where case_id='$id' and email_id='$email_id' and deleted=0";
		$this->db->query($query,true,"Error clearing email to user relationship: ");
	}
	function mark_email_account_relationship_deleted($id, $email_id)
	{
		$query = "delete from $this->rel_accounts_table where account_id='$id' and email_id='$email_id' and deleted=0";
		$this->db->query($query,true,"Error clearing email to user relationship: ");
	}
	function mark_email_opportunity_relationship_deleted($id, $email_id)
	{
		$query = "delete from $this->rel_opportunities_table where opportunity_id='$id' and email_id='$email_id' and deleted=0";
		$this->db->query($query,true,"Error clearing email to user relationship: ");
	}
	function get_list_view_data(){
		$email_fields = $this->get_list_view_array();
		global $app_list_strings;
		if (isset($this->parent_type) && $this->parent_type != "")
			$email_fields['PARENT_MODULE'] = $this->parent_type;

		$email_fields['CONTACT_NAME']= return_name($email_fields,'FIRST_NAME','LAST_NAME');

		//add ATTACHMENT_IMAGE
		$email_fields['ATTACHMENT_IMAGE'] = $this-> attachment_image;

		return $email_fields;
	}

	/** Returns a list of the associated opportunities
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_opportunities()
	{
		// First, get the list of IDs.
		$query = "SELECT opportunity_id as id from emails_opportunities where email_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new Opportunity());
	}

	/** Returns a list of the associated accounts
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_accounts()
	{
		// First, get the list of IDs.
		$query = "SELECT account_id as id from emails_accounts where email_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new Account());
	}


	/** Returns a list of the associated cases
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_cases()
	{
		// First, get the list of IDs.
		$query = "SELECT case_id as id from emails_cases where email_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new aCase());
	}

	function parse_additional_headers(&$list_form, $xTemplateSection) {

	}

	function list_view_parse_additional_sections(&$list_form, $xTemplateSection) {
		return $list_form;
	}

	function set_notification_body($xtpl, $email)
	{
		$xtpl->assign("EMAIL_SUBJECT", $email->name);
		$xtpl->assign("EMAIL_DATESENT", $email->date_start . " " . $email->time_start);

		return $xtpl;
	}

	function check_email_settings()
	{
		global $current_user;

		//$mail = new PHPMailer();
		$mail_fromaddress = $current_user->getPreference('mail_fromaddress');
		$mail_fromname = $current_user->getPreference('mail_fromname');
		if(   empty($mail_fromaddress) || empty($mail_fromname ))
		{
	  	return false;
		}

    $send_type = $current_user->getPreference('mail_sendtype') ;

		if ( ! empty($send_type) && $send_type == "SMTP")
		{
			$mail_smtpserver = $current_user->getPreference('mail_smtpserver');
			$mail_smtpport = $current_user->getPreference('mail_smtpport');
			$mail_smtpauth_req = $current_user->getPreference('mail_smtpauth_req');
			$mail_smtpuser = $current_user->getPreference('mail_smtpuser');
			$mail_smtppass = $current_user->getPreference('mail_smtppass');

			if ( empty($mail_smtpserver) || empty($mail_smtpport) ||
                	(! empty($mail_smtpauth_req) && ( empty($mail_smtpuser) || empty($mail_smtppass))))
			{
				return false;
			}
		}

		return true;

	}

	function send()
	{
		global $current_user;
		global $sugar_config;
		$mail = new PHPMailer();
		foreach ($this->to_addrs_arr as $addr_arr)
		{
			if ( empty($addr_arr['name']))
			{
                		$mail->AddAddress($addr_arr['email'], "");
			}
			else
			{
                		$mail->AddAddress($addr_arr['email'], $addr_arr['name']);
			}
		}
		foreach ($this->cc_addrs_arr as $addr_arr)
		{
			if ( empty($addr_arr['name']))
			{
                		$mail->AddCC($addr_arr['email'], "");
			}
			else
			{
                		$mail->AddCC($addr_arr['email'], $addr_arr['name']);
			}
		}
		foreach ($this->bcc_addrs_arr as $addr_arr)
		{
			if ( empty($addr_arr['name']))
			{
                		$mail->AddBCC($addr_arr['email'], "");
			}
			else
			{
                		$mail->AddBCC($addr_arr['email'], $addr_arr['name']);
			}
		}

		if ($current_user->getPreference('mail_sendtype') == "SMTP") {
			$mail->Mailer = "smtp";
			$mail->Host = $current_user->getPreference('mail_smtpserver');
			$mail->Port = $current_user->getPreference('mail_smtpport');

			if ($current_user->getPreference('mail_smtpauth_req'))
			{
				$mail->SMTPAuth = TRUE;
				$mail->Username = $current_user->getPreference('mail_smtpuser');
				$mail->Password = $current_user->getPreference('mail_smtppass');
			}
		}

		$mail->From = $current_user->getPreference('mail_fromaddress');
		$this->from_addr = $mail->From;
		$mail->FromName =  $current_user->getPreference('mail_fromname');
		$this->from_name = $mail->FromName;
		$mail->AddReplyTo($mail->From,$mail->FromName);
		$mail->Subject = $this->name;

		foreach ($this->attachments as $note)
		{
			$mime_type = 'text/plain';
			if (! empty($note->file->temp_file_location))
			{
				$file_location = $note->file->temp_file_location;
				$filename = $note->file->original_file_name;
				$mime_type = $note->file->mime_type;
			}
			else
			{
				$file_location = UploadFile::get_file_path($note->filename,$note->id);
				$filename = $note->filename;
				$mime_type = $note->file_mime_type;

			}

			$mail->AddAttachment(
					$file_location,
					$filename,
					'base64',
					$mime_type);

		}
		$mail->Body = from_html($this->description);
		//$mail->IsHTML(true);

		if ($mail->Send())
		{
  			return true;
		}

    $this->log->warn("Error emailing:".$mail->ErrorInfo);

		return false;

	}



	function remove_empty_fields(&$arr)
	{
		$newarr = array();
		foreach($arr as $field)
		{
			if (empty($field))
			{
				continue;
			}
			array_push($newarr,$field);
		}
		return $newarr;
	}

	function parse_addrs($to_addrs,$to_addrs_ids,$to_addrs_names,$to_addrs_emails)
	{
		$return_arr = array();
		$to_addrs = preg_replace("/&gt;/",">",$to_addrs);
		$to_addrs = preg_replace("/&lt;/","<",$to_addrs);
		$to_addrs_arr = explode(";",$to_addrs);
		$to_addrs_arr = $this->remove_empty_fields($to_addrs_arr);
		$to_addrs_ids_arr = explode(";",$to_addrs_ids);
		$to_addrs_ids_arr = $this->remove_empty_fields($to_addrs_ids_arr);
		$to_addrs_emails_arr = explode(";",$to_addrs_emails);
		$to_addrs_emails_arr = $this->remove_empty_fields($to_addrs_emails_arr);
		$to_addrs_names_arr = explode(";",$to_addrs_names);
		$to_addrs_names_arr = $this->remove_empty_fields($to_addrs_names_arr);

		$contact = new Contact();

		for ($i = 0; $i < count($to_addrs_arr); $i++)
		{
			$newarr = array();
			$field = $to_addrs_arr[$i];

			//extract the email address:
			preg_match("/([a-zA-Z0-9\-\+\.\_]+\@[a-zA-Z0-9\-\+\.\_]+)/",$field,$match);

			$newarr['display'] = $field;
			if ( empty($match[1]))
			{
				$newarr['email'] = '';
			}
			else
			{
				$newarr['email'] = $match[1];
			}

			if ( isset($to_addrs_emails_arr[$i]) && $to_addrs_emails_arr[$i] == $match[1])
			{
				$newarr['contact_id'] = $to_addrs_ids_arr[$i];
				$newarr['name'] = $to_addrs_names_arr[$i];
			}
			else
			{
				$contact_id = $contact->get_contact_id_by_email($newarr['email']);
				if ( ! empty($contact_id))
				{
					$newarr['contact_id'] = $contact_id;
				}
			}
			array_push($return_arr,$newarr);
		}
		return $return_arr;

	}


}
?>
