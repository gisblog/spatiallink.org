<?php
#Application name: PhpCollab
#Status page: 2
#Path by root: ../includes/update_db.php

# PhpCollab 1.0 / 2002-04-07
if ($dumpVersion["1.0"] == "1") {
$SQL[] = <<<STAMP
UPDATE {$tablePrefix}projects SET organization='1' WHERE organization='0';
STAMP;
}

# PhpCollab 1.1 / 2002-04-21
if ($dumpVersion["1.1"] == "1") {
$SQL[] = <<<STAMP
ALTER TABLE {$tablePrefix}organizations ADD email $db_varchar155[$databaseType] AFTER url;
STAMP;
$SQL[] = <<<STAMP
ALTER TABLE {$tablePrefix}projects ADD upload_max $db_varchar155[$databaseType];
STAMP;
}

# PhpCollab 1.3 / 2002-05-11
if ($dumpVersion["1.3"] == "1") {
$SQL[] = <<<STAMP
ALTER TABLE {$tablePrefix}tasks ADD start_date $db_varchar10[$databaseType] AFTER description;
STAMP;
$SQL[] = <<<STAMP
ALTER TABLE {$tablePrefix}tasks ADD completion $db_mediumint[$databaseType] AFTER comments;
STAMP;
}

# PhpCollab 1.4 / 2002-06-02
if ($dumpVersion["1.4"] == "1") {
$SQL[] = <<<STAMP
ALTER TABLE {$tablePrefix}files ADD approver $db_mediumint[$databaseType] AFTER comments_approval;
STAMP;
$SQL[] = <<<STAMP
ALTER TABLE {$tablePrefix}files ADD date_approval $db_varchar16[$databaseType] AFTER approver;
STAMP;
}

# PhpCollab 1.6 / 2002-07-06
if ($dumpVersion["1.6"] == "1" || $action == "printUpdate") {
if ($action == "printUpdate") {
$SQL[] = <<<STAMP
<br><b>1.6</b>
STAMP;
}
$SQL[] = <<<STAMP
ALTER TABLE {$tablePrefix}projects ADD url_dev $db_varchar255[$databaseType] AFTER description;
STAMP;
$SQL[] = <<<STAMP
ALTER TABLE {$tablePrefix}projects ADD url_prod $db_varchar255[$databaseType] AFTER url_dev;
STAMP;
$SQL[] = <<<STAMP
CREATE TABLE {$tablePrefix}notes (
  id $db_mediumint_auto[$databaseType],
  project $db_mediumint[$databaseType],
  owner $db_mediumint[$databaseType],
  topic $db_varchar255[$databaseType],
  subject $db_varchar255[$databaseType],
  description $db_text[$databaseType],
  date $db_varchar10[$databaseType],
  published $db_char1[$databaseType],
  PRIMARY KEY (id)
)
STAMP;
$SQL[] = <<<STAMP
ALTER TABLE {$tablePrefix}sorting ADD notes $db_varchar155[$databaseType];
STAMP;
$SQL[] = <<<STAMP
ALTER TABLE {$tablePrefix}files ADD vc_status $db_varchar255a[$databaseType] AFTER status;
STAMP;
$SQL[] = <<<STAMP
ALTER TABLE {$tablePrefix}files ADD vc_version $db_varchar255b[$databaseType] AFTER vc_status;
STAMP;
$SQL[] = <<<STAMP
ALTER TABLE {$tablePrefix}files ADD vc_parent $db_int[$databaseType] AFTER vc_version;
STAMP;
$SQL[] = <<<STAMP
UPDATE {$tablePrefix}sorting SET home_discussions='',discussions='',project_discussions='';
STAMP;
}

# PhpCollab 1.8 / 2002-07-31
if ($dumpVersion["1.8"] == "1" || $action == "printUpdate") {
if ($action == "printUpdate") {
$SQL[] = <<<STAMP
<br><b>1.8</b>
STAMP;
}
$SQL[] = <<<STAMP
ALTER TABLE {$tablePrefix}sorting ADD calendar $db_varchar155[$databaseType];
STAMP;

$SQL[] = <<<STAMP
CREATE TABLE {$tablePrefix}calendar (
  id $db_mediumint_auto[$databaseType],
  owner $db_mediumint[$databaseType],
  subject $db_varchar155[$databaseType],
  description $db_text[$databaseType],
  shortname $db_varchar155[$databaseType],
  date_start $db_varchar10[$databaseType],
  date_end $db_varchar10[$databaseType],
  time_start $db_varchar155[$databaseType],
  time_end $db_varchar155[$databaseType],
  reminder $db_char1[$databaseType],
  recurring $db_char1[$databaseType],
  recur_day $db_char1[$databaseType],
  PRIMARY KEY (id)
)
STAMP;
}

# PhpCollab 1.9 / 2002-09-01
if ($dumpVersion["1.9"] == "1" || $action == "printUpdate") {
if ($action == "printUpdate") {
$SQL[] = <<<STAMP
<br><b>1.9</b>
STAMP;
}
$SQL[] = <<<STAMP
CREATE TABLE {$tablePrefix}phases (
  id $db_mediumint_auto[$databaseType],
  project_id $db_mediumint[$databaseType],
  order_num $db_mediumint[$databaseType],
  status $db_char1default0[$databaseType],
  name $db_varchar155[$databaseType],
  date_start $db_varchar10[$databaseType],
  date_end $db_varchar10[$databaseType],
  comments $db_varchar255[$databaseType],
  PRIMARY KEY (id)
)
STAMP;
$SQL[] = <<<STAMP
CREATE TABLE {$tablePrefix}support_posts (
  id $db_mediumint_auto[$databaseType],
  request_id $db_mediumint[$databaseType],
  message $db_text[$databaseType],
  date $db_varchar16[$databaseType],
  owner $db_mediumint[$databaseType],
  project $db_mediumint[$databaseType],
  PRIMARY KEY (id)
)
STAMP;
$SQL[] = <<<STAMP
CREATE TABLE {$tablePrefix}support_requests (
  id $db_mediumint_auto[$databaseType],
  status $db_mediumint[$databaseType],
  member $db_mediumint[$databaseType],
  priority $db_mediumint[$databaseType],
  subject $db_varchar255[$databaseType],
  message $db_text[$databaseType],
  owner $db_mediumint[$databaseType],
  date_open $db_varchar16[$databaseType],
  date_close $db_varchar16[$databaseType],
  project $db_mediumint[$databaseType],
  PRIMARY KEY (id)
)
STAMP;
$SQL[] = <<<STAMP
ALTER TABLE {$tablePrefix}sorting ADD phases $db_varchar155[$databaseType];
STAMP;
$SQL[] = <<<STAMP
ALTER TABLE {$tablePrefix}sorting ADD support_requests $db_varchar155[$databaseType];
STAMP;
$SQL[] = <<<STAMP
ALTER TABLE {$tablePrefix}projects ADD phase_set $db_mediumint[$databaseType];
STAMP;
$SQL[] = <<<STAMP
ALTER TABLE {$tablePrefix}tasks ADD parent_phase $db_int[$databaseType];
STAMP;
$SQL[] = <<<STAMP
ALTER TABLE {$tablePrefix}files ADD phase $db_mediumint[$databaseType];
STAMP;
if ($databaseType == "mysql") {
$SQL[] = <<<STAMP
ALTER TABLE {$tablePrefix}reports CHANGE start_date date_due_start $db_varchar10[$databaseType];
STAMP;
$SQL[] = <<<STAMP
ALTER TABLE {$tablePrefix}reports CHANGE end_date date_due_end $db_varchar10[$databaseType];
STAMP;
}
if ($databaseType == "sqlserver") {
$SQL[] = <<<STAMP
sp_rename N'dbo.{$tablePrefix}reports.start_date', N'date_due_start', 'COLUMN'
STAMP;
$SQL[] = <<<STAMP
sp_rename N'dbo.{$tablePrefix}reports.end_date', N'date_due_end', 'COLUMN'
STAMP;
}
$SQL[] = <<<STAMP
ALTER TABLE {$tablePrefix}reports ADD date_complete_start $db_varchar10[$databaseType];
STAMP;
$SQL[] = <<<STAMP
ALTER TABLE {$tablePrefix}reports ADD date_complete_end $db_varchar10[$databaseType];
STAMP;
$SQL[] = <<<STAMP
ALTER TABLE {$tablePrefix}notifications ADD clientAddTask $db_char1default0[$databaseType];
STAMP;
$SQL[] = <<<STAMP
UPDATE {$tablePrefix}notifications SET clientAddTask='0';
STAMP;
$SQL[] = <<<STAMP
ALTER TABLE {$tablePrefix}organizations ADD extension_logo $db_char3[$databaseType];
STAMP;
$SQL[] = <<<STAMP
ALTER TABLE {$tablePrefix}tasks ADD complete_date $db_varchar10[$databaseType];
STAMP;
}

# PhpCollab 2.0 / 2002-09-29
if ($dumpVersion["2.0"] == "1" || $action == "printUpdate") {
if ($action == "printUpdate") {
$SQL[] = <<<STAMP
<br><b>2.0</b>
STAMP;
}
$SQL[] = <<<STAMP
CREATE TABLE {$tablePrefix}updates (
  id $db_mediumint_auto[$databaseType],
  type $db_char1[$databaseType],
  item $db_mediumint[$databaseType],
  member $db_mediumint[$databaseType],
  comments $db_text[$databaseType],
  created $db_varchar16[$databaseType],
  PRIMARY KEY (id)
)
STAMP;
$SQL[] = <<<STAMP
ALTER TABLE {$tablePrefix}organizations ADD owner $db_mediumint[$databaseType];
STAMP;
$SQL[] = <<<STAMP
ALTER TABLE {$tablePrefix}sorting ADD subtasks $db_varchar155[$databaseType];
STAMP;
$SQL[] = <<<STAMP
ALTER TABLE {$tablePrefix}assignments ADD subtask $db_mediumint[$databaseType];
STAMP;
$SQL[] = <<<STAMP
CREATE TABLE {$tablePrefix}subtasks (
  id $db_mediumint_auto[$databaseType],
  task $db_mediumint[$databaseType],
  priority $db_mediumint[$databaseType],
  status $db_mediumint[$databaseType],
  owner $db_mediumint[$databaseType],
  assigned_to $db_mediumint[$databaseType],
  name $db_varchar155[$databaseType],
  description $db_text[$databaseType],
  start_date $db_varchar10[$databaseType],
  due_date $db_varchar10[$databaseType],
  estimated_time $db_varchar10[$databaseType],
  actual_time $db_varchar10[$databaseType],
  comments $db_text[$databaseType],
  completion $db_mediumint[$databaseType],
  created $db_varchar16[$databaseType],
  modified $db_varchar16[$databaseType],
  assigned $db_varchar16[$databaseType],
  published $db_char1[$databaseType],
  complete_date $db_varchar10[$databaseType],
  PRIMARY KEY (id)
)
STAMP;
}

# PhpCollab 2.1 / 2002-10-13
if ($dumpVersion["2.1"] == "1" || $action == "printUpdate") {
if ($action == "printUpdate") {
$SQL[] = <<<STAMP
<br><b>2.1</b>
STAMP;
}
$SQL[] = <<<STAMP
ALTER TABLE {$tablePrefix}reports ADD clients $db_varchar255[$databaseType];
STAMP;
$SQL[] = <<<STAMP
ALTER TABLE {$tablePrefix}members ADD timezone $db_char3[$databaseType];
STAMP;
$SQL[] = <<<STAMP
ALTER TABLE {$tablePrefix}sorting ADD bookmarks $db_varchar155[$databaseType];
STAMP;
$SQL[] = <<<STAMP
CREATE TABLE {$tablePrefix}bookmarks (
  id $db_mediumint_auto[$databaseType],
  owner $db_mediumint[$databaseType],
  category $db_mediumint[$databaseType],
  name $db_varchar255[$databaseType],
  url $db_varchar255[$databaseType],
  description $db_text[$databaseType],
  shared $db_char1[$databaseType],
  home $db_char1[$databaseType],
  comments $db_char1[$databaseType],
  users $db_varchar255[$databaseType],
  created $db_varchar16[$databaseType],
  modified $db_varchar16[$databaseType],
  PRIMARY KEY (id)
)
STAMP;
$SQL[] = <<<STAMP
CREATE TABLE {$tablePrefix}bookmarks_categories (
  id $db_mediumint_auto[$databaseType],
  name $db_varchar255[$databaseType],
  description $db_text[$databaseType],
  PRIMARY KEY (id)
)
STAMP;
$SQL[] = <<<STAMP
UPDATE {$tablePrefix}projects SET phase_set='0' WHERE phase_set='';
STAMP;
}
?>