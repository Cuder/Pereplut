<?php
$target_dir = $rootdir."/projects/";
$target_file = $target_dir.basename($_FILES["fileToUpload"]["name"]);

// Checking if file already exists
if (file_exists($target_file)) {
	$_SESSION['errorCode'] = "fileExists";
	fallback();
}

// Checking mime type
if (mime_content_type($_FILES["fileToUpload"]["tmp_name"]) != "application/zip") {
	$_SESSION['errorCode'] = "fileMime";
	fallback();
}

// Checking file extension
$fileExtension = pathinfo($target_file, PATHINFO_EXTENSION);
if (strtolower($fileExtension) != "hmxz" && strtolower($fileExtension) != "zip") {
	$_SESSION['errorCode'] = "fileExtension";
	fallback();
}

// Checking file size
if ($_FILES["fileToUpload"]["size"] > $maxFileSize) {
	$_SESSION['errorCode'] = "fileMax";
	fallback();
}

// The file is OK, uploading...
if (!move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
	$_SESSION['errorCode'] = "fileError";
	fallback();
}