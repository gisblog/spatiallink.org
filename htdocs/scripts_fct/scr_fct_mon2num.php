<?php
// switch{} $mon [string] to [0.number]: strtotime(), cal_info()
function fct_mon2num($mon) {
	if($mon == 'Jan') {
		return '01';
	} elseif($mon == 'Feb') {
		return '02';
	} elseif($mon == 'Mar') {
		return '03';
	} elseif($mon == 'Apr') {
		return '04';
	} elseif($mon == 'May') {
		return '05';
	} elseif($mon == 'Jun') {
		return '06';
	} elseif($mon == 'Jul') {
		return '07';
	} elseif($mon == 'Aug') {
		return '08';
	} elseif($mon == 'Sep') {
		return '09';
	} elseif($mon == 'Oct') {
		return '10';
	} elseif($mon == 'Nov') {
		return '11';
	} elseif($mon == 'Dec') {
		return '12';
	} else {
		return '';
	}
}
?>