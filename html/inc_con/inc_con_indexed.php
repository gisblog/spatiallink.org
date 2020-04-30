<?php
/*	from form, NOT from home since variable exists */
/*	done */
//	require checkjs_clean here
require '/var/chroot/home/content/57/3881957/html/inc/inc_checkjs_clean.php';
//	include scripts_fct(): can NOT redeclare f()
include '/var/chroot/home/content/57/3881957/html/scripts_fct/scr_fct_cleanstring.php';
//
$qsearchkeywords = $_POST[qsearchkeywords];
$location = $_POST[location];
//	include scripts
//	note: do NOT include imgmenu since it reloads this page on submission
?>
<!--include content-->
		<table width="760" border="0" align="left" cellspacing="0" cellpadding="0" bgcolor="#C2C0C0">
	<tr>
<td class="medium_bold">
<span class="medium_bold">
>> Quick Search
</span>
</td>
	</tr>
<?php
/*	script time */
$timestart = microtime(true);
/*	done */
// to hide "skew" $qsearchkeywords, do NOT add them to [JOBTITLE][JOBHISTORY] here
//	monitor whether $qsearchkeywords return data or not: optionally, may send automail to self
$filehandle0 = fopen("/var/chroot/home/content/57/3881957/html/txt/volunteer/searchmonitor.txt", "a");
$filecontent0 = " || QUICKSEARCH::$vardate::$varip::$qsearchkeywords\r\n";
fwrite($filehandle0, $filecontent0);
fclose($filehandle0);
/*	query */
//	add "skew" $qsearchkeywords to [JOBTITLE][JOBHISTORY] here. also, note the *,
if ($location == 4) {
	//	Inside United States
	$table_qsearch = "sl1_gisvolunteer_profile";
} else {
	//	$location == 5: Outside United States
	$table_qsearch = "sl1_gisvolunteer_profile_co";
}
$query_qsearch = "SELECT *, MATCH(JOBHISTORY, JOBTITLE) AGAINST ('".$qsearchkeywords." volunteer') AS relevance FROM $table_qsearch WHERE SN_PROFILE > 3 AND EMAIL NOT LIKE '%@SPATIALLINK.ORG' AND MATCH(JOBHISTORY, JOBTITLE) AGAINST ('".$qsearchkeywords." volunteer' IN BOOLEAN MODE) ORDER BY relevance DESC, EXPERIENCE DESC, EDUCATION DESC, VEXPERIENCE DESC, PROFICIENCY DESC LIMIT 10";
$result_qsearch = mysql_query($query_qsearch) or die("Operation Failure :" . mysql_error());
/*	done */
/*	script time OR substr($mtimeend - $mtimestart,0,4) OR number_format($num, 10, '.', '') */
$timeend = microtime(true);
$timedifference = round(abs($timeend - $timestart),2);
/*	done */
/*	check for NULL and print results */
$num_rows = mysql_num_rows($result_qsearch);
if ($num_rows == 0) {
	/*	$query_qsearch does NOT return data [OR it was NOT a SELECT] */
	?>
		<tr>
	<td class="medium">
	<br />
	&bull;&nbsp;Your quick search did not match any profiles
	<br />
	Please try more general keywords and make sure they are spelled correctly, or expand the buffer, then <a href="javascript:history.back()">quick search again</a>.
	<br />
	<br />
	</td>
		</tr>
			</table>
	<?php
} else {
	/*	$query_qsearch MUST have returned data */
	//	textfile
	$filehandle1 = fopen("/var/chroot/home/content/57/3881957/html/txt/volunteer/automail_".$_POST[email]."_".$varip.".txt", "w");
	//	except for [latitude], [longitude], [qsearchkeywords]
	$filecontent1 = "Quick Search::$vardate::$varip";
	fwrite($filehandle1, $filecontent1);
	fclose($filehandle1);
	/*	done */
	?>
		<tr>
	<td class="medium">
	<br />
	&bull;&nbsp;Top <?php print $num_rows; ?> result[s] out of 10 maximum permissible ordered by relevance [<?php print $timedifference; ?> seconds][<a href="javascript:history.back()">Quick search again</a>]
	<br />
	&#186;&nbsp;<a href="/gistools/volunteer/gisvolunteer_profile.php">Submit profile</a> or <a href="/gistools/volunteer/gisvolunteer_search.php">search, map and contact</a> result[s]!
	<br />
	<br />
	</td>
		</tr>
		<tr>
	<td class="medium">
	<!--include cell table: layout. class needs to be defined for every cell table or else IE will NOT render correctly-->
				<table width="750" border="0" align="left" cellspacing="0" cellpadding="0" bgcolor="#C2C0C0">
		<?php		
		//	set microtime() once for both forms to avoid "no such file or directory" error
		$filename_distinct_space = microtime(true);
		$filename_distinct = str_replace(" ", "_", $filename_distinct_space);
		//
		//	sn
		$sn = 1;
		//
		while ($row_qsearch = mysql_fetch_array($result_qsearch)) {
			//	fee
			if ($row_qsearch['FEE'] == '1') {
				$fee = 'Volunteer';
			} elseif ($row_qsearch['FEE'] == '2') {
				$fee = 'USD $1 - USD $25';
			} elseif ($row_qsearch['FEE'] == '3') {
				$fee = 'USD $26 - USD $50';
			} elseif ($row_qsearch['FEE'] == '4') {
				$fee = 'USD $51 - USD $75';
			} elseif ($row_qsearch['FEE'] == '5') {
				$fee = 'USD $76 - USD $100';
			} elseif ($row_qsearch['FEE'] == '6') {
				$fee = 'USD $101+';
			}					
			?>
				<tr>
			<td class="medium">	
			<?php
			//	using print() to avoid printf() "Too few arguments"
			print("[".$sn."] ".$row_qsearch['JOBTITLE'].":: [Fee Estimate = ".$fee." Per Hour]<br /><div class=\"gisvolunteer\">".fct_cleanstring($row_qsearch['JOBHISTORY'])."</div>");
			?>
			<br />
			<br />
			</td>
				</tr>
			<?php
		//	sn
		$sn++;
		//
		}
		?>
				</table>
<!--include cell table-->			
</td>
	</tr>		
		</table>
<?php
}
/*	done */
//	include XHTML break all: NA if NOT sending header()
include '/var/chroot/home/content/57/3881957/html/inc/inc_xhtmlbreakall.php';
?>
<!--include content-->