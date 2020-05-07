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


//to_string methods to get strings for values

 // var_export gets rid of the empty values that we use to display None 	 
 // thishelper function fixes that 	 
  	 
 function var_export_helper($tempArray) 	 
 { 	 
         $addNone = 0; 	 
  	 
         foreach($tempArray as $key=>$val) 	 
         { 	 
                 if($key == '' && $val == '') 	 
                         $addNone = 1; 	 
         } 	 
  	 
         $newArray = var_export($tempArray, true); 	 
  	 
         if($addNone) 	 
         { 	 
                 $newArray = str_replace("array (", "array ( '' => '',", $newArray); 	 
         } 	 
  	 
         return $newArray; 	 
 }
 
 
//this function is used to overide a value in an array and returns the string code to write
function override_value_to_string($array_name, $value_name, $value){
	$string = "\${$array_name}[". var_export($value_name, true). "] = ";
	$string .= var_export_helper($value,true);
	return $string . ";";
}

function add_blank_option($options){
	if(!isset($options['']) && !isset($options['0']))
		$options = array_merge(array(''=>''), $options);
	return $options;
}

// This function iterates through the given arrays and combines the values of each key, to form one array
// Returns FALSE if number of elements in the arrays do not match; otherwise, returns merged array
// Example: array("a", "b", "c") and array("x", "y", "z") are passed in; array("ax", "by", "cz") is returned
function array_merge_values($arr1, $arr2) {
	if (count($arr1) != count($arr2)) {
		return FALSE;
	}

	for ($i = 0; $i < count($arr1); $i++) {
		$arr1[$i] .= $arr2[$i];
	}

	return $arr1;
}
?>
