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
 * $Id: ImportAccount.php,v 1.16.2.1 2005/05/06 00:35:44 clint Exp $
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
class ImportAccount extends Account {
	 var $db;

	// these are fields that may be set on import
	// but are to be processed and incorporated
	// into fields of the parent class


	// This is the list of fields that are required.
	var $required_fields =  array("name"=>1);
	
	// This is the list of the functions to run when importing
	var $special_functions =  array(
	"add_billing_address_streets"
	,"add_create_assigned_user_name"
	,"add_shipping_address_streets"
	,"fix_website"
	,"add_industry"
	,"add_type"
	 );


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

        $result = mysqli_query($varconnect, $query)
               or sugar_die("Error selecting sugarbean: ".mysqli_error($varconnect));

        $row = $this->db->fetchByAssoc($result, -1, false);

		// we found a row with that id
                if (isset($row['id']) && $row['id'] != -1)
                {
                        // if it exists but was deleted, just remove it entirely
                        if ( isset($row['deleted']) && $row['deleted'] == 1)
                        {
                                $query2 = "delete from {$focus->table_name} WHERE id='". PearDatabase::quote($row['id'])."'";

                                $this->log->info($query2);

                                $result2 = mysqli_query($varconnect, $query2)
                                        or sugar_die("Error deleting existing sugarbean: ".mysqli_error($varconnect));

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

    function fix_website()
	{
		if ( isset($this->website) &&
			preg_match("/^http:\/\//",$this->website) )
		{
			$this->website = substr($this->website,7);
		}	
	}

	
	function add_industry()
	{
		global $app_list_strings;
		if ( isset($this->industry) &&
			! isset( $app_list_strings['industry_dom'][$this->industry]))
		{
			unset($this->industry);
		}	
	}

	function add_type()
	{
		global $app_list_strings;
		if ( isset($this->type) &&
			! isset($app_list_strings['account_type_dom'][$this->type]))
		{
			unset($this->type);
		}	
	}

	function add_billing_address_streets() 
	{ 
		if ( isset($this->billing_address_street_2)) 
		{ 
			$this->billing_address_street .= 
				" ". $this->billing_address_street_2; 
		} 

		if ( isset($this->billing_address_street_3)) 
		{  
			$this->billing_address_street .= 
				" ". $this->billing_address_street_3; 
		} 
		if ( isset($this->billing_address_street_4)) 
		{  
			$this->billing_address_street .= 
				" ". $this->billing_address_street_4; 
		}
	}

	function add_shipping_address_streets() 
	{ 
		if ( isset($this->shipping_address_street_2)) 
		{ 
			$this->shipping_address_street .= 
				" ". $this->shipping_address_street_2; 
		} 

		if ( isset($this->shipping_address_street_3)) 
		{  
			$this->shipping_address_street .= 
				" ". $this->shipping_address_street_3; 
		} 

		if ( isset($this->shipping_address_street_4)) 
		{  
			$this->shipping_address_street .= 
				" ". $this->shipping_address_street_4; 
		} 
	}


	//module prefix used by ImportSteplast when calling ListView.php
	var $list_view_prefix = 'ACCOUNT';

	// This is the list of fields that are importable.
	// some of these may not map directly to database columns
	var $importable_fields = Array(
		"id"=>1
		,"assigned_user_name"=>1
	    ,"name"=>1
		,"website"=>1
		,"industry"=>1
		,"account_type"=>1
		,"ticker_symbol"=>1
		,"parent_name"=>1
		,"employees"=>1
		,"ownership"=>1
		,"phone_office"=>1
		,"phone_fax"=>1
		,"phone_alternate"=>1
		,"email1"=>1
		,"email2"=>1
		,"rating"=>1
		,"sic_code"=>1
		,"annual_revenue"=>1
		,"billing_address_street"=>1
		,"billing_address_street_2"=>1
		,"billing_address_street_3"=>1
		,"billing_address_street_4"=>1
		,"billing_address_city"=>1
		,"billing_address_state"=>1
		,"billing_address_postalcode"=>1
		,"billing_address_country"=>1
		,"shipping_address_street"=>1
		,"shipping_address_street_2"=>1
		,"shipping_address_street_3"=>1
		,"shipping_address_street_4"=>1
		,"shipping_address_city"=>1
		,"shipping_address_state"=>1
		,"shipping_address_postalcode"=>1
		,"shipping_address_country"=>1
		,"description"=>1
		);

	//columns to be displayed in listview for displaying user's last import in ImportSteplast.php
	var $list_fields = Array(
			 'id'
			,'name'
			,'website'
			,'phone_office'
			,'billing_address_city'
			,'assigned_user_name'
			,'assigned_user_id'
			);

	//this list defines what beans get populated during an import of accounts
	var $related_modules = array("Accounts",); 
	

	function ImportAccount() {
		parent::Account();
	}

}



?>
