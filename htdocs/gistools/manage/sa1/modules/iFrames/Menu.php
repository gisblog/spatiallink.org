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
 * $Id: Menu.php,v 1.8 2005/02/18 22:02:08 lam Exp $
 * Description:  TODO To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

global $mod_strings;
$module_menu = array();
require_once('modules/iFrames/iFrame.php');
$iFrame = new iFrame();
$frames = $iFrame->lookup_frames('shortcut');

foreach($frames as $name =>$values){
	 	$id = $values[0];
	 	$place = $values[2];
	 	if($place == 'all' || $place == 'tab'){
			$tab = 'true';
		}else{
			$tab = 'false';	
		}
		$module_menu[] = array('index.php?module=iFrames&action=index&record=' .$id .'&tab='.$tab, $name, 'iFrames', 'iFrames');

		unset($name);
		unset($values);
}

$module_menu[] = array('index.php?module=iFrames&action=index&edit=true', translate('LBL_ADD_SITE', 'iFrames'), 'CreateiFrames', 'iFrames');
$module_menu[] = array('index.php?module=iFrames&action=index&listview=true', translate('LBL_LIST_SITES', 'iFrames'),  'iFrames', 'iFrames');

?>
