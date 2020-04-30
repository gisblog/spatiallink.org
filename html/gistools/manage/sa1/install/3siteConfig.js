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
// $Id: 3siteConfig.js,v 1.5 2005/04/26 00:55:13 bob Exp $

function toggleSiteDefaults(){
   var theForm = document.forms[0];
   var elem = document.getElementById('setup_site_session');

   if( theForm.setup_site_defaults.checked ){
      document.getElementById('setup_site_session_section_pre').style.display = 'none';
      document.getElementById('setup_site_session_section').style.display = 'none';
      document.getElementById('setup_site_log_dir_pre').style.display = 'none';
      document.getElementById('setup_site_log_dir').style.display = 'none';
      document.getElementById('setup_site_guid_section_pre').style.display = 'none';
      document.getElementById('setup_site_guid_section').style.display = 'none';
   }
   else {
      document.getElementById('setup_site_session_section_pre').style.display = '';
      document.getElementById('setup_site_log_dir_pre').style.display = '';
      document.getElementById('setup_site_guid_section_pre').style.display = '';
      toggleSession();
      toggleGUID();
   }
}

function toggleSession(){
   var theForm = document.forms[0];
   var elem = document.getElementById('setup_site_session_section');

   if( theForm.setup_site_custom_session_path.checked ){
      elem.style.display = '';
   }
   else {
      elem.style.display = 'none';
   }
}

function toggleLogDir(){
   var theForm = document.forms[0];
   var elem = document.getElementById('setup_site_log_dir');

   if( theForm.setup_site_custom_log_dir.checked ){
      elem.style.display = '';
   }
   else {
      elem.style.display = 'none';
   }
}

function toggleGUID(){
   var theForm = document.forms[0];
   var elem = document.getElementById('setup_site_guid_section');

   if( theForm.setup_site_specify_guid.checked ){
      elem.style.display = '';
   }
   else {
      elem.style.display = 'none';
   }
}
