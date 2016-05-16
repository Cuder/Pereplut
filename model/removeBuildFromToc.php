<?php
$removeBuildFromToc = array();

foreach ($_POST["removeBuildFromToc"] as $key => $removeItem) {
	if (strpos($removeItem, "/") !== false) {
		$explode = explode("/", $removeItem);
		$build = $explode[0];
		$toc = $explode[1];
		if (!isset($removeBuildFromToc[$toc])) $removeBuildFromToc[$toc] = array();
		array_push($removeBuildFromToc[$toc], $build);
	} else {
		if (!isset($removeBuildFromToc["table_of_contents.xml"])) $removeBuildFromToc["table_of_contents.xml"] = array();
		array_push($removeBuildFromToc["table_of_contents.xml"], $key);
	}
}

foreach ($removeBuildFromToc as $toc => $builds) {

	$tocPath = $projectDir."/Maps/".$toc;
	$dom = createDom($tocPath);
	$topicrefs = $dom->getElementsByTagName("topicref");
	$changedToc = false;

	foreach ($topicrefs as $topicref) {
		$topicrefBuilds = $topicref->getAttribute("build");
		if ($topicrefBuilds && $topicrefBuilds != "ALL") {
			$topicrefBuilds = explode(",", $topicrefBuilds);
			$topicrefBuildsChanged = array_diff($topicrefBuilds, $removeBuildFromToc[$toc]);
			if (!empty($topicrefBuildsChanged)) {
				if ($topicrefBuildsChanged != $topicrefBuilds) {
					$topicrefBuildsChanged = implode(",", $topicrefBuildsChanged);
					$topicref->setAttribute("build", $topicrefBuildsChanged);
					$changedToc = true;
				}
			} else {
				if ($_POST["removeBuildFromTocMethod"] == "none") {
					$topicref->removeAttribute("build");
				} elseif ($_POST["removeBuildFromTocMethod"] == "all") {
					$topicref->setAttribute("build", "ALL");
				}
				$changedToc = true;
			}
		}
	}

	if ($changedToc == true) {
		$xml_string = $dom->saveXML();
		$fp = fopen($tocPath, 'w');
		fwrite($fp, $xml_string);
	}
}