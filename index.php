<?php
session_start();
$rootdir = dirname(__FILE__);
$textStrings = simplexml_load_file($rootdir."/view/textStrings.xml");

require_once $rootdir."/config.php";
require_once $rootdir."/core.php";
require_once $rootdir."/libs/Smarty/setup.php";

if (isset($_POST["upload"])) {
	require_once $rootdir."/model/loadFile.php";
	require_once $rootdir."/model/unzipFile.php";
	require_once $rootdir."/model/getProjectInfo.php";
} elseif (isset($_POST["process"])) {
	if (isset($process) && $process != "") {
		$projectDir = $rootdir."/projects/".$process;
		if (!file_exists($projectDir)) {
			$_SESSION['errorCode'] = "session";
			fallback();
		}
		require_once $rootdir."/libs/xmlFunctions.php";
		
		// Processing according to defined settings...
		if (isset($_POST["trailing"])) {
			require_once $rootdir."/model/removeNewlines.php";
		}
		// Deleting a build from ToC
		if (isset($_POST["removeBuildFromToc"])) {
			require_once $rootdir."/model/removeBuildFromToc.php";
		}
		// Archive a project, return it to the user and delete the temp directory
		require_once $rootdir."/model/prepareProject.php";
		#fallback();
	} else {
		fallback();
	}
}
if ((!isset($_POST["upload"]) && !isset($_POST["process"])) || isset($_SESSION['errorCode'])) {
	if (isset($_SESSION['errorCode'])) {
		echo $_SESSION['errorCode']."<br>";
	} elseif (isset($upload) && empty($_FILES) && empty($_POST) && isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
		echo "post_max_size exceeded<br>";
	}
	require_once $rootdir."/controller/loadFile.php";
} else {
	// A file has been successfully uploaded, displaying the form with settings
	// Delete !isset($_POST["process"]) and uncomment fallback above when done...
	if (!isset($_POST["process"])) require_once $rootdir."/controller/settings.php";
}

require_once $rootdir."/controller/main.php";
session_destroy();