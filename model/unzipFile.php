<?php
$zip = new ZipArchive();

if ($zip->open($target_file)) {
	// Listing all files in archive to find an H&M project
	$foundProject = false;
	for ($i = 0; $i < $zip->numFiles; $i++) {
		if (endsWith($zip->getNameIndex($i), ".hmxp")) {
			$projectName = $zip->getNameIndex($i);
			$foundProject = true;
			break;
		}
	}
	if ($foundProject == false) {
		unlink($target_file);
		rmdir($target_dir);
		$_SESSION['errorCode'] = "noHMXP";
		fallback();
	}
	$zip->extractTo($target_dir);
	$zip->close();
	unlink($target_file);
} else {
	$_SESSION['errorCode'] = "unzip";
	fallback();
}

function endsWith($haystack, $needle) {
	$length = strlen($needle);
	if ($length == 0) return true;
	return (substr($haystack, -$length) === $needle);
}