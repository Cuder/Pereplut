<?php
$topicsDir = $unzipDir."/Topics/";

if (!file_exists($topicsDir)) {
	$_SESSION['errorCode'] = "noTopics";
	fallback();
} else {
	$topics = array_diff(scandir($topicsDir), array('..', '.'));
	$trailingCounter = 0;
	foreach ($topics as $topicName) {
		$topicPath = $topicsDir.$topicName;

		$dom = new DOMDocument();
		$dom->preserveWhiteSpace = false;
		$dom->recover = true;
		$dom->load($topicPath);
		$dom->formatOutput = true;

		$paraCount = ($dom->getElementsByTagName('para')->length) - 1;
		$lastPara = $dom->getElementsByTagName('para')->item($paraCount);

		if (
			$lastPara->hasChildNodes() == null &&
			$lastPara->nodeValue == "" &&
			$lastPara->parentNode->nodeName == "body" &&
			$lastPara->previousSibling->nodeName != "header"
		) {
			if ($lastPara->previousSibling->nodeName == "para") {
				$do = true;
				while ($do == true) {
					if (
						$lastPara->previousSibling->nodeName == "para" &&
						$lastPara->previousSibling->hasChildNodes() == null &&
						$lastPara->previousSibling->nodeValue == "" &&
						$lastPara->previousSibling->nodeName != "header"
					) {
						$lastPara = $lastPara->previousSibling;
						$trailingCounter++;
					} else {
						$do = false;
					}
				}
			}
			$trailingCounter++;
		}
	}
}