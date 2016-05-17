<?php
$reservedBuilds = array(
	"ALL",
	"CHM",
	"HXS",
	"HTML",
	"PDF",
	"PRINT",
	"EBOOK"
);

foreach ($_POST["renameBuild"] as $oldName => $newName) {
	$newName = strtoupper($newName);
	if ($newName) {
		if (in_array($newName, $reservedBuilds)) {
			$_SESSION['errorCode'] = "reservedBuildName";
			fallback();
		}
		if (!preg_match('/[^A-Z0-9]/u',$newName) != 1) {
			$_SESSION['errorCode'] = "unallowedBuildName";
			fallback();
		}
		if (strlen($newName) > $maxBuildNameLength) {
			$_SESSION['errorCode'] = "BuildNameLength";
			fallback();
		}
		$_POST["renameBuild"][$oldName] = $newName;
	} else {
		unset($_POST["renameBuild"][$oldName]);
	}
}

// Getting project file path
if (!file_exists($projectDir."/project.hmxp")) {
	foreach (glob($projectDir."/*.hmxp") as $projectPath) { break; }
}

$dom = createDom($projectPath);
$xpath = new DomXpath($dom);

$changedProject = false;

for ($i=1; $i<7; $i++) {
	foreach ($xpath->query('//*[@name="make'.$i.'include"]') as $node) {
		$builds = explode(",", $node->nodeValue);
		$changed = false;
		foreach ($_POST["renameBuild"] as $oldName => $newName) {
			if (in_array($oldName, $builds)) {
				$key = array_search($oldName, $builds);
				$builds[$key] = $newName;
				$changed = true;
			}
		}
		if ($changed == true) {
			$node->nodeValue = implode(",", $builds);
			$changedProject = true;
		}
	}
}

foreach ($_POST["renameBuild"] as $oldName => $newName) {
	foreach ($xpath->query('//config-group[@name="custombuilds"][1]')->item(0)->childNodes as $childNode) {
		if ($childNode->nodeType == XML_ELEMENT_NODE && $childNode->getAttribute("name") == $oldName) {
			$childNode->setAttribute("name", $newName);
			$changedProject = true;
		}
	}
}

$fileMods = $dom->getElementsByTagName("file-mod");
foreach ($fileMods as $fileMod) {
	$builds = $fileMod->getAttribute("build");
	$builds = explode(",", $builds);
	$changed = false;
	foreach ($_POST["renameBuild"] as $oldName => $newName) {
		if (in_array($oldName, $builds)) {
			$key = array_search($oldName, $builds);
			$builds[$key] = $newName;
			$changed = true;
		}
	}
	if ($changed == true) {
		$builds = implode(",", $builds);
		$fileMod->setAttribute("build", $builds);
		$changedProject = true;
	}
}

if ($changedProject == true) {
	$xml_string = $dom->saveXML();
	$fp = fopen($projectPath, 'w');
	fwrite($fp, $xml_string);
}

// Getting list of Table of Contents
$tocs = getTOCs($projectPath, $projectDir);

// Renaming builds in Tables of Contents
foreach ($tocs as $toc) {
	$dom = createDom($toc["path"]);

	$topicrefs = $dom->getElementsByTagName("topicref");
	$changedToc = false;

	foreach ($topicrefs as $topicref) {
		$topicrefBuilds = $topicref->getAttribute("build");
		if ($topicrefBuilds && $topicrefBuilds != "ALL") {
			$topicrefBuilds = explode(",", $topicrefBuilds);
			$changed = false;
			foreach ($_POST["renameBuild"] as $oldName => $newName) {
				if (in_array($oldName, $topicrefBuilds)) {
					$key = array_search($oldName, $topicrefBuilds);
					$topicrefBuilds[$key] = $newName;
					$changed = true;
				}
			}
			if ($changed == true) {
				$topicrefBuildsChanged = implode(",", $topicrefBuilds);
				$topicref->setAttribute("build", $topicrefBuildsChanged);
				$changedToc = true;
			}
		}
	}

	if ($changedToc == true) {
		$xml_string = $dom->saveXML();
		$fp = fopen($toc["path"], 'w');
		fwrite($fp, $xml_string);
	}
}

// Renaming builds in topics
$topicsDir = $projectDir."/Topics/";

if (!file_exists($topicsDir)) {
	$_SESSION['errorCode'] = "noTopics";
	fallback();
}

$topics = array_diff(scandir($topicsDir), array('..', '.'));

foreach ($topics as $topicName) {
	$changedTopic = false;

	$topicPath = $topicsDir.$topicName;
	$dom = createDom($topicPath);
	$xpath = new DomXpath($dom);
	foreach ($xpath->query('//conditional-text[@type="IF"]') as $condition) {
		$builds = $condition->getAttribute("value");
		$builds = explode(",", $builds);
		$changed = false;
		foreach ($_POST["renameBuild"] as $oldName => $newName) {
			if (in_array($oldName, $builds)) {
				$key = array_search($oldName, $builds);
				$builds[$key] = $newName;
				$changed = true;
			}
		}
		if ($changed == true) {
			$builds = implode(",", $builds);
			$condition->setAttribute("value", $builds);
			$changedTopic = true;
		}
	}
	if ($changedTopic == true) {
		$xml_string = $dom->saveXML();
		$fp = fopen($topicPath, 'w');
		fwrite($fp,$xml_string);
	}
}