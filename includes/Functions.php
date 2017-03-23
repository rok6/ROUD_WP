<?php
if( !defined('ROUD_INC_PATH') ) {
  define('ROUD_INC_PATH' , dirname(__FILE__));
}
if( !defined('ROUD_MDLS_PATH') ) {
  define('ROUD_MDLS_PATH' , get_template_directory() . '/modules');
}
require_once(ROUD_MDLS_PATH . '/controller/Controller.php');
require_once(ROUD_MDLS_PATH . '/model/Model.php');
require_once(ROUD_INC_PATH . '/Helper.php');


 /**
  * MVC START
  *=====================================================*/
if( !function_exists('component') ) {
  function component( $name = '' )
  {
    return request_module( $name, 'controller' );
  }
}


 /**
  * Utls
  *=====================================================*/
if( !function_exists('h') ) {
  function h( $str )
  {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
  }
}

if( !function_exists('request_module') ) {
  function request_module( $filename = '', $module_type, $namespace = 'Roud\\module\\' )
  {
    if( $filename === '' ) {
      $filename = get_post_type();
    }

    $filename = pascalize($filename) . pascalize($module_type);
    if( !is_file( $module = ROUD_MDLS_PATH . '/'. $module_type .'/' . $filename . '.php' ) )
      return false;

    require_once($module);

    $request_class = $namespace . $module_type . '\\' . $filename;
    return new $request_class();
  }
}

if( !function_exists('pascalize') ) {
  function pascalize( $str, $d = '._ -' )
  {
    return preg_replace("/[$d]/", '', ucwords(strtolower($str), $d) );
  }
}

if( !function_exists('set_option') ) {
  function set_option( $key, $value, $add = false )
  {
    if( get_option($key) !== false ) {
      update_option($key, $value);
    }
    else if( $add ) {
      add_option($key, $value, null, 'no');
    }
  }
}

function _dump($str){
  echo '<pre>';
  var_dump($str);
  echo '</pre>';
}
