<?php
#Application name: PhpCollab
#Status page: 2
#Path by root: ../includes/request.class.php

class request {
function request() {
}

function connectClass() {
global $strings,$res,$databaseType;
if ($databaseType == "mysql") {
	$res = mysql_connect(MYSERVER,MYLOGIN,MYPASSWORD) or die($strings["error_server"]);
	mysql_select_db(MYDATABASE,$res) or die($strings["error_database"]);
}
if ($databaseType == "postgresql") {
	$res = pg_connect("host=".MYSERVER." port=5432 dbname=".MYDATABASE." user=".MYLOGIN." password=".MYPASSWORD);
}
if ($databaseType == "sqlserver") {
	$res = mssql_connect(MYSERVER,MYLOGIN,MYPASSWORD) or die($strings["error_server"]);
	mssql_select_db(MYDATABASE,$res) or die($strings["error_database"]);
}
}

function query($sql) {
global $res,$databaseType,$comptRequest;
$comptRequest = $comptRequest + 1;
if ($databaseType == "mysql") {
	$this->index = mysql_query($sql, $res);
}
if ($databaseType == "postgresql") {
	$this->index = pg_query($res, $sql);
}
if ($databaseType == "sqlserver") {
	$this->index = mssql_query($sql, $res);
}
}

function fetch() {
global $row,$databaseType;
if ($databaseType == "mysql") {
	@$row = mysql_fetch_row($this->index);
if (mysql_errno() != 0) {
	echo "<font color=red><b>".mysql_error()."</b></font><br>";
}
}
if ($databaseType == "postgresql") {
	$row = pg_fetch_row($this->index);
}
if ($databaseType == "sqlserver") {
	$row = mssql_fetch_row($this->index);
}
return $row;
}

function close() {
global $res,$databaseType;
if ($databaseType == "mysql") {
	@mysql_free_result($this->index);
	@mysql_close($res);
}
if ($databaseType == "postgresql") {
	@pg_free_result($this->index);
	@pg_close($res);
}
if ($databaseType == "sqlserver") {
	@mssql_free_result($this->index);
	@mssql_close($res);
}
}

//results sorting
function openSorting($querymore,$start="",$rows="") {
global $tableCollab, $strings, $res, $row, $databaseType, $initrequest;
$this->connectClass();
$sql = $initrequest["sorting"];
$sql .= ' '.$querymore;
if ($databaseType == "mysql" && $start != "") {
$sql .= " LIMIT $start,$rows";
}
if ($databaseType == "postgresql" && $start != "") {
$sql .= " LIMIT $rows,$start";
}

$index = $this->query($sql);
while($this->fetch()) {
	$this->sor_id[] = ($row[0]);
	$this->sor_member[] = ($row[1]);
	$this->sor_home_projects[] = ($row[2]);
	$this->sor_home_tasks[] = ($row[3]);
	$this->sor_home_discussions[] = ($row[4]);
	$this->sor_home_reports[] = ($row[5]);
	$this->sor_projects[] = ($row[6]);
	$this->sor_organizations[] = ($row[7]);
	$this->sor_project_tasks[] = ($row[8]);
	$this->sor_discussions[] = ($row[9]);
	$this->sor_project_discussions[] = ($row[10]);
	$this->sor_users[] = ($row[11]);
	$this->sor_team[] = ($row[12]);
	$this->sor_tasks[] = ($row[13]);
	$this->sor_report_tasks[] = ($row[14]);
	$this->sor_assignment[] = ($row[15]);
	$this->sor_reports[] = ($row[16]);
	$this->sor_files[] = ($row[17]);
	$this->sor_organization_projects[] = ($row[18]);
	$this->sor_notes[] = ($row[19]);
	$this->sor_calendar[] = ($row[20]);
	$this->sor_phases[] = ($row[21]);
	$this->sor_support_requests[] = ($row[22]);
	$this->sor_subtasks[] = ($row[23]);
	$this->sor_bookmarks[] = ($row[24]);
}
$this->close();
}

//results calendar
function openCalendar($querymore,$start="",$rows="") {
global $tableCollab, $strings, $res, $row, $databaseType, $initrequest;
$this->connectClass();
$sql = $initrequest["calendar"];
$sql .= ' '.$querymore;
if ($databaseType == "mysql" && $start != "") {
$sql .= " LIMIT $start,$rows";
}
if ($databaseType == "postgresql" && $start != "") {
$sql .= " LIMIT $rows,$start";
}

$index = $this->query($sql);
while($this->fetch()) {
	$this->cal_id[] = ($row[0]);
	$this->cal_owner[] = ($row[1]);
	$this->cal_subject[] = ($row[2]);
	$this->cal_description[] = ($row[3]);
	$this->cal_shortname[] = ($row[4]);
	$this->cal_date_start[] = ($row[5]);
	$this->cal_date_end[] = ($row[6]);
	$this->cal_time_start[] = ($row[7]);
	$this->cal_time_end[] = ($row[8]);
	$this->cal_reminder[] = ($row[9]);
	$this->cal_recurring[] = ($row[10]);
	$this->cal_recur_day[] = ($row[11]);
	$this->cal_mem_email_work[] = ($row[12]);
}
$this->close();
}

//results notes
function openNotes($querymore,$start="",$rows="") {
global $tableCollab, $strings, $res, $row, $databaseType, $initrequest;
$this->connectClass();
$sql = $initrequest["notes"];
$sql .= ' '.$querymore;
if ($databaseType == "mysql" && $start != "") {
$sql .= " LIMIT $start,$rows";
}
if ($databaseType == "postgresql" && $start != "") {
$sql .= " LIMIT $rows,$start";
}

$index = $this->query($sql);
while($this->fetch()) {
	$this->note_id[] = ($row[0]);
	$this->note_project[] = ($row[1]);
	$this->note_owner[] = ($row[2]);
	$this->note_topic[] = ($row[3]);
	$this->note_subject[] = ($row[4]);
	$this->note_description[] = ($row[5]);
	$this->note_date[] = ($row[6]);
	$this->note_published[] = ($row[7]);
	$this->note_mem_id[] = ($row[8]);
	$this->note_mem_login[] = ($row[9]);
	$this->note_mem_name[] = ($row[10]);
	$this->note_mem_email_work[] = ($row[11]);
}
$this->close();
}

//results logs
function openLogs($querymore,$start="",$rows="") {
global $tableCollab, $strings, $res, $row, $databaseType, $initrequest;
$this->connectClass();
$sql = $initrequest["logs"];
$sql .= ' '.$querymore;
if ($databaseType == "mysql" && $start != "") {
$sql .= " LIMIT $start,$rows";
}
if ($databaseType == "postgresql" && $start != "") {
$sql .= " LIMIT $rows,$start";
}

$index = $this->query($sql);
while($this->fetch()) {
	$this->log_id[] = ($row[0]);
	$this->log_login[] = ($row[1]);
	$this->log_password[] = ($row[2]);
	$this->log_ip[] = ($row[3]);
	$this->log_session[] = ($row[4]);
	$this->log_compt[] = ($row[5]);
	$this->log_last_visite[] = ($row[6]);
	$this->log_connected[] = ($row[7]);
	$this->log_mem_profil[] = ($row[8]);
}
$this->close();
}

//results notifications
function openNotifications($querymore,$start="",$rows="") {
global $tableCollab, $strings, $res, $row, $databaseType, $initrequest;
$this->connectClass();
$sql = $initrequest["notifications"];
$sql .= ' '.$querymore;
if ($databaseType == "mysql" && $start != "") {
$sql .= " LIMIT $start,$rows";
}
if ($databaseType == "postgresql" && $start != "") {
$sql .= " LIMIT $rows,$start";
}

$index = $this->query($sql);
while($this->fetch()) {
	$this->not_id[] = ($row[0]);
	$this->not_member[] = ($row[1]);
	$this->not_taskassignment[] = ($row[2]);



	$this->not_removeprojectteam[] = ($row[3]);
	$this->not_addprojectteam[] = ($row[4]);
	$this->not_newtopic[] = ($row[5]);
	$this->not_newpost[] = ($row[6]);
	$this->not_statustaskchange[] = ($row[7]);
	$this->not_prioritytaskchange[] = ($row[8]);
	$this->not_duedatetaskchange[] = ($row[9]);
	$this->not_clientaddtask[] = ($row[10]);
	$this->not_mem_id[] = ($row[11]);
	$this->not_mem_login[] = ($row[12]);
	$this->not_mem_name[] = ($row[13]);
	$this->not_mem_email_work[] = ($row[14]);
	$this->not_mem_organization[] = ($row[15]);
	$this->not_mem_profil[] = ($row[16]);
}
$this->close();
}

//results members
function openMembers($querymore,$start="",$rows="") {
global $tableCollab, $strings, $res, $row, $databaseType, $initrequest;
$this->connectClass();
global $sql;
$sql = $initrequest["members"];
$sql .= ' '.$querymore;
if ($databaseType == "mysql" && $start != "") {
$sql .= " LIMIT $start,$rows";
}
if ($databaseType == "postgresql" && $start != "") {
$sql .= " LIMIT $rows,$start";
}

$index = $this->query($sql);
while($this->fetch()) {
	$this->mem_id[] = ($row[0]);
	$this->mem_organization[] = ($row[1]);
	$this->mem_login[] = ($row[2]);
	$this->mem_password[] = ($row[3]);
	$this->mem_name[] = ($row[4]);
	$this->mem_title[] = ($row[5]);
	$this->mem_email_work[] = ($row[6]);
	$this->mem_email_home[] = ($row[7]);
	$this->mem_phone_work[] = ($row[8]);
	$this->mem_phone_home[] = ($row[9]);
	$this->mem_mobile[] = ($row[10]);
	$this->mem_fax[] = ($row[11]);
	$this->mem_comments[] = ($row[12]);
	$this->mem_profil[] = ($row[13]);
	$this->mem_created[] = ($row[14]);
	$this->mem_logout_time[] = ($row[15]);
	$this->mem_last_page[] = ($row[16]);
	$this->mem_timezone[] = ($row[17]);
	$this->mem_org_name[] = ($row[18]);
	$this->mem_log_connected[] = ($row[19]);
}
$this->close();
}

//results projects
function openProjects($querymore,$start="",$rows="") {
global $tableCollab, $strings, $res, $row, $databaseType, $initrequest;
$this->connectClass();
$sql = $initrequest["projects"];
$sql .= ' '.$querymore;
if ($databaseType == "mysql" && $start != "") {
$sql .= " LIMIT $start,$rows";
}
if ($databaseType == "postgresql" && $start != "") {
$sql .= " LIMIT $rows,$start";
}

$index = $this->query($sql);
while($this->fetch()) {
	$this->pro_id[] = ($row[0]);
	$this->pro_organization[] = ($row[1]);
	$this->pro_owner[] = ($row[2]);
	$this->pro_priority[] = ($row[3]);
	$this->pro_status[] = ($row[4]);
	$this->pro_name[] = ($row[5]);
	$this->pro_description[] = ($row[6]);
	$this->pro_url_dev[] = ($row[7]);
	$this->pro_url_prod[] = ($row[8]);
	$this->pro_created[] = ($row[9]);
	$this->pro_modified[] = ($row[10]);
	$this->pro_published[] = ($row[11]);
	$this->pro_upload_max[] = ($row[12]);
	$this->pro_phase_set[] = ($row[13]);
	$this->pro_org_id[] = ($row[14]);
	$this->pro_org_name[] = ($row[15]);
	$this->pro_mem_id[] = ($row[16]);
	$this->pro_mem_login[] = ($row[17]);
	$this->pro_mem_name[] = ($row[18]);
	$this->pro_mem_email_work[] = ($row[19]);
}
$this->close();
}

//results files
function openFiles($querymore,$start="",$rows="") {
global $tableCollab, $strings, $res, $row, $databaseType, $initrequest;
$this->connectClass();
global $sql;
$sql = $initrequest["files"];
$sql .= ' '.$querymore;
if ($databaseType == "mysql" && $start != "") {
$sql .= " LIMIT $start,$rows";
}
if ($databaseType == "postgresql" && $start != "") {
$sql .= " LIMIT $rows,$start";
}

$index = $this->query($sql);
while($this->fetch()) {
	$this->fil_id[] = ($row[0]);
	$this->fil_owner[] = ($row[1]);
	$this->fil_project[] = ($row[2]);
	$this->fil_task[] = ($row[3]);
	$this->fil_name[] = ($row[4]);
	$this->fil_date[] = ($row[5]);
	$this->fil_size[] = ($row[6]);
	$this->fil_extension[] = ($row[7]);
	$this->fil_comments[] = ($row[8]);
	$this->fil_comments_approval[] = ($row[9]);
	$this->fil_approver[] = ($row[10]);
	$this->fil_date_approval[] = ($row[11]);
	$this->fil_upload[] = ($row[12]);
	$this->fil_published[] = ($row[13]);
	$this->fil_status[] = ($row[14]);
	$this->fil_vc_status[] = ($row[15]);
	$this->fil_vc_version[] = ($row[16]);
	$this->fil_vc_parent[] = ($row[17]);
	$this->fil_phase[] = ($row[18]);
	$this->fil_mem_id[] = ($row[19]);
	$this->fil_mem_login[] = ($row[20]);
	$this->fil_mem_name[] = ($row[21]);
	$this->fil_mem_email_work[] = ($row[22]);
	$this->fil_mem2_id[] = ($row[23]);
	$this->fil_mem2_login[] = ($row[24]);
	$this->fil_mem2_name[] = ($row[25]);
	$this->fil_mem2_email_work[] = ($row[26]);
}
$this->close();
}

//results organizations
function openOrganizations($querymore,$start="",$rows="") {
global $tableCollab, $strings, $res, $row, $databaseType, $initrequest;
$this->connectClass();
$sql = $initrequest["organizations"];
$sql .= ' '.$querymore;
if (($databaseType == "mysql") && $start != "") {
$sql .= " LIMIT $start,$rows";
}
if ($databaseType == "postgresql" && $start != "") {
$sql .= " LIMIT $rows,$start";
}

$index = $this->query($sql);
while($this->fetch()) {
	$this->org_id[] = ($row[0]);
	$this->org_name[] = ($row[1]);
	$this->org_address1[] = ($row[2]);
	$this->org_address2[] = ($row[3]);
	$this->org_zip_code[] = ($row[4]);
	$this->org_city[] = ($row[5]);
	$this->org_country[] = ($row[6]);
	$this->org_phone[] = ($row[7]);
	$this->org_fax[] = ($row[8]);
	$this->org_url[] = ($row[9]);
	$this->org_email[] = ($row[10]);
	$this->org_comments[] = ($row[11]);
	$this->org_created[] = ($row[12]);
	$this->org_extension_logo[] = ($row[13]);
	$this->org_owner[] = ($row[14]);
	$this->org_mem_id[] = ($row[15]);
	$this->org_mem_login[] = ($row[16]);
	$this->org_mem_name[] = ($row[17]);
	$this->org_mem_email_work[] = ($row[18]);
}
$this->close();
}

//results topics
function openTopics($querymore,$start="",$rows="") {
global $tableCollab, $strings, $res, $row, $databaseType, $initrequest;
$this->connectClass();
$sql = $initrequest["topics"];
$sql .= ' '.$querymore;
if ($databaseType == "mysql" && $start != "") {
$sql .= " LIMIT $start,$rows";
}
if ($databaseType == "postgresql" && $start != "") {
$sql .= " LIMIT $rows,$start";
}

$index = $this->query($sql);
while($this->fetch()) {
	$this->top_id[] = ($row[0]);
	$this->top_project[] = ($row[1]);
	$this->top_owner[] = ($row[2]);
	$this->top_subject[] = ($row[3]);
	$this->top_status[] = ($row[4]);
	$this->top_last_post[] = ($row[5]);
	$this->top_posts[] = ($row[6]);
	$this->top_published[] = ($row[7]);
	$this->top_mem_id[] = ($row[8]);
	$this->top_mem_login[] = ($row[9]);
	$this->top_mem_name[] = ($row[10]);
	$this->top_mem_email_work[] = ($row[11]);
	$this->top_pro_id[] = ($row[12]);
	$this->top_pro_name[] = ($row[13]);
}
$this->close();
}

//results posts
function openPosts($querymore,$start="",$rows="") {
global $tableCollab, $strings, $res, $row, $databaseType, $initrequest;
$this->connectClass();
$sql = $initrequest["posts"];
$sql .= ' '.$querymore;
if ($databaseType == "mysql" && $start != "") {
$sql .= " LIMIT $start,$rows";
}
if ($databaseType == "postgresql" && $start != "") {
$sql .= " LIMIT $rows,$start";
}

$index = $this->query($sql);
while($this->fetch()) {
	$this->pos_id[] = ($row[0]);
	$this->pos_topic[] = ($row[1]);
	$this->pos_member[] = ($row[2]);
	$this->pos_created[] = ($row[3]);
	$this->pos_message[] = ($row[4]);
	$this->pos_mem_id[] = ($row[5]);
	$this->pos_mem_login[] = ($row[6]);
	$this->pos_mem_name[] = ($row[7]);
	$this->pos_mem_email_work[] = ($row[8]);
}
$this->close();
}

//results assignments
function openAssignments($querymore,$start="",$rows="") {
global $tableCollab, $strings, $res, $row, $databaseType, $initrequest;
$this->connectClass();
$sql = $initrequest["assignments"];
$sql .= ' '.$querymore;
if ($databaseType == "mysql" && $start != "") {
$sql .= " LIMIT $start,$rows";
}
if ($databaseType == "postgresql" && $start != "") {
$sql .= " LIMIT $rows,$start";
}

$index = $this->query($sql);
while($this->fetch()) {
	$this->ass_id[] = ($row[0]);
	$this->ass_task[] = ($row[1]);
	$this->ass_owner[] = ($row[2]);
	$this->ass_assigned_to[] = ($row[3]);
	$this->ass_comments[] = ($row[4]);
	$this->ass_assigned[] = ($row[5]);
	$this->ass_mem1_id[] = ($row[6]);
	$this->ass_mem1_login[] = ($row[7]);
	$this->ass_mem1_name[] = ($row[8]);
	$this->ass_mem1_email_work[] = ($row[9]);
	$this->ass_mem2_id[] = ($row[10]);
	$this->ass_mem2_login[] = ($row[11]);
	$this->ass_mem2_name[] = ($row[12]);
	$this->ass_mem2_email_work[] = ($row[13]);
}
$this->close();
}

//results reports
function openReports($querymore,$start="",$rows="") {
global $tableCollab, $strings, $res, $row, $databaseType, $initrequest;
$this->connectClass();
$sql = $initrequest["reports"];
$sql .= ' '.$querymore;
if ($databaseType == "mysql" && $start != "") {
$sql .= " LIMIT $start,$rows";
}
if ($databaseType == "postgresql" && $start != "") {
$sql .= " LIMIT $rows,$start";
}

$index = $this->query($sql);
while($this->fetch()) {
	$this->rep_id[] = ($row[0]);
	$this->rep_owner[] = ($row[1]);
	$this->rep_name[] = ($row[2]);
	$this->rep_projects[] = ($row[3]);
	$this->rep_members[] = ($row[4]);
	$this->rep_priorities[] = ($row[5]);
	$this->rep_status[] = ($row[6]);
	$this->rep_date_due_start[] = ($row[7]);
	$this->rep_date_due_end[] = ($row[8]);
	$this->rep_created[] = ($row[9]);
	$this->rep_date_complete_start[] = ($row[10]);
	$this->rep_date_complete_end[] = ($row[11]);
	$this->rep_clients[] = ($row[12]);
}
$this->close();
}

//results teams
function openTeams($querymore,$start="",$rows="") {
global $tableCollab, $strings, $res, $row, $databaseType, $initrequest;
$this->connectClass();
$sql = $initrequest["teams"];
$sql .= ' '.$querymore;
if ($databaseType == "mysql" && $start != "") {
$sql .= " LIMIT $start,$rows";
}
if ($databaseType == "postgresql" && $start != "") {
$sql .= " LIMIT $rows,$start";
}

$index = $this->query($sql);
while($this->fetch()) {
	$this->tea_id[] = ($row["0"]);
	$this->tea_project[] = ($row["1"]);
	$this->tea_member[] = ($row["2"]);
	$this->tea_published[] = ($row["3"]);
	$this->tea_authorized[] = ($row["4"]);
	$this->tea_mem_id[] = ($row[5]);
	$this->tea_mem_login[] = ($row[6]);
	$this->tea_mem_name[] = ($row[7]);
	$this->tea_mem_email_work[] = ($row[8]);
	$this->tea_mem_title[] = ($row[9]);
	$this->tea_mem_phone_work[] = ($row[10]);
	$this->tea_org_name[] = ($row[11]);
	$this->tea_pro_id[] = ($row[12]);
	$this->tea_pro_name[] = ($row[13]);
	$this->tea_pro_priority[] = ($row[14]);
	$this->tea_pro_status[] = ($row[15]);
	$this->tea_pro_published[] = ($row[16]);
	$this->tea_org2_name[] = ($row[17]);
	$this->tea_mem2_login[] = ($row[18]);
	$this->tea_mem2_email_work[] = ($row[19]);
	$this->tea_org2_id[] = ($row[20]);
	$this->tea_log_connected[] = ($row[21]);
	$this->tea_mem_profil[] = ($row[22]);
	$this->tea_mem_password[] = ($row[23]);
}
$this->close();
}

//results tasks
function openTasks($querymore,$start="",$rows="") {
global $tableCollab, $strings, $res, $row, $databaseType, $initrequest;
$this->connectClass();
$sql = $initrequest["tasks"];
$sql .= ' '.$querymore;
if ($databaseType == "mysql" && $start != "") {
$sql .= " LIMIT $start,$rows";
}
if ($databaseType == "postgresql" && $start != "") {
$sql .= " LIMIT $rows,$start";
}

$index = $this->query($sql);
while($this->fetch()) {
	$this->tas_id[] = ($row[0]);
	$this->tas_project[] = ($row[1]);
	$this->tas_priority[] = ($row[2]);
	$this->tas_status[] = ($row[3]);
	$this->tas_owner[] = ($row[4]);
	$this->tas_assigned_to[] = ($row[5]);
	$this->tas_name[] = ($row[6]);
	$this->tas_description[] = ($row[7]);
	$this->tas_start_date[] = ($row[8]);
	$this->tas_due_date[] = ($row[9]);
	$this->tas_estimated_time[] = ($row[10]);
	$this->tas_actual_time[] = ($row[11]);
	$this->tas_comments[] = ($row[12]);
	$this->tas_completion[] = ($row[13]);
	$this->tas_created[] = ($row[14]);
	$this->tas_modified[] = ($row[15]);
	$this->tas_assigned[] = ($row[16]);
	$this->tas_published[] = ($row[17]);
	$this->tas_parent_phase[] = ($row[18]);
	$this->tas_complete_date[] = ($row[19]);
	$this->tas_mem_id[] = ($row[20]);
	$this->tas_mem_name[] = ($row[21]);
	$this->tas_mem_login[] = ($row[22]);
	$this->tas_mem_email_work[] = ($row[23]);
	$this->tas_mem2_id[] = ($row[24]);
	$this->tas_mem2_name[] = ($row[25]);
	$this->tas_mem2_login[] = ($row[26]);
	$this->tas_mem2_email_work[] = ($row[27]);
	$this->tas_mem_organization[] = ($row[28]);
	$this->tas_pro_name[] = ($row[29]);
	$this->tas_org_id[] = ($row[30]);
}
$this->close();
}

//results subtasks
function openSubtasks($querymore,$start="",$rows="") {
global $tableCollab, $strings, $res, $row, $databaseType, $initrequest;
$this->connectClass();
$sql = $initrequest["subtasks"];
$sql .= ' '.$querymore;
if ($databaseType == "mysql" && $start != "") {
$sql .= " LIMIT $start,$rows";
}
if ($databaseType == "postgresql" && $start != "") {
$sql .= " LIMIT $rows,$start";
}

$index = $this->query($sql);
while($this->fetch()) {
	$this->subtas_id[] = ($row[0]);
	$this->subtas_task[] = ($row[1]);
	$this->subtas_priority[] = ($row[2]);
	$this->subtas_status[] = ($row[3]);
	$this->subtas_owner[] = ($row[4]);
	$this->subtas_assigned_to[] = ($row[5]);
	$this->subtas_name[] = ($row[6]);
	$this->subtas_description[] = ($row[7]);
	$this->subtas_start_date[] = ($row[8]);
	$this->subtas_due_date[] = ($row[9]);
	$this->subtas_estimated_time[] = ($row[10]);
	$this->subtas_actual_time[] = ($row[11]);
	$this->subtas_comments[] = ($row[12]);
	$this->subtas_completion[] = ($row[13]);
	$this->subtas_created[] = ($row[14]);
	$this->subtas_modified[] = ($row[15]);
	$this->subtas_assigned[] = ($row[16]);
	$this->subtas_published[] = ($row[17]);




	$this->subtas_complete_date[] = ($row[18]);
	$this->subtas_mem_id[] = ($row[19]);
	$this->subtas_mem_name[] = ($row[20]);
	$this->subtas_mem_login[] = ($row[21]);
	$this->subtas_mem_email_work[] = ($row[22]);
	$this->subtas_mem2_id[] = ($row[23]);
	$this->subtas_mem2_name[] = ($row[24]);
	$this->subtas_mem2_login[] = ($row[25]);
	$this->subtas_mem2_email_work[] = ($row[26]);
	$this->subtas_mem_organization[] = ($row[27]);
	$this->subtas_tas_name[] = ($row[28]);
}
$this->close();
}

//results phases
function openPhases($querymore,$start="",$rows="") {
global $tableCollab, $strings, $res, $row, $databaseType, $initrequest;
$this->connectClass();
$sql = $initrequest["phases"];
$sql .= ' '.$querymore;
if ($databaseType == "mysql" && $start != "") {
$sql .= " LIMIT $start,$rows";
}
if ($databaseType == "postgresql" && $start != "") {
$sql .= " LIMIT $rows,$start";
}

$index = $this->query($sql);
while($this->fetch()) {
	$this->pha_id[] = ($row[0]);
	$this->pha_project_id[] = ($row[1]);
	$this->pha_order_num[] = ($row[2]);
	$this->pha_status[] = ($row[3]);
	$this->pha_name[] = ($row[4]);
	$this->pha_date_start[] = ($row[5]);
	$this->pha_date_end[] = ($row[6]);
	$this->pha_comments[] = ($row[7]);
}
$this->close();
}

//results updates
function openUpdates($querymore,$start="",$rows="") {
global $tableCollab, $strings, $res, $row, $databaseType, $initrequest;
$this->connectClass();
$sql = $initrequest["updates"];
$sql .= ' '.$querymore;
if ($databaseType == "mysql" && $start != "") {
$sql .= " LIMIT $start,$rows";
}
if ($databaseType == "postgresql" && $start != "") {
$sql .= " LIMIT $rows,$start";
}

$index = $this->query($sql);
while($this->fetch()) {
	$this->upd_id[] = ($row[0]);
	$this->upd_type[] = ($row[1]);
	$this->upd_item[] = ($row[2]);
	$this->upd_member[] = ($row[3]);
	$this->upd_comments[] = ($row[4]);
	$this->upd_created[] = ($row[5]);
	$this->upd_mem_id[] = ($row[6]);
	$this->upd_mem_name[] = ($row[7]);
	$this->upd_mem_login[] = ($row[8]);	
	$this->upd_mem_email_work[] = ($row[9]);	
}
$this->close();
}

//results support requests
function openSupportRequests($querymore,$start="",$rows="") {
global $tableCollab, $strings, $res, $row, $databaseType, $initrequest;
$this->connectClass();
$sql = $initrequest["support_requests"];
$sql .= ' '.$querymore;
if ($databaseType == "mysql" && $start != "") {
$sql .= " LIMIT $start,$rows";
}
if ($databaseType == "postgresql" && $start != "") {
$sql .= " LIMIT $rows,$start";
}

$index = $this->query($sql);
while($this->fetch()) {
	$this->sr_id[] = ($row[0]);
	$this->sr_status[] = ($row[1]);
	$this->sr_user[] = ($row[2]);
	$this->sr_priority[] = ($row[3]);
	$this->sr_subject[] = ($row[4]);
	$this->sr_message[] = ($row[5]);
	$this->sr_owner[] = ($row[6]);
	$this->sr_date_open[] = ($row[7]);
	$this->sr_date_close[] = ($row[8]);
	$this->sr_project[] = ($row[9]);
	$this->sr_pro_name[] = ($row[10]);
	$this->sr_mem_name[] = ($row[11]);	
	$this->sr_mem_email_work[] = ($row[12]);	
}
$this->close();
}

//results support posts
function openSupportPosts($querymore,$start="",$rows="") {
global $tableCollab, $strings, $res, $row, $databaseType, $initrequest;
$this->connectClass();
$sql = $initrequest["support_posts"];
$sql .= ' '.$querymore;
if ($databaseType == "mysql" && $start != "") {
$sql .= " LIMIT $start,$rows";
}
if ($databaseType == "postgresql" && $start != "") {
$sql .= " LIMIT $rows,$start";
}

$index = $this->query($sql);
while($this->fetch()) {
	$this->sp_id[] = ($row[0]);
	$this->sp_request_id[] = ($row[1]);
	$this->sp_message[] = ($row[2]);
	$this->sp_date[] = ($row[3]);
	$this->sp_owner[] = ($row[4]);
	$this->sp_project[] = ($row[5]);
	$this->sp_mem_name[] = ($row[6]);
	$this->sp_mem_email_work[] = ($row[7]);
}
$this->close();
}

//results bookmarks
function openBookmarks($querymore,$start="",$rows="") {
global $tableCollab, $strings, $res, $row, $databaseType, $initrequest;
$this->connectClass();
$sql = $initrequest["bookmarks"];
$sql .= ' '.$querymore;
if ($databaseType == "mysql" && $start != "") {
$sql .= " LIMIT $start,$rows";
}
if ($databaseType == "postgresql" && $start != "") {
$sql .= " LIMIT $rows,$start";
}

$index = $this->query($sql);
while($this->fetch()) {
	$this->boo_id[] = ($row[0]);
	$this->boo_owner[] = ($row[1]);
	$this->boo_category[] = ($row[2]);
	$this->boo_name[] = ($row[3]);
	$this->boo_url[] = ($row[4]);
	$this->boo_description[] = ($row[5]);
	$this->boo_shared[] = ($row[6]);
	$this->boo_home[] = ($row[7]);
	$this->boo_comments[] = ($row[8]);
	$this->boo_users[] = ($row[9]);
	$this->boo_created[] = ($row[10]);	
	$this->boo_modified[] = ($row[11]);
	$this->boo_mem_login[] = ($row[12]);	
	$this->boo_mem_email_work[] = ($row[13]);	
	$this->boo_boocat_name[] = ($row[14]);	
}
$this->close();
}

//results bookmarks_categories
function openBookmarksCategories($querymore,$start="",$rows="") {
global $tableCollab, $strings, $res, $row, $databaseType, $initrequest;
$this->connectClass();
$sql = $initrequest["bookmarks_categories"];
$sql .= ' '.$querymore;
if ($databaseType == "mysql" && $start != "") {
$sql .= " LIMIT $start,$rows";
}
if ($databaseType == "postgresql" && $start != "") {
$sql .= " LIMIT $rows,$start";
}

$index = $this->query($sql);
while($this->fetch()) {
	$this->boocat_id[] = ($row[0]);
	$this->boocat_name[] = ($row[1]);
	$this->boocat_description[] = ($row[2]);
}
$this->close();
}

}
?>