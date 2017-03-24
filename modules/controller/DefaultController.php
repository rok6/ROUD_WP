<?php
namespace Roud\module\controller;

class DefaultController extends Controller
{

  public function __construct( $render_type )
  {
    $this->set( get_post_type() );
    $this->render( $render_type );
  }

}
