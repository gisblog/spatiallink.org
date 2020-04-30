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
 * $Id: PopupReports.php,v 1.2 2005/04/19 01:22:05 joey Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
// This file is used for all popups on this module
// The popup_picker.html file is used for generating a list from which to find and choose one instance.

global $theme;
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('XTemplate/xtpl.php');
require_once('include/utils.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;

$current_module_strings = return_module_language($current_language, 'Reports');

// build query for listing
$query = "SELECT id, name, module FROM saved_reports WHERE deleted = '0' ";
$query .= "AND module = '" . $_REQUEST['modulevar'] . "' ";

if(isset($_REQUEST['name']))
	$where_clause = "AND name LIKE '%" . $_REQUEST['name'] . "'";
else
	$where_clause = "";

$query .= $where_clause;
$db =& new PearDatabase();
$result = $db->query($query);

$image_path = 'themes/'.$theme.'/images/';

$the_javascript  = "<script type='text/javascript' language='JavaScript'>";
$the_javascript .= "	function set_return(report_id, report_module) {";
$the_javascript .= "		window.opener.document.ContactsForm.id.value = report_id;";
$the_javascript .= "		window.opener.document.ContactsForm.report_module.value = report_module;";
$the_javascript .= " 		window.opener.document.ContactsForm.return_module.value = 'ProspectLists';";
$the_javascript .= "		window.opener.document.ContactsForm.return_action.value = 'DetailView';";
$the_javascript .= "		window.opener.document.ContactsForm.return_id.value = window.opener.document.ContactsForm.record.value;";
$the_javascript .= "		window.opener.document.ContactsForm.module.value = 'ProspectLists';";
$the_javascript .= "		window.opener.document.ContactsForm.action.value = 'SaveReportRelationship';";
$the_javascript .= "		window.opener.document.ContactsForm.submit();";
$the_javascript .= "	}";
$the_javascript .= "</script>";

////////////////////////////////////////////////////////
// Start the output
////////////////////////////////////////////////////////
$form =new XTemplate ('modules/Prospects/Popup_Reports_picker.html');
$log->debug("using file modules/Prospects/Popup_Reports_picker.html");
insert_popup_header($theme);
echo get_form_header($current_module_strings['LBL_SEARCH_FORM_TITLE'], "", false);

$form->assign("THEME", $theme);
$form->assign("IMAGE_PATH", $image_path);
$form->assign("MOD", $current_module_strings);
$form->assign("APP", $app_strings);
$form->assign("SET_RETURN_JS", $the_javascript);
$form->assign("PROSPECT_LIST_ID", $_REQUEST['record']);
$form->assign("MODULE_VAR", $_REQUEST['modulevar']);
$form->parse("main.SearchHeader");
$form->out("main.SearchHeader");

$loopData = "";
while($row = $db->fetchByAssoc($result))
{
	if($row['module'] == 'Contacts')
		$return_module = "ReportContact";
	if($row['module'] == 'Leads')
		$return_module = "ReportLead";
	
$loopData .= <<<EOQ
<tr><td colspan="20" class="listViewHRS1"></td></tr>
<tr height="20"  onmouseover="setPointer(this, '{$row['id']}', 'over', '#fdfdfd', '#DEEFFF', '');" onmouseout="setPointer(this, '{$row['id']}', 'out', '#fdfdfd', '#DEEFFF', '');" onmousedown="setPointer(this, '{$row['id']}', 'click', '#fdfdfd', '#DEEFFF', '');">
<td class="oddListRowS1" bgcolor="#fdfdfd" NOWRAP><a <a href="a" LANGUAGE="javascript" onclick='set_return("{$row['id']}","{$return_module}"); window.close()'  class="listViewTdLinkS1">{$row['name']}</a></td>
<td class="oddListRowS1" bgcolor="#fdfdfd" NOWRAP>{$row['module']}</a></td>
</tr>
EOQ;

}

$form->assign("LOOPDATA", $loopData);

$form->parse("main.ListBody");
$form->out("main.ListBody");


?>
