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
* $Id: Charts.php,v 1.80.2.2 2005/05/18 00:59:49 majed Exp $
* Description:  Includes the functions for Customer module specific charts.
********************************************************************************/

require_once('config.php');
require_once('include/logging.php');
require_once('modules/Opportunities/Opportunity.php');
require_once('include/charts/Charts.php');
require_once('include/utils.php');
require_once('include/logging.php');

class charts {
	/**
	* Creates opportunity pipeline image as a VERTICAL accumlated bar graph for multiple users.
	* param $datax- the month data to display in the x-axis
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	* All Rights Reserved..
	* Contributor(s): ______________________________________..
	*/
	function outcome_by_month($date_start='1971-10-15', $date_end='2071-10-15', $user_id=array('1'), $cache_file_name='a_file', $refresh=false) {
		global $app_strings, $app_list_strings, $current_module_strings, $log, $charset, $lang, $barChartColors;
		$log =& LoggerManager::getLogger('outcome_by_month chart');
		if (!file_exists($cache_file_name) || $refresh == true) {
			$log->debug("date_start is: $date_start");
			$log->debug("date_end is: $date_end");
			$log->debug("user_id is: ");
			$log->debug($user_id);
			$log->debug("cache_file_name is: $cache_file_name");

			$where = "";
			//build the where clause for the query that matches $user
			$count = count($user_id);
			$id = array();
			if ($count>0) {
				foreach ($user_id as $the_id) {
					$id[] = "'".$the_id."'";
				}
				$ids = join(",",$id);
				$where .= "opportunities.assigned_user_id IN ($ids) ";

			}


			$opp = new Opportunity();
			//build the where clause for the query that matches $date_start and $date_end
			$where .= "AND opportunities.date_closed >= '$date_start' AND opportunities.date_closed <= '$date_end' AND opportunities.deleted=0";
			$query = "SELECT sales_stage,date_format(opportunities.date_closed,'%Y-%m')  as m,sum(amount/1000) as total, count(*) as opp_count FROM opportunities ";



			$query .= "WHERE ".$where;
			$query .= " GROUP BY sales_stage,m ORDER BY m";
			//Now do the db queries
			//query for opportunity data that matches $datay and $user
			$result = $opp->db->query($query)
			or sugar_die("Error selecting sugarbean: ".mysqli_error($varconnect));
			//build pipeline by sales stage data
			$total = 0;
			$div = 1;
			$symbol = translate('LBL_CURRENCY_SYMBOL');
			$other = $current_module_strings['LBL_LEAD_SOURCE_OTHER'];
			$rowTotalArr = array();
			$rowTotalArr[] = 0;
			global $current_user;
			$salesStages = array("Closed Lost"=>$app_list_strings['sales_stage_dom']["Closed Lost"],"Closed Won"=>$app_list_strings['sales_stage_dom']["Closed Won"],"Other"=>$other);
			if($current_user->getPreference('currency') ){
				require_once('modules/Currencies/Currency.php');
				$currency = new Currency();
				$currency->retrieve($current_user->getPreference('currency'));
				$div = $currency->conversion_rate;
				$symbol = $currency->symbol;
			}
			$months = array();
			$monthArr = array();
			while($row = $opp->db->fetchByAssoc($result, -1, false))
			{
				if($row['total']*$div<=100){
					$sum = round($row['total']*$div, 2);
				} else {
					$sum = round($row['total']*$div);
				}
				if($row['sales_stage'] == 'Closed Won' || $row['sales_stage'] == 'Closed Lost'){
					$salesStage = $row['sales_stage'];
					$salesStageT = $app_list_strings['sales_stage_dom'][$row['sales_stage']];
				} else {
					$salesStage = "Other";
					$salesStageT = $other;
				}

				$months[$row['m']] = $row['m'];
				if(!isset($monthArr[$row['m']]['row_total'])) {$monthArr[$row['m']]['row_total']=0;}
				$monthArr[$row['m']][$salesStage]['opp_count'][] = $row['opp_count'];
				$monthArr[$row['m']][$salesStage]['total'][] = $sum;
				$monthArr[$row['m']]['outcome'][$salesStage]=$salesStageT;
				$monthArr[$row['m']]['row_total'] += $sum;

				$total += $sum;
			}

			$fileContents = '     <xData length="20">'."\n";
			if (!empty($months)) {
				foreach ($months as $month){
					$rowTotalArr[]=$monthArr[$month]['row_total'];
					if($monthArr[$month]['row_total']>100)
					{
						$monthArr[$month]['row_total']=round($monthArr[$month]['row_total']);
					}
					$fileContents .= '          <dataRow title="'.$month.'" endLabel="'.$monthArr[$month]['row_total'].'">'."\n";
					arsort($salesStages);
					foreach ($salesStages as $outcome=>$outcome_translation){
						if(isset($monthArr[$month][$outcome])) {
						$fileContents .= '               <bar id="'.$outcome.'" totalSize="'.array_sum($monthArr[$month][$outcome]['total']).'" altText="'.$month.': '.array_sum($monthArr[$month][$outcome]['opp_count']).' '.$current_module_strings['LBL_OPPS_WORTH'].' '.array_sum($monthArr[$month][$outcome]['total']).$current_module_strings['LBL_OPP_THOUSANDS'].' '.$current_module_strings['LBL_OPPS_OUTCOME'].' '.$outcome_translation.'" url="index.php?module=Opportunities&action=index&date_closed='.$month.'&sales_stage='.urlencode($outcome).'&query=true"/>'."\n";
						}
					}
					$fileContents .= '          </dataRow>'."\n";
				}
			} else {
				$fileContents .= '          <dataRow title="" endLabel="">'."\n";
				$fileContents .= '               <bar id="" totalSize="0" altText="" url=""/>'."\n";
				$fileContents .= '          </dataRow>'."\n";
				$rowTotalArr[] = 1000;
			}
			$fileContents .= '     </xData>'."\n";
			$max = get_max($rowTotalArr);
			$fileContents .= '     <yData min="0" max="'.$max.'" length="10" prefix="'.$symbol.'" suffix="" defaultAltText="'.$current_module_strings['LBL_ROLLOVER_DETAILS'].'"/>'."\n";
			$fileContents .= '     <colorLegend>'."\n";
			$i=0;
			asort($salesStages);
			foreach ($salesStages as $outcome=>$outcome_translation) {
				$color = generate_graphcolor($outcome,$i);
				$fileContents .= '          <mapping id="'.$outcome.'" name="'.$outcome_translation.'" color="'.$color.'"/>'."\n";
				$i++;
			}
			$fileContents .= '     </colorLegend>'."\n";
			$fileContents .= '     <graphInfo>'."\n";
			$fileContents .= '          <![CDATA['.$current_module_strings['LBL_DATE_RANGE']." ".$date_start." ".$current_module_strings['LBL_DATE_RANGE_TO']." ".$date_end."<br/>".$current_module_strings['LBL_OPP_SIZE'].' '.$symbol.'1'.$current_module_strings['LBL_OPP_THOUSANDS'].']]>'."\n";
			$fileContents .= '     </graphInfo>'."\n";
			$fileContents .= '     <chartColors ';
			foreach ($barChartColors as $key => $value) {
				$fileContents .= ' '.$key.'='.'"'.$value.'" ';
			}
			$fileContents .= ' />'."\n";
			$fileContents .= '</graphData>'."\n";
			$total = round($total, 2);
			$title = '<graphData title="'.$current_module_strings['LBL_TOTAL_PIPELINE'].$symbol.$total.$app_strings['LBL_THOUSANDS_SYMBOL'].'">'."\n";
			$fileContents = $title.$fileContents;

			//echo $fileContents;
			save_xml_file($cache_file_name, $fileContents);
		}
		$return = create_chart('vBarF',$cache_file_name);
		return $return;
	}


	/**
	* Creates lead_source_by_outcome pipeline image as a HORIZONAL accumlated bar graph for multiple users.
	* param $datay- the lead source data to display in the x-axis
	* param $ids - list of assigned users of opps to find
	* param $cache_file_name - file name to write image to
	* param $refresh - boolean whether to rebuild image if exists
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	* All Rights Reserved..
	* Contributor(s): ______________________________________..
	*/
	function lead_source_by_outcome($datay=array('foo','bar'), $user_id=array('1'), $cache_file_name='a_file', $refresh=false) {
		global $app_strings, $current_module_strings, $log, $charset, $lang, $barChartColors,$app_list_strings;
		$log =& LoggerManager::getLogger('lead_source_by_outcome chart');
		if (!file_exists($cache_file_name) || $refresh == true) {
			$log->debug("datay is:");
			$log->debug($datay);
			$log->debug("user_id is: ");
			$log->debug($user_id);
			$log->debug("cache_file_name is: $cache_file_name");
			$opp = new Opportunity();
			$where="";
			//build the where clause for the query that matches $user
			$count = count($user_id);
			$id = array();
			if ($count>0) {
				foreach ($user_id as $the_id) {
					$id[] = "'".$the_id."'";
				}
				$ids = join(",",$id);
				$where .= "opportunities.assigned_user_id IN ($ids) ";

			}

			//build the where clause for the query that matches $datay
			$count = count($datay);
			$datayArr = array();
			if ($count>0) {

				foreach ($datay as $key=>$value) {
					$datayArr[] = "'".$key."'";
				}
				$datayArr = join(",",$datayArr);
				$where .= "AND opportunities.lead_source IN	($datayArr) ";
			}
			$query = "SELECT lead_source,sales_stage,sum(amount/1000) as total,count(*) as opp_count FROM opportunities ";



			$query .= "WHERE " .$where." AND opportunities.deleted=0 ";
			$query .= " GROUP BY sales_stage,lead_source ORDER BY lead_source,sales_stage";
			//Now do the db queries
			//query for opportunity data that matches $datay and $user
			
			$result = $opp->db->query($query)
			or sugar_die("Error selecting sugarbean: ".mysqli_error($varconnect));
			//build pipeline by sales stage data
			$total = 0;
			$div = 1;
			$symbol = translate('LBL_CURRENCY_SYMBOL');
			$other = $current_module_strings['LBL_LEAD_SOURCE_OTHER'];
			$rowTotalArr = array();
			$rowTotalArr[] = 0;
			global $current_user;
			$salesStages = array("Closed Lost"=>$app_list_strings['sales_stage_dom']["Closed Lost"],"Closed Won"=>$app_list_strings['sales_stage_dom']["Closed Won"],"Other"=>$other);
			if($current_user->getPreference('currency') ){
				require_once('modules/Currencies/Currency.php');
				$currency = new Currency();
				$currency->retrieve($current_user->getPreference('currency'));
				$div = $currency->conversion_rate;
				$symbol = $currency->symbol;
			}
			$fileContents = '     <yData defaultAltText="'.$current_module_strings['LBL_ROLLOVER_DETAILS'].'">'."\n";
			$leadSourceArr = array();
			while($row = $opp->db->fetchByAssoc($result, -1, false))
			{
				if($row['total']*$div<=100){
					$sum = round($row['total']*$div, 2);
				} else {
					$sum = round($row['total']*$div);
				}
				if($row['lead_source'] == ''){
					$row['lead_source'] = $current_module_strings['NTC_NO_LEGENDS'];
				}
				if($row['sales_stage'] == 'Closed Won' || $row['sales_stage'] == 'Closed Lost'){
					$salesStage = $row['sales_stage'];
					$salesStageT = $app_list_strings['sales_stage_dom'][$row['sales_stage']];
				} else {
					$salesStage = "Other";
					$salesStageT = $other;
				}
				if(!isset($leadSourceArr[$row['lead_source']]['row_total'])) {$leadSourceArr[$row['lead_source']]['row_total']=0;}
				$leadSourceArr[$row['lead_source']][$salesStage]['opp_count'][] = $row['opp_count'];
				$leadSourceArr[$row['lead_source']][$salesStage]['total'][] = $sum;
				$leadSourceArr[$row['lead_source']]['outcome'][$salesStage]=$salesStageT;
				$leadSourceArr[$row['lead_source']]['row_total'] += $sum;

				$total += $sum;
			}
			foreach ($datay as $key=>$translation) {
				if ($key == '') {
					$key = $current_module_strings['NTC_NO_LEGENDS'];
					$translation = $current_module_strings['NTC_NO_LEGENDS'];
				}
				if(!isset($leadSourceArr[$key])){
					$leadSourceArr[$key] = $key;
				}
				if(isset($leadSourceArr[$key]['row_total'])){$rowTotalArr[]=$leadSourceArr[$key]['row_total'];}
				if(isset($leadSourceArr[$key]['row_total']) && $leadSourceArr[$key]['row_total']>100){
					$leadSourceArr[$key]['row_total'] = round($leadSourceArr[$key]['row_total']);
				}
				$fileContents .= '          <dataRow title="'.$translation.'" endLabel="'.$leadSourceArr[$key]['row_total'].'">'."\n";
				if(is_array($leadSourceArr[$key]['outcome'])){
					foreach ($leadSourceArr[$key]['outcome'] as $outcome=>$outcome_translation){
						$fileContents .= '               <bar id="'.$outcome.'" totalSize="'.array_sum($leadSourceArr[$key][$outcome]['total']).'" altText="'.array_sum($leadSourceArr[$key][$outcome]['opp_count']).' '.$current_module_strings['LBL_OPPS_WORTH'].' '.array_sum($leadSourceArr[$key][$outcome]['total']).$current_module_strings['LBL_OPP_THOUSANDS'].' '.$current_module_strings['LBL_OPPS_OUTCOME'].' '.$outcome_translation.'" url="index.php?module=Opportunities&action=index&lead_source='.$key.'&sales_stage='.urlencode($outcome).'&query=true"/>'."\n";
					}
				}
				$fileContents .= '          </dataRow>'."\n";
			}
			$fileContents .= '     </yData>'."\n";
			$max = get_max($rowTotalArr);
			$fileContents .= '     <xData min="0" max="'.$max.'" length="10" prefix="'.$symbol.'" suffix=""/>'."\n";
			$fileContents .= '     <colorLegend>'."\n";
			$i=0;

				foreach ($salesStages as $outcome=>$outcome_translation) {
					$color = generate_graphcolor($outcome,$i);
					$fileContents .= '          <mapping id="'.$outcome.'" name="'.$outcome_translation.'" color="'.$color.'"/>'."\n";
					$i++;
				}
			$fileContents .= '     </colorLegend>'."\n";
			$fileContents .= '     <graphInfo>'."\n";
			$fileContents .= '          <![CDATA['.$current_module_strings['LBL_OPP_SIZE'].' '.$symbol.'1'.$current_module_strings['LBL_OPP_THOUSANDS'].']]>'."\n";
			$fileContents .= '     </graphInfo>'."\n";
			$fileContents .= '     <chartColors ';
			foreach ($barChartColors as $key => $value) {
				$fileContents .= ' '.$key.'='.'"'.$value.'" ';
			}
			$fileContents .= ' />'."\n";
			$fileContents .= '</graphData>'."\n";
			$total = round($total, 2);
			$title = '<graphData title="'.$current_module_strings['LBL_ALL_OPPORTUNITIES'].$symbol.$total.$app_strings['LBL_THOUSANDS_SYMBOL'].'">'."\n";
			$fileContents = $title.$fileContents;

			save_xml_file($cache_file_name, $fileContents);
		}
		$return = create_chart('hBarF',$cache_file_name);
		return $return;
	}

	/**
	* Creates opportunity pipeline image as a HORIZONTAL accumlated BAR GRAPH for multiple users.
	* param $datax- the sales stage data to display in the x-axis
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	* All Rights Reserved..
	* Contributor(s): ______________________________________..
	*/
	function pipeline_by_sales_stage($datax=array('foo','bar'), $date_start='2071-10-15', $date_end='2071-10-15', $user_id=array('1'), $cache_file_name='a_file', $refresh=false,$chart_size='hBarF') {
		global $app_strings, $current_module_strings, $log, $charset, $lang, $barChartColors;

		$log =& LoggerManager::getLogger('opportunity charts');

		if (!file_exists($cache_file_name) || $refresh == true) {

			$log->debug("starting pipeline chart");
			$log->debug("datax is:");
			$log->debug($datax);
			$log->debug("user_id is: ");
			$log->debug($user_id);
			$log->debug("cache_file_name is: $cache_file_name");
			$opp = new Opportunity;
			$where="";
			//build the where clause for the query that matches $user
			$count = count($user_id);
			$id = array();
			$user_list = get_user_array(false);
			foreach ($user_id as $key) {
				$new_ids[$key] = $user_list[$key];
			}
			if ($count>0) {
				foreach ($new_ids as $the_id=>$the_name) {
					$id[] = "'".$the_id."'";
				}
				$ids = join(",",$id);
				$where .= "opportunities.assigned_user_id IN ($ids) ";

			}
			//build the where clause for the query that matches $datax
			$count = count($datax);
			$dataxArr = array();
			if ($count>0) {

				foreach ($datax as $key=>$value) {
					$dataxArr[] = "'".$key."'";
				}
				$dataxArr = join(",",$dataxArr);
				$where .= "AND opportunities.sales_stage IN	($dataxArr) ";
			}

			//build the where clause for the query that matches $date_start and $date_end
			$where .= "AND opportunities.date_closed >= '$date_start' AND opportunities.date_closed <= '$date_end' ";
			$where .= "AND opportunities.assigned_user_id = users.id  AND opportunities.deleted=0 ";

			//Now do the db queries
			//query for opportunity data that matches $datax and $user
			$query = "SELECT opportunities.sales_stage,users.user_name,opportunities.assigned_user_id,count( * ) AS opp_count, sum(amount/1000) as total FROM opportunities,users  ";



			$query .= "WHERE " .$where;
			$query .= " GROUP BY opportunities.sales_stage,users.user_name,opportunities.assigned_user_id";
			
			$result = $opp->db->query($query)
			or sugar_die("Error selecting sugarbean: ".mysqli_error($varconnect));
			//build pipeline by sales stage data
			$total = 0;
			$div = 1;
			$symbol = translate('LBL_CURRENCY_SYMBOL');
			global $current_user;
			if($current_user->getPreference('currency') ){
				require_once('modules/Currencies/Currency.php');
				$currency = new Currency();
				$currency->retrieve($current_user->getPreference('currency'));
				$div = $currency->conversion_rate;
				$symbol = $currency->symbol;
			}
			$fileContents = '     <yData defaultAltText="'.$current_module_strings['LBL_ROLLOVER_DETAILS'].'">'."\n";
			$stageArr = array();
			$usernameArr = array();
			$rowTotalArr = array();
			$rowTotalArr[] = 0;
			while($row = $opp->db->fetchByAssoc($result, -1, false))
			{
				if($row['total']*$div<=100){
					$sum = round($row['total']*$div, 2);
				} else {
					$sum = round($row['total']*$div);
				}
				if(!isset($stageArr[$row['sales_stage']]['row_total'])) {$stageArr[$row['sales_stage']]['row_total']=0;}
				$stageArr[$row['sales_stage']][$row['assigned_user_id']]['opp_count'] = $row['opp_count'];
				$stageArr[$row['sales_stage']][$row['assigned_user_id']]['total'] = $sum;
				$stageArr[$row['sales_stage']]['people'][$row['assigned_user_id']] = $row['user_name'];
				$stageArr[$row['sales_stage']]['row_total'] += $sum;

				$usernameArr[$row['assigned_user_id']] = $row['user_name'];
				$total += $sum;
			}
			foreach ($datax as $key=>$translation) {
				if(isset($stageArr[$key]['row_total'])){$rowTotalArr[]=$stageArr[$key]['row_total'];}
				if(isset($stageArr[$key]['row_total']) && $stageArr[$key]['row_total']>100) {
					$stageArr[$key]['row_total'] = round($stageArr[$key]['row_total']);
				}
				$fileContents .= '     <dataRow title="'.$translation.'" endLabel="';
				if(isset($stageArr[$key]['row_total'])){$fileContents .= $stageArr[$key]['row_total'];}
				$fileContents .= '">'."\n";
				if(isset($stageArr[$key]['people'])){
					asort($stageArr[$key]['people']);
					reset($stageArr[$key]['people']);
					foreach ($stageArr[$key]['people'] as $nameKey=>$nameValue) {
						$fileContents .= '          <bar id="'.$nameKey.'" totalSize="'.$stageArr[$key][$nameKey]['total'].'" altText="'.$nameValue.': '.$stageArr[$key][$nameKey]['opp_count'].' '.$current_module_strings['LBL_OPPS_WORTH'].' '.$stageArr[$key][$nameKey]['total'].$current_module_strings['LBL_OPP_THOUSANDS'].' '.$current_module_strings['LBL_OPPS_IN_STAGE'].' '.$translation.'" url="index.php?module=Opportunities&action=index&assigned_user_id[]='.$nameKey.'&sales_stage='.urlencode($key).'&date_start='.$date_start.'&date_closed='.$date_end.'&query=true"/>'."\n";
					}
				}
				$fileContents .= '     </dataRow>'."\n";
			}
			$fileContents .= '     </yData>'."\n";
			$max = get_max($rowTotalArr);
			if($chart_size=='hBarF'){
				$length = "10";
			}else{
				$length = "4";
			}
			$fileContents .= '     <xData min="0" max="'.$max.'" length="'.$length.'" prefix="'.$symbol.'" suffix=""/>'."\n";
			$fileContents .= '     <colorLegend>'."\n";
			$i=0;
			asort($new_ids);
			foreach ($new_ids as $key=>$value) {
			$color = generate_graphcolor($key,$i);
			$fileContents .= '          <mapping id="'.$key.'" name="'.$value.'" color="'.$color.'"/>'."\n";
			$i++;
			}
			$fileContents .= '     </colorLegend>'."\n";
			$fileContents .= '     <graphInfo>'."\n";
			$fileContents .= '          <![CDATA['.$current_module_strings['LBL_DATE_RANGE'].' '.$date_start.' '.$current_module_strings['LBL_DATE_RANGE_TO'].' '.$date_end.'<BR/>'.$current_module_strings['LBL_OPP_SIZE'].' '.$symbol.'1'.$current_module_strings['LBL_OPP_THOUSANDS'].']]>'."\n";
			$fileContents .= '     </graphInfo>'."\n";
			$fileContents .= '     <chartColors ';
			foreach ($barChartColors as $key => $value) {
				$fileContents .= ' '.$key.'='.'"'.$value.'" ';
			}
			$fileContents .= ' />'."\n";
			$fileContents .= '</graphData>'."\n";
			$total = $total;
			$title = '<graphData title="'.$current_module_strings['LBL_TOTAL_PIPELINE'].$symbol.$total.$app_strings['LBL_THOUSANDS_SYMBOL'].'">'."\n";
			$fileContents = $title.$fileContents;

			save_xml_file($cache_file_name, $fileContents);
		}

		if($chart_size=='hBarF'){
			$width = "800";
			$height = "400";
		} else {
			$width = "350";
			$height = "400";
		}
		$return = create_chart($chart_size,$cache_file_name,$width,$height);
		return $return;
	}


	/**
	* Creates PIE CHART image of opportunities by lead_source.
	* param $datax- the sales stage data to display in the x-axis
	* param $datay- the sum of opportunity amounts for each opportunity in each sales stage
	* to display in the y-axis
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	* All Rights Reserved..
	* Contributor(s): ______________________________________..
	*/
	function pipeline_by_lead_source($legends=array('foo','bar'), $user_id=array('1'), $cache_file_name='a_file', $refresh=true) {
		global $app_strings, $current_module_strings, $log, $charset, $lang, $pieChartColors;


		if (!file_exists($cache_file_name) || $refresh == true) {
			$log =& LoggerManager::getLogger('opportunity charts');
			$log->debug("starting pipeline chart");
			$log->debug("legends is:");
			$log->debug($legends);
			$log->debug("user_id is: ");
			$log->debug($user_id);
			$log->debug("cache_file_name is: $cache_file_name");

			$opp = new Opportunity;
			//Now do the db queries
			//query for opportunity data that matches $legends and $user
			$where="";
			//build the where clause for the query that matches $user
			$count = count($user_id);
			$id = array();
			if ($count>0) {
				foreach ($user_id as $the_id) {
					$id[] = "'".$the_id."'";
				}
				$ids = join(",",$id);
				$where .= "opportunities.assigned_user_id IN ($ids) ";

			}

			//build the where clause for the query that matches $datax
			$count = count($legends);
			$legendItem = array();
			if ($count>0) {

				foreach ($legends as $key=>$value) {
					$legendItem[] = "'".$key."'";
				}
				$legendItems = join(",",$legendItem);
				$where .= "AND opportunities.lead_source IN	($legendItems) ";
			}
			$query = "SELECT lead_source,sum(amount/1000) as total,count(*) as opp_count FROM opportunities ";



			$query .= "WHERE ".$where." AND opportunities.deleted=0 ";
			$query .= "GROUP BY lead_source ORDER BY opportunities.amount_usdollar DESC, opportunities.date_closed DESC";
			
			//build pipeline by lead source data
			$total = 0;
			$div = 1;
			$symbol = translate('LBL_CURRENCY_SYMBOL');
			global $current_user;
			if($current_user->getPreference('currency') ) {
				require_once('modules/Currencies/Currency.php');
				$currency = new Currency();
				$currency->retrieve($current_user->getPreference('currency'));
				$div = $currency->conversion_rate;
				$symbol = $currency->symbol;
			}
			$subtitle = $current_module_strings['LBL_OPP_SIZE'].' '.$symbol.'1'.$current_module_strings['LBL_OPP_THOUSANDS'];
			$fileContents = '';
			$fileContents .= '     <pie defaultAltText="'.$current_module_strings['LBL_ROLLOVER_WEDGE_DETAILS'].'">'."\n";
			$result = $opp->db->query($query)
			or sugar_die("Error selecting sugarbean: ".mysqli_error($varconnect));
			$leadSourceArr =  array();
			while($row = $opp->db->fetchByAssoc($result, -1, false))
			{
				if($row['lead_source'] == ''){
					$leadSource = $current_module_strings['NTC_NO_LEGENDS'];
				} else {
					$leadSource = $row['lead_source'];
				}
				if($row['total']*$div<=100){
					$sum = round($row['total']*$div, 2);
				} else {
					$sum = round($row['total']*$div);
				}

				$leadSourceArr[$leadSource]['opp_count'] = $row['opp_count'];
				$leadSourceArr[$leadSource]['sum'] = $sum;
			}
			$i=0;
			foreach ($legends as $lead_source_key=>$translation) {
				if ($lead_source_key == '') {
					$lead_source_key = $current_module_strings['NTC_NO_LEGENDS'];
					$translation = $current_module_strings['NTC_NO_LEGENDS'];
				}
				if(!isset($leadSourceArr[$lead_source_key])) {
					$leadSourceArr[$lead_source_key] = $lead_source_key;
					$leadSourceArr[$lead_source_key]['sum'] = 0;
				}
				$color = generate_graphcolor($lead_source_key,$i);
				$fileContents .= '          <wedge title="'.$translation.'" value="'.$leadSourceArr[$lead_source_key]['sum'].'" color="'.$color.'" labelText="'.$symbol.$leadSourceArr[$lead_source_key]['sum'].'" url="index.php?module=Opportunities&action=index&lead_source='.urlencode($lead_source_key).'&query=true" altText="'.$leadSourceArr[$lead_source_key]['opp_count'].' '.$current_module_strings['LBL_OPPS_IN_LEAD_SOURCE'].' '.$translation.'"/>'."\n";
				if(isset($leadSourceArr[$lead_source_key])){$total += $leadSourceArr[$lead_source_key]['sum'];}
				$i++;
			}

			$fileContents .= '     </pie>'."\n";
			$fileContents .= '     <graphInfo>'."\n";
			$fileContents .= '          <![CDATA[]]>'."\n";
			$fileContents .= '     </graphInfo>'."\n";
			$fileContents .= '     <chartColors ';
			foreach ($pieChartColors as $key => $value) {
				$fileContents .= ' '.$key.'='.'"'.$value.'" ';
			}
			$fileContents .= ' />'."\n";
			$fileContents .= '</graphData>'."\n";
			$total = round($total, 2);
			$title = $current_module_strings['LBL_TOTAL_PIPELINE'].$symbol.$total.$app_strings['LBL_THOUSANDS_SYMBOL'];
			$fileContents = '<graphData title="'.$title.'" subtitle="'.$subtitle.'">'."\n" . $fileContents;
			$log->debug("total is: $total");
			if ($total == 0) {
				return ($current_module_strings['ERR_NO_OPPS']);
			}

			save_xml_file($cache_file_name, $fileContents);
		}

		$return = create_chart('pieF',$cache_file_name);
		return $return;

	}

}// end charts class




function generate_graphcolor($input,$instance) {
	if ($instance <20) {
	$color = array(
	"0xFF0000",
	"0x00FF00",
	"0x0000FF",
	"0xFF6600",
	"0x42FF8E",
	"0x6600FF",
	"0xFFFF00",
	"0x00FFFF",
	"0xFF00FF",
	"0x66FF00",
	"0x0066FF",
	"0xFF0066",
	"0xCC0000",
	"0x00CC00",
	"0x0000CC",
	"0xCC6600",
	"0x00CC66",
	"0x6600CC",
	"0xCCCC00",
	"0x00CCCC");
	$out = $color[$instance];
	} else {
	$out = "0x" . substr(md5($input), 0, 6);

	}
	return $out;
}

function save_xml_file($filename,$xml_file) {
	global $app_strings;

	$log =& LoggerManager::getLogger('save_xml_file');

	if (!$handle = fopen($filename, 'w')) {
		$log->debug("Cannot open file ($filename)");
		return;
	}
	// Write $somecontent to our opened file.)
if ($app_strings['LBL_CHARSET'] != "UTF-8") {
	if (fwrite($handle,utf8_encode($xml_file)) === FALSE) {
		$log->debug("Cannot write to file ($filename)");
		return false;
	}
} else {
	if (fwrite($handle,$xml_file) === FALSE) {
		$log->debug("Cannot write to file ($filename)");
		return false;
	}
}

	$log->debug("Success, wrote ($xml_file) to file ($filename)");

	fclose($handle);
	return true;

}

function get_max($numbers) {
	$num_len =  strlen(floor(max($numbers)))-1;
	$whole=pow(10,$num_len);
	$dec=1/$whole;
	$max = ceil(max($numbers)*$dec)*$whole;
	return $max;
}

// retrieve the translated strings.
$app_strings = return_application_language($current_language);

if(isset($app_strings['LBL_CHARSET']))
{
	$charset = $app_strings['LBL_CHARSET'];
}
else
{
	$charset = $sugar_config['default_charset'];
}
?>
