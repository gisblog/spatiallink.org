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
require_once('data/SugarBean.php');
class EmailMan extends SugarBean{
	var $id;
	var $deleted; 
	var $date_created;
	var $date_modified;
	var $module;
	var $module_id;
	var $marketing_id;
	var $campaign_id;
	var $user_id;
	var $list_id;
	var $invalid_email;
	var $from_name;
	var $from_email;
	var $in_queue;
	var $in_queue_date;
	var $template_id;
	var $send_date_time;
	var $table_name = "emailman";
	var $object_name = "EmailMan";
	var $module_dir = "EmailMan";
	var $send_attempts;

	var $column_fields = Array(
		"id"
		, "date_entered"
		, "date_modified"
		, 'user_id'
		, 'module'
		, 'module_id'
		, 'marketing_id'
		, 'campaign_id'
		, 'list_id'
		, 'template_id'
		, 'from_email'
		, 'from_name'
		, 'invalid_email'
		, 'send_date_time'
		, 'in_queue'
		, 'in_queue_date'
		,'send_attempts'
		);
	function toString(){
		return "EmailMan:\nid = $this->id ,user_id= $this->user_id module = $this->module , module_id = $this->module_id ,list_id = $this->list_id, send_date_time= $this->send_date_time\n";
	}

		


	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array();
	var $required_fields = array();
	// This is the list of fields that are in the lists.
		var $list_fields = Array(
		"id"
		, 'user_id'
		, 'module'
		, 'module_id'
		, 'campaign_id'
		, 'marketing_id'
		, 'list_id'
		, 'invalid_email'
		, 'from_name'
		, 'from_email'
		, 'template_id'
		, 'send_date_time'
		, 'in_queue'
		, 'in_queue_date'
		,'send_attempts'
		,'user_name'
		,'to_email'
		,'from_email'
		,'campaign_name'
		,'to_contact'
		,'to_lead'
		,'to_prospect'
		,'contact_email'
		, 'lead_email'
		, 'prospect_email'
		
		);

	function EmailMan() {
		parent::SugarBean();
	}

	var $new_schema = true;
	function create_tables(){
		parent::create_tables();
		$this->table_name = 'emailman_sent';
		$this->object_name = 'EmailManSent';
		parent::create_tables();
		$this->table_name = 'emailman';
		$this->object_name = 'EmailMan';
			
	}
	
	function drop_tables () {
		parent::drop_tables();
		$this->table_name = 'emailman_sent';
		$this->object_name = 'EmailManSent';
		parent::drop_tables();
		$this->table_name = 'emailman';
		$this->object_name = 'EmailMan';
	}
	

	function create_list_query(&$order_by, &$where){
		$query = "SELECT $this->table_name.* , 
					CONCAT(users.first_name, users.last_name) AS user_name,  
					CONCAT(CONCAT(contacts.first_name, '&nbsp;' ), contacts.last_name) AS to_contact, 
					contacts.email1 AS contact_email, 
					CONCAT(CONCAT(leads.first_name, '&nbsp;' ), leads.last_name) AS to_lead, 
					leads.email1 AS lead_email, 
					CONCAT(CONCAT(prospects.first_name, '&nbsp;' ), prospects.last_name) AS to_prospect, 
					prospects.email1 AS prospect_email, 
					campaigns.name as campaign_name 
					FROM $this->table_name 
					LEFT JOIN users ON users.id = $this->table_name.user_id 
					LEFT JOIN contacts ON contacts.id = $this->table_name.module_id 
					LEFT JOIN leads ON leads.id = $this->table_name.module_id 
					LEFT JOIN prospects ON prospects.id = $this->table_name.module_id 
					LEFT JOIN prospect_lists ON prospect_lists.id = $this->table_name.list_id
					LEFT JOIN campaigns ON campaigns.id = $this->table_name.campaign_id ";
		$where_auto = " $this->table_name.deleted=0";

        if($where != "")
			$query .= "where $where AND ".$where_auto;
		else
			$query .= "where ".$where_auto;
		
		if($order_by != ""){
			if(substr_count( $order_by, 'to_name') > 0){
				$order_by = 'to_contact,to_lead,to_prospect';	
			}
			if(substr_count($order_by, 'to_email' ) > 0){
				$order_by = 'contact_email,lead_email,prospect_email';	
			}
			$query .= " ORDER BY $order_by";
		}else
			$query .= " ORDER BY $this->table_name.send_date_time";

		return $query;
				  
					
					
						
		
	}
	
	function set_as_sent($success=true, $delete= true){
		
		$this->send_attempts++;
		if($delete || $this->send_attempts > 5){
			$this->table_name = "emailman_sent";
			$oldid = $this->id;
			$this->id = '';
			$this->date_time  = date('Y-m-d H:i:s');
			if(!$success){
	    		$this->invalid_email = 1;
			}
			$this->save();
			$this->table_name = 'emailman';
			$query = 'DELETE FROM '. $this->table_name . " WHERE id = '$oldid'";
			$this->db->query($query);
		}else{
			
			$query = 'UPDATE ' . $this->table_name . " SET in_queue='0', send_attempts='$this->send_attempts' WHERE id = '$this->id'";	
			$this->db->query($query);
		}
	}
	

	
	function sendEmail(){
		
		global $beanList, $beanFiles, $mail, $sugar_config;
		if(!isset($beanList[$this->module])){
			return false;	
		}
		$class = $beanList[$this->module];
		require_once('modules/Emails/Email.php');
		require_once('include/TimeDate.php');
		require_once($beanFiles[$class]);
		
		$module =& new $class();
		$module->retrieve($this->module_id);
		if((!isset($module->email_opt_out) || $module->email_opt_out != 'on') && (!isset($module->invalid_email) || $module->invalid_email != 1)){
			$start = microtime();
			require_once('modules/EmailTemplates/EmailTemplate.php');
			$template =& new EmailTemplate();
			$template->retrieve($this->template_id);
			
			require_once('modules/Campaigns/Campaign.php');
			$campaign =& new Campaign();
			$campaign->retrieve($this->campaign_id);
			$tracker_url = $sugar_config['site_url'] . '/campaign_tracker.php?track=' . $campaign->tracker_key;
			$tracker_text = $campaign->tracker_text;
			
			if($tracker_text == '')
				$tracker_text = $tracker_url;
			
			$mail->ClearAllRecipients();
			$mail->From     = $this->from_email;
			$mail->FromName = $this->from_name;
			$mail->AddAddress($module->email1, $module->first_name . ' ' . $module->last_name); 
			$mail->Subject =  $template->parse_template_bean($template->subject, 'Contacts', $module);
			$mail->AltBody = $template->parse_template_bean($template->body, 'Contacts', $module);
			$mail->Body = nl2br($mail->AltBody);
			$mail->Body .= "<br><br><a href='". $tracker_url ."'>" . $tracker_text . "</a><br><br>"; 
			$mail->Body .= "<br><font size='2'>To remove yourself from this email list <a href='". $sugar_config['site_url'] . "/removeme.php?remove=$this->module_id&from=$this->module'>click here</a></font>";
	    	$mail->AltBody .="\n". $tracker_url. "\n\n\nTo remove yourself from this email list go to ". $sugar_config['site_url'] . "/removeme.php?remove=$this->module_id&from=$this->module'";
	    	$success = $mail->send();
			$this->set_as_sent($success, $success);
			if($success ){
				$email =& new Email();



				$email->to_addrs= $module->first_name . ' ' . $module->last_name . '&lt;'.$module->email1.'&gt;';
				$email->to_addrs_ids = $module->id .';';
				$email->to_addrs_names = $module->first_name . ' ' . $module->last_name . ';';
				$email->to_addrs_emails = $module->email1 . ';';
				$email->type= 'archived';
				$email->deleted = '0';
				$email->name = $campaign->name.': '.$mail->Subject ;
				$email->description = $mail->AltBody;
				$email->from_addr = $mail->From;
				$email->assigned_user_id = $this->user_id;
				$email->parent_type = 'Leads';
				if($this->module == 'Leads'){
					$email->parent_id = $module->id ;
				}
				
				$timedate =& new TimeDate();
				$email->date_start = $timedate->to_display_date(date('Y-m-d'), false);
				$email->time_start = $timedate->to_display_time(date('H:i:s'), false);
				$email->save();
				if($this->module == 'Contacts'){
					$module->set_email_contact_relationship($module->id, $email->id);
				}
			}
		}else{
			$mail->ErrorInfo .= "\nRecipient Email Opt Out";
			$success = false;	
			$this->set_as_sent($success, true);
		}
    	
		return $success;
		
			
	}

}


?>
