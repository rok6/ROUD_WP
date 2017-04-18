<?php

$wprd = null;

if( !defined('ROUD_INC_PATH') ) {
	define('ROUD_INC_PATH' , dirname(__FILE__) . '/includes');
}
if( !defined('ROUD_MDLS_PATH') ) {
	define('ROUD_MDLS_PATH' , dirname(__FILE__) . '/modules');
}

add_action('after_setup_theme', function () {
	require_once dirname(__FILE__) . '/includes/Functions.php';
	require_once dirname(__FILE__) . '/includes/wprd.php';
}, 0);

add_action('after_setup_theme', function () {
	$wprd = new WPRD();
	require_once dirname(__FILE__) . '/config.php';
}, 9999);
