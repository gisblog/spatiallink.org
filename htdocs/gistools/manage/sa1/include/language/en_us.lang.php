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
 * $Id: en_us.lang.php,v 1.167.2.3 2005/05/12 18:53:47 ajay Exp $
 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

//the left value is the key stored in the db and the right value is the display value
//to translate, only modify the right value in each key/value pair
$app_list_strings = array (
//e.g. auf Deutsch 'Contacts'=>'Contakten',
  'moduleList' =>
  array (
    'Home' => 'Home',
    'Dashboard' => 'Dashboard',
    'Contacts' => 'Contacts',
    'Accounts' => 'Accounts',
    'Opportunities' => 'Opportunities',
    'Cases' => 'Cases',
    'Notes' => 'Notes',
    'Calls' => 'Calls',
    'Emails' => 'Emails',
    'Meetings' => 'Meetings',
    'Tasks' => 'Tasks',
    'Calendar' => 'Calendar',
    'Leads' => 'Leads',






    'Activities' => 'Activities',
    'Bugs' => 'Bug Tracker',
    'Feeds' => 'RSS',
    'iFrames'=>'My Portal',
    'TimePeriods'=>'Time Periods',
    'Project'=>'Projects',
    'ProjectTask'=>'Project Tasks',
    'Campaigns'=>'Campaigns',
    'Documents'=>'Documents',
  ),
  //e.g. en fran�ais 'Analyst'=>'Analyste',
  'account_type_dom' =>
  array (
    '' => '',
    'Analyst' => 'Analyst',
    'Competitor' => 'Competitor',
    'Customer' => 'Customer',
    'Integrator' => 'Integrator',
    'Investor' => 'Investor',
    'Partner' => 'Partner',
    'Press' => 'Press',
    'Prospect' => 'Prospect',
    'Reseller' => 'Reseller',
    'Other' => 'Other',
  ),
  //e.g. en espa�ol 'Apparel'=>'Ropa',
  'industry_dom' =>
  array (
    '' => '',
    'Apparel' => 'Apparel',
    'Banking' => 'Banking',
    'Biotechnology' => 'Biotechnology',
    'Chemicals' => 'Chemicals',
    'Communications' => 'Communications',
    'Construction' => 'Construction',
    'Consulting' => 'Consulting',
    'Education' => 'Education',
    'Electronics' => 'Electronics',
    'Energy' => 'Energy',
    'Engineering' => 'Engineering',
    'Entertainment' => 'Entertainment',
    'Environmental' => 'Environmental',
    'Finance' => 'Finance',
    'Government' => 'Government',
    'Healthcare' => 'Healthcare',
    'Hospitality' => 'Hospitality',
    'Insurance' => 'Insurance',
    'Machinery' => 'Machinery',
    'Manufacturing' => 'Manufacturing',
    'Media' => 'Media',
    'Not For Profit' => 'Not For Profit',
    'Recreation' => 'Recreation',
    'Retail' => 'Retail',
    'Shipping' => 'Shipping',
    'Technology' => 'Technology',
    'Telecommunications' => 'Telecommunications',
    'Transportation' => 'Transportation',
    'Utilities' => 'Utilities',
    'Other' => 'Other',
  ),
  'lead_source_default_key' => 'Self Generated',
  'lead_source_dom' =>
  array (
    '' => '',
    'Cold Call' => 'Cold Call',
    'Existing Customer' => 'Existing Customer',
    'Self Generated' => 'Self Generated',
    'Employee' => 'Employee',
    'Partner' => 'Partner',
    'Public Relations' => 'Public Relations',
    'Direct Mail' => 'Direct Mail',
    'Conference' => 'Conference',
    'Trade Show' => 'Trade Show',
    'Web Site' => 'Web Site',
    'Word of mouth' => 'Word of mouth',
    'Other' => 'Other',
  ),
  'opportunity_type_dom' =>
  array (
    '' => '',
    'Existing Business' => 'Existing Business',
    'New Business' => 'New Business',
  ),
  //Note:  do not translate opportunity_relationship_type_default_key
//       it is the key for the default opportunity_relationship_type_dom value
  'opportunity_relationship_type_default_key' => 'Primary Decision Maker',
  'opportunity_relationship_type_dom' =>
  array (
    '' => '',
    'Primary Decision Maker' => 'Primary Decision Maker',
    'Business Decision Maker' => 'Business Decision Maker',
    'Business Evaluator' => 'Business Evaluator',
    'Technical Decision Maker' => 'Technical Decision Maker',
    'Technical Evaluator' => 'Technical Evaluator',
    'Executive Sponsor' => 'Executive Sponsor',
    'Influencer' => 'Influencer',
    'Other' => 'Other',
  ),
  //Note:  do not translate case_relationship_type_default_key
//       it is the key for the default case_relationship_type_dom value
  'case_relationship_type_default_key' => 'Primary Contact',
  'case_relationship_type_dom' =>
  array (
    '' => '',
    'Primary Contact' => 'Primary Contact',
    'Alternate Contact' => 'Alternate Contact',
  ),
  'sales_stage_default_key' => 'Prospecting',
  'sales_stage_dom' =>
  array (
    'Prospecting' => 'Prospecting',
    'Qualification' => 'Qualification',
    'Needs Analysis' => 'Needs Analysis',
    'Value Proposition' => 'Value Proposition',
    'Id. Decision Makers' => 'Id. Decision Makers',
    'Perception Analysis' => 'Perception Analysis',
    'Proposal/Price Quote' => 'Proposal/Price Quote',
    'Negotiation/Review' => 'Negotiation/Review',
    'Closed Won' => 'Closed Won',
    'Closed Lost' => 'Closed Lost',
  ),

  'activity_dom' =>
  array (
    'Call' => 'Call',
    'Meeting' => 'Meeting',
    'Task' => 'Task',
    'Email' => 'Email',
    'Note' => 'Note',
  ),
  'salutation_dom' =>
  array (
    '' => '',
    'Mr.' => 'Mr.',
    'Ms.' => 'Ms.',
    'Mrs.' => 'Mrs.',
    'Dr.' => 'Dr.',
    'Prof.' => 'Prof.',
  ),
  //time is in seconds; the greater the time the longer it takes;
  'reminder_max_time'=>3600,
  'reminder_time_options' => array( 60=> '1 minute prior',
  								  300=> '5 minutes prior',
  								  600=> '10 minutes prior',
  								  900=> '15 minutes prior',
  								  1800=> '30 minutes prior',
  								  3600=> '1 hour prior',
								 ),

  'task_priority_default' => 'Medium',
  'task_priority_dom' =>
  array (
    'High' => 'High',
    'Medium' => 'Medium',
    'Low' => 'Low',
  ),
  'task_status_default' => 'Not Started',
  'task_status_dom' =>
  array (
    'Not Started' => 'Not Started',
    'In Progress' => 'In Progress',
    'Completed' => 'Completed',
    'Pending Input' => 'Pending Input',
    'Deferred' => 'Deferred',
  ),
  'meeting_status_default' => 'Planned',
  'meeting_status_dom' =>
  array (
    'Planned' => 'Planned',
    'Held' => 'Held',
    'Not Held' => 'Not Held',
  ),
  'call_status_default' => 'Planned',
  'call_status_dom' =>
  array (
    'Planned' => 'Planned',
    'Held' => 'Held',
    'Not Held' => 'Not Held',
  ),
  'call_direction_default' => 'Outbound',
  'call_direction_dom' =>
  array (
    'Inbound' => 'Inbound',
    'Outbound' => 'Outbound',
  ),
  'lead_status_dom' =>
  array (
    '' => '',
    'New' => 'New',
    'Assigned' => 'Assigned',
    'In Process' => 'In Process',
    'Converted' => 'Converted',
    'Recycled' => 'Recycled',
    'Dead' => 'Dead',
  ),
  'lead_status_noblank_dom' =>
  array (
    'New' => 'New',
    'Assigned' => 'Assigned',
    'In Process' => 'In Process',
    'Converted' => 'Converted',
    'Recycled' => 'Recycled',
    'Dead' => 'Dead',
  ),
  //Note:  do not translate case_status_default_key
//       it is the key for the default case_status_dom value
  'case_status_default_key' => 'New',
  'case_status_dom' =>
  array (
    'New' => 'New',
    'Assigned' => 'Assigned',
    'Closed' => 'Closed',
    'Pending Input' => 'Pending Input',
    'Rejected' => 'Rejected',
    'Duplicate' => 'Duplicate',
  ),
  'case_priority_default_key' => 'P2',
  'case_priority_dom' =>
  array (
    'P1' => 'High',
    'P2' => 'Medium',
    'P3' => 'Low',
  ),
  'user_status_dom' =>
  array (
    'Active' => 'Active',
    'Inactive' => 'Inactive',
  ),
  'employee_status_dom' =>
  array (
    'Active' => 'Active',
    'Terminated' => 'Terminated',
    'Leave of Absence' => 'Leave of Absence',
  ),
  'messenger_type_dom' =>
  array (
    'MSN' => 'MSN',
    'Yahoo!' => 'Yahoo!',
    'AOL' => 'AOL',
  ),

	'project_task_priority_options' => array (
		'High' => 'High',
		'Medium' => 'Medium',
		'Low' => 'Low',
	),
	'project_task_status_options' => array (
		'Not Started' => 'Not Started',
		'In Progress' => 'In Progress',
		'Completed' => 'Completed',
		'Pending Input' => 'Pending Input',
		'Deferred' => 'Deferred',
	),
	'project_task_utilization_options' => array (
		'0' => 'none',
		'25' => '25',
		'50' => '50',
		'75' => '75',
		'100' => '100',
	),
  //Note:  do not translate record_type_default_key
//       it is the key for the default record_type_module value
  'record_type_default_key' => 'Accounts',
  'record_type_display' =>
  array (
    'Accounts' => 'Account',
    'Opportunities' => 'Opportunity',
    'Cases' => 'Case',
    'Leads' => 'Lead',




    'Bugs' => 'Bug',
    'Project' => 'Project',
    'ProjectTask' => 'Project Task',
  ),

  'record_type_display_notes' =>
  array (
    'Accounts' => 'Account',
    'Opportunities' => 'Opportunity',
    'Cases' => 'Case',
    'Leads' => 'Lead',




    'Bugs' => 'Bug',
    'Emails' => 'Email',
    'Project' => 'Project',
    'ProjectTask' => 'Project Task',
  ),






































	
  'quote_type_dom' =>
  array (
    'Quotes' => 'Quote',
    'Orders' => 'Order',
  ),
  'default_quote_stage_key' => 'Draft',
  'quote_stage_dom' =>
  array (
    'Draft' => 'Draft',
    'Negotiation' => 'Negotiation',
    'Delivered' => 'Delivered',
    'On Hold' => 'On Hold',
    'Confirmed' => 'Confirmed',
    'Closed Accepted' => 'Closed Accepted',
    'Closed Lost' => 'Closed Lost',
    'Closed Dead' => 'Closed Dead',
  ),
  'default_order_stage_key' => 'Pending',
  'order_stage_dom' =>
  array (
    'Pending' => 'Pending',
    'Confirmed' => 'Confirmed',
    'On Hold' => 'On Hold',
    'Shipped' => 'Shipped',
    'Cancelled' => 'Cancelled',
  ),

//Note:  do not translate quote_relationship_type_default_key
//       it is the key for the default quote_relationship_type_dom value
  'quote_relationship_type_default_key' => 'Primary Decision Maker',
  'quote_relationship_type_dom' =>
  array (
    '' => '',
    'Primary Decision Maker' => 'Primary Decision Maker',
    'Business Decision Maker' => 'Business Decision Maker',
    'Business Evaluator' => 'Business Evaluator',
    'Technical Decision Maker' => 'Technical Decision Maker',
    'Technical Evaluator' => 'Technical Evaluator',
    'Executive Sponsor' => 'Executive Sponsor',
    'Influencer' => 'Influencer',
    'Other' => 'Other',
  ),
  'layouts_dom' =>
  array (
    'Standard' => 'Standard',
    'Terms' => 'Payment Terms'
  ),

  'bug_priority_default_key' => 'Medium',
  'bug_priority_dom' =>
  array (
    'Urgent' => 'Urgent',
    'High' => 'High',
    'Medium' => 'Medium',
    'Low' => 'Low',
  ),
   'bug_resolution_default_key' => '',
  'bug_resolution_dom' =>
  array (
  	'' => '',
  	'Accepted' => 'Accepted',
    'Duplicate' => 'Duplicate',
    'Fixed' => 'Fixed',
    'Out of Date' => 'Out of Date',
    'Invalid' => 'Invalid',
    'Later' => 'Later',
  ),
  'bug_status_default_key' => 'New',
  'bug_status_dom' =>
  array (
    'New' => 'New',
    'Assigned' => 'Assigned',
    'Closed' => 'Closed',
    'Pending' => 'Pending',
    'Rejected' => 'Rejected',
  ),
   'bug_type_default_key' => 'Bug',
  'bug_type_dom' =>
  array (
    'Defect' => 'Defect',
    'Feature' => 'Feature',
  ),

  'source_default_key' => '',
  'source_dom' =>
  array (
	'' => '',
  	'Internal' => 'Internal',
  	'Forum' => 'Forum',
  	'Web' => 'Web',
  ),

  'product_category_default_key' => '',
  'product_category_dom' =>
  array (
	'' => '',
  	'Accounts' => 'Accounts',
  	'Activities' => 'Activities',
  	'Bug Tracker' => 'Bug Tracker',
  	'Calendar' => 'Calendar',
  	'Calls' => 'Calls',
  	'Campaigns' => 'Campaigns',  	
  	'Cases' => 'Cases',
  	'Contacts' => 'Contacts',
  	'Currencies' => 'Currencies',
  	'Dashboard' => 'Dashboard',
  	'Documents' => 'Documents',
  	'Emails' => 'Emails',
  	'Feeds' => 'Feeds',
  	'Forecasts' => 'Forecasts',  	
  	'Help' => 'Help',
  	'Home' => 'Home',
  	'Leads' => 'Leads',
  	'Meetings' => 'Meetings',
  	'Notes' => 'Notes',
  	'Opportunities' => 'Opportunities',
  	'Outlook Plugin' => 'Outlook Plugin',
  	'Products' => 'Products',  	
  	'Projects' => 'Projects',  	
  	'Quotes' => 'Quotes',
  	'Releases' => 'Releases',
  	'RSS' => 'RSS',
  	'Studio' => 'Studio',
  	'Upgrade' => 'Upgrade',
  	'Users' => 'Users',
  ),

  'campaign_status_dom' =>
  array (
    '' => '',
        'Planning' => 'Planning',
        'Active' => 'Active',
        'Inactive' => 'Inactive',
        'Complete' => 'Complete',
  ),
  'campaign_type_dom' =>
  array (
        '' => '',
        'Telesales' => 'Telesales',
        'Mail' => 'Mail',
        'Email' => 'Email',
        'Print' => 'Print',
        'Web' => 'Web',
        'Radio' => 'Radio',
        'Television' => 'Television',
        ),



  'notifymail_sendtype' =>
  array (
    'sendmail' => 'sendmail',
    'SMTP' => 'SMTP',
  ),
  'dom_timezones' => array('-12'=>'(GMT - 12) International Date Line West',
  							'-11'=>'(GMT - 11) Midway Island, Samoa',
  							'-10'=>'(GMT - 10) Hawaii',
  							'-9'=>'(GMT - 9) Alaska',
  							'-8'=>'(GMT - 8) San Francisco',
  							'-7'=>'(GMT - 7) Phoenix',
  							'-6'=>'(GMT - 6) Saskatchewan',
  							'-5'=>'(GMT - 5) New York',
  							'-4'=>'(GMT - 4) Santiago',
  							'-3'=>'(GMT - 3) Buenos Aires',
  							'-2'=>'(GMT - 2) Mid-Atlantic',
  							'-1'=>'(GMT - 1) Azores',
  							'0'=>'(GMT)',
  							'1'=>'(GMT + 1) Madrid',
  							'2'=>'(GMT + 2) Athens',
  							'3'=>'(GMT + 3) Moscow',
  							'4'=>'(GMT + 4) Kabul',
  							'5'=>'(GMT + 5) Ekaterinburg',
  							'6'=>'(GMT + 6) Astana',
  							'7'=>'(GMT + 7) Bangkok',
  							'8'=>'(GMT + 8) Perth',
  							'9'=>'(GMT + 9) Seol',
  							'10'=>'(GMT + 10) Brisbane',
  							'11'=>'(GMT + 11) Solomone Is.',
  							'12'=>'(GMT + 12) Auckland',
  							),
      'dom_cal_month_long'=>array(
                '0'=>"",
                '1'=>"January",
                '2'=>"February",
                '3'=>"March",
                '4'=>"April",
                '5'=>"May",
                '6'=>"June",
                '7'=>"July",
                '8'=>"August",
                '9'=>"September",
                '10'=>"October",
                '11'=>"November",
                '12'=>"December",
        ),

        'dom_report_types'=>array(
                'tabular'=>'Rows and Columns',
                'summary'=>'Summation',
                'detailed_summary'=>'Summation with details',
        ),
        'dom_email_types'=>array(
                'out'=>'Sent',
                'archived'=>'Archived',
                'draft'=>'Draft',
        ),
	'forecast_schedule_status_dom' =>
  	array (
    'Active' => 'Active',
    'Inactive' => 'Inactive',
  ),
	'forecast_type_dom' =>
  	array (
    'Direct' => 'Direct',
    'Rollup' => 'Rollup',
  ),  
	
	'document_category_dom' =>
  	array (
  	'' => '',
    'Marketing' => 'Marketing',
    'Knowledege Base' => 'Knowledge Base',
    'Sales' => 'Sales',    
  ),  

	'document_subcategory_dom' =>
  	array (
  	'' => '',
    'Marketing Collateral' => 'Marketing Collateral',
    'Product Brochures' => 'Product Brochures',
	'FAQ' => 'FAQ',
  ),  
  
	'document_status_dom' =>
  	array (
    'Active' => 'Active',
    'Draft' => 'Draft',
	'FAQ' => 'FAQ',
	'Expired' => 'Expired',
	'Under Review' => 'Under Review',
	'Pending' => 'Pending',
  ),
	'dom_meeting_accept_options' =>
  	array (
	'accept' => 'Accept',
	'decline' => 'Decline',
	'tentative' => 'Tentative',
  ),
	'dom_meeting_accept_status' =>
  	array (
	'accept' => 'Accepted',
	'decline' => 'Declined',
	'tentative' => 'Tentative',
  ),
);

$app_strings = array (
  'LBL_SERVER_RESPONSE_TIME' => 'Server response time:',
  'LBL_SERVER_RESPONSE_TIME_SECONDS' => 'seconds.',
  'LBL_CHARSET' => 'ISO-8859-1',
  'LBL_BROWSER_TITLE' => 'SugarCRM - Commercial Open Source CRM',
  'LBL_MY_ACCOUNT' => 'My Account',
  'LBL_EMPLOYEES' => 'Employees',
  'LBL_ADMIN' => 'Admin',
  'LBL_LOGOUT' => 'Logout',
  'LBL_SEARCH' => 'Search',
  'LBL_LAST_VIEWED' => 'Last Viewed',
  'NTC_WELCOME' => 'Welcome',
  'NTC_SUPPORT_SUGARCRM' => 'Support the SugarCRM open source project with a donation through PayPal - it\'s fast, free and secure!',
  'NTC_NO_ITEMS_DISPLAY' => 'none',
  'LBL_ALT_HOT_KEY' => 'Alt+',
  'LBL_SAVE_BUTTON_TITLE' => 'Save [Alt+S]',
  'LBL_EDIT_BUTTON_TITLE' => 'Edit [Alt+E]',
  'LBL_EDIT_BUTTON' => 'Edit',
  'LBL_DUPLICATE_BUTTON_TITLE' => 'Duplicate [Alt+U]',
  'LBL_DUPLICATE_BUTTON' => 'Duplicate',
  'LBL_DELETE_BUTTON_TITLE' => 'Delete [Alt+D]',
  'LBL_DELETE_BUTTON' => 'Delete',
  'LBL_NEW_BUTTON_TITLE' => 'Create [Alt+N]',
  'LBL_CHANGE_BUTTON_TITLE' => 'Change [Alt+G]',
  'LBL_CANCEL_BUTTON_TITLE' => 'Cancel [Alt+X]',
  'LBL_SEARCH_BUTTON_TITLE' => 'Search [Alt+Q]',
  'LBL_CLEAR_BUTTON_TITLE' => 'Clear [Alt+C]',
  'LBL_SELECT_BUTTON_TITLE' => 'Select [Alt+T]',
  'LBL_ADD_BUTTON' => 'Add',
  'LBL_ADD_BUTTON_TITLE' => 'Add [Alt+A]',
  'LBL_ADD_BUTTON_KEY' => 'A',
  'LBL_SAVE_BUTTON_KEY' => 'S',
  'LBL_EDIT_BUTTON_KEY' => 'E',
  'LBL_DUPLICATE_BUTTON_KEY' => 'U',
  'LBL_DELETE_BUTTON_KEY' => 'D',
  'LBL_NEW_BUTTON_KEY' => 'N',
  'LBL_CHANGE_BUTTON_KEY' => 'G',
  'LBL_CANCEL_BUTTON_KEY' => 'X',
  'LBL_SEARCH_BUTTON_KEY' => 'Q',
  'LBL_CLEAR_BUTTON_KEY' => 'C',
  'LBL_SELECT_BUTTON_KEY' => 'T',
  'LBL_SAVE_BUTTON_LABEL' => 'Save',
  'LBL_EDIT_BUTTON_LABEL' => 'Edit',
  'LBL_DUPLICATE_BUTTON_LABEL' => 'Duplicate',
  'LBL_DELETE_BUTTON_LABEL' => 'Delete',
  'LBL_NEW_BUTTON_LABEL' => 'Create',
  'LBL_CHANGE_BUTTON_LABEL' => 'Change',
  'LBL_CANCEL_BUTTON_LABEL' => 'Cancel',
  'LBL_SEARCH_BUTTON_LABEL' => 'Search',
  'LBL_CLEAR_BUTTON_LABEL' => 'Clear',
  'LBL_NEXT_BUTTON_LABEL' => 'Next',
  'LBL_SELECT_BUTTON_LABEL' => 'Select',
  'LBL_SELECT_CONTACT_BUTTON_TITLE' => 'Select Contact [Alt+T]',
  'LBL_SELECT_CONTACT_BUTTON_KEY' => 'T',
  'LBL_VIEW_PDF_BUTTON_LABEL' => 'Print as PDF',
  'LBL_VIEW_PDF_BUTTON_TITLE' => 'Print as PDF [Alt+P]',
  'LBL_VIEW_PDF_BUTTON_KEY' => 'P',
  'LBL_QUOTE_TO_OPPORTUNITY_LABEL' => 'Create Opportunity from Quote',
  'LBL_QUOTE_TO_OPPORTUNITY_TITLE' => 'Create Opportunity from Quote [Alt+O]',
  'LBL_QUOTE_TO_OPPORTUNITY_KEY' => 'O',
  'LBL_SELECT_CONTACT_BUTTON_LABEL' => 'Select Contact',
  'LBL_SELECT_USER_BUTTON_TITLE' => 'Select User [Alt+U]',
  'LBL_SELECT_USER_BUTTON_KEY' => 'U',
  'LBL_SELECT_USER_BUTTON_LABEL' => 'Select User',
  'LBL_CREATE_BUTTON_LABEL' => 'Create',
  'LBL_SELECT_REPORTS_BUTTON_TITLE' => 'Select Reports',
  'LBL_SELECT_REPORTS_BUTTON_LABEL' => 'Select from Reports',
  'LBL_DONE_BUTTON_KEY' => 'X',
  'LBL_DONE_BUTTON_TITLE' => 'Done [Alt+X]',
  'LBL_DONE_BUTTON_LABEL' => 'Done',
  'LBL_SHORTCUTS' => 'Shortcuts',
  'LBL_LIST_NAME' => 'Name',



  'LBL_LIST_USER_NAME' => 'User Name',
  'LBL_LIST_EMAIL' => 'Email',
  'LBL_LIST_PHONE' => 'Phone',
  'LBL_LIST_CONTACT_NAME' => 'Contact Name',
  'LBL_LIST_CONTACT_ROLE' => 'Contact Role',
  'LBL_LIST_ACCOUNT_NAME' => 'Account Name',
  'LBL_USER_LIST' => 'User List',
  'LBL_CONTACT_LIST' => 'Contact List',
  'LBL_RELATED_RECORDS' => 'Related Records',
  'LBL_MASS_UPDATE' => 'Mass Update',
  'LNK_ADVANCED_SEARCH' => 'Advanced',
  'LNK_BASIC_SEARCH' => 'Basic',
  'LNK_EDIT' => 'edit',
  'LNK_REMOVE' => 'rem',
  'LNK_DELETE' => 'del',
  'LNK_LIST_START' => 'Start',
  'LNK_LIST_NEXT' => 'Next',
  'LNK_LIST_PREVIOUS' => 'Previous',
  'LNK_LIST_END' => 'End',
  'LBL_LIST_OF' => 'of',
  'LBL_OR' => 'OR',
  'LBL_BY' => 'by',
  'LNK_PRINT' => 'Print',
  'LNK_HELP' => 'Help',
  'LNK_ABOUT' => 'About',
  'NTC_REQUIRED' => 'Indicates required field',
  'LBL_REQUIRED_SYMBOL' => '*',
  'LBL_CURRENCY_SYMBOL' => '$',
  'LBL_PERCENTAGE_SYMBOL' => '%',
  'LBL_THOUSANDS_SYMBOL' => 'K',
  'NTC_YEAR_FORMAT' => '(yyyy)',
  'NTC_DATE_FORMAT' => '(yyyy-mm-dd)',
  'NTC_TIME_FORMAT' => '(24:00)',
  'NTC_DATE_TIME_FORMAT' => '(yyyy-mm-dd 24:00)',
  'NTC_DELETE_CONFIRMATION' => 'Are you sure you want to delete this record?',
  'ERR_DELETE_RECORD' => 'A record number must be specified to delete the contact.',
  'ERR_CREATING_TABLE' => 'Error creating table: ',
  'ERR_CREATING_FIELDS' => 'Error filling in additional detail fields: ',
  'ERR_MISSING_REQUIRED_FIELDS' => 'Missing required field:',
  'ERR_INVALID_EMAIL_ADDRESS' => 'not a valid email address.',
  'ERR_INVALID_DATE_FORMAT' => 'The date format must be: yyyy-mm-dd',
  'ERR_INVALID_MONTH' => 'Please enter a valid month.',
  'ERR_INVALID_DAY' => 'Please enter a valid day.',
  'ERR_INVALID_YEAR' => 'Please enter a valid 4 digit year.',
  'ERR_INVALID_DATE' => 'Please enter a valid date.',
  'ERR_INVALID_HOUR' => 'Please enter a valid hour.',
  'ERR_INVALID_TIME' => 'Please enter a valid time.',
  'ERR_INVALID_AMOUNT' => 'Please enter a valid amount.',
  'NTC_CLICK_BACK' => 'Please click the browser back button and fix the error.',
  'LBL_LIST_ASSIGNED_USER' => 'User',
  'LBL_ASSIGNED_TO' => 'Assigned to:',
  'LBL_DATE_MODIFIED' => 'Last Modified:',
  'LBL_DATE_ENTERED' => 'Created:',
  'LBL_CURRENT_USER_FILTER' => 'Only my items:',
  'NTC_LOGIN_MESSAGE' => 'Please enter your username and password.',
  'LBL_NONE' => '--None--',
  'LBL_BACK' => 'Back',
  'LBL_IMPORT' => 'Import',
  'LBL_EXPORT' => 'Export',
  'LBL_EXPORT_ALL' => 'Export All',
  'LBL_SAVE_NEW_BUTTON_TITLE' => 'Save & Create New [Alt+V]',
  'LBL_SAVE_NEW_BUTTON_KEY' => 'V',
  'LBL_SAVE_NEW_BUTTON_LABEL' => 'Save & Create New',
  'LBL_NAME' => 'Name',



  'LBL_CHECKALL' => 'Check All',
  'LBL_CLEARALL' => 'Clear All',
  'LBL_SUBJECT' => 'Subject',
  'LBL_ENTER_DATE' => 'Enter Date',
  'LBL_CREATED' => 'Created by',
  'LBL_MODIFIED' => 'Modified by',
  'LBL_DELETED'=>'Deleted',
  'LBL_TEAM_ID'=>'Team:',
  'LBL_ID'=>'ID',
  'LBL_COMPOSE_EMAIL_BUTTON_TITLE' => 'Compose Email [Alt+L]',
  'LBL_COMPOSE_EMAIL_BUTTON_KEY' => 'L',
  'LBL_COMPOSE_EMAIL_BUTTON_LABEL' => 'Compose Email',
  'ERR_OPPORTUNITY_NAME_MISSING' => 'An opportunity name was not entered.  Please enter an opportunity name below.',
  'ERR_OPPORTUNITY_NAME_DUPE' => 'An opportunity with the name %s already exists.  Please enter another name below.',
  'LBL_OPPORTUNITY_NAME' => 'Opportunity Name',
  'LBL_DELETE' => 'Delete',
  'LBL_UPDATE' => 'Update',
  'LBL_STATUS_UPDATED'=>'Your Status for this event has been updated!',
  'LBL_STATUS'=>'Status:',
  'LBL_CLOSE_WINDOW'=>'Close Window',

);

?>
