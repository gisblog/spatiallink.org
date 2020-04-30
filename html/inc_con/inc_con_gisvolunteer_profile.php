<!--include content-->
<?php
// include self
if ($_SERVER['HTTP_REFERER'] == "http://www.spatiallink.org/gistools/volunteer/gisvolunteer_profile.php") {
	require '/var/chroot/home/content/57/3881957/html/inc_con/inc_con_gisvolunteer_profiled.php';
	// done
} else {
	?>
			<table width="760" border="0" align="left" cellspacing="0" cellpadding="0" bgcolor="#C2C0C0">
	<?php
	/*	include form */
	//	it seems that there are only two ways to get pages fully XML/XHTML validated when using forms together with tables. The first "acceptable" way is to place the whole form entirely within a single td cell of the table. The second way is to put the table entirely within the form as follows: the initial <form> tag is placed before the <table> tag, then every form <input> (incuding hidden inputs) must be placed within a table cell, and then the </table> and </form> must be closed in that order.
	//
	//	giant table/td: form>>table>>cell table>>span>>cell_span OR table/td>>form for xhtml. but form>>table causes whitespace in IE. and giant table/td to contain form causes whitespace in FF/MZ. hence, ignoring xhtml.
	//
	//	also, remember that xhtml is all lower-case.
	?>
	<form method="post" id="gisvolunteer" name="gisvolunteer" action="
	<?php
	print $varfilename;
	?>
	" onsubmit="return checkgisvolunteer_profile()">
	<?php
	/*	done */
	/*	include form scripts */
	/*	multiple js () can be executed on submission by nestling them inside a parent (), like so:
		function parent()
		{
			return DoSubmit();
			return false;
		}	*/
	//	http://www.w3schools.com/js/js_obj_string.asp
	//	mysql acceptable characters: http://dev.mysql.com/doc/mysql/en/string-syntax.html
	//	it may not be possible to include PHP withIN javascript, but if there are only a few radio buttons, they can each be given unique names and then possibly checked for emptiness (but not blanks). they can also be given names in the format NAME# for a loop through all the NAME#s. note that this name canNOT have ".". radio buttons can also be checked with PHP's $_POST[]. refer to [function getSelectedRadio()] at http://www.breakingpar.com/bkp/home.nsf/0/CA99375CC06FB52687256AFB0013E5E9. also, refer to [&&]/[||] at http://www.w3schools.com/js/js_operators.asp and http://www.pageresource.com/jscript/jifelse.htm.
	//	enable js: IE- From the Tools menu select Internet Options. Select the Security tab, and then select the Custom Level button. Scroll down until you see Scripting options. Make sure the Enable radio button under Active scripting is selected. Click OK to save changes. Netscape- 1. From the browser's menu bar, select Edit. 2. Click Preferences. 3. Click Advanced. 4. Click the Enable JavaScript box to select JavaScript. Note- If the box has a checkmark, JavaScript is already enabled. Complete step 5 to exit. 5. Click OK. Firefox-.
	//	trends: <input type="email" name="addr">; <input type="date" name="start">; <input type="number" required="required" name="qty">; <input type="time" min="09:00" max="17:00" name="mt">; <input type="uri" name="hp" required="required" oninvalid="alert('You must enter a valid home page address.'); return false;">. Refer to http://whatwg.org/specs/web-forms/current-work/#introduction0
	//	xmlhttprequest:
	//	http://www.webpasties.com/xmlHttpRequest/xmlHttpRequest_tutorial_1.html
	//	http://jpspan.sourceforge.net/wiki/doku.php?id=javascript:xmlhttprequest
	//	http://www.xml.com/pub/a/2005/02/09/xml-http-request.html
	// note: load onsubmit script at the end
	?>
	<script src="/scripts/scr_zipcode.js"></script>
	<script src="/scripts/scr_number.js"></script>
	<script src="/scripts_xhr/scr_xhr_gethttpobject.js"></script>
	<script src="/scripts_xhr/scr_xhr_gisvolunteer_updatepsll.js"></script>
	<script src="/scripts/scr_string.js"></script>
	<script src="/scripts/scr_email.js"></script>
	<script src="/scripts/scr_stringcomplex_a.js"></script>
	<script src="/scripts/scr_textarealength.js"></script>
	<script src="/scripts/scr_gisvolunteer_profile.js"></script>
		<tr>
	<td class="medium_bold">
	<?php
	//	note: do NOT include imgmenu since it reloads this page on submission
	?>
	<span class="medium_bold">
	>> Submit Profile
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
		include '/var/chroot/home/content/57/3881957/html/inc_con/inc_con_gisvolunteer_profiletable.php';
		?>
		</td>
		<td class="medium">
		<img src="/images/img_volunteer_profile.gif" alt="spatiallink_org" style="width:375;height:200;" align="left"/>
		</td>
			</tr>
				</table>
	<!--include cell table: layout-->
	</td>
		</tr>

		<tr>
	<td class="medium">
	Job History
	<br />
	<!--xhtml: instead of "onKeyUp", use "onkeyup". refer to [http://www.w3schools.com/xhtml/xhtml_eventattributes.asp]-->
	<textarea cols="104" rows="10" name="jobhistory" onkeydown="checktextarealength(this.form.jobhistory,this.form.remaininglength,1024);" onkeyup="checktextarealength(this.form.jobhistory,this.form.remaininglength,1024);"></textarea>
	<br />
	<input readonly="readonly" type="text" name="remaininglength" value="1024" size="4" maxlength="1024" class="read_only" />
	<br />
	<br />
	</td>
		</tr>
				
		<tr>
	<td class="medium">
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