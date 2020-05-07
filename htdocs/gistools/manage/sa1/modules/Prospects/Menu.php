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
 * $Id: Menu.php,v 1.8 2005/04/21 21:05:32 joey Exp $
 * Description:  TODO To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

global $mod_strings, $app_strings;
$module_menu = Array(
	Array("index.php?module=Campaigns&action=EditView&return_module=Campaigns&return_action=index", $mod_strings['LNK_NEW_CAMPAIGN'],"CreateCampaigns"),
	Array("index.php?module=Campaigns&action=index&return_module=Campaigns&return_action=index", $mod_strings['LNK_CAMPAIGN_LIST'],"Campaigns"),
	Array("index.php?module=ProspectLists&action=EditView&return_module=ProspectLists&return_action=DetailView", $mod_strings['LNK_NEW_PROSPECT_LIST'],"CreateProspectLists"),
	Array("index.php?module=ProspectLists&action=index&return_module=ProspectLists&return_action=index", $mod_strings['LNK_PROSPECT_LIST_LIST'],"ProspectLists"),
	Array("index.php?module=Prospects&action=EditView&return_module=Prospects&return_action=DetailView", $mod_strings['LNK_NEW_PROSPECT'],"CreateProspects"),
	Array("index.php?module=Prospects&action=index&return_module=Prospects&return_action=index", $mod_strings['LNK_PROSPECT_LIST'],"Prospects"),
	Array("index.php?module=Prospects&action=Import&step=1&return_module=Prospects&return_action=index", $app_strings['LBL_IMPORT'],"Import"),
	);
?>
