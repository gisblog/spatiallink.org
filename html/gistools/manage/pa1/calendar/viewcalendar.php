<?php
#Application name: PhpCollab
#Status page: 0
#Path by root: ../calendar/viewcalendar.php

$checkSession = "true";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	$postnukeIntegration = "true";
	include("modules/PhpCollab/includes/library.php");
} else {
	$postnukeIntegration = "false";
	include("../includes/library.php");
}

if ($type == "") {
	$type = "monthPreview";
}

function _dayOfWeek($timestamp) { 
	return intval(strftime("%w",$timestamp)+1);
} 

$year = date("Y");
$month = date("n");
$day = date("j");
	if (strlen($month) == 1) {
		$month = "0$month";
	}
	if (strlen($day) == 1) {
		$day= "0$day";
	}
$dateToday = "$year-$month-$day";

if ($dateCalend != "") {
	$year = substr("$dateCalend", 0, 4);
	$month = substr("$dateCalend", 5, 2);
	$day = substr("$dateCalend", 8, 2);
}

if ($dateCalend == "") {
	$year = date("Y");
	$month = date("n");
	$day = date("d");
	if (strlen($day) == 1) {
		$day = "0$day";
	}
	if (strlen($month) == 1) {
		$month = "0$month";
	}
	$dateCalend = "$year-$month-$day";
}

$yearDay = date("Y");
$monthDay = date("n");
$dayDay = date("d");

$dayName = date("w",mktime(0,0,0,$month,$day,$year));
$monthName = date("n",mktime(0,0,0,$month,$day,$year));
$dayName = $dayNameArray[$dayName];
$monthName = $monthNameArray[$monthName];

$daysmonth = date("t",mktime(0,0,0,$month,$day,$year));
$firstday = date("w",mktime(0,0,0,$month,1,$year));
$padmonth = date("m",mktime(0,0,0,$month,$day,$year));
$padday = date("d",mktime(0,0,0,$month,$day,$year));

if ($firstday == 0) {
	$firstday = 7;
}

if ($type == "calendEdit") {
	if ($action == "update") {
		if ($recurring == "") {
			$recurring = "0";
		} else {
			$dateStart_A = substr("$dateStart", 0, 4);
			$dateStart_M = substr("$dateStart", 5, 2);
			$dateStart_J = substr("$dateStart", 8, 2);
			$dayRecurr = _dayOfWeek(mktime(12,12,12,$dateStart_M,$dateStart_J,$dateStart_A));
		}
		$subject = convertData($subject);
		$description = convertData($description);
		$tmpquery = "UPDATE ".$tableCollab["calendar"]." SET subject='$subject',description='$description',shortname='$shortname',date_start='$dateStart',date_end='$dateEnd',time_start='$time_start',time_end='$time_end',reminder='$reminder',recurring='$recurring',recur_day='$dayRecurr' WHERE id = '$dateEnreg'";
		connectSql("$tmpquery");
		headerFunction("../calendar/viewcalendar.php?dateEnreg=$dateEnreg&dateCalend=$dateCalend&type=calendDetail&msg=update&".session_name()."=".session_id());
	}
	if ($action == "add") {
		if($shortname == "") {
		$error = $strings["blank_fields"];
		} else {
		if ($recurring == "") {
			$recurring = "0";
		} else {
			$dateStart_A = substr("$dateStart", 0, 4);
			$dateStart_M = substr("$dateStart", 5, 2);
			$dateStart_J = substr("$dateStart", 8, 2);
			$dayRecurr = _dayOfWeek(mktime(12,12,12,$dateStart_M,$dateStart_J,$dateStart_A));
		}
		$subject = convertData($subject);
		$description = convertData($description);
		$shortname = convertData($shortname);
		$tmpquery = "INSERT INTO ".$tableCollab["calendar"]."(owner,subject,description,shortname,date_start,date_end,time_start,time_end,reminder,recurring,recur_day) VALUES('$idSession','$subject','$description','$shortname','$dateStart','$dateEnd','$time_start','$time_end','$reminder','$recurring','$dayRecurr')";
		connectSql("$tmpquery");
		$tmpquery = $tableCollab["calendar"];
		last_id($tmpquery);
		$num = $lastId[0];
		unset($lastId);
		headerFunction("../calendar/viewcalendar.php?dateEnreg=$num&dateCalend=$dateCalend&type=calendDetail&msg=add&".session_name()."=".session_id());
		}
	}
}

if ($type == "calendEdit") {
if ($dateEnreg == "" && $id != "") {
	$dateEnreg = $id;
}
if ($id != "") {
	$tmpquery = "WHERE cal.owner = '$idSession' AND cal.id = '$dateEnreg'";
	$detailCalendar = new request();
	$detailCalendar->openCalendar($tmpquery);
	$comptDetailCalendar = count($detailCalendar->cal_id);
if ($comptDetailCalendar == "0") {
	headerFunction("../calendar/viewcalendar.php?".session_name()."=".session_id());
}
}
}

if ($type == "calendDetail") {
if ($dateEnreg == "" && $id != "") {
	$dateEnreg = $id;
}
$tmpquery = "WHERE cal.owner = '$idSession' AND cal.id = '$dateEnreg'";
$detailCalendar = new request();
$detailCalendar->openCalendar($tmpquery);
$comptDetailCalendar = count($detailCalendar->cal_id);
if ($comptDetailCalendar == "0") {
	headerFunction("../calendar/viewcalendar.php?".session_name()."=".session_id());
}
}

if ($type == "calendEdit") {
$bodyCommand = "onLoad=\"document.calendForm.subject.focus();\"";
}
if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/header.php");
} else {
	include("../themes/".THEME."/header.php");
}

if ($type == "calendEdit") {
if ($id != "") {
	$subject = $detailCalendar->cal_subject[0];
	$description = $detailCalendar->cal_description[0];
	$shortname = $detailCalendar->cal_shortname[0];
	$date_start = $detailCalendar->cal_date_start[0];
	$date_end = $detailCalendar->cal_date_end[0];
	$time_start = $detailCalendar->cal_time_start[0];
	$time_end = $detailCalendar->cal_time_end[0];
	$reminder = $detailCalendar->cal_reminder[0];
	$recurring = $detailCalendar->cal_recurring[0];
	if ($recurring == "1") {
		$checked2_a = "checked"; //true
	}
	if ($reminder == "0") {
		$checked1_b = "checked"; //false
	} else {
		$checked2_b = "checked"; //true
	}
} else {
	$checked2_b = "checked"; //true
}

$blockPage = new block();
$blockPage->openBreadcrumbs();
$blockPage->itemBreadcrumbs($blockPage->buildLink("../calendar/viewcalendar.php?type=monthPreview",$strings["calendar"],in));
$blockPage->itemBreadcrumbs($blockPage->buildLink("../calendar/viewcalendar.php?type=monthPreview&amp;dateCalend=$dateCalend","$monthName $year",in));
$blockPage->itemBreadcrumbs($blockPage->buildLink("../calendar/viewcalendar.php?type=dayList&amp;dateCalend=$dateCalend","$dayName $day $monthName $year",in));

if ($id != "") {
	$blockPage->itemBreadcrumbs($blockPage->buildLink("../calendar/viewcalendar.php?type=calendDetail&amp;dateCalend=$dateCalend&amp;dateEnreg=$dateEnreg",$detailCalendar->cal_shortname[0],in));
	$blockPage->itemBreadcrumbs($strings["edit"]);
} else {
	$blockPage->itemBreadcrumbs($strings["add"]);
}
$blockPage->closeBreadcrumbs();

if ($msg != "") {
if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/includes/messages.php");
} else {
	include("../includes/messages.php");
}
	$blockPage->messagebox($msgLabel);
}

$block1 = new block();

$block1->form = "calend";
if ($id != "") {
$block1->openForm("../calendar/viewcalendar.php?".session_name()."=".session_id()."&amp;dateEnreg=$dateEnreg&amp;dateCalend=$dateCalend&amp;type=$type&amp;action=update#".$block1->form."Anchor");

} else {
$block1->openForm("../calendar/viewcalendar.php?".session_name()."=".session_id()."&amp;dateEnreg=$dateEnreg&amp;dateCalend=$dateCalend&amp;type=$type&amp;action=add#".$block1->form."Anchor");
}

if ($error != "") {            
	$block1->headingError($strings["errors"]);
	$block1->contentError($error);
}


if ($id != "") {
	$block1->heading($strings["edit"].": ".$detailCalendar->cal_shortname[0]);
} else {
	$block1->heading($strings["add"].":");
}

$block1->openContent();
$block1->contentTitle($strings["details"]);

echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["subject"]." :</td><td><input size=\"24\" style=\"width: 250px;\" maxlength=\"128\" type=\"text\" name=\"subject\" value=\"$subject\"></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["description"]." :</td><td><textarea style=\"width: 400px; height: 50px;\" name=\"description\" cols=\"35\" rows=\"2\">$description</textarea></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">* ".$strings["shortname"].$blockPage->printHelp("calendar_shortname")." :</td><td><input size=\"24\" style=\"width: 250px;\" maxlength=\"128\" type=\"text\" name=\"shortname\" value=\"$shortname\"></td></tr>";

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/includes/calendar.php");
} else {
	include("../includes/calendar.php");
}

if ($date_start == "") {
	$date_start = $dateCalend;
}
if ($date_end == "") {
	$date_end = $dateCalend;
}

$block1->contentRow($strings["date_start"],"<input type=\"text\" name=\"dateStart\" id=\"sel1\" size=\"20\" value=\"$date_start\"><input type=\"reset\" value=\" ... \" onclick=\"return showCalendar('sel1', 'y-mm-dd');\">");

$block1->contentRow($strings["date_end"],"<input type=\"text\" name=\"dateEnd\" id=\"sel3\" size=\"20\" value=\"$date_end\"><input type=\"reset\" value=\" ... \" onclick=\"return showCalendar('sel3', 'y-mm-dd');\">");

echo "</td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["time_start"]." :</td><td><input size=\"24\" style=\"width: 250px;\" maxlength=\"128\" type=\"text\" name=\"time_start\" value=\"$time_start\"></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["time_end"]." :</td><td><input size=\"24\" style=\"width: 250px;\" maxlength=\"128\" type=\"text\" name=\"time_end\" value=\"$time_end\"></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["calendar_reminder"]." :</td><td><input type=\"radio\" name=\"reminder\" value=\"0\" $checked1_b> ".$strings["no"]."&nbsp;<input type=\"radio\" name=\"reminder\" value=\"1\" $checked2_b> ".$strings["yes"]."</td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["calendar_recurring"]." :</td><td><input type=\"checkbox\" name=\"recurring\" value=\"1\" $checked2_a></td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">&nbsp;</td><td><input type=\"SUBMIT\" value=\"".$strings["save"]."\"></td></tr>";

$block1->closeContent();
$block1->closeForm();
}

if ($type == "calendDetail") {
$reminder = $detailCalendar->cal_reminder[0];
$recurring = $detailCalendar->cal_recurring[0];
if ($reminder == "0") {
	$reminder = $strings["no"];
} else {
	$reminder = $strings["yes"];
}
if ($recurring == "0") {

	$recurring = $strings["no"];
} else {
	$recurring = $strings["yes"];
}

$blockPage = new block();
$blockPage->openBreadcrumbs();
$blockPage->itemBreadcrumbs($blockPage->buildLink("../calendar/viewcalendar.php?type=monthPreview",$strings["calendar"],in));
$blockPage->itemBreadcrumbs($blockPage->buildLink("../calendar/viewcalendar.php?type=monthPreview&amp;dateCalend=$dateCalend","$monthName $year",in));
$blockPage->itemBreadcrumbs($blockPage->buildLink("../calendar/viewcalendar.php?type=dayList&amp;dateCalend=$dateCalend","$dayName $day $monthName $year",in));
$blockPage->itemBreadcrumbs($detailCalendar->cal_shortname[0]);
$blockPage->closeBreadcrumbs();

if ($msg != "") {
if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/includes/messages.php");
} else {
	include("../includes/messages.php");
}
	$blockPage->messagebox($msgLabel);
}

$block1 = new block();

$block1->form = "calend";
$block1->openForm("../calendar/viewcalendar.php?".session_name()."=".session_id()."#".$block1->form."Anchor");

if ($error != "") {            
	$block1->headingError($strings["errors"]);
	$block1->contentError($error);
}

$block1->heading($detailCalendar->cal_shortname[0]);

$block1->openPaletteIcon();
$block1->paletteIcon(0,"remove",$strings["delete"]);
$block1->paletteIcon(1,"edit",$strings["edit"]);
$block1->paletteIcon(2,"export",$strings["export"]);
$block1->closePaletteIcon();

$block1->openContent();
$block1->contentTitle($strings["details"]);


echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["subject"]." :</td><td>".$detailCalendar->cal_subject[0]."</td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["description"]." :</td><td>".nl2br($detailCalendar->cal_description[0])."&nbsp;</td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["shortname"].$blockPage->printHelp("calendar_shortname")." :</td><td>".$detailCalendar->cal_shortname[0]."&nbsp;</td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["date_start"]." :</td><td>".$detailCalendar->cal_date_start[0]."</td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["date_end"]." :</td><td>".$detailCalendar->cal_date_end[0]."</td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["time_start"]." :</td><td>".$detailCalendar->cal_time_start[0]."</td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["time_end"]." :</td><td>".$detailCalendar->cal_time_end[0]."</td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["calendar_reminder"]." :</td><td>$reminder</td></tr>
<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">".$strings["calendar_recurring"]." :</td><td>$recurring</td></tr>";

$block1->closeContent();
$block1->closeForm();

$block1->openPaletteScript();
$block1->paletteScript(0,"remove","../calendar/deletecalendar.php?id=$dateEnreg","true,true,true",$strings["delete"]);
$block1->paletteScript(1,"edit","../calendar/viewcalendar.php?id=$dateEnreg&type=calendEdit&dateCalend=$dateCalend","true,true,true",$strings["edit"]);
$block1->paletteScript(2,"export","../calendar/exportcalendar.php?id=$dateEnreg","true,true,true",$strings["export"]);
$block1->closePaletteScript("","");
}

$blockPage = new block();

if ($type == "dayList") {
$blockPage->openBreadcrumbs();
$blockPage->itemBreadcrumbs($blockPage->buildLink("../calendar/viewcalendar.php?type=monthPreview",$strings["calendar"],in));
$blockPage->itemBreadcrumbs($blockPage->buildLink("../calendar/viewcalendar.php?type=monthPreview&amp;dateCalend=$dateCalend","$monthName $year",in));
$blockPage->itemBreadcrumbs("$dayName $day $monthName $year");
$blockPage->closeBreadcrumbs();

$block1 = new block();

$block1->form = "calendList";
$block1->openForm("../calendar/viewcalendar.php?type=$type&amp;dateCalend=$dateCalend&amp;".session_name()."=".session_id()."#".$block1->form."Anchor");

$block1->heading("$dayName $day $monthName $year");

$block1->openPaletteIcon();

$block1->paletteIcon(0,"add",$strings["add"]);
$block1->paletteIcon(1,"remove",$strings["delete"]);
$block1->paletteIcon(2,"info",$strings["view"]);
$block1->paletteIcon(3,"edit",$strings["edit"]);

$block1->closePaletteIcon();

$block1->sorting("calendar",$sortingUser->sor_calendar[0],"cal.date_end DESC",$sortingFields = array(0=>"cal.shortname",1=>"cal.subject",2=>"cal.date_start",3=>"cal.date_end"));

$dayRecurr = _dayOfWeek(mktime(12,12,12,$month,$day,$year));
$tmpquery = "WHERE cal.owner = '$idSession' AND ((cal.date_start <= '$dateCalend' AND cal.date_end >= '$dateCalend' AND cal.recurring = '0') OR ((cal.date_start <= '$dateCalend' AND cal.date_end <= '$dateCalend') AND cal.recurring = '1' AND cal.recur_day = '$dayRecurr')) ORDER BY cal.shortname";
//$tmpquery = "WHERE cal.owner = '$idSession' AND cal.date_start <= '$dateCalend' AND cal.date_end >= '$dateCalend' ORDER BY $block1->sortingValue";
$listCalendar = new request();
$listCalendar->openCalendar($tmpquery);
$comptListCalendar = count($listCalendar->cal_id);

if ($comptListCalendar != "0") {
	$block1->openResults();

	$block1->labels($labels = array(0=>$strings["shortname"],1=>$strings["subject"],2=>$strings["date_start"],3=>$strings["date_end"]),"false");

for ($i=0;$i<$comptListCalendar;$i++) {
$block1->openRow();
$block1->checkboxRow($listCalendar->cal_id[$i]);
$block1->cellRow($blockPage->buildLink("../calendar/viewcalendar.php?$dateEnreg=".$listCalendar->cal_id[$i]."&amp;type=calendDetail&amp;dateCalend=$dateCalend",$listCalendar->cal_shortname[$i],in));
$block1->cellRow($listCalendar->cal_subject[$i]);
$block1->cellRow($listCalendar->cal_date_start[$i]);
$block1->cellRow($listCalendar->cal_date_end[$i]);
$block1->closeRow();
}
$block1->closeResults();
} else {
$block1->noresults();
}
$block1->closeFormResults();

$block1->openPaletteScript();
$block1->paletteScript(0,"add","../calendar/viewcalendar.php?dateCalend=$dateCalend&type=calendEdit","true,false,false",$strings["add"]);
$block1->paletteScript(1,"remove","../calendar/deletecalendar.php?","false,true,true",$strings["delete"]);
$block1->paletteScript(2,"info","../calendar/viewcalendar.php?dateCalend=$dateCalend&type=calendDetail","false,true,false",$strings["view"]);
$block1->paletteScript(3,"edit","../calendar/viewcalendar.php?dateCalend=$dateCalend&type=calendEdit","false,true,false",$strings["edit"]);
$block1->closePaletteScript($comptListCalendar,$listCalendar->cal_id);
}

if ($type == "monthPreview") {
$blockPage->openBreadcrumbs();
$blockPage->itemBreadcrumbs($blockPage->buildLink("../calendar/viewcalendar.php?",$strings["calendar"],in));
$blockPage->itemBreadcrumbs("$monthName $year");
$blockPage->closeBreadcrumbs();

$block2 = new block();

$block2->heading("$monthName $year");

echo "<table border=0 cellpadding=0 cellspacing=2 width=100% class=listing><tr>";
for($daynumber = 1; $daynumber < 8; $daynumber++) {
	echo "<td width=14% class=calendDays>&nbsp;$dayNameArray[$daynumber]</td>";
}
echo "</tr>";
//	Print the calendar
echo "<tr>";

$tmpquery = "WHERE tas.assigned_to = '$idSession' ORDER BY tas.name";
$listTasks = new request();
$listTasks->openTasks($tmpquery);
$comptListTasks = count($listTasks->tas_id);
for ($g=0;$g<$comptListTasks;$g++) {
if (substr($listTasks->tas_start_date[$g],0,7) == substr($dateCalend,0,7)) {
$gantt = "true";
}
}
for ($i = 1; $i < $daysmonth + $firstday; $i++) {

	$a = $i - $firstday + 1;
	$day = $i - $firstday + 1;
	if (strlen($a) == 1) {
		$a = "0$a";
	}
			if (strlen($month) == 1) {
		$month = "0$month";
	}
$dateLink = "$year-$month-$a";
$todayClass = "";
$dayRecurr = _dayOfWeek(mktime(12,12,12,$month,$a,$year));
$comptListCalendarScan = "0";
$tmpquery = "WHERE cal.owner = '$idSession' AND ((cal.date_start <= '$dateLink' AND cal.date_end >= '$dateLink' AND cal.recurring = '0') OR ((cal.date_start <= '$dateLink' AND cal.date_end <= '$dateLink') AND cal.recurring = '1' AND cal.recur_day = '$dayRecurr')) ORDER BY cal.shortname";
$listCalendarScan = new request();
$listCalendarScan->openCalendar($tmpquery);
$comptListCalendarScan = count($listCalendarScan->cal_id);

	if (($i < $firstday) || ($a == "00")) { 
			echo "<td width=14% class=even>&nbsp;</td>";
	} else {
if ($dateLink == $dateToday) {
	$classCell = "old";
} else {
	$classCell = "odd";
}

		echo "<td width=14% align=left valign=top class=\"$classCell\" onmouseover=\"this.style.backgroundColor='".$block2->highlightOn."'\" onmouseout=\"this.style.backgroundColor='".$highlightOff."'\"><div align=right>".$blockPage->buildLink("../calendar/viewcalendar.php?dateCalend=$dateLink&amp;type=dayList",$day,in)."</div>";
		if ($comptListCalendarScan != "0") {
		for ($h=0;$h<$comptListCalendarScan;$h++) {
			echo $blockPage->buildLink("../calendar/viewcalendar.php?dateEnreg=".$listCalendarScan->cal_id[$h]."&amp;type=calendDetail&amp;dateCalend=$dateLink",$listCalendarScan->cal_shortname[$h],in)."<br>";
		}
		}
		if ($comptListTasks != "0") {
		for ($h=0;$h<$comptListTasks;$h++) {

		if ($listTasks->tas_start_date[$h] == $dateLink && $listTasks->tas_start_date[$h] != $listTasks->tas_due_date[$h]) {
		echo $strings["task"].": ";
			echo $blockPage->buildLink("../tasks/viewtask.php?id=".$listTasks->tas_id[$h],$listTasks->tas_name[$h],in)." (".$strings["start_date"].")<br>";
		}

		if ($listTasks->tas_due_date[$h] == $dateLink && $listTasks->tas_start_date[$h] != $listTasks->tas_due_date[$h]) {
		echo $strings["task"].": ";
		if ($listTasks->tas_due_date[$h] <= $date && $listTasks->tas_completion[$h] != "10") {
			echo $blockPage->buildLink("../tasks/viewtask.php?id=".$listTasks->tas_id[$h],"<b>".$listTasks->tas_name[$h]."</b>",in)." (".$strings["due_date"].")<br>";
		} else {
			echo $blockPage->buildLink("../tasks/viewtask.php?id=".$listTasks->tas_id[$h],$listTasks->tas_name[$h],in)." (".$strings["due_date"].")<br>";
		}
		}

		if ($listTasks->tas_start_date[$h] == $dateLink && $listTasks->tas_due_date[$h] == $dateLink) {
		echo $strings["task"].": ";
		if ($listTasks->tas_due_date[$h] <= $date && $listTasks->tas_completion[$h] != "10") {
			echo $blockPage->buildLink("../tasks/viewtask.php?id=".$listTasks->tas_id[$h],"<b>".$listTasks->tas_name[$h]."</b>",in)."<br>";
		} else {
			echo $blockPage->buildLink("../tasks/viewtask.php?id=".$listTasks->tas_id[$h],$listTasks->tas_name[$h],in)."<br>";
		}
		}

		}
		}
		if ($comptListTasks == "0" || $comptListCalendarScan == "0") {
			echo "<br>";
		}
echo "</td>";
	}

	if (($i%7) == 0) {
		echo "</tr><tr>\n";
	}
}

if (($i%7) != 1) {
	echo "</tr><tr>\n";
}
	
echo "</table>";

	if ($month == 1) {
		$pyear = $year - 1;
		$pmonth = 12;
	} else {
		$pyear = $year;
		$pmonth = $month - 1;
	}

	if ($month == 12) {
		$nyear = $year + 1;
		$nmonth = 1;
	} else {
		$nyear = $year;
		$nmonth = $month + 1;
	}
	
	$year = date("Y");
	$month = date("n");
	$day = date("j");
	if (strlen($month) == 1) {
		$month = "0$month";
	}
	if (strlen($pmonth) == 1) {
		$pmonth = "0$pmonth";
	}
	if (strlen($nmonth) == 1) {
		$nmonth = "0$nmonth";
	}
	if (strlen($day) == 1) {
		$day= "0$day";
	}
$datePast = "$pyear-$pmonth-01";
$dateNext = "$nyear-$nmonth-01";

$dateToday = "$year-$month-$day";
	echo "<table><tr><td class=calend> </td></tr></table>";

echo "<table cellspacing=\"0\" width=\"100%\" border=\"0\" cellpadding=\"0\"><tr><td nowrap align=\"right\" class=\"footerCell\">".$blockPage->buildLink("../calendar/viewcalendar.php?dateCalend=$datePast",$strings["previous"],in)." | ".$blockPage->buildLink("../calendar/viewcalendar.php?dateCalend=$dateToday",$strings["today"],in)." | ".$blockPage->buildLink("../calendar/viewcalendar.php?dateCalend=$dateNext",$strings["next"],in)."</td></tr><tr><td height=\"5\" colspan=\"2\"><img width=\"1\" height=\"5\" border=\"0\" src=\"../themes/".THEME."/spacer.gif\" alt=\"\"></td></tr></table>";

if ($activeJpgraph == "true" && $gantt == "true") {
echo "<img src=\"graphtasks.php?".session_name()."=".session_id()."&amp;dateCalend=$dateCalend\" alt=\"\"><br>
<span class=\"listEvenBold\">".$blockPage->buildLink("http://www.aditus.nu/jpgraph/","JpGraph",powered)."</span>";
}
}

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>