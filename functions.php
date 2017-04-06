<?php

if( !defined('ROUD_INC_PATH') ) {
  define('ROUD_INC_PATH' , dirname(__FILE__) . '/includes');
}
if( !defined('ROUD_MDLS_PATH') ) {
  define('ROUD_MDLS_PATH' , dirname(__FILE__) . '/modules');
}

add_action('after_setup_theme', function () {
  require_once dirname(__FILE__) . '/includes/Functions.php';
  require_once dirname(__FILE__) . '/includes/Roud.php';
  require_once dirname(__FILE__) . '/config.php';
}, 0);

add_action('after_setup_theme', function () {
  $Roud = new Roud();
  $Roud->cmb2->init();
  $Roud->custom_post->add(['news', 'illurweb']);
}, 9999);

// ローカルでのメール送信アクション様
add_filter('wp_mail_from', function() {
	return 'wordpress@example.com';
});
