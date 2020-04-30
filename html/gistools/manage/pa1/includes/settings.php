<?php
#Application name: PhpCollab
#Status page: 2
#Path by root: ../includes/settings.php

# installation type
$installationType = "online"; //select "offline" or "online"

# select database application
$databaseType = "mysql"; //select "sqlserver", "postgresql" or "mysql"

# database parameters
define('MYSERVER','spatiallink.db.3881957.hostedresource.com'); # mysql19.secureserver.net
define('MYLOGIN','spatiallink');
define('MYPASSWORD','XSQLhvp246#');
define('MYDATABASE','spatiallink');

# create folder method
$mkdirMethod = "PHP"; //select "FTP" or "PHP"

# ftp parameters (only if $mkdirMethod == "FTP")
define('FTPSERVER','');
define('FTPLOGIN','');
define('FTPPASSWORD','');

# PhpCollab root according to ftp account (only if $mkdirMethod == "FTP")
$ftpRoot = ""; //no slash at the end

# theme choice
define('THEME','default');

# Xoops integration
// Should Xoops integration be enabled?
$xoopsIntegration = "false";

// Xoops full path
define('XOOPS_ROOT_PATH','D:/wwwroot/xoops/html');

# session.trans_sid forced
$trans_sid = "true";

# timezone GMT management
$gmtTimezone = "false";

# language choice
$langDefault = "en";

# Mantis bug tracking parameters
// Should bug tracking be enabled?
$enableMantis = "false";

// Mantis installation directory
$pathMantis = "http://localhost/mantis/";  // add slash at the end

# CVS parameters
// Should CVS be enabled?
$enable_cvs = "false";

// Should browsing CVS be limited to project members?
$cvs_protected = "false";

// Define where CVS repositories should be stored
$cvs_root = "D:\cvs"; //no slash at the end

// Who is the owner CVS files?
// Note that this should be user that runs the web server.
// Most *nix systems use "httpd" or "nobody"
$cvs_owner = "httpd";

// CVS related commands
$cvs_co = "/usr/bin/co";
$cvs_rlog = "/usr/bin/rlog";
$cvs_cmd = "/usr/bin/cvs";

# https related parameters
$pathToOpenssl = "/usr/bin/openssl";

# login method, set to "CRYPT" in order CVS authentication to work (if CVS support is enabled)
$loginMethod = "MD5"; //select "MD5", "CRYPT", or "PLAIN"

# enable LDAP
$useLDAP = "false";
$configLDAP[ldapserver] = "your.ldap.server.address";
$configLDAP[searchroot] = "ou=People, ou=Intranet, dc=YourCompany, dc=com";

# htaccess parameters
$htaccessAuth = "false";
$fullPath = "/usr/local/apache/htdocs/phpcollab/files"; //no slash at the end

# file management parameters
$fileManagement = "true";
$maxFileSize = 51200; //bytes limit for upload
$root = "http://www.spatiallink.org/gistools/manage/pa1"; //no slash at the end

# security issue to disallow php files upload
$allowPhp = "false";

# project site creation
$sitePublish = "true";

# enable update checker
$updateChecker = "true";

# e-mail notifications
$notifications = "true";

# show peer review area
$peerReview = "true";

# security issue to disallow auto-login from external link
$forcedLogin = "true";

# table prefix
$tablePrefix = "wms_pa1_";

# database tables
$tableCollab["assignments"] = "wms_pa1_assignments";
$tableCollab["calendar"] = "wms_pa1_calendar";
$tableCollab["files"] = "wms_pa1_files";
$tableCollab["logs"] = "wms_pa1_logs";
$tableCollab["members"] = "wms_pa1_members";
$tableCollab["notes"] = "wms_pa1_notes";
$tableCollab["notifications"] = "wms_pa1_notifications";
$tableCollab["organizations"] = "wms_pa1_organizations";
$tableCollab["posts"] = "wms_pa1_posts";
$tableCollab["projects"] = "wms_pa1_projects";
$tableCollab["reports"] = "wms_pa1_reports";
$tableCollab["sorting"] = "wms_pa1_sorting";
$tableCollab["tasks"] = "wms_pa1_tasks";
$tableCollab["teams"] = "wms_pa1_teams";
$tableCollab["topics"] = "wms_pa1_topics";
$tableCollab["phases"] = "wms_pa1_phases";
$tableCollab["support_requests"] = "wms_pa1_support_requests";
$tableCollab["support_posts"] = "wms_pa1_support_posts";
$tableCollab["subtasks"] = "wms_pa1_subtasks";
$tableCollab["updates"] = "wms_pa1_updates";
$tableCollab["bookmarks"] = "wms_pa1_bookmarks";
$tableCollab["bookmarks_categories"] = "wms_pa1_bookmarks_categories";

# PhpCollab version
$version = "2.4";
$versionOld = "2.4";

# demo mode parameters
$demoMode = "false";
$urlContact = "http://www.sourceforge.net/projects/phpcollab";

# Gantt graphs
$activeJpgraph = "true";

# developement options in footer
$footerDev = "false";

# filter to see only logged user clients (in team / owner)
$clientsFilter = "false";

# filter to see only logged user projects (in team / owner)
$projectsFilter = "false";

# Enable help center support requests, values "true" or "false"
$enableHelpSupport = "true";

# Return email address given for clients to respond too.
$supportEmail = "email@yourdomain.com";

# Support Type, either team or admin. If team is selected a notification will be sent to everyone in the team when a new request is added
$supportType = "team";

# html header parameters
$setDoctype = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">";
$setTitle = "PhpCollab";
$setDescription = "Groupware module. Manage web projects with team collaboration, users management, tasks and projects tracking, files approval tracking, project sites clients access, customer relationship management (Php / Mysql, PostgreSQL or Sql Server).";
$setKeywords = "PhpCollab, phpcollab.com, Sourceforge, management, web, projects, tasks, organizations, reports, Php, MySql, Sql Server, mssql, Microsoft Sql Server, PostgreSQL, module, application, module, file management, project site, team collaboration, free, crm, CRM, cutomer relationship management, workflow, workgroup";
?>