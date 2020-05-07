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

// $Id: StoreQuery.php,v 1.6 2005/02/23 23:34:10 andrew Exp $

class StoreQuery{
	var $query = array();
	
	function addToQuery($name, $val){
		$this->query[$name] = $val;	
	}
	
	function SaveQuery($name){
		global $current_user;
		$current_user->setPreference($name.'Q', $this->query);
	}
	
	function clearQuery($name){
		$this->query = array();
		$this->saveQuery($name);	
	}
	
	function loadQuery($name){
		$saveType = $this->getSaveType($name);
		if($saveType == 'all' || $saveType == 'myitems'){
			global $current_user;
			$this->query = $current_user->getPreference($name.'Q');
			if(empty($this->query)){
				$this->query = array();	
			}
		}
	}
	
	
	function populateRequest(){
		foreach($this->query as $key=>$val){
			$_REQUEST[$key] = $val;	
			$_GET[$key] = $val;	
		}	
	}
	
	function getSaveType($name)
	{
		global $sugar_config;
		$save_query = empty($sugar_config['save_query']) ?
			'all' : $sugar_config['save_query'];

		if(is_array($save_query))
		{
			if(isset($save_query[$name]))
			{
				$saveType = $save_query[$name];
			}
			elseif(isset($save_query['default']))
			{
				$saveType = $save_query['default'];
			}
			else
			{
				$saveType = 'all';
			}	
		}
		else
		{
			$saveType = $save_query;
		}	

		return $saveType;
	}

	function saveFromGet($name){
		if(isset($_GET['query'])){
			if(!empty($_GET['clear_query']) && $_GET['clear_query'] == 'true'){
				$this->clearQuery($name);
				return;	
			}
			$saveType = $this->getSaveType($name);
			
			if($saveType == 'myitems'){
				if(!empty($_GET['current_user_only'])){
					$this->query['current_user_only'] = $_GET['current_user_only'];
					$this->query['query'] = true;
				}
				$this->saveQuery($name);
				
			}else if($saveType == 'all'){
				$this->query = $_GET;
				$this->saveQuery($name);	
			}
		}
	}
}

?>
