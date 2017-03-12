<?php

add_action('after_setup_theme', function () {
  require_once dirname(__FILE__) . '/includes/Functions.php';
  require_once dirname(__FILE__) . '/includes/Roud.php';
  require_once dirname(__FILE__) . '/config.php';
}, 0);

add_action('after_setup_theme', function () {
  $Roud = new Roud();
  $Roud->custom_post->init();
}, 9999);
