<?php
// Setting debug level
if ($debugging == true) {
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
}
// If register_globals is turned off, extract super globals (php 4.2.0+)
if (ini_get('register_globals') != 1) {
	$supers = array("_REQUEST", "_ENV", "_SERVER", "_POST", "_GET", "_COOKIE", "_SESSION", "_FILES", "_GLOBALS");
	foreach ($supers as $__s) {
		if ((isset($$__s) == true) && (is_array($$__s) == true)) extract($$__s, EXTR_OVERWRITE);
	}
	unset($supers);
}
// Prevent any possible XSS attacks via $_GET.
if (stripget($_GET)) {
	$_SESSION['errorCode'] = "xss";
	fallback();
}
function stripget($check_url)
{
	$return = false;
	if (is_array($check_url)) {
		foreach ($check_url as $value) {
			if (stripget($value) == true) {
				return true;
			}
		}
	} else {
		$check_url = str_replace(array("\"", "\'"), array("", ""), urldecode($check_url));
		if (preg_match("/<[^<>]+>/i", $check_url)) {
			return true;
		}
	}
	return $return;
}

// Fallback to safe area in event of unauthorised access
function fallback($location = "")
{
	header("Location: http://".$_SERVER['SERVER_NAME']."/".$location);
	exit;
}

// Setting custom error handler to catch warnings
set_error_handler(function($errno, $errstr, $errfile, $errline, array $errcontext) {
	// error was suppressed with the @-operator
	if (0 === error_reporting()) {
		return false;
	}
	throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

// Generating random session identifier
function generateSession() {
	$session = strtolower(substr(base64_encode(time()),0,-2).base64_encode($_SERVER['REMOTE_ADDR'])).rand(10,99);
	return $session;
}