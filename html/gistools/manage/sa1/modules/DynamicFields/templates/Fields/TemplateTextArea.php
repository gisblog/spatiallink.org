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
require_once('modules/DynamicFields/templates/Fields/TemplateText.php');
class TemplateTextArea extends TemplateText{
	function get_html_edit(){
		$this->prepare();
		return "<textarea name='". $this->name. "' id='".$this->name."' rows='4' cols='".$this->size."'>{". strtoupper($this->name). "} </textarea>";
	}

	function get_db_type(){
		return " TEXT ";	
	}
	
	
	
	function get_xtpl_detail(){
		$name = $this->name;
		return nl2br($this->bean->$name);	
	}
	
	
	
	
}


?>
