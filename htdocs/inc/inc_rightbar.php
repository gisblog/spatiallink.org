</td>
<!--center <td> ends-->
<!--right <td> begins-->
<td width="120" bgcolor="#dddddd" class="medium">
	<?php
	/*	URL/URI filter: 
		$_SERVER['REQUEST_URI'] ~ $PHP_SELF. note that PHP_SELF only returns the page url (page.php), whereas REQUEST_URI will also return the GET variables or $_SERVER['QUERY_STRING'] (page.php?x=y).	you can concatenate like so- 
				$pageurl = $PHP_SELF."?".$_SERVER['QUERY_STRING'];	*/
	if ($_SERVER['REQUEST_URI'] == "/poll/poll_voteresult.php") {
		// do NOT include standard right bar
	} else {
		// do include standard right bar
		?>
		<!--include poll-->			
				<table cellspacing="0" cellpadding="0">
			<tr>
		<td width="120" bgcolor="#c2c0c0" class="medium">
		>> Poll
		</td>
			</tr>
			<tr>
		<td width="120" bgcolor="#dddddd" class="medium">
		<!--span: <span> can NOT enclose <form> or <table> for XHTML-->
		<?php
		// include poll_variables
		include '/opt/bitnami/apache2/htdocs/poll/poll_variables.php'; 
		// done: initialize
		$answer = null;
		$vresult = null;
		if (strlen($answer)<=0&&!$vresult) {
			?>
			<!--form: vote-->
			<form method="post" name="poll_vote" action="/poll/poll_voteresult.php">
				<span class="medium">
				<?php
				print $QUESTION;
				?>	
				<br />
				<?php
				while (list($key, $val) = each($ANSWER)) {
				print "<input type=\"radio\" name=\"answer\" value=\"$key\" /> $val<br />";}
				?>
				<input type="submit" name="vote" value="Vote" />
				</span>
			</form>
			<!--form: vote-->
			</td>
				</tr>
					</table>
			<?php
		}
		//	include poll
	}
	?>	
</td>
<!--right <td> ends-->
	</tr>
		</table>
