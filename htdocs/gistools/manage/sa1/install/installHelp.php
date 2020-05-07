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
// $Id: installHelp.php,v 1.5 2005/01/14 18:41:01 julian Exp $

class HelpItem
{
   var $associated_field = '';
   var $title = '';
   var $text = '';
}

function &help_menu_html()
{
   $str = <<< HEREDOC_END
<div>SugarCRM Install Help</div>
<ul>
<li><a href="$_SERVER[PHP_SELF]?step=1">Step 1: Prerequisite checks</a></li>
<li><a href="$_SERVER[PHP_SELF]?step=2">Step 2: Database configuration</a></li>
<li><a href="$_SERVER[PHP_SELF]?step=3">Step 3: Site configuration</a></li>
<li><a href="$_SERVER[PHP_SELF]?step=4">Step 4: Saving config file and setting up the database</a></li>
<li><a href="$_SERVER[PHP_SELF]?step=5">Step 5: Registration</a></li>
</ul>
HEREDOC_END;
   return $str;
}

function &format_help_items(&$help_items)
{
   $str = '<table>';

   foreach($help_items as $help_item)
   {
      $str .= <<< HEREDOC_END
<tr><td><b>$help_item->title</b></td></tr>
<tr><td>$help_item->text</td></tr>
HEREDOC_END;
   }

   $str .= '</table>';

   return $str;
}

function &help_step_1_html()
{
   $help_items = array();

   $help_item = new HelpItem();
   $help_item->title = 'PHP Version';
   $help_item->text = <<< HEREDOC_END
The version of PHP installed must be 4.2.x or 4.3.x.
HEREDOC_END;

   $help_items[] = $help_item;

   $help_item = new HelpItem();
   $help_item->title = 'MySQL Database';
   $help_item->text = <<< HEREDOC_END
Checking that the MySQL API is accessible.
HEREDOC_END;

   $help_items[] = $help_item;

   $help_item = new HelpItem();
   $help_item->title = 'SugarCRM Configuration File';
   $help_item->text = <<< HEREDOC_END
The configuration file (config.php) must be writable.
HEREDOC_END;

   $help_items[] = $help_item;

   $help_item = new HelpItem();
   $help_item->title = 'Cache Sub-Directories';
   $help_item->text = <<< HEREDOC_END
All the sub-directories beneath the cache directory (cache) must be
writable.
HEREDOC_END;

   $help_items[] = $help_item;

   $help_item = new HelpItem();
   $help_item->title = 'Session Save Path';
   $help_item->text = <<< HEREDOC_END
The session save path specified in the PHP initialization file (php.ini)
as session_save_path must exist and be writable.
HEREDOC_END;

   $help_items[] = $help_item;

   $str = &format_help_items($help_items);
   return $str;
}

function &help_step_2_html()
{
   $help_items = array();

   $help_item = new HelpItem();
   $help_item->title = 'Host Name';
   $help_item->text = <<< HEREDOC_END
TODO
HEREDOC_END;

   $help_items[] = $help_item;

   $help_item = new HelpItem();
   $help_item->title = 'Database Name';
   $help_item->text = <<< HEREDOC_END
TODO
HEREDOC_END;

   $help_items[] = $help_item;

   $help_item = new HelpItem();
   $help_item->title = 'Create Database';
   $help_item->text = <<< HEREDOC_END
TODO
HEREDOC_END;

   $help_items[] = $help_item;

   $help_item = new HelpItem();
   $help_item->title = 'User Name for SugarCRM';
   $help_item->text = <<< HEREDOC_END
TODO
HEREDOC_END;

   $help_items[] = $help_item;

   $help_item = new HelpItem();
   $help_item->title = 'Create User';
   $help_item->text = <<< HEREDOC_END
TODO
HEREDOC_END;

   $help_items[] = $help_item;

   $help_item = new HelpItem();
   $help_item->title = 'Password for SugarCRM';
   $help_item->text = <<< HEREDOC_END
TODO
HEREDOC_END;

   $help_items[] = $help_item;

   $help_item = new HelpItem();
   $help_item->title = 'Re-Type Password for SugarCRM';
   $help_item->text = <<< HEREDOC_END
TODO
HEREDOC_END;

   $help_items[] = $help_item;

   $help_item = new HelpItem();
   $help_item->title = 'Drop and re-create existing SugarCRM tables?';
   $help_item->text = <<< HEREDOC_END
TODO
HEREDOC_END;

   $help_items[] = $help_item;

   $help_item = new HelpItem();
   $help_item->title = 'Populate database with demo data?';
   $help_item->text = <<< HEREDOC_END
TODO
HEREDOC_END;

   $help_items[] = $help_item;

   $help_item = new HelpItem();
   $help_item->title = 'Database Admin User Name';
   $help_item->text = <<< HEREDOC_END
TODO
HEREDOC_END;

   $help_items[] = $help_item;

   $help_item = new HelpItem();
   $help_item->title = 'Database Admin Password';
   $help_item->text = <<< HEREDOC_END
TODO
HEREDOC_END;

   $help_items[] = $help_item;

   $str = &format_help_items($help_items);
   return $str;
}

function &help_step_3_html()
{
   $help_items = array();

   $help_item = new HelpItem();
   $help_item->title = 'URL';
   $help_item->text = <<< HEREDOC_END
TODO
HEREDOC_END;

   $help_items[] = $help_item;

   $help_item = new HelpItem();
   $help_item->title = 'SugarCRM Admin Password';
   $help_item->text = <<< HEREDOC_END
TODO
HEREDOC_END;

   $help_items[] = $help_item;

   $help_item = new HelpItem();
   $help_item->title = 'Re-type SugarCRM Admin Password';
   $help_item->text = <<< HEREDOC_END
TODO
HEREDOC_END;

   $help_items[] = $help_item;

   $help_item = new HelpItem();
   $help_item->title = 'Allow SugarCRM to collect anonymous usage information?';
   $help_item->text = <<< HEREDOC_END
TODO
HEREDOC_END;

   $help_items[] = $help_item;

   $help_item = new HelpItem();
   $help_item->title = 'Use a Custom Session Directory for SugarCRM';
   $help_item->text = <<< HEREDOC_END
TODO
HEREDOC_END;

   $help_items[] = $help_item;

   $help_item = new HelpItem();
   $help_item->title = 'Path to Session Directory';
   $help_item->text = <<< HEREDOC_END
TODO
HEREDOC_END;

   $help_items[] = $help_item;

   $help_item = new HelpItem();
   $help_item->title = 'Provide Your Own Application ID';
   $help_item->text = <<< HEREDOC_END
TODO
HEREDOC_END;

   $help_items[] = $help_item;

   $help_item = new HelpItem();
   $help_item->title = 'Application ID';
   $help_item->text = <<< HEREDOC_END
TODO
HEREDOC_END;

   $help_items[] = $help_item;

   $str = &format_help_items($help_items);
   return $str;
}

function &help_step_4_html()
{
   $help_items = array();

   $help_item = new HelpItem();
   $help_item->title = 'Perform Install';
   $help_item->text = <<< HEREDOC_END
TODO
HEREDOC_END;

   $help_items[] = $help_item;

   $str = &format_help_items($help_items);
   return $str;
}

function &help_step_5_html()
{
   $help_items = array();

   $help_item = new HelpItem();
   $help_item->title = 'Registration';
   $help_item->text = <<< HEREDOC_END
TODO
HEREDOC_END;

   $help_items[] = $help_item;

   $str = &format_help_items($help_items);
   return $str;
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <meta http-equiv="Content-Style-Type" content="text/css">
   <title>SugarCRM Install Help</title>
   <link rel="stylesheet" href="install/install.css" type="text/css" />
</head>
<body>
<?php
if(isset($_GET['step']))
{
   switch($_GET['step'])
   {
      case 1:
         echo help_step_1_html();
      break;
      case 2:
         echo help_step_2_html();
      break;
      case 3:
         echo help_step_3_html();
      break;
      case 4:
         echo help_step_4_html();
      break;
      case 5:
         echo help_step_5_html();
      break;
      default:
         echo help_menu_html();
      break;
   }
}
else
{
   echo help_menu_html();
}
?>

<form>
   <input type="button" value="Close" onclick="javascript:window.close();" />
</form>
</body>
</html>
