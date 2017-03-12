<?php
if( !defined('ROUD_INC_PATH') ) {
  define('ROUD_INC_PATH' , dirname(__FILE__));
}
require_once(ROUD_INC_PATH . '/module/Controller.php');
require_once(ROUD_INC_PATH . '/module/Model.php');
require_once(ROUD_INC_PATH . '/Helper.php');


if( !function_exists('component') ) {
  function component( $name )
  {
    return request_module( $name, 'controller' );
  }
}

if( !function_exists('h') ) {
  function h( $str )
  {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
  }
}

if( !function_exists('request_module') ) {
  function request_module( $filename, $module_type, $rename = 'nfm', $namespace = 'Roud\\' )
  {
    if( !is_file( $module = ROUD_INC_PATH . '/module/'. $module_type .'s/' . $filename . '.php' ) )
      return false;

    require_once($module);

    $request_class = '';

    preg_match_all( '/[nfm]/', $rename, $m );

    foreach( $m[0] as $v ) {
      switch( $v ) {
        case 'n':
          $request_class .= $namespace;
          break;
        case 'f':
          $request_class .= pascalize($filename);
          break;
        case 'm':
          $request_class .= pascalize($module_type);
          break;
      }
    }

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
