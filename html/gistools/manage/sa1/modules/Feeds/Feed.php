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
 * $Id: Feed.php,v 1.30 2005/04/29 19:28:29 robert Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');
require_once('include/utils.php');

// Contact is used to store customer information.
class Feed extends SugarBean {
	var $log;
	var $db;
        var $field_name_map;

	// Stored fields
	var $id;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $assigned_user_id;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;



	var $title;
	var $description;
	var $content;
	var $favorite = false;
	var $user_id;

	var $my_favorites = false;

	var $table_name = "feeds";
	var $rel_users_feeds = "users_feeds";

	var $object_name = "Feed";

	var $new_schema = true;

	var $column_fields = Array("id"
		,"date_entered"
		,"date_modified"
		,"modified_user_id"
		,"assigned_user_id"
		, "created_by"



		,"title"
		,"description"
		,"url"
		);
	var $additional_column_fields = array();

	var $module_dir = 'Feeds';
	var $field_defs = array();
	var $field_defs_map = array();

	// This is the list of fields that are in the lists.
	var $list_fields = Array('id', 'title','url','favorite');

	// This is the list of fields that are required
	var $required_fields =  array("url"=>1);

	function Feed() {
		$this->log = LoggerManager::getLogger('feed');
		parent::SugarBean();
		$this->setupCustomFields('Feeds');
		foreach ($this->field_defs as $field)
		{
			$this->field_name_map[$field['name']] = $field;
		}




	}

	function create_tables () 
  {
	  parent::create_tables();
		$this->populate_feeds();
  }

	function move ($dir='up',$feed_id,$user_id)
	{
		$user_id = $this->db->quote($user_id);


		$query = "SELECT rank from {$this->rel_users_feeds} where user_id='$user_id' AND feed_id='$feed_id' AND deleted=0 order by rank";

//print ">SD:".$query;
		$result = $this->db->query($query, -1);
		$feeds = array();

		$feed_at = -1;
                $row =  $this->db->fetchByAssoc($result, -1);
		if ( empty($row))
		{
			sugar_die("feed_id not found:".$feed_id);
		}

		if ($dir == 'up')
		{
			if ( $row['rank'] <= 1)
			{
				return;
			}

			$oldotherrank = $row['rank'] - 1;
			$newotherrank = $row['rank'];
			$oldrank = $row['rank'] ;
			$newrank = $oldrank - 1;
		}
		else
		{
			$query = "SELECT count(*) as count from {$this->rel_users_feeds} where user_id='$user_id' AND deleted=0 order by rank";

			$result = $this->db->query($query, -1);

                	$countrow =  $this->db->fetchByAssoc($result, -1);

			$count = $countrow['count'];

			if ( $row['rank'] >=$count)
			{
				return;
			}

			$oldotherrank = $row['rank'] + 1;
			$newotherrank = $row['rank'];
			$oldrank = $row['rank'] ;
			$newrank = $oldrank + 1;
		}

		$query = "update {$this->rel_users_feeds} set rank=$newotherrank where user_id='$user_id' AND rank=$oldotherrank AND deleted=0";
		$this->db->query($query);
print $query."<BR>";

		$query = "update {$this->rel_users_feeds} set rank=$newrank where user_id='$user_id' AND feed_id='$feed_id' AND deleted=0";

		$this->db->query($query);
//print $query."<BR>";
//exit;

	}


	function addToFavorites ($feed_id,$user_id)
	{
		$user_id = $this->db->quote($user_id);

		$query = "SELECT max(rank) as maxrank from {$this->rel_users_feeds} where user_id='$user_id' AND  deleted=0";
		$result = $this->db->query($query, -1);
                $row =  $this->db->fetchByAssoc($result, -1);
		if ($row['maxrank'] == 0)
                {
		$rank = 1;
		}
		else
		{
		$rank = $row['maxrank'] + 1;
		}

		$query = "SELECT deleted from {$this->rel_users_feeds} where user_id='$user_id' AND feed_id='$feed_id' and deleted=0";
		$result = $this->db->query($query, -1);
                $row =  $this->db->fetchByAssoc($result, -1);


		if (empty($row))
		{
		
			$query = "insert into {$this->rel_users_feeds} (user_id, feed_id, rank, deleted) VALUES( '$user_id','$feed_id',$rank,0 )";
			$this->db->query($query);
		}
		else if ( ! empty($row) && $row['deleted'] == 1)
		{
			$query = "update {$this->rel_users_feeds} set deleted=0,rank=$rank where  user_id='$user_id' AND feed_id='$feed_id'";
			$this->db->query($query);
		}
	}

	function removeFavorites ($feed_id,$user_id)
	{
		$user_id = $this->db->quote($user_id);
		$query = "SELECT deleted,rank from {$this->rel_users_feeds} where user_id='$user_id' AND feed_id='$feed_id'";
		$result = $this->db->query($query, -1);

                $row =  $this->db->fetchByAssoc($result, -1);

		if ( isset($row) && $row['deleted'] == 0)
		{
			$query = "delete from {$this->rel_users_feeds} where  user_id='$user_id' AND feed_id='$feed_id'";
			$this->db->query($query);

			$query = "update {$this->rel_users_feeds} set rank=rank-1 where rank > {$row['rank']} AND  user_id='$user_id' AND deleted=0";
			$this->db->query($query);
		}


	}

	function createRSSHomePage($user_id)
	{
//from feeds.sql:

		$this->addToFavorites('4bbca87f-2017-5488-d8e0-41e7808c2553',$user_id) ;
	}


	

	function populate_feeds()
	{
		$lines = file('modules/Feeds/feeds.sql');
		foreach ($lines as $line)
		{
			$line = chop($line);
			$this->db->query($line);
		}
	}

	function get_summary_text()
	{
		return $this->title;
	}

        function add_list_count_joins(&$query, $where)
        {
		global $current_user;
                $query .= " LEFT JOIN  {$this->rel_users_feeds} ";
                $query  .= " ON ( {$this->rel_users_feeds}.user_id IS NULL                                 OR {$this->rel_users_feeds}.deleted=0 )
                        AND  {$this->rel_users_feeds}.user_id='{$current_user->id}'
                        AND {$this->rel_users_feeds}.feed_id={$this->table_name}.id ";
         }

	function create_list_query(&$order_by, &$where)
	{
		global $current_user;

		$query = "SELECT {$this->table_name}.*, {$this->rel_users_feeds}.user_id AS favorite";
	        $query .= " FROM {$this->table_name} ";
		$query .= " LEFT JOIN  {$this->rel_users_feeds} ";
		$query  .= " ON ( {$this->rel_users_feeds}.user_id IS NULL OR {$this->rel_users_feeds}.deleted=0 ) AND  {$this->rel_users_feeds}.user_id='{$current_user->id}' AND {$this->rel_users_feeds}.feed_id={$this->table_name}.id ";

		$where_auto = '';

		$where_auto .= " {$this->table_name}.deleted=0 ";

		if($where != "")
			$query .= "where ($where) AND ".$where_auto;
		else
			$query .= "where ".$where_auto;

		if(!empty($order_by))
			$query .= " ORDER BY $order_by";
		return $query;
	}

  function create_export_query($order_by, $where)   
	{
    $query = "SELECT         feeds.*";
    $query .= " FROM feeds ";

    $where_auto = " feeds.deleted = 0";

    if($where != "")       
		{
			$query .= " WHERE $where AND " . $where_auto;
		}
    else       
		{
			$query .= " WHERE " . $where_auto;
		}
    if($order_by != "")
		{
      $query .= " ORDER BY $order_by";     
		}

    return $query;
  }


	function save($check_notify=false)
	{

		global $current_user;

 		if (!defined('DOMIT_RSS_INCLUDE_PATH')) {
       	         define('DOMIT_RSS_INCLUDE_PATH', "include/domit_rss/");
       	 	}

		require_once(DOMIT_RSS_INCLUDE_PATH . 'xml_domit_rss.php');

		// check if this already exists
		$query = "select id from {$this->table_name} where url='{$this->url}' AND deleted=0";

                $result = $this->db->query($query, -1);

                $row =  $this->db->fetchByAssoc($result, -1);

                if (! empty($row))
                {
                        $this->addToFavorites ($row['id'],$current_user->id) ;
                        return;
                }



        	$rssdoc =& new xml_domit_rss_document($this->url,'cache/feeds/',3600);
        	if (  $rssdoc == null)
		{
			return;
		}
        	$currChannel =& $rssdoc->getChannel(0);

		if ( isset($currChannel))
		{
			$this->title = $currChannel->getTitle();
			parent::save();
			$this->addToFavorites ($this->id,$current_user->id);
		}
	}



	function fill_in_additional_list_fields()
	{
		//$this->fill_in_additional_detail_fields();
	}

	function fill_in_additional_detail_fields()
	{
		global $current_user;

		$query = "select user_id from {$this->rel_users_feeds} where user_id='{$current_user->id}' AND feed_id='{$this->id}' AND deleted=0";

                $result = $this->db->query($query, -1);

                $row =  $this->db->fetchByAssoc($result, -1);

                if (! empty($row))
                {
		 	$this->favorite = true;
		}

	}

	function get_list_view_data()
	{
		global $image_path;
		global $mod_strings;
		$temp_array = $this->get_list_view_array();

		if ( $this->my_favorites )
		{
			$view = '';
		}
		else
		{
			$view = '&view=all';
		}

		if ( empty($temp_array['FAVORITE']))
		{
    		$temp_array['FAVORITE']= "<a href=\"index.php?return_action=".$_REQUEST['action']."&return_module=Feeds&action=AddFavorite&module=Feeds&record=".$temp_array['ID']."$view\" class=\"listViewTdToolsS1\">".get_image($image_path."plus_inline",'border="0" align="absmiddle" alt="'.$mod_strings['LBL_ADD_FAV_BUTTON_LABEL'].'"')."</a>&nbsp;<a href=\"index.php?return_action=".$_REQUEST['action']."&return_module=Feeds&action=AddFavorite&module=Feeds&record=".$temp_array['ID']."$view\" class=\"listViewTdToolsS1\">".$mod_strings['LBL_ADD_FAV_BUTTON_LABEL']."</a>";
		}
		else
		{

		//	if (! $this->my_favorites)
		//	{
    				$temp_array['ASTERISK'] = "*";
		//	}
    			$temp_array['FAVORITE'] = "<a href=\"index.php?return_action=".$_REQUEST['action']."&return_module=Feeds&action=DeleteFavorite&module=Feeds&record=".$temp_array['ID']."$view\" class=\"listViewTdToolsS1\">".get_image($image_path."minus_inline",'border="0" align="absmiddle" alt="'.$mod_strings['LBL_DELETE_FAV_BUTTON_LABEL'].'"')."</a>&nbsp;<a href=\"index.php?return_action=".$_REQUEST['action']."&return_module=Feeds&action=DeleteFavorite&module=Feeds&record=".$temp_array['ID']."$view\" class=\"listViewTdToolsS1\">".$mod_strings['LBL_DELETE_FAV_BUTTON_LABEL']."</a>";
		}
    	return $temp_array;

	}
	function parse_additional_headers(&$list_form, $xTemplateSection) {

	}

	function list_view_parse_additional_sections(&$list_form, $xTemplateSection) {
		return $list_form;
	}
	/**
		builds a generic search based on the query string using or
		do not include any $this-> because this is called on without having the class instantiated
	*/
	function build_generic_where_clause ($the_query_string)
	{
		$where_clauses = Array();
		$the_query_string = addslashes($the_query_string);
			//array_push($where_clauses, "contacts.phone_fax like '%$the_query_string%'");

		$the_where = "";
		foreach($where_clauses as $clause)
		{
			if($the_where != "") $the_where .= " or ";
			$the_where .= $clause;
		}


		return $the_where;
	}

	function display_feed()
	{
		global $mod_strings;
		$rssurl = $this->url;

		if (!defined('DOMIT_RSS_INCLUDE_PATH')) {
                define('DOMIT_RSS_INCLUDE_PATH', "include/domit_rss/");
		}

		require_once(DOMIT_RSS_INCLUDE_PATH . 'xml_domit_rss.php');

		$rssdoc =& new xml_domit_rss_document($rssurl,'cache/feeds/',3600);
		$content = '';
		$currChannel =& $rssdoc->getChannel(0);

		if (! method_exists($currChannel,'getTitle' ))
		{
			print $mod_strings['LBL_FEED_NOT_AVAILABLE']."<BR><BR>";
			return;
		}

	$channel_link = $currChannel->getLink();
	$channel_title = $currChannel->getTitle();

	$channel_desc = $currChannel->getDescription();

	$img_html = '';

	if ( $currChannel->hasElement('image'))
	{
		$img_element =& $currChannel->getElement('image');


		$img_src = $img_element->getUrl();
		$img_width = $img_element->getWidth();
		$img_height = $img_element->getHeight();
		$img_title = $img_element->getTitle();
   		$img_html = "<td width=\"1%\" class=\"rssimg\" bgcolor=\"black\"><a href=\"$channel_link\"><img title=\"$img_title\" height=\"$img_height\" width=\"$img_width\" src=\"$img_src\" border=\"0\"/></a></td>\n";
	}

	$url = $this->url;

$content = <<<EOQ

<style type="text/css">
.modtitle { font-family:arial,sans-serif; font-weight:bold; font-size:12pt; color:#000000 }
</style>

<table class="mod" id="2:0" border=0 cellpadding=0 cellspacing=0 width=100%>
<tr>
 <td bgcolor=aaaaaa>
  <table border=0 cellpadding=2 cellspacing=0 width=100%>
  <tr>

   <td class="modtitle" width="98%"><a href="$channel_link">$channel_title</a></td>
  $img_html
  </tr>
  </table>
 </td>
</tr>
<tr>
<td>
 <table border=0 cellpadding=3 cellspacing=0 width=100%>

 <tr>
 <td>


<style type="text/css">
.itemtitle { font-family:arial,sans-serif; font-weight:bold; font-size:10pt; color:#000000 }
.itemdate { font-family:arial,sans-serif; font-weight:normal; font-size:8pt; color:#999999 }
.itemdesc { font-family:times,serif; font-weight:normal; font-size:10pt; color:#000000 }
</style>
        <table cellpadding=4 cellspacing=0>
EOQ;

                //get total number of channels
                //$totalChannels = $rssdoc->getChannelCount();



                        //get total number of items
                        $totalItems = $currChannel->getItemCount();

                        //loop through each item
                        for ($j = 0; $j < $totalItems; $j++) {
                                //get reference to current item
                                $currItem =& $currChannel->getItem($j);
				$item_link = $currItem->getLink();
				$item_title = $currItem->getTitle();
				$item_date = $currItem->getPubDate();
				$item_desc = $currItem->getDescription();

                                //echo item info
$content .= "<tr><td align=top></td><td>\n";
$content .= "<table cellpadding=0 cellspacing=2><tr>\n";
$content .= "<td class=\"itemtitle\"><a target=\"_new\" href=\"$item_link\">$item_title</a></td>\n";
$content .= "</tr>\n";
$content .= "<tr><td class=\"itemdate\">$item_date</td></tr>\n";
$content .= "<tr><td class=\"itemdesc\">$item_desc</td></tr>\n";
$content .= "</table>\n";
$content .= "</td></tr>\n";

}
$content .= "</table> </td> </tr> </table>\n";
$content .= "</td> </tr> </table>\n";

return $content;
	}



}



?>
