<!--include content-->
<?php
/* POST as caps and string as case-sensitive */
if ($_SERVER['HTTP_REFERER'] == 
"http://www.spatiallink.org/gistools/awk/private/awk.user.entered.php" && strstr($_SERVER["HTTP_USER_AGENT"], "MSIE")) {
// include protected content once
?>
						<table width="760" border="1" align="left" cellspacing="0" cellpadding="0" bgcolor="#C2C0C0">
					<tr>
				<td>
				<span class="large_red">
				Insert "AWK"
				<?php
				// include scripts
				?><script src="/scripts/scr_date.js"></script><?php 
				// done
				?>
					</span>
					<br />
					<br />
					
				<?php
				// require database variables
				require '/var/chroot/home/content/57/3881957/html/inc/inc_db_variables.php'; 
				// done
				// connect and select
				$varconnect = mysql_connect($varhost, $varuser, $varpass) 
				or die("Could NOT connect to dataserver: " . mysql_error());
				mysql_select_db("$vardb") 
				or die("Could NOT select database: " . mysql_error());
				// done
				// clean submitted variables
				function safeEscapeString($string){
				       if (get_magic_quotes_gpc()) {
				          return $string;
				       } else {
				          return mysql_real_escape_string($string);
				       }
				  }
				  function cleanVar($string){
				      $string = trim($string);
				      $string = safeEscapeString($string);
				      $string = htmlentities($string);
				      return $string;
				  }
				  foreach($_POST as $name => $value){
				      $_POST[$name] = cleanVar($value);
				  } 
				  foreach($_GET as $name => $value){
				      $_GET[$name] = cleanVar($value);
				  } 
				  foreach($_COOKIE as $name => $value){
				      $_COOKIE[$name] = cleanVar($value);
				  } 
				  foreach($_REQUEST as $name => $value){
				      $_REQUEST[$name] = cleanVar($value);
				  }
				// done
				// set variables: SN
				$varstamp = time();
				$vartime = date('h:i:s A');
				$vardate = date('l dS F Y');
				$varip = $_SERVER['REMOTE_ADDR'];
				$varhost = gethostbyaddr($_SERVER['REMOTE_ADDR']);
				$varrefpage = $_SERVER['HTTP_REFERER'];
				// done
				/* variable check:
				print "SN";
				print $varstamp;
				print $vartime;
				print $vardate;
				print $varip;
				print $varhost;
				print $varrefpage;
				
				print $_POST[filename];
				print $_POST[filepath];
				print $_POST[filenote];
				print $_POST[keyword];
				print $_POST[title];
				print $_POST[summary];
				
				print $_POST[examimp];
				print $_POST[difficulty];
				print $_POST[sex];
				print $_POST[agegroup];
				print $_POST[bodysystem];
				print $_POST[exam];
				print $_POST[medication];
				done */
				// perform  SQL query:
				// ex. $varquery1 = "SELECT * FROM `spatiallink_org`.`TB_AWK`";
				$varuse = "USE 'spatiallink_org'";
				$varquery1 = "INSERT INTO `spatiallink_org`.`TB_AWK` (PK_AWK_SN, PK_AWK_STAMP, AWK_TIME, AWK_DATE, AWK_IP, AWK_HOST, AWK_REFSITE, AWK_FILENAME, AWK_FILEPATH, AWK_KEYWORD, AWK_TITLE, AWK_SUMMARY, AWK_FILENOTE, AWK_EXAMIMP, AWK_DIFFICULTY, AWK_SEX, AWK_AGEGROUP, AWK_BODYSYSTEM) VALUES ('', '$varstamp', '$vartime', '$vardate', '$varip', '$varhost', '$varrefpage', '$_POST[filename]', '$_POST[filepath]', '$_POST[keyword]', '$_POST[title]', '$_POST[summary]', '$_POST[filenote]', '$_POST[examimp]', '$_POST[difficulty]', '$_POST[sex]', '$_POST[agegroup]', '$_POST[bodysystem]')";
				$varresult1 = mysql_query($varquery1) or 
				die("AWK Insert Failed. " . mysql_error());
				// done
				?>
			
					<hr />
					<span class="medium">
					Insert Confirmed
					<br />
					&bull;&nbsp;<a href="awk.user.entered.php">Insert Again</a>
					<br />
					&bull;&nbsp;<a href="awk.user.query.php" target="_blank">Query</a>
					</span>
					</td>
						</tr>
							</table>
<?php
// include protected content once
} else {
// include content
print "<table width=\"760\" border=\"0\" align=\"left\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#FFFFFF\"><tr><td><span class=\"medium\">Unauthorized Access or Incompatible Browser</span></td></tr></table>";
// include content
}
?>
<!--include content-->