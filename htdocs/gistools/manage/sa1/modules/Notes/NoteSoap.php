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
require_once('include/upload_file.php');
require_once('modules/Notes/Note.php');
require_once('include/upload_file.php');

class NoteSoap{
var $upload_file;
function NoteSoap(){
	$this->upload_file = new UploadFile('uploadfile');		
}
	
function saveFile($note, $portal = false){

	$focus =& new Note();
	





	
	if(!empty($note['id'])){
		$focus->retrieve($note['id']);	
	}else{
		return '-1';	
	}
	
	if(!empty($note['file'])){
		$decodedFile = base64_decode($note['file']);
		$this->upload_file->set_for_soap($note['filename'], $decodedFile);
		$focus->filename = $this->upload_file->get_stored_file_name();
		$focus->file_mime_type = $this->upload_file->getMimeSoap($focus->filename);
		$return_id = $focus->save();
		$this->upload_file->final_move($focus->id);
	}else{
		return '-1';
	}
	return $return_id;
}

function retrieveFile($id, $filename){
	if(empty($filename)){
		return '';
	}
	
	
	$this->upload_file->stored_file_name = $filename;
	$filepath = $this->upload_file->get_upload_path($id);
	if(file_exists($filepath)){
		$fp = fopen($filepath, 'rb');
		$file = fread($fp, filesize($filepath));
		fclose($fp);
		return base64_encode($file);
	}
	
	return -1;
	

	
	
}

}




?>
