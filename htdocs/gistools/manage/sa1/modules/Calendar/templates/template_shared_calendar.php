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
 * $Id: template_shared_calendar.php,v 1.14 2005/03/23 19:07:21 robert Exp $
 ********************************************************************************/
include_once("modules/Calendar/Calendar.php");
include_once("modules/Calendar/templates/templates_calendar.php");

function template_shared_calendar(&$args)
{

global $current_user;
global $app_strings;
global $mod_strings;
$date_arr= array("activity_focus"=>$args['activity_focus']);
$calendar = new Calendar("day",$date_arr);
$calendar->show_tasks = false;
$calendar->toggle_appt = false;
foreach($args['users'] as $user)
{
/*
	if ($user->id != $current_user->id)
	{
*/
		$calendar->add_activities($user,'vfb');
/*
	}
*/
}
?>
<p>

<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td><h5 class="listViewSubHeadS1"><?php echo $mod_strings['LBL_USER_CALENDARS']; ?></h5>
</td>
<td align=right>
<h5 class="listViewSubHeadS1"><?php template_echo_date_info("day",$calendar->date_time);?></h5>
</td></tr></table>
<table cellpadding="0" cellspacing="0" width="100%" border="0" class="listView">
        <tr height="20">
        <td scope="col" width="25%" class="listViewThS1"><?php echo $app_strings['LBL_LIST_NAME']; ?></td>
<?php
 $start_slice_idx = $calendar->get_start_slice_idx();
  $end_slice_idx = $calendar->get_end_slice_idx();
  $cur_slice_idx = 1;
  $slice_args = array();
  for($cur_slice_idx=$start_slice_idx;$cur_slice_idx<=$end_slice_idx;$cur_slice_idx++)
  {
        $slice_args['slice'] = $calendar->slice_hash[$calendar->slices_arr[$cur_slice_idx]];
        $slice_args['calendar'] = $calendar;
        //print_r($cur_time);
  ?>
	<td class="listViewThS1"><?php template_echo_slice_date($slice_args) ; ?></td>
<?php
  }
?>
        </tr>
<?php
global $hilite_bg, $click_bg, $odd_bg, $even_bg;
$oddRow = true;
foreach($args['users'] as $curr_user)
{

	if($oddRow)
	{
		$bg_color = $odd_bg;
		$row_class = 'oddListRowS1';
	} else
	{
		$bg_color = $even_bg;
		$row_class = 'evenListRowS1';
	}
	$oddRow = !$oddRow;
?>
<tr height="20"> 
<td scope="row" valign=TOP  class="<?php echo $row_class; ?>" bgcolor="<?php echo $bg_color; ?>"><a href="index.php?action=DetailView&module=Users&record=<?php echo $curr_user->id; ?>" class="listViewTdLinkS1"><?php echo $curr_user->first_name; ?>&nbsp;<?php echo $curr_user->last_name; ?></a></td>
<?php
  // loop through each slice for this user and show free/busy
  for($cur_slice_idx=$start_slice_idx;$cur_slice_idx<=$end_slice_idx;$cur_slice_idx++)
  {

  $cur_slice =  $calendar->slice_hash[$calendar->slices_arr[$cur_slice_idx]];

  // if this current activitiy occurs within this time slice
	if ( Calendar::occurs_within_slice($cur_slice,$calendar->activity_focus))
	{
/*
		$got_conflict = 0;
		if ( isset($cur_slice->acts_arr[$curr_user->id]) )
		{
			foreach( $cur_slice->acts_arr[$curr_user->id] as $act)
			{
				if ($act->sugar_bean->id != $calendar->activity_focus->sugar_bean->id)
				{
					$got_conflict = 1;
				}
			}
		}
*/

		if (isset($cur_slice->acts_arr[$curr_user->id]) && count($cur_slice->acts_arr[$curr_user->id]) > 1)
		{
?>

  <td class="listViewCalConflictAppt">&nbsp;</td>
<?php
		} else
		{
?>
  <td class="listViewCalCurrentAppt">&nbsp;</td>
<?php
		}
	}
	else if ( isset($cur_slice->acts_arr[$curr_user->id]))
	{
  ?>
  <td class="listViewCalOtherAppt">&nbsp;</td>
<?php
	}
	else
	{
  ?>
  <td class="<?php echo $row_class; ?>" bgcolor="<?php echo $bg_color; ?>">&nbsp;</td>
<?php
	}
     
  }
?>

</tr>
<tr><td colspan="20" class="listViewHRS1"></td></tr>
<?php 
} 
?>
</table>

<table width="100%" cellspacing="2" cellpadding="0" border="0">
<tr height="15">
	<td width="100%"></td>
    <td class="listViewCalCurrentApptLgnd"><img src="include/images/blank.gif" alt="<?php echo $mod_strings['LBL_SCHEDULED']; ?>" width="15" height="15">&nbsp;</td>
    <td>&nbsp;<?php echo $mod_strings['LBL_SCHEDULED']; ?>&nbsp;</td>
    <td class="listViewCalOtherApptLgnd"><img src="include/images/blank.gif" alt="<?php echo $mod_strings['LBL_BUSY']; ?>" width="15" height="15">&nbsp;</td>
    <td>&nbsp;<?php echo $mod_strings['LBL_BUSY']; ?>&nbsp;</td>
    <td class="listViewCalConflictApptLgnd"><img src="include/images/blank.gif" alt="<?php echo $mod_strings['LBL_CONFLICT']; ?>" width="15" height="15">&nbsp;</td>
    <td>&nbsp;<?php echo $mod_strings['LBL_CONFLICT']; ?></td>
</tr>
</table>
</p>
<?php

}

?>
