<?php /*********************************************************************************
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
 * $Id: CustomFields.php,v 1.37.2.1 2005/05/08 07:15:40 clint Exp $
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
require_once('cache/custom_fields/custom_fields_def.php');
require_once('modules/Administration/Common.php');
require_once('include/TimeDate.php');

class CustomFields extends SugarBean {
	// Stored fields
	var $id;
	var $num_custom_fields = 10;

	var $table_name = "custom_fields";
	var $object_name = "CustomFields";
	var $new_schema = true;
	var $column_fields = Array("id"
		,"date_entered"
		,"deleted"
		,"date_modified"
		,"modified_user_id"
		,"assigned_user_id"
		);
	var $td;

	function CustomFields() {

		$this->log = LoggerManager::getLogger('custom_fields');
		$this->td = new TimeDate();
		$this->db = new PearDatabase();
	}

	function setFieldDefs(&$focus)
	{
		global $custom_fields_def;
		global $app_list_strings;
		if (!  isset($custom_fields_def[$focus->object_name]) || count($custom_fields_def[$focus->object_name]) == 0)
		{
			return;
		}

		$select_arr = array();
		$this->get_list_query_custom_select_array($focus,$select_arr);

		foreach($custom_fields_def[$focus->object_name] as $index=>$field )
		{
			$field['vname'] = preg_replace('/^[A-Z]+\./','',$field['label']);
			$field['rname'] = $select_arr[$field['name']]['table_field'];
			$field['table'] = $select_arr[$field['name']]['table_name'];
			$field['custom_type'] = $field['type'];
			if ( $field['custom_type'] == 'char')
			{
				$field['custom_type'] = 'varchar';
			}
			$field['type'] = 'relate' ;
                       //array('name'=>'account_name','rname'=>'name','id_name'=>'account_id','vname'=>'LBL_ACCOUNT_NAME','type'=>'relate','table'=>'accounts','isnull'=>'true','module'=>'Accounts'),
			array_push($focus->field_defs,$field);
		}
		$this->get_list_query_custom_from_array($focus,$focus->joins);

	}

	function setWhereClauses(&$focus,&$where_clauses_arr)
	{
		global $custom_fields_def;

		if (!  isset($custom_fields_def[$focus->object_name]) )
		{
			return;
		}

		$num_module_fields = count($custom_fields_def[$focus->object_name]);

		if ( $num_module_fields == 0)
		{
			return;
		}

		$field_count = 0;

		foreach($custom_fields_def[$focus->object_name] as $index=>$field )
		{
			$current_row = floor( $index / $this->num_custom_fields);
			$field_count = floor($index % $this->num_custom_fields);
			$focus_field = $field['name'];
			$field_name = 'custom_fields'.$current_row.".field".$field_count;

			if ( isset($_REQUEST[$focus_field]) && $_REQUEST[$focus_field] != "")
			{

				if ( $field['type'] == 'char')
				{
					array_push($where_clauses_arr, "{$field_name} LIKE '".PearDatabase::quote($_REQUEST[$focus_field])."%'");
				}
				else
				{
					array_push($where_clauses_arr, "{$field_name} = '".PearDatabase::quote($_REQUEST[$focus_field])."'");
				}
			}

			$field_count++;
		}
	}

	function setXtplSearchVars(&$focus,&$xtpl)
	{
		global $custom_fields_def;
		global $app_list_strings;
		if (!isset($custom_fields_def)|| !isset($focus->object_name) || !  isset($custom_fields_def[$focus->object_name]) || count($custom_fields_def[$focus->object_name]) == 0)
		{
			return;
		}

		foreach($custom_fields_def[$focus->object_name] as $index=>$field )
		{
			$focus_field = $field['name'];

			if ( empty($_REQUEST[$focus_field]))
			{
				$_REQUEST[$focus_field] = '';;
			}

			$xtpl_var = strtoupper( $focus_field);
			$xtpl->assign($xtpl_var, $field['label']);

			if ($field['type'] == 'date')
			{
				$xtpl->assign($xtpl_var, substr($_REQUEST[$focus_field],0,16));
			}
			else if ($field['type'] == 'bool')
			{
				if ( isset($_REQUEST[$focus_field]) && $_REQUEST[$focus_field] == 'on')
				$xtpl->assign($xtpl_var, " CHECKED");
			}
			else if ($field['type'] == 'enum')
			{
				$xtpl->assign($xtpl_var, $_REQUEST[$focus_field]);

				// using for edit view..
				$xtpl_var = 'OPTIONS_'.$xtpl_var;
				$xtpl->assign($xtpl_var, get_select_options_with_id($app_list_strings[$field['options']], $_REQUEST[$focus_field]));
			}
			else
			{
				$xtpl->assign($xtpl_var, $_REQUEST[$focus_field]);
			}
		}

	}

	function setXtplDetailVars(&$focus,&$xtpl)
	{
		global $custom_fields_def;
		global $app_list_strings;
		if (!  isset($custom_fields_def[$focus->object_name]) || count($custom_fields_def[$focus->object_name]) == 0)
		{
			return;
		}

		foreach($custom_fields_def[$focus->object_name] as $index=>$field )
		{
			$focus_field = $field['name'];
			if ( empty($focus->$focus_field))
			{
				$focus->$focus_field = '';;
			}

			$xtpl_var = strtoupper( $focus_field);
			$xtpl->assign($xtpl_var, $field['label']);

			if ($field['type'] == 'date')
			{
				$xtpl->assign('USER_DATEFORMAT', $this->td->get_user_date_format());
				$xtpl->assign('CALENDAR_DATEFORMAT', $this->td->get_cal_date_format());
				$xtpl->assign($xtpl_var, $this->td->to_display_date($focus->$focus_field, false));
				$xtpl->assign($xtpl_var, $this->td->to_display_date($focus->$focus_field, false));
			}
			else if ($field['type'] == 'bool')
			{
				if ( isset($focus->$focus_field) && $focus->$focus_field == 'on')
				$xtpl->assign($xtpl_var, " CHECKED");
			}
			else if ($field['type'] == 'enum')
			{
				
				$xtpl->assign($xtpl_var, $app_list_strings[$field['options']][$focus->$focus_field]);

				// using for edit view..
				$xtpl_var = 'OPTIONS_'.$xtpl_var;
				$xtpl->assign($xtpl_var, get_select_options_with_id($app_list_strings[$field['options']], $focus->$focus_field));
			}
			else
			{
				$xtpl->assign($xtpl_var, $focus->$focus_field);
			}
		}

	}

	function setXtplEditVars(&$focus,&$xtpl)
	{
		$this->setXtplDetailVars($focus,$xtpl);
	}

	function save_field(&$args)
	{
		global $custom_fields_def;
		global $current_language;
		global $beanList;
		global $beanFiles;

		if ( empty( $args['field_label']) ||
			empty( $args['module_name']) ||
			empty( $args['field_type'])
		)
		{
			die ("correct parameters not set for save field");
		}

		if ( empty($beanList[$args['module_name']]))
		{
			die ("module is not defined in the beanList");
		}

		$object_name = $beanList[$args['module_name']];
		require_once($beanFiles[$object_name]);
		$bean = new $object_name();

		if ( empty($custom_fields_def[$bean->object_name]))
		{
			$custom_fields_def[$bean->object_name] = array();
		}


		$new_field = array();
		$new_field['name'] = preg_replace("/[^\w]+/","_",strtolower($args['field_label']));

		foreach($bean->column_fields as $column_name)
		{
			if ($new_field['name'] == $column_name)
			{
				$new_field['name'] .= '_custom';
			}
		}
		

		$field_key = "LBL_".strtoupper($new_field['name']);
		$curr_field_key = $field_key;
		$count = 1;
		$limit = 10;
		while( ! create_field_label($args['module_name'], $current_language, $curr_field_key, $args['field_label']) )
		{
			$curr_field_key = $field_key. "_$count";
			if ( $count == $limit)
			{
				sugar_die("can't create field label");
			}
			$count++;
		}

		$new_field['label'] = "MOD.".$curr_field_key;

		$new_field['type'] = $args['field_type'];

		if ( $new_field['type'] == 'enum')
		{
			if ( empty( $args['options'] ))
			{
				die("options where not defined for this field");
			}

			$new_field['options'] = $args['options'];
		}
		array_push($custom_fields_def[$bean->object_name],$new_field);
		$this->save_custom_file($custom_fields_def);
		return $new_field;
	}

	function save_custom_file($fields_def)
	{
		$GLOBAL_CUSTOM_FIELDS_FILE = "cache/custom_fields/custom_fields_def.php";
		$fp = fopen($GLOBAL_CUSTOM_FIELDS_FILE,"w");
		if (! $fp)
		{
			sugar_die("Can't write custom fields definition file");
		}
		fwrite($fp,"<?php\n");
		fwrite($fp,"\$custom_fields_def = \n");
		fwrite($fp,var_export($fields_def,true));
		fwrite($fp,"\n?>");
		fclose($fp);

	}

	function get_custom_fields_by_module_name($module_name)
	{
		global $custom_fields_def,$beanList,$beanFiles;

		$object_name = $beanList[$module_name];
		require_once($beanFiles[$object_name]);
		$bean = new $object_name();

		if ( empty($custom_fields_def[$bean->object_name]))
		{
			return array();
		}

		return $custom_fields_def[$bean->object_name];

	}

	function get_field_def($module_name,$field_name)
	{
		$module_custom_fields_def = $this->get_custom_fields_by_module_name($module_name);

		$field_def = null;

		foreach ( $module_custom_fields_def as $index=>$field)
		{
			if ( $field['name'] == $field_name)
			{
				$field_def = $field;
			}
		}

		if ( $field_def == null)
		{
			die("$field_name is not defined in this module: $module_name");
		}

		return $field_def;
	}

	function get_edit_html($module_name,$field_name)
	{

		$field_def = $this->get_field_def($module_name,$field_name);
		$html_func = "get_edit_html_".$field_def['type'];

		if ( method_exists($this,$html_func))
		{
			return $this->$html_func($field_def);
		}
		else
		{
			return "function not defined: $html_func";
		}
	}

	function get_detail_html($module_name,$field_name)
	{

		$field_def = $this->get_field_def($module_name,$field_name);
		$html_func = "get_detail_html_".$field_def['type'];

		if ( method_exists($this,$html_func))
		{
			return $this->$html_func($field_def);
		}
		else
		{
			return "function not defined: $html_func";
		}
	}

	function get_html_label($module_name,$field_name)
	{
		$field_def = $this->get_field_def($module_name,$field_name);
		$xtpl_var = strtoupper( $field_def['label']);
		return '{'.$xtpl_var. '}';
	}

	function get_detail_html_char($field_def)
	{
		$xtpl_var = strtoupper( $field_def['name']);
		return '{'.$xtpl_var. '}';
	}

	function get_edit_html_int($field_def)
	{
		$xtpl_var = strtoupper( $field_def['name']);
		return "<input type=\"text\" name=\"{$field_def['name']}\" value=\"{".$xtpl_var. "}\"/>";
	}

	function get_detail_html_int($field_def)
	{
		$xtpl_var = strtoupper( $field_def['name']);
		return '{'.$xtpl_var. '}';
	}

	function get_edit_html_char($field_def)
	{
		$xtpl_var = strtoupper( $field_def['name']);
		return "<input type=\"text\" name=\"{$field_def['name']}\" value=\"{".$xtpl_var. "}\"/>";
	}

	function get_detail_html_date($field_def)
	{
		$xtpl_var = strtoupper( $field_def['name']);
		return '{'.$xtpl_var. '}';
	}

	function get_edit_html_date($field_def)
	{
		global $theme;
		$xtpl_var = strtoupper( $field_def['name']);

		$html = "<input name='{$field_def['name']}' id='jscal_field{$field_def['name']}' type=\"text\" tabindex='1' size='11' maxlength='10' value=\"{".$xtpl_var."}\"> <img src=\"themes/$theme/images/jscalendar.gif\" alt=\"Enter Date\"  id=\"jscal_trigger{$field_def['name']}\" align=\"absmiddle\"> <span class=\"dateFormat\">{USER_DATEFORMAT}</span>
<script type=\"text/javascript\">
        Calendar.setup ({
                inputField : \"jscal_field{$field_def['name']}\", ifFormat : \"{CALENDAR_DATEFORMAT}\", showsTime : false, button : \"jscal_trigger{$field_def['name']}\", singleClick : true, step : 1
        });
        &lt;/script&gt;";



		return $html;
	}

	function get_detail_html_enum($field_def)
	{
		$xtpl_var = strtoupper( $field_def['name']);
		return "{".$xtpl_var. "}";
	}

	function get_detail_html_bool($field_def)
	{
		$xtpl_var = strtoupper( $field_def['name']);
		return "<input type=\"checkbox\" name=\"{$field_def['name']}\" {".$xtpl_var. "} disabled/>";
	}

	function get_edit_html_enum($field_def)
	{
		$xtpl_var = strtoupper( $field_def['name']);
		return "<select name=\"{$field_def['name']}\">{OPTIONS_".$xtpl_var. "}</select>";
	}

	function get_edit_html_bool($field_def)
	{
		$xtpl_var = strtoupper( $field_def['name']);
		return "<input type=\"checkbox\" name=\"{$field_def['name']}\" {".$xtpl_var. "}/>";
	}

	

	function drop_tables()
        {
		$query = 'DROP TABLE IF EXISTS '.$this->table_name;

		$this->db->query($query);

	}

	function create_list_query(&$order_by, &$where)
	{
		$query = "SELECT
                users.user_name as assigned_user_name, ";
                $query .= " FROM {$this->table_name} ";

		$query .=		"LEFT JOIN users
	                    ON contacts.assigned_user_id=users.id ";

		$where_auto = " custom_fields.deleted=0 ";

		if($where != "")
			$query .= "where ($where) AND ".$where_auto;
		else
			$query .= "where ".$where_auto;

		if(!empty($order_by))
			$query .= " ORDER BY $order_by";

		return $query;
	}

	function retrieve(&$bean)
	{
		global $custom_fields_def;
		if (!  isset($custom_fields_def[$bean->object_name]) || count($custom_fields_def[$bean->object_name]) == 0)
		{
			return;
		}
		$num_rows = floor(count($custom_fields_def[$bean->object_name]) / $this->num_custom_fields) + 1;
		$query = "SELECT * FROM {$this->table_name} where bean_id='{$bean->id}' order by set_num ASC";
                $this->log->info("Select: ".$query);
                $result = $this->db->query($query, true);
		$i = 0;
		while ( $row = $this->db->fetchByAssoc($result, -1) )
		{
			for($j = 0; $j < $this->num_custom_fields;$j++)
                	{
				$field_index = ($j + ($i * 10));
                  		if ( isset($custom_fields_def[$bean->object_name][$field_index]))
				{
					$field_name = $custom_fields_def[$bean->object_name][$field_index]['name'];
					$db_field_name = "field$j";
					$bean->$field_name = $row[$db_field_name];
				}
			}
			$i++;
		}

	}

	function save(&$bean)
	{
		global $custom_fields_def;
		if (!  isset($custom_fields_def[$bean->object_name]) || count($custom_fields_def[$bean->object_name]) == 0)
		{
			return;
		}
		$num_rows = floor(count($custom_fields_def[$bean->object_name]) / $this->num_custom_fields) + 1;
		$query = "SELECT * FROM {$this->table_name} where bean_id='{$bean->id}' order by set_num";
                $result = $this->db->query($query, true);
		$rows_exist = $this->db->getRowCount($result);

		$isUpdate = 0;

		for ($i = 0; $i < $num_rows; $i++)
		{
			$firstPass = 0;
			if ( $i >= $rows_exist)
			{
				$query = "INSERT INTO {$this->table_name} SET ";
				$isUpdate = 0;
			}
			else
			{
				$query = "UPDATE {$this->table_name} SET ";
				$isUpdate = 1;
			}
			$query .= "bean_id='{$bean->id}', set_num={$i}, ";

			for($j = 0; $j < $this->num_custom_fields;$j++)
                	{
				$field_index = ($j + ($i * 10));
                  		if (! isset($custom_fields_def[$bean->object_name][$field_index]))
				{
					$value = '';
				}
				else
				{
                  			$field = $custom_fields_def[$bean->object_name][$field_index];
				
				if ( $bean->save_from_post && (isset($_REQUEST['name']) || isset($_REQUEST['last_name']))  )
					{
						if ( ! isset($_POST[$field['name']]))
						{
							$value = '';
						}
						else
						{
							$value =
							PearDatabase::quote($_POST[$field['name']]);
						}
					}	
					else
					{
						if ( ! isset($bean->$field['name']))
						{
							$value = '';
						}
						else
						{
							$value =
							PearDatabase::quote($bean->$field['name']);
						}
					}
				}
				 if($field['type']== 'date'){
						$value = $this->td->to_db_date($value, false);
        }

				if(0 == $firstPass)
                                        $firstPass = 1;
                                else
                                        $query .= ", ";

   				$query .= 'field'. $j . "='" .$value ."'";
			}

			if ($isUpdate )
			{
				$query .= " where bean_id='{$bean->id}' AND set_num={$i}";
			}
                	$result = $this->db->query($query, true);
		}

		for ($i = $num_rows; $i < $rows_exist; $i++)
		{
			$query = "DELETE FROM {$this->table_name} ";
			$query .= " where bean_id='{$bean->id}' AND set_num={$i}";
                	$result = $this->db->query($query, true);
		}

	}


        function get_list_query_custom_select(&$bean)
	{
		$select_arr = array();
		$new_fields = array();
        	$this->get_list_query_custom_select_array($bean,$select_arr);
		foreach($select_arr as $key=>$custom_fields)
		{
			array_push( $new_fields, " {$custom_fields['table_name']}.{$custom_fields['table_field']} AS '{$custom_fields['field_name']}' ");
		}
		if ( count($new_fields) == 0)
		{
			return "";
		}
		else
		{
			return implode(",",$new_fields) . ",";
		}
	}

        function get_list_query_custom_select_array(&$bean,&$select_arr)
        {
		global $custom_fields_def;
                //return " custom_fields0.field0 AS 'test', ";
		if ( ! isset($custom_fields_def[$bean->object_name]) || count($custom_fields_def[$bean->object_name]) == 0)
		{
			return "";
		}

		$num_rows = floor(count($custom_fields_def[$bean->object_name]) / $this->num_custom_fields) + 1;

		$custom_fields = array();
                for($i = 0; $i < $num_rows;$i++)
		{
                        for($j = 0; $j < $this->num_custom_fields;$j++)
                        {
				$field_index = ($j + ($i * 10));

                                if ( isset($custom_fields_def[$bean->object_name][$field_index]))
				{
					$field_name = $custom_fields_def[$bean->object_name][$field_index]['name'];
					$select_arr[$field_name] = array('table_name'=>"custom_fields${i}","table_field"=>"field{$j}","field_name"=>"{$field_name}");
                                }
                        }
                }


        }

        function get_list_query_custom_from(&$bean)
        {
		$joins_array = array();
		$new_array = array();
        	$this->get_list_query_custom_from_array($bean,$joins_array);
		foreach($joins_array as $key=>$arr)
		{
			array_push($new_array,$arr['join']);
		}
		return implode(" ",$new_array);
	}

        function get_list_query_custom_from_array(&$bean,&$joins_arr)
        {
		global $custom_fields_def;

		if ( ! isset($custom_fields_def[$bean->object_name]) || count($custom_fields_def[$bean->object_name]) == 0)
		{
			return "";
		}

		$num_rows = floor(count($custom_fields_def[$bean->object_name]) / $this->num_custom_fields) + 1;


                for($i = 0; $i < $num_rows;$i++)
		{
			$joins_arr["custom_fields{$i}"] = array("join"=>"LEFT JOIN custom_fields AS custom_fields{$i} ON custom_fields{$i}.bean_id ={$bean->table_name}.id AND custom_fields{$i}.set_num ={$i} ","where"=>"");
		}
        }

	function addListFields(&$bean)
	{
		global $custom_fields_def;

		if ( ! isset($custom_fields_def[$bean->object_name]) || count($custom_fields_def[$bean->object_name]) == 0)
		{
			return "";
		}
		for($i = 0;$i<count($custom_fields_def[$bean->object_name]);$i++)
		{
			array_push($bean->list_fields,$custom_fields_def[$bean->object_name][$i]['name']);
		}

	}

	function mark_deleted($bean_id)
	{
		$query = "update {$this->table_name} set deleted=1 WHERE bean_id='{$bean_id}'";
               	$result = $this->db->query($query, true);
	}


}



?>
