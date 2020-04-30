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

require_once('modules/iFrames/iFrame.php');
$iFrame = new iFrame();
$frames = $iFrame->lookup_frames('tab');




foreach($frames as $name => $values){
	$id = $values[0];
	$place = $values[2];
	$xtpl->assign("MODULE_NAME", $name);
	$xtpl->assign("MODULE_KEY", 'iFrames');
	if($place == 'all' || $place = 'tab'){
			$tab = 'true';
	}else{
		$tab = 'false';	
	}
	$xtpl->assign("MODULE_QUERY", '&record='.$id.'&tab='.$tab);
	if('iFrames' == $currentModule && !empty($_REQUEST['record'])  && $_REQUEST['record'] == $id)
	{
		
		$xtpl->assign("TAB_CLASS", "currentTab");
		$xtpl->assign("MOUSEOVER", "onmouseover=\"this.style.cursor='hand';\" onmouseout=\"this.style.cursor='auto';\"");
	}
	else
	{
		$xtpl->assign("TAB_CLASS", "otherTab");
		if ($theme=="Links"){
		$xtpl->assign("MOUSEOVER", "onmouseover=\"this.style.cursor='hand';this.style.background='#7FCB7A'\" onmouseout=\"this.style.cursor='auto';this.style.background='transparent'\"");
		} else {
		$xtpl->assign("MOUSEOVER", "onmouseover=\"this.style.background='#FFB5B5';this.style.cursor='hand';\" onmouseout=\"this.style.background='#ffffff';this.style.cursor='auto';\"");
		}
	}
	if($xtpl->exists('main.tab'))$xtpl->parse("main.tab");
	if($xtpl->exists('main.left_form.tab'))$xtpl->parse("main.left_form.tab");
	unset($name);
	unset($values);
	unset($id);
	unset($place);
}

?>
