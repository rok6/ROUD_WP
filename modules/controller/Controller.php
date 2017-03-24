<?php
namespace Roud\module\controller;

class Controller
{
  protected $views = array();
  protected $params = array();

  /**
   * render
   *=====================================================*/
  protected function render( $render_type = '' ) {

    if( $file = $this->valid_views( $render_type ) ) {

      extract($this->views);
      require( $file );

    }

  }

  /**
   * set - post_data
   *=====================================================*/
  protected function set( $name )
  {
    $this->$name = request_module( 'model', false, array(
      'filename'  => $name,
    ));
    $this->params['post_type'] = $name;
    $this->views['post'] = $this->$name->get($this->params);
  }

  private function valid_views( $render_type = '' )
  {
    $module_path = ROUD_MDLS_PATH . '/view/';
    $filename = ( $render_type !== '' ) ?
      $this->params['post_type'] . '-' . $render_type
    : $this->params['post_type'];

    if( is_file( $file = $module_path . $filename . '.php' )
          ||
      ( $render_type !== '' && is_file( $file = $module_path . $this->params['post_type'] . '.php' ) )
          ||
      ( $render_type !== '' && is_file( $file = $module_path . 'default-' . $render_type . '.php' ) )
          ||
      ( is_file( $file = $module_path . 'default' . '.php' ) )
    ) {
      return $file;
    }
    return false;
  }

}
