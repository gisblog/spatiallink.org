	<!--footer-->
	<?php
	//	include XHTML break all: NA if NOT sending header()
	//	include '/var/chroot/home/content/57/3881957/html/inc/inc_xhtmlbreakall.php';
	?>
			<table width="760" cellspacing="0" cellpadding="0">
		<tr>
	<td width="760" bgcolor="#c2c0c0" colspan="3" class="small">
		<?php
		// include footernavigate
		include '/var/chroot/home/content/57/3881957/html/inc/inc_footernavigate.php';
		?>
		<!--place <hr> outside <span> for XHTML-->
		copyright &copy; <?php print $varyear; ?> spatiallink_org. all rights reserved
		 |
		<?php
		// require log
		require '/var/chroot/home/content/57/3881957/html/inc/inc_visitor_log.php';
		// close/free before clocking microtime(): free only if SELECT
		require '/var/chroot/home/content/57/3881957/html/inc/inc_db_close.php';
		/* script time: 1 microsecond is a millionth of 1 second- OR substr($mtimeend - $mtimestart,0,4) OR number_format($num, 10, '.', '') */
		$mtimeend = microtime(true);
		$mtimedifference = round(abs($mtimeend - $mtimestart),2);
		//	print "script download time:: approx $varapproxdownloadtime seconds [56K modem] | ";
		print "script execution time:: approx $mtimedifference seconds";
		?>
	</td>
		</tr>
			</table>
	<!--footer-->
</body>
	</html>