<?php
#Application name: PhpCollab
#Status page: 0
#Path by root: ../administration/phpmyadmin.php

$checkSession = "true";
if (file_exists("modules/PhpCollab/pnversion.php")) {
	$postnukeIntegration = "true";
	include("modules/PhpCollab/includes/library.php");
} else {
	$postnukeIntegration = "false";
	include("../includes/library.php");
}

if ($profilSession != "0") {
	headerFunction("../general/permissiondenied.php?".session_name()."=".session_id());
	exit;
}

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/header.php");
} else {
	include("../themes/".THEME."/header.php");
}

$blockPage = new block();
$blockPage->openBreadcrumbs();
$blockPage->itemBreadcrumbs($blockPage->buildLink("../administration/admin.php?",$strings["administration"],in));
$blockPage->itemBreadcrumbs($strings["database"]." ".MYDATABASE);
$blockPage->closeBreadcrumbs();

$block1 = new block();
$block1->heading($strings["database"]." ".MYDATABASE);

$block1->openContent();
$block1->contentTitle("Backup database");

echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">&nbsp;</td><td>
<script src=\"../includes/phpmyadmin/functions.js\" type=\"text/javascript\" language=\"javascript\"></script>
       <form method=\"post\" action=\"../includes/phpmyadmin/tbl_dump.php\" name=\"db_dump\">
        <table>
        <tr>
    
            <td>
                <select name=\"table_select[]\" size=\"5\" multiple=\"multiple\">
                    <option value=\"".$tableCollab["assignments"]."\" selected>assignments</option>
                    <option value=\"".$tableCollab["calendar"]."\" selected>calendar</option>
                    <option value=\"".$tableCollab["files"]."\" selected>files</option>
                    <option value=\"".$tableCollab["logs"]."\" selected>logs</option>
                    <option value=\"".$tableCollab["members"]."\" selected>members</option>
                    <option value=\"".$tableCollab["notes"]."\" selected>notes</option>
                    <option value=\"".$tableCollab["notifications"]."\" selected>notifications</option>
                    <option value=\"".$tableCollab["organizations"]."\" selected>organizations</option>
                    <option value=\"".$tableCollab["posts"]."\" selected>posts</option>
                    <option value=\"".$tableCollab["projects"]."\" selected>projects</option>
                    <option value=\"".$tableCollab["reports"]."\" selected>reports</option>
                    <option value=\"".$tableCollab["sorting"]."\" selected>sorting</option>
                    <option value=\"".$tableCollab["tasks"]."\" selected>tasks</option>
                    <option value=\"".$tableCollab["teams"]."\" selected>teams</option>
                    <option value=\"".$tableCollab["topics"]."\" selected>topics</option>
                    <option value=\"".$tableCollab["phases"]."\" selected>phases</option>
                    <option value=\"".$tableCollab["support_requests"]."\" selected>support_requests</option>
                    <option value=\"".$tableCollab["support_posts"]."\" selected>support_posts</option>
                    <option value=\"".$tableCollab["subtasks"]."\" selected>subtasks</option>
                    <option value=\"".$tableCollab["bookmarks"]."\" selected>bookmarks</option>
                    <option value=\"".$tableCollab["bookmarks_categories"]."\" selected>bookmarks_categories</option>
                </select>
            </td>
        
            <td valign=\"middle\">
                <input type=\"radio\" name=\"what\" value=\"structure\" />
                Structure only<br />
                <input type=\"radio\" name=\"what\" value=\"data\" checked=\"checked\" />
                Structure and data<br />
                <input type=\"radio\" name=\"what\" value=\"dataonly\" />
                Data only
            </td>
        </tr>
        <tr>
            <td colspan=\"2\">
                <input type=\"checkbox\" name=\"drop\" value=\"1\" checked=\"checked\" />
                Add 'drop table'
            </td>
        </tr>
        <tr>
            <td colspan=\"2\">
                <input type=\"checkbox\" name=\"showcolumns\" value=\"yes\" />
                Complete inserts
            </td>
        </tr>
        <tr>
            <td colspan=\"2\">
                <input type=\"checkbox\" name=\"extended_ins\" value=\"yes\" />
                Extended inserts
            </td>

        </tr>
            <tr>
            <td colspan=\"2\">
                <input type=\"checkbox\" name=\"use_backquotes\" value=\"1\" />
                Use backquotes with tables and fields' names
            </td>
        </tr>
        
        <tr>
            <td colspan=\"2\">
                <input type=\"checkbox\" name=\"asfile\" value=\"sendit\" onclick=\"return checkTransmitDump(this.form, 'transmit')\" checked=\"checked\" />
                Save as file
    
                (
                <input type=\"checkbox\" name=\"zip\" value=\"zip\" onclick=\"return checkTransmitDump(this.form, 'zip')\" />\"zipped\"&nbsp;
                
                <input type=\"checkbox\" name=\"gzip\" value=\"gzip\" onclick=\"return checkTransmitDump(this.form, 'gzip')\" />\"gzipped\"
                
                )
            </td>
        </tr>
        <tr>
            <td colspan=\"2\">
                <input type=\"submit\" value=\"Go\" />
            </td>
        </tr>
        </table>
        <input type=\"hidden\" name=\"server\" value=\"1\" />
        <input type=\"hidden\" name=\"lang\" value=\"en\" />
        <input type=\"hidden\" name=\"db\" value=\"".MYDATABASE."\" />
        </form>
".$blockPage->buildLink("http://phpwizard.net/projects/phpMyAdmin","phpMyAdmin",powered)."</a></td></tr>";

$block1->contentTitle("Restore database from sql file");

echo "<tr class=\"odd\"><td valign=\"top\" class=\"leftvalue\">&nbsp;</td><td>
        <form method=\"post\" action=\"../includes/phpmyadmin/read_dump.php\" enctype=\"multipart/form-data\">
            <input type=\"hidden\" name=\"is_js_confirmed\" value=\"0\" />
            <input type=\"hidden\" name=\"lang\" value=\"en\" />
            <input type=\"hidden\" name=\"server\" value=\"1\" />
            <input type=\"hidden\" name=\"db\" value=\"".MYDATABASE."\" />
            <input type=\"hidden\" name=\"pos\" value=\"0\" />
            <input type=\"hidden\" name=\"goto\" value=\"db_details.php\" />
            <input type=\"hidden\" name=\"zero_rows\" value=\"Your SQL-query has been executed successfully\" />
            <input type=\"hidden\" name=\"prev_sql_query\" value=\"\" /><br />
            Location of sql file&nbsp;:<br />
            <div style=\"margin-bottom: 5px\">
            <input type=\"file\" name=\"sql_file\" /><br />
            </div>
    
            <input type=\"submit\" name=\"SQL\" value=\"Go\" />
        </form>
".$blockPage->buildLink("http://phpwizard.net/projects/phpMyAdmin","phpMyAdmin",powered)."</a>
</td></tr>";

$block1->closeContent();

if (file_exists("modules/PhpCollab/pnversion.php")) {
	include("modules/PhpCollab/themes/".THEME."/footer.php");
} else {
	include("../themes/".THEME."/footer.php");
}
?>