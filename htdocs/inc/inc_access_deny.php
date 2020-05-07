<?php
/*	access_deny: refer to htaccess */
	if (!$vartime) {
		// environment has NOT been set, hence include
		include '/opt/bitnami/apache2/htdocs/inc/inc_environment.php';
	}
	//
	//	spam typically targets the stats page. culprit emails- pr@umaxsearch.com, domains@smartsol.biz, atrivo.com
	//	apply filter/condition (&& ||): ereg("cheap", $varrefpage).
	//	refer to preg_match(), stristr(), strstr(), stristr, and http://www.webtrickscentral.com/article.php?a=252. preg_match(), which uses a Perl-compatible regular expression syntax, is often a faster alternative to ereg(). but do not use preg_match() if you only want to check if one string is contained in another string. use stristr() or strstr() instead as they will be faster. and if you only want to determine if a particular needle occurs within haystack, use the faster and less memory intensive function *stristr()* instead. also, refer to fnmatch(). 
	//	http://www.tiffanybbrown.com/viewqb.php/299. stristr() detects where in the haystack a particular needle lies. stristr() tests whether the needle is anywhere in the haystack. However, if the needle you are looking for could be the first character of the haystack, stristr() will return a result of zero (since counts start at 0). Your test would fail. However, the trick with stristr() is to test whether or not an integer value was returned using "===". Also keep in mind, too, that stristr() is case-sensitive. If you’re comparing string of mixed case you may still wish to use stristr(). PHP 5 has added the stripos() function, a case-insenstive version of stristr(). 
	// note: use ===. simply == would not work as expected if the position is the 0th [first] character. FALSE works better than TRUE. ex: if $haystack = 'vortex.anonymizer.com' and $needle = 'anonymizer', $position is 7.
	//	thus: stristr($varuserhost, ".anonymizer.com") OR strpos($varuserhost, ".anonymizer.com") OR ereg("ArcGIS Server", $item['category']). note the order of string V. variable.
	//	note: strrchr() begins searching from the last part of the string for the character[s] specificied. once it finds the character[s], it returns everything it has searched upto the point + the character[s]. EX: in ("virginia.edu.edu", "d"), it will return "du".
	//	http://www.php.net/manual/en/language.control-structures.php 
	//	http://www.php.net/manual/en/control-structures.switch.php
	if (
	strrchr($varuserhost, ".") == ".zh" || 
	strrchr($varrefpage, ".") == ".zh"
	) {
		// deny [BAD guys from OUTside]: if you are using templates with numerous includes then exit() will end you script and your template will not complete (no </table>, </body>, </html> etc...). Rather than having complex nested conditional logic within your content, just create a "footer.php" file that closes all of your HTML and if you want to exit out of a script just include() the footer before you exit():
		//	include ('header.php');
		//	blah blah blah
		//	if (!$mysqli_connect) {
			//	echo "unable to connect";
			//	include ('footer.php');
			//	exit;
			//	}
			//	blah blah blah
			//	include ('footer.php');
		exit;
		return;
		die;
	} elseif (
	$varuserhost == "" || 
	strrchr($varuserhost, ".") == ".gov" || 
	stristr($varuserhost, ".alexa.com") || 
	stristr($varuserhost, ".b2evolution.net") || 
	stristr($varuserhost, ".blogshares.com") || 
	stristr($varuserhost, ".blogsnow.com") || 
	stristr($varuserhost, ".icerocket.com") || 
	stristr($varuserhost, ".pubsub.com") || 
	stristr($varuserhost, ".rojo.com") || 
	stristr($varuserhost, ".technorati.com") || 
	stristr($varuserhost, "feedparser.org") || 
	stristr($varuserhost, "bot") || 
	stristr($varuserhost, "google") || 
	stristr($varuserhost, "inktomi") || 
	stristr($varuserhost, "magpie") || 
	stristr($varuserhost, "reference") || 
	stristr($varuserhost, "yahoo") || 
	$varrefpage == "" || 
	strrchr($varrefpage, ".") == ".gov" || 
	stristr($varrefpage, ".alexa.com") || 
	stristr($varrefpage, ".b2evolution.net") || 
	stristr($varrefpage, ".blogshares.com") || 
	stristr($varrefpage, ".blogsnow.com") || 
	stristr($varrefpage, ".icerocket.com") || 
	stristr($varrefpage, ".pubsub.com") || 
	stristr($varrefpage, ".rojo.com") || 
	stristr($varrefpage, ".technorati.com") || 
	stristr($varrefpage, "feedparser.org") || 
	stristr($varrefpage, "bot") || 
	stristr($varrefpage, "google") || 
	stristr($varrefpage, "inktomi") || 
	stristr($varrefpage, "magpie") || 
	stristr($varrefpage, "reference") || 
	stristr($varrefpage, "yahoo")
	) {
		//	do NOT deny [GOOD guys from IN/OUTside]
	} elseif (
	$varip == "84.146.51.80" || 
	stristr($varuserhost, ".anonymization.net") || 
	stristr($varuserhost, ".anonymizer.com") || 
	stristr($varrefpage, "porn")
	) {
		//	deny AGAIN [BAD guys from INside]
		exit;
		return;
		die;
	} else {
		// do NOT deny AGAIN [MIGHT-BE-GOOD guys from IN/OUTside]
	}
	//
	//
	//	deny IP range [BAD guys from OUTside]: IP block- if (substr($varip, 0, 7) == "10.1.0.") {...}
	for ($i=224; $i<=255; $i++) {
		if ($varip == "63.148.99.".$i) {
			//	deny IP range: cyveillance.com
			exit;
			return;
			die;
		}
	}
	//	deny IP range [BAD guys from OUTside]
	for ($i=192; $i<=223; $i++) {
		if ($varip == "65.118.41.".$i) {
			//	deny IP range: cyveillance.com
			exit;
			return;
			die;
		}
	}
	//
	//
	//	deny direct access
	if (
	$varrefpage == "http://www.spatiallink.org/gistools/discuss/weblogs/blogs/pi.php?disp=comments" || 
	$varrefpage == "http://www.spatiallink.org/gistools/discuss/weblogs/blogs/pi.php?disp=stats" || 
	$varrefpage == "http://www.spatiallink.org/gistools/discuss/weblogs/blogs/admin/b2edit.php?blog=5" || 
	$varrefpage == "http://www.spatiallink.org/gistools/discuss/weblogs/blogs/pi.php?m=200410"
	) {
		//	check for ip
	}
	//
	if ($varrefpage == "http://www.spatiallink.org/gistools/discuss/weblogs/blogs/pi.php?m=200410") {
		//	deny
		exit;
		return;
		die;
	}
	//	done
	//
	//
	//	deny_internal_user: note that G: time 0 through 23, D: day Mon through Sun
	//	if (($varip == "#.#.#.#") && (date("G", time()+10800) < 17) && (date(D) != "Sat") && (date(D) != "Sun") && (strstr($varbrowser, "MSIE"))) {
	//		?><script src="/scripts/scr_prompt.js"></script><?php
	//	}
	//	done
	//
	//
	//	deny_browser: disallow Adobe WebCapture-->"Mozilla/3.0 (compatible; WebCapture 2.0; Windows)". auto-link to URL: 	IE- javascript:q=document.selection.createRange().text;if(!q)void(q=prompt('PHP%20Reference:',''));if(q)location.href='http://us4.php.net/'+escape(q). JS- javascript:q=document.getSelection();if(!q)void(q=prompt('PHP Reference:',''));if(q)location.href='http://us4.php.net/'+escape(q)
	if (strstr($_SERVER["HTTP_USER_AGENT"], "WebCapture")) {
		// PHP: header("Location: http://www.spatiallink.org/browser.php");-->"Warning: Cannot modify header information", although this error can be suppressed.
		// OR
		// JS: <title>auto-forward</title><META HTTP-EQUIV="Refresh" CONTENT="0; URL=http://www.spatiallink.org/browser.php">
		print "Unauthorized Access or Incompatible Browser";
		exit;
		return;
		die;
	}
/*	done */
?>