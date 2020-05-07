<?PHP
function create_cache_directory($file){
			$paths = explode('/',$file);
			$dir = 'cache';
			if(!file_exists($dir))
					mkdir($dir, 0755);
			for($i = 0; $i < sizeof($paths) - 1; $i++){
				$dir .= '/' . $paths[$i];
				if(!file_exists($dir))
					mkdir($dir, 0755);
			}
			return $dir . '/'. $paths[sizeof($paths) - 1];
}


?>
