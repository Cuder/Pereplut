<?php
$zip = new ZipArchive();

if ($zip->open($target_file)) {

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