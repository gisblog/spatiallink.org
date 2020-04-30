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
 * $Id: Save.php,v 1.39.2.2 2005/05/06 20:16:56 robert Exp $
 * Description:  Saves an Account record and then redirects the browser to the
 * defined return URL.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('modules/Emails/Email.php');
require_once('include/logging.php');

$local_log =& LoggerManager::getLogger('index');

$focus = new Email();
require_once('include/TimeDate.php');
	$timedate = new TimeDate();
global $mod_strings;


if(! isset($prefix))
{
	$prefix = '';
}
	
	if(isset($_POST[$prefix.'meridiem']) && !empty($_POST[$prefix.'meridiem'])){
		$_POST[$prefix.'time_start'] = $timedate->merge_time_meridiem($_POST[$prefix.'time_start'],$timedate->get_time_format(true), $_POST[$prefix.'meridiem']);
		
	}
	
// retrieve the record
if ( isset ($_POST['record']))
{
	$focus->retrieve($_POST['record']);
}

if (!empty($_POST['assigned_user_id']) && ($focus->assigned_user_id != $_POST['assigned_user_id']) && ($_POST['assigned_user_id'] != $current_user->id)) {
	$check_notify = TRUE;
}


// populate the fields of this Email
$allfields = array_merge($focus->column_fields, $focus->additional_column_fields);

foreach($allfields as $field)
{
	if(isset($_POST[$field]))
	{
		$value = $_POST[$field];
		$focus->$field = $value;

	}
}

// compare the 3 fields and return list of contact_ids to link:
$focus->to_addrs_arr = $focus->parse_addrs($_REQUEST['to_addrs'],$_REQUEST['to_addrs_ids'],$_REQUEST['to_addrs_names'],$_REQUEST['to_addrs_emails']);
$focus->cc_addrs_arr = $focus->parse_addrs($_REQUEST['cc_addrs'],$_REQUEST['cc_addrs_ids'],$_REQUEST['cc_addrs_names'],$_REQUEST['cc_addrs_emails']);
$focus->bcc_addrs_arr = $focus->parse_addrs($_REQUEST['bcc_addrs'],$_REQUEST['bcc_addrs_ids'],$_REQUEST['to_addrs_names'],$_REQUEST['bcc_addrs_emails']);


if ( ! empty( $_REQUEST['type'] ) )
{
 $focus->type = $_REQUEST['type'];
}
else
{
 $focus->type = 'archived';
}


 $object_arr = array();

 if ( ! empty ($focus->parent_id))
 {
	 $object_arr[$focus->parent_type] = $focus->parent_id;
 }
if(isset($focus->to_addrs_arr[0]['contact_id'])){
 	$object_arr['Contacts'] = $focus->to_addrs_arr[0]['contact_id'];
}

//do not parse email templates if the email is being saved as draft....

if ( $focus->type != 'draft') {
	
 require_once($beanFiles['EmailTemplate']);

 $focus->name = EmailTemplate::parse_template($focus->name,$object_arr);

 $focus->description = EmailTemplate::parse_template($focus->description,$object_arr);
}

 $focus->to_addrs = $_REQUEST['to_addrs'];
 $max_files_upload = 10;
 //$file_count = 0;

if ( ! empty($focus->id))
{
 $note = new Note();
 $where = "notes.parent_id='{$focus->id}'";
 $notes_list = $note->get_full_list("", $where,true);
}


if (! isset($notes_list))
{
        $notes_list = array();
} 

$focus->attachments = array_merge($focus->attachments,$notes_list);

 for ($i = 0;$i < $max_files_upload;$i++)
 {
 
  $note = new Note();

  $upload_file = new UploadFile('email_attachment'.$i);

  if ($upload_file == -1)
  {
    continue;
  }


  if (isset($_FILES['email_attachment'.$i]) && $upload_file->confirm_upload())
  {

		$note->filename = $upload_file->get_stored_file_name();
	  $note->file = $upload_file;
	  $note->name = $mod_strings["LBL_EMAIL_ATTACHMENT"].": ".$note->file->original_file_name;
 //         $note->original_filename = $upload_file->original_file_name;

	array_push($focus->attachments,$note);
  }

 }


if ( $focus->type == 'out' && isset( $_REQUEST['send'] ) && $_REQUEST['send'] == '1')
{
 if ( $focus->send() )
 {
  $focus->status = 'sent';
  $focus->date_start = date("Y-m-d");
  $focus->time_start = date("H:i");
 }
 else
 {
  $focus->status = 'send_error';
 }
}


if(!empty($GLOBALS['check_notify'])) 
{     
	$focus->save($GLOBALS['check_notify']);
} 
else
{
	$focus->save(FALSE);
}

$return_id = $focus->id;

$seen_contact_ids = array();
$all_arr = array_merge( $focus->to_addrs_arr, $focus->cc_addrs_arr, $focus->bcc_addrs_arr);
reset($all_arr);

$contacts_list = $focus->get_contacts();

foreach($contacts_list as $contact)
{
$seen_contact_ids[$contact->id] = 1;
}


foreach ( $all_arr as $subarr)
{
	if ( ! isset($subarr['contact_id']) || isset($seen_contact_ids[$subarr['contact_id']]))
	{
		continue;
	}

	$seen_contact_ids[$subarr['contact_id']] = 1;

	$focus->set_emails_contact_invitee_relationship($return_id, $subarr['contact_id']);
}

foreach($focus->attachments as $note)
{
		if (! empty($note->id))
		{
			continue;	
		}
	 $note->parent_id = $focus->id;
	 $note->parent_type = "Emails";
	 $note->file_mime_type = $note->file->mime_type;
	 $note_id = $note->save();
	
	 $note->file->final_move($note->id);
}

if(isset($_POST['return_module']) && $_POST['return_module'] != "") $return_module = $_POST['return_module'];
else $return_module = "Emails";
if(isset($_POST['return_action']) && $_POST['return_action'] != "") $return_action = $_POST['return_action'];
else $return_action = "DetailView";

$local_log->debug("Saved record with id of ".$return_id);

if ( $focus->type == 'draft')
{
header("Location: index.php?action=ListViewDrafts&module=$return_module");
}
else if ( $focus->type == 'out')
{
header("Location: index.php?action=Status&module=Emails&record=$return_id");
}
else
{
if(isset($_POST['return_id']) && $_POST['return_id'] != "") $return_id = $_POST['return_id'];
header("Location: index.php?action=$return_action&module=$return_module&record=$return_id");
}
?>
