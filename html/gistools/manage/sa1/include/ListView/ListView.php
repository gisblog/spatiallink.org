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
 * $Id: ListView.php,v 1.76 2005/04/30 05:51:44 joey Exp $
 * Description:  generic list view class.
 ********************************************************************************/
require_once('include/logging.php');

class ListView {

	var $local_theme = null;
	var  $local_app_strings= null;
	var  $local_image_path = null;
	var  $local_current_module = null;
	var $local_mod_strings = null;
	var  $records_per_page = 20;
	var  $xTemplate = null;
	var $xTemplatePath = null;
	var $seed_data = null;
	var $query_where = null;
	var $query_limit = -1;
	var $query_orderby = null;
	var $header_title = "";
	var $header_text = "";
	var $log = null;
	var $initialized = false;
	var $show_export_button = true;
	var $show_paging = true;
	var $querey_where_has_changed = false;
	var $display_header_and_footer = true;
	var $baseURL = '';

	
	var $shouldProcess= false;
var $data_array;


function setDataArray($value){
	$this->data_array = $value;
}	

function processListViewMulti($seed, $xTemplateSection, $html_varName) {
	
	$this->shouldProcess = true;
	
	echo "<form name='MassUpdate' method='post' action='index.php'>";
	$ListView = $this->processListViewTwo($seed, $xTemplateSection, $html_varName);

	echo "<a class='listViewCheckLink' href='javascript:checkAll(document.MassUpdate, \"mass[]\", 1);'>".translate('LBL_CHECKALL')."</a> - <a class='listViewCheckLink' href='javascript:checkAll(document.MassUpdate, \"mass[]\", 0);'>".translate('LBL_CLEARALL')."</a>";
	echo '<br><br>';
}
	
	
function processListView($seed, $xTemplateSection, $html_varName)
{
	require_once('include/MassUpdate.php');
	$mass = new MassUpdate();
	$this->shouldProcess = is_subclass_of($seed, "SugarBean")
		&& ($_REQUEST['action'] == 'index')
		&& ($_REQUEST['module'] == $seed->module_dir);

	if($this->shouldProcess)
	{
		echo  $mass->getDisplayMassUpdateForm(true);
		echo $mass->getMassUpdateFormHeader();
		$mass->setSugarBean($seed);
		$mass->handleMassUpdate();
	}

	$ListView = $this->processListViewTwo($seed,$xTemplateSection, $html_varName);
	if($this->shouldProcess)
	{
		echo "<a class='listViewCheckLink' href='javascript:checkAll(document.MassUpdate, \"mass[]\", 1);'>".translate('LBL_CHECKALL')."</a> - <a class='listViewCheckLink' href='javascript:checkAll(document.MassUpdate, \"mass[]\", 0);'>".translate('LBL_CLEARALL')."</a>";
		echo '<br><br>';
		echo $mass->getMassUpdateForm();
		echo $mass->endMassUpdateForm();
	}
}

/**sets whether or not to display the xtemplate header and footer
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
*/
function setDisplayHeaderAndFooter($bool){
		$this->display_header_and_footer = $bool;
}

/**initializes ListView
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
*/
 function ListView(){


	if(!$this->initialized){
		global $sugar_config;
		$this->records_per_page = $sugar_config['list_max_entries_per_page'] + 0;
		$this->initialized = true;
		global $theme, $app_strings, $image_path, $currentModule;
		$this->local_theme = $theme;
		$this->local_app_strings = &$app_strings;
		$this->local_image_path = $image_path;
		$this->local_current_module = $currentModule;

		if(empty($this->local_image_path)){
			$this->local_image_path = 'themes/'.$theme.'/images';
		}
		$this->log = LoggerManager::getLogger('listView_'.$this->local_current_module);

	}
}
/**sets how many records should be displayed per page in the list view
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
*/
 function setRecordsPerPage($count){
	$this->records_per_page = $count;
}
/**sets the header title */
 function setHeaderTitle($value){
	$this->header_title = $value;
}
/**sets the header text this is text thats appended to the header table and is usually used for the creation of buttons
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
*/
 function setHeaderText($value){
	$this->header_text = $value;
}
/**sets the path for the XTemplate HTML file to be used this is only needed to be set if you are allowing ListView to create the XTemplate
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
*/
 function setXTemplatePath($value){
	$this->xTemplatePath= $value;
}

/**this is a helper function for allowing ListView to create a new XTemplate it groups parameters that should be set into a single function
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
*/
 function initNewXTemplate($XTemplatePath, &$modString, $imagePath = null){
	$this->setXTemplatePath($XTemplatePath);
	if(isset($modString))
		$this->setModStrings($modString);
	if(isset($imagePath))
		$this->setImagePath($imagePath);
}


/**sets the parameters dealing with the db
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
*/
 function setQuery($where, $limit, $orderBy, $varName, $allowOrderByOveride=true){
	$this->query_where = $where;
	if($this->getSessionVariable("query", "where") != $where){
		$this->querey_where_has_changed = true;
		$this->setSessionVariable("query", "where", $where);
	}

	$this->query_limit = $limit;
	if(!$allowOrderByOveride){
		$this->query_orderby = $orderBy;
		return;
 	}
	$sortBy = $this->getSessionVariable($varName, "ORDER_BY") ;

	if(empty($sortBy)){
		$this->setUserVariable($varName, "ORDER_BY", $orderBy);
		$sortBy = $orderBy;
	}else{
		$this->setUserVariable($varName, "ORDER_BY", $sortBy);
	}
	if($sortBy == 'amount'){
		$sortBy = 'amount*1';
	}
	if($sortBy == 'amount_usdollar'){
		$sortBy = 'amount_usdollar*1';
	}

	$desc = false;
	$desc = $this->getSessionVariable($varName, $sortBy."S");

	if(empty($desc))
		$desc = false;
	if(isset($_REQUEST[$this->getSessionVariableName($varName,  "ORDER_BY")]))
		$last = $this->getSessionVariable($varName, "OBL");
		if(!empty($last) && $last == $sortBy){
			$desc = !$desc;
		}else {
			$this->setSessionVariable($varName, "OBL", $sortBy);
		}
	$this->setSessionVariable($varName, $sortBy."S", $desc);
	if(!empty($sortBy)){
	if(substr_count(strtolower($sortBy), ' desc') == 0 && substr_count(strtolower($sortBy), ' asc') == 0){
		if($desc){
			$this->query_orderby = $sortBy.' desc';
		}else{
			$this->query_orderby = $sortBy.' asc';
		}
	}
	else{
		$this->query_orderby = $sortBy;
	}
	}else {
		$this->query_orderby = "";
	}





}

function displayArrow(){

}

/**sets the theme used only use if it is different from the global
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
*/
 function setTheme($theme){
	$this->local_theme = $theme;
	if(isset($this->xTemplate))$this->xTemplate->assign("THEME", $this->local_theme );
}

/**sets the AppStrings used only use if it is different from the global
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
*/
 function setAppStrings(&$app_strings){
	unset($this->local_app_strings);
	$this->local_app_strings = $app_strings;
	if(isset($this->xTemplate))$this->xTemplate->assign("APP", $this->local_app_strings );
}

/**sets the ModStrings used
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
*/
 function setModStrings(&$mod_strings){
	unset($this->local_module_strings);
	$this->local_mod_strings = $mod_strings;
	if(isset($this->xTemplate))$this->xTemplate->assign("MOD", $this->local_mod_strings );
}

/**sets the ImagePath used
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
*/
 function setImagePath($image_path){
	$this->local_image_path = $image_path;
	if(empty($this->local_image_path)){
		$this->local_image_path = 'themes/'.$this->local_theme.'/images';
	}
	if(isset($this->xTemplate))$this->xTemplate->assign("IMAGE_PATH", $this->local_image_path );
}

/**sets the currentModule only use if this is different from the global
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
*/
 function setCurrentModule($currentModule){
	unset($this->local_current_module);
	$this->local_current_module = $currentModule;
	$this->log = LoggerManager::getLogger('listView_'.$this->local_current_module);
	if(isset($this->xTemplate))$this->xTemplate->assign("MODULE_NAME", $this->local_current_module );
}

/**INTERNAL FUNCTION creates an XTemplate DO NOT CALL THIS THIS IS AN INTERNAL FUNCTION
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
*/
 function createXTemplate(){
 	if(!isset($this->xTemplate)){
		if(isset($this->xTemplatePath)){
			$this->xTemplate = new XTemplate ($this->xTemplatePath);
			$this->xTemplate->assign("APP", $this->local_app_strings);
			if(isset($this->local_mod_strings))$this->xTemplate->assign("MOD", $this->local_mod_strings);
			$this->xTemplate->assign("THEME", $this->local_theme);
			$this->xTemplate->assign("IMAGE_PATH", $this->local_image_path);
			$this->xTemplate->assign("MODULE_NAME", $this->local_current_module);
		}else{
			$log->error("NO XTEMPLATEPATH DEFINED CANNOT CREATE XTEMPLATE");
		}
 	}
}

/**sets the XTemplate telling ListView to use newXTemplate as its current XTemplate
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
*/
 function setXTemplate(&$newXTemplate){
	$this->xTemplate = $newXTemplate;
}

/**returns the XTemplate
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
*/
 function getXTemplate(){
	return $this->xTemplate;
}

/**assigns a name value pair to the XTemplate
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
*/
 function xTemplateAssign($name, $value){

		if(!isset($this->xTemplate)){
			$this->createXTemplate();
		}
 		$this->xTemplate->assign($name, $value);

}

/**INTERNAL FUNCTION returns the offset first checking the querey then checking the session if the where clause has changed from the last time it returns 0
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
*/
 function getOffset($localVarName){
 	if($this->querey_where_has_changed){
 		$this->setSessionVariable($localVarName,"offset", 0);
 	}
	$offset = $this->getSessionVariable($localVarName,"offset");
	if(isset($offset)){
		return $offset;
	}
	return 0;
}

/**INTERNAL FUNCTION sets the offset in the session
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
*/
 function setOffset($localVarName, $value){
		$this->setSessionVariable($localVarName, "offset", $value);
}

/**INTERNAL FUNCTION sets a session variable
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
*/
 function setSessionVariable($localVarName,$varName, $value){
		$_SESSION[$this->local_current_module."_".$localVarName."_".$varName] = $value;
}

function setUserVariable($localVarName,$varName, $value){
		global $current_user;
		$current_user->setPreference($this->local_current_module."_".$localVarName."_".$varName, $value);
}

/**INTERNAL FUNCTION returns a session variable first checking the querey for it then checking the session
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
*/
 function getSessionVariable($localVarName,$varName){

		if(isset($_REQUEST[$this->getSessionVariableName($localVarName, $varName)])){
			$this->setSessionVariable($localVarName,$varName,$_REQUEST[$this->getSessionVariableName($localVarName, $varName)]);
		}
		 if(isset($_SESSION[$this->getSessionVariableName($localVarName, $varName)])){
		 	return $_SESSION[$this->getSessionVariableName($localVarName, $varName)];
		 }
		 return "";
}

function getUserVariable($localVarName, $varName){
	global $current_user;
	if(isset($_REQUEST[$this->getSessionVariableName($localVarName, $varName)])){
			$this->setUserVariable($localVarName,$varName,$_REQUEST[$this->getSessionVariableName($localVarName, $varName)]);
	}
	return $current_user->getPreference($this->getSessionVariableName($localVarName, $varName));
}





/**

 * @return void
 * @param unknown $localVarName
 * @param unknown $varName
 * @desc INTERNAL FUNCTION returns the session/query variable name
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function getSessionVariableName($localVarName,$varName){
	return $this->local_current_module."_".$localVarName."_".$varName;
}

/**

 * @return void
 * @param unknown $seed
 * @param unknown $xTemplateSection
 * @param unknown $html_varName
 * @desc INTERNAL FUNCTION Handles List Views using seeds that extend SugarBean
	$XTemplateSection is the section in the XTemplate file that should be parsed usually main
	$html_VarName is the variable name used in the XTemplateFile e.g. TASK
	$seed is a seed that extends SugarBean
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
 */
 function processSugarBean($xtemplateSection, $html_varName, $seed){
	$current_offset = $this->getOffset($html_varName);
	$response = $seed->get_list($this->query_orderby, $this->query_where, $current_offset, $this->query_limit);
	$list = $response['list'];
	$row_count = $response['row_count'];
	$next_offset = $response['next_offset'];
	$previous_offset = $response['previous_offset'];
	global $list_view_row_count;
	$list_view_row_count = $row_count;
	$this->processListNavigation( $xtemplateSection,$html_varName, $current_offset, $next_offset, $previous_offset, $row_count);
	
	return $list;
}

/**

 * @return void
 * @param unknown $data
 * @param unknown $xTemplateSection
 * @param unknown $html_varName
 * @desc INTERNAL FUNCTION process the List Navigation
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
 function processListNavigation( $xtemplateSection, $html_varName, $current_offset, $next_offset, $previous_offset, $row_count ){
 global $image_path, $export_module, $sugar_config, $current_user;
	$start_record = $current_offset + 1;
	if($row_count == 0)
		$start_record = 0;
	$end_record = $start_record + $this->records_per_page;
	// back up the the last page.
	if($end_record > $row_count+1)
	{
		$end_record = $row_count+1;
	}
	// Deterime the start location of the last page
	if($row_count == 0)
		$number_pages = 0;
	else
		$number_pages = floor(($row_count - 1) / $this->records_per_page);
	$last_offset = $number_pages * $this->records_per_page;
	if(empty($this->query_limit)  || $this->query_limit > $this->records_per_page){
		if(!isset($this->base_URL)){
		$this->base_URL = $_SERVER['PHP_SELF'];
		if(empty($this->base_URL)){
			$this->base_URL = 'index.php';	
		}
		/*fixes an issue with deletes when doing a search*/
		foreach($_GET as $name=>$value){
			if(!empty($value)){
			if($name != $this->getSessionVariableName($html_varName,"ORDER_BY") && $name != $this->getSessionVariableName($html_varName,"offset") && substr_count($name, "ORDER_BY")==0){
				if (is_array($value)) {
					foreach($value as $valuename=>$valuevalue){
					  $this->base_URL	.= "&{$name}[]=".$valuevalue;
					}				
				} else {
					if(substr_count( $this->base_URL, '?') > 0){
						$this->base_URL	.= "&$name=$value";
					}else{
						$this->base_URL	.= "?$name=$value";
					}
				}
			}	
			}
		}
		//had an issue with deletes when doing searches
		/*if(isset($_SERVER['QUERY_STRING'])){
			$this->base_URL = ereg_replace("\&".$this->getSessionVariableName($html_varName,"ORDER_BY")."=[0-9a-zA-Z\_\.]*","",$this->base_URL .'?'.$_SERVER['QUERY_STRING']);
			$this->base_URL = ereg_replace("\&".$this->getSessionVariableName($html_varName,"offset")."=[0-9]*","",$this->base_URL);
		}*/
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$this->base_URL .= '?';
			if(isset($_REQUEST['action'])) $this->base_URL .= '&action='.$_REQUEST['action'];
			if(isset($_REQUEST['record'])) $this->base_URL .= '&record='.$_REQUEST['record'];
			if(isset($_REQUEST['module'])) $this->base_URL .= '&module='.$_REQUEST['module'];
		}
		$this->base_URL .= "&".$this->getSessionVariableName($html_varName,"offset")."=";
		}
		$current_URL = $this->base_URL.$current_offset;
		$start_URL = $this->base_URL."0";
		$previous_URL  = $this->base_URL.$previous_offset;
		$next_URL  = $this->base_URL.$next_offset;
		$end_URL  = $this->base_URL.$last_offset;

		$this->log->debug("Offsets: (start, previous, next, last)(0, $previous_offset, $next_offset, $last_offset)");

		if(0 == $current_offset){
			$start_link = get_image($image_path."start_off","alt='".$this->local_app_strings['LNK_LIST_START']."'  border='0' align='absmiddle'")."&nbsp;".$this->local_app_strings['LNK_LIST_START'];
			$previous_link = get_image($image_path."previous_off","alt='".$this->local_app_strings['LNK_LIST_PREVIOUS']."'  border='0' align='absmiddle'")."&nbsp;".$this->local_app_strings['LNK_LIST_PREVIOUS'];
		}else{
			$start_link = "<a href=\"$start_URL\" class=\"listViewPaginationLinkS1\">".get_image($image_path."start","alt='".$this->local_app_strings['LNK_LIST_START']."'  border='0' align='absmiddle'")."&nbsp;".$this->local_app_strings['LNK_LIST_START']."</a>";
			$previous_link = "<a href=\"$previous_URL\" class=\"listViewPaginationLinkS1\">".get_image($image_path."previous","alt='".$this->local_app_strings['LNK_LIST_PREVIOUS']."'  border='0' align='absmiddle'")."&nbsp;".$this->local_app_strings['LNK_LIST_PREVIOUS']."</a>";
		}

		if($last_offset <= $current_offset){
			$end_link = $this->local_app_strings['LNK_LIST_END']."&nbsp;".get_image($image_path."end_off","alt='".$this->local_app_strings['LNK_LIST_END']."'  border='0' align='absmiddle'");
			$next_link = $this->local_app_strings['LNK_LIST_NEXT']."&nbsp;".get_image($image_path."next_off","alt='".$this->local_app_strings['LNK_LIST_NEXT']."'  border='0' align='absmiddle'");
		}
		else{
			$end_link = "<a href=\"$end_URL\" class=\"listViewPaginationLinkS1\">".$this->local_app_strings['LNK_LIST_END']."&nbsp;".get_image($image_path."end","alt='".$this->local_app_strings['LNK_LIST_END']."'  border='0' align='absmiddle'")."</a>";
			$next_link = "<a href=\"$next_URL\" class=\"listViewPaginationLinkS1\">".$this->local_app_strings['LNK_LIST_NEXT']."&nbsp;".get_image($image_path."next","alt='".$this->local_app_strings['LNK_LIST_NEXT']."'  border='0' align='absmiddle'")."</a>";
		}
		global $current_module;
		$this->log->info("Offset (next, current, prev)($next_offset, $current_offset, $previous_offset)");
		$this->log->info("Start/end records ($start_record, $end_record)");


		$end_record = $end_record-1;
		$export_link = "<a target=\"_blank\" href=\"export.php?module=".$export_module."\" class=\"listViewPaginationLinkS1\">".get_image($image_path."export","alt='".$this->local_app_strings['LBL_EXPORT']."'  border='0' align='absmiddle'")."&nbsp;".$this->local_app_strings['LBL_EXPORT']."</a>";

		if ($_REQUEST['module']== 'Home' || $this->local_current_module == 'Import'



		|| $this->show_export_button == false
		|| (isset($sugar_config['disable_export']) && $sugar_config['disable_export'])
		|| (isset($sugar_config['admin_export_only']) && $sugar_config['admin_export_only'] && !(is_admin($current_user)))
		)

		{
			$export_link = "&nbsp;";
		}


	if ( $this->show_paging == true)
	{
	$html_text = "";
	$html_text .= "<tr>\n";
	$html_text .= "<td COLSPAN=\"20\" align=\"right\">\n";
	$html_text .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\"><tr><td align=\"left\"  class=\"listViewPaginationTdS1\">$export_link</td>\n";
	$html_text .= "<td nowrap align=\"right\"  class=\"listViewPaginationTdS1\">".$start_link."&nbsp;&nbsp;".$previous_link."&nbsp;&nbsp;<span class='pageNumbers'>(".$start_record." - ".$end_record." ".$this->local_app_strings['LBL_LIST_OF']." ".$row_count.")</span>&nbsp;&nbsp;".$next_link."&nbsp;&nbsp;".$end_link."</td></tr></table>\n";
	$html_text .= "</td>\n";
	$html_text .= "</tr>\n";
	$this->xTemplate->assign("PAGINATION",$html_text);
	}

		$_SESSION['export_where'] = $this->query_where;



		$this->xTemplate->parse($xtemplateSection.".list_nav_row");


	}

}

function processOrderBy($html_varName){
	if(!isset($this->base_URL)){
		$this->base_URL = $_SERVER['PHP_SELF'];
		if(isset($_SERVER['QUERY_STRING'])){
			$this->base_URL = ereg_replace("\&".$this->getSessionVariableName($html_varName,"ORDER_BY")."=[0-9a-zA-Z\_\.]*","",$this->base_URL .'?'.$_SERVER['QUERY_STRING']);
			$this->base_URL = ereg_replace("\&".$this->getSessionVariableName($html_varName,"offset")."=[0-9]*","",$this->base_URL);
		}
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$this->base_URL .= '?';
			if(isset($_REQUEST['action'])) $this->base_URL .= '&action='.$_REQUEST['action'];
			if(isset($_REQUEST['record'])) $this->base_URL .= '&record='.$_REQUEST['record'];
			if(isset($_REQUEST['module'])) $this->base_URL .= '&module='.$_REQUEST['module'];
		}
		$this->base_URL .= "&".$this->getSessionVariableName($html_varName,"offset")."=";
		}
		$sort_URL_base = $this->base_URL. "&".$this->getSessionVariableName($html_varName,"ORDER_BY")."=";
		if($sort_URL_base !== "") $this->xTemplate->assign("ORDER_BY", $sort_URL_base);

}
function getAdditionalHeader(){
}


/**

 * @return void
 * @param unknown $data
 * @param unknown $xTemplateSection
 * @param unknown $html_varName
 * @desc INTERNAL FUNCTION handles the rows
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
 function processListRows(&$data,$xtemplateSection, $html_varName ){
 global $odd_bg;
 global $even_bg;
 global $hilite_bg;
 global $click_bg;

$this->xTemplate->assign("BG_HILITE", $hilite_bg);
$this->xTemplate->assign('CHECKALL', "<img src='include/images/blank.gif' width=\"1\" height=\"1\" alt=\"\">");
//$this->xTemplate->assign("BG_CLICK", $click_bg);
	$oddRow = true;
	$count = 0;
	reset($data);
	while(list($aVal, $aItem) = each($data))
	{
		if(isset($this->data_array)){
			$fields = $this->data_array;
		}else{
			$aItem->check_date_relationships_load();
			$fields = $aItem->get_list_view_data();
		}
		if($this->shouldProcess){
		$this->xTemplate->assign('PREROW', "<input type='checkbox' class='checkbox' name='mass[]' value='". $fields['ID']. "'>");
		$this->xTemplate->assign('CHECKALL', "<input type='checkbox' class='checkbox' name='massall' value='' onclick='checkAll(document.MassUpdate, \"mass[]\", this.checked)'>");
		}
		
		if(isset($this->data_array))
		{
			$this->xTemplate->assign('KEY', $aVal);
			$this->xTemplate->assign('VALUE', $aItem);
			$this->xTemplate->assign('INDEX', $count);
		}
		else
		{
			$this->xTemplate->assign($html_varName, $fields);
			$aItem->setupCustomFields($aItem->module_dir);
			$aItem->custom_fields->populateXTPL($this->xTemplate, 'detail');
			
		}

		$count++;
		
		
		if($oddRow)
		{
			$this->xTemplate->assign("ROW_COLOR", 'oddListRow');
			$this->xTemplate->assign("BG_COLOR", $odd_bg);
		}
		else
		{
			$this->xTemplate->assign("ROW_COLOR", 'evenListRow');
			$this->xTemplate->assign("BG_COLOR", $even_bg);
		}
		$oddRow = !$oddRow;
		if(!isset($this->data_array)){
			$aItem->list_view_parse_additional_sections($this->xTemplate, $xtemplateSection);
		
		if ($this->xTemplate->exists($xtemplateSection.'.row.pro')) {
			$this->xTemplate->parse($xtemplateSection.'.row.pro');
		}
		}
		$this->xTemplate->parse($xtemplateSection.".row");
	}
	$this->xTemplate->parse($xtemplateSection);
}
/**

 * @return void
 * @param unknown $seed
 * @param unknown $xTemplateSection
 * @param unknown $html_varName
 * @desc PUBLIC FUNCTION Handles List Views using seeds that extend SugarBean
	$XTemplateSection is the section in the XTemplate file that should be parsed usually main
	$html_VarName is the variable name used in the XTemplateFile e.g. TASK
	$seed is a seed there are two types of seeds one is a subclass of SugarBean, the other is a list usually created from a sugar bean using get_list
	if no XTemplate is set it will create  a new XTemplate
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
 */
 function processListViewTwo($seed, $xTemplateSection, $html_varName){
	if(!isset($this->xTemplate))
		$this->createXTemplate();
	$isSugarBean = is_subclass_of($seed, "SugarBean");
	$list = null;
	if($isSugarBean){
		$list =	$this->processSugarBean($xTemplateSection, $html_varName, $seed);

	}else{
		$list = &$seed;
	}

	$this->processSortArrows($html_varName);
	if ($isSugarBean) {
		$seed->parse_additional_headers($this->xTemplate, $xTemplateSection);
	}
	$this->xTemplateAssign('CHECKALL', "<img src='include/images/blank.gif' width=\"1\" height=\"1\" al=\"\">");
	$this->processOrderBy($html_varName);




	$this->processListRows($list,$xTemplateSection, $html_varName);
	if($this->display_header_and_footer){
		$this->getAdditionalHeader();
		echo get_form_header( $this->header_title, $this->header_text, false);
	}
	$this->xTemplate->out($xTemplateSection);
	if($this->display_header_and_footer)
		echo get_form_footer();
	if($isSugarBean )
		//echo "</td></tr>\n</table>\n";

	if(isset($_SESSION['validation'])){
		print base64_decode('PGEgaHJlZj0naHR0cDovL3d3dy5zdWdhcmNybS5jb20nPlBPV0VSRUQmbmJzcDtCWSZuYnNwO1NVR0FSQ1JNPC9hPg==');
}


}

function processSortArrows($html_varName){
	global $png_support;
	if ($png_support == false)
	$ext = "gif";
	else
	$ext = "png";
	$this->xTemplateAssign("arrow_start", "&nbsp;<img border='0' src='".$this->local_image_path."arrow");
	$orderBy = $this->getSessionVariable($html_varName, "OBL");

	$desc = $this->getSessionVariable($html_varName, $orderBy.'S');
	$orderBy = str_replace('.', '_', $orderBy);

	$imgArrow = "_down";
	if($desc){
		$imgArrow = "_up";
	}
		$image=$this->local_image_path."arrow".$imgArrow;
		$size=getimagesize($image.'.'.$ext);
		$width = $size[0];
		$height = $size[1];
	if($orderBy == 'amount*1')
		$this->xTemplateAssign('amount_arrow', $imgArrow);
	else{
		$this->xTemplateAssign($orderBy.'_arrow', $imgArrow);
	}
	$this->xTemplateAssign('arrow_end', ".$ext' width='$width' height='$height' align='absmiddle' alt='Sort'>");
}

}



?>
