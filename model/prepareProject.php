<?php
$projectFile = $projectDir."/project.hmxp";

if (file_exists($projectFile)) {
	$zipExtension = ".hmxz";
} else {
	$zipExtension = ".zip";
	foreach (glob($projectDir."/*.hmxp") as $projectFile) { break; }
}

// Getting project title for the filename
$dom = createDom($projectFile);
$xpath = new DomXpath($dom);
$fileName = $xpath->query('//config-value[@name="title"]')->item(0);

if ($fileName) {
	// Removing unallowed characters from the filename (Windows)
	$fileName = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $fileName->nodeValue);
	$fileName = mb_ereg_replace("([\.]{2,})", '', $fileName);
} else {
	$fileName = "Project";
}

ZipProject($projectDir, $projectDir."/".$fileName.$zipExtension);

// Setting headers for download
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"".$fileName.$zipExtension."\"");
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".filesize($projectDir."/".$fileName.$zipExtension));
ob_end_flush();
@readfile($projectDir."/".$fileName.$zipExtension);

ignore_user_abort(true);
require_once $rootdir."/libs/removeDir.php";
removeDir($projectDir);

function ZipProject($source, $destination) {
	$zip = new ZipArchive();
	if (!$zip->open($destination, ZipArchive::CREATE)) {
		return false;
	}
	$source = str_replace('\\', '/', realpath($source));
	if (is_dir($source) === true) {
		$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
		foreach ($files as $file) {
			$file = str_replace('\\', '/', $file);
			// Ignore "." and ".." folders
			if (in_array(substr($file, strrpos($file, '/') + 1), array('.', '..'))) continue;
			$file = realpath($file);
			if (is_dir($file) === true) {
				$zip->addEmptyDir(str_replace($source.'/', '', $file.'/'));
			} else if (is_file($file) === true) {
				$zip->addFromString(str_replace($source.'/', '', $file), file_get_contents($file));
			}
		}
	} else if (is_file($source) === true) {
		$zip->addFromString(basename($source), file_get_contents($source));
	}
	return $zip->close();
}