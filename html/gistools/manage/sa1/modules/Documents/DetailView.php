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
 * $Id: DetailView.php,v 1.9.2.3 2005/05/17 19:10:14 ajay Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Documents/Document.php');
require_once('modules/Documents/DocumentRevision.php');
require_once('include/upload_file.php');
require_once('include/ListView/ListView.php');


global $app_strings;
global $mod_strings;

$mod_strings = return_module_language($current_language, 'Documents');

$focus =& new Document();
if(!empty($_REQUEST['record'])) {
    $result = $focus->retrieve($_REQUEST['record']);
    if($result == null)
    {
    	sugar_die("Error retrieving record.  You may not be authorized to view this record.");
    }
}
else {
	header("Location: index.php?module=Documents&action=index");
}
$old_id="";
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {

	$focus->id = "";
}

echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->document_name, true);
echo "\n</p>\n";
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("Document detail view");

$xtpl=new XTemplate ('modules/Documents/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
$xtpl->assign("THEME", $theme);
$xtpl->assign("GRIDLINE", $gridline);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("ID", $focus->id);

$xtpl->assign("DOCUMENT_NAME", $focus->document_name);
$xtpl->assign("REVISION", $focus->latest_revision);
$xtpl->assign("CATEGORY", $focus->category_id);
$xtpl->assign("SUBCATEGORY", $focus->subcategory_id);
$xtpl->assign("STATUS", $focus->status_id);
$xtpl->assign("DESCRIPTION", $focus->description);
$xtpl->assign("FILE_URL", $focus->file_url);
$xtpl->assign("ACTIVE_DATE", $focus->active_date);
$xtpl->assign("EXP_DATE", $focus->exp_date);
$xtpl->assign("FILE_NAME", $focus->filename);
$xtpl->assign("FILE_URL_NOIMAGE", $focus->file_url_noimage);
$xtpl->assign("LAST_REV_CREATOR", $focus->last_rev_created_name);
if (isset($focus->last_rev_create_date)) {
	require_once("include/TimeDate.php");
	$timedate=& new TimeDate();
	$xtpl->assign("LAST_REV_DATE", $timedate->to_display_date_time($focus->last_rev_create_date));
} else {
	$xtpl->assign("LAST_REV_DATE",  "");
}
$xtpl->assign("DOCUMENT_REVISION_ID", $focus->document_revision_id);













$xtpl->parse("main.open_source");





$xtpl->parse("main");
$xtpl->out("main");


//process subpanel.
$old_contents = ob_get_clean();
echo $old_contents;
ob_start();

$revision = new DocumentRevision();
$ListView = new ListView();

$ListView->initNewXTemplate('modules/Documents/DocumentRevisionListView.html',$mod_strings);
$ListView->setQuery(" document_id = '$focus->id'",""," date_entered desc","");
$ListView->setHeaderTitle($mod_strings['LBL_DOC_REV_HEADER']);
$ListView->show_export_button=false;
$ListView->show_paging =false;
$ListView->xTemplateAssign("ID",$focus->id);
$ListView->xTemplateAssign("VIEW_DOC",get_image('themes/'.$theme.'/images/view',"","",""));

if (isset($_REQUEST['return_module'])) $ListView->xTemplateAssign("RETURN_MODULE", $_REQUEST['return_module']);
if (isset($_REQUEST['return_action'])) $ListView->xTemplateAssign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) $ListView->xTemplateAssign("RETURN_ID", $_REQUEST['return_id']);

$return_url="return_module=Documents&return_action=DetailView&return_id=".$focus->id;

$ListView->xTemplateAssign("RETURN_URL", $return_url);
$ListView->xTemplateAssign("DOCUMENT_NAME", $focus->document_name);
$ListView->xTemplateAssign("DOCUMENT_REVISION", $focus->latest_revision);
$ListView->xTemplateAssign("DOCUMENT_FILENAME", $focus->filename);
$ListView->xTemplateAssign("DOCUMENT_REVISION_ID", $focus->document_revision_id);
$ListView->xTemplateAssign("DELETE_INLINE_PNG",  get_image($image_path.'delete_inline','align="absmiddle" alt="'.$app_strings['LNK_DELETE'].'" border="0"'));

$ListView->processListView($revision, "main", "DOCREVISION");

$panelcontent= ob_get_clean();

$xtpl->assign("REVISIONS", $panelcontent);
//end process

$xtpl->parse("subpanel");
$xtpl->out("subpanel");



