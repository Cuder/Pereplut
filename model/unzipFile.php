<?php
$zip = new ZipArchive();

if ($zip->open($target_file)) {
	// Listing all files in archive to find an H&M project
	$foundProject = false;
	for ($i = 0; $i < $zip->numFiles; $i++) {
		if (strpos($zip->getNameIndex($i),".hmxp") !== false) {
			$foundProject = true;
			break;
		}
	}
	if ($foundProject == false) {
		unlink($target_file);
		$_SESSION['errorCode'] = "noHMXP";
		fallback();
	}
	
	$unzipDir = $target_dir.basename($_FILES["fileToUpload"]["name"],".".$fileExtension);
	if (file_exists($unzipDir)) {
		require_once $rootdir."/libs/removeDir.php";
		removeDir($unzipDir);
	}
	mkdir($unzipDir);

	$zip->extractTo($unzipDir);
	$zip->close();
	unlink($target_file);
} else {
	$_SESSION['errorCode'] = "unzip";
	fallback();
}