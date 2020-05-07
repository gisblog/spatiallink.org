	<!-- include content-->
	<!--<span> can NOT enclose <table> for XHTML, but it can enclose <span>. <span> can include its subsets, but outside of elements like <form>-->
			<table width="520" cellspacing="0" cellpadding="0" class="medium">
		<tr>
	<td class="medium_bold">
		>> Blogs
	</td>
	<td class="medium_right_middle">
		<img src="/images/phpthumb/phpThumb.php?src=/images/img_blog.gif" alt="spatiallink_org" width="50" height="50" />
	</td>
		</tr>
		<tr>
	<td colspan="2" class="medium">
		&#8226;&nbsp;Archives::
		<div class="summary_visible">
		<?php
		// include archives
		include '/opt/bitnami/apache2/htdocs/inc_con/inc_con_index_archives.php';
		?>	
		</div>
		<br />
	</td>
		</tr>
			</table>
	<!-- include content-->