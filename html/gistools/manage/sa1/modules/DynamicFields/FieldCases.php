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
switch(strtolower($type)){
			case 'char':
			case 'varchar':
			case 'varchar2':
			case 'text': 	
						require_once('modules/DynamicFields/templates/Fields/TemplateText.php');
						$field =& new TemplateText();
						break;
			case 'textarea':
						require_once('modules/DynamicFields/templates/Fields/TemplateTextArea.php');
						$field =& new TemplateTextArea();
						break;
			case 'double':
			case 'float':
						require_once('modules/DynamicFields/templates/Fields/TemplateFloat.php');
						$field =& new TemplateFloat();
						break;
			case 'int':
						require_once('modules/DynamicFields/templates/Fields/TemplateInt.php');
						$field =& new TemplateInt();
						break;
			case 'date':
						require_once('modules/DynamicFields/templates/Fields/TemplateDate.php');
						$field =& new TemplateDate();
						break;
			case 'bool':
						require_once('modules/DynamicFields/templates/Fields/TemplateBoolean.php');
						$field =& new TemplateBoolean();
						break;
			case 'enum':
						require_once('modules/DynamicFields/templates/Fields/TemplateEnum.php');
						$field =& new TemplateEnum();
						break;
			case 'relate':
											
			default:
						require_once('modules/DynamicFields/templates/Fields/TemplateText.php');
						$field =& new TemplateText();

			
	}	
?>
