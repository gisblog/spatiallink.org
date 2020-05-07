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
// $Id: 5register.js,v 1.3 2005/01/21 09:14:19 julian Exp $

function submitbutton()
{
   var form = document.mosForm;
   var r = new RegExp("[^0-9A-Za-z]", "i");

   if (form.email.value != "")
   {
      var myString = form.email.value;
      var pattern = /(\W)|(_)/g;
      var adate = new Date();
      var ms = adate.getMilliseconds();
      var sec = adate.getSeconds();
      var mins = adate.getMinutes();
      ms = ms.toString();
      sec = sec.toString();
      mins = mins.toString();
      newdate = ms + sec + mins;
   
      var newString = myString.replace(pattern,"");
      newString = newString + newdate;
      form.username.value = newString;
      form.password.value = newString;
      form.password2.value = newString;
   }

   // do field validation
   if (form.name.value == "")
   {
      form.name.focus();
      alert( "Please provide your name" );
      return false;
   }
   else if (form.email.value == "")
   {
      form.email.focus();
      alert( "Please provide your email address" );
      return false;
   }
   else
   {
      form.submit();
   }

   document.thx.submit();
   window.focus();
}
