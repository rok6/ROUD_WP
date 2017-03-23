<?php
namespace Roud\module\controller;

abstract class Controller
{
  //abstract public function render();

  protected $post_type = 'post';
  protected $views = array();
  protected $params = array();

  public function __construct()
  {
    $this->params['post_type'] = $this->post_type;
  }

  /**
   * render
   *=====================================================*/
  public function render( $view_type = '' ) {

    $this->set( $this->post_type );

    $filename = ( $view_type !== '' ) ? $this->post_type . '-' . $view_type : $this->post_type;

    if( is_file( $file = ROUD_MDLS_PATH . '/view/' . $filename . '.php' )
          ||
      ( $view_type !== '' && is_file( $file = ROUD_MDLS_PATH . '/view/' . $this->post_type . '.php' ) )
    ) {
      extract($this->views);
      require( $file );
    }
    else {
      exit( 'file does not exist - ' . $this->post_type );
    }

  }

  /**
   * set - post_data
   *=====================================================*/
  protected function set( $name )
  {
    $this->$name = request_module( $name, 'model' );
    $this->views['posts'] = $this->$name->get($this->params);
  }

}
