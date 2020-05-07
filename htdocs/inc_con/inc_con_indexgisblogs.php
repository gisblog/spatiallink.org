	<!-- include content-->
	<!-- <span> can NOT enclose <table> for XHTML, but it can enclose <span>. <span> can include its subsets, but outside of elements like <form> -->
			<table width="520" cellspacing="0" cellpadding="0" class="medium">
		<tr>
	<td class="medium_bold">
		>> Blogs
		<br />
		<img src="/images/phpthumb/phpThumb.php?src=/images/img_cyber-bullying.gif" alt="spatiallink_org" width="94" height="29" />
	</td>
	<td class="medium_right_middle">
		<img src="/images/phpthumb/phpThumb.php?src=/images/img_blog.gif" alt="spatiallink_org" width="50" height="50" />
	</td>
		</tr>
		<tr>
	<td colspan="2" class="medium">
		<!-- google blog search -->
		<form method="get" action="http://blogsearch.google.com/blogsearch">
		<input type="text" name="q" size="55" maxlength="255" value="" />
		<input type="submit" name="btng" value="G Blog Search" />
		</form>
		<!-- google blog search -->
		<br />
		&#8226;&nbsp;<a href="/gistools/discuss/weblogs/archives/">Archives</a>::<a href="http://www.placeblogger.com/location/directory">Find a place blog</a>::
		<br />
		<br />
		<?php
		// include scr_fct_sort: scr_sort.js in header
		include '/opt/bitnami/apache2/htdocs/scripts_fct/scr_fct_sort.php';
		?>
		&#8226;&nbsp;Spatial::
		<div class="summary_visible">
			<?php
			// fct_sort3(): <table><thead></thead><tbody>
			fct_sort3('sort_spatial', 'Date', 'Title', 'Name');
			// include rss_blog.spatial:
			include '/opt/bitnami/apache2/htdocs/inc_con/rss/rss_blog.spatial.php';
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
			// include rss_blog.sci_tech:
				include '/opt/bitnami/apache2/htdocs/inc_con/rss/rss_blog.sci_tech.php';
			?>
				</tbody>
					</table>
		</div>
		<!-- note: <br /> included in rss -->
		<br />
		&#8226;&nbsp;Data::
		<div class="summary_visible">
			<?php
			// fct_sort3(): <table><thead></thead><tbody>
			fct_sort3('sort_data', 'Date', 'Title', 'Name');
			// include rss_blog.data
			include '/opt/bitnami/apache2/htdocs/inc_con/rss/rss_blog.data.php';
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