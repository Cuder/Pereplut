<?php
function createDom($xmlPath) {
	$dom = new DOMDocument();
	$dom->preserveWhiteSpace = false;
	$dom->recover = true;
	$dom->load($xmlPath);
	$dom->formatOutput = true;
	return $dom;
}