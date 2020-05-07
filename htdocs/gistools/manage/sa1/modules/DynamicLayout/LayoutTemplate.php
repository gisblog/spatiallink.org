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


class LayoutTemplate{

	function get_blank_slot(){
		return "<slot>&nbsp;</slot>";	
	}
	function get_edit_source_row(){
		return <<<EOQ
		<tr>
			<td  class="dataLabel"><slot>&nbsp;</slot></td>
			<td class="dataField"><slot>&nbsp;</slot></td>
			<td  class="dataLabel"><slot>&nbsp;</slot></td>
			<td class="dataField"><slot>&nbsp;</slot></td>
		</tr>
EOQ;
	}
	
	function get_detail_source_row(){
		return <<<EOQ
		<tr>
	<td width="15%" valign="top" class="tabDetailViewDL"><slot>&nbsp;</slot></td>
	<td width="35%" valign="top" class="tabDetailViewDF"><slot>&nbsp;</slot></td>
	<td width="15%" valign="top" class="tabDetailViewDL"><slot>&nbsp;</slot></td>
	<td width="35%" valign="top" class="tabDetailViewDF"><slot>&nbsp;</slot></td>
	</tr>
EOQ;
	}
	
	function get_list_view_header(){
		return <<<EOQ
			<td scope="col" width="20%" class="listViewThS1"><slot>&nbsp;</slot></td>
EOQ;
	}
	function get_list_view_column(){
		return <<<EOQ
			<td valign=TOP class="{ROW_COLOR}S1" bgcolor="{BG_COLOR}" nowrap><slot>&nbsp;</slot></td>
EOQ;
	}
	
}



?>
