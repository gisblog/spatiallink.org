<?PHP

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
?>
<script type="text/javascript" src="../include/javascript/sugar_3.js"></script>

<form name='test'>
<table>
<tr><td>*Name:</td><td><input type='text' name='name'></td></tr>
<tr><td>*Email:</td><td><input type='text' name='email'></td></tr>
<tr><td>Address:</td><td><input type='text' name='add'></td></tr>
<tr><td>Time:</td><td><input type='text' name='time'></td></tr>
<tr><td>Date:</td><td><input type='text' name='date'></td></tr>
<tr><td>Amount:</td><td>$<input type='text' name='amount'></td></tr>

</table>
<input type='button' name='test' value='Test' onclick="check_form('test');">
</form>
<script>
addToValidate('test','email', 'email', true, 'EMAIL'); 
addToValidate('test','name', '', true, 'NAME'); 
addToValidate('test','add', '', false, 'ADDRESS'); 
addToValidate('test','time', 'time', false, 'TIME'); 
addToValidate('test','date', 'date', true, 'DATE'); 
addToValidate('test','amount', 'numeric', false, 'AMOUNT');
</script>
