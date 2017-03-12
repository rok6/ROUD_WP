<?php
namespace Roud;

class PostController extends Controller
{

  public function __construct()
  {
    $this->post_type = 'post';
    $this->set( $this->post_type );
  }

}
