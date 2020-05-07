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
 * $Id: en_us.lang.php,v 1.43 2005/04/28 08:11:29 robert Exp $
 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

$mod_strings = array (
  'LBL_MODULE_NAME' => 'Opportunities',
  'LBL_MODULE_TITLE' => 'Opportunities: Home',
  'LBL_SEARCH_FORM_TITLE' => 'Opportunity Search',
  'LBL_LIST_FORM_TITLE' => 'Opportunity List',
  'LBL_OPPORTUNITY_NAME' => 'Opportunity Name:',
  'LBL_OPPORTUNITY' => 'Opportunity:',
  'LBL_NAME' => 'Opportunity Name',
  'LBL_INVITEE' => 'Contacts',
  'LBL_LIST_OPPORTUNITY_NAME' => 'Opportunity',
  'LBL_LIST_ACCOUNT_NAME' => 'Account Name',
  'LBL_LIST_AMOUNT' => 'Amount',
  'LBL_LIST_DATE_CLOSED' => 'Close',
  'LBL_LIST_SALES_STAGE' => 'Sales Stage',
//DON'T CONVERT THESE THEY ARE MAPPINGS
  'db_sales_stage' => 'LBL_LIST_SALES_STAGE',
  'db_name' => 'LBL_NAME',
  'db_amount' => 'LBL_LIST_AMOUNT',
  'db_date_closed' => 'LBL_LIST_DATE_CLOSED',
//END DON'T CONVERT
  'UPDATE' => 'Opportunity - Currency Update',
  'UPDATE_DOLLARAMOUNTS' => 'Update U.S. Dollar Amounts',
  'UPDATE_VERIFY' => 'Verify Amounts',
  'UPDATE_VERIFY_TXT' => 'Verifies that the amount values in opportunities are valid decimal numbers with only numeric characters(0-9) and decimals(.)',
  'UPDATE_FIX' => 'Fix Amounts',
  'UPDATE_FIX_TXT' => 'Attempts to fix any invalid amounts by creating a valid decimal from the current amount. This will backup any amounts it modifies into a database field amount_backup. If you run this and notice bugs, do not rerun this without restoring from the backup as it may overwrite the backup with new invalid data.',
  'UPDATE_DOLLARAMOUNTS_TXT' => 'Update the U.S. Dollar amounts for opportunities based on the current set currency rates. This value is used to calculate Graphs and List View Currency Amounts.',
  'UPDATE_CREATE_CURRENCY' => 'Creating New Currency:',
  'UPDATE_VERIFY_FAIL' => 'Record Failed Verification:',
  'UPDATE_VERIFY_CURAMOUNT' => 'Current Amount:',
  'UPDATE_VERIFY_FIX' => 'Running Fix would give',
  'UPDATE_INCLUDE_CLOSE' => 'Include Closed Records',
  'UPDATE_VERIFY_NEWAMOUNT' => 'New Amount:',
  'UPDATE_VERIFY_NEWCURRENCY' => 'New Currency:',
  'UPDATE_DONE' => 'Done',
  'UPDATE_BUG_COUNT' => 'Bugs Found and Attempted to Resolve:',
  'UPDATE_BUGFOUND_COUNT' => 'Bugs Found:',
  'UPDATE_COUNT' => 'Records Updated:',
  'UPDATE_RESTORE_COUNT' => 'Record Amounts Restored:',
  'UPDATE_RESTORE' => 'Restore Amounts',
  'UPDATE_RESTORE_TXT' => 'Restores amount values from the backups created during Fix.',
  'UPDATE_FAIL' => 'Could not update - ',
  'UPDATE_NULL_VALUE' => 'Amount is NULL setting it to 0 -',
  'UPDATE_MERGE' => 'Merge Currencies',
  'UPDATE_MERGE_TXT' => 'Merge multiple currencies into a single currency. If you notice that there are multiple currency records for the same currency, you may choose to merge them together. This will also merge the currencies for all other modules.',
  'LBL_ACCOUNT_NAME' => 'Account Name:',
  'LBL_AMOUNT' => 'Amount:',
  'LBL_CURRENCY' => 'Currency:',
  'LBL_DATE_CLOSED' => 'Expected Close Date:',
  'LBL_TYPE' => 'Type:',
  'LBL_NEXT_STEP' => 'Next Step:',
  'LBL_LEAD_SOURCE' => 'Lead Source:',
  'LBL_SALES_STAGE' => 'Sales Stage:',
  'LBL_PROBABILITY' => 'Probability (%):',
  'LBL_DESCRIPTION' => 'Description:',
  'LBL_DUPLICATE' => 'Possible Duplicate Opportunity',
  'MSG_DUPLICATE' => 'Creating this opportunity may potentialy create a duplicate opportunity. You may either select an opportunity from the list below or you may click on Create New Opportunity to continue creating a new opportunity with the previously entered data.',
  'LBL_NEW_FORM_TITLE' => 'Create Opportunity',
  'LNK_NEW_OPPORTUNITY' => 'Create Opportunity',
  'LNK_OPPORTUNITY_LIST' => 'Opportunities',
  'ERR_DELETE_RECORD' => 'A record number must be specified to delete the opportunity.',
  'LBL_TOP_OPPORTUNITIES' => 'My Top Open Opportunities',
  'NTC_REMOVE_OPP_CONFIRMATION' => 'Are you sure you want to remove this contact from this opportunity?',
	'OPPORTUNITY_REMOVE_PROJECT_CONFIRM' => 'Are you sure you want to remove this opportunity from this project?',
	'LBL_AMOUNT_BACKUP'=>'Amount Backup',

);

?>
