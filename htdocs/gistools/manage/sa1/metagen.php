<?php
/*
 * Created on Mar 17, 2005
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

include_once('config.php');
require_once('include/logging.php'); 
include_once('include/database/PearDatabase.php');

function process_columns($rows)
{
   foreach ($rows as $row){
      $name = $row['Field'];
      $type = $row['Type'];
      $type = str_replace(")", "", $type);
      $type = explode("(", $type);
      
      $column = array("name" => $name, "type" => $type[0], "len" => "", 'default' => $row['Default']);
      if (sizeof($type) == 2) $column["len"] = $type[1];
      $cols[] = $column;
   }   
   
   return $cols;
}

function process_index($rows)
{
   $indices = array();
   foreach ($rows as $row)
   {
      $indices[$row['Key_name']]['Non_unique'] = $row['Non_unique'];
      $indices[$row['Key_name']]['cols'][$row['Seq_in_index']] = $row['Column_name'];      
   }    
   
   return $indices;
}

function print_to_file($table_array)
{
    $table = $table_array['table'];
    $object = $table;
   $fp = fopen("metadata/$object"."MetaData.php", "w");
   
   fwrite($fp, "<?php\n");
   fwrite($fp, "$"."dictionary['$object'] = array ( 'table' => '$table'\n");

   $separator = "";
   fwrite($fp, "                                  , 'fields' => array (\n");   
   foreach ($table_array['columns'] as $colArray)
   {
      $name = $colArray['name'];
      $len = $colArray['len'];
      $type = $colArray['type'];
      $default = $colArray['default'];
      
      fwrite($fp, "      $separator array('name' =>'$name', 'type' =>'$type', 'len'=>'$len', 'default'=>'$default')\n");
      $separator = ",";
   }
   fwrite($fp, "                                                      )");
   
   $separator = "";
   fwrite($fp, "                                  , 'indices' => array (\n");   
   foreach ($table_array['index'] as $name => $colArray)
   {
      if ($name == 'PRIMARY') {
         $name = $table."pk";
         $type = "primary";
      } else {
         $type = ($colArray['Non_unique'] == 1) ? "index" : "unique";         
      }
      $cols = "array('".implode("','", $colArray['cols'])."')";
      
      fwrite($fp, "      $separator array('name' =>'$name', 'type' =>'$type', 'fields'=>$cols)\n");
      $separator = ",";
   }
   fwrite($fp, "                                                      )\n");
   
   fwrite($fp, "                                  )\n");
   fwrite($fp, "?>\n");
   fclose($fp);    
}

$pdb = new PearDatabase();

$database =@mysqli_pconnect($pdb->dbHostName,$pdb->userName,$pdb->userPassword);
            @mysqli_select_db($pdb->dbName) or die( "Unable to select database");               

$result = mysqli_list_tables($pdb->dbName);

if (!$result) {
   echo "DB Error, could not list tables\n";
   echo 'MySQL Error: ' . mysqli_error($varconnect);
   exit;
}

$dictionary = array();
$show = array("columns" => "process_columns", "index" => "process_index");
while ($row = mysqli_fetch_row($result)) {
    $table = $row[0];
    $dictionary[] = $table;
    $table_array = array();
    $table_array['table'] = $table;
    foreach ($show as $col => $fn)
    {
        $rows = array();
        $query = "show $col from $table";
        $cols = mysqli_query($varconnect, $query);
       // Check result
        // This shows the actual query sent to MySQL, and the error. Useful for debugging.
        if (!$cols) {
           $message  = 'Invalid query: ' . mysqli_error($varconnect) . "\n";
           $message .= 'Whole query: ' . $query;
           die($message);
        }
       
        // Use result
        // Attempting to print $result won't allow access to information in the resource
        // One of the mysql result functions must be used
        // See also mysqli_result(), mysqli_fetch_array(), mysqli_fetch_row(), etc.
        while ($row = mysqli_fetch_assoc($cols)) {
           $rows[] = $row;
        }        
        
        $table_array[$col] = $fn($rows);
    }
    
    print_to_file($table_array);
    echo "Processed $table<br>\n";
}

// write the dictionary to the main directory
$fp = fopen("dictionary.php", "w");
fwrite($fp, "<?php\n");
foreach ($dictionary as $table) fwrite($fp, "include_once ('metadata/$table"."MetaData.php');\n");
fwrite($fp, "?>\n");
fclose($fp);

echo "Processed dictionary";

mysqli_free_result($result);

?>
