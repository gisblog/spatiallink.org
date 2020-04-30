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
 * $Id: footer.php,v 1.5 2005/04/29 04:31:28 lam Exp $
 * Description:  Contains a variety of utility functions used to display UI
 * components such as form headers and footers.  Intended to be modified on a per
 * theme basis.
 ********************************************************************************/
global $app_strings;
?>
<!--end body panes-->
		</td>
		<td width="10"><img src="include/images/blank.gif" width="10" height="1" alt=""></td>
	</tr>
	<tr>
    	<td style="background-image: url('<?php echo $image_path; ?>grass_bg.gif');"><img src="<?php echo $image_path; ?>leftColumnCornerBottom.gif" height="10" width="10" alt=""></td>
    	<td colspan="2" style="border-bottom: 1px solid #164D00"><img src="include/images/blank.gif" width="1" height="1" alt=""></td>
	</tr>
	</table></td></tr></table></td>
</tr>
<tr>
    <td colspan="2" align="center" class="footer">
	<hr width="80%" size="1" class="footerHR">
	<?php 
$numTabs = count ($modListHeader);
$i=1;
foreach($modListHeader as $module_name) {
	?>
	<A href="index.php?module=<?php echo $module_name ?>&action=index" class="footerLink"><?php echo $app_list_strings['moduleList'][$module_name] ?></A><?php 
	if ($i > $numTabs-1 or $i == $max_tabs) echo "";
	else echo " | ";
	if ($i == $max_tabs)
	echo "<br>";
$i++;
} ?>
	  <hr width="80%" size="1" class="footerHR">
	  </td>
</tr>
</table>

