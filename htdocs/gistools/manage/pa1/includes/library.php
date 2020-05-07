<?php
#Application name: PhpCollab
#Status page: 2
#Path by root: ../includes/library.php

$profilSession = "";

error_reporting(2039);
@ini_set("session.use_trans_sid",0);
@ini_set("session.bug_compat_warn","off");

//disable session on export
if ($export != "true" && $postnukeIntegration != "true") {
	session_start();
}

function returnGlobal($var,$type){
if (phpversion() >= "4.1.0") {
	if ($type == "SERVER") {
		return $_SERVER[$var];
	}
	if ($type == "POST") {
		return $_POST[$var];
	}
	if ($type == "GET") {
		return $_GET[$var];
	}
	if ($type == "SESSION") {
		return $_SESSION[$var];
	}
	if ($type == "REQUEST") {
		return $_REQUEST[$var];
	}
	if ($type == "COOKIE") {
		return $_COOKIE[$var];
	}
} else {
	if ($type == "SERVER") {
		return $_SERVER[$var];
	}
	if ($type == "POST") {
		return $HTTP_POST_VARS[$var];
	}
	if ($type == "GET") {
		return $HTTP_GET_VARS[$var];
	}
	if ($type == "SESSION") {
		return $HTTP_SESSION_VARS[$var];
	}
	if ($type == "REQUEST") {
		if ($HTTP_POST_VARS[$var] != "") {
			return $HTTP_POST_VARS[$var];
		} else if ($HTTP_GET_VARS[$var] != "") {
			return $HTTP_GET_VARS[$var];
		}
	}
	if ($type == "COOKIE") {
		return $HTTP_COOKIE_VARS[$var];
	}
}
}

// register_globals cheat code
//HTTP_GET_VARS
while (list($key, $val) = @each($HTTP_GET_VARS)) {
       $GLOBALS[$key] = $val;
}
//HTTP_POST_VARS
while (list($key, $val) = @each($HTTP_POST_VARS)) {
       $GLOBALS[$key] = $val;
}
//$HTTP_SESSION_VARS
while (list($key, $val) = @each($HTTP_SESSION_VARS)) {
       $GLOBALS[$key] = $val;
}
//$HTTP_SERVER_VARS
while (list($key, $val) = @each($HTTP_SERVER_VARS)) {
       $GLOBALS[$key] = $val;
}

$msg = returnGlobal('msg','GET');
$session = returnGlobal('session','GET');
$logout = returnGlobal('logout','GET');
$idSession = returnGlobal('idSession','SESSION');
$dateunixSession = returnGlobal('dateunixSession','SESSION');
$loginSession = returnGlobal('loginSession','SESSION');
$profilSession = returnGlobal('profilSession','SESSION');
$logouttimeSession = returnGlobal('logouttimeSession','SESSION');

global $loginSession;

//check last version of PhpCollab
function updatechecker($iCV) {
global $strings;
	$sVersiondata = join('',file("http://www.phpcollab.com/website/version.txt"));
	$aVersiondata = explode("|",$sVersiondata);
	$iNV = $aVersiondata[0];
	
	if ($iCV < $iNV) {
		$checkMsg = "<br><b>".$strings["update_available"]."</b> ".$strings["version_current"]." $iCV. ".$strings["version_latest"]." $iNV.<br>";
		$checkMsg .= "<a href=\"http://www.sourceforge.net/projects/phpcollab\" target=\"_blank\">".$strings["sourceforge_link"]."</a>.";
	}
return $checkMsg;
}

//calculate time to parse page
function getmicrotime(){
	list($usec, $sec) = explode(" ",microtime());
	return ((float)$usec + (float)$sec);
}
$parse_start = getmicrotime();

//database update array
$updateDatabase = array(0 => "1.0", 1 => "1.1", 2 => "1.3", 3 => "1.4", 4 => "1.6", 5 => "1.8", 6 => "1.9", 7 => "2.0", 8 => "2.1");

//languages array
$langValue = array("en" => "English", "es" => "Spanish", "fr" => "French", "it" => "Italian", "pt" => "Portuguese", "da" => "Danish", "no" => "Norwegian", "nl" => "Dutch", "de" => "German", "zh" => "Chinese simplified", "uk" => "Ukrainian", "pl" => "Polish", "in" => "Indonesian", "ru" => "Russian", "az" => "Azerbaijani", "ko" => "Korean", "zh-tw" => "Chinese traditional", "ca" => "Catalan", "pt-br" => "Brazilian Portuguese", "et" => "Estonian", "bg" => "Bulgarian", "ro" => "Romanian", "hu" => "Hungarian", "cs-iso" => "Czech (iso)", "cs-win1250" => "Czech (win1250)", "is" => "Icelandic", "sk-win1250" => "Slovak (win1250)", "tr" => "Turkish");

//settings and date selector includes
if ($postnukeIntegration == "true") {
	include("modules/PhpCollab/includes/settings.php");
} else {
	if ($indexRedirect == "true") {
		include("includes/settings.php");
	} else {
		include("../includes/settings.php");
	}
}

if ($langDefault != "") {
	$langSelected[$langDefault] = "selected";
} else {
	$langSelected = "";
}

//fix if update from old version
if ($theme == "" && $postnukeIntegration == "true") {
	$theme = "default";
}
if (!is_resource(THEME)) {
	define('THEME',$theme);
}
if (!is_resource(FTPSERVER)) {
	define('FTPSERVER','');
}
if (!is_resource(FTPLOGIN)) {
	define('FTPLOGIN','');
}
if (!is_resource(FTPPASSWORD)) {
	define('FTPPASSWORD','');
}
if ($uploadMethod == "") {
	$uploadMethod = "PHP";
}
if ($peerReview == "") {
	$peerReview = "true";
}

if ($loginMethod == "") {
	$loginMethod = "PLAIN";
}
if ($databaseType == "") {
	$databaseType = "mysql";
}
if ($installationType == "") {
	$installationType = "online";
}

//request data class
if ($postnukeIntegration == "true") {
	include("modules/PhpCollab/includes/initrequests.php");
	include("modules/PhpCollab/includes/request.class.php");
} else {
	if ($indexRedirect == "true") {
		include("includes/initrequests.php");
		include("includes/request.class.php");
	} else {
		include("../includes/initrequests.php");
		include("../includes/request.class.php");
	}
}

//layout class
if ($postnukeIntegration == "true") {
	include("modules/PhpCollab/themes/".THEME."/block.class.php");
} else {
	if ($indexRedirect == "true") {
			include("themes/".THEME."/block.class.php");
	} else {
		include("../themes/".THEME."/block.class.php");
	}
}

function headerFunction($url) {
global $postnukeIntegration;
if ($postnukeIntegration == "true") {
	$url = substr($url,3);
	$url = str_replace(".php?","&",$url);
	$url = str_replace(".php","",$url);
	header("Location:index.php?module=PhpCollab&func=main&file=$url");
} else {
	header("Location:$url");
}
}

//language browser detection
if ($langDefault == "") {
	if(isset($HTTP_ACCEPT_LANGUAGE)) {
		$plng = split(",", $HTTP_ACCEPT_LANGUAGE);
			if(count($plng) > 0) {
				while(list($k,$v) = each($plng)) {
					$k = split(";", $v, 1);

					$k = split("-", $k[0]);
						if(file_exists("../languages/lang_".$k[0].".php")) {
							$langDefault = $k[0];
							break;
						}
					$langDefault = "en";
				}





			} else {
				$langDefault = "en";
			}
	} else {
		$langDefault = "en";
	}
}

//check session validity on main phpcollab, except for demo user
if ($checkSession != "false" && $demoSession != "true") {

//if auto logout feature used, store last required page before deconnexion
if ($profilSession != "3") {
	if ($logouttimeSession != "0" && $logouttimeSession != "") {
		$dateunix=date("U");
		$diff = $dateunix-$dateunixSession;
			if ($diff > $logouttimeSession) {
				$sidCode = session_name();
				$page = $PHP_SELF."?".$QUERY_STRING;
				$page = eregi_replace("(&".$sidCode."=)([A-Za-z0-9.]*)($|.)", "", $page); 
				$page = eregi_replace("(".$sidCode."=)([A-Za-z0-9.]*)($|.)", "", $page);
$page = strrev($page);
$pieces = explode("/",$page);
$pieces[0] = strrev($pieces[0]);
$pieces[1] = strrev($pieces[1]);
$page = $pieces[1]."/".$pieces[0];
				$tmpquery = "UPDATE ".$tableCollab["members"]." SET last_page='$page' WHERE id = '$idSession'";
				connectSql("$tmpquery");
				headerFunction("../general/login.php?logout=true");
			} else {
				$dateunixSession=$dateunix;
				session_register("dateunixSession");
			}
	}
}
	$tmpquery = "WHERE log.login = '$loginSession'";
	$checkLog = new request();
	$checkLog->openLogs($tmpquery);
	$comptCheckLog = count($checkLog->log_id);
	if ($comptCheckLog != "0") {
		if (session_id() != $checkLog->log_session[0]) {
			headerFunction("../index.php?session=false");
		}
	} else {
		headerFunction("../index.php?session=false");
	}
}

//count connected users
if ($checkConnected != "false") {
	$dateunix=date("U");
	$tmpquery1 = "UPDATE ".$tableCollab["logs"]." SET connected='$dateunix' WHERE login = '$loginSession'";
	connectSql("$tmpquery1");
	$tmpsql = "SELECT * FROM ".$tableCollab["logs"]." WHERE connected > $dateunix-5*60";
	compt($tmpsql);
	$connectedUsers = $countEnregTotal;
}

//redirect if server/database in error
if ($databaseType == "mysql") {
if (!@mysqli_connect(MYSERVER,MYLOGIN,MYPASSWORD)) {
	headerFunction("../general/error.php?type=myserver");
	exit;
} else {
	$res = mysqli_connect(MYSERVER,MYLOGIN,MYPASSWORD);
}
if (!@mysqli_select_db(MYDATABASE,$res)) {
	headerFunction("../general/error.php?type=mydatabase");
	exit;
} else {
	@mysqli_close($res);
}
}

//disable actions if demo user logged in demo mode
if ($action != "") {
	if ($demoSession == "true") {
		$closeTopic = "";
		$addToSiteTask = "";
		$removeToSiteTask = "";
		$addToSiteTopic = "";
		$removeToSiteTopic = "";
		$addToSiteTeam = "";
		$removeToSiteTeam = "";
		$action = "";
		$msg = "demo";
	}
}

//set language session
if ($languageSession == "") {
	$lang = $langDefault;
} else {
	$lang = $languageSession;
}


//language include
if ($postnukeIntegration == "true") {
	include("modules/PhpCollab/languages/lang_".$lang.".php");
	include("modules/PhpCollab/languages/help_".$lang.".php");
} else {

	if ($indexRedirect == "true") {
		include("languages/lang_".$lang.".php");
		include("languages/help_".$lang.".php");
	} else {
		include("../languages/lang_".$lang.".php");
		include("../languages/help_".$lang.".php");
	}
}

//automatic links
function autoLinks($data) {
global $newText;
$lines = explode("\n", $data); 
while (list ($key, $line) = each ($lines)) { 
$line = eregi_replace("([ \t]|^)www\.", " http://www.", $line); 
$line = eregi_replace("([ \t]|^)ftp\.", " ftp://ftp.", $line); 

$line = eregi_replace("(http://[^ )\r\n]+)", "<a href=\"\\1\" target=\"_blank\">\\1</a>", $line); 
$line = eregi_replace("(https://[^ )\r\n]+)", "<a href=\"\\1\" target=\"_blank\">\\1</a>", $line); 
$line = eregi_replace("(ftp://[^ )\r\n]+)", "<a href=\"\\1\" target=\"_blank\">\\1</a>", $line); 
$line = eregi_replace("([-a-z0-9_]+(\.[_a-z0-9-]+)*@([a-z0-9-]+(\.[a-z0-9-]+)+))", "<a href=\"mailto:\\1\">\\1</a>", $line); 
if (empty($newText)) { 
	$newText = $line; 
} else { 
	$newText .= "\n$line"; 
} 
} 
}


//return number of day between 2 dates
function diff_date($date1, $date2){
	$an = substr("$date1", 0, 4);
	$mois = substr("$date1", 5, 2);
	$jour = substr("$date1", 8, 2);

	$an2 = substr("$date2", 0, 4);
	$mois2 = substr("$date2", 5, 2);
	$jour2 = substr("$date2", 8, 2);

	$timestamp = mktime(0, 0, 0, $mois, $jour, $an); 
	$timestamp2 = mktime(0, 0, 0, $mois2, $jour2, $an2); 
  	$diff = floor(($timestamp - $timestamp2) / (3600 * 24)); 
	return $diff; 
}

// checks for password match using the globally specified login method
function is_password_match($formUsername, $formPassword, $storedPassword ) {
	global $loginMethod, $useLDAP, $configLDAP;
	if($useLDAP == "true"){
		if($formUsername == "admin"){
			switch ($loginMethod) {
				case MD5:	
					if (md5($formPassword) == $storedPassword) {
						return true;
					} else {
						return false;
					}
				case CRYPT:	
					$salt = substr($storedPassword, 0, 2 );
					if (crypt($formPassword,$salt) == $storedPassword) {
						return true;
					} else {
						return false;
					}
				case PLAIN:	
					if ($formPassword == $storedPassword) {
						return true;
					} else {


						return false;
					}
				return false;
			}
		}
		$conn = ldap_connect($configLDAP[ldapserver]);
		$sr = ldap_search($conn, $configLDAP[searchroot], "uid=$formUsername");
		$info = ldap_get_entries($conn, $sr);
		$user_dn = $info[0]["dn"];
		if(!$bind = @ldap_bind($conn, $user_dn, $formPassword))
			return false;	
		else
			return true;
	}
	else{
	switch ($loginMethod) {
		case MD5:	
			if (md5($formPassword) == $storedPassword) {
				return true;
			} else {
				return false;
			}
			case CRYPT:	
				$salt = substr($storedPassword, 0, 2 );
				if (crypt($formPassword,$salt) == $storedPassword) {
					return true;
				} else {
					return false;
				}
			case PLAIN:	
				if ($formPassword == $storedPassword) {
					return true;
				} else {
					return false;
				}
			return false;
		}
	}
}

// return a password using the globally specified method
function get_password($newPassword) {
global $loginMethod;
switch ($loginMethod) {
 	case MD5:	
			return md5($newPassword);
	case CRYPT:	
			$salt = substr($newPassword,0,2);
			return crypt($newPassword,$salt);
	case PLAIN:	
			return $newPassword;
return $newPassword;
}
}

//generate password
function password_generator($size=8 , $with_numbers=true , $with_tiny_letters=true , $with_capital_letters=true){ 
global $pass_g;
$pass_g = ""; 
$sizeof_lchar = 0; 
$letter = ""; 
$letter_tiny = "abcdefghijklmnopqrstuvwxyz"; 
$letter_capital = "ABCDEFGHIJKLMNOPQRSTUVWXYZ"; 
$letter_number = "0123456789";
if($with_tiny_letters == true){ 
	$sizeof_lchar += 26; 
		if (isset($letter)) $letter .= $letter_tiny; 
		else $letter = $letter_tiny; 
} 
if($with_capital_letters == true){ 
	$sizeof_lchar += 26; 
		if (isset($letter)) $letter .= $letter_capital; 
		else $letter = $letter_capital; 
} 
if($with_numbers == true){ 
	$sizeof_lchar += 10; 
		if (isset($letter)) $letter .= $letter_number; 
		else $letter = $letter_number; 
} 
if($sizeof_lchar > 0){ 
	srand((double)microtime()*date("YmdGis")); 
	for($cnt = 0; $cnt < $size; $cnt++){ 
		$char_select = rand(0, $sizeof_lchar - 1); 
		$pass_g .= $letter[$char_select]; 
	} 
  } 
return $pass_g; 
}

function moveFile($source, $dest) {
global $mkdirMethod,$ftpRoot;
if($mkdirMethod == "FTP") {
	$ftp = ftp_connect(FTPSERVER);
	ftp_login($ftp,FTPLOGIN,FTPPASSWORD);
	ftp_rename($ftp, "$ftpRoot/$source", "$ftpRoot/$dest");
	ftp_quit($ftp);
} else {
	copy("../".$source, "../".$dest);
}
}

function deleteFile($source) {
global $mkdirMethod,$ftpRoot;
if($mkdirMethod == "FTP") {
	$ftp = ftp_connect(FTPSERVER);
	ftp_login($ftp,FTPLOGIN,FTPPASSWORD);
	ftp_chdir($ftp, $pathNew);
	ftp_delete($ftp, $ftpRoot."/".$source);
	ftp_quit($ftp);
} else {
	unlink("../".$source);
}
}

function uploadFile($path, $source, $dest) {
global $mkdirMethod,$ftpRoot;
if($mkdirMethod == "FTP") {
	$pathNew = $ftpRoot."/".$path;
	$ftp = ftp_connect(FTPSERVER);
	ftp_login($ftp,FTPLOGIN,FTPPASSWORD);
	ftp_chdir($ftp, $pathNew);
	ftp_put($ftp, $dest, $source, FTP_BINARY);
	ftp_quit($ftp);
} else {
	@move_uploaded_file($source, "../".$path."/".$dest);
}
}

//folder creation
function createDir($path) {
global $mkdirMethod,$ftpRoot;
if ($mkdirMethod == "FTP") {
	$pathNew = $ftpRoot."/".$path;
	$ftp = ftp_connect(FTPSERVER);
	ftp_login($ftp,FTPLOGIN,FTPPASSWORD);
	ftp_mkdir ($ftp,$pathNew);
	ftp_quit ($ftp);
}
if ($mkdirMethod == "PHP") {
	@mkdir("../$path",0755);
	@chmod("../$path",0777);
}
}

//folder recursive deletion
function delDir($location) { 
	if(is_dir($location)) {
		$all = opendir($location); 
		while ($file = readdir($all)) { 
			if (is_dir("$location/$file") && $file !=".." && $file!=".") { 
				deldir("$location/$file"); 
				if(file_exists("$location/$file")){@rmdir("$location/$file"); }
				unset($file); 
			} else if (!is_dir("$location/$file")) { 


				if(file_exists("$location/$file")){@unlink("$location/$file"); }
				unset($file); 
			} 
		} 
		closedir($all);
		@rmdir($location);
	} else {
		if(file_exists("$location")) {@unlink("$location");}
	}
}

//return recursive folder size
function folder_info_size($path, $recursive=TRUE) { 
$result = 0; 
	if (is_dir($path) || is_readable($path)) {
		$dir = opendir($path); 
		while($file = readdir($dir)) {
			if ($file != "." && $file != "..") {
				if (@is_dir("$path$file/")) {
					$result += $recursive?folder_info_size("$path$file/"):0; 
				} else {
					$result += filesize("$path$file"); 
				} 
			}
		} 
	closedir($dir);


	return $result;
	} 
}

function convertSize($result) {
global $byteUnits;
	if ($result >= 1073741824) {
		$result = round($result / 1073741824 * 100) / 100 . " ".$byteUnits[3];
	} else if ($result >= 1048576) {
		$result = round($result / 1048576 * 100) / 100 . " ".$byteUnits[2];

	} else if ($result >= 1024) {
		$result = round($result / 1024 * 100) / 100 . " ".$byteUnits[1];
	} else {
		$result = $result . " ".$byteUnits[0];
	}
	if($result==0) {
		$result="-";
	}
	return $result;
}

//return file size
function file_info_size($fichier) {
	global $taille;
	$taille = filesize($fichier);
	return $taille;
}

//return file dimensions
function file_info_dim($fichier) { 
	$temp = GetImageSize($fichier); 
	global $dim; 
	$dim = ($temp[0])."x".($temp[1]); 
	return $dim; 
} 

//return file date
function file_info_date($fichier) {
	global $dateFile;
	$dateFile = date("Y-m-d",filemtime($fichier));
	return $dateFile;
}

function recupFile($file) {
if(!file_exists($file)) {
	echo "File does not exist : " . $file;
		return false;
}
$fp = fopen ($file, "r");
if(!$fp) {
	echo "Unable to open file : " . $file;
	return false;
}

while (!feof ($fp)) {
	$tmpline = fgets($fp, 4096);
	$content .= $tmpline;
}

fclose($fp);
return $content;
}

//provide id session if trans_sid false on server (if $trans_sid true in settings)
if ($trans_sid == "true") {
	global $transmitSid;
	$transmitSid = session_name()."=".session_id();
}

//time variables
if ($gmtTimezone == "true") {
	$date = gmdate("Y-m-d");
	$dateheure = gmdate("Y-m-d H:i");
} else {
	$date = date("Y-m-d");
	$dateheure = date("Y-m-d H:i");
}

function createDate($storedDate, $gmtUser) {
global $gmtTimezone;
if ($gmtTimezone == "true") {
	if ($storedDate != "") {
		$extractHour = substr("$storedDate", 11, 2);
		$extractMinute = substr("$storedDate", 14, 2);
		$extractYear = substr("$storedDate", 0, 4);
		$extractMonth = substr("$storedDate", 5, 2);
		$extractDay = substr("$storedDate", 8, 2);

		return date("Y-m-d H:i", mktime($extractHour + $gmtUser,$extractMinute,'',$extractMonth,$extractDay,$extractYear));
	}
} else {
	return $storedDate;
}
}

//update sorting table if query sort column
if ($sor_cible != "" && $sor_champs != "none") {
	$tmpquery = "UPDATE ".$tableCollab["sorting"]." SET $sor_cible='$sor_champs $sor_ordre' WHERE member = '$idSession'";
	connectSql("$tmpquery");
}

//set all sorting values for logged user
$tmpquery = "WHERE sor.member = '$idSession'";
$sortingUser = new request();
$sortingUser->openSorting($tmpquery);

//convert insert data value in form
function convertData($data) {
global $databaseType;
	if ($databaseType == "sqlserver") {
		$data = str_replace('"','&quot;',$data);
		$data = str_replace("'",'&#39;',$data);
		$data = str_replace('<','&lt;',$data);
		$data = str_replace('>','&gt;',$data);
		$data = stripslashes($data);
		return ($data);
	} else if (get_magic_quotes_gpc() == 1) {
		$data = str_replace('"','&quot;',$data);
		$data = str_replace('<','&lt;',$data);
		$data = str_replace('>','&gt;',$data);
		return ($data);
     	} else {
		$data = str_replace('"','&quot;',$data);
		$data = str_replace('<','&lt;',$data);
		$data = str_replace('>','&gt;',$data);
		$data = addslashes($data);
		return ($data);
	}
}

//count total results from a request
function compt($tmpsql) {
global $tableCollab,$databaseType,$countEnregTotal,$comptRequest;
$comptRequest = $comptRequest + 1;
if ($databaseType == "mysql") {
$res = mysqli_connect(MYSERVER,MYLOGIN,MYPASSWORD) or die($strings["error_server"]);
mysqli_select_db(MYDATABASE,$res) or die($strings["error_database"]);
$sql = "$tmpsql";
$index = mysqli_query($varconnect, $sql, $res);
while($row = mysqli_fetch_row($index)) {
	$countEnreg[] = ($row[0]);
}
$countEnregTotal = count($countEnreg);
@mysqli_free_result($index);
@mysqli_close($res);
}
if ($databaseType == "postgresql") {
$res = pg_connect("host=".MYSERVER." port=5432 dbname=".MYDATABASE." user=".MYLOGIN." password=".MYPASSWORD);
$sql = "$tmpsql";
$index = pg_query($res,$sql);
while($row = pg_fetch_row($index)) {
	$countEnreg[] = ($row[0]);
}
$countEnregTotal = count($countEnreg);
@pg_free_result($index);
@pg_close($res);
}
if ($databaseType == "sqlserver") {
$res = mssql_connect(MYSERVER,MYLOGIN,MYPASSWORD) or die($strings["error_server"]);
mssql_select_db(MYDATABASE,$res) or die($strings["error_database"]);
$sql = "$tmpsql";
$index = mssql_query($sql, $res);
while($row = mssql_fetch_row($index)) {
	$countEnreg[] = ($row[0]);
}
$countEnregTotal = count($countEnreg);
@mssql_free_result($index);
@mssql_close($res);
}
return $countEnregTotal;
}

//simple query
function connectSql($tmpsql) {
global $tableCollab,$databaseType;
if ($databaseType == "mysql") {
$res = mysqli_connect(MYSERVER,MYLOGIN,MYPASSWORD) or die($strings["error_server"]);
mysqli_select_db(MYDATABASE,$res) or die($strings["error_database"]);
$sql = $tmpsql;
$index = mysqli_query($varconnect, $sql, $res);
@mysqli_free_result($index);
@mysqli_close($res) ;
}
if ($databaseType == "postgresql") {
$res = pg_connect("host=".MYSERVER." port=5432 dbname=".MYDATABASE." user=".MYLOGIN." password=".MYPASSWORD);

$sql = $tmpsql;
$index = pg_query($res,$sql);
@pg_free_result($index);
@pg_close($res) ;
}
if ($databaseType == "sqlserver") {
$res = mssql_connect(MYSERVER,MYLOGIN,MYPASSWORD) or die($strings["error_server"]);
mssql_select_db(MYDATABASE,$res) or die($strings["error_database"]);
$sql = $tmpsql;
$index = mssql_query($sql, $res);
@mssql_free_result($index);
@mssql_close($res) ;
}
}

//return last id from any table
function last_id($tmpsql) {
global $tableCollab,$databaseType;
if ($databaseType == "mysql") {
$res = mysqli_connect(MYSERVER,MYLOGIN,MYPASSWORD) or die($strings["error_server"]);
mysqli_select_db(MYDATABASE,$res) or die($strings["error_database"]);
global $lastId;
$sql = "SELECT id FROM $tmpsql ORDER BY id DESC";
$index = mysqli_query($varconnect, $sql,$res);
while($row = mysqli_fetch_row($index)) {
	$lastId[] = ($row[0]);
}
@mysqli_free_result($index);
@mysqli_close($res);
}
if ($databaseType == "postgresql") {
$res = pg_connect("host=".MYSERVER." port=5432 dbname=".MYDATABASE." user=".MYLOGIN." password=".MYPASSWORD);
global $lastId;
$sql = "SELECT id FROM $tmpsql ORDER BY id DESC";
$index = pg_query($res,$sql);
while($row = pg_fetch_row($index)) {
	$lastId[] = ($row[0]);
}
@pg_free_result($index);
@pg_close($res);
}
if ($databaseType == "sqlserver") {
$res = mssql_connect(MYSERVER,MYLOGIN,MYPASSWORD) or die($strings["error_server"]);
mssql_select_db(MYDATABASE,$res) or die($strings["error_database"]);
global $lastId;
$sql = "SELECT id FROM $tmpsql ORDER BY id DESC";
$index = mssql_query($sql,$res);



while($row = mssql_fetch_row($index)) {
	$lastId[] = ($row[0]);
}
@mssql_free_result($index);
@mssql_close($res);
}
}

/*spatiallink: $setCopyright = "<!-- Powered by PhpCollab v$version //-->";*/
$setCopyright = "";
?>