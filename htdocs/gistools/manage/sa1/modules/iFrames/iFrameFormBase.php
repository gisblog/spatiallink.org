<?PHP
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
 * $Id: iFrameFormBase.php,v 1.3 2005/02/10 06:34:03 clint Exp $
 * Description:  Base form for contact
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

class iFrameFormBase  {




function handleSave($prefix,$redirect=true, $useRequired=false){
	require_once('modules/iFrames/iFrame.php'); 
	require_once('include/logging.php');
	require_once('include/formbase.php');
	require_once('include/TimeDate.php');
$timedate = new TimeDate();
	$local_log =& LoggerManager::getLogger('index');

	$focus = new iFrame();
	if($useRequired &&  !checkRequired($prefix, array_keys($focus->required_fields))){
		return null;
	}
	
	$focus = populateFromPost($prefix, $focus);

	if(empty($_REQUEST['status']) || $_REQUEST['status'] == 'off'){
		$focus->status = 0;	
	}else{
		$focus->status= 1;	
	}

	$focus->save();
	$local_log->debug("Saved record with id of ".$return_id);
	if($redirect){
		$this->handleRedirect('');
	}else{
		return $focus;
	}
}

function handleRedirect($return_id){
	if(isset($_POST['return_module']) && $_POST['return_module'] != "") $return_module = $_POST['return_module'];
	else $return_module = "iFrame";
	if(isset($_POST['return_action']) && $_POST['return_action'] != "") $return_action = $_POST['return_action'];
	else $return_action = "index";
	header("Location: index.php?action=$return_action&module=$return_module");

}

}


?>
