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
* $Id: DBManagerFactory.php,v 1.6 2005/04/27 17:57:06 bob Exp $
* Description: This file generates the appropriate manager for the database
* 
* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
* All Rights Reserved.
* Contributor(s): ______________________________________..
********************************************************************************/

include_once('config.php');

include_once('sugar_version.php');
require_once('include/logging.php');
require_once('MysqlManager.php');
require_once('MysqlHelper.php');





class DBManagerFactory
{
	/** This function returns the correct instance of the manager
	*   depending on the database type
	*/
	function getInstance(){
		global $sugar_config, $db_instance;
		if(!isset($db_instance)){
            if( $sugar_config['dbconfig']['db_type'] == "oci8" ){



            }
            else {
                $db_instance =& new MysqlManager;
            }
		}
		return $db_instance;
	}

    /** This function returns the correct instance of the manager
    *   depending on the database type
    */
    function getHelperInstance(){
        global $sugar_config;

        if( $sugar_config['dbconfig']['db_type'] == "oci8" ){



        }
        else {
            return new MysqlHelper;
        }
    }
}

?>
