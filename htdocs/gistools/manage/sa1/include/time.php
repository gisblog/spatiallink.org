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
 * $Id: time.php,v 1.10 2005/02/09 07:08:54 andrew Exp $
 * Description:  
 ********************************************************************************/

function to_display_time($string, $displayMeridiem=false){
	global $current_user, $timeMeridiem;
	global $sugar_config;
	if($current_user->getPreference('time')){
		$time = $current_user->getPreference('time');
	}else $time = $sugar_config['default_time_format'];
	$hours = getHours($string);
	$minutes = getMinutes($string);
	if(substr_count($time, 'HH') > 0){
		if($hours >= 12){
				if($hours > 12){
					$hours = $hours - 12;
					if($hours < 10){
						$hours = '0'.$hours;	
					}
				}
				if($displayMeridiem)	
				$minutes.=$timeMeridiem[1];
		}else{
			
			if($hours == 0){
				$hours = 12;	
			}
			if($displayMeridiem)
			$minutes.=$timeMeridiem[0];
		}
	}
	if(substr_count($time, ':') > 0){
		return $hours.':'.$minutes;
	}
	
	return $hours.$minutes;
	
	
}

function to_db_time($string, $mer=''){
		global  $timeMeridiem;
		$hours = getHours($string);
		$minutes = getMinutes($string);
		if(!empty($mer)){
			$meridiem = $mer;	
		}else $meridiem = getMeridiem($string);
		
		if(!empty($meridiem)){
			$hours = $hours % 12;	
		}
		if($meridiem == $timeMeridiem[1] ){
			$hours += 12;	
		}
		if($hours > 24){
			$hours = $hours % 24;	
		}
		if($hours < 10 && strlen($hours) == 1){
				$hours = '0'.$hours;	
		}
		return $hours.':'.$minutes;
		
}
function getHours($string){
			return substr($string ,0, 2);
		
}
function getMinutes($string){
	if(substr_count($string, ':') > 0){
			return substr($string ,3, 2);	
		}
		else{
			return substr($string ,2, 2);	
		}
}
function getMeridiem($string){
	global $current_user;
	global $sugar_config;
	if($current_user->getPreference('time')){
		$time = $current_user->getPreference('time');
	}else $time = $sugar_config['default_time_format'];
	if(substr_count($time, 'HH')){
		if(substr_count($string, ':') > 0){
			return substr($string ,5, 2);	
		}
	}
	return '';
}
function AMPMMenu($prefix, $string){
	global $current_user,  $timeMeridiem;
	global $sugar_config;
	if($current_user->getPreference('time')){
		$time = $current_user->getPreference('time');
	}else $time = $sugar_config['default_time_format'];
	if(substr_count($time, 'HH')){
		$menu = "<select name='".$prefix."meridiem'>";
		$mer = $timeMeridiem[0];
		if(getHours($string) < 12 && getHours($string) >23){
			$menu .="<option value='$mer' selected>$mer";
		}else $menu .="<option value='$mer'>$mer";
		$mer = $timeMeridiem[1];
		if(getHours($string) > 11 && getHours($string) < 24){
			$menu .="<option value='$mer' selected>$mer";
		}else $menu .="<option value='$mer'>$mer";
		return $menu. "</select>";
	}
	return '';
}

function getDisplayTimeFormat(){
	global $current_user, $timeMeridiem;
	global $sugar_config;
	
	if($current_user->getPreference('time')){
			$time = $current_user->getPreference('time');
			if(substr_count($time, 'HH'))
				return $sugar_config['time_formats'][$time]. $timeMeridiem[1];
			return $sugar_config['time_formats'][$time];
	}
	if(substr_count($sugar_config['default_time_format'], 'HH')){
		return $sugar_config['time_formats'][$sugar_config['default_time_format']]. $timeMeridiem[1];
	}
	return $sugar_config['time_formats'][$sugar_config['default_time_format']];
	
}

?>
