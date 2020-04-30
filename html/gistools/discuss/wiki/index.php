<?php
/*	img_verification_code: goes before anything else

	using fwrite() instead of session().

	initialize session for img_verification_code: start a new session with session_start(). call this session on a different page later. also, create $verification_code that will be assigned a value later. and then call session_register(). 

	note that session_register() can only take in $verification_code minus the "$" sign inside single quotes. an argument/variable refers to ' or " separated information inside () of a function.

	note that session_start() must be the 1st statement right after "<?". and that "<?" must be the 1st character in your page, not even a whitespace before it. so you need to always put session_start()/setcookie()/header() at the very top of your code, even before <html>. also, "<?" has to be the very 1st line, even before !DOCTYPE.
	
	Warning: Cannot add header information - headers already sent by (output started at c:\inetpub\wwwroot\test.php:3) in c:\inetpub\wwwroot\test.php on line 5
	This warning tells you that test.php started some output on line 3.
	
	http://forums.devshed.com/t25412/s.html */
//	session_start();
//	$verification_code;
//	session_register('verification_code');
//	img_verification_code
//	do NOT close php before sending the header() below

// intro xhtml: goes before anything else
print '<!DOCTYPE html>';
print '<html>';

// include head
include '/var/chroot/home/content/57/3881957/html/inc/inc_head.php';
//	include wiki.css
print '<link rel="stylesheet" type="text/css" href="/css/wiki.css" />';
print "<body>";
// include header
include '/var/chroot/home/content/57/3881957/html/inc/inc_header.php'; 
// include leftbar
include '/var/chroot/home/content/57/3881957/html/inc/inc_leftbar.php'; 

/* CONFIGURATION */
//	$data_dir = "../../data"; to put the files out of the webservers area
$data_dir = "wikidata";
$page_default = "MainPage";
//	key and history variables: note that a simple URL typo will start an edit session. EX: WhatIsWiki]]] will start an edit session for empty $filename = WhatIsWiki]]]. a corresponding $key will thus be created.
$key_dir = "/var/chroot/home/content/57/3881957/html/gistools/discuss/wiki/wikikey/";
$history_dir = "/var/chroot/home/content/57/3881957/html/gistools/discuss/wiki/wikihistory/";
$wiki_url_sub = "spatiallink.org/gistools/discuss/wiki/index.php?wiki=";
$filename = $_GET['wiki'];
$edit_true = "&edit=True";
//	note: advisable to keep *.html for security
$template_show = "show.html";
$template_edit = "edit.html";
$wiki_get = "wiki";
// if you have to adjust the timezone (if server uses GMT and you want GMT+1, then $timezone must be 1. Note that EST = GMT - 5.
$timezone;
/* CONFIGURATION */

/*	ACCESS CHECK */
//	may NOT contain www
if ($varstristrurl == $wiki_url_sub.$filename.$edit_true) {
	//	editing: hence check referral
	if ($varstristrrefsite != $wiki_url_sub.$filename) {
		//	referral NOT OK: hence stop
		include '/var/chroot/home/content/57/3881957/html/inc/inc_stop.php';
	}
}
/*	ACCESS CHECK */

	// if ( PHP version < 4.1 ) $_GET is not available!
	$version_info = explode('.', phpversion());
	if ($version_info[0] < 4 || ($version_info[0] > 3 && $version_info[1] < 1)) {
	        $_POST = $HTTP_POST_VARS;
	        $_GET = $HTTP_GET_VARS;
	}
	
	// specials
	function findpage() {
		global $wiki_get;
		global $name;
		global $data_dir;
		$name = basename($name);
		$content = "";
		$formular = "<form method=\"get\" action=\"index.php\">\n<input type=\"hidden\" name=\"$wiki_get\" value=\"$name\" /><table id=\"find\">\n<tr>\n<td>Page Name</td>\n<td><input type=\"text\" name=\"PageName\" /></td>\n</tr><tr>\n<td>Page Content</td>\n<td><input type=\"text\" name=\"PageContent\" /></td>\n</tr><tr>\n<td colspan=\"2\"><input type=\"submit\" value=\"WIKI\" /></td>\n</tr>\n</table>\n</form>\n";
		// get all existing pages from the directory
		$handle = opendir("$data_dir");
		while( $newdir = readdir($handle) )
			if(preg_match("/([A-Z][a-z0-9]+){2,}/",$newdir) )
				$allpages[] = $newdir;
		// search for matching page names
		if( isset($_GET["PageName"]) && $_GET["PageName"] != "" ) {
			$pagename = $_GET["PageName"];
			$content .= "Searching for page name $pagename\n<ul>\n";
			foreach ($allpages as $page)
				if (preg_match("/$pagename/i",$page) )
					$content .= "<li><a href=\"index.php?$wiki_get=$page\">$page</a></li>\n";
				$content .= "</ul>\n";
		}
		// search for matching page content
		if ( isset($_GET["PageContent"]) && $_GET["PageContent"] != "" ) {
			$pagecontent = $_GET["PageContent"];
			$content .= "Searching page content for $pagecontent\n<ul>\n";
			foreach ($allpages as $page) {
				$current = implode( "", file("$data_dir/$page") );
				if (preg_match("/$pagecontent/i",$current) )
					$content .= "<li><a href=\"index.php?$wiki_get=$page\">$page</a></li>\n";
			}
			$content .= "</ul>\n";
		}
		if( $content == "" )
			$content = $formular;
		return $content;
	}
	
	function recentchanges() {
		global $data_dir;
		global $timezone;
		global $wiki_get;
		$content = "";
		$handle = opendir($data_dir);
		$allpages = array();
		while( $newfile = readdir($handle) )
			if(preg_match("/([A-Z][a-z0-9]+){2,}/",$newfile) )
				$allpages[] = $newfile;
		$max_pages = sizeof($allpages) / 3;
		if( $max_pages < 30 )
			$max_pages = 30;
		$counter = 0;
		$date = mktime(12,0,0,date('m'),date('d'));
		$day = 0;
		while( $counter < $max_pages && $day < 40 ) {
			$today = array();
			foreach( $allpages as $page ) {
				$filetime = filemtime("$data_dir/$page");
				if( (($filetime + 43199) > $date) && (($filetime - 43200) < $date) )
					$today[] = $page;
			}
			if( sizeof($today) > 0 ) {
				$content .= "<h3>".date("d.m.Y",$date)."</h3>\n<ul>\n";
				foreach( $today as $page )
					$content .= "<li><a href=\"index.php?$wiki_get=$page\">$page</a> (".date("G:i", filemtime("$data_dir/$page")+(3600*$timezone) ).")</li>";
				$content .= "</ul>\n";
			}
			$counter += sizeof($today);
			$date -= 86400;
			$day += 1;
		}
		$content .= "</ul>\n";
		return $content;
	}
	
	function allpages() {
		global $data_dir;
		global $wiki_get;
		// list all existing pages
		$content = "<ul>\n";
		$handle = opendir($data_dir);
		while( $newfile = readdir($handle) )
			if(preg_match("/([A-Z][a-z0-9]+){2,}/",$newfile) )
				$allpages[] = $newfile;
		sort($allpages);
		foreach( $allpages as $page )
			$content .= "<li><a href=\"index.php?$wiki_get=$page\">$page</a></li>\n";
		$content .= "</ul>\n";
		return $content;
	}
	
	// format the WikiStyle to XHTML
	function filter($raw) {
		global $wiki_get;
		$regexURL = "((http|https|ftp|mailto):\/\/[\w\.\:\@\?\&\~\%\=\+\-\/\_\;]+)";
		$regexURLText = "([\w\.\:\@\?\&\~\%\=\+\-\/\_\ \;\,\$]+)";
		
		$filtered = stripslashes(htmlentities("\n\n".$raw,ENT_NOQUOTES,"UTF-8"));
	
		// php-specific
		$filtered = str_replace("\r\n","\n",$filtered);
	
		// [ url | link ] external links
		$filtered = preg_replace("/\[$regexURL\|$regexURLText\]/i","<a href=\"\\1\">\\3</a>", $filtered);
	
		// pictures [ url ]
		$filtered = preg_replace("/\[($regexURL\.(png|gif|jpg))\]/i","<img src=\"\\1\" class=\"wikiimage\" />",$filtered);
		
		// plain urls in the text
		$filtered = preg_replace("/(?<![\"\[])$regexURL(?!\")/","<a href=\"\\0\">\\0</a>",$filtered);
		
		// the WikiWords
		// look several lines below for another way of creating wiki pages
		$filtered = preg_replace("/(?<=\s)([A-Z][a-z0-9\;\&]+){2,}/","<a href=\"index.php?$wiki_get=\\0\">\\0</a>", $filtered);
		
		// headlines <h1><h2><h3>
		$filtered = preg_replace("/\n(!!!)(.+)\n/","</p>\n<h5>\\2</h5>\n<p>",$filtered);
		$filtered = preg_replace("/\n(!!)(.+)\n/","</p>\n<h3>\\2</h3>\n<p>",$filtered);
		$filtered = preg_replace("/\n(!)(.+)\n/","</p>\n<h2>\\2</h2>\n<p>",$filtered);
	
		// text decorations (bold,italic,underline,boxed)
		$filtered = preg_replace("/\*\*(.+)\*\*/U","<strong>\\1</strong>", $filtered);
		$filtered = preg_replace("/__(.+)__/U","<u>\\1</u>", $filtered);
		$filtered = preg_replace("/''(.+)''/U","<em>\\1</em>", $filtered);
		$filtered = preg_replace("/\|(.+)\|/U","<span class=\"box\">\\1</span>", $filtered);
		
		// horizontal lines
		$filtered = preg_replace("/\n---.*\n/","\n<hr class=\"wiki\"/>\n",$filtered);
	
		// lists <ul>
		$filtered = preg_replace("/(?<=[\n>])\* (.+)\n/","<li>\\1</li>",$filtered);
		$filtered = preg_replace("/<li>(.+)\<\/li>/","</p><ul>\\0</ul><p>",$filtered);
		
		// strip leading and ending line breaks
		$filtered = preg_replace("/^(\n+)/","",$filtered); 
		$filtered = preg_replace("/\n{3,}/","\n\n",$filtered); 
	
		// <pre> blocks
		$filtered = preg_replace("/(?<=\n) (.*)(\n)/","<pre>\\1</pre>", $filtered);
		
		// ad html line breaks <br />
		$filtered = str_replace("\n","<br />\n",$filtered);
		$filtered = str_replace("</pre><pre>","\n", $filtered);
	
		// create wiki pages with [brackets]
		// if you uncomment this line, you should comment the standard WikiWord line
		// this line makes words between such [ ] brackets a link to a wiki page
		// $filtered = preg_replace("/\[([\w]+)\]/","<a href=\"index.php?$wiki_get=\\1\">\\1</a>", $filtered);
		
		// insert specials, check it first to prevent useless execution of the functions
		if( strpos($filtered, "&lt;findpage&gt;") !== FALSE )
			$filtered = str_replace("&lt;findpage&gt;", findpage(), $filtered);
		if( strpos($filtered, "&lt;allpages&gt;") !== FALSE )
			$filtered = str_replace("&lt;allpages&gt;", allpages(), $filtered);
		if( strpos($filtered, "&lt;recentchanges&gt;") !== FALSE )
			$filtered = str_replace("&lt;recentchanges&gt;", recentchanges(), $filtered);
	
		// html replace
		$filtered = str_replace("</li>","</li>\n",$filtered);
		$filtered = str_replace("ul>","ul>\n",$filtered);
		$filtered = str_replace("<br />\n<h","\n<h", $filtered);
		$filtered = preg_replace("/(<\/h[1-3]>)<br \/>\n/","\\1\n", $filtered);
		$filtered = str_replace("<p></p>","",$filtered);
		
		return $filtered;
	}
	
	// the ONLY page output of this script
	function output($data, $file) {
		$pagename = basename($file);
		$modified = "";
		if( file_exists($file) ) {
			$modified = date("g:i A T, jS F Y",filemtime($file));
		}
		$data = str_replace("<!--wikiname-->",$pagename,$data);
		$data = str_replace("<!--lastmodified-->",$modified,$data);
		echo $data;
	}
	
	// get the page content
	function showpage($file) {
		global $template_show;
		global $wiki_get;
		$content = "";
		
		// get the wanted file
		$raw = implode("", file($file) );
		// but filter it !
		$content = filter( $raw ) . $content;
		
		// get the page template
		$template = implode( "", file($template_show) );
		$whole = str_replace("<!--wikicontent-->",$content,$template);
		output( $whole, $file );
	}
	
	//	edit a page
	function editpage($file) {
						
			/* lock first: $file can be = "wikidata/AllPages"; and on EDIT, the following $_GET is passsed 'http://www.spatiallink.org/gistools/discuss/wiki/index.php?wiki=AllPages&edit=True'. so... */
			//	declare key and history variables as global once to use withIN f()
			global $key_dir;
			global $filename;
			$key = $key_dir.$filename;
			//	check to see if $key exists. also, refer to is_file(). also, check to see if $key's time-limit [900 seconds or 15 minutes] has NOT expired. in such a case, EDIT is locked. else, EDIT is NOT locked and $key gets overwritten with a new time-limit. note that getlastmod() works ONLY for the current file and $vartimestamp stamps the date ONLY of the index file. also, refer to http://www.4webhelp.net/us/timestamp.php.
			if ((file_exists($key)) && (time()-filemtime($key) < 900)) {
				//	lock edit: $key exists AND its time-limit has NOT expired. note that time-limit can NOT expire if $key does NOT exist.
				$lock = "<span class=\"medium\">Page is Under Edit or Protected</span>";
				output($lock, $file);
				/* lock first */
			} else {
				//	include scripts for comparison
				//// include '/var/chroot/home/content/57/3881957/html/scripts_xhr/scr_xhr_gethttpobject.js';
				//// include '/var/chroot/home/content/57/3881957/html/scripts_xhr/scr_xhr_wiki_submit.js';
				?>
				<script src="/scripts_xhr/scr_xhr_gethttpobject.js"></script>
				<script src="/scripts_xhr/scr_xhr_wiki_submit.js"></script>
				<?php
				//	img_verification_code: set $verification_code
				include '/var/chroot/home/content/57/3881957/html/images/img_verification_code.php';
				//	do NOT lock first: 'w' open for writing only; place the file pointer at the beginning of the file and truncate the file to zero length. if the file does not exist, attempt to create it. Windows offers a text-mode translation flag ('t') which will transparently translate \n to \r\n when working with the file. In contrast, you can also use 'b' to force binary mode. To use these flags, specify either 'b' or 't' as the last character of the mode parameter.
				$filehandle1 = fopen($key, "w");
				$filecontent1 = $verification_code;
				fwrite($filehandle1, $filecontent1);
				fclose($filehandle1);
				//	delete $key only after fclose(). refer below.
											
				global $template_edit;
				$already = "";
				if( file_exists( $file ) )
					$already = implode( "", file( "$file" ) );
				if( file_exists( $template_edit ) )
					$template = file($template_edit);
				else
					echo "No Template (".$template_edit.") found!<br />\n";
				$template = implode( "", $template );
				$whole =str_replace("<!--already-->",stripslashes($already),$template);
				// file could be locked
				if( file_exists($file) && !is_writable($file) )
					// note the s modifier !
					$whole = preg_replace("/<form>.*<\/form>/s","<span class=\"medium\">Page is Under Edit or Protected</span>", $whole);
				output($whole, $file );
				/* do NOT lock first */			
			}
		
	}
		
	$edit = False;
	$name = "$data_dir/$page_default";
	if( isset( $_GET[$wiki_get] ) )
		$name = "$data_dir/".basename($_GET[$wiki_get]);
		# basename() solves security issue: $wiki_get could be "../../../../etc/passwd" ;)
	// now we know the name of the current page
	
	// write possible stuff from a edit session
	if (isset($_POST["content"])) {
		$data = rtrim($_POST["content"])."\n";
		
		/* if you are having trouble just creating files then set the directory permissions to allow writing (for whatever directory the file is supposed to be in), and include touch before fopen().
			chmod 777 * (repeat for all sub-directories)
			chmod 700 * (repeat for all sub-directories) */
		if (touch($name)) {
			$handle = fopen("$name",'w');
		} else {
			print "Could NOT change modification time of $name";
		}
			
		if( ! fwrite($handle, $data ) ) {
			$data_perm = decoct(fileperms($name)) % 1000;
			$data_owner = (fileowner($name));
			$data_group = (filegroup($name));
			die("Error writing Data into $name ( it has permissions $data_perm  and is owned by $data_owner:$data_group) !");
		}
		fclose($handle);
		
		/*	delete last: delete $key once unique EDIT has been posted */
		//	do NOT declare key and history variables as global once to use withIN IF() {}
		$key = $key_dir.$filename;
		//	check to see if $key is present. also, refer to is_file(). $key MUST be present. if EDIT was refreshed W/O posting, EDIT gets lost and $key does NOT get deleted. hence, inserted time-limit on EDIT above. also, refer to unset(), static(), session(), @unlink. this check is also helpful when $key gets deleted during server clean-up.
		if (file_exists($key)) {
			unlink($key);
		}
		/* delete last */
		/*	create history: alternatively, you can set-up auto-mail notifications */
		$history = $history_dir.$filename;
		//	'a' open for writing only; place the file pointer at the end of the file. If the file does not exist, attempt to create it.
		$filehandle2 = fopen($history, "a");
		//	include
		if (!$vartime) {
			// environment variables have NOT been set
			include '/var/chroot/home/content/57/3881957/html/inc/inc_environment.php';
		}
		$filecontent2 = "$vardate::$vartime::$varuserhost::$varip\r\n";
		fwrite($filehandle2, $filecontent2);
		fclose($filehandle2);
		/*	create history */
	}
	
	if( ! file_exists( "$name" ) )
		$edit = True;
	if( isset($_GET["edit"]) && $_GET["edit"] == "True" )
		$edit = True;
	
	// error: commented to avoid 'Warning: Cannot add header information - headers already sent'
	// force reload
	// header ("Expires: Mon, 26 Jul 1990 05:00:00 GMT");
	// header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	// header ("Cache-Control: no-cache, must-revalidate");
	// header ("Pragma: no-cache");
	
	// shall we edit or just show it?
	if ($edit ) {
		editpage( $name );
	} else {
		showpage( $name );
	}	

// include rightbar
include '/var/chroot/home/content/57/3881957/html/inc/inc_rightbar.php'; 
// include footer
include '/var/chroot/home/content/57/3881957/html/inc/inc_footer.php';
// done
?>