<?php
function copy_recursive( $source, $dest ){
    if( is_file( $source ) ){
        return( copy( $source, $dest ) );
    }
    if( !is_dir($dest) ){
        mkdir( $dest );
    }

    $status = true;

    $d = dir( $source );
    while( $f = $d->read() ){
        if( $f == "." || $f == ".." ){
            continue;
        }
        $status &= copy_recursive( "$source/$f", "$dest/$f" );
    }
    $d->close();
    return( $status );
}

function mkdir_recursive( $path ){
    if( is_dir( $path ) ){
        return( true );
    }
    if( is_file( $path ) ){
        print( "ERROR: mkdir_recursive(): argument $path is already a file.\n" );
        return( false );
    }
    $parent_dir = substr( $path, 0, strrpos( $path, "/" ) );
    if( mkdir_recursive( $parent_dir ) ){
        return( mkdir( $path ) );
    }
    return( false );
}

function rmdir_recursive( $path ){
    if( is_file( $path ) ){
        return( unlink( $path ) );
    }
    if( !is_dir( $path ) ){
        print( "ERROR: rmdir_recursive(): argument $path is not a file or a dir.\n" );
        return( false );
    }

    $status = true;

    $d = dir( $path );
    while( $f = $d->read() ){
        if( $f == "." || $f == ".." ){
            continue;
        }
        $status &= rmdir_recursive( "$path/$f" );
    }
    $d->close();
    rmdir( $path );
    return( $status );
}

function findTextFiles( $the_dir, $the_array ){
    $d = dir( $the_dir );
    while( $f = $d->read() ){
        if( $f == "." || $f == ".." ){
            continue;
        }
        if( is_dir( "$the_dir/$f" ) ){
            // i think depth first is ok, given our cvs repo structure -Bob.
            $the_array = findTextFiles( "$the_dir/$f", $the_array );
        }
        else {
            switch( mime_content_type( "$the_dir/$f" ) ){
                // we take action on these cases
                case "text/html":
                case "text/plain":
                    array_push( $the_array, "$the_dir/$f" );
                    break;
                // we consciously skip these types
                case "application/pdf":
                case "application/x-zip":
                case "image/gif":
                case "image/jpeg":
                case "image/png":
                case "text/rtf":
                    break;
                default:
                    debug( "no type handler for $the_dir/$f with mime_content_type: " . mime_content_type( "$the_dir/$f" ) . "\n" );
            }
        }
    }
    return( $the_array );
}

function findAllFiles( $the_dir, $the_array ){
    $d = dir( $the_dir );
    while( $f = $d->read() ){
        if( $f == "." || $f == ".." ){
            continue;
        }
        if( is_dir( "$the_dir/$f" ) ){
            // i think depth first is ok, given our cvs repo structure -Bob.
            $the_array = findAllFiles( "$the_dir/$f", $the_array );
        }
        else {
            array_push( $the_array, "$the_dir/$f" );
        }
    }
    return( $the_array );
}
?>
