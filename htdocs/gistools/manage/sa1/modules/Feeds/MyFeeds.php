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
 * $Id: MyFeeds.php,v 1.15 2005/04/14 18:03:43 lam Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('modules/Feeds/Feed.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');

require_once('include/ListView/ListView.php');


global $app_strings;
global $app_list_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'Feeds');

global $urlPrefix;

$log = LoggerManager::getLogger('feed_list');

global $currentModule;

global $theme;

if (!isset($where)) $where = "";

$seedFeed = new Feed();

global $current_user;
$where = " users_feeds.user_id='{$current_user->id}' ";
$orderby = 'rank asc';
$query = $seedFeed->create_list_query($orderby,$where);
$result = $seedFeed->db->query($query, -1);

while($row =  $seedFeed->db->fetchByAssoc($result, -1))
{
echo "<p>";
template_display_my_feed($row);
echo "</p>";
}



// My feeds headlines template
function template_display_my_feed(&$feed_row)
{
	global $image_path, $even_bg, $sugar_config ,$mod_strings;


        if (!defined('DOMIT_RSS_INCLUDE_PATH')) {
                define('DOMIT_RSS_INCLUDE_PATH', "include/domit_rss/");
        }

        require_once(DOMIT_RSS_INCLUDE_PATH . 'xml_domit_rss.php');

        $rssdoc =& new xml_domit_rss_document($feed_row['url'],'cache/feeds/',$sugar_config['rss_cache_time']);
        $content = '';
        $currChannel =& $rssdoc->getChannel(0);
        
//        $channel_desc = $currChannel->getDescription();
	if (! method_exists($currChannel,'getTitle' ))
	{
		print $mod_strings['LBL_FEED_NOT_AVAILABLE'];

		// This section of the code fetches the filename of the cache required to delete and refresh
		require_once(DOMIT_RSS_INCLUDE_PATH . 'php_text_cache.php');
		$cache = new php_text_cache('cache/feeds/',$sugar_config['rss_cache_time']);
		
		$deletecache = $cache->getCacheFileName($feed_row['url']);
		if (file_exists($deletecache)){
			unlink($deletecache);
		}
		
		print "<br><a href='index.php?module=Feeds&action=index&return_module=Feeds&delete_cache='>".$mod_strings['LBL_REFRESH_CACHE']."</a>";
		
		return;
	}
	
	if ( method_exists($currChannel,'getLastBuildDate' ))
	{
        $last_build_date = $currChannel->getLastBuildDate();
	}

        $img_html = '';

?>
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="right"><nobr>
<a href="index.php?return_action=<?php echo $_REQUEST['action'];?>&return_module=Feeds&action=MoveUp&module=Feeds&record=<?Php echo $feed_row['id']; ?>"><?php echo get_image($image_path."uparrow",'border="0" align="absmiddle" alt="Move Up"');?></a>
<a href="index.php?return_action=<?php echo $_REQUEST['action'];?>&return_module=Feeds&action=MoveDown&module=Feeds&record=<?Php echo $feed_row['id']; ?>"><?php echo get_image($image_path."downarrow",'border="0" align="absmiddle" alt="Move Down"');?></a>
<a href="index.php?return_action=<?php echo $_REQUEST['action'];?>&return_module=Feeds&action=DeleteFavorite&module=Feeds&record=<?Php echo $feed_row['id']; ?>" class="listViewTdToolsS1"><?php echo get_image($image_path."delete",'border="0" align="absmiddle" alt="delete from favorites"');?></a>
</nobr></td>
</tr>
</table>

<table cellpadding="0" cellspacing="0" width="100%" border="0" class="listView">
<tr height="20"><td scope="col" width="100%" class="listViewThS1"><a href="index.php?action=DetailView&module=Feeds&record=<?php echo $feed_row['id']; ?>" class="listViewThLinkS1"><?php echo $currChannel->getTitle(); ?></a>  - <a   target="_new" href="<?php echo $currChannel->getLink(); ?>" class="listViewThLinkS1">(<?php echo $mod_strings['LBL_VISIT_WEBSITE'];?>)</a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php if ( ! empty($last_build_date)) { ?>
<?php echo $mod_strings['LBL_LAST_UPDATED'];?>: <?php echo $last_build_date; ?>
<?php } ?>
</td>
      
</tr>
<tr>
<td scope="col" bgcolor="<?php echo $even_bg; ?>" class="evenListRowS1" colspan="10">

<?php
//get total number of items
//$totalItems = $currChannel->getItemCount();
$totalItems = 5;
	$topitem = $currChannel->getItem(0);
	if ( ! isset( $topitem))
	{
?>

No content


<?php
	}
	else
	{
                        //loop through each item
                        for ($j = 0; $j < $totalItems; $j++) 
			{
                                //get reference to current item
                                $currItem =& $currChannel->getItem($j);
				if ( ! isset($currItem))
				{
					continue;
				}
                                $item_link = $currItem->getLink();
                                $item_title = $currItem->getTitle();
                                $item_date = $currItem->getPubDate();
                                //$item_desc = $currItem->getDescription();
?>
<li> <a target="_new" href="<?php echo $currItem->getLink(); ?>" class="listViewTdLinkS1"><?php echo $currItem->getTitle(); ?></a>&nbsp;&nbsp;<span class="rssItemDate"><?php echo $item_date; ?></span>



<?php			} 
	}

?></td></tr>
</table>
<?php
}


?>
