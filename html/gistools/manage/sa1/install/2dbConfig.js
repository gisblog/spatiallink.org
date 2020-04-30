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
// $Id: 2dbConfig.js,v 1.5 2005/04/18 00:13:35 bob Exp $

function togglePasswordRetypeRequired() {
   var theForm = document.forms[0];
   var elem = document.getElementById('password_retype_required');

   if( theForm.setup_db_create_sugarsales_user.checked ){
      elem.style.display = '';
      // theForm.setup_db_sugarsales_user.focus();
      theForm.setup_db_username_is_privileged.checked = "";
      theForm.setup_db_username_is_privileged.disabled = "disabled";
      toggleUsernameIsPrivileged();
   }
   else {
      elem.style.display = 'none';
      theForm.setup_db_username_is_privileged.disabled = "";
   }
}

function toggleDropTables(){
   var theForm = document.forms[0];

   if( theForm.setup_db_create_database.checked ){
      theForm.setup_db_drop_tables.checked = '';
      theForm.setup_db_drop_tables.disabled = "disabled";
   }
   else {
      theForm.setup_db_drop_tables.disabled = '';
   }
}

function toggleUsernameIsPrivileged(){
   var theForm = document.forms[0];
   var elem = document.getElementById('privileged_user_info');

   if( theForm.setup_db_username_is_privileged.checked ){
      elem.style.display = 'none';
   }
   else {
      elem.style.display = '';
   }
}
