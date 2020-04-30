<!--include content-->
<?php
/* POST as caps and string as case-sensitive */
if ($_SERVER['HTTP_REFERER'] == 
"http://www.spatiallink.org/gistools/awk/private/awk.user.enter.php" && 
$_POST['username'] == "renee" && $_POST['password'] == "quire" && strstr($_SERVER["HTTP_USER_AGENT"], "MSIE")) {
// include protected content once
?>
						<table width="670" border="1" align="left" cellspacing="0" cellpadding="0" bgcolor="#C2C0C0">
					<tr>
				<td>
				<span class="large_red">
				Insert "AWK"
				<?php
				// include scripts
				include '/var/chroot/home/content/57/3881957/html/scripts/scr_date.js';
				include '/var/chroot/home/content/57/3881957/html/scripts/scr_awk_insert.js';
				// include scripts
				?>
				</span>
				<br />
				<br />
				
				<hr />
				<span class="medium">
				&bull;&nbsp;<a href="awk.user.query.php" target="_blank">Query</a>
				</span>
				<br />
				<br />

				<hr />	
				<!--span: <span> can NOT enclose <form> for xhtml-->
				<form method="post" name="awk" action="awk.user.confirm.php" onsubmit="return checkawk()">

				<span class="medium_bold">
				<input type="submit" value="Submit Only Once" />
			    <input type="reset" />
			    <br />
				[Create Text Dump]
			    [Create Summary PDF]
			    [Request Summary To Be Emailed Every Week]
				</span>
			    <br />
				<br />

				<!--*1*-->
				<hr />
				<span class="large_red">
				>> Brief Description (required)
				</span>
				<br />
				<br />

				<span class="medium_bold">
				Filename&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Filepath
			    <br />
			    <input type="text" name="filename" value="" size="49" maxlength="50" />
			    <input type="text" name="filepath" value="" size="49" maxlength="255" />
				<br />
				<br />

				Keywords
			    <br />
			    <input type="text" name="keyword" value="" size="100" maxlength="255" />
				<br />
				<br />

				Title
			    <br />
			    <input type="text" name="title" value="" size="100" maxlength="255" />
				<br />
				<br />

				Summary
				</span>
				<span class="medium">
				(probable causes, likely symptoms, diseases caused, recommended exams/tests, recommended treatments, recommended natural treatments, recommended surgery procedures, medications prescribed, preventive measures, reference sources, diagram description/location)
			   	<br />
			   	<textarea cols="80" rows="10" name="summary"></textarea>
				</span>
			   	<!--*1*-->

				<!--*2*-->
			   	<hr />
				<span class="large_red">
				>> Detail Description (optional)
				</span>
				<br />
				<br />

				<span class="medium_bold">
				Filenote
				<br />
				<input type="radio" name="filenote" value="S" /> Notes By Self
				<input type="radio" name="filenote" value="O" /> Notes By Others
				<br />
				<br />

				Importance For Exam
				<br />
				<input type="radio" name="examimp" value="H" /> High
				<input type="radio" name="examimp" value="M" /> Medium
				<input type="radio" name="examimp" value="L" /> Low
				<br />
				<br />

				Personal Difficulty Level
				<br />
				<input type="radio" name="difficulty" value="H" /> High
				<input type="radio" name="difficulty" value="M" /> Medium
				<input type="radio" name="difficulty" value="L" /> Low
				<br />
				<br />

				Effected Sex
				<br />
				<input type="radio" name="sex" value="M" /> Male
				<input type="radio" name="sex" value="F" /> Female
				<input type="radio" name="sex" value="B" /> Both
				<br />
				<br />

				Effected Agegroup
				<br />
				<input type="radio" name="agegroup" value="0000" /> All
				<input type="radio" name="agegroup" value="0110" /> 1-10
				<input type="radio" name="agegroup" value="1130" /> 11-30
				<input type="radio" name="agegroup" value="1100" /> 11+
				<input type="radio" name="agegroup" value="3150" /> 31-50
				<input type="radio" name="agegroup" value="3100" /> 31+
				<input type="radio" name="agegroup" value="5100" /> 51+
				<input type="radio" name="agegroup" value="7100" /> 71+
				<br />
				<br />

			   	Effected Bodysystem
			   	<br />
				<input type="radio" name="bodysystem" value="MOR" /> More Than 1
				<input type="radio" name="bodysystem" value="CIR" /> Circulatory
				<input type="radio" name="bodysystem" value="DIG" /> Digestive
				<input type="radio" name="bodysystem" value="END" /> Endocrine
				<input type="radio" name="bodysystem" value="INT" /> Integumentary
				<input type="radio" name="bodysystem" value="MUS" /> Muscular
				<br />
				<input type="radio" name="bodysystem" value="NER" /> Nervous
				<input type="radio" name="bodysystem" value="REP" /> Reproductive
			   	<input type="radio" name="bodysystem" value="RES" /> Respiratory
			   	<input type="radio" name="bodysystem" value="SKE" /> Skeletal
			   	<input type="radio" name="bodysystem" value="URI" /> Urinary

				</span>
				<!--*2*-->

				</form>
				<!--span: <span> can NOT enclose <form> for xhtml-->
				</td>
					</tr>
						</table>
		<?php
		// include protected content once
		} elseif ($_SERVER['HTTP_REFERER'] == "http://www.spatiallink.org/gistools/awk/private/awk.user.confirm.php") {
		// include protected content again
		?>
						<table width="670" border="1" align="left" cellspacing="0" cellpadding="0" bgcolor="#C2C0C0">
					<tr>
				<td>
				<span class="large_red">
				Insert "AWK"
				<?php
				// include scripts
				include '/var/chroot/home/content/57/3881957/html/scripts/scr_date.js';
				include '/var/chroot/home/content/57/3881957/html/scripts/scr_awk_insert.js';
				// include scripts
				?>
				</span>
				<br />
				<br />
				
				<hr />
				<span class="medium">
				&bull;&nbsp;<a href="awk.user.query.php" target="_blank">Query</a>
				</span>
				<br />
				<br />

				<hr />
				<!--span: <span> can NOT enclose <form> for xhtml-->
				<form method="post" name="awk" action="awk.user.confirm.php" onsubmit="return checkawk()">				

				<span class="medium_bold">
				<input type="submit" value="Submit Only Once" />
			    <input type="reset" />
			    <br />
				[Create Text Dump]
			    [Create Summary PDF]
			    [Request Summary To Be Emailed Every Week]
				</span>
			    <br />
				<br />

				<!--*1*-->
				<hr />
				<span class="large_red">
				>> Brief Description (required)
				</span>
				<br />
				<br />

				<span class="medium_bold">
				Filename&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Filepath
			    <br />
			    <input type="text" name="filename" value="" size="49" maxlength="50" />
			    <input type="text" name="filepath" value="" size="49" maxlength="255" />
				<br />
				<br />

				Keywords
			    <br />
			    <input type="text" name="keyword" value="" size="100" maxlength="255" />
				<br />
				<br />

				Title
			    <br />
			    <input type="text" name="title" value="" size="100" maxlength="255" />
				<br />
				<br />

				Summary
				</span>
				<span class="medium">
				(probable causes, likely symptoms, diseases caused, recommended exams/tests, recommended treatments, recommended natural treatments, recommended surgery procedures, medications prescribed, preventive measures, reference sources, diagram description/location)
			   	<br />
			   	<textarea cols="80" rows="10" name="summary"></textarea>
				</span>
			   	<!--*1*-->

				<!--*2*-->
			   	<hr />
				<span class="large_red">
				>> Detail Description (optional)
				</span>
				<br />
				<br />

				<span class="medium_bold">
				Filenote
				<br />
				<input type="radio" name="filenote" value="S" /> Notes By Self
				<input type="radio" name="filenote" value="O" /> Notes By Others
				<br />
				<br />

				Importance For Exam
				<br />
				<input type="radio" name="examimp" value="H" /> High
				<input type="radio" name="examimp" value="M" /> Medium
				<input type="radio" name="examimp" value="L" /> Low
				<br />
				<br />

				Personal Difficulty Level
				<br />
				<input type="radio" name="difficulty" value="H" /> High
				<input type="radio" name="difficulty" value="M" /> Medium
				<input type="radio" name="difficulty" value="L" /> Low
				<br />
				<br />

				Effected Sex
				<br />
				<input type="radio" name="sex" value="M" /> Male
				<input type="radio" name="sex" value="F" /> Female
				<input type="radio" name="sex" value="B" /> Both
				<br />
				<br />

				Effected Agegroup
				<br />
				<input type="radio" name="agegroup" value="0000" /> All
				<input type="radio" name="agegroup" value="0110" /> 1-10
				<input type="radio" name="agegroup" value="1130" /> 11-30
				<input type="radio" name="agegroup" value="1100" /> 11+
				<input type="radio" name="agegroup" value="3150" /> 31-50
				<input type="radio" name="agegroup" value="3100" /> 31+
				<input type="radio" name="agegroup" value="5100" /> 51+
				<input type="radio" name="agegroup" value="7100" /> 71+
				<br />
				<br />

			   	Effected Bodysystem
			   	<br />
				<input type="radio" name="bodysystem" value="MOR" /> More Than 1
				<input type="radio" name="bodysystem" value="CIR" /> Circulatory
				<input type="radio" name="bodysystem" value="DIG" /> Digestive
				<input type="radio" name="bodysystem" value="END" /> Endocrine
				<input type="radio" name="bodysystem" value="INT" /> Integumentary
				<input type="radio" name="bodysystem" value="MUS" /> Muscular
				<br />
				<input type="radio" name="bodysystem" value="NER" /> Nervous
				<input type="radio" name="bodysystem" value="REP" /> Reproductive
			   	<input type="radio" name="bodysystem" value="RES" /> Respiratory
			   	<input type="radio" name="bodysystem" value="SKE" /> Skeletal
			   	<input type="radio" name="bodysystem" value="URI" /> Urinary

				</span>
				<!--*2*-->

				</form>
				<!--span: <span> can NOT enclose <form> for xhtml-->
				</td>
					</tr>
						</table>
<?php
// include protected content again
} else {
// include content
print "<table width=\"670\" border=\"0\" align=\"left\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#FFFFFF\"><tr><td><span class=\"medium\">Unauthorized Access or Incompatible Browser</span></td></tr></table>";
// include content
}
?>
<!--include content-->