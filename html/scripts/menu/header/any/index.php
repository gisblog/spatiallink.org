<script src="/scripts/menu/header/any/any.js"></script>
<?php
//	include script: http://www.webmasterworld.com/forum91/121.htm
//	include_once noscript
include_once("/var/chroot/home/content/57/3881957/html/inc/inc_noscript.php");
?>
			<table class="anytable">
		<tr>		
	<!--0 anchor link and menu-->
	<td width="152" class="anytd">
		<span class="whitetext">
		<!--LINKED-->
		<a href="/about.php">About</a>
		</span>
	</td>
	
	<!--1st anchor link and menu-->
	<td width="152" class="anytd">
		<span class="whitetext">
		<!--LINKED-->
		<a href="/partners.php">Partners</a>
		</span>
	</td>
	
	<!--2nd anchor link and menu-->
	<td width="152" class="anytd">
		<span class="whitetext">
		<!--UNlinked-->
		<a onClick="return clickreturnvalue()" onMouseover="dropdownmenu(this, event, 'anymenu1')">Discuss</a>
		</span>
		<div id="anymenu1" style="width:152px;" class="anycss">
			<a href="/gistools/discuss/weblogs/">Blogs</a>
			<a href="/gistools/discuss/pubchat/chat/">Chat</a>
			<a href="/gistools/discuss/pubforum/">Forums</a>
			<a href="/gistools/discuss/news/">News</a>
			<a href="/gistools/discuss/wap/">WAP</a>
			<a href="/gistools/discuss/wiki/wiki.php">WIKI</a>
			<a href="/gistools/discuss/">More...</a>
		</div>
	</td>
	
	<!--3rd anchor link and menu-->
	<td width="152" class="anytd">
		<span class="whitetext">
		<!--UNlinked-->
		<a onClick="return clickreturnvalue()" onMouseover="dropdownmenu(this, event, 'anymenu2')">Manage</a>
		</span>                                                    
		<div id="anymenu2" style="width:152px;" class="anycss">
			<a href="/gistools/manage/sa1/">CRM</a>
			<a href="/gistools/manage/pa1/">WMS</a>
			<a href="/gistools/manage/">More...</a>
		</div>
	</td>
	
	<!--4th anchor link and menu-->
	<td width="152" class="anytd">
		<span class="faintredtext">
		<!--LINKED-->
		<?php
		/* <a href="/plus/">Plus</a> */
		?>
		<a href="">Plus</a>
		</span>
	</td>	
		</tr>
			</table>