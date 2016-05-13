<?php
$projectPath = $unzipDir."/".$projectName;

if (!file_exists($projectPath)) {
	$_SESSION['errorCode'] = "noHMXP";
	fallback();
} else {
	$dom = createDom($projectPath);

	// Getting list of Table of Contents
	$tocsCount = $dom->getElementsByTagNameNS("http://www.w3.org/2001/XInclude","include")->length;
	if (!$tocsCount) {
		$_SESSION['errorCode'] = "noTOCs";
		fallback();
	}
	$xiInclude = $dom->getElementsByTagNameNS("http://www.w3.org/2001/XInclude","include");
	$i = 0;
	foreach ($xiInclude as $toc) {
		$tocs[$i]["path"] = $unzipDir."/".$toc->getAttribute("href");
		if (!file_exists($tocs[$i]["path"])) {
			$_SESSION['errorCode'] = "TOCDamage";
			fallback();
		}
		$domToc = createDom($tocs[$i]["path"]);
		$tocs[$i]["title"] = $domToc->getElementsByTagName("map")->item(0)->getAttribute("title");
		$i++;
	}
	
	// Getting custom builds
	$xpath = new DomXpath($dom);
	$i = 0;
	$customBuilds = null;
	foreach ($xpath->query('//config-group[@name="custombuilds"][1]')->item(0)->childNodes as $childNode) {
		if ($childNode->nodeType == XML_ELEMENT_NODE) {
			$customBuilds[$i] = $childNode->nodeValue;
			$i++;
		}
	}
}