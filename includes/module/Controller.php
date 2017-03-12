<?php
namespace Roud;

abstract class Controller
{
  //abstract public function render();

  protected $post_type = 'post';
  protected $views = array();

  public function __construct()
  {

  }

  protected function set( $name )
  {
    $this->$name = request_module( $name, 'model', 'nf' );

    $this->views['posts'] = $this->$name->get(array(
      'post_type' => $this->post_type,
    ));
  }

  public function render() {
    extract($this->views);
    require( dirname(__FILE__) . '/views/' . $this->post_type . '.php' );
  }
}
