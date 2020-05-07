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
 * $Id: populateSeedData.php,v 1.63.2.2 2005/05/06 01:33:22 ajay Exp $
 * Description:  Executes a step in the installation process.
 ********************************************************************************/

require_once('config.php');

// check for old config format.
if(empty($sugar_config) && isset($dbconfig['db_host_name']))
{
   make_sugar_config($sugar_config);
}

require_once('sugar_version.php');

require_once('modules/Contacts/contactSeedData.php');
require_once('modules/Contacts/Contact.php');
require_once('modules/Leads/Lead.php');
require_once('modules/Accounts/Account.php');
require_once('modules/Opportunities/Opportunity.php');
require_once('modules/Tasks/Task.php');
require_once('include/database/PearDatabase.php');
require_once('include/utils.php');
require_once('include/language/en_us.lang.php');

require_once('modules/Currencies/Currency.php');











global $first_name_array;
global $first_name_count;
global $last_name_array;
global $last_name_count;
global $company_name_array;
global $company_name_count;
global $street_address_array;
global $street_address_count;
global $city_array;
global $city_array_count;
global $app_list_strings;
global $sugar_config;

$db =& new PearDatabase();
require_once('include/TimeDate.php');
$timedate =& new TimeDate();







































function add_digits($quantity, &$string, $min = 0, $max = 9)
{
	for($i=0; $i < $quantity; $i++)
	{
		$string .= mt_rand($min,$max);
	}
}

function create_phone_number()
{
	$phone = "(";
	add_digits(3, $phone);
	$phone .= ") ";
	add_digits(3, $phone);
	$phone .= "-";
	add_digits(4, $phone);

	return $phone;
}


function create_date()
{
	global $timedate;
	
	$date = date($timedate->get_date_format(), mktime(0, 0, 0, date("m")  , date("d")+mt_rand(0,365), date("Y")));
	return $date;
}

function create_past_date()
{
global $timedate;
	
	$date = date($timedate->get_date_format(), mktime(0, 0, 0, date("m")  , date("d")+mt_rand(-365,-1), date("Y")));
	return $date;
}

$possible_duration_hours_arr = array( 0, 1, 2, 3);
$possible_duration_minutes_arr = array( '00','15', '30', '45');

$account_ids = Array();
$accounts = Array();
$opportunity_ids = Array();

// Determine the assigned user for all demo data.  This is the default user if set, or admin
$assigned_user_name = "admin";
if(!empty($sugar_config['default_user_name']) &&
	!empty($sugar_config['create_default_user']) &&
	$sugar_config['create_default_user'])
{
	$assigned_user_name = $sugar_config['default_user_name'];
}

// Look up the user id for the assigned user
$seed_user =& new User();

$assigned_user_id = $seed_user->retrieve_user_id($assigned_user_name);









for($i = 0; $i < $company_name_count; $i++)
{

	$account_name = $company_name_array[$i];






	// Create new accounts.
	$account =& new Account();
	$account->name = $account_name;
	$account->phone_office = create_phone_number();
	$account->assigned_user_id = $assigned_user_id;

	$whitespace = array(" ", ".", "&", "\/");
	$website = str_replace($whitespace, "", strtolower($account->name));
	$account->website = 'www.'.$website.'.com';

	$account->billing_address_street = $street_address_array[mt_rand(0,$street_address_count-1)];
	$account->billing_address_city = $city_array[mt_rand(0,$city_array_count-1)];

	if($i % 3 == 1)
	{
		$account->billing_address_state = "NY";
















	}
	else
	{
		$account->billing_address_state = "CA";


















	}

	












	$account->billing_address_postalcode = mt_rand(10000, 99999);
	$account->billing_address_country = 'USA';

	$account->shipping_address_street = $account->billing_address_street;
	$account->shipping_address_city = $account->billing_address_city;
	$account->shipping_address_state = $account->billing_address_state;
	$account->shipping_address_postalcode = $account->billing_address_postalcode;
	$account->shipping_address_country = $account->billing_address_country;

	$key = array_rand($app_list_strings['industry_dom']);
	$account->industry = $app_list_strings['industry_dom'][$key];

	$account->account_type = "Customer";

	$account->save();

	$account_ids[] = $account->id;
	$accounts[] = $account;

	//Create new opportunities
	$opp =& new Opportunity();

	$opp->assigned_user_id = $assigned_user_id;







	$opp->name = $account_name." - 1000 units";
	$opp->date_closed = & create_date();

	$key = array_rand($app_list_strings['lead_source_dom']);
	$opp->lead_source = $app_list_strings['lead_source_dom'][$key];

	$key = array_rand($app_list_strings['sales_stage_dom']);
	$opp->sales_stage = $app_list_strings['sales_stage_dom'][$key];










	$key = array_rand($app_list_strings['opportunity_type_dom']);
	$opp->opportunity_type = $app_list_strings['opportunity_type_dom'][$key];

	$amount = array("10000", "25000", "50000", "75000");
	$key = array_rand($amount);
	$opp->amount = $amount[$key];

	$opp->save();

	$opportunity_ids[] = $opp->id;
	// Create a linking table entry to assign an account to the opportunity.
	$opp->set_relationship('accounts_opportunities', array('opportunity_id'=>$opp->id ,'account_id'=> $account->id), false);

}

$titles = array("President",
				"VP Operations",
				"VP Sales",
				"Director Operations",
				"Director Sales",
				"Mgr Operations",
				"IT Developer",
				"");

$account_max = count($account_ids) - 1;





























for($i=0; $i<1000; $i++)
{
	$contact =& new Contact();

	$contact->first_name = ucfirst(strtolower($first_name_array[$i]));
	$contact->last_name = ucfirst(strtolower($last_name_array[$i]));
	$contact->assigned_user_id = $assigned_user_id;
	$contact->email1 = strtolower($contact->first_name)."_".strtolower($contact->last_name)."@company.com";
	$key = array_rand($street_address_array);
	$contact->primary_address_street = $street_address_array[$key];
	$key = array_rand($city_array);
	$contact->primary_address_city = $city_array[$key];

	$key = array_rand($app_list_strings['lead_source_dom']);
	$contact->lead_source = $app_list_strings['lead_source_dom'][$key];

	$key = array_rand($titles);
	$contact->title = $titles[$key];





	$contact->phone_work = create_phone_number();
	$contact->phone_home = create_phone_number();
	$contact->phone_mobile = create_phone_number();

	$account_number = mt_rand(0,$account_max);
	$account_id = $account_ids[$account_number];

	// Fill in a bogus address
	$contact->primary_address_state = "CA";

	$contacts_account = $accounts[$account_number];
	$contact->primary_address_state = $contacts_account->billing_address_state;



	$contact->assigned_user_id = $contacts_account->assigned_user_id;
	$contact->assigned_user_name = $contacts_account->assigned_user_name;

	$contact->primary_address_postalcode = mt_rand(10000,99999);
	$contact->primary_address_country = 'USA';

	$contact->save();


	// Create a linking table entry to assign an account to the contact.
	$contact->set_relationship('accounts_contacts', array('contact_id'=>$contact->id ,'account_id'=> $account_id), false);
	

	// This assumes that there will be one opportunity per company in the seed data.
	$opportunity_key = array_rand($opportunity_ids);
	$contact->set_relationship('opportunities_contacts', array('contact_id'=>$contact->id ,'opportunity_id'=> $opportunity_key, 'contact_role'=>$app_list_strings['opportunity_relationship_type_default_key']), false);
	

	//Create new tasks
	$task =& new Task();

	$key = array_rand($task->default_task_name_values);
	$task->name = $task->default_task_name_values[$key];
	$task->date_due = & create_date();
	$task->time_due = date("H:i:s",time());
	$task->date_due_flag = 'off';
	$task->assigned_user_id = $assigned_user_id;



	$task->assigned_user_id = $contacts_account->assigned_user_id;
	$task->assigned_user_name = $contacts_account->assigned_user_name;

	$key = array_rand($app_list_strings['task_status_dom']);
	$task->status = $app_list_strings['task_status_dom'][$key];
	$task->contact_id = $contact->id;
	if ($contact->primary_address_city == "San Mateo") {
		$task->parent_id = $account_id;
		$task->parent_type = 'Accounts';
		$task->save();
	}

	//Create new meetings
	$meeting =& new Meeting();

	$key = array_rand($meeting->default_meeting_name_values);
	$meeting->name = $meeting->default_meeting_name_values[$key];
	$meeting->date_start = & create_date();
	$meeting->time_start = date("H:i",time());
	$meeting->duration_hours = array_rand($possible_duration_hours_arr);
	$meeting->duration_minutes = array_rand($possible_duration_minutes_arr);
	$meeting->assigned_user_id = $assigned_user_id;



	$meeting->assigned_user_id = $contacts_account->assigned_user_id;
	$meeting->assigned_user_name = $contacts_account->assigned_user_name;
	$meeting->description = 'Meeting to discuss project plan and hash out the details of implementation';

	$key = array_rand($app_list_strings['meeting_status_dom']);
	$meeting->status = $app_list_strings['meeting_status_dom'][$key];
	$meeting->contact_id = $contact->id;
	$meeting->parent_id = $account_id;
	$meeting->parent_type = 'Accounts';

  // dont update vcal
  $meeting->update_vcal  = false;

	$meeting->save();
  $meeting->set_accept_status($seed_user,'accept');

	//Create new emails
	$email =& new Email();

	$key = array_rand($email->default_email_subject_values);
	$email->name = $email->default_email_subject_values[$key];
	$email->date_start = & create_date();
	$email->time_start = date("H:i",time());
	$email->duration_hours = array_rand($possible_duration_hours_arr);
	$email->duration_minutes = array_rand($possible_duration_minutes_arr);
	$email->assigned_user_id = $assigned_user_id;



	$email->assigned_user_id = $contacts_account->assigned_user_id;
	$email->assigned_user_name = $contacts_account->assigned_user_name;
	$email->description = 'Discuss project plan and hash out the details of implementation';

	$email->status = 'sent';
	$email->contact_id = $contact->id;
	$email->parent_id = $account_id;
	$email->parent_type = 'Accounts';
	$email->save();
}


















































































































































































































































































































































































































///
/// SEED DATA FOR PROJECT AND PROJECT TASK
///

include_once('modules/Project/Project.php');
include_once('modules/ProjectTask/ProjectTask.php');

// Project: Take Over The World

$project = new Project();
$project->name = 'Take Over The World';
$project->description = 'World conquest has always been a passion of mine.';
$project->assigned_user_id = 1;



$take_over_world_id = $project->save();

$project_task = new ProjectTask();
$project_task->assigned_user_id = 1;



$project_task->name = 'Make lots of money';
$project_task->status = 'Completed';
$project_task->date_due = '2006-04-05';
$project_task->time_due = '08:00:00';
$project_task->date_start = '2006-03-05';
$project_task->time_start = '08:00:00';
$project_task->parent_id = $take_over_world_id;
$project_task->priority = 'High';
$project_task->description = "Need proper funding for such an ambitious undertaking.  Be sure to ask Moriarty for some cash--he owes me a favor.";
$project_task->order_number = 1;
$project_task->task_number = 101;
$project_task->estimated_effort = 40;
$project_task->actual_effort = 45;
$project_task->utilization = 100;
$project_task->percent_complete = 100;
$make_money_id = $project_task->save();

$project_task = new ProjectTask();
$project_task->assigned_user_id = 1;



$project_task->name = 'Build fortress';
$project_task->status = 'In Progress';
$project_task->date_due = '2006-04-15';
$project_task->time_due = '07:00:00';
$project_task->date_start = '2006-04-05';
$project_task->time_start = '08:00:00';
$project_task->parent_id = $take_over_world_id;
$project_task->priority = 'Medium';
$project_task->description = 'Must be secluded, but close to an interstate highway exit.  Would be great if it was in a good school zone.';
$project_task->order_number = 2;
$project_task->task_number = 102;
$project_task->depends_on_id = $make_money_id;
$project_task->estimated_effort = 40;
$project_task->actual_effort = 45;
$project_task->utilization = 100;
$project_task->percent_complete = 50;
$build_fortress_id = $project_task->save();

$project_task = new ProjectTask();
$project_task->assigned_user_id = 1;



$project_task->name = 'Purchase giant death ray gun';
$project_task->status = 'Not Started';
$project_task->date_due = '2006-04-15';
$project_task->time_due = '07:00:00';
$project_task->date_start = '2006-04-05';
$project_task->time_start = '08:00:00';
$project_task->parent_id = $take_over_world_id;
$project_task->priority = 'Medium';
$project_task->description = 'The bigger, the better.  Go with a known name brand.';
$project_task->order_number = 3;
$project_task->task_number = 103;
$project_task->depends_on_id = $make_money_id;
$project_task->estimated_effort = 100;
$project_task->actual_effort = 60;
$project_task->utilization = 100;
$project_task->percent_complete = 90;
$project_task->save();

$project_task = new ProjectTask();
$project_task->assigned_user_id = 1;



$project_task->name = 'Threaten all major countries on public television';
$project_task->status = 'Not Started';
$project_task->date_due = '2006-04-15';
$project_task->time_due = '10:00:00';
$project_task->date_start = '2006-04-15';
$project_task->time_start = '07:00:00';
$project_task->parent_id = $take_over_world_id;
$project_task->priority = 'High';
$project_task->description = "Be sure to stick it to them with an attitude.  We all know what happened the last time they didn't take us seriously.";
$project_task->order_number = 4;
$project_task->task_number = 100;
$project_task->estimated_effort = 3;
$project_task->utilization = 104;
$project_task->percent_complete = 0;
$project_task->save();

$project_task = new ProjectTask();
$project_task->assigned_user_id = 1;



$project_task->name = 'Pick color for new throne';
$project_task->status = 'Not Started';
$project_task->date_due = '2006-04-15';
$project_task->time_due = '10:00:00';
$project_task->date_start = '2006-03-05';
$project_task->time_start = '08:00:00';
$project_task->parent_id = $take_over_world_id;
$project_task->priority = 'Low';
$project_task->description = 'Not terribly urgent, yet still important.  Remember to pick a color that matches the decor of the fortress.';
$project_task->order_number = 5;
$project_task->task_number = 105;
$project_task->depends_on_id = $build_fortress_id;
$project_task->estimated_effort = 10;
$project_task->utilization = 20;
$project_task->percent_complete = 0;
$project_task->save();

// Project: Move Mountain

$project = new Project();
$project->name = 'Move Mountain';
$project->description = 'The mountain to move has yet to be determined.';
$project->assigned_user_id = 1;



$move_mountain_id = $project->save();

$project_task = new ProjectTask();
$project_task->assigned_user_id = 1;



$project_task->name = 'Purchase lots of shovels';
$project_task->status = 'Completed';
$project_task->date_due = '2005-08-22';
$project_task->time_due = '08:00:00';
$project_task->date_start = '2005-08-15';
$project_task->time_start = '08:00:00';
$project_task->parent_id = $move_mountain_id;
$project_task->priority = 'High';
$project_task->description = "Lots and lots of shovels.";
$project_task->order_number = 1;
$project_task->task_number = 101;
$project_task->estimated_effort = 40;
$project_task->actual_effort = 36;
$project_task->utilization = 100;
$project_task->percent_complete = 100;
$project_task->save();

$project_task = new ProjectTask();
$project_task->assigned_user_id = 1;



$project_task->name = 'Make friends with woodland creatures who can dig';
$project_task->status = 'In Progress';
$project_task->date_due = '2005-08-29';
$project_task->time_due = '08:00:00';
$project_task->date_start = '2005-08-22';
$project_task->time_start = '08:00:00';
$project_task->parent_id = $move_mountain_id;
$project_task->priority = 'High';
$project_task->description = "Gophers, rabbits, and lemmings are well suited for the job.";
$project_task->order_number = 2;
$project_task->task_number = 102;
$project_task->estimated_effort = 40;
$project_task->actual_effort = 20;
$project_task->utilization = 100;
$project_task->percent_complete = 50;
$project_task->save();

$project_task = new ProjectTask();
$project_task->assigned_user_id = 1;



$project_task->name = 'Find a very large hole to put all the dirt into';
$project_task->status = 'Not Started';
$project_task->date_due = '2005-09-05';
$project_task->time_due = '08:00:00';
$project_task->date_start = '2005-08-29';
$project_task->time_start = '08:00:00';
$project_task->parent_id = $move_mountain_id;
$project_task->priority = 'High';
$project_task->description = "Craters and very large valleys will do.  So will the Grand Canyon.";
$project_task->order_number = 3;
$project_task->task_number = 103;
$project_task->estimated_effort = 40;
$project_task->utilization = 100;
$project_task->percent_complete = 0;
$project_task->save();

// Project: Setup Booth At Tradeshow

$project = new Project();
$project->name = 'Setup Booth At Tradeshow';
$project->description = "The annual Widgets Tradeshow will be held in Springfield this year.  We are going to design a booth so good, it will knock the socks off of our competition.  No more fish heads thrown at us this year!";
$project->assigned_user_id = 1;



$tradeshow_id = $project->save();

$project_task = new ProjectTask();
$project_task->assigned_user_id = 1;



$project_task->name = 'Build the marketing message theme';
$project_task->status = 'Completed';
$project_task->date_due = '2006-06-07';
$project_task->time_due = '08:00:00';
$project_task->date_start = '2006-06-01';
$project_task->time_start = '08:00:00';
$project_task->parent_id = $tradeshow_id;
$project_task->priority = 'High';
$project_task->description = "Thinking along the lines of a post-apocalyptic world in which the human race is gone.  Where the robots have taken over the world.  Gray color palette, blinking LED lights.";
$project_task->order_number = 1;
$project_task->task_number = 111;
$project_task->estimated_effort = 32;
$project_task->actual_effort = 40;
$project_task->utilization = 100;
$project_task->percent_complete = 100;
$project_task_id = $project_task->save();

$project_task = new ProjectTask();
$project_task->assigned_user_id = 1;



$project_task->name = 'Order tradeshow booth';
$project_task->status = 'Completed';
$project_task->date_due = '2006-06-28';
$project_task->time_due = '08:00:00';
$project_task->date_start = '2006-06-07';
$project_task->time_start = '08:00:00';
$project_task->parent_id = $tradeshow_id;
$project_task->priority = 'High';
$project_task->description = "";
$project_task->order_number = 2;
$project_task->task_number = 109;
$project_task->depends_on_id = $project_task_id;
$project_task->estimated_effort = 80;
$project_task->actual_effort = 70;
$project_task->utilization = 100;
$project_task->percent_complete = 100;
$project_task_id = $project_task->save();

$project_task = new ProjectTask();
$project_task->assigned_user_id = 1;



$project_task->name = 'Order tradeshow graphics';
$project_task->status = 'Completed';
$project_task->date_due = '2006-06-28';
$project_task->time_due = '08:00:00';
$project_task->date_start = '2006-06-07';
$project_task->time_start = '08:00:00';
$project_task->parent_id = $tradeshow_id;
$project_task->priority = 'High';
$project_task->description = "We need a big poster of a fallen Statue of Liberty--a la Planet of the Apes.  And flying cars--we need flying cars.";
$project_task->order_number = 3;
$project_task->task_number = 110;
$project_task->depends_on_id = $project_task_id;
$project_task->estimated_effort = 40;
$project_task->actual_effort = 50;
$project_task->utilization = 100;
$project_task->percent_complete = 100;
$project_task_id = $project_task->save();

$project_task = new ProjectTask();
$project_task->assigned_user_id = 1;



$project_task->name = 'Confirm booth number with the tradeshow';
$project_task->status = 'Completed';
$project_task->date_due = '2006-06-28';
$project_task->time_due = '17:00:00';
$project_task->date_start = '2006-06-28';
$project_task->time_start = '10:00:00';
$project_task->parent_id = $tradeshow_id;
$project_task->priority = 'High';
$project_task->description = "Make sure we get a good booth location near the center of the show floor.";
$project_task->order_number = 4;
$project_task->task_number = 112;
$project_task->depends_on_id = $project_task_id;
$project_task->estimated_effort = 4;
$project_task->actual_effort = 2;
$project_task->utilization = 50;
$project_task->percent_complete = 100;
$confirm_booth_id = $project_task->save();

$project_task = new ProjectTask();
$project_task->assigned_user_id = 1;



$project_task->name = 'Organize union help';
$project_task->status = 'Completed';
$project_task->date_due = '2006-07-05';
$project_task->time_due = '10:00:00';
$project_task->date_start = '2006-06-29';
$project_task->time_start = '10:00:00';
$project_task->parent_id = $tradeshow_id;
$project_task->priority = 'Medium';
$project_task->description = "";
$project_task->order_number = 5;
$project_task->task_number = 108;
$project_task->depends_on_id = $confirm_booth_id;
$project_task->estimated_effort = 24;
$project_task->actual_effort = 10;
$project_task->utilization = 100;
$project_task->percent_complete = 100;
$project_task_id = $project_task->save();

$project_task = new ProjectTask();
$project_task->assigned_user_id = 1;



$project_task->name = 'Order drayage';
$project_task->status = 'Completed';
$project_task->date_due = '2006-08-01';
$project_task->time_due = '17:00:00';
$project_task->date_start = '2006-06-29';
$project_task->time_start = '10:00:00';
$project_task->parent_id = $tradeshow_id;
$project_task->priority = 'High';
$project_task->description = "";
$project_task->order_number = 6;
$project_task->task_number = 107;
$project_task->depends_on_id = $project_task_id;
$project_task->estimated_effort = 40;
$project_task->actual_effort = 40;
$project_task->utilization = 100;
$project_task->percent_complete = 100;
$project_task_id = $project_task->save();

$project_task = new ProjectTask();
$project_task->assigned_user_id = 1;



$project_task->name = 'Order chotskies';
$project_task->status = 'Completed';
$project_task->date_due = '2006-08-15';
$project_task->time_due = '17:00:00';
$project_task->date_start = '2006-06-29';
$project_task->time_start = '10:00:00';
$project_task->parent_id = $tradeshow_id;
$project_task->priority = 'Medium';
$project_task->description = "The edible pencils we gave our last year did well.";
$project_task->order_number = 7;
$project_task->task_number = 105;
$project_task->depends_on_id = $confirm_booth_id;
$project_task->estimated_effort = 16;
$project_task->actual_effort = 40;
$project_task->utilization = 100;
$project_task->percent_complete = 100;
$project_task_id = $project_task->save();

$project_task = new ProjectTask();
$project_task->assigned_user_id = 1;



$project_task->name = 'Order lead capture device';
$project_task->status = 'Completed';
$project_task->date_due = '2006-08-15';
$project_task->time_due = '17:00:00';
$project_task->date_start = '2006-06-29';
$project_task->time_start = '10:00:00';
$project_task->parent_id = $tradeshow_id;
$project_task->priority = 'Medium';
$project_task->description = "Leads will be piped straight into SugarCRM from a swipe of the admission badge this year.";
$project_task->order_number = 8;
$project_task->task_number = 106;
$project_task->depends_on_id = $confirm_booth_id;
$project_task->estimated_effort = 4;
$project_task->actual_effort = 16;
$project_task->utilization = 100;
$project_task->percent_complete = 100;
$project_task_id = $project_task->save();

$project_task = new ProjectTask();
$project_task->assigned_user_id = 1;



$project_task->name = 'Assign booth duty';
$project_task->status = 'Completed';
$project_task->date_due = '2006-08-31';
$project_task->time_due = '17:00:00';
$project_task->date_start = '2006-06-29';
$project_task->time_start = '10:00:00';
$project_task->parent_id = $tradeshow_id;
$project_task->priority = 'Medium';
$project_task->description = "No more than 3 hour shifts.  Let the employees have a look around the tradeshow floor.";
$project_task->order_number = 9;
$project_task->task_number = 103;
$project_task->depends_on_id = $confirm_booth_id;
$project_task->estimated_effort = 4;
$project_task->actual_effort = 3;
$project_task->utilization = 100;
$project_task->percent_complete = 100;
$project_task_id = $project_task->save();

$project_task = new ProjectTask();
$project_task->assigned_user_id = 1;



$project_task->name = 'Remind booth workers to wear their uniforms';
$project_task->status = 'Not Started';
$project_task->date_due = '2006-08-31';
$project_task->time_due = '17:00:00';
$project_task->date_start = '2006-06-29';
$project_task->time_start = '10:00:00';
$project_task->parent_id = $tradeshow_id;
$project_task->priority = 'Low';
$project_task->description = "Be sure to suggest to dress up as a famous suppressive robot (e.g. HAL2000).";
$project_task->order_number = 10;
$project_task->task_number = 104;
$project_task->depends_on_id = $project_task_id;
$project_task->estimated_effort = 1;
$project_task->utilization = 100;
$project_task->percent_complete = 0;
$project_task_id = $project_task->save();

$project_task = new ProjectTask();
$project_task->assigned_user_id = 1;



$project_task->name = 'Build press kits';
$project_task->status = 'In Progress';
$project_task->date_due = '2006-08-01';
$project_task->time_due = '17:00:00';
$project_task->date_start = '2006-06-29';
$project_task->time_start = '10:00:00';
$project_task->parent_id = $tradeshow_id;
$project_task->priority = 'High';
$project_task->description = "";
$project_task->order_number = 11;
$project_task->task_number = 101;
$project_task->depends_on_id = $confirm_booth_id;
$project_task->estimated_effort = 16;
$project_task->actual_effort = 3;
$project_task->utilization = 100;
$project_task->percent_complete = 25;
$project_task_id = $project_task->save();

$project_task = new ProjectTask();
$project_task->assigned_user_id = 1;



$project_task->name = 'Arrange partner meetings';
$project_task->status = 'In Progress';
$project_task->date_due = '2006-08-15';
$project_task->time_due = '17:00:00';
$project_task->date_start = '2006-06-29';
$project_task->time_start = '10:00:00';
$project_task->parent_id = $tradeshow_id;
$project_task->priority = 'Medium';
$project_task->description = "Get the usual bunch.";
$project_task->order_number = 12;
$project_task->task_number = 102;
$project_task->depends_on_id = $project_task_id;
$project_task->estimated_effort = 40;
$project_task->actual_effort = 10;
$project_task->utilization = 100;
$project_task->percent_complete = 25;
$project_task_id = $project_task->save();

?>
