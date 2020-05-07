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
class TemplateDate extends TemplateText{
	function get_html_edit(){
		$this->prepare();
		global $theme;
		$xtpl_var = strtoupper($this->name);
		$name = $this->name;
		$html = "<input name='$name' id='jscal_field$name' type='text'  size='11' maxlength='10' value='{".$xtpl_var."}'> <img src='themes/$theme/images/jscalendar.gif' alt='Enter Date'  id='jscal_trigger$name' align='absmiddle'> <span class='dateFormat'>{USER_DATEFORMAT}</span>
		\<script type='text/javascript'\>
        Calendar.setup (\{inputField : 'jscal_field$name', ifFormat : '{CALENDAR_DATEFORMAT}', showsTime : false, button : 'jscal_trigger$name', singleClick : true, step : 1\});
    	addToValidate('EditView', '$name', 'date', false,'$name' );   
	 \</\script>";
        
		return $html;
	}
	
	
	
function get_field_def(){
		return array('required'=>$this->is_required(),'source'=>'custom_fields', "name"=>$this->name, "vname"=>$this->label,"len"=>$this->max_size,'rname'=>$this->name, 'table'=>$this->bean->table_name . '_cstm',  'custom_type'=>'date', 'type'=>'relate');	
}

function get_db_type(){
		return " DATE ";	
}	
function get_xtpl_edit(){
		global $timedate;
		$name = $this->name;
		if(isset($this->bean->$name)){
			$returnXTPL = array();
			$returnXTPL['USER_DATEFORMAT'] = $timedate->get_user_date_format();
			$returnXTPL['CALENDAR_DATEFORMAT'] = $timedate->get_cal_date_format();
			$returnXTPL[strtoupper($this->name)] = $this->bean->$name;
			return $returnXTPL;	
		}
		return '';
	}
	
}


?>
