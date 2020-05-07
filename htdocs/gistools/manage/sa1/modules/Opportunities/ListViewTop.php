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
 * $Id: ListViewTop.php,v 1.31 2005/04/14 18:03:43 lam Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once("data/Tracker.php");
require_once('modules/Opportunities/Opportunity.php');
require_once('include/logging.php');
require_once('include/ListView/ListView.php');
$current_module_strings = return_module_language($current_language, "Opportunities");
$log = LoggerManager::getLogger('top opportunity_list');
$seedOpportunity = new Opportunity();
	
//build top 5 opportunity list
$where = "opportunities.sales_stage <> 'Closed Won' AND opportunities.sales_stage <> 'Closed Lost' AND opportunities.assigned_user_id='".$current_user->id."'";
$header_text = '';
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){	
		$header_text = "&nbsp;<a href='index.php?action=index&module=DynamicLayout&from_action=ListView&from_module=Opportunities'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>";
}
$ListView = new ListView();
$ListView->initNewXTemplate( 'modules/Opportunities/ListViewTop.html',$current_module_strings);
$ListView->setHeaderTitle($current_module_strings['LBL_TOP_OPPORTUNITIES']. $header_text );
$ListView->setQuery($where, 5, "amount  DESC", "OPPORTUNITY", false);
$ListView->processListView($seedOpportunity, "main", "OPPORTUNITY");

?>
