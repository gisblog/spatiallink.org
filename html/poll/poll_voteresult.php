<?php
/*
// intro xhtml: goes before anything else
if (stristr($_SERVER['HTTP_ACCEPT'], "application/xhtml+xml"))
  {
    header("content-Type: application/xhtml+xml; charset=utf-8");
  }
  else
  {
    header("content-type: text/html; charset=utf-8");
  }
*/
print '<!DOCTYPE html>';
print '<html>';
// include head:
include '/var/chroot/home/content/57/3881957/html/inc/inc_head.php';
// include header:
include '/var/chroot/home/content/57/3881957/html/inc/inc_header.php';
/* include content here */
// check for browser compatibility:
if (strstr($_SERVER["HTTP_USER_AGENT"], "MSIE")) {
	//	include leftbar:
	include '/var/chroot/home/content/57/3881957/html/inc/inc_leftbar.php';
	// include poll_voteresult: modified from 'simple php poll 1.0 copyright 2004 cgixp team @ http://www.cgixp.tk'. note that with multiple tables herein, it is best to include them between leftbar<td> and rightbar</td>, and do 1 <br /> in footer.
	// include poll_variables: also included with rightbar
	include 'poll_variables.php';
	// leftbar<span> can NOT enclose <table> or <form> for xhtml. leftbar<td> continues.
	print ">> $QUESTION";
	print "
	<br />
	<span class=\"small\">
	  Results are indicative and may not reflect public opinion.
	</span>
	<br />
	<br />
	";
	if (strlen($answer)>0) {
		$plsr = file("poll_log.txt");
		for($x=0;$x<sizeof($plsr);$x++) {
		$temp = explode("|",$plsr[$x]);
	}
	if($ip==$temp[0] && strlen($answer)>0 ) {
		print "
		<span class=\"medium_red\">
		  Duplicate Vote
		</span>
		";
		// <table border="0" align="left" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
		// <tr>
		// <td width="760" align="left" valign="top" bgcolor="#FFFFFF"><span class="medium_red">Duplicate Vote</span></td>
		// </tr>
		// </table>
		$file_array = file($RESULT_FILE_NAME);
		if ($answer < count($ANSWER) && $vote) {
		while (list($key, $val) = each($file_array)) {
		$total += $val;
	}
	?>
	<!-- include cell table 1 -->
	    <table border="0" align="left" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
	  <tr>
	<td width="156" align="left" valign="top" bgcolor="#FFFFFF">
	  <span class="medium">
	    <u>
	      Option
	    </u>
	  </span>
	</td>
	<td width="156" align="left" valign="top" bgcolor="#FFFFFF">
	  <span class="medium">
	    <u>
	      Percentage
	    </u>
	  </span>
	</td>
	<td width="156" align="left" valign="top" bgcolor="#FFFFFF">
	  <span class="medium">
	    <u>
	      Vote
	    </u>
	  </span>
	</td>
	  </tr>
	  <tr>
	<?php
	while (list($key, $val) = each($ANSWER)) {
	$percent =  $file_array[$key] * 100 / $total;
	$percent_int = floor($percent);
	$percent_float = number_format($percent, 1);
	$tp += $percent_float;
	if($percent_int>=75) {
		$color="blue";
	} elseif($percent_int>=50) {
		$color="green";
	} elseif($percent_int>=25) {
		$color="orange";
	} elseif($percent_int<25) {
		$color="red";
	}
	// td1: option/answer
	print "
	<td>
	  <span class=\"medium\">
	    $ANSWER[$key]
	  </span>
	</td>
	";
	?>
	<!-- td2: percentage -->
	<td>
	<!--include sub-cell table: boundary-->
	      <table width="100%" border="0" bgcolor="black" cellspacing="0" cellpadding="1">
	    <tr>
	  <td>
	    <!--include sub-sub-cell table: bar-->
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
	      <tr>
	    <?php
	    print "
	    <td width=\"$percent_int%\" height=\"10\" bgcolor=\"$color\">
	      <spacer type=\"block\" width=\"2\" height=\"8\" />
	    </td>
	    ";
	    ?>
	    <td width="91%" height="10" bgcolor="white">
	      <spacer type="block" width="2" height="8" />
	    </td>
	      </tr>
		</table>
	  <!--include sub-sub-cell table: bar-->
	  </td>
	    </tr>
	      </table>
	<!--include sub-cell table: boundary-->
	<span class="medium">
	<?php
	print "$percent_float%";
	?>
	</span>
      </td>
      <!-- td3: vote -->
      <td>
	<span class="medium">
	<?php
	print "$file_array[$key]";
	print "
	</span>
      </td>
	</tr>
	";
	}
	?>
	  </table>
      <?php
      $tv=$total;
		}
	} elseif (strlen($answer)>0 && $ip!=$temp[0]) {
	  $file_array = file($RESULT_FILE_NAME);
	  if ($answer < count($ANSWER) && $vote) {
	    $old_answer = $file_array[$answer];
	    $old_answer = preg_replace("/\n\r*/", "", $old_answer);
	    $file_array[$answer] = ($old_answer + 1)."\n";
	    $fname="poll_log.txt";
	    $fq = fopen($fname, "a++");
	    fwrite ($fq, $ip);
	    fwrite ($fq, "|");
	    fwrite ($fq, "\n");
	    fclose ($fq);
	    $file = join('', $file_array);
	    $fp = fopen("$RESULT_FILE_NAME", "w");
	    flock($fp, 1);
	    fputs($fp, $file);
	    flock($fp, 3);
	    fclose($fp);
	    print "Vote saved";
	  }
	  while (list($key, $val) = each($file_array)) {
	    $total += $val;
	  }
	  ?>
	  <!-- include cell table 2 -->
	      <table border="0" align="left" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
	    <tr>
	  <td width="156" align="left" valign="top" bgcolor="#FFFFFF">
	    <span class="medium">
	      <u>
		Option
	      </u>
	    </span>
	  </td>
	  <td width="156" align="left" valign="top" bgcolor="#FFFFFF">
	    <span class="medium">
	      <u>
		Percentage
	      </u>
	    </span>
	  </td>
	  <td width="156" align="left" valign="top" bgcolor="#FFFFFF">
	    <span class="medium">
	      <u>
		Vote
	      </u>
	    </span>
	  </td>
	    </tr>
	    <tr>
	  <?php
	  while (list($key, $val) = each($ANSWER)) {
	    $percent =  $file_array[$key] * 100 / $total;
	    $percent_int = floor($percent);
	    $percent_float = number_format($percent, 1);
	    $tp += $percent_float;
	    if($percent_int>=75) {
	      $color="blue";
	    } elseif($percent_int>=50) {
	      $color="green";
	    } elseif($percent_int>=25) {
	      $color="orange";
	    } elseif($percent_int<25) {
	      $color="red";
	    }
	    // td1: option/answer
	    print "
	    <td>
	      <span class=\"medium\">
		$ANSWER[$key]
	      </span>
	    </td>
	    ";
	    ?>
	    <!-- td2: percentage -->
	    <td>
	      <!-- include sub-cell table: boundary -->
		  <table width="100%" border="0" bgcolor="black" cellspacing="0" cellpadding="1">
		<tr>
	      <td>
		<!--include sub-sub-cell table: bar -->
		    <table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
		<?php
		print "
		<td width=\"$percent_int%\" height=\"10\" bgcolor=\"$color\">
		  <spacer type=\"block\" width=\"2\" height=\"8\" />
		</td>
		";
		?>
		<td width="91%" height="10" bgcolor="white">
		  <spacer type="block" width="2" height="8" />
		</td>
		  </tr>
		    </table>
		<!-- include sub-sub-cell table: bar -->
	      </td>
		</tr>
		  </table>
	    <!-- include sub-cell table: boundary -->
	    <span class="medium">
	    <?php
	    print "$percent_float%";
	    ?>
	    </span>
	  </td>
	  <!-- td3: vote -->
	  <td>
	    <span class="medium">
	      <?php
	      print "$file_array[$key]";
	      print "
	    </span>
	  </td>
	  ";
	  }
	   ?>
	    
	  <?php
	  $tv=$total;
	} else {
	  print "Please Select an option first";
	}
	}
	if (!empty ($vresult)) {
	  $file_array = file($RESULT_FILE_NAME);
	  while (list($key, $val) = each($file_array)) {
	    $total += $val;
	  }
	  ?>
	  <!-- include cell table 3 -->
		<table border="0" align="left" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
	      <tr>
	    <td width="173" align="left" valign="top" bgcolor="#FFFFFF">
	      <span class="medium">
		<u>
		  Option
		</u>
	      </span>
	    </td>
	    <td width="173" align="left" valign="top" bgcolor="#FFFFFF">
	      <span class="medium">
		<u>
		  Percentage
		</u>
	      </span>
	    </td>
	    <td width="174" align="left" valign="top" bgcolor="#FFFFFF">
	      <span class="medium">
		<u>
		  Vote
		</u>
	      </span>
	    </td>
	      </tr>
	      <tr>
	    <?php
	    while (list($key, $val) = each($ANSWER)) {
	      $percent =  $file_array[$key] * 100 / $total;
	      $percent_int = floor($percent);
	      $percent_float = number_format($percent, 1);
	      $tp += $percent_float;
	      if($percent_int>=75) {
		$color="blue";
	      } elseif($percent_int>=50) {
		$color="green";
	      } elseif($percent_int>=25) {
		$color="orange";
	      } elseif($percent_int<25) {
		$color="red";
	      }
	      // td1: option/answer
	      print "
	    <td>
	      <span class=\"medium\">
		$ANSWER[$key]
	      </span>
	    </td>
	    ";
	    ?>
	    <!-- td2: percentage -->
	    <td>
	      <!-- include sub-cell table: boundary -->
		  <table width="100%" border="0" bgcolor="black" cellspacing="0" cellpadding="1">
		<tr>
	      <td>
		<!-- include sub-sub-cell table: bar -->
		    <table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
		<?php
		print "
		<td width=\"$percent_int%\" height=\"10\" bgcolor=\"$color\">
		  <spacer type=\"block\" width=\"2\" height=\"8\" />
		</td>
		";
		?>
		<td width="91%" height="10" bgcolor="white">
		  <spacer type="block" width="2" height="8" />
		</td>
		  </tr>
		    </table>
		<!-- include sub-sub-cell table: bar -->
	      </td>
		</tr>
		  </table>
	      <!-- include sub-cell table: boundary -->
	      <span class="medium">
		<?php
		print "$percent_float%";
		?>
	      </span>
	    </td>
	    <!-- td3: vote -->
	    <td>
	      <span class="medium">
		<?php
		print "$file_array[$key]";
		print "
	      </span>
	    </td>
	    ";
	    }
	    ?>
	      </tr>
		</table>
	    <?php
	    $tv=$total;
	}
	?>
	<!-- span: rightbar<span> begins -->
	<span class="medium">
	<?php
	// include poll_voteresult:
	// include rightbar:
	include '/var/chroot/home/content/57/3881957/html/inc/inc_rightbar.php';
	// include footer:
	include '/var/chroot/home/content/57/3881957/html/inc/inc_footer.php';
} else {
	// do NOT include leftbar:
	// do NOT include poll_voteresult:
	print "Error"; # "Unauthorized Access or Incompatible Browser";
	// do NOT include rightbar:
	// include footer:
	include '/var/chroot/home/content/57/3881957/html/inc/inc_footer.php';
} # check for browser compatibility
?>