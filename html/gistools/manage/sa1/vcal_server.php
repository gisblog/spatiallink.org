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


// SugarCRM free/busy server
// put and get free/busy information for sugarcrm users in vCalendar format. 
// Uses WebDAV for HTTP PUT and GET methods of access
// REQUIRED PHP packages: 
// 1. PEAR
//
// Saves PUTs as Freebusy SugarBeans
//
// documentation on Free/Busy from Microsoft:
// http://support.microsoft.com/kb/196484
//
// other docs:
// http://www.windowsitpro.com/MicrosoftExchangeOutlook/Article/ArticleID/7697/7697.html
//
// excerpt:
// You must install the Microsoft Internet Explorer (IE) Web Publishing Wizard to get 
// the functionality you need to publish Internet free/busy data to a server or the Web. 
// You can install this wizard from Control Panel, Add/Remove Programs, Microsoft Internet 
// Explorer, Web Publishing Wizard. For every user, you must configure the path and filename
// where you want Outlook to store free/busy information. You configure this location on the 
// Free/Busy Options dialog box you see in Screen 2. You must initiate publishing manually by 
// using Tools, Send/Receive, Free/Busy Information in Outlook.
//
// To access a non-Exchange Server user's free/busy information, you must configure the 
// appropriate path on each contact's Details tab. For example, you enter 
// "http://servername/sugarcrm/vcal_server.php/type=vfb&source=outlook&email=myemail@servername.com". 
// If you don't configure this information correctly, the client software looks up the entry 
// in the Search at this URL window on the Free/Busy Options dialog box. 
//
// Setup for: Microsoft Outlook XP
// In Tools > Options > Calendar Options > Free/Busy
// 
// Global search path:
// %USERNAME% and %SERVER% are Outlook replacement variables to construct the email address:
// http://servername/sugarcrm/vcal_server.php/type=vfb&source=outlook&email=%NAME%@%SERVER%
// or contact by contact by editing the details and entering its Free/Busy URL:
// http://servername/sugarcrm/vcal_server.php/type=vfb&source=outlook&email=user@email.com
// or
// http://servername/sugarcrm/vcal_server.php/type=vfb&source=outlook&user_name=user_name
// or:
// http://servername/sugarcrm/vcal_server.php/type=vfb&source=outlook&user_id=user_id

   require_once "modules/vCals/HTTP_WebDAV_Server_vCal.php";
   $server = new HTTP_WebDAV_Server_vCal();
   $server->ServeRequest();
?>
