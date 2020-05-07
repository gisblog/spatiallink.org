<?php
// open files and directories
$handle = opendir('.'); # . = current [archives]
while(($list = readdir($handle)) !== false) {
	if($list != '.' && $list != '..' && stristr($list, 'index.') == false && empty($list) == false) {
		print '&#186;&nbsp;'.$list.'<br />'; # $list = 2007
		//
		$subhandle = opendir($list); # /2007/
		while(($sublist = readdir($subhandle)) !== false) {
			if($sublist != '.' && $sublist != '..' && stristr($list, 'index.') == false && empty($sublist) == false) {
				print '&nbsp;&nbsp;- '.$sublist.'<br />'; # $sublist = Apr
				//
				$subsubhandle = opendir($list.'/'.$sublist); # 2007/Apr
				while(($subsublist = readdir($subsubhandle)) !== false) {
					// create empty array for push
					$dates = array('');
					if($subsublist != '.' && $subsublist != '..' && stristr($list, 'index.') == false && empty($subsublist) == false) {
						// substr($string, start, length): return numeric part of string for sort()- 26th.html->26
						// $subsubdate = substr($subsublist, 0, 2)*1; # str_split() for php 5
						// array_push()
						array_push($dates, $subsublist); # // array_push($dates, $subsubdate);
					}
					// sort: natsort(); natcasesort();
					sort($dates, SORT_NUMERIC);
					foreach ($dates as $value) {
						$value_pieces = explode('.', $value);
						print '<a href="'.$list.'/'.$sublist.'/'.$value.'">'.$value_pieces[0].'</a> &nbsp;&nbsp; '; # $subsublist = 26th.html; print_r(); # &nbsp; chain causes linebreak to fail
					}
				}
				print '<br />';
				closedir($subsubhandle);
			}
		}
		closedir($subhandle);
	}
}
closedir($handle);
?>