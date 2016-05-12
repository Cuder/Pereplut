<?php
function createDom($xmlPath) {
	$dom = new DOMDocument();
	//$dom->preserveWhiteSpace = false;
	//$dom->recover = true;
	$dom->load($xmlPath);
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