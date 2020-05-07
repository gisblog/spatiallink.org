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

// $Id: PearDatabase.php,v 1.30.2.1 2005/05/17 19:55:11 robert Exp $

require_once('include/logging.php');
require_once('include/utils.php');

class PearDatabase{
	var $database = null;
	var $dieOnError = false;
	var $encode = true;
	var $dbType = null;
	var $dbHostName = null;
	var $dbName = null;
	var $dbOptions = null;
	var $userName=null;
	var $userPassword=null;
	var $query_time = 0;
	var $log = null;
	var $lastmysqlrow = -1;




    function checkOCIError($obj){
        $err = ocierror($obj);
        if ($err != false){
            $result = false;
            print_r($err);
            debug_trace();
            $this->log->fatal("OCIError:".var_export($err, true));
            return true;
        }
        return false; 
    }

	function setDieOnError($value){
		$this->dieOnError = $value;
	}

	function setDatabaseType($type){
		$this->dbType = $type;
	}

	function setUserName($name){
		$this->userName = $name;
	}

	function setOption($name, $value){
		if(isset($this->dbOptions))
			$this->dbOptions[$name] = $value;
		if(isset($this->database))
			$this->database->setOption($name, $value);
	}

	function setUserPassword($pass){
		$this->userPassword = $pass;
	}

	function setDatabaseName($db){
		$this->dbName = $db;
	}

	function setDatabaseHost($host){
		$this->dbHostName = $host;
	}

	function getDataSourceName(){
		return 	$this->dbType. "://".$this->userName.":".$this->userPassword."@". $this->dbHostName . "/". $this->dbName;
	}

	function checkError($msg='', $dieOnError=false){
		if($this->dbType == "mysql"){
			if (mysqli_errno()){
    			if($this->dieOnError || $dieOnError){
             	 	$this->log->fatal("MySQL error ".mysqli_errno().": ".mysqli_error($varconnect));	
    				die ($msg."MySQL error ".mysqli_errno().": ".mysqli_error($varconnect));		
    			}else{
    				$this->log->error("MySQL error ".mysqli_errno().": ".mysqli_error($varconnect));	
    			}
            }
        }
        else if ($this->dbType == 'oci8') {














		}
        else{
			if(!isset($this->database)){
				$this->log->error("Database Is Not Connected");
				return true;
			}
			if(DB::isError($this->database)){
				
				if($this->dieOnError || $dieOnError){
					$this->log->fatal($msg.$this->database->getMessage());
					 die ($msg.$this->database->getMessage());	
				}else{
					$this->log->error($msg.$this->database->getMessage());		
				}
				return true;
			}
			return true;
		}
        return false;
	}

	/**
	* @desc This method is called by every method that runs a query.
	*	If slow query dumping is turned on and the query time is beyond
	*	the time limit, we will log the query. This function may do
	*	additional reporting or log in a different area in the future.
	*/
	function dump_slow_queries($query)
	{
		global $sugar_config;

		$do_the_dump = isset($sugar_config['dump_slow_queries']) ? $sugar_config['dump_slow_queries'] : 0;
		$slow_query_time_msec = isset($sugar_config['slow_query_time_msec']) ? $sugar_config['slow_query_time_msec'] : 5000;

		if($do_the_dump)
		{
			if($slow_query_time_msec < ($this->query_time * 1000))
			{
				// Then log both the query and the query time
				$this->log->fatal("Slow Query: \n" . $query);
				$this->log->fatal('Slow Query Execution Time: ' .
					$this->query_time);
			}
		}
	}

	/**
	* @return void
	* @desc checks if a connection exists if it does not it closes the connection
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function checkConnection(){
			if(!isset($this->database)) $this->connect(false);
	}

	function query($sql, $dieOnError=false, $msg=''){
		$this->log->info('Query:' . $sql);
		//$this->log->fatal('Query:' . $sql);
		$this->checkConnection();
		$this->query_time = microtime();
		if($this->dbType == "mysql"){
			$result =& mysqli_query($varconnect, $sql);
			$this->lastmysqlrow = -1; 	
        }
        else if ($this->dbType == "oci8") {





















		}
        else{
			$result =& $this->database->query($sql);
		}
		$this->query_time = microtime_diff($this->query_time, microtime());
		$this->log->info('Query Execution Time:'.$this->query_time);

		$this->dump_slow_queries($sql);

		$this->checkError($msg.' Query Failed:' . $sql . '::', $dieOnError);
		return $result;
	}

	function limitQuery($sql,$start,$count, $dieOnError=false, $msg=''){
        $this->log->info('Limit Query:' . $sql. ' Start: ' .$start . ' count: ' . $count);
        $this->lastsql = $sql;
        
		if($this->dbType == "mysql")
			 return $this->query("$sql LIMIT $start,$count", $dieOnError, $msg);
        else if ($this->dbType == "oci8"){




		}

		$this->log->info('Limit Query:' . $sql. ' Start: ' .$start . ' count: ' . $count);
		$this->lastsql = $sql;

		$this->checkConnection();
		$this->query_time = microtime();
		$result =& $this->database->limitQuery($sql,$start, $count);
		$this->query_time = microtime_diff($this->query_time, microtime());
		$this->log->info('Query Execution Time:'.$this->query_time);

		$this->dump_slow_queries($sql);

		$this->checkError($msg.' Query Failed:' . $sql . '::', $dieOnError);
		return $result;
	}

	function getOne($sql, $dieOnError=false, $msg=''){
		$this->log->info("Get One: . |$sql|");
		$this->checkConnection();
		if($this->dbType == "mysql"){
			$queryresult =& $this->query($sql, $dieOnError, $msg);
            if (!$queryresult) $result = false;
			else $result =& mysqli_result($queryresult,0);
        }
        else if ($this->dbType == 'oci8'){














 		}
        else{
			$result =& $this->database->getOne($sql);
		}
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

		if($this->dbType == "mysql")
		{
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
        } else if($this->dbType == "oci8") {












		}else{
			$arr = tableInfo($result);
			foreach ($arr as $index=>$subarr)
			{
				array_push($field_array,$subarr['name']);
			}

		}

		return $field_array;

	}

	function getRowCount(&$result){
		if(isset($result) && !empty($result))
			if($this->dbType == "mysql"){
				return mysqli_numrows($result);
	        } else if($this->dbType == "oci8"){






    		}else{
				 return $result->numRows();
			}
		return 0;

	}
	function getAffectedRowCount(&$result){
			if($this->dbType == "mysql"){
				return mysqli_affected_rows();
	        } else if ($this->dbType == 'oci8'){





    		}
			else {
				return $result->affectedRows();
			}
		return 0;

	}
	function requireSingleResult($sql, $dieOnError=false,$msg='', $encode=true){
			$result = $this->query($sql, $dieOnError, $msg);

			//$this->log->fatal("requireSingleResult result:$result");
			
            if ($this->dbType == "mysql"){
    			if($this->getRowCount($result ) == 1)
	       			return $result;
            }else if ($this->dbType == 'oci8'){





            }
			$this->log->error('Rows Returned:'. $this->getRowCount($result) .' No row or more than 1 row returned for '. $sql);
			return '';
	}

	function ociFetchRow($result){
		$row = array();
		ocifetchinto($result, $row, OCI_ASSOC|OCI_RETURN_NULLS|OCI_RETURN_LOBS);
		if ($this->checkOCIError($result) == false){
			$temp = $row;
			$row = array();
			foreach ($temp as $key => $val){
				// make the column keys as lower case. Trim the val returned
				$row[strtolower($key)] = trim($val);
			}
		} else return false;
		
		return $row;
	}



    function fetchByAssoc(&$result, $rowNum = -1, $encode=true){
		if(isset($result) && $rowNum < 0){
			if($this->dbType == "mysql"){
				$row = mysqli_fetch_assoc($result);

				if($encode && $this->encode&& is_array($row))return array_map('to_html', $row);
				return $row;
			}
            else if ($this->dbType == 'oci8') {





            }
            return $row;
			//$row = $result->fetchRow(DB_FETCHMODE_ASSOC);	
		}
		if($this->dbType == "mysql"){
				if($this->getRowCount($result) > $rowNum){

					mysqli_data_seek($result, $rowNum);
				}
				$this->lastmysqlrow = $rowNum;

				$row = mysqli_fetch_assoc($result);

				if($encode && $this->encode && is_array($row))return array_map('to_html', $row);
				return $row;

		}
        else if($this->dbType == "oci8"){









        }
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
	/*function execute($stmt, $data, $dieOnError=false, $msg=''){
		$this->log->info('Executing:'.$stmt);
		$this->checkConnection();
		$this->query_time = microtime();
		$prepared	= $this->database->prepare($stmt);
		$result = execute($stmt, $data);
		$this->query_time = microtime_diff($this->query_time, microtime());
		//$this->log->info('Query Execution Time:'.$this->query_time);
		$this->checkError('Execute Failed:' . $stmt. '::', $dieOnError);
		return $result;
	}*/



	function connect($dieOnError = false){
		global $sugar_config;
		if($this->dbType == "mysql" && $sugar_config['dbconfigoption']['persistent'] == true){
			$this->database =@mysqli_pconnect($this->dbHostName,$this->userName,$this->userPassword);
			@mysqli_select_db($this->dbName) or die( "Unable to select database: " . mysqli_error($varconnect));
			if(!$this->database){
				$this->connection = mysqli_connect($this->dbHostName,$this->userName,$this->userPassword) or die("Could not connect to server ".$this->dbHostName." as ".$this->userName.".".mysqli_error($varconnect));
				if($this->connection == false && $sugar_config['dbconfigoption']['persistent'] == true){
					$_SESSION['administrator_error'] = "<B>Severe Performance Degradation: Persistent Database Connections not working.  Please set \$sugar_config['dbconfigoption']['persistent'] to false in your config.php file</B>";
				}
			}
		}
        else if($this->dbType == "oci8" && $sugar_config['dbconfigoption']['persistent'] == true){
















        }
		else $this->database = DB::connect($this->getDataSourceName(), $this->dbOptions);
		if($this->checkError('Could Not Connect:', $dieOnError))
			$this->log->info("connected to db");
			
        $this->log->info("Connect:".$this->database);            
	}
	function PearDatabase(){
			global $currentModule;
			$this->log =& LoggerManager::getLogger('PearDatabase_'. $currentModule);
			$this->resetSettings();
	}
	function resetSettings(){
		global $sugar_config;
		$this->disconnect();
		$this->setDatabaseType($sugar_config['dbconfig']['db_type']);
		$this->setUserName($sugar_config['dbconfig']['db_user_name']);
		$this->setUserPassword($sugar_config['dbconfig']['db_password']);
		$this->setDatabaseHost( $sugar_config['dbconfig']['db_host_name']);
		$this->setDatabaseName($sugar_config['dbconfig']['db_name']);
		$this->dbOptions = $sugar_config['dbconfigoption'];
		if($this->dbType != "mysql"){



		    	require_once( 'DB.php' );



		}
	}

    function quote($string){
        global $sugar_config;
        $string = from_html($string);
        if($sugar_config['dbconfig']['db_type'] == 'mysql'){
            $string = mysqli_escape_string($string);
        }else {//$string = quoteSmart($string);
        $string = strtr($string, array('_' => '\_', '%'=>'\%'));
        }
        return $string;
    }


    function disconnect() {
		if(isset($this->database)){
			if($this->dbType == "mysql"){
				mysqli_close($this->database);
            } else if ($this->dbType == 'oci8'){



			}else{
				$this->database->disconnect();
			}
			unset($this->database);
        }
    }	
    
    function tableExists($tableName){
        $this->log->info("tableExists: $tableName");
        
        $this->checkConnection();

        if ($this->database){
            if ($this->dbType == 'mysql'){
                $result = $this->query("SHOW TABLES LIKE '".$tableName."'");
                return ($this->getRowCount($result) == 0) ? false : true;                
            }else if ($this->dbType == 'oci8') {






            }
        }
        return false;
    }    
}
?>
