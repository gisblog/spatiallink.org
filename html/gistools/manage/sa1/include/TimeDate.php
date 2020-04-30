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

class TimeDate{
var $dbDayFormat = 'Y-m-d';
var $dbTimeFormat = 'H:i:s';
var $supported_strings = array('a'=>'[ap]m','A'=>'[AP]M', 'd'=>'[0-9]{1,2}', 'h'=>'[0-9]{1,2}','H'=>'[0-9]{1,2}', 'i'=>'[0-9]{1,2}','m'=>'[0-9]{1,2}', 'Y'=>'[0-9]{4}','s'=>'[0-9]{1,2}' );

function get_regular_expression($format){
		$newFormat = '';
		$regPositions = array();
		$ignoreNextChar = false;
		$count = 1;
		for($i = 0; $i < strlen($format); $i++){
			$char = substr($format, $i,  1);
			if(!$ignoreNextChar && isset($this->supported_strings[$char])){
				$newFormat .= '('.$this->supported_strings[$char].')';
				$regPositions[$char]= $count;
				$count++;
			}else{
				$ignoreNextChar = false;
			
				$newFormat .= $char;
				
			}
			if($char == "\\"){
				$ignoreNextChar	= true;
			}
		}

		return array('format'=>$newFormat, 'positions'=>$regPositions);

}

function check_matching_format($date, $format, $toformat = ''){
	$regs = array();
	$startreg = $this->get_regular_expression($format);
	if(!empty($toformat)){
		$otherreg = $this->get_regular_expression($toformat);
		//if the other format has the same regular expression then maybe it is shifting month and day position or something similar let it go for formating
		if($startreg['format'] == $otherreg['format']){
			return false;
		}
	}
	ereg($startreg['format'], $date, $regs);
	if(empty($regs)){
		return false;
	}
	return true;
}

function swap_formats($date, $startFormat, $endFormat){

	$startreg = $this->get_regular_expression($startFormat);
	
	ereg($startreg['format'], $date, $regs);
	$newDate = $endFormat;

	//handle 12 to 24 hour conversion

	if(isset($startreg['positions']['h']) && !isset($startreg['positions']['H']) && !empty($regs[$startreg['positions']['h']]) && strpos($endFormat, 'H') > -1){
		$startreg['positions']['H'] = sizeof($startreg['positions']) + 1;
		$regs[$startreg['positions']['H']] = $regs[$startreg['positions']['h']];
		if((isset($startreg['positions']['A']) && $regs[$startreg['positions']['A']] == 'PM') || (isset($startreg['positions']['a']) && $regs[$startreg['positions']['a']] == 'pm')) {

			if($regs[$startreg['positions']['h']] != 12){
				$regs[$startreg['positions']['H']] =  $regs[$startreg['positions']['h']] + 12;
			}
			}
		if((isset($startreg['positions']['A']) && $regs[$startreg['positions']['A']] == 'AM') || (isset($startreg['positions']['a']) && $regs[$startreg['positions']['a']] == 'am')) {
			if($regs[$startreg['positions']['h']] == 12){
				$regs[$startreg['positions']['H']] =  0;
			}
		}



	}
	if(!empty($startreg['positions']['H']) && !empty($regs[$startreg['positions']['H']])  && !isset($startreg['positions']['h'] )&& strpos($endFormat, 'h') > -1){
		$startreg['positions']['h'] = sizeof($startreg['positions']) + 1;
		$regs[$startreg['positions']['h']] = $regs[$startreg['positions']['H']];
		if(!isset($startreg['positions']['A'])){
			$startreg['positions']['A'] = sizeof($startreg['positions']) + 1;
			$regs[$startreg['positions']['A']] = 'AM';
		}

		if(!isset($startreg['positions']['a'])){
			$startreg['positions']['a'] = sizeof($startreg['positions']) + 1;
			$regs[$startreg['positions']['a']] = 'am';
		}

		if( $regs[$startreg['positions']['H']] > 11){
			$regs[$startreg['positions']['h']] =  $regs[$startreg['positions']['H']] - 12;
			if($regs[$startreg['positions']['h']] == 0){
				$regs[$startreg['positions']['h']] = 12;
			}
			$regs[$startreg['positions']['a']] = 'pm';
			$regs[$startreg['positions']['A']] = 'PM';
	}
		if($regs[$startreg['positions']['H']] == 0){
			$regs[$startreg['positions']['h']] =  12;
			$regs[$startreg['positions']['a']] = 'am';
			$regs[$startreg['positions']['A']] = 'AM';
	}


	}

	if(!empty($startreg['positions']['h'])){
		if(!isset($regs[$startreg['positions']['h']])){
			$regs[$startreg['positions']['h']] = '12';
		}else if(strlen($regs[$startreg['positions']['h']]) < 2)
			$regs[$startreg['positions']['h'] ] = '0'.$regs[$startreg['positions']['h']];
	}
	if(!empty($startreg['positions']['H'])){
		if(!isset($regs[$startreg['positions']['H']])){
			$regs[$startreg['positions']['H']] = '00';
		}else if(strlen($regs[$startreg['positions']['H']]) < 2)
			$regs[$startreg['positions']['H']] = '0'.$regs[$startreg['positions']['H']];
	}
	if(!empty($startreg['positions']['d'])){
		if(!isset($regs[$startreg['positions']['d']])){
			$regs[$startreg['positions']['d']] = '01';
		}else if(strlen($regs[$startreg['positions']['d']]) < 2)
			$regs[$startreg['positions']['d']] = '0'.$regs[$startreg['positions']['d']];
	}
	if(!empty($startreg['positions']['i'])){
		if(!isset($regs[$startreg['positions']['i']])){
			$regs[$startreg['positions']['i']] = '00';
		}else if(strlen($regs[$startreg['positions']['i']]) < 2)
			$regs[$startreg['positions']['i']] = '0'.$regs[$startreg['positions']['i']];
	}
	if(!empty($startreg['positions']['m'])){
		if(!isset($regs[$startreg['positions']['m']])){
			$regs[$startreg['positions']['m']] = '01';
		}elseif(strlen($regs[$startreg['positions']['m']]) < 2)
			$regs[$startreg['positions']['m']] = '0'.$regs[$startreg['positions']['m']];
	}
	if(!empty($startreg['positions']['Y'])){
		if(!isset($regs[$startreg['positions']['Y']])){
			$regs[$startreg['positions']['Y']] = '2000';
		}
	}
	if(!empty($startreg['positions']['s'])){
		if(!isset($regs[$startreg['positions']['s']])){
			$regs[$startreg['positions']['s']] = '00';
		}else if(strlen($regs[$startreg['positions']['s']]) < 2)
			$regs[$startreg['positions']['s']] = '0'.$regs[$startreg['positions']['s']];
	}else{
		$startreg['positions']['s'] = sizeof($startreg['positions']) + 1;
		$regs[$startreg['positions']['s']] = '00';
	}
	foreach($startreg['positions'] as $key=>$val){
		if(isset($regs[$val ])){
			$newDate = str_replace( $key, $regs[$val ] , $newDate);
		}
	}
	return $newDate;

}
function to_display_time($date, $meridiem= true, $offset=true){

	$date = trim($date);
		if(empty($date)){
		return $date;
	}
	if($offset){
		$date = $this->handle_offset($date,  $this->get_db_date_time_format(), true);
	}

	return  $this->to_display($date,$this->dbTimeFormat, $this->get_time_format($meridiem));
}

function to_display_date($date, $use_offset = true){
	$date = trim($date);
		if(empty($date)){
		return $date;
	}
	
	if($use_offset)$date = $this->handle_offset($date,  $this->get_db_date_time_format(), true);

	return  $this->to_display($date,$this->dbDayFormat, $this->get_date_format());
}

function to_display_date_time($date,  $meridiem= true, $offset=true){
	 $date = trim($date);
		if(empty($date)){
		return $date;
	}
	
	if($offset){
		$date = $this->handle_offset($date, $this->get_db_date_time_format(), true);
	}

	return  $this->to_display($date, $this->get_db_date_time_format(), $this->get_date_time_format($meridiem));
}

function to_display($date,$fromformat, $toformat){
	$date = trim($date);
		if(empty($date)){
		return $date;
	}

	return  $this->swap_formats($date, $fromformat, $toformat);
}

function to_db($date){
		$date = trim($date);
		$date = trim($date);
		if(empty($date)){
			return $date;
		}
	
		$date =  $this->swap_formats($date, $this->get_date_time_format(), $this->get_db_date_time_format());
		return $this->handle_offset($date,  $this->get_db_date_time_format(), false);
}

function get_javascript_validation(){
  $cal_date_format = $this->get_cal_date_format();
	$timereg = $this->get_regular_expression($this->get_time_format());
	$datereg = $this->get_regular_expression($this->get_date_format());
	$date_pos = '';
	foreach($datereg['positions'] as $type=>$pos){
		if(empty($date_pos)){
			$date_pos .= "'$type': $pos";
		}else{
			$date_pos .= ",'$type': $pos";
		}
		
	}
	$date_pos = '{' . $date_pos . '}';
	if ( preg_match('/\)([^\d])\(/',$timereg['format'],$match))
	{
		$time_separator = $match[1];	
	}
	else
	{
		$time_separator = ":";
	}
  $hour_offset = $this->get_hour_offset() * 60 * 60;

	$the_script = "<script type=\"text/javascript\">\n"
		. "\tvar time_reg_format = '". $timereg['format'] . "';\n"
		. "\tvar date_reg_format = '". $datereg['format'] . "';\n"
		. "\tvar date_reg_positions = $date_pos;\n"
		. "\tvar time_separator = '$time_separator';\n"
		. "\tvar cal_date_format = '$cal_date_format';\n"
		. "\tvar time_offset = $hour_offset;\n"
		. "</script>";

	return $the_script;

}

function to_db_date($date, $use_offset = true){
	$date = trim($date);
		if(empty($date)){
		return $date;
	}
	
	
	if($use_offset){
		$date = $this->to_db($date);
		$date = $this->swap_formats($date, $this->dbDayFormat,  $this->dbDayFormat);
	}else{
		$date = $this->swap_formats($date, $this->get_date_format(),  $this->dbDayFormat);	
	}
	
	
	/*
	$date = $this->swap_formats($date, $this->get_date_format(), $this->dbDayFormat);
	echo '<br> Before '.$date . '<br>';
	if($use_offset)$date= $this->handle_offset($date,  $this->dbDayFormat, false);
	echo '<br> After'.$date . '<br>';
	*/
	return $date;
}

function to_db_time($date, $use_offset = true){
		$date = trim($date);
		if(empty($date)){
		return $date;
		}
		$date =  $this->swap_formats($date, $this->get_time_format(),  $this->dbTimeFormat);
		if($use_offset)$date= $this->handle_offset($date,  $this->dbTimeFormat, false);
		return $date;


}
function handle_offset($date, $format, $to=true){
		$date = trim($date);
		if(empty($date)){
			return $date;
		}
		if( strtotime($date) == -1){
			return $date;
		}

		return date($format, strtotime($date) + $this->get_hour_offset($to) * 60 * 60);
}

function merge_date_time($date, $time){
		return $date . ' ' . $time;
}
function merge_time_meridiem($date, $format, $mer){
		$date = trim($date);
		if(empty($date)){
			return $date;
		}
		$fakeMerFormat = str_replace(array('a', 'A'), array('!@!', '!@!'), $format);
		$noMerFormat = str_replace(array('a', 'A'), array('', ''), $format);
		$newDate = $this->swap_formats($date, $noMerFormat, $fakeMerFormat);
		return str_replace('!@!', $mer, $newDate);

}
function AMPMMenu($prefix, $date, $attrs=''){

	if(substr_count($this->get_time_format(), 'a') == 0 && substr_count($this->get_time_format(), 'A') == 0){
		return '';
	}

	$menu = "<select name='".$prefix."meridiem' ".$attrs.">";

	if(strpos($this->get_time_format(), 'a') > -1){

		if(substr_count($date, 'am') > 0 )
			$menu.= "<option value='am' selected>am";
		else
			$menu.= "<option value='am'>am";
		if(substr_count($date, 'pm') > 0 )
			$menu.= "<option value='pm' selected>pm";
		else
			$menu.= "<option value='pm'>pm";

	}
	else{

		if(substr_count($date, 'AM') > 0 )
			$menu.= "<option value='AM' selected>AM";
		else
			$menu.= "<option value='AM'>AM";
		if(substr_count($date, 'PM') > 0){
			$menu.= "<option value='PM' selected>PM";
		}
		else
			$menu.= "<option value='PM'>PM";

	}

	return $menu. '</select>';
}

function get_hour_offset($to = true){
		global $current_user;
		if(is_a($current_user,"User") && $current_user->getPreference('timez'))
		{
			$timeDelta = $current_user->getPreference('timez');

		}else{
			$timeDelta = 0;
		}

		if($to){

		return $timeDelta;
		}

		return -1 * $timeDelta;
}
function get_time_format($meridiem = true){
	global $current_user;
	global $sugar_config;

	if( is_a($current_user,"User") && $current_user->getPreference('timef'))
	{
		$timeFormat =  $current_user->getPreference('timef');
	}else{
		$timeFormat =  $sugar_config['default_time_format'];
	}
	if(!$meridiem){
		$timeFormat= str_replace(array('a', 'A'), array('', '' ),$timeFormat)	;
	}
	return $timeFormat;
}

function get_date_format(){
	global $current_user;
	global $sugar_config;

	if( is_a($current_user,"User") && $current_user->getPreference('datef'))
	{
		return $current_user->getPreference('datef');
	}

	return $sugar_config['default_date_format'];
}

function get_date_time_format($meridiem= true){
	 return $this->get_date_format(). ' ' .  $this->get_time_format($meridiem);
}

function get_db_date_time_format(){
	 return $this->dbDayFormat. ' ' .  $this->dbTimeFormat;
}
function get_cal_date_format(){
	$format = str_replace('Y', '%Y',	$this->get_date_format());
	$format = str_replace('m', '%m',	$format);
	$format = str_replace('d', '%d',	$format);
	return $format;
}

function get_user_date_format(){
	$format = str_replace('Y', 'yyyy',	$this->get_date_format());
	$format = str_replace('m', 'mm',	$format);
	$format = str_replace('d', 'dd',	$format);
	return $format;
}

function get_user_time_format(){
	return $this->to_display_time('23:00:00', true, false);
}
}

?>
