<!--include content-->
<?php
/* POST as caps and string as case-sensitive */
if ($_SERVER['HTTP_REFERER'] == 
"http://www.spatiallink.org/gistools/awk/private/awk.user.query.php" && strstr($_SERVER["HTTP_USER_AGENT"], "MSIE")) {
// include protected content once
?>
			<table width="760" border="1" align="left" cellspacing="0" cellpadding="0" bgcolor="#C2C0C0">
		<tr>
	<td>
	<span class="large_red">
	Query "AWK"
	<?php
	// include scripts
	?><script src="/scripts/scr_date.js"></script><?php 
	// done
	?>
	</span>
	<br />
	<br />
	
	<hr />
	<br />
	<input type="button" name="print" value="Print" size="" maxlength="" onclick="printdoc()" />
	<br />
	<br />
	
	<hr />
	<span class="large_red">
	>> Result For "<?php
	print $_POST['queryword'];
	?>": Top 10 Ranked By Relevance
	</span>
	<br />
	<br />
	
<?php
// require database variables
require '/opt/bitnami/apache2/_sec/inc_db_variables.php'; 
// done
// connect and select
$varconnect = mysqli_connect($varhost, $varuser, $varpass) 
or die("Could NOT connect to dataserver: " . mysqli_error($varconnect));
mysqli_select_db("$vardb") 
or die("Could NOT select database: " . mysqli_error($varconnect));
// done
// clean submitted variables
function safeEscapeString($string){
       if (get_magic_quotes_gpc()) {
          return $string;
       } else {
          return mysqli_real_escape_string($string);
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
// variable check: split string- N/A; check for NULL;
	if ($_POST[filenote] == "") {
		$filenote = "%";
	} else {
		$filenote = $_POST[filenote];
	}
						
	if ($_POST[examimp] == "") {
		$examimp = "%";
	} else {
		$examimp = $_POST[examimp];
	}
	
	if ($_POST[difficulty] == "") {
		$difficulty = "%";
	} else {
		$difficulty = $_POST[difficulty];
	}
	
	if ($_POST[sex] == "") {
		$sex = "%";
	} else {
		$sex = $_POST[sex];
	}

	if ($_POST[agegroup] == "") {
		$agegroup = "%";
	} else {
		$agegroup = $_POST[agegroup];
	}

	if ($_POST[bodysystem] == "") {
		$bodysystem = "%";
	} else {
		$bodysystem = $_POST[bodysystem];
	}
// done							
// perform SQL query: score or relevance
// ex. $varquery1 = "SELECT * FROM `spatiallink_org`.`TB_AWK`";
$varuse = "USE 'awk'";
$varquery1 = "SELECT *, MATCH(AWK_KEYWORD, AWK_TITLE, AWK_SUMMARY) AGAINST ('".$_POST['queryword']."') AS score FROM `spatiallink_org`.`TB_AWK` WHERE MATCH(AWK_KEYWORD, AWK_TITLE, AWK_SUMMARY) AGAINST ('".$_POST['queryword']."') AND AWK_EXAMIMP LIKE '$examimp'  AND AWK_DIFFICULTY LIKE '$difficulty' AND AWK_SEX LIKE '$sex' AND AWK_AGEGROUP LIKE '$agegroup' AND AWK_BODYSYSTEM LIKE '$bodysystem' ORDER BY score DESC LIMIT 10";
$varresult1 = mysqli_query($varconnect, $varquery1) or die("AWK Query failed :" . mysqli_error($varconnect));
while ($varrow1 = mysqli_fetch_array($varresult1)) {
	 printf("<span class=\"medium_red\">Score: ".$varrow1['score']."<br />SN: ".$varrow1['PK_AWK_SN']."<br />Date Created: ".$varrow1['AWK_DATE']."</span><br /><span class=\"medium_bold\">Filename: </span><span class=\"medium\">".$varrow1['AWK_FILENAME']."</span><br /><span class=\"medium_bold\">Filepath: </span><span class=\"medium\"><a href=\"".$varrow1['AWK_FILEPATH']."\">".$varrow1['AWK_FILEPATH']."</a></span><br /><span class=\"medium_bold\">Keywords: </span><span class=\"medium\">".$varrow1['AWK_KEYWORD']."</span><br /><span class=\"medium_bold\">Title: </span><span class=\"medium\">".$varrow1['AWK_TITLE']."</span><hr />");
	 }
// done
// check for NULL result
if (mysqli_num_rows($varresult1) > 0) {
	} else {
		   print "<span class=\"medium\">Your query resulted in <b>no match</b>. Please go back and query again.</span>";
		   }
// done
?>
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