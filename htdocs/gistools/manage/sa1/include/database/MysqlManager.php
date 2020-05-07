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
* $Id: MysqlManager.php,v 1.4 2005/04/27 17:57:06 bob Exp $
* Description: This file handles the Data base functionality for the application.
* It acts as the DB abstraction layer for the application. It depends on helper classes
* which generate the necessary SQL. This sql is then passed to PEAR DB classes.
* The helper class is chosen in DBManagerFactory, which is driven by 'db_type' in 'dbconfig' under config.php.
*
* All the functions in this class will work with any bean which implements the meta interface.
* The passed bean is passed to helper class which uses these functions to generate correct sql.
*
* The meta interface has the following functions:
* getTableName()	        	Returns table name of the object.
* getFieldDefinitions()	    	Returns a collection of field definitions in order. 
* getFieldDefintion(name)		Return field definition for the field.
* getFieldValue(name)	    	Returns the value of the field identified by name. 
*                           	If the field is not set, the function will return boolean FALSE.
* getPrimaryFieldDefinition()	Returns the field definition for primary key
*
* The field definition is an array with the following keys: 
* 
* name 		This represents name of the field. This is a required field.
* type 		This represents type of the field. This is a required field and valid values are:
*      		•	int
*      		•	long
*      		•	varchar
*      		•	text
*      		•	date
*      		•	datetime
*      		•	double
*      		•	float
*      		•	uint
*      		•	ulong
*      		•	time
*      		•	short
*      		•	enum
* length	This is used only when the type is varchar and denotes the length of the string. 
*  			The max value is 255.
* enumvals  This is a list of valid values for an enum separated by "|". 
*			It is used only if the type is ‘enum’;
* required	This field dictates whether it is a required value. 
*			The default value is ‘FALSE’.
* isPrimary	This field identifies the primary key of the table. 
*			If none of the fields have this flag set to ‘TRUE’, 
*			the first field definition is assume to be the primary key. 
*			Default value for this field is ‘FALSE’.
* default	This field sets the default value for the field definition.
* 
* 
* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
* All Rights Reserved.
* Contributor(s): ______________________________________..
********************************************************************************/

include_once('config.php');
include_once('sugar_version.php');
require_once('include/logging.php');
include_once('DBManager.php');
include_once('MysqlHelper.php');

//Technically we can port all the functions in the latest bean to this file
// that is what PEAR is doing anyways.

class MysqlManager extends DBManager
{
	function MysqlManager(){
		global $currentModule;
		
		parent::DBManager();
		$this->log = LOGGER::getLogger('MysqlManager_'.$currentModule);
	}	
	
	function checkError($msg='', $dieOnError=false){
		if (mysqli_errno()){
			if($this->dieOnError || $dieOnError){
         	 	$this->log->fatal("MySQL error ".mysqli_errno().": ".mysqli_error($varconnect));	
				die ($msg."MySQL error ".mysqli_errno().": ".mysqli_error($varconnect));	
			}else{
				$this->log->error("MySQL error ".mysqli_errno().": ".mysqli_error($varconnect));	
			}
			return true;
		}
		return false;		
	}
/*    
    function query($sql, $dieOnError=false, $msg=''){
        $this->log->info('Query:' . $sql);
        $this->checkConnection();
        $this->query_time = microtime();
        $result =& mysqli_query($varconnect, $sql);
        $this->lastmysqlrow = -1;   
        $this->query_time = microtime_diff($this->query_time, microtime());
        $this->log->info('Query Execution Time:'.$this->query_time);

        $this->dump_slow_queries($sql);
        
        $this->checkError($msg.' Query Failed:' . $sql . '::', $dieOnError);    
        return $result;
    }
    
    function limitQuery($sql,$start,$count, $dieOnError=false, $msg=''){
       return $this->query("$sql LIMIT $start,$count", $dieOnError, $msg);
    }
    
    function getOne($sql, $dieOnError=false, $msg=''){
        $this->log->info('Get One:' . $sql);
        $this->checkConnection();
        $queryresult =& $this->query($sql, $dieOnError, $msg);
        $result =& mysqli_result($queryresult,0);
        $this->checkError($msg.' Get One Failed:' . $sql . '::', $dieOnError);  
        return $result;
    }

    function getFieldsArray(&$result)
    {
        $field_array = array();

        if(! isset($result) || empty($result))
        {
            return 0;
        }

            $i = 0;
            while ($i < mysqli_num_fields($result)) 
            {
                $meta = mysqli_fetch_field($result, $i);

                if (!$meta) 
                {
                    return 0;
                }
                    
                array_push($field_array,$meta->name);

                $i++;
            }

        return $field_array;
            
    }
    
    function getRowCount(&$result){
        if(isset($result) && !empty($result))
                return mysqli_numrows($result);
        return 0;
            
    }
    function getAffectedRowCount(&$result){
                return mysqli_affected_rows();
        return 0;
            
    }
    function requireSingleResult($sql, $dieOnError=false,$msg='', $encode=true){
            $result = $this->query($sql, $dieOnError, $msg);
            if($this->getRowCount($result ) == 1)
                return to_html($result, $encode && $this->encode);
            $this->log->error('Rows Returned:'. $this->getRowCount($result) .' No row or more than 1 row returned for '. $sql);
            return '';
    }
    
    
    
    function fetchByAssoc(&$result, $rowNum = -1, $encode=true){
        if(isset($result) && $rowNum < 0){
                $row = mysqli_fetch_assoc($result);
                
                if($encode && $this->encode&& is_array($row))return array_map('to_html', $row); 
                return $row;
            $row = $result->fetchRow(DB_FETCHMODE_ASSOC);   
        }
                if($this->getRowCount($result) > $rowNum){

                    mysqli_data_seek($result, $rowNum);  
                }
                $this->lastmysqlrow = $rowNum;
            
                $row = mysqli_fetch_assoc($result);
                
                if($encode && $this->encode && is_array($row))return array_map('to_html', $row);    
                return $row;
                
        $row = $result->fetchRow(DB_FETCHMODE_ASSOC, $rowNum);
        if($encode && $this->encode)return array_map('to_html', $row);  
        return $row;
    }
    
    function getNextRow(&$result, $encode=true){
        if(isset($result)){
            $row = $result->fetchRow();
            if($encode && $this->encode&& is_array($row))return array_map('to_html', $row); 
                return $row;
            
        }
        return null;
    }
    
    
    function getQueryTime(){
        return $this->query_time;   
    }

    function connect($dieOnError = false){
        global $sugar_config;
        if($this->dbType == "mysql" && $sugar_config['dbconfigoption']['persistent'] == true){
            $this->database =@mysqli_pconnect($this->dbHostName,$this->userName,$this->userPassword);
            @mysqli_select_db($this->dbName) or die( "Unable to select database");               
            if(!$this->database){
                $this->connection = mysqli_connect($this->dbHostName,$this->userName,$this->userPassword) or die("Could not connect to server ".$this->dbHostName." as ".$this->userName.".".mysqli_error($varconnect));
                if($this->connection == false && $sugar_config['dbconfigoption']['persistent'] == true){
                    $_SESSION['administrator_error'] = "<B>Severe Performance Degradation: Persistent Database Connections not working.  Please set \$sugar_config['dbconfigoption']['persistent'] to false in your config.php file</B>";           
                }   
            }
        }
        if($this->checkError('Could Not Connect:', $dieOnError))
            $this->log->info("connected to db");
            
    }
    
    function disconnect() {
        if(isset($this->database)) mysqli_close($this->database);
        unset($this->database);    
    }        

    function quote($string){
        $this->checkConnection();
        return $this->helper->quote($string);
    }
*/    
    function tableExists($table_name){
        $result = $this->query("SHOW TABLES LIKE '".$table_name."'");
     	
        return ($this->getRowCount($result) == 0) ? false : true;
    }
}

?>
