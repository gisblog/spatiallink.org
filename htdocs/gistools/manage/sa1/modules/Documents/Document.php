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

require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');
require_once('include/upload_file.php');

// User is used to store Forecast information.
class Document extends SugarBean {


	var $id;
	var $document_name;
	var $description;
	var $category_id;
	var $subcategory_id;
	var $status_id;
	var $created_by;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;



	var $active_date;
	var $exp_date;
	var $document_revision_id;
	var $filename;
	
	var $img_name;
	var $img_name_bare;
	
	//additional fields.
	var $revision;
	var $last_rev_create_date;
	var $last_rev_created_by;
	var $last_rev_created_name;	
	var $latest_revision;
	var $file_url;
	var $file_url_noimage;
	
	var $table_name = "documents";
    var $required_fields = Array("document_name"=>1,"active_date"=>1,"revision"=>1);
	var $object_name = "Document";
	var $user_preferences;
	var $column_fields = Array("id"
		,"document_name"
		,"description"
		,"category_id"
		,"subcategory_id"
		,"status_id"
		,"active_date"
		,"exp_date"
		,"date_entered"
		,"date_modified"
		,"created_by"
		,"modified_user_id"	



		,"document_revision_id"	
		);

	var $encodeFields = Array();

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('revision');

	// This is the list of fields that are in the lists.
	var $list_fields = Array("id"
		,"document_name"
		,"description"
		,"category_id"
		,"subcategory_id"
		,"status_id"
		,"active_date"
		,"exp_date"
		,"date_entered"
		,"date_modified"
		,"created_by"
		,"modified_user_id"	



		,"document_revision_id"
		,"last_rev_create_date"
		,"last_rev_created_by"
		,"latest_revision" 
		,"file_url"
		,"file_url_noimage"
		);
		
	var $default_order_by = "document_name";      //todo

	var $new_schema = true;
	var $module_dir = 'Documents';
	
	function Document() {
		parent::SugarBean();
		$this->log = LoggerManager::getLogger('Document');
		$this->setupCustomFields('Documents');  //parameter is module name
		$this->disable_row_level_security =false;
	}

	function save($check_notify = false){	
		return parent::save($check_notify);		
	}
	function get_summary_text()
	{
		return "$this->document_name";
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

		$query = "SELECT filename,revision,file_ext FROM document_revisions WHERE id='$this->document_revision_id'";
		
		$result = $this->db->query($query);
		$row = $this->db->fetchByAssoc($result);
		
		//popuplate filename
		$this->filename =$row['filename'];
		$this->latest_revision=$row['revision'];
		
		//populate the file url. 
		//image is selected based on the extension name <ext>_icon_inline, extension is stored in document_revisions.
		//if file is not found then default image file will be used.
		global $img_name;
		global $img_name_bare;
		if (!empty($row['file_ext'])) {
			$img_name = "themes/".$theme."/images/{$row['file_ext']}_image_inline.gif";	
			$img_name_bare = "{$row['file_ext']}_image_inline";		
		}
		
		//set default file name.
		if (!empty($img_name) && file_exists($img_name)) {
			$img_name = $img_name_bare;			
		}
		else {
			$img_name = "def_image_inline";  //todo change the default image.						
		}
		$this->file_url = "<a href='".UploadFile::get_url($this->filename,$this->document_revision_id)."' target='_blank'>".get_image('themes/'.$theme.'/images/'.$img_name,'alt="'.$mod_strings['LBL_LIST_VIEW_DOCUMENT'].'"  border="0"')."</a>";		
		$this->file_url_noimage=UploadFile::get_url($this->filename,$this->document_revision_id);
		
		//get last_rev_by user name.
		$query = "SELECT first_name,last_name, document_revisions.date_entered FROM users, document_revisions WHERE users.id = document_revisions.created_by and document_revisions.id = '$this->document_revision_id'";
		$result = $this->db->query($query,true,"Eror fetching user name: ");
		$row = $this->db->fetchByAssoc($result);
		if (!empty($row)) {
			require_once("include/TimeDate.php");
			$timedate=& new TimeDate();
			
			$this->last_rev_created_name = $row['first_name'].' '.$row['last_name'];	
			$this->last_rev_create_date = $timedate->to_display_date_time($row['date_entered']);
		}
	}

	function list_view_parse_additional_sections(&$list_form, $xTemplateSection){
		return $list_form;
	}

	function create_export_query($order_by, $where)
	{
	$query = "SELECT
				documents.*";
		$query .= " FROM documents ";

		$where_auto = " documents.deleted = 0";

		if($where != "")
			$query .= " WHERE $where AND " . $where_auto;
		else
			$query .= " WHERE " . $where_auto;

		if($order_by != "")
			$query .= " ORDER BY $order_by";
		else
			$query .= " ORDER BY documents.document_name";

		return $query;		
	}

	/** Returns a list of the associated document revisions
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_revisions()
	{
		// First, get the list of IDs.
		$query = "SELECT id from document_revisions where document_id='$this->id' AND deleted=0 order by date_entered desc";

		return $this->build_related_list($query, new DocumentRevision());
	}
	
	function create_list_query($order_by, $where)
	{	
		$custom_join = false;
		if(isset($this->custom_fields))
			$custom_join = $this->custom_fields->getJOIN();
		
		$query = "SELECT $this->table_name.* ";
		$query .= "  ,document_revisions.revision as latest_revision";
		$query .= "  ,document_revisions.date_entered as last_rev_create_date";
		$query .= " ,document_revisions.created_by as last_rev_created_by";
		
		if($custom_join){
		  $query .= $custom_join['select'];
		}		
		$query .= " FROM $this->table_name, document_revisions ";
		
		




		if($custom_join){		
			$query .= $custom_join['join'];
		}
		
		if($where != "")
			$query .= " where ($where) AND ";
		else
			$query .= " where ";
		$query .= $this->table_name.".deleted=0 AND document_revisions.deleted=0";
		$query .= " AND documents.document_revision_id = document_revisions.id";
		
		if(!empty($order_by))
		$query .= " ORDER BY $order_by";


		return $query;
	}	
	
	function get_list_view_data(){
		$document_fields = $this->get_list_view_array();
		$document_fields['FILE_URL'] = $this->file_url;
		$document_fields['FILE_URL_NOIMAGE'] = $this->file_url_noimage;		
		$document_fields['LAST_REV_CREATED_BY']= $this->last_rev_created_name;
		return $document_fields;
	}
}
?>
