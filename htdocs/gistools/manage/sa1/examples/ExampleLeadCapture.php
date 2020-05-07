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
?>
<script>
	function addToDescription(form, name, value){
			form.description.value += '--' + name + "=" + value+ "--"
	}
</script>
<form name='leadcap' action='../leadCapture.php' method='post'>
	<input type='hidden' name='lead_source' value='Web Site'>
	<input type='hidden' name='user' value='cheeto'>
	<input type='hidden' name='description' value=''>
	<input type='hidden' name='redirect' value='http://localhost/sugarcrm/examples/FormValidationTest.php'>
	<div align='center'>
	Please fill out this form so we can better server your game playing and food eating needs. It will redirect you to the form validation test.
	<table border=1><tr><td><table>
	<tr><td>First Name:</td><td><input type='text' name='first_name'></td></tr>
	<tr><td>Last Name:</td><td><input type='text' name='last_name'></td></tr>
	<tr><td>Company Name:</td><td><input type='text' name='account_name'></td></tr>
	<tr><td>Title:</td><td><input type='text' name='title'></td></tr> 
	<tr><td>Favorite Game:</td><td><select name='game'>
		<option value='monopoly'> Monopoly</option>
		<option value='uno'> UNO</option>
		<option value='sorry'> Sorry</option>
		<option value='Checkers'> Checkers</option>
	</select></td></tr>
	<tr><td>Favorite Food:</td><td><select name='food'>
		<option value='pizza'> Pizza</option>
		<option value='hamburger'> Hamburger</option>
		<option value='candy'> Candy </option>
		<option value='icecream'> Ice Cream</option>
	</select></td></tr>
	
	<tr><td></td><td><input type='Submit' name='submit' value='Submit' onclick='addToDescription(document.leadcap,"Favorite Food", document.leadcap.food.options[document.leadcap.food.selectedIndex].text);addToDescription(document.leadcap,"Favorite Game", document.leadcap.game.options[document.leadcap.game.selectedIndex].text);' ></td></tr></table></table>
</form>
