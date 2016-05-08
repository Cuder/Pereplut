<?php
// Load Smarty library
require('Smarty.class.php');

class Smarty_pereplut extends Smarty {
	function __construct()
	{
		$rootdir = dirname(dirname(dirname(__FILE__)));
		parent::__construct();
		$this->setTemplateDir($rootdir.'/view/templates/');
		$this->setCompileDir($rootdir.'/view/templates_c/');
		$this->setConfigDir($rootdir.'/view/configs/');
		$this->setCacheDir($rootdir.'/view/cache/');
		$this->caching = Smarty::CACHING_LIFETIME_CURRENT;
   }
}
$smarty = new Smarty_pereplut();
