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


class jsAlerts{
	var $script;
	
	function jsAlerts(){
		$this->script .= <<<EOQ
		if(window.addEventListener){
			window.addEventListener("load", checkAlerts, false);
		}else{
			window.attachEvent("onload", checkAlerts);
		}
		
EOQ;
		$this->addActivities();
		$this->addAlert('System', 'Session Timeout','', 'Your session is about to timeout in 2 minutes. Please save your work.', (session_cache_expire() - 2) * 3600 );	
		$this->addAlert('System', 'Session Timeout','', 'Your session has timed out.', (session_cache_expire()) * 3600 , 'index.php');
	}
	function addAlert($type, $name,$subtitle, $description, $countdown, $redirect=''){
		$this->script .= 'addAlert("' . addslashes($type) .'", "' . addslashes($name). '","' . addslashes($subtitle). '", "'. addslashes(str_replace(array("\r", "\n"), array('','<br>'),$description)) . '",' . $countdown . ',"'.addslashes($redirect).'")' . "\n";
	}
	
	function getScript(){
		return "<script>" . $this->script . "</script>";	
	}
	
	function addActivities(){
		global $app_list_strings, $timedate, $current_user;
		$date = date('Y-m-d H:i:s', time() + $app_list_strings['reminder_max_time']);
		$curtime = date('H:i:s' ,time());
		$time = date('H:i:s', strtotime($date));
		$date = date('Y-m-d', strtotime($date));
		$timecur = strtotime($curtime);
		$today = date('Y-m-d');
		global $sugar_config;
      
       	$select = 
		"SELECT meetings.id, name,reminder_time, description,location, date_start, time_start FROM meetings LEFT JOIN meetings_users ON meetings.id = meetings_users.meeting_id WHERE meetings_users.user_id ='$current_user->id' AND meetings.date_start = ". db_convert($date, 'date')." AND meetings.time_start >= ".db_convert($curtime, 'time')." AND meetings.reminder_time != '-1' ";
		if($date == $today) $select.=" AND meetings.time_start <= ".db_convert($time, 'time'); 
		

		global $db;
		$result = $db->query($select);
		while($row = $db->fetchByAssoc($result)){
				$row['time_start'] = from_db_convert($row['time_start'], 'time');
				$row['date_start'] = from_db_convert($row['date_start'], 'date');
				$timestart = strtotime($row['time_start']);
				$timeremind = $row['reminder_time'];
				$timestart -= $timeremind;
				
				
				$this->addAlert('Meeting', $row['name'], 'Time:'.$timedate->to_display_date_time($timedate->merge_date_time($row['date_start'], $row['time_start'])) , 'Location:'.$row['location']. "\nDescription:".$row['description']. "\nClick OK to view this meeting or click Cancel to dismiss this message." , $timestart- $timecur, 'index.php?action=DetailView&module=Meetings&record=' . $row['id']);
				
		}

       
		$select = 
		"SELECT calls.id,name,reminder_time, description, date_start, time_start FROM calls LEFT JOIN calls_users ON calls.id = calls_users.call_id WHERE calls_users.user_id ='$current_user->id' AND calls.date_start = ".db_convert($date, 'date')." AND calls.time_start >= ".db_convert($curtime, 'time')." AND calls.reminder_time != '-1' ";
		if($date == $today) $select.=" AND calls.time_start <= ".db_convert($time, 'time');
		 
		 
		global $db;
		$result = $db->query($select);
		while($row = $db->fetchByAssoc($result)){
				$row['time_start'] = from_db_convert($row['time_start'], 'time');
				$row['date_start'] = from_db_convert($row['date_start'], 'date');
				
				$timestart = strtotime($row['time_start']);
				$timeremind = $row['reminder_time'];
				$timestart -= $timeremind;
				$this->addAlert('Call', $row['name'], 'Time:'.$timedate->to_display_date_time($timedate->merge_date_time($row['date_start'], $row['time_start'])) , "Description:".$row['description']. "\nClick OK to view this call or click Cancel to dismiss this message." , $timestart- $timecur, 'index.php?action=DetailView&module=Calls&record=' . $row['id']);
				
		}
	}
	
	
	
}



?>
