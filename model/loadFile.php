<?php
// Deleting obsolete & forgotten directories with projects
require_once $rootdir."/libs/removeDir.php";
$dirs = array_filter(glob($rootdir.'/projects/*'), 'is_dir');
foreach ($dirs as $dir) {
	if ((time()-stat($dir)['mtime']) > $projectStorageTime) {
		removeDir($dir);
	}
}

$sessionId = generateSession();
$target_dir = $rootdir."/projects/".$sessionId."/";
mkdir($target_dir);
$target_file = $target_dir.basename($_FILES["fileToUpload"]["name"]);

// Checking file size
if (!is_uploaded_file($_FILES["fileToUpload"]["tmp_name"])) {
	$_SESSION['errorCode'] = "fileMax";
	fallback();
}

// Checking if file already exists
if (file_exists($target_file)) {
	$_SESSION['errorCode'] = "fileExists";
	fallback();
}

// Checking mime type
$finfo = new finfo(FILEINFO_MIME);
$mimeType = $finfo->file($_FILES["fileToUpload"]["tmp_name"]);
if (!isset($mimeType) || $mimeType != "application/zip; charset=binary") {
	$_SESSION['errorCode'] = "fileMime";
	fallback();
}

// Checking file extension
$fileExtension = pathinfo($target_file, PATHINFO_EXTENSION);
if (strtolower($fileExtension) != "hmxz" && strtolower($fileExtension) != "zip") {
	$_SESSION['errorCode'] = "fileExtension";
	fallback();
}

// The file is OK, uploading...
if (!move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
	$_SESSION['errorCode'] = "fileError";
	fallback();
}