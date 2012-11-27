<?php
	header('Content-Type: text/cache-manifest');
	echo "CACHE MINIFEST\n";
	$dir = new RecursiveDirectoryIterator('.');

	foreach (new RecursiveDirectoryIterator($dir) as $file) {
		
		if($file->IsFile() && $file != "./finetv-manifest.php" && substr($file->getFilename(), 0, 1) != ".")
		{
			echo $file . "\n";
		}
	}

?>