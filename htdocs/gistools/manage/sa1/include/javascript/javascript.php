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
 * $Id: javascript.php,v 1.26 2005/04/28 18:24:58 robert Exp $
 * Description:  Creates the runtime database connection.
 ********************************************************************************/
class javascript{
	var $formname = 'form';
	var $script = "<script type=\"text/javascript\">\n";
	var $sugarbean = null;
	function setFormName($name){
		$this->formname = $name;
	}

	function javascript(){
		global $app_strings;
		$this->script .= "requiredTxt = '{$app_strings['ERR_MISSING_REQUIRED_FIELDS']}';\n";

	}
	function setSugarBean($sugar){
		$this->sugarbean =& $sugar;
	}

	function addRequiredFields($prefix=''){
			if(isset($this->sugarbean->required_fields)){
				foreach($this->sugarbean->required_fields as $field=>$value){
					$this->addField($field,'true', $prefix);
				}
			}
	}


	function addField($field,$required, $prefix=''){
		if(isset($this->sugarbean->field_name_map[$field]['vname'])){
			if(empty($required)){
				if(isset($this->sugarbean->field_name_map[$field]['required']) && $this->sugarbean->field_name_map[$field]['required']){
					$required = 'true';
				}else{
					$required = 'false';	
				}
				if(isset($this->sugarbean->required_fields[$field]) && $this->sugarbean->required_fields[$field]){
					$required = 'true';
				}
				if($field == 'id'){
					$required = 'false';	
				}	
						
			}
			if(isset($this->sugarbean->field_name_map[$field]['validation'])){
				switch($this->sugarbean->field_name_map[$field]['validation']['type']){
					case 'range': 
						$min = 0;
						$max = 100;
						if(isset($this->sugarbean->field_name_map[$field]['validation']['min'])){
							$min = $this->sugarbean->field_name_map[$field]['validation']['min'];
						}
						if(isset($this->sugarbean->field_name_map[$field]['validation']['max'])){
							$max = $this->sugarbean->field_name_map[$field]['validation']['max'];
						}
						if($min > $max){
							$max = $min;
						}
						$this->addFieldRange($field,$this->sugarbean->field_name_map[$field]['type'],$this->sugarbean->field_name_map[$field]['vname'],$required,$prefix, $min, $max );	
						break;
					case 'isbefore':
						$compareTo = $this->sugarbean->field_name_map[$field]['validation']['compareto'];
						$this->addFieldDateBefore($field,$this->sugarbean->field_name_map[$field]['type'],$this->sugarbean->field_name_map[$field]['vname'],$required,$prefix, $compareTo );
						break;
					default: $this->addFieldGeneric($field,$this->sugarbean->field_name_map[$field]['type'],$this->sugarbean->field_name_map[$field]['vname'],$required,$prefix );	
				}
			}else{
				$this->addFieldGeneric($field,$this->sugarbean->field_name_map[$field]['type'],$this->sugarbean->field_name_map[$field]['vname'],$required,$prefix );
			}
		}else{
			$this->sugarbean->log->debug('No VarDef Label For ' . $field . ' in module ' . $this->sugarbean->module_dir ); 	
		}

	}


	function stripEndColon($modString)
	{
		if(substr($modString, -1, 1) == ":")
			$modString = substr($modString, 0, (strlen($modString) - 1));
		if(substr($modString, -2, 2) == ": ")
			$modString = substr($modString, 0, (strlen($modString) - 2));
		return $modString;
		
	}
	
	function addFieldGeneric($field, $type,$displayName, $required, $prefix=''){
		
		$this->script .= "addToValidate('".$this->formname."', '".$prefix.$field."', '".$type . "', $required,'". $this->stripEndColon(translate($displayName)) . "' );\n";
	}
	function addFieldRange($field, $type,$displayName, $required, $prefix='',$min, $max){
		$this->script .= "addToValidateRange('".$this->formname."', '".$prefix.$field."', '".$type . "', $required,'".$this->stripEndColon(translate($displayName)) . "', $min, $max );\n";
	}
	
	function addFieldDateBefore($field, $type,$displayName, $required, $prefix='',$compareTo){
		$this->script .= "addToValidateDateBefore('".$this->formname."', '".$prefix.$field."', '".$type . "', $required,'".$this->stripEndColon(translate($displayName)) . "', '$compareTo' );\n";
	}
	

	function addAllFields($prefix,$skip_fields=null){
		if (!isset($skip_fields))
		{
			$skip_fields = array();
		}
		foreach($this->sugarbean->field_name_map as $field=>$value){
			if (! isset($skip_fields[$field]))
			{
			  $this->addField($field, '', $prefix);
			}
		}
	}

	function getScript(){
		return $this->script . "</script>";
	}
}
?>
