<?php
namespace Roud\module\controller;

abstract class Controller
{
  //abstract public function render();

  protected $post_type = 'post';
  protected $views = array();

  public function __construct()
  {
    //
  }

  protected function set( $name )
  {
    $this->$name = request_module( $name, 'model' );

    $this->views['posts'] = $this->$name->get(array(
      'post_type' => $this->post_type,
    ));
  }

  public function render() {
    extract($this->views);
    require( ROUD_INC_PATH . '/module/view/' . $this->post_type . '.php' );
  }
}
