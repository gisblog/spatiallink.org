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
 * $Id: Administration.php,v 1.20.2.2 2005/05/17 22:38:06 majed Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');

class Administration extends SugarBean {
	var $table_name = "config";
	var $object_name = "Administration";
	var $new_schema = true;
	var $module_dir = 'Administration';
	var $config_categories = Array(
							'mail',
							 'notify', 
							'system',
							 'portal',



							 );

	var $checkbox_fields = Array("notify_send_by_default", "mail_smtpauth_req", "notify_on", 'portal_on');

	function Administration() {
		parent::SugarBean();
		$this->log = LoggerManager::getLogger('Administration');
		
		 $this->setupCustomFields('Administration');



	}

	

	

	function retrieveSettings($category = FALSE) {
		$query = "SELECT category, name, value FROM $this->table_name";
		if ($category) {
			$query .= " WHERE category='$category'";
		}

		$result =& $this->db->query($query, true, "Unable to retrieve system settings");
		if (empty($result)) {
			return NULL;
		}

		while ($row = $this->db->fetchByAssoc($result, -1, true)) {
			$this->settings[$row['category']."_".$row['name']] = $row['value'];
		}

		return $this;
	}

	function saveSetting($category, $key, $value) {
		$result =& $this->db->query("UPDATE config SET value = '{$value}' WHERE category = '{$category}' AND name = '{$key}'");
		if($this->db->getAffectedRowCount($result) == 0){
			$result =& $this->db->query("INSERT INTO config (value, category, name) VALUES ('$value','$category', '$key');");
		}
		
		return $this->db->getAffectedRowCount($result);
	
	}

	function get_config_prefix($str) {
		return Array(substr($str, 0, strpos($str, "_")), substr($str, strpos($str, "_")+1));
	}
}
?>
