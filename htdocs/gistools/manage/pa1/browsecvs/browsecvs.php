<?php
#Application name: PhpCollab
#Status page: 0
#Path by root: ../browsecvs/browsecvs.php

//TY
$checkSession = "true";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	$postnukeIntegration = "true";
	include("modules/PhpCollab/includes/library.php");
} else {
	$postnukeIntegration = "false";
	include("../includes/library.php");
}

if ($profilSession < 0 || $profilSession > 2) {
	headerFunction("../general/permissiondenied.php?".session_name()."=".session_id());
	exit;
}

if ($enable_cvs == "true") {
if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/includes/cvslib.php");
} else {
	include("../includes/cvslib.php");
}
}

$conf['images'] = array('.bmp','.jpg','.jpeg','.gif','.png','.wbm','.psd','.psp');
$conf['scripts'] = array('.php','.phps','.php3','.php4','.pl','.sh');

$conf['bin']['co'] = $cvs_co;
$conf['bin']['rlog'] = $cvs_rlog;
$conf['bin']['cvs'] = $cvs_cmd;

$conf['cvsrep'] = array('rep' => $cvs_root.'/'.$id.'/');
$conf['cvsreps'] = array('rep');
$conf['defaultcvsrep'] = 'rep';

$tmpquery = "WHERE pro.id = '$id'";
$projectDetail = new request();
$projectDetail->openProjects($tmpquery);

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/header.php");
} else {
	include("../themes/".THEME."/header.php");
}

$modulehref = basename($PHP_SELF)."?id=$id";
$moduledir = (dirname($PHP_SELF) == "/") ? "" : dirname($PHP_SELF);

function listdirs($folder,$hide=array(".","..")) {
    $retval = array();
    $handle = opendir($folder);
    while ($file=readdir($handle)) {
	if (is_dir($folder.$file)&&(!in_array($file,$hide))) {
	    $retval[] = $file;
	}
    }
    closedir($handle);
    return $retval;
}

function listfiles($folder) {
    $retval = array();
    $handle = opendir($folder);
    while ($file=readdir($handle)) {
	if (is_file($folder.$file)) {
	    $retval[] = $file;
	}
    }
    closedir($handle);
    return $retval;
}


function drawtop($listing,$dir=''){
    global $rgb_top,$rgb_sub,$theme,$modulehref,$moduledir,$color;
    $elements=count($listing);
    $color = ($color == $rgb_top) ? $rgb_sub : $rgb_top;
    if (ereg("/",$dir)) {
	$dirlist = split("/",$dir);
	$dirs = count($dirlist);
	for ($i=0; $i<$dirs-2; $i++) {
	    $link .= $dirlist[$i]."/";
	}
	echo $theme->opentable("100%",0,0,$color,$color);
	echo '<img align="absmiddle" src="'.$moduledir.'/images/back.gif" border="0" alt=""> ';
	echo '<a href="'.$modulehref.'&dir='.urlencode($link).'">';
	echo "Up one level";
	echo '</a>';

	echo $theme->closetable();
    }
    for ($i=0; $i<$elements; $i++) {
	$color = ($color == $rgb_top) ? $rgb_sub : $rgb_top;
	echo $theme->opentable("100%",0,0,$color,$color);
	echo '<img align="absmiddle" src="'.$moduledir.'/images/dir.gif" border="0" alt=""> ';
	echo '<a href="'.$modulehref.'&dir='.urlencode($dir.$listing[$i]."/").'">';
	echo $listing[$i];
	echo '</a>';
	echo $theme->closetable();
    }
}

function drawbottom($listing,$dir='',$path){
    global $rgb_off,$rgb_sub,$theme,$color;
    global $modulehref,$moduledir,$textutil;
    global $browser, $cvsrep;
    $elements=count($listing);
    for ($i=0; $i<$elements; $i++) {
	$rev = $browser->getRevision($path.$listing[$i]);
	$file = str_replace(",v","",$listing[$i]);
	$color = ($color == $rgb_sub) ? $rgb_off : $rgb_sub;
	echo $theme->opentable("100%",0,0,$color,$color);
	echo '<img align="absmiddle" src="'.$moduledir.'/images/file.gif" border="0" alt=""> ';
	echo '<a href="'.$modulehref.'&cvsrep='.urlencode($cvsrep).'&dir='.urlencode($dir).'&file='.urlencode($file).'">';
	echo $file;
	echo '</a>';
	echo " <b>(</b>";
	echo "Last modified by <b>".htmlspecialchars($rev['auth'])."</b> ".$browser->timetoreadable($rev['date'])." ago, revision ".$rev['rev'];
	echo "<b>)</b>";
	echo '</td><td width="40%">';
	echo "<b>Message:</b> ".htmlspecialchars($rev['comment']);
	echo $theme->closetable();
    }
}

function filetotal($files,$path){
    global $textutil;
    $total = count($files);
    $retval=0;
    for ($i=0; $i<$total; $i++) {
	$retval += filesize($path.$files[$i]);
    }
    return $textutil->formatsize($retval);
}

function drawstats($files,$dirs,$path){
    global $rgb_off,$rgb_sub,$theme,$color;
    if (count($dirs)||count($files)) {
	$color = ($color == $rgb_sub) ? $rgb_off : $rgb_sub;
	echo $theme->opentable("100%",0,0,$color,$color);
	echo '<center>... ';
	if (count($dirs)) {
	    echo '<b>'.count($dirs).'</b> dirs';
	    if (count($files)) echo ' ... ';
	}
	if (count($files)) {
	    echo '<b>'.count($files).'</b> files ';
	    echo '(<b>'.filetotal($files,$path).'</b>)';
	}
	echo ' ...</center>';
	echo $theme->closetable();
    }
}

function checkout($file,$filename){
    global $browser,$dir,$cvsrep,$rev,$modulehref,$theme;
    if (is_file ($file.",v")) {
	if (!isset($rev)) {
	    $info = $browser->getRevisionTree($file);
	    foreach ($info['log'] as $rev => $i) {
		echo $theme->opentable();
		echo 'Rev: <a href="'.$modulehref;
		echo '&dir='.urlencode($dir);
		echo '&file='.urlencode($filename);
		echo '&rev='.urlencode($rev);
		echo '&cvsrep='.urlencode($cvsrep);
		echo '">'.$rev.'</a> ';
		echo htmlspecialchars($i['date']." (". $browser->timetoreadable($i['date']) . " ago) ");
		echo "by <b>". htmlspecialchars($i['auth']). "</b><br>\n";
		if (isset($i['lines'])&&($i['lines']!="+0 -0")) {
		    echo sprintf("Changed: <b>%s</b> lines", $i['lines'])."<br>\n";
		}
		echo '<hr noshade size="1">';
		echo "\n".nl2br(htmlspecialchars($i['comment']))."\n";
		echo $theme->closetable()."<br>\n";
	    }
	    echo count($info['log']) . " revision" . ((count($info['log'])>1) ? "s":"");
	    echo " sofar.";
	} else {
	    echo "<hr noshade>\n";
	    echo $theme->opentable();
	    echo "Current file: ".htmlspecialchars($cvsrep)."<b> :: </b>".htmlspecialchars($dir.$filename);
	    echo "<br>\n";
	    $info = $browser->getRevision($file, $rev);
	    echo "Revision: <b>".$info['rev']."</b> ";
	    echo htmlspecialchars("(". $browser->timetoreadable($info['date'],true). " ago) by ");
	    echo "<b>".htmlspecialchars($info['auth']). "</b><br>\n";
	    if (isset($info['lines'])&&($info['lines']!="+0 -0")) {
		echo sprintf("Changed: <b>%s</b> lines", $info['lines'])."\n";
	    }
	    echo "<br>\n";
	    echo nl2br(htmlspecialchars($info['comment']));
	    echo $theme->closetable();
	    echo "<hr noshade>\n";
	    $browser->doCheckout($cvsrep, $dir.$filename, $rev);
	}
    }
} 


include("textutil.php");
include("browsecvs.class.php");

if (!isset($cvsrep) || $cvsrep == '') $cvsrep = urlencode($conf['defaultcvsrep']);
$cvsrep = urldecode($cvsrep);

$dir = urldecode($dir);
if (eregi("\/\.\.",$dir)||($dir=="..")||eregi("\.\.\/",$dir)) $dir="";
$path = $conf['cvsrep'][$cvsrep].$dir;

if (isset($file)){
    $file = urldecode($file);
//    $file = escapeshellarg($file);
    if (eregi("\/\.\.",$file)||($file=="..")||eregi("\.\.\/",$file)) $file="";
}

$blockPage = new block();
$blockPage->openBreadcrumbs();
$blockPage->itemBreadcrumbs("<a href=\"../projects/listprojects.php?$sid\">".$strings["projects"]."</a>");
$blockPage->itemBreadcrumbs("<a href=\"../projects/viewproject.php?$sid&id=$id\">".$projectDetail->pro_name[0]."</a>");
$blockPage->itemBreadcrumbs($strings["repository"]);
$blockPage->closeBreadcrumbs();

$block1 = new block();

$block1->heading($strings["browse_cvs"]." : ".$projectDetail->pro_name[0]);

$block1->openContent();
$block1->contentTitle("Files");

echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">&nbsp;</td><td>";

if ($cvs_protected) {
  $cvs_access = has_access($loginSession, $id);
} else {
  $cvs_access = true;
}

if ($cvs_access) {

    echo "<!-- Start browsecvs -->";
    
    include("theme.php");
    
    //echo $theme->header();
    
    $rgb_top = $theme->bgcolor3;
    $rgb_sub = $theme->bgcolor2;
    $rgb_off = $theme->bgcolor1;
    
    $dirs = listdirs($path,array(".","..","CVSROOT","Attic"));
    $files = listfiles($path); 
    sort($dirs);
    sort($files);
    
    echo $theme->opentable();
    
    if (isset($file)&&($file!="")){
	checkout($path.$file,$file);
    } else {
	if (count($conf['cvsreps'])>1){
	    echo $htmlform->start($modulehref);
	    for ($i=0; $i<count($conf['cvsreps']); $i++)
	      $data[] = array(urlencode($conf['cvsreps'][$i]),htmlspecialchars($conf['cvsreps'][$i]));
	    echo $htmlform->selectlist("cvsrep",$data,urlencode($cvsrep));
	    echo $htmlform->input("submit"," Go ","submit");
	    echo $htmlform->stop();
	    echo "</center>";
	}
	drawtop($dirs,$dir);
	drawbottom($files,$dir,$path);
	drawstats($files,$dirs,$path);
    }
    
    echo $theme->closetable();
    //echo $theme->footer();
    
    echo "<!-- End browsecvs -->";
}

echo "&nbsp;</td></tr>";

$block1->closeContent();

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>