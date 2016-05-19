<?php
function createDom($xmlPath) {
	$dom = new DOMDocument();
	$dom->preserveWhiteSpace = false;
	$dom->recover = true;
	try {
		$dom->load($xmlPath);
	} catch (ErrorException $e) {
		repairVariables($xmlPath);
		$dom->load($xmlPath);
	}
	$dom->formatOutput = true;
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

// Find & remove trailing newlines
function trailingNL($projectDir, $delete = false) {
	$topics = getTopics($projectDir);
	$trailingCounter = 0;

	foreach ($topics as $topicPath) {
		$dom = createDom($topicPath);

		$paraCount = ($dom->getElementsByTagName('para')->length) - 1;
		$lastPara = $dom->getElementsByTagName('para')->item($paraCount);
		$body = $lastPara->parentNode;

		if (
			hasChild($lastPara) == false &&
			$lastPara->nodeValue == "" &&
			$lastPara->parentNode->nodeName == "body" &&
			$lastPara->previousSibling->nodeName != "header"
		) {
			$penultimatePara = $lastPara->previousSibling;
			if ($penultimatePara->nodeName == "para") {
				$do = true;
				while ($do == true) {
					if (
						$penultimatePara->nodeName == "para" &&
						hasChild($penultimatePara) == false &&
						$penultimatePara->nodeValue == "" &&
						$penultimatePara->previousSibling->nodeName != "header"
					) {
						$trailingCounter++;
						if ($delete == true) {
							$PreviousPenultimatePara = $penultimatePara->previousSibling;
							$body->removeChild($penultimatePara);
							$penultimatePara = $PreviousPenultimatePara;
						} else {
							$penultimatePara = $penultimatePara->previousSibling;
						}
					} else {
						$do = false;
					}
				}
			}
			if ($delete == true) {
				$body->removeChild($lastPara);
				
				$xml_string = $dom->saveXML();
				$fp = fopen($topicPath, 'w');
				fwrite($fp,$xml_string);
			}
			$trailingCounter++;
		}
	}
	return $trailingCounter;
}

// Get information about ToCs
function getTOCs($projectPath, $projectDir) {
	$tocs = array();
	
	$dom = createDom($projectPath);
	
	$tocsCount = $dom->getElementsByTagNameNS("http://www.w3.org/2001/XInclude","include")->length;
	if (!$tocsCount) {
		$_SESSION['errorCode'] = "noTOCs";
		fallback();
	}
	$xiInclude = $dom->getElementsByTagNameNS("http://www.w3.org/2001/XInclude","include");
	$i = 0;
	foreach ($xiInclude as $toc) {
		$tocs[$i]["path"] = $projectDir."/".$toc->getAttribute("href");
		if (!file_exists($tocs[$i]["path"])) {
			$_SESSION['errorCode'] = "TOCDamage";
			fallback();
		}
		$domToc = createDom($tocs[$i]["path"]);
		$tocs[$i]["title"] = $domToc->getElementsByTagName("map")->item(0)->getAttribute("title");
		$tocs[$i]["file"] = basename($tocs[$i]["path"], "/");
		$i++;
	}
	return $tocs;
}

// Get list of paths to topic files
function getTopics($projectDir) {
	$topicsDir = $projectDir."/Topics/";
	if (!file_exists($topicsDir)) {
		$_SESSION['errorCode'] = "noTopics";
		fallback();
	}
	$topics = glob($topicsDir.'*.xml', GLOB_BRACE);
	return $topics;
}