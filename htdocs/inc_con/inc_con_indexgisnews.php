	<!-- include content -->
	<!-- <span> can NOT enclose <table> for XHTML, but it can enclose <span>. <span> can include its subsets, but outside of elements like <form> -->
			<table width="520" cellspacing="0" cellpadding="0" class="medium">
		<tr>
	<td class="medium_bold">
		<?php
		// include new
		//	include '/opt/bitnami/apache2/htdocs/inc/inc_new.php';
		//	include store
		// include '/opt/bitnami/apache2/htdocs/gistools/store/store_google_content.php';
		/*
		<style type="text/css">
		<!--
			div.grab {width: 520px; height: 50px; overflow: scroll;}
		-->
		</style>
		$url = "http://www.weather.com/weather/local/22903";
		$handle = fopen($url, "r");
		$contents = file_get_contents($url);
		<div id="grab" class="grab">
			print $contents;
			fclose($handle);
		</div>
		*/
		// include alert: hurricane x
		// <span class="medium_red">
		// 	Looking to volunteer for Darfur relief efforts? <a href="/gistools/volunteer/gisvolunteer_profile.php">Submit profile</a> or <a href="/gistools/volunteer/gisvolunteer_search.php">search, map and contact</a> professionals and volunteers
		// </span>
		// <br />
		?>
		>> News::
		<a href="http://www.spatiallink.org/gistools/discuss/pubforum/forum/login.php?redirect=posting.php&mode=newtopic&f=1">Log-in</a> or <a href="http://www.spatiallink.org/gistools/discuss/pubforum/forum/profile.php?mode=register">register</a> to post your news!
	</td>
	<td class="medium_right_middle">
		<img src="/images/phpthumb/phpThumb.php?src=/images/img_news.gif" alt="spatiallink_org" width="50" height="50" />
	</td>
		</tr>
		<tr>
	<td colspan="2" class="medium">
		<!-- google news search -->
		<form method="get" action="http://news.google.com/news">
		<input type="text" name="q" size="55" maxlength="255" value="" />
		<input type="submit" name="btng" value="G News Search" />
		</form>
		<!-- google news search -->
		<!-- google news archive search -->
		<form method="get" action="http://news.google.com/archivesearch">
		<input type="text" name="q" size="55" maxlength="255" value="" />
		<input type="submit" name="btng" value="G News Archive Search" />
		</form>
		<!-- google news archive search -->
		<br />
		&#8226;&nbsp;<a href="/gistools/discuss/news/archives/">Archives</a>::
		<br />
		<br />
		<?php
		// include scr_fct_sort:
		include '/opt/bitnami/apache2/htdocs/scripts_fct/scr_fct_sort.php';
		?>
		&#8226;&nbsp;Spatial::
		<div class="summary_visible">
			<?php
			// fct_sort3(): <table><thead></thead><tbody>
			fct_sort3('sort_spatial', 'Date', 'Title', 'Name');
			// include rss_news.spatial:
			include '/opt/bitnami/apache2/htdocs/inc_con/rss/rss_news.spatial.php';
			?>
				</tbody>
					</table>
		</div>
		<!-- note: <br /> included in rss -->
		<br />
		&#8226;&nbsp;Science &amp; Technology::
		<div class="summary_visible">
			<?php
			// fct_sort3(): <table><thead></thead><tbody>
			fct_sort3('sort_scitech', 'Date', 'Title', 'Name');
			// include rss_news.sci_tech:
				include '/opt/bitnami/apache2/htdocs/inc_con/rss/rss_news.sci_tech.php';
			?>
				</tbody>
					</table>
		</div>
		<!-- note: <br /> included in rss -->
		<br />
	</td>
		</tr>
			</table>	
	<!-- include content -->