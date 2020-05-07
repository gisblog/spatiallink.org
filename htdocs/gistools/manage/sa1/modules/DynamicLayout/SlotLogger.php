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

class SlotLogger{
var $fp = '';
function SlotLogger(){
		
}

function open($filename){
	require_once('modules/DynamicLayout/DynamicLayoutUtils.php');
	$custom_file = create_custom_directory($filename. '_slot.log');
	$this->fp = fopen($custom_file, 'a');	
	fwrite($this->fp, "BEGIN;");
}

function add_row($row){
	fwrite($this->fp, "ar:$row;");
}

function add_col($col){
	fwrite($this->fp, "ac:$col;");
}

function del_row($row){
	fwrite($this->fp, "dr:$row;");
}

function del_col($col){
	fwrite($this->fp, "dc:$col;");
}

function swap_fields($slot1, $slot2){
	if($slot1 != $slot2){
		fwrite($this->fp, "sf:$slot1*$slot2;");
	}
}

function add_field($slot, $add){
	fwrite($this->fp, "af:$slot\n". $add . '\neaf;');
}

function close(){
	fwrite($this->fp, "END;\n");
	fclose($this->fp);	
}

}
?>
