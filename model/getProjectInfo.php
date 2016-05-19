<?php
require_once $rootdir."/libs/xmlFunctions.php";

$trailingCounter = trailingNL($target_dir);

$projectPath = $target_dir."/".$projectName;

if (!file_exists($projectPath)) {
	$_SESSION['errorCode'] = "noHMXP";
	fallback();
} else {
	// Getting list of Table of Contents
	$tocs = getTOCs($projectPath, $target_dir);
	
	// Getting custom builds
	$dom = createDom($projectPath);
	$xpath = new DomXpath($dom);
	$i = 0;
	$customBuilds = null;
	foreach ($xpath->query('//config-group[@name="custombuilds"][1]')->item(0)->childNodes as $childNode) {
		if ($childNode->nodeType == XML_ELEMENT_NODE) {
			$customBuilds[$i] = $childNode->getAttribute("name");
			$i++;
		}
	}
}