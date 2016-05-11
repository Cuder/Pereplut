<?php
session_start();
$rootdir = dirname(__FILE__);
$textStrings = simplexml_load_file($rootdir."/view/textStrings.xml");

require_once $rootdir."/config.php";
require_once $rootdir."/core.php";
require_once $rootdir."/libs/Smarty/setup.php";

if (isset($_POST["submit"])) {
	require_once $rootdir."/model/loadFile.php";
	require_once $rootdir."/model/unzipFile.php";
	require_once $rootdir."/model/findNewlines.php";
}
if (!isset($_POST["submit"]) || isset($_SESSION['errorCode'])) {
	if (isset($_SESSION['errorCode'])) echo $_SESSION['errorCode']."<br>";
	require_once $rootdir."/controller/loadFile.php";
} else {
	// A file has been successfully uploaded, displaying the form with settings
}

require_once $rootdir."/controller/main.php";
session_destroy();