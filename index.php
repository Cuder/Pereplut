<?php
$rootdir = dirname(__FILE__);
$textStrings = simplexml_load_file($rootdir."/view/textStrings.xml");

require_once $rootdir."/config.php";
require_once $rootdir."/core.php";
require_once $rootdir."/libs/Smarty/setup.php";

require_once $rootdir."/controller/main.php";