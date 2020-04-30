<!--include content-->
<?php
// include self
if ($_SERVER['HTTP_REFERER'] == "http://www.spatiallink.org/gistools/volunteer/gisvolunteer_search.php") {
	require '/var/chroot/home/content/57/3881957/html/inc_con/inc_con_gisvolunteer_searched.php';
// done
} else {
	?>
			<table width="760" border="0" align="left" cellspacing="0" cellpadding="0" bgcolor="#C2C0C0">
	<?php
	/*	include form */
	?>
	<form method="post" name="gisvolunteer" id="gisvolunteer" action="
	<?php
	print $varfilename;
	?>
	" onsubmit="return checkgisvolunteer_search()">
	<?php
	/*	done */
	/*	include form scripts */
	// note: load onsubmit script at the end
	?><script src="/scripts/scr_zipcode.js"></script><?php
	?><script src="/scripts/scr_number.js"></script><?php
	?><script src="/scripts_xhr/scr_xhr_gethttpobject.js"></script><?php
	?><script src="/scripts_xhr/scr_xhr_gisvolunteer_updatepsll.js"></script><?php
	//
	?><script src="/scripts/scr_string.js"></script><?php
	?><script src="/scripts/scr_email.js"></script><?php
	?><script src="/scripts/scr_stringcomplex_a.js"></script><?php
	?><script src="/scripts/scr_stringcomplex_c.js"></script><?php
	//
	?><script src="/scripts/scr_gisvolunteer_search.js"></script><?php
	/*	done */
	?>
		<tr>
	<td class="medium_bold">
	<?php
	//	note: do NOT include imgmenu since it reloads this page on submission
	?>
	<span class="medium_bold">
	>> Search, Map and Contact
	</span>
	</td>
		</tr>

		<tr>
	<td class="medium">
	<br />
	<br />
	<!--include cell table: layout. class needs to be defined for every cell table or else IE will NOT render correctly-->
				<table width="755" border="0" align="left" cellspacing="0" cellpadding="0" bgcolor="#C2C0C0">
			<tr>
		<td class="medium">		
		<?php
		// include cell table
		include '/var/chroot/home/content/57/3881957/html/inc_con/inc_con_gisvolunteer_searchtable.php';
		?>
		</td>
		<td class="medium">
		<img src="/images/img_volunteer_search.gif" alt="spatiallink_org" style="width:375;height:200;" align="left" />
		</td>
			</tr>			
				</table>
	<!--include cell table: layout-->
	</td>
		</tr>
			
		<tr>
	<td class="medium">
	Keywords
	<br />
	<input type="text" name="searchkeywords" value="" size="106" maxlength="106" />
	</td>
		</tr>
			
		<tr>
	<td class="medium">
	<br />
	<input type="hidden" name="javascript" value="" />
	<input type="submit" value="Submit Once" />
	<input type="reset" />
	<br />
	<?php
	include '/var/chroot/home/content/57/3881957/html/inc_con/inc_con_gisvolunteer_disclaimer.php';
	?>
	<br />
	<br />
	</td>
		</tr>
	</form>
			</table>
	<?php
// done
}
//	include XHTML break all: NA if NOT sending header()
include '/var/chroot/home/content/57/3881957/html/inc/inc_xhtmlbreakall.php';
?>
<!--include content-->