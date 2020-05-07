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
 * $Id: layout_utils.php,v 1.51 2005/04/14 23:28:02 lam Exp $
 * Description:  Contains a variety of utility functions used to display UI
 * components such as form headers and footers.  Intended to be modified on a per
 * theme basis.
 ********************************************************************************/

require_once('include/logging.php');
global $app_strings,$current_user;

// list view bg colors
$even_bg = "#EEEECC";
$odd_bg = "#FFFFEE";
$hilite_bg = "#D7E8F4";
// set $click_bg to a color to have a listview row to highlight when clicked $click_bg = "";

//graph colors
$barChartColors = array(
"docBorder"=>"0xCBCBAE",
"docBg1"=>"0xEEEECC",
"docBg2"=>"0xEEEECC",
"xText"=>"0x444444",
"yText"=>"0x444444",
"title"=>"0x356799",
"misc"=>"0x666666",
"altBorder"=>"0xCBCBAE",
"altBg"=>"0xFFFFEE",
"altText"=>"0x666666",
"graphBorder"=>"0x59594C",
"graphBg1"=>"0xFFFFEE",
"graphBg2"=>"0xFFFFEE",
"graphLines"=>"0xCBCBAE",
"graphText"=>"0x003860",
"graphTextShadow"=>"0xFFFFFF",
"barBorder"=>"0x666666",
"barBorderHilite"=>"0xFFFFFF",
"legendBorder"=>"0x59594C",
"legendBg1"=>"0xFFFFEE",
"legendBg2"=>"0xFFFFEE",
"legendText"=>"0x444444",
"legendColorKeyBorder"=>"0x777777",
"scrollBar"=>"0x999999",
"scrollBarBorder"=>"0x777777",
"scrollBarTrack"=>"0xeeeeee",
"scrollBarTrackBorder"=>"0x777777"
);

$pieChartColors = array(
"docBorder"=>"0xCBCBAE",
"docBg1"=>"0xEEEECC",
"docBg2"=>"0xEEEECC",
"title"=>"0x356799",
"subtitle"=>"0x666666",
"misc"=>"0x666666",
"altBorder"=>"0xCBCBAE",
"altBg"=>"0xFFFFEE",
"altText"=>"0x666666",
"graphText"=>"0x444444",
"graphTextShadow"=>"0xFFFFFF",
"pieBorder"=>"0x666666",
"pieBorderHilite"=>"0xFFFFFF",
"legendBorder"=>"0x59594C",
"legendBg1"=>"0xFFFFEE",
"legendBg2"=>"0xFFFFEE",
"legendText"=>"0x444444",
"legendColorKeyBorder"=>"0x777777",
"scrollBar"=>"0x999999",
"scrollBarBorder"=>"0x777777",
"scrollBarTrack"=>"0xeeeeee",
"scrollBarTrackBorder"=>"0x777777"
);

$log = LoggerManager::getLogger('layout_utils');

if (!$current_user->getPreference('gridline') || $current_user->getPreference('gridline') == 'on') {
$gridline = 1;
} else {
$gridline = 0;
}


/**
 * Create HTML to display formatted form title of a form in the left pane
 * param $left_title - the string to display as the title in the header
 */
function get_left_form_header ($left_title)
{
global $image_path;

$the_header = '<table cellpadding="0" cellspacing="0" border="0" width="100%" class="leftColumnModuleHead">';
$the_header .= '<tr>';
$the_header .= '<th width="5"><img src="'.$image_path.'moduleTab_left.gif" alt="" width="5" height="20"  border="0" alt="'.$left_title.'"></th>';
$the_header .= '<th style="background-image : url('.$image_path.'moduleTab_middle.gif);" width="100%">'.$left_title.'</th>';
$the_header .= '<th width="5"><img src="'.$image_path.'moduleTab_right.gif" alt="" width="5" height="20" border="0" alt="'.$left_title.'"></th></tr>';
$the_header .= '</table>';
$the_header .= '<table width="100%" cellpadding="3" cellspacing="0" border="0"><tr><td align="left" class="leftColumnModuleS3">';
return $the_header;
}

/**
 * Create HTML to display formatted form footer of form in the left pane.
 */
function get_left_form_footer() {
return ("</td></tr></table>\n");
}

/**
 * Create HTML to display formatted form title.
 * param $form_title - the string to display as the title in the header
 * param $other_text - the string to next to the title.  Typically used for form buttons.
 * param $show_help - the boolean which determines if the print and help links are shown.
 */
function get_form_header ($form_title, $other_text, $show_help)
{
global $image_path;
global $app_strings;

$the_form = <<<EOQ
</p>
<p>
<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr>
	  <td nowrap><h3>$form_title</h3></td>
EOQ;

$keywords = array("/class=\"button\"/","/class='button'/","/class=button/","/<\/form>/");
$match="";

	foreach ($keywords as $left) {
		 if (preg_match($left,$other_text)) {$match=true;}
	}
if ($other_text && $match) {
$the_form .= "<td colspan='10' width='100%'><IMG height='1' width='1' src='include/images/blank.gif' alt=''></td>\n";
	$the_form .= "</tr><tr>\n";
	$the_form .= "<td align='left' valign='middle' nowrap style='padding-bottom: 2px;'>$other_text</td>\n";
	$the_form .= "<td width='100%'><IMG height='1' width='1' src='include/images/blank.gif' alt=''></td>\n";

	if ($show_help==true) {
		$the_form .= "<td align='right' nowrap>";
		if ($_REQUEST['action'] != "EditView") {
	     	$the_form .= "<A
href='index.php?".$GLOBALS['request_string']."' class='utilsLink'><img
src='".$image_path."print.gif' width='9' height='9' alt='Print' border='0'
align='absmiddle'></a>&nbsp;<A
href='index.php?".$GLOBALS['request_string']."'
class='utilsLink'>".$app_strings['LNK_PRINT']."</A>\n";
	    }
	    $the_form .= "&nbsp;<A href='http://www.sugarcrm.com/docs'
target='_blank' class='utilsLink'><img src='".$image_path."help.gif'
width='6' height='9' alt='Help' border='0' align='absmiddle'></a>&nbsp;<A
href='http://www.sugarcrm.com/docs' target='_blank'
class='utilsLink'>".$app_strings['LNK_HELP']."</A></td>\n";
	}

} else {

if ($other_text) {



		$the_form .= "<td width='20'><IMG height='1' width='20' src='include/images/blank.gif' alt=''></td>\n";
		$the_form .= "<td  valign='middle' nowrap width='100%'>$other_text</td>\n";
	}
	else {
		$the_form .= "<td width='100%'><IMG height='1' width='1' src='include/images/blank.gif' alt=''></td>\n";
	}
	
	if ($show_help==true) {
		$the_form .= "<td align='right' nowrap>";
		if ($_REQUEST['action'] != "EditView") {
	     	$the_form .= "<A
href='index.php?".$GLOBALS['request_string']."' class='utilsLink'><img
src='".$image_path."print.gif' width='9' height='9' alt='Print' border='0'
align='absmiddle'></a>&nbsp;<A
href='index.php?".$GLOBALS['request_string']."'
class='utilsLink'>".$app_strings['LNK_PRINT']."</A>\n";
	    }
	    $the_form .= "&nbsp;<A href='http://www.sugarcrm.com/docs'
target='_blank' class='utilsLink'><img src='".$image_path."help.gif'
width='6' height='9' alt='Help' border='0' align='absmiddle'></a>&nbsp;<A
href='http://www.sugarcrm.com/docs' target='_blank'
class='utilsLink'>".$app_strings['LNK_HELP']."</A></td>\n";
	}

}



$the_form .= <<<EOQ
	  </tr>
</table>

EOQ;

return $the_form;
}

/**
 * Create HTML to display formatted form footer
 */
function get_form_footer() {
}

/**
 * Create HTML to display formatted module title.
 * param $module - the string to next to the title.  Typically used for form buttons.
 * param $module_title - the string to display as the module title
 * param $show_help - the boolean which determines if the print and help links are shown.
 */
function get_module_title ($module, $module_title, $show_help) {
global $image_path;
global $app_strings;

$the_title = "<table width='100%' cellpadding='0' cellspacing='0' border='0'><tr><td valign='top'>\n";
$module = preg_replace("/ /","",$module);
if (is_file($image_path.$module.".gif")) {
	$the_title .= "<IMG src='".$image_path.$module.".gif' width='16' height='16' border='0' alt='".$module_title."' style='margin-top: 3px;'>&nbsp;</td><td width='100%'>";
}
$the_title .= "<h2>".$module_title."</h2></td>\n";

if ($show_help) {
		
		$the_title .= "<td valign='top' align='right' nowrap style='padding-top:3px; padding-left: 5px;'>";
		if ($_REQUEST['action'] != "EditView") {
	     	$the_title .= "<A href='index.php?".$GLOBALS['request_string']."' class='utilsLink'><img src='".$image_path."print.gif' width='9' height='9' alt='".$app_strings['LNK_PRINT']."' border='0' align='absmiddle'></a>&nbsp;<A href='index.php?".$GLOBALS['request_string']."' class='utilsLink'>".$app_strings['LNK_PRINT']."</A>\n";
	    }
	    $the_title .= "&nbsp;<A href='http://www.sugarcrm.com/home/component/option,com_docman' target='_blank' class='utilsLink'><img src='".$image_path."help.gif' width='6' height='9' alt='".$app_strings['LNK_HELP']."' border='0' align='absmiddle'></a>&nbsp;<A href='http://www.sugarcrm.com/home/component/option,com_docman' target='_blank' class='utilsLink'>".$app_strings['LNK_HELP']."</A></td>\n";
	}


$the_title .= "</tr></table>\n";

return $the_title;

}

/**
 * Create a header for a popup.
 * param $theme - The name of the current theme
 */
function insert_popup_header($theme)
{
global $app_strings, $sugar_config;
$charset = $sugar_config['default_charset'];

if(isset($app_strings['LBL_CHARSET']))
{
	$charset = $app_strings['LBL_CHARSET'];
}

$out  = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">';
$out .=	'<HTML><HEAD>';
$out .=	'<meta http-equiv="Content-Type" content="text/html; charset='.$charset.'">';
$out .=	'<title>'.$app_strings['LBL_BROWSER_TITLE'].'</title>';
$out .=	'<style type="text/css">@import url("themes/'.$theme.'/style.css"); </style>';
$out .=	'</HEAD><BODY style="margin: 10px">';

echo $out;
}

/**
 * Create a footer for a popup.
 */
function insert_popup_footer()
{
echo <<< EOQ
	</BODY>
	</HTML>
EOQ;
}

?>
