<?php
/* set */
	// SN: auto_increment. Note that UNSIGNED integers have a range of [0 to 4,294,967,295] and SIGNED integers have a range of [-2,147,438,648 to 2,147,438,647]. By default, the INT data type is signed. Also, refer to http://juicystudio.com/tutorial/mysql/tables.asp.
	//
	//
	// time and date:
	// EST: ()+3 hrs x 60 min x 60 sec
	// varchar(8) NOT Null. EX: 11:32 PM
	$vartime = date('h:i A', time()+10800);
	// varchar(14) NOT Null. EX: 05/02/10th Thu
	$vardate = date('y/m/dS D', time()+10800);
	$varyear = date('Y', time()+10800);
	//
	//
	/* IP: Conditions used fail with ip, but not host retrieval on occassions. Note that these conditions check for users coming from behind a proxy server, like Yahoo. In that case, ($varip = $_SERVER['REMOTE_ADDR'];) would count them as 1 user. These conditions try to get the real ip. Note that getenv() function doesn't work when PHP is running as ISAPI module.
	
	Also refer to empty() and ip2long. Note that empty() only checks variables as anything else will result in a parse error. In otherwords, the following will not work- empty(addslashes($name)). Hence:
	
	$varipcheck = getenv('HTTP_CLIENT_IP');
		if (empty($varipcheck)) {
			$varip = $_SERVER['REMOTE_ADDR'];
		} else {
			$varip = getenv('HTTP_CLIENT_IP');
		}	
	
	<script type="text/javascript">
		<!--begin
		var ip = '<!--#echo var="REMOTE_ADDR"-->'
		document.write(ip)
		// end-->
	</script> */
	if ((getenv('HTTP_CLIENT_IP')) != '') {
			$varip = getenv('HTTP_CLIENT_IP');
	} 
	elseif ((getenv('HTTP_X_FORWARDED_FOR')) != '') {
		$varip = getenv('HTTP_X_FORWARDED_FOR');
	}
	elseif ((getenv('HTTP_X_FORWARDED')) != '') {
		$varip = getenv('HTTP_X_FORWARDED');
	}
	elseif ((getenv('HTTP_FORWARDED_FOR')) != '') {
		$varip = getenv('HTTP_FORWARDED_FOR');
	}
	elseif ((getenv('HTTP_FORWARDED')) != '') {
			$varip = getenv('HTTP_FORWARDED');
	} else {
		$varip = $_SERVER['REMOTE_ADDR'];
		// OR $varip = getenv('REMOTE_ADDR');
	}
	//
	//
	/*	userhost: $varhostname = gethostbyname($_SERVER['REMOTE_ADDR']) NOT used. 
		EX: #.#.#.#.bras01.charter.com */
	$varuserhost = gethostbyaddr($_SERVER['REMOTE_ADDR']);
	//
	//
	//	referral page: FROM
	if (empty($_SERVER['HTTP_REFERER'])) {
		$varrefpage = "-";
	} else {
		$varrefpage = $_SERVER['HTTP_REFERER'];
	}
	//	$varrefpage may NOT contain www. hence, splitting string for comparison using case-insensitive stristr(). stristr() returns needle+haystack_after_needle. if needle is NOT found, it === FALSE
	$varstristrrefpage = stristr($varrefpage, 'spatiallink.org');
	//
	//
	/*	page: TO
		you can get the base page/url by using split() etc on $varip, like so:
		$varwhois = "<a href=\"http://ws.arin.net/cgi-bin/whois.pl?queryinput=".$varip."+\">arin</a>";
		$varwhois = "<a href=\"http://www.whois.sc/".$varip."\" target=\"_blank\">whois</a>";
	
	using $_SERVER['DOCUMENT_ROOT']-->
	/var/chroot/home/content/57/3881957/html
		
	<script type="text/javascript">
		<!--begin
		document.write(window.location)
		// end-->	
	</script> */
	$test_varpage = sprintf("%s%s%s", "http://", $_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI']);
	if (empty($test_varpage)) {
		$varpage = "-";
	} else {
		$varpage = $test_varpage;
	}
	//	$varpage may NOT contain www. hence, splitting string for comparison using case-insensitive stristr(). stristr() returns needle+haystack_after_needle. if needle is NOT found, it === FALSE
	$varstristrpage = stristr($varpage, 'spatiallink.org');
	//
	//
	// timestamp: this stamps the date ONLY of the index file. note that timestamp [BIGINT(12). EX: 050210145040] != time [VARCHAR(8). EX: 02:50 PM]. also, note that INT can only be (10).
	$vartimestamp = date('ymdHis', time()+10800);
	// 1 hrs=1*60 min=1*60*60 sec=3600 sec. EX: 50210148805 = 050210152405 - 3600. EX: 24 hrs=86400.
	$varoldtimestamp = $vartimestamp - 86400;
	// last modified
	$varlastmodified = date("g:i A T, jS F Y", getlastmod()+10800);
	// file name, size and download time [56K modem]. if you want know the real directory of the include file: dirname(__FILE__)
	$varfilepath = $_SERVER['PHP_SELF'];
	$varfilename = basename($varfilepath);
	$varbytespersecond = 4000;
	$vardownloadtime = filesize($varfilename) / ($varbytespersecond);
	// using abs() for absolute value
	$varapproxdownloadtime = round(abs($vardownloadtime), 2);
	//
	//
	//	SWITCH: the SWITCH statement is similar to a series of IF statements on the same expression. in many occasions, you may want to compare the same variable/expression with many different values, and execute a different piece of code depending on which value it equals to. this is exactly what the switch statement is for. however, here we are testing presence of string, hence...
	//	Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0; image_azv; .NET CLR 1.1.4322)
	//	Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.10) Gecko/20050716 Firefox/1.0.6
	//	Lynx/2.8.4rel.1 libwww-FM/2.14
	//	OPWV-SDK/62UP.Browser/6.2.2.1.208 (GUI) MMP/2.0
	//	Nokia7110 (DeckIt/1.2.3)
	$test_varbrowser = $_SERVER["HTTP_USER_AGENT"];
	if (stristr($test_varbrowser, "msie")) {
		$varbrowser = "MSIE";
	} elseif (stristr($test_varbrowser, "firefox")) {
		$varbrowser = "Firefox";
	} elseif (stristr($test_varbrowser, "netscape")) {
		$varbrowser = "Netscape";
	} elseif (stristr($test_varbrowser, "opera")) {
		$varbrowser = "Opera";
	} elseif (stristr($test_varbrowser, "safari")) {
		$varbrowser = "Safari";
	} elseif (stristr($test_varbrowser, "konqueror")) {
		$varbrowser = "Konqueror";
	} elseif (stristr($test_varbrowser, "amaya")) {
		$varbrowser = "Amaya";
	} elseif (stristr($test_varbrowser, "lynx")) {
		$varbrowser = "Lynx";
	} elseif (stristr($test_varbrowser, "charlotte")) {
		$varbrowser = "Charlotte";
	} elseif (stristr($test_varbrowser, "icab")) {
		$varbrowser = "iCab";
	} elseif (stristr($test_varbrowser, "wap") || stristr($test_varbrowser, "wml") || stristr($test_varbrowser, "sdk") || stristr($test_varbrowser, "cell") || stristr($test_varbrowser, "mobile") || stristr($test_varbrowser, "phone") || stristr($test_varbrowser, "klondike") || stristr($test_varbrowser, "nokia") || stristr($test_varbrowser, "sprint")) {
		$varbrowser = "WAP";
	} else {
		$varbrowser = "Other";
	}
	//
	//
	/*
	//	test: check source code
	<script type="text/javascript">
	<!--begin
	var vartimestamp;
	var varoldtimestamp;
	vartimestamp = 
	<?php
	print $vartimestamp;
	?>
	;
	varoldtimestamp = 
	<?php
	print $varoldtimestamp;
	?>
	;
	// end-->
	</script>
	*/
/* done */
?>
