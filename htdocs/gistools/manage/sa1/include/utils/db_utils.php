<?PHP

function db_convert($string, $type){
	global $sugar_config;
	if($sugar_config['dbconfig']['db_type']== "mysql"){
		switch($type){
			case 'today': return "CURDATE()";	
		}
		return "'$string'";
	}else if($sugar_config['dbconfig']['db_type']== "oci8"){








	}
	return "'$string'";
	
}

function from_db_convert($string, $type){

	global $sugar_config;
	if($sugar_config['dbconfig']['db_type']== "mysql"){
		return $string;
	}else if($sugar_config['dbconfig']['db_type']== "oci8"){






	}
	return $string;
	
	
}

$toHTML = array(
	'"' => '&quot;',
	'<' => '&lt;',
	'>' => '&gt;',
	'& ' => '&amp; ',
	"'" =>  '&#039;',

);

function to_html($string, $encode=true){
	global $toHTML;
	
	if($encode && is_string($string)){//$string = htmlentities($string, ENT_QUOTES);
	$string = str_replace(array_keys($toHTML), array_values($toHTML), $string);
	}
	return $string;
}


function from_html($string, $encode=true){
	global $toHTML;
	//if($encode && is_string($string))$string = html_entity_decode($string, ENT_QUOTES);
	if($encode && is_string($string)){
		$string = str_replace(array_values($toHTML), array_keys($toHTML), $string);
	}
	return $string;
}

function run_sql_file( $filename ){
    if( !is_file( $filename ) ){
        print( "Could not find file: $filename <br>" );
        return( false );
    }

    require_once('include/database/PearDatabase.php');

    $fh         = fopen( $filename,'r' );
    $contents   = fread( $fh, filesize($filename) );
    fclose( $fh );

    $lastsemi   = strrpos( $contents, ';') ;
    $contents   = substr( $contents, 0, $lastsemi );
    $queries    = split( ';', $contents );
    $db         = new PearDatabase();

    foreach( $queries as $query ){
        if( !empty($query) ){
            print( "Sending query: $query ;<br>" );
            $db->query( $query.';', true, "An error has occured while running.<br>" );
        }
    }
}
?>
