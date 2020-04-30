<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version
 * 1.1.3 ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an "AS IS" basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied.  See the License
 * for the specific language governing rights and limitations under the
 * License.
 *
 * All copies of the Covered Code must include on each user interface screen:
 *    (i) the "Powered by SugarCRM" logo and
 *    (ii) the SugarCRM copyright notice
 * in the same form as they appear in the distribution.  See full license for
 * requirements.
 *
 * The Original Code is: SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) 2004 SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Id: Menu.php,v 1.42.2.1 2005/05/02 22:05:19 robert Exp $
 * Description:  TODO To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/


global $mod_strings;
$module_menu = Array(
        Array("index.php?module=Emails&action=EditView&type=out&return_module=Emails&return_action=DetailView", $mod_strings['LNK_NEW_SEND_EMAIL'],"CreateEmails", 'Emails'),
        Array("index.php?module=Emails&action=EditView&type=archived&return_module=Emails&return_action=DetailView", $mod_strings['LNK_NEW_ARCHIVE_EMAIL'],"CreateEmails", 'Emails'),

	Array("index.php?module=EmailTemplates&action=EditView&return_module=EmailTemplates&return_action=DetailView", $mod_strings['LNK_NEW_EMAIL_TEMPLATE'],"CreateEmails","Emails"),

        Array("index.php?module=Emails&action=ListViewDrafts", $mod_strings['LNK_DRAFTS_EMAIL_LIST'],"EmailFolder", 'Emails'),
        Array("index.php?module=Emails&action=index", $mod_strings['LNK_ALL_EMAIL_LIST'],"EmailFolder","Emails"),

        Array("index.php?module=EmailTemplates&action=index", $mod_strings['LNK_EMAIL_TEMPLATE_LIST'],"EmailFolder", 'Emails'),


        Array("index.php?module=Calendar&action=index&view=day", $mod_strings['LNK_VIEW_CALENDAR'],"Calendar", 'Emails'),
        );


?>
