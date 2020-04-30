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
 * $Id: layout_utils.php,v 1.8 2005/04/14 23:26:33 lam Exp $
 * Description:  Contains a variety of utility functions used to display UI
 * components such as form headers and footers.  Intended to be modified on a per
 * theme basis.
 ********************************************************************************/

require_once('include/logging.php');
global $app_strings,$current_user;

// list view colors
$even_bg = "#ffffff";
$odd_bg = "#E5FFDB";
$hilite_bg = "#DEEFFF";
//$click_bg = "#FCB670";


//graph colors
$barChartColors = array(
"docBorder"=>"0x2EC200",
"docBg1"=>"0xE5FFDB",
"docBg2"=>"0xE5FFDB",
"xText"=>"0x444444",
"yText"=>"0x444444",
"title"=>"0x207800",
"misc"=>"0x666666",
"altBorder"=>"0x36CA00",
"altBg"=>"0xFFFFFF",
"altText"=>"0x207800",
"graphBorder"=>"0x207800",
"graphBg1"=>"0xF3FFEF",
"graphBg2"=>"0xF3FFEF",
"graphLines"=>"0xB1D2A5",
"graphText"=>"0x003860",
"graphTextShadow"=>"0xFFFFFF",
"barBorder"=>"0x666666",
"barBorderHilite"=>"0xFFFFFF",
"legendBorder"=>"0x207800",
"legendBg1"=>"0xF3FFEF",
"legendBg2"=>"0xF3FFEF",
"legendText"=>"0x444444",
"legendColorKeyBorder"=>"0x777777",
"scrollBar"=>"0x999999",
"scrollBarBorder"=>"0x777777",
"scrollBarTrack"=>"0xeeeeee",
"scrollBarTrackBorder"=>"0x777777"
);

$pieChartColors = array(
"docBorder"=>"0x2EC200",
"docBg1"=>"0xE5FFDB",
"docBg2"=>"0xE5FFDB",
"title"=>"0x207800",
"subtitle"=>"0x666666",
"misc"=>"0x666666",
"altBorder"=>"0x36CA00",
"altBg"=>"0xFFFFFF",
"altText"=>"0x207800",
"graphText"=>"0x444444",
"graphTextShadow"=>"0xFFFFFF",
"pieBorder"=>"0x666666",
"pieBorderHilite"=>"0xFFFFFF",
"legendBorder"=>"0x207800",
"legendBg1"=>"0xF3FFEF",
"legendBg2"=>"0xF3FFEF",
"legendText"=>"0x444444",
"legendColorKeyBorder"=>"0x777777",
"scrollBar"=>"0x999999",
"scrollBarBorder"=>"0x777777",
"scrollBarTrack"=>"0xeeeeee",
"scrollBarTrackBorder"=>"0x777777"
);

$log = LoggerManager::getLogger('layout_utils');
if ($current_user->getPreference('gridline') == 'on') {
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


$the_header = '<table cellspacing="0" cellpadding="0" border="0" width="100%" style="background-image: url('.$image_path.'doc_bg.gif);">';
$the_header .= '<tr>';
$the_header .= '<td width="10"><img src="'.$image_path.'moduleTab_left.gif" width="10" height="10" border="0" alt=""></td>';
$the_header .= '<td width="100%" style="background-image: url('.$image_path.'moduleTab_top.gif)"><img src="include/images/blank.gif" width="100%" height="10" alt=""></td>';
$the_header .= '<td><img src="'.$image_path.'moduleTab_right.gif" width="10" height="10" border="0"  alt=""></td>';
$the_header .= '</tr>';
$the_header .= '<tr>';
$the_header .= '<td  width="10" style="background-image: url('.$image_path.'moduleTab_leftCol.gif)"><img src="include/images/blank.gif" width="10" height="1" alt=""></td>';
$the_header .= '<td class="leftColumnModuleS3"><span class="leftColumnModuleHead">'.$left_title.'</span><br>';
    
    


return $the_header;
}

/**
 * Create HTML to display formatted form footer of form in the left pane.
 */
function get_left_form_footer() {
global $image_path;
$html = '</td>';
$html .= '<td style="background-image: url('.$image_path.'moduleTab_rightCol.gif)"><img src="include/images/blank.gif" width="10" height="1" alt=""></td>';
$html .= '</tr>';
$html .= '<tr>';
$html .= ' <td><img src="'.$image_path.'moduleTab_lowerLeft.gif" width="10" height="10" border="0" alt=""></td>';
$html .= '<td style="background-image: url('.$image_path.'moduleTab_lower.gif)"><img src="include/images/blank.gif" width="10" height="10" alt=""></td>';
$html .= '<td><img src="'.$image_path.'moduleTab_lowerRight.gif" width="10" height="10" border="0" alt=""></td>';
$html .= '</tr>';
$html .= '</table>';
return ($html);
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

$the_form = '';
if ( isset($_REQUEST['module']) && $_REQUEST['module'] != 'Calendar')
{
$the_form = <<<EOQ
</p>
<p>
EOQ;
}

$the_form = '<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr>';
$the_form .= '<td nowrap><h3><img src="'.$image_path.'h3Arrow.png" width="14" height="14" border="0" alt="'.$form_title.'">&nbsp;'.$form_title.'</h3></td>';


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
src='".$image_path."print.gif' width='13' height='13' alt='Print' border='0'
align='absmiddle'></a>&nbsp;<A
href='index.php?".$GLOBALS['request_string']."'
class='utilsLink'>".$app_strings['LNK_PRINT']."</A>\n";
	    }
	    $the_form .= "&nbsp;<A href='http://www.sugarcrm.com/docs'
target='_blank' class='utilsLink'><img src='".$image_path."help.gif'
width='13' height='13' alt='Help' border='0' align='absmiddle'></a>&nbsp;<A
href='http://www.sugarcrm.com/docs' target='_blank'
class='utilsLink'>".$app_strings['LNK_HELP']."</A></td>\n";
	}

} else {

if ($other_text) {



		$the_form .= "<td width='20'><IMG height='1' width='20' src='include/images/blank.gif' alt=''></td>\n";
		$the_form .= "<td valign='middle' nowrap width='100%'>$other_text</td>\n";
	}
	else {
		$the_form .= "<td width='100%'><IMG height='1' width='1' src='include/images/blank.gif' alt=''></td>\n";
	}
	
	if ($show_help==true) {
		$the_form .= "<td align='right' nowrap>";
		if ($_REQUEST['action'] != "EditView") {
	     	$the_form .= "<A
href='index.php?".$GLOBALS['request_string']."' class='utilsLink'><img
src='".$image_path."print.gif' width='13' height='13' alt='Print' border='0'
align='absmiddle'></a>&nbsp;<A
href='index.php?".$GLOBALS['request_string']."'
class='utilsLink'>".$app_strings['LNK_PRINT']."</A>\n";
	    }
	    $the_form .= "&nbsp;<A href='http://www.sugarcrm.com/docs'
target='_blank' class='utilsLink'><img src='".$image_path."help.gif'
width='13' height='13' alt='Help' border='0' align='absmiddle'></a>&nbsp;<A
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

$the_title = "<table width='100%' cellpadding='0' cellspacing='0' border='0' class='moduleTitle'><tr><td valign='top'>\n";
$module = preg_replace("/ /","",$module);
if (is_file($image_path.$module.".gif")) {
	$the_title .= "<IMG src='".$image_path.$module.".gif' border='0' style='margin-top: 3px;' alt='".$module_title."' height='16' width='16'>&nbsp;</td><td width='100%'>";
}
$the_title .= "<h2>".$module_title."</h2></td>\n";

if ($show_help) {
		
		$the_title .= "<td valign='top' align='right' nowrap style='padding-top:3px; padding-left: 5px;'>";
		if ($_REQUEST['action'] != "EditView") {
	     	$the_title .= "<A href='index.php?".$GLOBALS['request_string']."' class='utilsLink'><img src='".$image_path."print.gif' width='13' height='13' alt='".$app_strings['LNK_PRINT']."' border='0' align='absmiddle'></a>&nbsp;<A href='index.php?".$GLOBALS['request_string']."' class='utilsLink'>".$app_strings['LNK_PRINT']."</A>\n";
	    }
	    $the_title .= "&nbsp;<A href='http://www.sugarcrm.com/home/component/option,com_docman' target='_blank' class='utilsLink'><img src='".$image_path."help.gif' width='13' height='13' alt='".$app_strings['LNK_HELP']."' border='0' align='absmiddle'></a>&nbsp;<A href='http://www.sugarcrm.com/home/component/option,com_docman' target='_blank' class='utilsLink'>".$app_strings['LNK_HELP']."</A></td>\n";
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
