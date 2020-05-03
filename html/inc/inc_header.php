<body>
	<!--header-->
	<?php
	/*
	include_once("inc_noscript.php"):
	FROM /menu/header/any/index.php FOR menu
	FROM inc_popup_string.php FOR overlib
	FROM inc_checkjs_clean.php-->exit; return; die; FOR form
	*/
	// impose condition: NA
	// if ($varpage != 'http://www.spatiallink.org/' && $varpage != 'http://spatiallink.org/')
	// include: even though using this ONLY on selected pages
	include '/var/chroot/home/content/57/3881957/html/inc/inc_popup_string.php';
	?>
				<table width="760" cellspacing="0" cellpadding="0">
			<tr>
		<td bgcolor="#c2c0c0" class="medium">
			<a name="top">
			<!--headersimple: <noscript>
			<span class="header">spatiallink</span><span class="header_red">_</span><span class="header">org</span>
			headersimple-->
			<?php
			// include headerflash:
			// include '/var/chroot/home/content/57/3881957/html/mmx/headerflash.php';
			?>
			<img src="/images/img-spatiallink-heartbeat-high.jpg" alt="spatiallink_org">
			</a>
			<span class="small">
			<?php
			// add to favorites:
			$favoriteurl = "http://www.spatiallink.org/";
			$favoritetitle = "spatiallink_org";
			if (stristr($varbrowser, "MSIE")) {
				?><script src="/scripts/scr_addtofavorites.js"></script><?php
				print "<a href=\"javascript:addtofavorites()\">[+]</a>";
			} else {
				print "";
			}
			?>
			</span>
		</td>
		<td bgcolor="#c2c0c0" class="medium_right_middle">
			<?php
			// include search:
			include '/var/chroot/home/content/57/3881957/html/inc_con/inc_con_search.php';
			?>
			<a href="/">Home</a>
			 |
			<a href="mailto:website@spatiallink.org?subject=spatiallink_org">Contact</a>
			 |
			<a href="https://email.secureserver.net/login.php?domain=email.spatiallink.org">Login</a>
			 |
			<?php
			// include scripts:
			?><script src="/scripts/scr_popup_tos.js"></script><?php
			?>
			<a href="javascript:popup_tos()">ToS</a>
			<?php
			/*
			translation:
			<iframe src="http://www.appliedlanguage.com/translate.aspx?sourcel=en&iindex=3&col=&id=d3d3LnNwYXRpYWxsaW5rLm9yZw==" title="" scrolling="no" frameborder="0" marginheight="0" marginwidth="0" style="width:500px;height:30px;">
			</iframe>
			English%2FGerman
			English%2FItalian
			English%2FDutch
			English%2FPortuguese
			English%2FNorwegian
			print "http://".$_SERVER['HTTP_HOST']."/";
			*/
			// print '[<a href="'.htmlentities("http://64.233.179.104/translate_c?hl=en&langpair=en%7Ces&u=$varpage").'">Spanish</a> | <a href="'.htmlentities("http://64.233.179.104/translate_c?hl=en&langpair=en%7Cfr&u=$varpage").'">French</a>]';
			?>
		</td>
		<td bgcolor="#c2c0c0">
			&nbsp;
			<?php
			// include store:
			include '/var/chroot/home/content/57/3881957/html/gistools/store/store_google_header.php';
			?>
		</td>
			</tr>
			<tr>
		<td colspan="3">
			<?php
			// include menu_header: any. note that nowrap="nowrap" is deprecated. also, bear in mind that a DHTML menu floats over <span> and therefore covers part/all of the content inserted there.
			include '/var/chroot/home/content/57/3881957/html/scripts/menu/header/any/index.php';
			?>
		</td>
			</tr>
				</table>
	<?php
	// include XHTML break all: NA if NOT sending header()
	// include '/var/chroot/home/content/57/3881957/html/inc/inc_xhtmlbreakall.php';
	?>
	<!--header-->