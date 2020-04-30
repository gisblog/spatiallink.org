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
 * $Id: DocumentRevision.php,v 1.7.2.1 2005/05/12 20:51:39 ajay Exp $
 * Description: TODO:  To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');
require_once('include/upload_file.php');

// User is used to store Forecast information.
class DocumentRevision extends SugarBean {

	var $id;
	var $document_id;
	var $date_entered;
	var $created_by;
	var $filename;
	var $file_mime_type;
	var $revision;
	var $change_log;
	var $document_name;
	var $latest_revision;
	var $file_url;
	var $file_ext;
	var $created_by_name;

	var $img_name;
	var $img_name_bare;
	
	var $table_name = "document_revisions";	
	var $object_name = "DocumentRevision";
	var $module_dir = 'Documents';
	var $new_schema = true;
	
	var $column_fields = Array("id"
		,"document_id"
		,"date_entered"
		,"created_by"
		,"filename"	
		,"file_mime_type"
		,"revision"
		,"change_log"
		,"file_ext"
		);

	var $encodeFields = Array();

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('');

	// This is the list of fields that are in the lists.
	var $list_fields = Array("id"
		,"document_id"
		,"date_entered"
		,"created_by"
		,"filename"	
		,"file_mime_type"
		,"revision"
		,"file_url"
		,"change_log"
		,"file_ext"
		,"created_by_name"
		);
		
	var $required_fields = Array("revision");
	
	var $default_order_by = "date_entered desc";

	function DocumentRevision() {
		parent::SugarBean();
		$this->log = LoggerManager::getLogger('DocumentRevision');
		$this->setupCustomFields('Documents');  //parameter is module name
		$this->disable_row_level_security =true; //no direct access to this module. 
	}

	function save($check_notify = false){	
		parent::save($check_notify);
				
		//update documents table.
		//$query = "UPDATE documents set document_version_id='$this->id' where id = '$this->document_id'";	
		//$this->db->query($query);
	}
	function get_summary_text()
	{
		return "$this->filename";
	}

	function retrieve($id, $encode=false){
		$ret = parent::retrieve($id, $encode);	
		
		return $ret;
	}

	function is_authenticated()
	{
		return $this->authenticated;
	}

	function fill_in_additional_list_fields() {
		$this->fill_in_additional_detail_fields();
	}

	function fill_in_additional_detail_fields()
	{
		global $theme;
		global $current_language;
		$mod_strings=return_module_language($current_language, 'Documents');
		
		//find the document name and current version.
		$query = "SELECT document_name, revision FROM documents, document_revisions where documents.id = '$this->document_id' AND document_revisions.id = documents.document_revision_id";
		$result = $this->db->query($query,true,"Error fetching document details...:");
		$row = $this->db->fetchByAssoc($result);
		if ($row != null) {
			$this->document_name = $row['document_name'];
			$this->latest_revision = $row['revision'];	
		}
		
		//populate the file url. 
		//image is selected based on the extension name <ext>_image_inline, extension is stored in document_revisions.
		//if file is not found then default image file will be used.
		global $img_name;
		global $img_name_bare;
		if (!empty($this->file_ext)) {
			$img_name = "themes/".$theme."/images/{$this->file_ext}_image_inline.gif";	
			$img_name_bare = "{$this->file_ext}_image_inline";		
		}
		
		//set default file name.
		if (!empty($img_name) && file_exists($img_name)) {
			$img_name = $img_name_bare;			
		}
		else {
			$img_name = "def_image_inline";  //todo change the default image.						
		}
		$this->file_url = "<a href='".UploadFile::get_url($this->filename,$this->id)."' target='_blank'>".get_image('themes/'.$theme.'/images/'.$img_name,'alt="'.$mod_strings['LBL_LIST_VIEW_DOCUMENT'].'"  border="0"')."</a>";		
	}

	function fill_document_name_revision($doc_id) {

		//find the document name and current version.
		$query = "SELECT documents.document_name, revision FROM documents, document_revisions where documents.id = '$doc_id'";
		$query .= " AND document_revisions.id = documents.document_revision_id";
		$result = $this->db->query($query,true,"Error fetching document details...:");
		$row = $this->db->fetchByAssoc($result);
		if ($row != null) {
			$this->name = $row['document_name'];
			$this->latest_revision = $row['revision'];	
		}	
	}
	
	function list_view_parse_additional_sections(&$list_form, $xTemplateSection){
		return $list_form;
	}
	

	function create_list_query($order_by, $where)
	{	
		$custom_join = false;
		if(isset($this->custom_fields))
			$custom_join = $this->custom_fields->getJOIN();
			
		$query = "SELECT $this->table_name.*, concat(ifnull(users.first_name,''),' ', ifnull(users.last_name,'')) as created_by_name ";
		
		if($custom_join){
		  $query .= $custom_join['select'];
		}		
		$query .= " FROM $this->table_name, users ";
		

		




		if($custom_join){		
			$query .= $custom_join['join'];
		}
		if($where != "")
			$query .= " where ($where) AND ";
		else
			$query .= " where ";
		$query .= $this->table_name.".deleted=0 AND users.deleted=0";
		$query .= " AND users.id = document_revisions.created_by";
		
		if(!empty($order_by))
		$query .= " ORDER BY $order_by";


		return $query;
	}		
	
	
	function get_list_view_data(){
		$revision_fields = $this->get_list_view_array();

		$forecast_fields['FILE_URL'] = $this->file_url;						
		return $revision_fields;
	}

}
?>
