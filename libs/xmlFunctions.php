<?php
function createDom($xmlPath) {
	$dom = new DOMDocument();
	//$dom->preserveWhiteSpace = false;
	//$dom->recover = true;
	try {
		$dom->load($xmlPath);
	} catch (ErrorException $e) {
		repairVariables($xmlPath);
		$dom->load($xmlPath);
	}
	//$dom->formatOutput = true;
	return $dom;
}

function hasChild($hc) {
	if (($hc != null) && $hc->hasChildNodes()) {
		foreach ($hc->childNodes as $c) {
			if ($c->nodeType == XML_ELEMENT_NODE) return true;
		}
	}
	return false;
}

// Replacing <%VARIABLE_NAME%> with &lt;%VARIABLE_NAME%&gt;
function repairVariables($path2XML) {
	$file_contents = file_get_contents($path2XML);
	$file_contents = str_replace("<%","&lt;%", $file_contents);
	$file_contents = str_replace("%>","%&gt;", $file_contents);
	file_put_contents($path2XML, $file_contents);
}