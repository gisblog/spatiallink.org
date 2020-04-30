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

include_once('modules/CustomFields/custom_fields.php');

//function template_custom_fields_edit(&$args)
function template_custom_fields_detail(&$bean)
{
global $custom_fields_def;
if ( ! isset($custom_fields_def[$_REQUEST['module']]) || count($custom_fields_def[$_REQUEST['module']]) == 0 )
{
 return;
}

?>
<br>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabDetailView">
        <tr>
<?php

  for ($i = 0;$i < count($custom_fields_def[$_REQUEST['module']]) ; $i++)
  {
	$field = $custom_fields_def[$_REQUEST['module']][$i];
	$field_name = $field['name'];
 	if ($i % 2 == 0)
 	{
?>
<tr>
<?php
	}
?>
        <td width="15%" class="tabDetailViewDL"><?php echo $field['label']; ?>:</td>
        <td width="35%" class="tabDetailViewDF"><?php echo $bean->$field_name; ?></td>
<?php
 if ($i % 2 == 1)
 {
?>
        </tr>
<?php
 }
}
?>

        </table>
<?php
}
?>
