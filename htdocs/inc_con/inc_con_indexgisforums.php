<?php
if (!$MAGPIE_ERROR) {
	// require rss: require_once if multiple feeds
	require_once '/opt/bitnami/apache2/htdocs/inc_con/rss/magpierss061/rss_fetch.inc';
}
//	include scripts_fct(): TITLE OR OVERLIB. refer to rss_blog.sci_tech.php
//	URL parsing with PHP: refer to htmlentities(), urlencode(), htmlspecialchars() OR preg_replace(). also, refer to http://www.zend.com/tips/tips.php?id=251&single=1
//	EX:
//	$str = "A 'quote' is <b>bold</b>";
//	Outputs: A 'quote' is &lt;b&gt;bold&lt;/b&gt;
//	echo htmlentities($str);
//	EX:
//	<a href=[php echo htmlspecialchars($url); php]>example</a>
//	EX:
//	$title=preg_replace("/&/","&amp;",$title); // &amp;
//	print "<b>".$title."</b><br/>\n";
include '/opt/bitnami/apache2/htdocs/scripts_fct/scr_fct_cleanstring.php';
//	done
?>
	<!-- include content-->
	<!--<span> can NOT enclose <table> for XHTML, but it can enclose <span>. <span> can include its subsets, but outside of elements like <form>-->
			<table width="520" cellspacing="0" cellpadding="0" class="medium">
		<tr>
	<td class="medium_bold">
		>> Forums
	</td>
	<td class="medium_right_middle">
		<img src="/images/phpthumb/phpThumb.php?src=/images/img_forum.gif" alt="spatiallink_org" width="50" height="50" />		
	</td>
		</tr>
		<tr>
	<td colspan="2" class="medium">
		<!--google groups search: also refer to usenet and http://www.1001newsgroups.com-->
		<form method="get" action="http://groups.google.com/groups">
		<input type="text" name="q" size="53" maxlength="255" value="" />
		<input type="submit" name="btng" value="G Groups Search" />
		</form>
		<!--google groups search-->
		<br />
		&#8226;&nbsp;<a href="http://groups-beta.google.com/group/comp.databases/">comp.databases</a>::
			<?php
			//	include line
			include '/opt/bitnami/apache2/htdocs/inc/inc_line.php';
			print "<div class=\"summary\">";
				// include rss
				include '/opt/bitnami/apache2/htdocs/inc_con/rss/rss_forum.databases.php';
				// done <br />
			print "</div>";
			//	include line
			include '/opt/bitnami/apache2/htdocs/inc/inc_line.php';
			?>
		&#8226;&nbsp;<a href="http://groups-beta.google.com/group/comp.soft-sys.gis.esri/">comp.soft-sys.gis.esri</a>::
			<?php
			//	include line
			include '/opt/bitnami/apache2/htdocs/inc/inc_line.php';
			print "<div class=\"summary\">";
				// include rss
				include '/opt/bitnami/apache2/htdocs/inc_con/rss/rss_forum.gis.esri.php';
				// done <br />
			print "</div>";
			//	include line
			include '/opt/bitnami/apache2/htdocs/inc/inc_line.php';
			?>		
		<!--gml: http://groups-beta.google.com/group/computer-text-gml-->
		&#8226;&nbsp;<a href="http://groups-beta.google.com/group/comp.infosystems.gis/">comp.infosystems.gis</a>::
			<?php
			//	include line
			include '/opt/bitnami/apache2/htdocs/inc/inc_line.php';
			print "<div class=\"summary\">";
				// include rss
				include '/opt/bitnami/apache2/htdocs/inc_con/rss/rss_forum.infosystems.gis.php';
				// done <br />
			print "</div>";
			//	include line
			include '/opt/bitnami/apache2/htdocs/inc/inc_line.php';
			?>
		&#8226;&nbsp;<a href="http://groups-beta.google.com/group/comp.lang.javascript/">comp.lang.javascript</a>::
			<?php
			//	include line
			include '/opt/bitnami/apache2/htdocs/inc/inc_line.php';
			print "<div class=\"summary\">";
				// include rss
				include '/opt/bitnami/apache2/htdocs/inc_con/rss/rss_forum.comp.lang.javascript.php';
				// done <br />
			print "</div>";
			//	include line
			include '/opt/bitnami/apache2/htdocs/inc/inc_line.php';
			?>
		&#8226;&nbsp;<a href="http://groups-beta.google.com/group/comp.lang.python/">comp.lang.python</a>::
			<?php
			//	include line
			include '/opt/bitnami/apache2/htdocs/inc/inc_line.php';
			print "<div class=\"summary\">";
				// include rss
				include '/opt/bitnami/apache2/htdocs/inc_con/rss/rss_forum.comp.lang.python.php';
				// done <br />
			print "</div>";
			//	include line
			include '/opt/bitnami/apache2/htdocs/inc/inc_line.php';
			?>
		&#8226;&nbsp;<a href="http://groups-beta.google.com/group/comp.infosystems.www.authoring.stylesheets/">comp.infosystems.stylesheets</a>::
			<?php
			//	include line
			include '/opt/bitnami/apache2/htdocs/inc/inc_line.php';
			print "<div class=\"summary\">";
				// include rss
				include '/opt/bitnami/apache2/htdocs/inc_con/rss/rss_forum.comp.infosystems.stylesheets.php';
				// done <br />
			print "</div>";
			//	include line
			include '/opt/bitnami/apache2/htdocs/inc/inc_line.php';
			?>
		&#8226;&nbsp;<a href="http://groups-beta.google.com/group/comp.text.xml/">comp.text.xml</a>::
			<?php
			//	include line
			include '/opt/bitnami/apache2/htdocs/inc/inc_line.php';
			print "<div class=\"summary\">";
				// include rss
				include '/opt/bitnami/apache2/htdocs/inc_con/rss/rss_forum.comp.text.xml.php';
				// done <br />
			print "</div>";
			//	include line
			include '/opt/bitnami/apache2/htdocs/inc/inc_line.php';
			?>
		&#8226;&nbsp;<a href="http://groups-beta.google.com/group/Apache-PHP-mysql/">apache-php-mysql</a>::
			<?php
			//	include line
			include '/opt/bitnami/apache2/htdocs/inc/inc_line.php';
			print "<div class=\"summary\">";
				// include rss
				include '/opt/bitnami/apache2/htdocs/inc_con/rss/rss_forum.apache-php-mysql.php';
				// done <br />
			print "</div>";
			//	include line
			include '/opt/bitnami/apache2/htdocs/inc/inc_line.php';
			?>
		&#8226;&nbsp;<a href="http://forums.devshed.com/">devshed</a>::
			<?php
			//	include line
			include '/opt/bitnami/apache2/htdocs/inc/inc_line.php';
			print "<div class=\"summary\">";
				// include rss
				include '/opt/bitnami/apache2/htdocs/inc_con/rss/rss_forum.devshed.php';
				// done <br />
			print "</div>";
			//	include line
			include '/opt/bitnami/apache2/htdocs/inc/inc_line.php';
			?>
		&#8226;&nbsp;<a href="http://forums.mysql.com/list.php?23">forums.mysql-gis.com</a>::
			<?php
			//	include line
			include '/opt/bitnami/apache2/htdocs/inc/inc_line.php';
			print "<div class=\"summary\">";
				// include rss
				include '/opt/bitnami/apache2/htdocs/inc_con/rss/rss_forum.mysql-gis.php';
				// done <br />
			print "</div>";
			//	include line
			include '/opt/bitnami/apache2/htdocs/inc/inc_line.php';
			?>		
		&#8226;&nbsp;<a href="http://groups-beta.google.com/group/Google-Maps-API/">google-maps-api</a>::
			<?php
			//	include line
			include '/opt/bitnami/apache2/htdocs/inc/inc_line.php';
			print "<div class=\"summary\">";
				// include rss
				include '/opt/bitnami/apache2/htdocs/inc_con/rss/rss_forum.google-maps-api.php';
				// done <br />
			print "</div>";
			//	include line
			include '/opt/bitnami/apache2/htdocs/inc/inc_line.php';
			?>
		&#8226;&nbsp;<a href="http://groups-beta.google.com/group/netscape.public.mozilla.svg/">netscape.public.mozilla.svg</a>::
			<?php
			//	include line
			include '/opt/bitnami/apache2/htdocs/inc/inc_line.php';
			print "<div class=\"summary\">";
				// include rss
				include '/opt/bitnami/apache2/htdocs/inc_con/rss/rss_forum.netscape.public.mozilla.svg.php';
				// done <br />
			print "</div>";
			//	include line
			include '/opt/bitnami/apache2/htdocs/inc/inc_line.php';
			?>
		&#8226;&nbsp;<a href="http://groups-beta.google.com/group/pgsql.general/">pgsql.general</a>::
			<?php
			//	include line
			include '/opt/bitnami/apache2/htdocs/inc/inc_line.php';
			print "<div class=\"summary\">";
				// include rss
				include '/opt/bitnami/apache2/htdocs/inc_con/rss/rss_forum.pgsql.general.php';
				// done <br />
			print "</div>";
			//	include line
			include '/opt/bitnami/apache2/htdocs/inc/inc_line.php';
			?>
		&#8226;&nbsp;<a href="http://groups-beta.google.com/group/SQL-Development/">sql-development</a>::
			<?php
			//	include line
			include '/opt/bitnami/apache2/htdocs/inc/inc_line.php';
			print "<div class=\"summary\">";
				// include rss
				include '/opt/bitnami/apache2/htdocs/inc_con/rss/rss_forum.sql-development.php';
				// done <br />
			print "</div>";
			//	include line
			include '/opt/bitnami/apache2/htdocs/inc/inc_line.php';
			?>
		<!--note: <br /> included in rss-->	
		<br />
		&#8226;&nbsp;<a href="http://mapserver.gis.umn.edu/support.html">MapServer</a>::
		<br />
		<br />
		&#8226;&nbsp;<a href="http://lists.maptools.org/">Maptools</a>::
		<br />
		<br />
		&#8226;&nbsp;<a href="http://lists.directionsmag.com/">Directionsmag</a>::
		<br />
		<br />
	</td>
		</tr>
			</table>
	<!-- include content-->