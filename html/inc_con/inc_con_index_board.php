<?php
//	http://slashdot.org/article.pl?sid=05/09/29/000223
//	http://www.aypwip.org/webnote/
//	http://ajaxoffice.sourceforge.net/
//	http://www.xajaxproject.org/
/*	
	text box to capture limited board
	submit button to save board and timestamp
	text to store saved board
	display script to display saved board along with date and time created
	erase button to erase saved board and append it
	board has to stay for 5 minutes before it can be erased
	...leave boards for other visitors about interests, openings etc
*/
?><script src="/scripts/scr_textarealength.js"></script><?php
?><script src="/scripts_xhr/scr_xhr_gethttpobject.js"></script><?php
?><script src="/scripts_xhr/scr_xhr_index_updateboard.js"></script><?php
//	fopen() may return a resource identifier. use file() to read the contents of a file as array; use file_get_contents() to return the contents of a file as string; also, refer to readfile();
$boardhandle = file_get_contents("/var/chroot/home/content/57/3881957/html/txt/txt_index_board.txt");
?>
<form>
Experimental Notice Board&nbsp;&nbsp;<img src="/images/phpthumb/phpThumb.php?src=/images/img_board.gif" alt="spatiallink_org" width="12" height="12" />
<br />
<textarea name="board" id="board" value="" cols="71" rows="2" onblur="updateboard();" onkeydown="checktextarealength(this.form.board,this.form.remaininglength,128);" onkeyup="checktextarealength(this.form.board,this.form.remaininglength,128);" /><?php print $boardhandle; ?></textarea>
<br />
<input readonly="readonly" type="text" name="remaininglength" value="128" size="3" maxlength="3" class="read_only" />
</form>