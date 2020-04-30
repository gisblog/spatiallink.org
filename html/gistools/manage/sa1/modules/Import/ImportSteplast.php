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
 * $Id: ImportSteplast.php,v 1.26 2005/02/09 07:08:54 andrew Exp $
 * Description:  TODO: To be written.
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Import/UsersLastImport.php');
require_once('modules/Import/parse_utils.php');
require_once('include/ListView/ListView.php');
require_once('modules/Import/config.php');

global $mod_strings, $app_list_strings, $app_strings, $current_user, $import_bean_map;
global $theme;
if (! isset( $_REQUEST['module']))
{
	$_REQUEST['module'] = 'Home';
}

if (! isset( $_REQUEST['return_id']))
{
	$_REQUEST['return_id'] = '';
}
if (! isset( $_REQUEST['return_module']))
{
	$_REQUEST['return_module'] = '';
}

if (! isset( $_REQUEST['return_action']))
{
	$_REQUEST['return_action'] = '';
}
echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME']." ".$mod_strings['LBL_RESULTS'], true); 
echo "\n</p>\n";
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("Upload Step 2");

if ( isset($_REQUEST['message']))
{
?>




<span class="body">
<?php 
function unhtmlentities($string)
{
   $trans_tbl = get_html_translation_table(HTML_ENTITIES);
   $trans_tbl = array_flip($trans_tbl);
   return strtr($string, $trans_tbl);
}

echo unhtmlentities($_REQUEST['message']); ?>


<?php 
}
?>
</span>

<form name="Import" method="POST" action="index.php">
<input type="hidden" name="module" value="<?php echo $_REQUEST['module']; ?>">
<input type="hidden" name="action" value="Import">
<input type="hidden" name="step" value="undo">
<input type="hidden" name="return_module" value="<?php echo $_REQUEST['return_module']; ?>">
<input type="hidden" name="return_id" value="<?php echo $_REQUEST['return_id']; ?>">
<input type="hidden" name="return_action" value="<?php echo $_REQUEST['return_action']; ?>">

<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr>
        <td align="right" style="padding-bottom: 2px;"><input title="<?php echo $mod_strings['LBL_UNDO_LAST_IMPORT']; ?>" accessKey="" class="button" type="submit" name="button" value="  <?php echo $mod_strings['LBL_UNDO_LAST_IMPORT'] ?>  "></td>
</tr>
</table>
</form>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
 <form enctype="multipart/form-data" name="Import" method="POST" action="index.php">
                        <input type="hidden" name="module" value="<?php echo $_REQUEST['module']; ?>">
                        <input type="hidden" name="action" value="Import">
                        <input type="hidden" name="step" value="1">
                        <input type="hidden" name="return_id" value="<?php echo $_REQUEST['return_id']; ?>">
                        <input type="hidden" name="return_module" value="<?php echo $_REQUEST['return_module']; ?>">
                        <input type="hidden" name="return_action" value="<?php echo $_REQUEST['return_action']; ?>">
        <tr>
        <td align="right">
<input title="<?php echo $mod_strings['LBL_IMPORT_MORE'] ?>" accessKey="" class="button" type="submit" name="button" value="  <?php echo $mod_strings['LBL_IMPORT_MORE'] ?>  "  onclick="return true;">
<input title="<?php echo $mod_strings['LBL_FINISHED'] ?>" accessKey="" class="button" type="submit" name="button" value="  <?php echo $mod_strings['LBL_FINISHED'] ?>  "  onclick="this.form.action.value=this.form.return_action.value;this.form.return_module.value=this.form.return_module.value;return true;"></td>

</tr>
</table>

        </form>

<?php


$currentModule = "Import";
$last_imported_title = $mod_strings['LBL_LAST_IMPORTED'];
global $limit, $current_language;

if (isset($import_bean_map[$_REQUEST['module']]))
{
	$currentModule = $_REQUEST['module'];
	$bean = $import_bean_map[$currentModule];
	require_once("modules/Import/$bean.php");
	$focus = new $bean();
}
else
{
 echo "Imports aren't set up for this module type\n";
 exit;
}

//because some imports also populate data in related modules, we sometimes need to 
//step through multiple modules

// set $currentModule to something other than $related_module
// or else return_module_language will not work
global $currentModule;
$currentModule = 'import';
foreach ($focus->related_modules as $related_module)
{
	echo "<BR>";
	$new_bean = $import_bean_map[$related_module];
	require_once("modules/Import/$new_bean.php");
	$new_focus = new $new_bean();

	$newForm = null;
	$seedUsersLastImport = new UsersLastImport();
	$seedUsersLastImport->bean_type = $related_module;
	$current_module_strings = return_module_language($current_language, $related_module);
	$seedUsersLastImport->list_fields = $new_focus->list_fields;
	
	$where = "users_last_import.assigned_user_id='{$current_user->id}' AND users_last_import.bean_type='$related_module' and users_last_import.bean_id={$new_focus->table_name}.id AND users_last_import.deleted=0";
	
	$ListView = new ListView();
	$ListView->initNewXTemplate( "modules/$related_module/ListView.html",$current_module_strings);
	$ListView->setHeaderTitle($last_imported_title." ".$related_module );
	$ListView->setQuery($where, "", "",$new_focus->list_view_prefix);
	$ListView->processListView($seedUsersLastImport, "main", $new_focus->list_view_prefix);
}
?>
