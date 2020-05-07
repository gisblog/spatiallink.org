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
	<!--span: <span> can NOT enclose <form> for xhtml-->
	<form method="post" name="awk" action="awk.user.result.php" onsubmit="return checkawk()">
	
	<span class="medium_bold">
	<input type="submit" value="Submit Only Once" />
    <input type="reset" />
    <br />
	Query Tips
	</span>
	<br />
	<span class="medium">
	&bull;&nbsp;Ensure that each query word is atleast 4 or more letters long
	<br />
	&bull;&nbsp;Separate 2 query words by a single space
	<br />
	&bull;&nbsp;Check word-spelling
	<br />
	&bull;&nbsp;Exclude numbers and special characters
	</span>
	<br />
	<br />

	<!--hr: <hr> can NOT enclose <form> for xhtml-->	
	<hr />
	<span class="medium_red">
	Query...
	</span>
	<br />
	<br />
	
	<span class="medium_bold">
	Keywords, Title, Summary
	</span>
	<span class="medium_red">
	(required)
	<br />
	<input type="text" name="queryword" value="" size="100" maxlength="255" />
	<br />
	<br />
	
	...Where
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
	
	Effected Bodysystems
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
	
	</form>
	<!--span: <span> can NOT enclose <form> for xhtml-->
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