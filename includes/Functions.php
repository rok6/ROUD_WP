<?php
require_once(ROUD_MDLS_PATH . '/controller/Controller.php');
require_once(ROUD_MDLS_PATH . '/model/Model.php');
require_once(ROUD_INC_PATH . '/Helper.php');


 /**
	* MVC START
	*=====================================================*/
function component( $name = '', $render_type = '' )
{
	return request_module( 'controller', $render_type, array(
		'filename'	=> $name,
	));
}


 /**
	* Request Module
	*=====================================================*/
function request_module( $module_type, $render_type, array $args = array() )
{
	$args = $args += array(
		'filename'	=> '',
		'namespace' => 'Roud\\module\\',
	);

	if( (string)$args['filename'] === '' ) {
		$args['filename'] = 'default';
	}

	$module_path = ROUD_MDLS_PATH . '/'. $module_type .'/';
	$args['filename'] = pascalize($args['filename']) . pascalize($module_type);

	if( !is_file( $module = $module_path . $args['filename'] . '.php' ) ) {
		// model の時、対応する Modelクラスがなければ Modelを呼び出す

		$args['filename'] = 'Model';

		if( $module_type !== 'model' || !is_file( $module = $module_path . $args['filename'] .'.php' ) ) {
			return;
		}

	}

	require_once($module);
	$request_class = $args['namespace'] . $module_type . '\\' . $args['filename'];

	return new $request_class( $render_type );
}



 /**
	* Utls
	*=====================================================*/
function pascalize( $str, $d = '._ -' )
{
	return preg_replace("/[$d]/", '', ucwords(strtolower($str), $d) );
}


function set_option( $key, $value, $add = false )
{
	if( get_option($key) !== false ) {
		update_option($key, $value);
	}
	else if( $add ) {
		add_option($key, $value, null, 'no');
	}
}


function h( $str )
{
	return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}


function _dump($str){
	echo '<pre>';
	var_dump($str);
	echo '</pre>' . PHP_EOL;
}
