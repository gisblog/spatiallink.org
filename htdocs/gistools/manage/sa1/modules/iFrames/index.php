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
 * $Id: index.php,v 1.9 2005/04/22 01:33:26 majed Exp $
 ********************************************************************************/

require_once('include/ListView/ListView.php');
require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/iFrames/iFrame.php');



global $theme, $current_user;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once ($theme_path."layout_utils.php");


global $mod_strings;
global $app_list_strings;
global $app_strings;
global $current_user, $log, $focus;
echo "<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_TITLE'], true); 
echo "\n</p>\n";
if(!empty($_REQUEST['record']) && empty($_REQUEST['edit'])){
	$iFrame = new iFrame();
	$iFrame->retrieve($_REQUEST['record']);
	$xtpl = new XTemplate('modules/iFrames/DetailView.html');
	$xtpl_data = $iFrame->get_xtemplate_data();
	$xtpl_data['URL'] = add_http($xtpl_data['URL']);
	$xtpl->assign('IFRAME', $xtpl_data);
	$xtpl->parse('main');
	$xtpl->out('main');
}else {
	if(!empty($_REQUEST['edit'])){
		$iFrame = new iFrame();
		$xtpl = new XTemplate('modules/iFrames/EditView.html');	
		if(!empty($_REQUEST['record'])){
			$iFrame->retrieve($_REQUEST['record']);
		}
		$xtpl_data = $iFrame->get_xtemplate_data();
		
		$xtpl->assign("MOD", $mod_strings);
		$xtpl->assign("APP", $app_strings);
		
		if (isset($_REQUEST['return_module'])){
			 $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
		}else{
			$xtpl->assign("RETURN_MODULE", 'iFrames');
		}
		if (isset($_REQUEST['return_action'])){
			 $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
		}else{
			 $xtpl->assign("RETURN_ACTION",'index');
		}
		if (isset($_REQUEST['return_id'])) {
			$xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
		}else if(!empty($_REQUEST['record'])){
				$xtpl->assign("RETURN_ID", $_REQUEST['record']);
			}
		
		if(!empty($xtpl_data['STATUS']) && $xtpl_data['STATUS'] > 0){
			$xtpl_data['STATUS_CHECKED'] = 	'checked';
			
		}
		$xtpl->assign('IFRAME', $xtpl_data);
		$xtpl->parse('main');
		$xtpl->out('main');
		require_once('include/javascript/javascript.php');
$javascript = new javascript();
$javascript->setFormName('EditView');
$javascript->setSugarBean($iFrame);
$javascript->addAllFields('');
echo $javascript->getScript();

		}else if(is_admin($current_user) || !empty($_REQUEST['listview'])){
			$button_title = $app_strings['LBL_NEW_BUTTON_LABEL'];
			
			$sugar_config['disable_export'] = true;
			$iFrame = new iFrame();
			$ListView = new ListView();
			$where = '';
			if(!is_admin($current_user)){
				$where = "created_by='$current_user->id'";
			}
			$ListView->initNewXTemplate( 'modules/iFrames/ListView.html',$mod_strings);
			$ListView->setHeaderTitle($mod_strings['LBL_LIST_FORM_TITLE']. '&nbsp;' );
			$ListView->setQuery($where, "", "name", "IFRAME");
			$ListView->processListView($iFrame, "main", "IFRAME");
		
		
	}else{
	$iFrame =& new iFrame();
	$xtpl =& new XTemplate('modules/iFrames/DetailView.html');
	$xtpl_data = array();
	$xtpl_data['URL'] = translate('DEFAULT_URL', 'iFrames');
	$xtpl->assign('IFRAME', $xtpl_data);
	$xtpl->parse('main');
	$xtpl->out('main');
	}
	
	
}



?>
