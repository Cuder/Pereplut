<?php
$topicsDir = $projectDir."/Topics/";

if (!file_exists($topicsDir)) {
	$_SESSION['errorCode'] = "noTopics";
	fallback();
}

trailingNL($topicsDir, $delete = true);