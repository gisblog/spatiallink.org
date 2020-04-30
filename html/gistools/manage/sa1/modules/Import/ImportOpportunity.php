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
 * $Id: ImportOpportunity.php,v 1.16.2.1 2005/05/06 00:35:44 clint Exp $
 * Description:  Defines the Account SugarBean Account entity with the necessary
 * methods and variables.
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('data/SugarBean.php');
require_once('modules/Contacts/Contact.php');
require_once('modules/Opportunities/Opportunity.php');
require_once('modules/Cases/Case.php');
require_once('modules/Calls/Call.php');
require_once('modules/Notes/Note.php');
require_once('modules/Emails/Email.php');
require_once('modules/Accounts/Account.php');

global $app_list_strings;

// Account is used to store account information.
class ImportOpportunity extends Opportunity {
	 var $db;

	// these are fields that may be set on import
	// but are to be processed and incorporated
	// into fields of the parent class


	// This is the list of fields that are required.
	var $required_fields =  array(
				"name"=>1,
				"account_name"=>1,
				"date_closed"=>1,
				"sales_stage"=>1
);
	
	// This is the list of the functions to run when importing
	var $special_functions =  array(
		"add_create_assigned_user_name",
		"add_create_account",
		"add_lead_source",
		"add_opportunity_type",
        	"add_date_closed",
        	"add_sales_stage"
	 );

        function add_lead_source()
        {
		global $app_list_strings;
                if ( isset($this->lead_source) &&
                        ! isset( $app_list_strings['lead_source_dom'][ $this->lead_source ]) )
                {
                        $this->lead_source = '';
                }

        }

        function add_sales_stage()
        {
                global $app_list_strings;

                if ( isset($this->sales_stage) &&
                        ! isset( $app_list_strings['sales_stage_dom'][ $this->sales_stage ]) )
                {
                        $this->sales_stage = 'Prospecting';
                }


	}

        function add_opportunity_type()
        {
                global $app_list_strings;

                if ( isset($this->opportunity_type) &&
                        ! isset( $app_list_strings['opportunity_type_dom'][ $this->opportunity_type ]) )
                {
                        $this->opportunity_type = '';
                }

        }

        function add_date_closed()
        {
                if ( isset($this->date_closed))
                {
                        if ( preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/',$this->date_closed,$match))
                        {
                                $this->date_closed = $match[3]."-".$match[1]."-".$match[2];
                        }

                        if (! preg_match('/^\d{4}-\d{1,2}-\d{1,2}$/',$this->date_closed))
                        {
                                $this->date_closed = '';
                        }
                }

        }

        function add_create_assigned_user_name()
        {
		// global is defined in UsersLastImport.php
		global $imported_ids;
                global $current_user;

		if ( empty($this->assigned_user_name))
		{
			return;
		}

		$user_name = $this->assigned_user_name;
		
		// check if it already exists
        $focus = new User();

       	$query = "select * from {$focus->table_name} WHERE user_name='{$user_name}'";

        $this->log->info($query);

        $result = mysql_query($query)
               or sugar_die("Error selecting sugarbean: ".mysql_error());

        $row = $this->db->fetchByAssoc($result, -1, false);

		// we found a row with that id
                if (isset($row['id']) && $row['id'] != -1)
                {
                        // if it exists but was deleted, just remove it entirely
                        if ( isset($row['deleted']) && $row['deleted'] == 1)
                        {
                                $query2 = "delete from {$focus->table_name} WHERE id='". PearDatabase::quote($row['id'])."'";

                                $this->log->info($query2);

                                $result2 = mysql_query($query2)
                                        or sugar_die("Error deleting existing sugarbean: ".mysql_error());

                        }

			// else just use this id to link the user to the contact
                        else
                        {
                                $focus->id = $row['id'];
                        }
                }

		// if we didnt find the user, just create it
                if (! isset($focus->id) || $focus->id == '')
                {
                        $focus->last_name = $user_name;
                        $focus->user_name = $user_name;
						$focus->status = 'Active';
                        $focus->save();
			// avoid duplicate mappings:
			if (! isset( $imported_ids[$focus->id]) )
			{
				// save the new account as a users_last_import
                		$last_import = new UsersLastImport();
                		$last_import->assigned_user_id = $current_user->id;
                		$last_import->bean_type = "Users";
                		$last_import->bean_id = $focus->id;
                		$last_import->save();
				$imported_ids[$focus->id] = 1;
			}
                }

		// now just link the account
                $this->assigned_user_id = $focus->id;
                $this->modified_user_id = $focus->id;

        }

	//exactly the same function from ImportAccount.php
	// lets put this in one place.. 

        function add_create_account()
        {
                // global is defined in UsersLastImport.php
                global $imported_ids;
                global $current_user;

                if ( (! isset($this->account_name) || $this->account_name == '') &&
                        (! isset($this->account_id) || $this->account_id == '') )
                {
                        return;
                }

                $arr = array();

                // check if it already exists
                $focus = new Account();

                $query = '';

                // if user is defining the account id to be associated with this contact..
                if ( isset($this->account_id) && $this->account_id != '')
                {
                        $query = "select * from {$focus->table_name} WHERE id='".  PearDatabase::quote($this->account_id)."'"
;
                }
                // else user is defining the account name to be associated with this contact..
                else
                {
                        $query = "select * from {$focus->table_name} WHERE name='".  PearDatabase::quote($this->account_name)."'";
                }

                $this->log->info($query);

                $result = mysql_query($query)
                       or sugar_die("Error selecting sugarbean: ".mysql_error());

                $row = $this->db->fetchByAssoc($result, -1, false);
                // if we found a row with that id
                if (isset($row['id']) && $row['id'] != -1)
                {
                        // if it exists but was deleted, just remove it entirely
                        if ( isset($row['deleted']) && $row['deleted'] == 1)
                        {
                                $query2 = "delete from {$focus->table_name} WHERE id='".  PearDatabase::quote($row['id']) ."'";

                                $this->log->info($query2);

                                $result2 = mysql_query($query2)
                                        or sugar_die("Error deleting existing sugarbean: ".mysql_error());

                        }
                        // else just use this id to link the contact to the account
                        else
                        {
                                $focus->id = $row['id'];
                        }
                }

                // we didnt find the account, so create it
                if (! isset($focus->id) || $focus->id == '')
                {
                        $focus->name = $this->account_name;
                        $focus->assigned_user_id = $current_user->id;
                        $focus->modified_user_id = $current_user->id;

                        if ( isset($this->account_id)  &&
                                $this->account_id != '')
                        {
                                $focus->new_with_id = true;
                                $focus->id = $this->account_id;
                        }

                        $focus->save();
                        // avoid duplicate mappings:
                        if (! isset( $imported_ids[$focus->id]) )
                        {
                                // save the new account as a users_last_import
                                $last_import = new UsersLastImport();
                                $last_import->assigned_user_id = $current_user->id;
                                $last_import->bean_type = "Accounts";
                                $last_import->bean_id = $focus->id;
                                $last_import->save();
                                $imported_ids[$focus->id] = 1;
                        }
                }

                $this->account_id = $focus->id;

	}



	function fix_website()
	{
		if ( isset($this->website) &&
			preg_match("/^http:\/\//",$this->website) )
		{
			$this->website = substr($this->website,7);
		}	
	}

	
	//module prefix used by ImportSteplast when calling ListView.php
	var $list_view_prefix = 'OPPORTUNITY';

	// This is the list of fields that are importable.
	// some may not map directly to database columns
	var $importable_fields = Array(
		"id"=>1
                , "name"=>1
				,"assigned_user_name"=>1
                , "account_id"=>1
                , "account_name"=>1
                , "opportunity_type"=>1
                , "lead_source"=>1
                , "amount"=>1
                , "date_entered"=>1
                , "date_closed"=>1
                , "next_step"=>1
                , "sales_stage"=>1
                , "probability"=>1
                , "description"=>1
		);
		
	//columns to be displayed in listview for displaying user's last import in ImportSteplast.php
	var $list_fields = Array(
						'id' 
						,'name'
						,'account_id'
						,'account_name'
						,'amount'
						,'date_closed'
						,'assigned_user_name'
						,'assigned_user_id'
						);
						
	//this list defines what beans get populated during an import of opportunities
	var $related_modules = array("Opportunities","Accounts"); 

function ImportOpportunity() {
		parent::Opportunity();
	}

}



?>
