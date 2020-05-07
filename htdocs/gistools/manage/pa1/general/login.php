<?php
#Application name: PhpCollab
#Status page: 0
#Path by root: ../general/login.php

$checkSession = "false";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	$postnukeIntegration = "true";
	include("modules/PhpCollab/includes/library.php");
} else {
	$postnukeIntegration = "false";
	include("../includes/library.php");
}

$auth = returnGlobal('auth','GET');
$loginForm = returnGlobal('loginForm','POST');
$passwordForm = returnGlobal('passwordForm','POST');

if ($logout == "true") {
	$tmpquery1 = "UPDATE ".$tableCollab["logs"]." SET connected='' WHERE login = '$loginSession'";
	connectSql("$tmpquery1");
	session_destroy();
	session_start();
}

$match = false;
$ssl = false;
if (!empty($SSL_CLIENT_CERT) && !$logout && $auth != "test") {
        $auth = "on";
        $ssl = true;
        if (function_exists("openssl_x509_read")) {
                $x509 = openssl_x509_read($SSL_CLIENT_CERT);
                $cert_array = openssl_x509_parse($x509, true);
                $subject_array = $cert_array["subject"];
                $ssl_email = $subject_array["Email"];
                openssl_x509_free($x509);
        } else {
                $ssl_email = `echo "$SSL_CLIENT_CERT" | $pathToOpenssl x509 -noout -email`;
        }
} else {
	//test blank fields in form
	if ($auth == "test") {
		if ($loginForm == "" && $passwordForm == "") {
			$error = $strings["login_username"]."<br>".$strings["login_password"];
		} else if ($loginForm == "") {
			$error = $strings["login_username"];
		} else if ($passwordForm == "") {
			$error = $strings["login_password"];
		} else {
			$auth = "on";
			if ($rememberForm == "on") {
				$oneyear = 22896000;
				$storePwd = get_password($passwordForm);
				setcookie("loginCookie", $loginForm, time() + $oneyear);
				setcookie("passwordCookie", $storePwd, time() + $oneyear);
			} else {
				setcookie("loginCookie");
				setcookie("passwordCookie");
			}
		}
	}
	
	if ($forcedLogin == "false") {
		if ($auth == "on" && !$loginForm && !$passwordForm) {
			$auth = "off";
			$error = "Detecting variables poisoning ;-)";
		}
	}
}

//authentification
if ($auth == "on") {
$loginForm = strip_tags($loginForm);
$passwordForm = strip_tags($passwordForm);

//query in members table (demo user not listed if demo mode false, to prohibit the access)
	if ($demoMode != "true") {
		if ($ssl) {
			$tmpquery = "WHERE mem.email_work = '$ssl_email' AND mem.login != 'demo' AND mem.profil != '4'";
		} else {
			$tmpquery = "WHERE mem.login = '$loginForm' AND mem.login != 'demo' AND mem.profil != '4'";
		}
	} else {
		$tmpquery = "WHERE mem.login = '$loginForm' AND mem.profil != '4'";
	}
	$loginUser = new request();
	$loginUser->openMembers($tmpquery);
	$comptLoginUser = count($loginUser->mem_id);

//test if user exits
	if ($comptLoginUser == "0") {
		$error = $strings["invalid_login"];
		setcookie("loginCookie");
		setcookie("passwordCookie");
	} else {

//test password
		if (!$ssl && !is_password_match($loginForm, $passwordForm, $loginUser->mem_password[0])) {
			$error = $strings["invalid_login"];
		} else {
			$match = true;
		}
		if ($match == true) {

//crypt password in session
				$r = substr($passwordForm, 0, 2); 
				$passwordForm = crypt($passwordForm, $r);

//set session variables
				$browserSession = $HTTP_USER_AGENT;
				$idSession = $loginUser->mem_id[0];
				$timezoneSession = $loginUser->mem_timezone[0];
				$languageSession = $languageForm;
	                 	$loginSession = $loginForm;
				$passwordSession = $passwordForm;
				$nameSession = $loginUser->mem_name[0];
				$profilSession = $loginUser->mem_profil[0];
				$ipSession = $REMOTE_ADDR;
				$dateunixSession = date("U");
				$dateSession = date("d-m-Y H:i:s");
				$logouttimeSession = $loginUser->mem_logout_time[0];
	                  session_register("browserSession","idSession","timezoneSession","languageSession","loginSession","passwordSession","nameSession","ipSession","dateunixSession","dateSession","profilSession","logouttimeSession");

//register demosession = true in session if user = demo
				if ($loginForm == "demo") {
	                        $demoSession = "true";
	                        session_register("demoSession");
				}

//insert into or update log
				$ip=$REMOTE_ADDR;
				$tmpquery = "WHERE log.login = '$loginForm'";
				$registerLog = new request();
				$registerLog->openLogs($tmpquery);
				$comptRegisterLog = count($registerLog->log_id);
				$session=session_id();
					if ($comptRegisterLog == "0") {
						$tmpquery1 = "INSERT INTO ".$tableCollab["logs"]."(login,password,ip,session,compt,last_visite) VALUES('$loginForm','$passwordForm','$ip','$session','1','$dateheure')";
						connectSql("$tmpquery1");
					} else {
						$lastvisiteSession = $registerLog->log_last_visite[0];
						session_register("lastvisiteSession");
						$increm = $registerLog->log_compt[0] + 1;
						$tmpquery1 = "UPDATE ".$tableCollab["logs"]." SET ip='$ip',session='$session',compt='$increm',last_visite='$dateheure' WHERE login = '$loginForm'";
						connectSql("$tmpquery1");
					}

//redirect for external link to internal page
				if ($url != "") {
					if ($loginUser->mem_profil[0] == "3") {
						headerFunction("../projects_site/$url&updateProject=true&".session_name()."=".session_id());
					} else {
						headerFunction("../$url&".session_name()."=".session_id());
					}

//redirect to last page required (with auto log out feature)
				} else if ($loginUser->mem_last_page[0] != "") {
					$tmpquery = "UPDATE ".$tableCollab["members"]." SET last_page='' WHERE login = '$loginForm'";
					connectSql("$tmpquery");
					headerFunction("../".$loginUser->mem_last_page[0]."&".session_name()."=".session_id());

//redirect to home or admin page (if user is administrator)
				} else {

					if ($loginUser->mem_profil[0] == "3") {
						headerFunction("../projects_site/home.php?".session_name()."=".session_id());
					} else if ($loginUser->mem_profil[0] == "0") {
						headerFunction("../administration/admin.php?".session_name()."=".session_id());
					} else {
						headerFunction("../general/home.php?".session_name()."=".session_id());
					}
				}
		}
	}
}

if ($session == "false" && $url == "") {
	$error = $strings["session_false"];
}

if ($logout == "true") {
	$msg = "logout";
}

if ($demoMode == "true") {
	$loginForm = "demo";
	$passwordForm = "demo";
}

$notLogged = "true";
$bodyCommand = "onLoad=\"document.loginForm.loginForm.focus();\"";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/header.php");
} else {
	include("../themes/".THEME."/header.php");
}

if ($xoopsIntegration == "true" && $demoMode != "true") {
if ($xoopsUser) {
	$loginForm = $xoopsUser->getVar("uname");
}
}

$blockPage = new block();
$blockPage->openBreadcrumbs();
$blockPage->itemBreadcrumbs("&nbsp;");
$blockPage->closeBreadcrumbs();

if ($msg != "") {
if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/includes/messages.php");
} else {
	include("../includes/messages.php");
}
	$blockPage->messagebox($msgLabel);
}

$block1 = new block();

$block1->form = "login";
$block1->openForm("../general/login.php?auth=test");

if ($url != "") {
	echo "<input value=\"$url\" type=\"hidden\" name=\"url\">";
}

if ($error != "") {            
	$block1->headingError($strings["errors"]);
	$block1->contentError($error);
}

$block1->heading("".$strings["login"]);
/*spatiallink:$block1->heading("PhpCollab : ".$strings["login"]);*/

$block1->openContent();
$block1->contentTitle($strings["please_login"]);

echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["language"]." :</td><td>
<select name=\"languageForm\">
	<option value=$langDefault>Default (".$langValue["$langDefault"].")</option>
	<option value=az>Azerbaijani</option>
	<option value=pt-br>Brazilian Portuguese</option>
	<option value=bg>Bulgarian</option>
	<option value=ca>Catalan</option>
	<option value=zh>Chinese simplified</option>
	<option value=zh-tw>Chinese traditional</option>
	<option value=cs-iso>Czech (iso)</option>
	<option value=cs-win1250>Czech (win1250)</option>
	<option value=da>Danish</option>
	<option value=nl>Dutch</option>
	<option value=en>English</option>
	<option value=et>Estonian</option>
	<option value=fr>French</option>
	<option value=de>German</option>
	<option value=hu>Hungarian</option>
	<option value=is>Icelandic</option>
	<option value=in>Indonesian</option>
	<option value=it>Italian</option>
	<option value=ko>Korean</option>
	<option value=no>Norwegian</option>
	<option value=pl>Polish</option>
	<option value=pt>Portuguese</option>
	<option value=ro>Romanian</option>
	<option value=ru>Russian</option>
	<option value=sk-win1250>Slovak (win1250)</option>
	<option value=es>Spanish</option>
	<option value=tr>Turkish</option>
	<option value=uk>Ukrainian</option>
</select>
</td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">* ".$strings["user_name"]." :</td><td><input value=\"$loginForm\" type=\"text\" name=\"loginForm\"></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">* ".$strings["password"]." :</td><td><input value=\"$passwordForm\" type=\"password\" name=\"passwordForm\"></td></tr>";

/*echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">* ".$strings["remember_password"]." :</td><td><input type=\"checkbox\" name=\"rememberForm\" value=\"on\">&nbsp;</td></tr>";*/

echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">&nbsp;</td><td><input type=\"submit\" name=\"save\" value=\"".$strings["login"]."\"><br><br><br>".$blockPage->buildLink("../general/sendpassword.php?",$strings["forgot_pwd"],in)."</td></tr>";
                      
$block1->closeContent();
$block1->closeForm();

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>