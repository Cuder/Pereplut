<?php
$projectPath = $unzipDir."/".$projectName;

if (!file_exists($projectPath)) {
	$_SESSION['errorCode'] = "noHMXP";
	fallback();
} else {
	$dom = createDom($projectPath);
	$xpath = new DomXpath($dom);
	$i = 0;
	foreach ($xpath->query('//config-group[@name="custombuilds"][1]')->item(0)->childNodes as $childNode) {
		$customBuilds[$i] = $childNode->nodeValue;
		$i++;
	}
}