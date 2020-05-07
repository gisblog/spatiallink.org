<?php
$PHP_SELF=$_SERVER['PHP_SELF'];
// include config.php
include "config.php";
// done
require_once("$MAGPIE_LOCATION/rss_fetch.inc");

/* headerless:
header("Content-type: text/vnd.wap.wml");
// Prevent caching, HTTP/1.1
header("Cache-Control: no-cache, must-revalidate");
header("Connection: close");

echo "<?xml version=\"1.0\"?>";  
echo "<!DOCTYPE wml PUBLIC \"-//WAPFORUM//DTD WML 1.1//EN\""  
   . " \"http://www.wapforum.org/DTD/wml_1.1.xml\">\n"; 
done */
   
$uid=$_GET[u]==""?0:$_GET[u];
$card=$_GET[c]<1?1:$_GET[c];
$mode=$_GET[m]==""?0:$_GET[m];
$url=$user_urls[$uid];
$rss=fetch_rss($url);
?>
<!--**headerless
<wml>
  <head>
    <meta http-equiv="Cache-Control" content="max-age=0" forua="true"/>
  </head>
**-->

<?php
print "<card id=\"blog\" title=\"".$rss->channel[title]."\">\n";
print "<p>\n";

switch($mode){
	case 0:
		for($i=0;$i<$MAX_ITEMS;$i++){
			$n=$i+1;
			$item=$rss->items[$i];

			$time=($item[pubdate]=="")?$item[dc][date]:$item[pubdate];
			$title=($item[title]=="")?$time:$item[title];
			$title=($title=="")?"[no title]":$title;
		
			/* OLD print "&bull;&nbsp;<a href=\"$PHP_SELF?u=$uid&amp;m=1&amp;c=$n\">$title</a><br/>\n";
				
				NEW to get around & in link or title ("AT&T"), like so: .htmlentities($item['link']).
				$htmlentitieslink=htmlentities('$PHP_SELF?u=$uid&amp;m=1&amp;c=$n');
				$htmlentitiestitle=htmlentities('$title=($title=="")?"[no title]":$title'); 
				
				less preferrable: htmlspecialchars() OR preg_replace()
				*/
			$htmlentitiestitle=htmlentities($title);			
			print "&bull;&nbsp;<a href=\"$PHP_SELF?u=$uid&amp;m=1&amp;c=$n\">$htmlentitiestitle</a><br/>\n";
		}
		break;

	case 1:
if($card<1 || $card>$MAX_ITEMS) { print "invalid card #</p></card></wml>"; exit(1); }

$prev=$card-1;
$next=$card+1;

$item=$rss->items[$card-1];

$time=($item[pubdate]=="")?$item[dc][date]:$item[pubdate];
$title=($item[title]=="")?"[no title]":$item[title];

/* $htmlentities() $htmlspecialchars() OR preg_replace() */
$title=preg_replace("/&/","&amp;",$title); /* &amp; */
print "<b>".$title."</b><br/>\n";

print "posted: $time<br/>\n";
print "-----------<br/>\n";

// massaging to work with browsers
$content=strip_tags($content,"<a><b><img><em><i><br>");
$content=($item[content][encoded]=="")?$item[description]:$item[content][encoded];
$content=preg_replace("/<img.*>/U","[image] ",$content);
// the next line needs work, but the idea is to redirect links to the google wml proxy
//$content=preg_replace("/<\s*a\s+href\s*=\s*\"http:\/\/(.*)\"\s*>/U","<a href=\"http://wmlproxy.google.com/wmltrans/h=en/g=/q=test/s=0/u=$1@2F/c=0\">",$content);
$content=preg_replace("/\\$/","$$",$content);
$content=preg_replace("/<\s*a\s*\/s*>/U","<b> ",$content);
$content=preg_replace("/<a\s+href.*>/U"," [link]<b>",$content);
$content=preg_replace("/<\s*\/a\s*>/U","</b>  ",$content);
$content=preg_replace("/<\s*span.*>/U","",$content);
$content=preg_replace("/<\s*\/span\s*>/U","",$content);
$content=preg_replace("/<p>/","",$content);
$content=preg_replace("/<\/p>/","",$content);
$content=preg_replace("/[\n\t]>/","",$content);
$content=preg_replace("/\"/","''",$content);
#$content=preg_replace("/\(/","[",$content);
#$content=preg_replace("/\)/","]",$content);
$content=preg_replace("/&#822[01];/","\"",$content); // double quotes
$content=preg_replace("/&#8230;/","...",$content); // ellipse
$content=preg_replace("/&#821[67];/","'",$content); // backtick?
$content=preg_replace("/\n/","<br/>",$content);

$content=preg_replace("/&/","&amp;",$content); /* &amp; */

print "\n$content\n<br/>\n";

print "-----------<br/>\n";

if($card>1){
	print "&lt;&lt;<a href=\"$PHP_SELF?u=$uid&amp;m=1&amp;c=$prev\">[Newer]</a>\n";
}
if($card<$MAX_ITEMS){
	print " ";
	print "<a href=\"$PHP_SELF?u=$uid&amp;m=1&amp;c=$next\">[Older]</a>&gt;&gt;<br/>\n";
}

	if(preg_match("/^http:\/\/(.+)$/U",$item[link])){
    	preg_match("/^http:\/\/(.+)$/",$item[link],$matches);
      	$url=urlencode($matches[1]); 		
        // subterfuge to get a google wmlproxy url
        $codedurl = str_replace("%", "@", $url);
        $googleurl = "http://wmlproxy.google.com/wmltrans/h=en/u=" . $codedurl . "/c=0";
        print "<a href=\"$googleurl\">[Full Post]</a>\n";

	}

		break;
}
	
print "</p>\n</card>\n";
// done
?>