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
 * $Id: index.php,v 1.67.2.1 2005/05/05 21:49:41 andrew Exp $
 * Description:  Main file for the Home module.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All R$modListHeaderights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;

$_REQUEST['search_form'] = 'false';
$_REQUEST['query'] = 'true';
$_REQUEST['status'] = 'In Progress--Not Started';
$_REQUEST['current_user_only'] = 'On';

$task_title = $mod_strings['LBL_OPEN_TASKS'];

?>
<script type="text/javascript" language="JavaScript">
<!-- Begin
function toggleDisplay(id){

	if(this.document.getElementById( id).style.display=='none'){
		this.document.getElementById( id).style.display='inline'
		if(this.document.getElementById(id+"link") != undefined){
			this.document.getElementById(id+"link").style.display='none';
		}

	}else{
		this.document.getElementById(  id).style.display='none'
		if(this.document.getElementById(id+"link") != undefined){
			this.document.getElementById(id+"link").style.display='inline';
		}
	}
}
		//  End -->
	</script>

<?PHP
	$home_xtpl = new XTemplate('modules/Home/Home.html');

	$panels	= array();

	if(array_key_exists('Activities', $modListHeader))
	{
		$panels['MYAPPOINTMENTS'] = "modules/Activities/OpenListView.php";
	}

	if(array_key_exists('Opportunities', $modListHeader))
		$panels['MYOPPORTUNITIES'] = "modules/Opportunities/ListViewTop.php";

	if(array_key_exists('Cases', $modListHeader))
		$panels['MYCASES'] = "modules/Cases/MyCases.php";

	if(array_key_exists('Activities', $modListHeader))
		$panels['MYTASKS'] = "modules/Tasks/MyTasks.php";

	if(array_key_exists('Leads', $modListHeader))
		$panels['MYLEADS'] = "modules/Leads/MyLeads.php";

	if(array_key_exists('Bugs', $modListHeader))
		$panels['MYBUGS'] = "modules/Bugs/MyBugs.php";
	
	if(array_key_exists('Calendar', $modListHeader))
		$panels['MYCAL'] = "modules/Calendar/small_month.php";






	if(array_key_exists('Dashboard', $modListHeader))
		$panels['MYPIPELINE'] = "modules/Dashboard/Chart_my_pipeline_by_sales_stage.php";

	if(array_key_exists('Project', $modListHeader))
	{
		$panels['MY_PROJECT_TASKS'] = "modules/ProjectTask/MyProjectTasks.php";
	}
		
	$section = 'main';
	$old_contents = ob_get_contents();
	ob_end_clean();
	$processed= array();
	foreach($panels as $name=>$path){
			
		if( $home_xtpl->var_exists($section,$name)){
			ob_start();
			include($path);
			echo "<BR>\n";
			$temp =  ob_get_contents();
			$processed[$name] = $temp;
			ob_end_clean();
		}
	}
		
	ob_start();
	echo $old_contents;
	foreach($processed as $name=>$val){

			$home_xtpl->assign($name, $val);
	}
	global $current_user;

	if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){
		echo "<a href='index.php?action=index&module=DynamicLayout&from_action=Home&from_module=Home'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>";
	}

	$home_xtpl->parse($section);
	$home_xtpl->out($section);

 ?>
 
<script type="text/javascript" language="JavaScript">
<!--
    //document.UnifiedSearch.query_string.focus();
   // document.UnifiedSearch.query_string.select();
//-->
</script>
