<?php
namespace Roud;

class NewsController extends Controller
{

  public function __construct()
  {
    $this->post_type = 'news';
    $this->set( $this->post_type );
  }

}
