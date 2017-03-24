<?php
namespace Roud\module\controller;

class NewsController extends Controller
{

  public function __construct( $render_type )
  {
    $this->set( 'news' );
    $this->render( $render_type );
  }

}
