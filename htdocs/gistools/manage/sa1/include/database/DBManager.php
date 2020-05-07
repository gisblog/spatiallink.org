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
* $Id: DBManager.php,v 1.7.2.1 2005/05/17 19:55:11 robert Exp $
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
include_once('include/database/PearDatabase.php');

class DBManager extends PearDatabase
{
	var $helper;
    var $tableName;
    
	function DBManager(){
		global $sugar_config, $currentModule;
        parent::PearDatabase();

        $my_db_helper = 'MysqlHelper';
        if( $sugar_config['dbconfig']['db_type'] == "oci8" ){



        }

 		$this->helper = new $my_db_helper($this);
		$this->log =& LoggerManager::getLogger('DBManager_'. $currentModule);		
	}

    function getHelper(){
        return $this->helper;
    }
    
	/**
	* This method implements a generic insert for any bean.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved..
	* Contributor(s): ______________________________________..
	*/
	function insert($bean){
		$sql = $this->helper->insertSQL($bean);
		$this->tableName = $bean->getTableName();
		$this->insertSQL($sql);;
	}
	
	/**
	* This method implements a generic update for any bean.
	* The where is an array of values with the keys as names of fields.
	* If we want to pass multiple values for a name, pass it as an array
	* If where is not passed, it defaults to id of table
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
	function update($bean, $where = array()){
		$sql = $this->helper->updateSQL($bean, $where);
		$this->tableName = $bean->getTableName();
		$this->updateSQL($sql);
	}
	
	/**
	* This method implements a generic delete for any bean idnetified by id.
	* The where is an array of values with the keys as names of fields.
	* If we want to pass multiple values for a name, pass it as an array
	* If where is not passed, it defaults to id of table
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
	function delete($bean, $where = array()){
        $sql = $this->helper->deleteSQL($bean, $where);
        $this->tableName = $bean->getTableName();
        $this->deleteSQL($sql);
	}
	
	/**
	* This method implements a generic retrieve for any bean identified by id.
	* The where is an array of values with the keys as names of fields.
	* If we want to pass multiple values for a name, pass it as an array
	* If where is not passed, it defaults to id of table
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
	function retrieve($bean, $where = array()){
		$sql = $this->helper->retrieveSQL($bean, $where);
		$this->tableName = $bean->getTableName();
		return $this->retrieveSQL($sql);
	}
	
    /**
    * This method implements a generic retrieve for a collection of beans.
    * These beans will be joined in the sql by the key attribute of field defs.
    * 
    * Cols is an array of columns to be returned with the keys as names of bean as identified by 
    * get_class of bean. Values of this array is the array of fieldDefs to be returned for a bean.
    * If an empty array is passed, all columns are selected.
    *  
    * Where is an array of values with the keys as names of bean as identified by get_class of bean
    * Each value at the first level is an array of values for that bean identified by name of fields.
    * If we want to pass multiple values for a name, pass it as an array
    * If where is not passed, all the rows will be returned.
    * 
    * Currently, this function does support outer joins.
    * 
    * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
    * All Rights Reserved.
    * Contributor(s): ______________________________________..
    */
    function retrieveView($beans, $cols = array(), $where = array()){
        $sql = $this->helper->retrieveViewSQL($beans, $cols, $where);

        $this->tableName = "View Collection"; // just use this string for msg
        return $this->retrieveSQL($sql);
    }
    
    
	/**
	* This method implements creation of a db table for a bean.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
	function createTable($bean){
		$sql = $this->helper->createTableSQL($bean);

		$this->tableName = $bean->getTableName();
		$this->createTableSQL($sql);
	}
	
	function createTableParams($tablename, $fieldDefs, $indices){
			$sql = $this->helper->createTableSQLParams($tablename, $fieldDefs, $indices);
			$this->tableName = $tablename;
			$this->createTableSQL($sql);
	} 
		 
	
	/**
	* This method creates an index identified by name on the given fields.
	* Non Unique index is created if $unique is set to FALSE
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
	function createIndex($bean, $fieldDefs, $name, $unique=TRUE){
		$sql = $this->helper->createIndexSQL($bean, $fieldDefs, $name, $unique);
		$this->tableName = $bean->getTableName();
		$this->createIndexSQL($sql,$fieldDefs, $name, $unique);
	}
	
	/**
	* This method adds a column to table identified by field def.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
	function addColumn($bean, $fieldDefs){
		$sql = $this->helper->addColumnSQL($bean, $fieldDefs);
		$this->tableName = $bean->getTableName();
		$this->addColumnSQL($sql, $fieldDefs);;
	}
	
	/**
	* This method alters old column identified by oldFieldDef to new fieldDef.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
	function alterColumn($bean, $newFieldDef){
		$sql = $this->helper->alterColumnSQL($bean, $newFieldDef);
		$this->tableName = $bean->getTableName();
		$this->alterColumnSQL($sql, $newFieldDef);
	}
	
	/**
	* This method drop a table.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
	function dropTable($bean){
        $this->table_name =  $bean->getTableName();
        $this->dropTableName( $this->table_name);
	}
	
	function dropTableName($name){
		$sql = $this->helper->dropTableNameSQL($name);
        $this->dropTableSQL($sql);
	}
	
	/**
	* This method deletes a column identified by fieldDef.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
	function deleteColumn($bean, $fieldDefs){
		$sql = $this->helper->deleteColumnSQL($bean, $fieldDefs);
		$this->tableName = $bean->getTableName();
		$this->deleteColumnSQL($sql, $fieldDefs);
	}	

    /*****************************************************************************
    ** SQL Functions
    */

	/**
	* This method implements a generic insert for any bean.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved..
	* Contributor(s): ______________________________________..
	*/
	function insertSQL($sql){
		$msg = "Error inserting into table: ".$this->tableName;
		$this->executeQuery($sql, $msg);
	}
	
	/**
	* This method implements a generic update for any bean.
	* Updates are based for the row identified by primary key only.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
	function updateSQL($sql){
		$msg = "Error updating table: ".$this->tableName. ":";
		if ($GLOBALS['test123']) { echo "<h1>save3</h1>"; }
		$this->executeQuery($sql, $msg);
	}
	
	/**
	* This method implements a generic delete for any bean idnetified by id.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
	function deleteSQL($sql){
		$msg = "Error deleting from table: ".$this->tableName. ":";
		$this->executeQuery($sql, $msg);
	}
	
	/**
	* This method implements a generic retrieve for any bean identified by id.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
	function retrieveSQL($sql){
		$msg = "Error retriving values from table:".$this->tableName. ":";
		$result = $this->executeQuery($sql, $msg, true);

		return $result;
	}
	
	/**
	* This method implements creation of a db table for a bean.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
	function createTableSQL($sql){
		$msg = "Error creating table: ".$this->tableName. ":";
		$this->executeQuery($sql, $msg);
	}
	
	/**
	* This method creates an index identified by name on the given fields.
	* Non Unique index is created if $unique is set to FALSE
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
	function createIndexSQL($sql, $fieldDefs, $name, $unique=TRUE){
		$msg = "Error creating index $name on table: ".$this->tableName. ":";
		$this->executeQuery($sql, $msg);
	}
	
	/**
	* This method adds a column to table identified by field def.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
	function addColumnSQL($sql, $fieldDefs){
        if ($this->helper->isFieldArray($fieldDefs)){
		  foreach ($fieldDefs as $fieldDef) $columns[] = $fieldDef['name'];
          $columns = implode(",", $columns); 
         
        } else $columns = $fieldDefs['name'];
          
		$msg = "Error adding column(s) ".$columns." on table: ".$this->tableName. ":";
		$this->executeQuery($sql, $msg);		
	}
	
	/**
	* This method alters old column identified by oldFieldDef to new fieldDef.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
	function alterColumnSQL($sql, $fieldDefs){
        if ($this->helper->isFieldArray($fieldDefs)){
          foreach ($fieldDefs as $fieldDef) $columns[] = $fieldDef['name'];
          $columns = implode(",", $columns); 
         
        } else $columns = $fieldDefs['name'];
          
		$msg = "Error altering column(s) ".$columns." on table: ".$this->tableName. ":";
		$this->executeQuery($sql, $msg);		
	}
	
	/**
	* This method drop a table.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
	function dropTableSQL($sql){
		$msg = "Error dropping table ".$this->tableName. ":";
		$this->executeQuery($sql, $msg);		
	}
	
	/**
	* This method deletes a column(s) identified by fieldDefs.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
	function deleteColumnSQL($sql, $fieldDefs){
		$msg = "Error deleting column(s) ".$columns." on table: ".$this->tableName. ":";
		$this->executeQuery($sql, $msg);		
	}	

	/**
	* Fetches all the rows for a select query. Returns FALSE if query failed and 
	* DB_OK for all other queries
	*/
	function setResult($result){
		if (PEAR::isError($result) === true){
			$this->log->error($msg);
			$result = FALSE;
		} else if ($result != DB_OK){
			// must be a result
            $this->log->fatal("setResult:".$result);
            $row = array();
            $rows = array();
            while (ocifetchinto($result, $row, OCI_ASSOC|OCI_RETURN_NULLS|OCI_RETURN_LOBS)){
                $err = ocierror($result);
                if ($err == false) $rows[] = $row;
                else print_r($err);
            }
			$result = $rows;
		} 
        $this->log->fatal("setResult: returning rows from setResult");
        return $result;
	}
	
	/** Private function to handle most of the sql statements which go as queries
	*/
	function executeLimitQuery($query, $start,$count, $dieOnError=false, $msg=''){
		$result = $this->limitQuery($query,$start,$count, $dieOnError, $msg);
		return $this->setResult($result); 
	}

	/** Priate function to handle most of the sql statements which go as queries
	*/
	function executeQuery($query, $msg, $getRows=false){
		$result = $this->query($query,true,$msg);
        
        if ($getRows) return $this->setResult($result);
        // dd not get rows. Simply go on.
	}

}

?>
